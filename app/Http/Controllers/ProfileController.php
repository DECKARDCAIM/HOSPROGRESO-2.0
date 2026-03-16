<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class ProfileController extends Controller
{
    /**
     * Mostrar el perfil del usuario
     */
    public function index()
    {
        $user = Auth::user();

        // Obtener solo sesiones activas de nuestro historial
        $sessions = \App\Models\SessionHistory::where('user_id', $user->id)
            ->where('is_active', true)
            ->orderBy('last_active_at', 'desc')
            ->get();

        $activeSessions = $sessions->map(function ($session) {
            $agent = new \Jenssegers\Agent\Agent();
            $agent->setUserAgent($session->user_agent);

            $currentSessionId = request()->session()->getId();

            return (object) [
                'agent' => [
                    'is_desktop' => $agent->isDesktop(),
                    'platform' => $agent->platform(),
                    'browser' => $agent->browser(),
                ],
                'ip_address' => $session->ip_address,
                'is_current_device' => $session->session_id === $currentSessionId,
                'login_at' => $session->login_at->translatedFormat('d M Y, h:i A'),
                'last_active' => $session->last_active_at->diffForHumans(),
                'is_active' => true,
            ];
        });

        // Filtrar aquellas que físicamente ya expiraron en la sesion real de laravel para no engañar a la vista
        $activeSessions = $activeSessions->filter(function ($s) {
            return $s->is_current_device || \Illuminate\Support\Facades\DB::table('sessions')->where('id', \App\Models\SessionHistory::where('ip_address', $s->ip_address)->where('user_id', Auth::id())->value('session_id'))->exists();
        });

        // Miembros del departamento del usuario
        $departamentMembers = \App\Models\User::where('id', '!=', $user->id)
            ->where('is_active', true)
            ->where(function ($query) use ($user) {
                if ($user->unity_execution_id) {
                    $query->where('unity_execution_id', $user->unity_execution_id);
                } elseif ($user->work_department_id) {
                    $query->where('work_department_id', $user->work_department_id);
                } else {
                    $query->whereRaw('1 = 0');
                }
            })
            ->limit(10)
            ->get();

        return view('modules.profile.index', compact('user', 'activeSessions', 'departamentMembers'));
    }

    /**
     * Obtener el historial completo paginado (AJAX)
     */
    public function sessionHistory(Request $request)
    {
        $history = \App\Models\SessionHistory::where('user_id', Auth::id())
            ->orderBy('login_at', 'desc')
            ->paginate(20);

        $currentSessionId = $request->session()->getId();

        $history->getCollection()->transform(function ($session) use ($currentSessionId) {
            $agent = new \Jenssegers\Agent\Agent();
            $agent->setUserAgent($session->user_agent);

            // Verificamos de nuevo si en realidad la sesión "activa" ya caducó físicamente
            $isPhysicallyActive = $session->is_active && ($session->session_id === $currentSessionId || \Illuminate\Support\Facades\DB::table('sessions')->where('id', $session->session_id)->exists());

            return [
                'id' => $session->id,
                'platform' => $agent->platform() ?: 'Desconocido',
                'browser' => $agent->browser() ?: 'Desconocido',
                'is_desktop' => $agent->isDesktop(),
                'ip_address' => $session->ip_address,
                'login_at' => $session->login_at->translatedFormat('d M Y, h:i A'),
                'last_active' => $session->last_active_at->diffForHumans(),
                'is_current_device' => $session->session_id === $currentSessionId,
                'is_active' => $isPhysicallyActive,
            ];
        });

        return response()->json($history);
    }

    /**
     * Cerrar otras sesiones del usuario
     */
    public function destroyOtherSessions(Request $request)
    {
        $request->validate([
            'password' => 'required|current_password',
        ]);

        try {
            $currentSessionId = $request->session()->getId();

            // 1. Eliminar de la base de datos de sesiones de Laravel para cerrar las conexiones
            \Illuminate\Support\Facades\DB::table('sessions')
                ->where('user_id', Auth::id())
                ->where('id', '!=', $currentSessionId)
                ->delete();

            // 2. Marcar como "inactivas" en nuestro historial personalizado
            \App\Models\SessionHistory::where('user_id', Auth::id())
                ->where('session_id', '!=', $currentSessionId)
                ->update(['is_active' => false]);

            return redirect()->route('profile.index')->with('success', 'Las demás sesiones han sido cerradas exitosamente.');
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Error al cerrar otras sesiones: ' . $e->getMessage());
            return redirect()->route('profile.index')->withErrors(['error' => 'Error al cerrar las sesiones: ' . $e->getMessage()]);
        }
    }

    /**
     * Mostrar formulario de edición del perfil
     */
    public function edit()
    {
        $user = Auth::user();
        return view('modules.profile.edit', compact('user'));
    }

    /**
     * Actualizar el perfil del usuario (o contraseña, si se está enviando desde el formulario de seguridad)
     */
    public function update(Request $request)
    {
        $user = Auth::user();

        // 1. Manejo exclusivo de cambio de contraseña
        if ($request->has('update_password_only')) {
            $request->validate([
                'current_password' => 'required|current_password',
                'password' => 'required|min:8|confirmed',
            ]);

            try {
                DB::table('users')->where('id', $user->id)->update([
                    'password' => \Illuminate\Support\Facades\Hash::make($request->password)
                ]);

                return redirect()->route('profile.index')->with('success', 'Contraseña actualizada correctamente');
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::error('Error al actualizar contraseña: ' . $e->getMessage());
                return redirect()->back()->withErrors(['error' => 'Error al actualizar contraseña: ' . $e->getMessage()])->withInput();
            }
        }

        // 2. Manejo de información demográfica normal
        $request->validate([
            'first_name' => 'required|string|max:255',
            'second_name' => 'nullable|string|max:255',
            'third_name' => 'nullable|string|max:255',
            'first_last_name' => 'required|string|max:255',
            'second_last_name' => 'nullable|string|max:255',
            'married_last_name' => 'nullable|string|max:255',

            // Correo no se actualiza desde aquí, se omite.
            'cui' => 'nullable|string|max:13|unique:users,cui,' . Auth::id(),
            'nit' => 'nullable|string|max:9|unique:users,nit,' . Auth::id(),

            'marital_status' => 'nullable|string|in:soltero,casado,divorciado,viudo,union_libre',
            'phone' => 'nullable|string|max:15', // phone is string
            'address' => 'nullable|string|max:500', // max 500 para dirección
            'birth_date' => 'nullable|date',
            'gender' => 'nullable|string|in:masculino,femenino',
            
            // Imágenes
            'profile_photo' => 'nullable|image|mimes:jpeg,png,jpg|max:4096',
            'banner_photo' => 'nullable|image|mimes:jpeg,png,jpg|max:4096',
        ]);

        try {
            $updateData = [
                'first_name' => $request->first_name,
                'second_name' => $request->second_name,
                'third_name' => $request->third_name,
                'first_last_name' => $request->first_last_name,
                'second_last_name' => $request->second_last_name,
                'married_last_name' => $request->married_last_name,
                'cui' => $request->cui,
                'nit' => $request->nit,
                'marital_status' => $request->marital_status,
                'phone' => $request->phone,
                'address' => $request->address,
                'birth_date' => $request->birth_date,
                'gender' => $request->gender,
            ];

            // Subir Avatar
            if ($request->hasFile('profile_photo')) {
                if ($user->profile_photo_path) {
                    $oldPublicFile = public_path('storage/' . $user->profile_photo_path);
                    if (\Illuminate\Support\Facades\File::exists($oldPublicFile)) {
                        try {
                            \Illuminate\Support\Facades\File::delete($oldPublicFile);
                        } catch (\Exception $e) {} // Ignorar si está bloqueado por otro proceso
                    }
                }

                $avatarFile = $request->file('profile_photo');
                $avatarName = \Illuminate\Support\Str::random(40) . '.' . $avatarFile->getClientOriginalExtension();
                $avatarPath = 'profile_photos/' . $avatarName;
                
                $avatarFile->move(public_path('storage/profile_photos'), $avatarName);
                $updateData['profile_photo_path'] = $avatarPath;
            }

            // Subir Banner
            if ($request->hasFile('banner_photo')) {
                if ($user->banner_photo_path) {
                    $oldPublicFile = public_path('storage/' . $user->banner_photo_path);
                    if (\Illuminate\Support\Facades\File::exists($oldPublicFile)) {
                        try {
                            \Illuminate\Support\Facades\File::delete($oldPublicFile);
                        } catch (\Exception $e) {} // Ignorar si está bloqueado por otro proceso
                    }
                }

                $bannerFile = $request->file('banner_photo');
                $bannerName = \Illuminate\Support\Str::random(40) . '.' . $bannerFile->getClientOriginalExtension();
                $bannerPath = 'banner_photos/' . $bannerName;
                
                $bannerFile->move(public_path('storage/banner_photos'), $bannerName);
                $updateData['banner_photo_path'] = $bannerPath;
            }

            DB::table('users')->where('id', $user->id)->update($updateData);
            
            $user->refresh();

            // Si es una petición AJAX, devolver JSON
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Perfil actualizado correctamente'
                ]);
            }

            // Si no es AJAX, redirigir
            return redirect()->route('profile.index')->with('success', 'Perfil actualizado correctamente');
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Error al actualizar perfil: ' . $e->getMessage());
            
            // Si es una petición AJAX, devolver JSON
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error al actualizar el perfil: ' . $e->getMessage()
                ], 500);
            }

            // Si no es AJAX, redirigir con error
            return redirect()->back()->withErrors(['error' => 'Error al actualizar el perfil: ' . $e->getMessage()])->withInput();
        }
    }

    /**
     * Actualizar el avatar del usuario
     */
    public function updateAvatar(Request $request)
    {
        // Validar que haya un archivo (puede ser 'avatar' o 'profile_photo')
        $file = $request->hasFile('avatar') ? $request->file('avatar') : $request->file('profile_photo');
        
        if (!$file) {
            return response()->json([
                'success' => false,
                'message' => 'No se proporcionó ningún archivo'
            ], 422);
        }

        $request->validate([
            'profile_photo_path' => 'nullable|image|mimes:jpeg,png,jpg'
        ]);

        try {
            $user = Auth::user();
            
            // Eliminar avatar anterior si existe
            if ($user->profile_photo_path) {
                // Eliminar de storage
                if (Storage::disk('public')->exists($user->profile_photo_path)) {
                    Storage::disk('public')->delete($user->profile_photo_path);
                }
                // Eliminar de public/storage
                $oldFileName = basename($user->profile_photo_path);
                $oldPublicFile = public_path('storage/avatars/' . $oldFileName);
                if (File::exists($oldPublicFile)) {
                    File::delete($oldPublicFile);
                }
            }

            // Guardar nuevo avatar en storage
            $profilePhotoPath = $file->store('profile_photos', 'public');
            
            // Copiar también a public/storage para acceso directo
            $publicStoragePath = public_path('storage');
            if (!File::exists($publicStoragePath)) {
                File::makeDirectory($publicStoragePath, 0755, true);
            }
            $publicProfilePhotosPath = $publicStoragePath . '/profile_photos';
            if (!File::exists($publicAvatarsPath)) {
                File::makeDirectory($publicAvatarsPath, 0755, true);
            }
            
            $fileName = basename($avatarPath);
            $sourceFile = storage_path('app/public/' . $avatarPath);
            $destinationFile = $publicAvatarsPath . '/' . $fileName;
            
            if (File::exists($sourceFile)) {
                File::copy($sourceFile, $destinationFile);
            }
            
            // Generar URL usando asset() para que funcione tanto en desktop como móvil
            $avatarUrl = asset('storage/avatars/' . $fileName);
            
            // Actualizar en la base de datos (ruta y URL)
            DB::table('users')
                ->where('id', $user->id)
                ->update([
                    'avatar' => $avatarPath,
                    'avatar_url' => $avatarUrl
                ]);
            
            $user->refresh();

            return response()->json([
                'success' => true,
                'message' => 'Avatar actualizado correctamente',
                'avatar_url' => $avatarUrl
            ]);
        } catch (\Exception $e) {
            \Log::error('Error al actualizar avatar: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar el avatar: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Actualizar el banner del usuario
     */
    public function updateBanner(Request $request)
    {
        // Validar que haya un archivo (puede ser 'banner' o 'banner_photo')
        $file = $request->hasFile('banner_photo_path') ? $request->file('banner_photo_path') : $request->file('banner_photo_path');
        
        if (!$file) {
            return response()->json([
                'success' => false,
                'message' => 'No se proporcionó ningún archivo'
            ], 422);
        }

        $request->validate([
            'banner_photo_path' => 'nullable|image|mimes:jpeg,png,jpg'
        ]);

        try {
            $user = Auth::user();
            
            // Eliminar banner anterior si existe
            if ($user->banner_photo_path) {
                // Eliminar de storage
                if (Storage::disk('public')->exists($user->banner_photo_path)) {
                    Storage::disk('public')->delete($user->banner_photo_path);
                }
                // Eliminar de public/storage
                $oldFileName = basename($user->banner);
                $oldPublicFile = public_path('storage/banners/' . $oldFileName);
                if (File::exists($oldPublicFile)) {
                    File::delete($oldPublicFile);
                }
            }

            // Guardar nuevo banner en storage
            $bannerPhotoPath = $file->store('banner_photos', 'public');
            
            // Copiar también a public/storage para acceso directo
            $publicStoragePath = public_path('storage');
            if (!File::exists($publicStoragePath)) {
                File::makeDirectory($publicStoragePath, 0755, true);
            }
            $publicBannersPath = $publicStoragePath . '/banners';
            if (!File::exists($publicBannersPath)) {
                File::makeDirectory($publicBannersPath, 0755, true);
            }
            
            $fileName = basename($bannerPath);
            $sourceFile = storage_path('app/public/' . $bannerPath);
            $destinationFile = $publicBannersPath . '/' . $fileName;
            
            if (File::exists($sourceFile)) {
                File::copy($sourceFile, $destinationFile);
            }
            
            // Generar URL usando asset() para que funcione tanto en desktop como móvil
            $bannerUrl = asset('storage/banners/' . $fileName);
            
            // Actualizar en la base de datos (ruta y URL)
            DB::table('users')
                ->where('id', $user->id)
                ->update([
                    'banner' => $bannerPath,
                    'banner_url' => $bannerUrl
                ]);
            
            $user->refresh();

            return response()->json([
                'success' => true,
                'message' => 'Banner actualizado correctamente',
                'banner_url' => $bannerUrl
            ]);
        } catch (\Exception $e) {
            \Log::error('Error al actualizar banner: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar el banner: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Eliminar el avatar del usuario
     */
    public function deleteAvatar(Request $request)
    {
        try {
            $user = Auth::user();
            
            if ($user->profile_photo_path) {
                // Eliminar de storage
                if (Storage::disk('public')->exists($user->profile_photo_path)) {
                    Storage::disk('public')->delete($user->profile_photo_path);
                }
                // Eliminar de public/storage
                $oldFileName = basename($user->profile_photo_path);
                $oldPublicFile = public_path('storage/avatars/' . $oldFileName);
                if (File::exists($oldPublicFile)) {
                    File::delete($oldPublicFile);
                }
            }
            
            // Limpiar en la base de datos
            DB::table('users')
                ->where('id', $user->id)
                ->update([
                    'profile_photo_path' => null
                ]);
            
            $user->refresh();

            return response()->json([
                'success' => true,
                'message' => 'Foto de perfil eliminada correctamente'
            ]);
        } catch (\Exception $e) {
            \Log::error('Error al eliminar avatar: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error al eliminar el avatar: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Eliminar el banner del usuario
     */
    public function deleteBanner(Request $request)
    {
        try {
            $user = Auth::user();
            
            if ($user->banner_photo_path) {
                // Eliminar de storage
                if (Storage::disk('public')->exists($user->banner_photo_path)) {
                    Storage::disk('public')->delete($user->banner_photo_path);
                }
                // Eliminar de public/storage
                $oldFileName = basename($user->banner);
                $oldPublicFile = public_path('storage/banners/' . $oldFileName);
                if (File::exists($oldPublicFile)) {
                    File::delete($oldPublicFile);
                }
            }
            
            // Limpiar en la base de datos
            DB::table('users')
                ->where('id', $user->id)
                ->update([
                    'banner_photo_path' => null
                ]);
            
            $user->refresh();

            return response()->json([
                'success' => true,
                'message' => 'Banner eliminado correctamente'
            ]);
        } catch (\Exception $e) {
            \Log::error('Error al eliminar banner: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error al eliminar el banner: ' . $e->getMessage()
            ], 500);
        }
    }
}

