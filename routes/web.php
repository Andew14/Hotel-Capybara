<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\HuespedController;
use App\Http\Controllers\ReservaController;
use App\Http\Controllers\FacturaController;
use App\Http\Controllers\ServicioController;
use App\Http\Controllers\HabitacionController;
use App\Http\Controllers\CategoriaHabitacionController;
use App\Http\Controllers\ConsumoServicioController;
use App\Http\Controllers\RolController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

// Rutas protegidas generales (Acceso para todos los roles logueados)
Route::middleware(['web', 'prevent-back-history'])->group(function () {
    
    // Huéspedes
    Route::resource('huespedes', HuespedController::class);

    // Reservas
    Route::resource('reservas', ReservaController::class);

    // Facturas
    Route::resource('facturas', FacturaController::class);

    // Servicios (Solo lectura para recepcionista)
    Route::resource('servicios', ServicioController::class)->only(['index']);

    // Habitaciones (Acceso compartido: Recepcionista puede ver/editar, Admin todo)
    // El controlador backend ya maneja la autorización fina, pero aquí permitimos acceso a la ruta.
    Route::resource('habitaciones', HabitacionController::class);
});

// Rutas protegidas SOLO ADMINISTRADOR
Route::middleware(['web', 'admin', 'prevent-back-history'])->group(function () {
    // Usuarios
    Route::resource('usuarios', UsuarioController::class);

    // Categorías de Habitación
    Route::resource('categorias', CategoriaHabitacionController::class);

    // Consumos de Servicios
    Route::resource('consumos', ConsumoServicioController::class);

    // Roles
    Route::resource('roles', RolController::class);

    // Servicios (CRUD completo para admin)
    Route::resource('servicios', ServicioController::class)->except(['index']);
});
