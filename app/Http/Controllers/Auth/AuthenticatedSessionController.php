<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest; // Se usa este Request para la autenticación
use Illuminate\Http\RedirectResponse; // Necesario para el tipo de retorno
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // Necesario para Auth::guard
use Illuminate\View\View;
use Illuminate\Support\Facades\Redirect; // ¡Añade esta importación para usar Redirect::intended!
use Illuminate\Support\Facades\Route; // ¡Añade esta importación para usar route()!


class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate(); // Este método de LoginRequest ya autentica al usuario

        $request->session()->regenerate();

        // ¡ESTA ES LA LÍNEA CLAVE QUE MODIFICAMOS!
        // Redirigimos explícitamente a books.index
        return Redirect::intended(route('books.index'));
        // También puedes usar return redirect()->intended(route('books.index'));
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}