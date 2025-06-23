<?php
use App\Http\Controllers\Auth\SocialiteController; // ¡Añade esta línea!
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BookController; // ¡AÑADE ESTA LÍNEA!

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// --- RUTAS PÚBLICAS (Sin autenticación) ---

// Ruta raíz (página de bienvenida de Laravel)
Route::get('/', function () {
    return view('welcome');
});

// --- RUTAS PROTEGIDAS (Requieren autenticación) ---

// Este es el grupo principal de rutas protegidas por autenticación.
// Laravel Breeze ya incluye sus rutas de dashboard, perfil y logout aquí.
Route::middleware('auth')->group(function () {
    // Ruta del Dashboard (ya viene con Breeze y requiere 'verified' si está configurado)
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->middleware(['verified'])->name('dashboard'); // 'verified' se añadió aquí para ser explícito.

    // Rutas del perfil de usuario (ya vienen con Breeze)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Ruta para cerrar sesión (logout)
    // Breeze ya usa ProfileController@destroy para esto. No es necesario duplicarla si ya está en auth.php.
    Route::post('/logout', [ProfileController::class, 'destroy'])->name('logout');


    // --- Tus Rutas de Recurso para Libros (ABM) ---
    // ESTA ES LA LÍNEA QUE FALTABA
    Route::resource('books', BookController::class);
});


// Rutas de Autenticación de Google con Socialite
Route::prefix('auth')->group(function () {
    Route::get('/google', [SocialiteController::class, 'redirectToGoogle'])->name('auth.google');
    Route::get('/google/callback', [SocialiteController::class, 'handleGoogleCallback']);
});

Route::middleware('auth')->group(function () {
    // ... tus rutas protegidas como /dashboard, /books, /profile ...
});

// --- RUTAS DE AUTENTICACIÓN DE BREEZE ---
// Este archivo contiene las rutas para login, register, password reset, etc.
// Es crucial que esta línea esté al final para que las rutas de Breeze se carguen correctamente.
require __DIR__.'/auth.php';