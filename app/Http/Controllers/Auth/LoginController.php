<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    protected $redirectTo = '/home';

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->middleware('auth')->only('logout');
    }

    protected function authenticated(Request $request, $user)
    {
        if (! $user->is_active || ! $user->role_id || ! ($user->staff && $user->staff->work_department_id)) {
            auth()->logout();

            return redirect()->route('login')->withErrors([
                'access_denied' => 'Su cuenta no está completada o se encuentra inactiva (Falta rol o departamento). Por favor, contacte con el departamento de informática para soporte técnico.',
            ]);
        }

        $user->update(['estado' => 'disponible']);
    }

    public function logout(Request $request)
    {
        $user = auth()->user();
        if ($user) {
            $user->update(['estado' => 'desconectado']);
        }

        $this->guard()->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        if ($response = $this->loggedOut($request)) {
            return $response;
        }

        return $request->wantsJson()
            ? new JsonResponse([], 204)
            : redirect('/');
    }
}
