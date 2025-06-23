<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User; // Importa el modelo User
use Illuminate\Support\Facades\Auth; // Importa la Facade Auth
use Laravel\Socialite\Facades\Socialite; // Importa la Facade Socialite
use Illuminate\Support\Facades\Hash; // Para generar contraseñas seguras
use Illuminate\Support\Str; // Para cadenas aleatorias
use Illuminate\Support\Facades\Redirect; // Importa Redirect para redirección
use Illuminate\Http\Request; // Importa Request para el logout

class SocialiteController extends Controller
{
    /**
     * Redirige al usuario a la página de autenticación de Google.
     */
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    /**
     * Maneja el callback de autenticación de Google.
     */
    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();

            // Busca al usuario por email
            $user = User::where('email', $googleUser->getEmail())->first();

            if ($user) {
                // Si el usuario existe
                if ($user->google_id === null) {
                    // Si el usuario ya existe pero no tiene Google ID, lo vinculamos
                    $user->google_id = $googleUser->getId();
                    $user->save();
                }
                Auth::login($user); // Inicia sesión
            } else {
                // Si el usuario no existe, crea una nueva cuenta
                $newUser = User::create([
                    'name' => $googleUser->getName(),
                    'email' => $googleUser->getEmail(),
                    'google_id' => $googleUser->getId(),
                    'password' => Hash::make(Str::random(24)), // Genera una contraseña aleatoria segura
                    'email_verified_at' => now(), // Marca el email como verificado
                ]);

                Auth::login($newUser); // Inicia sesión al nuevo usuario
            }

            // Redirección a la página de libros después del login con Google.
            // Usamos redirect()->to() en lugar de intended() porque este es un flujo de autenticación externo.
            return Redirect::to(route('books.index'));

        } catch (\Exception $e) {
            // Manejo de errores (en desarrollo, puedes ver el mensaje con dd($e->getMessage());)
            // dd($e->getMessage());
            return redirect('/login')->with('error', 'Hubo un error al iniciar sesión con Google. Por favor, inténtalo de nuevo.');
        }
    }

    /**
     * Cierra la sesión del usuario.
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}