<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateProfileRequest;
use App\Models\Gender;
use App\Models\SessionHistory;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Jenssegers\Agent\Agent;

class ProfileController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $sessions = SessionHistory::where('user_id', $user->id)
            ->where('is_active', true)
            ->orderBy('last_active_at', 'desc')
            ->get();

        $sessionHandler = Session::getHandler();
        $currentSessionId = request()->session()->getId();

        $activeSessions = $sessions->map(function ($session) use ($currentSessionId, $sessionHandler) {
            $agent = new Agent;
            $agent->setUserAgent($session->user_agent);

            $sessionExists = $session->session_id === $currentSessionId || $sessionHandler->read($session->session_id) !== '';

            return (object) [
                'agent' => [
                    'is_desktop' => $agent->isDesktop(),
                    'platform' => $agent->platform(),
                    'browser' => $agent->browser(),
                ],
                'session_id' => $session->session_id,
                'ip_address' => $session->ip_address,
                'is_current_device' => $session->session_id === $currentSessionId,
                'login_at' => $session->login_at->translatedFormat('d M Y, h:i A'),
                'last_active' => $session->last_active_at->diffForHumans(),
                'is_active' => $sessionExists,
            ];
        })->filter(fn ($s) => $s->is_active);

        $departamentMembers = User::where('id', '!=', $user->id)
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

        return view('modules.administration.user.profile.index', compact('user', 'activeSessions', 'departamentMembers'));
    }

    public function sessionHistory(Request $request)
    {
        $history = SessionHistory::where('user_id', Auth::id())
            ->orderBy('login_at', 'desc')
            ->paginate(20);

        $currentSessionId = $request->session()->getId();
        $sessionHandler = Session::getHandler();

        $history->getCollection()->transform(function ($session) use ($currentSessionId, $sessionHandler) {
            $agent = new Agent;
            $agent->setUserAgent($session->user_agent);

            $isPhysicallyActive = $session->is_active && ($session->session_id === $currentSessionId || $sessionHandler->read($session->session_id) !== '');

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

    public function destroyOtherSessions(Request $request)
    {
        $request->validate(['password' => 'required|current_password']);

        try {
            Auth::logoutOtherDevices($request->password);

            $currentSessionId = $request->session()->getId();

            SessionHistory::where('user_id', Auth::id())
                ->where('session_id', '!=', $currentSessionId)
                ->update(['is_active' => false]);

            return redirect()->route('profile.index')->with('success', 'Las demás sesiones han sido cerradas exitosamente.');
        } catch (\Exception $e) {
            Log::error('Error al cerrar otras sesiones: '.$e->getMessage());

            return redirect()->route('profile.index')->withErrors(['error' => 'Error al cerrar las sesiones.']);
        }
    }

    public function edit()
    {
        $user = Auth::user();
        $genders = Cache::tags(['genders'])->remember('active_genders', now()->addDays(1), fn () => Gender::where('is_active', true)->orderBy('name')->get());

        return view('modules.administration.user.profile.edit', compact('user', 'genders'));
    }

    public function update(UpdateProfileRequest $request)
    {
        $user = Auth::user();

        if ($request->has('update_password_only')) {
            try {
                $user->update(['password' => Hash::make($request->password)]);

                return redirect()->route('profile.index')->with('success', 'Contraseña actualizada correctamente');
            } catch (\Exception $e) {
                Log::error('Error al actualizar contraseña: '.$e->getMessage());

                return redirect()->back()->withErrors(['error' => 'Error al actualizar contraseña.'])->withInput();
            }
        }

        try {
            $updateData = $request->except(['profile_photo', 'banner_photo', 'update_password_only']);

            if ($request->hasFile('profile_photo')) {
                $this->deleteOldFile($user->profile_photo_path);

                $file = $request->file('profile_photo');
                $name = Str::random(40).'.'.$file->getClientOriginalExtension();
                $file->move(public_path('storage/profile_photos'), $name);
                $updateData['profile_photo_path'] = 'profile_photos/'.$name;
            }

            if ($request->hasFile('banner_photo')) {
                $this->deleteOldFile($user->banner_photo_path);

                $file = $request->file('banner_photo');
                $name = Str::random(40).'.'.$file->getClientOriginalExtension();
                $file->move(public_path('storage/banner_photos'), $name);
                $updateData['banner_photo_path'] = 'banner_photos/'.$name;
            }

            $user->update($updateData);

            if ($request->wantsJson()) {
                return response()->json(['success' => true, 'message' => 'Perfil actualizado correctamente']);
            }

            return redirect()->route('profile.index')->with('success', 'Perfil actualizado correctamente');
        } catch (\Exception $e) {
            Log::error('Error al actualizar perfil: '.$e->getMessage());

            if ($request->wantsJson()) {
                return response()->json(['success' => false, 'message' => 'Error al actualizar el perfil.'], 500);
            }

            return redirect()->back()->withErrors(['error' => 'Error al actualizar el perfil.'])->withInput();
        }
    }

    public function updateAvatar(Request $request)
    {
        $file = $request->file('avatar') ?? $request->file('profile_photo');

        if (! $file) {
            return response()->json(['success' => false, 'message' => 'No se proporcionó ningún archivo'], 422);
        }

        try {
            $user = Auth::user();
            $this->deleteOldFile($user->profile_photo_path);

            $name = Str::random(40).'.'.$file->getClientOriginalExtension();
            $path = 'profile_photos/'.$name;
            $file->move(public_path('storage/profile_photos'), $name);

            $user->update(['profile_photo_path' => $path]);

            return response()->json([
                'success' => true,
                'message' => 'Avatar actualizado correctamente',
                'avatar_url' => $user->avatar_url,
            ]);
        } catch (\Exception $e) {
            Log::error('Error al actualizar avatar: '.$e->getMessage());

            return response()->json(['success' => false, 'message' => 'Error al actualizar el avatar.'], 500);
        }
    }

    public function updateBanner(Request $request)
    {
        $file = $request->file('banner') ?? $request->file('banner_photo');

        if (! $file) {
            return response()->json(['success' => false, 'message' => 'No se proporcionó ningún archivo'], 422);
        }

        try {
            $user = Auth::user();
            $this->deleteOldFile($user->banner_photo_path);

            $name = Str::random(40).'.'.$file->getClientOriginalExtension();
            $path = 'banner_photos/'.$name;
            $file->move(public_path('storage/banner_photos'), $name);

            $user->update(['banner_photo_path' => $path]);

            return response()->json([
                'success' => true,
                'message' => 'Banner actualizado correctamente',
                'banner_url' => $user->banner_url,
            ]);
        } catch (\Exception $e) {
            Log::error('Error al actualizar banner: '.$e->getMessage());

            return response()->json(['success' => false, 'message' => 'Error al actualizar el banner.'], 500);
        }
    }

    public function deleteAvatar(Request $request)
    {
        try {
            $user = Auth::user();
            $this->deleteOldFile($user->profile_photo_path);

            $user->update(['profile_photo_path' => null]);

            return response()->json(['success' => true, 'message' => 'Foto de perfil eliminada correctamente']);
        } catch (\Exception $e) {
            Log::error('Error al eliminar avatar: '.$e->getMessage());

            return response()->json(['success' => false, 'message' => 'Error al eliminar el avatar.'], 500);
        }
    }

    public function deleteBanner(Request $request)
    {
        try {
            $user = Auth::user();
            $this->deleteOldFile($user->banner_photo_path);

            $user->update(['banner_photo_path' => null]);

            return response()->json(['success' => true, 'message' => 'Banner eliminado correctamente']);
        } catch (\Exception $e) {
            Log::error('Error al eliminar banner: '.$e->getMessage());

            return response()->json(['success' => false, 'message' => 'Error al eliminar el banner.'], 500);
        }
    }

    private function deleteOldFile($path)
    {
        if ($path) {
            $publicPath = public_path('storage/'.$path);
            if (File::exists($publicPath)) {
                try {
                    File::delete($publicPath);
                } catch (\Exception $e) {
                }
            }
        }
    }
}
