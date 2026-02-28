<?php

use App\Http\Controllers\Auth\RegisteredUserController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\indexController;
use App\Http\Controllers\inicioController;
use App\Http\Controllers\ImageController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\PinDetailController;
use App\Http\Controllers\PinController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\PinReportController;
use App\Http\Controllers\PublicPinsController;
use App\Http\Controllers\UserProfileController;
use App\Http\Controllers\FollowController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\ProfileController;

// Rutas para manejo de imágenes
Route::middleware(['auth'])->group(function () {
    Route::post('/images', [ImageController::class, 'store'])->name('images.store');
    Route::get('/images/{image}', [ImageController::class, 'show'])->name('images.show');
    Route::delete('/images/{image}', [ImageController::class, 'destroy'])->name('images.destroy');
    Route::get('/ideas/{idea}/images', [ImageController::class, 'getByIdea'])->name('ideas.images');
});



Route::get('/test-mongo', function () {
    try {
        $dbs = DB::connection('mongodb')->getMongoClient()->listDatabases();
        return '✅ Conectado a MongoDB';
    } catch (\Exception $e) {
        return '❌ Error: ' . $e->getMessage();
    }
});

Route::get('/test', function () {
    return response()->json(['message' => 'API funcionando']);
});

Route::get('/homefeed', [indexController::class, 'index']);

Route::get('/', [inicioController::class, 'inicio']);
Route::get('/dashboard', fn () => redirect()->route('inicioLogueado'))
    ->middleware(['auth'])
    ->name('dashboard');

Route::middleware(['auth'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('/inicio', [inicioController::class, 'inicio'])->name('inicio');

Route::get('/Información', [inicioController::class, 'Información'])->name('Información');

Route::get('/empresa', [inicioController::class, 'empresa'])->name('empresa');

Route::get('/Create', [inicioController::class, 'Create'])->name('Create');

Route::get('/News', [inicioController::class, 'News'])->name('News');

// Vista pública de pines (solo ver/descargar)
Route::get('/explorar-publico', [PublicPinsController::class, 'index'])->name('public.pins');

// Perfiles públicos
Route::get('/usuarios/{user}', [UserProfileController::class, 'show'])->name('users.profile.show');
Route::middleware(['auth'])->put('/usuarios/{user}', [UserProfileController::class, 'update'])->name('users.profile.update');

Route::get('/Login', [inicioController::class, 'Login'])->name('Login');

Route::get('/Condiciones', [inicioController::class, 'Condiciones'])->name('Condiciones');

Route::get('/PoliticasPrivacidad', [inicioController::class, 'PoliticasPrivacidad'])->name('PoliticasPrivacidad');

Route::get('/Comunidad', [inicioController::class, 'Comunidad'])->name('Comunidad');

Route::get('/propiedadIntelectual', [inicioController::class, 'propiedadIntelectual'])->name('propiedadIntelectual');

Route::get('/marcaComercial', [inicioController::class, 'marcaComercial'])->name('marcaComercial');

Route::get('/Transparencia', [inicioController::class, 'Transparencia'])->name('Transparencia');

Route::get('/Mas', [inicioController::class, 'Mas'])->name('Mas');

Route::get('/Ayuda', [inicioController::class, 'Ayuda'])->name('Ayuda');

Route::get('/AvisosnoUsuario', [inicioController::class, 'AvisosnoUsuarios'])->name('AvisosnoUsuario');
Route::get('/Liderazgo', [inicioController::class, 'Liderazgo'])->name('Liderazgo');
Route::get('/inicioLogueado', [inicioController::class, 'inicioLogueado'])
    ->middleware(['auth'])
    ->name('inicioLogueado');
Route::get('/buscar-pines', [inicioController::class, 'buscarPins'])
    ->middleware(['auth'])
    ->name('buscar.pins');

require __DIR__.'/auth.php';

Route::get('/buscaIdea', [inicioController::class, 'buscaIdea'])->name('buscaIdea');
Route::get('/guardaIdeas', [inicioController::class, 'guardaIdeas'])->name('guardaIdeas');
Route::get('/crealo', [inicioController::class, 'crealo'])->name('crealo');
// duplicado removido; la ruta ya está definida arriba con middleware('auth')
Route::get('/creacionPines', [inicioController::class, 'creacionPines'])
    ->middleware(['auth'])
    ->name('creacionPines');

Route::post('/creacionPines', [inicioController::class, 'storePin'])
    ->middleware(['auth'])
    ->name('pins.store');

Route::get('/creacionPinesMultiple', [inicioController::class, 'creacionPinesMultiple'])
    ->middleware(['auth'])
    ->name('creacionPinesMultiple');

Route::post('/creacionPinesMultiple', [inicioController::class, 'storeMultiplePins'])
    ->middleware(['auth'])
    ->name('pins.storeMultiple');

// Rutas para comentarios
Route::middleware(['auth'])->group(function () {
    Route::post('/pins/{pin}/comments', [CommentController::class, 'store'])->name('comments.store');
    Route::delete('/comments/{comment}', [CommentController::class, 'destroy'])->name('comments.destroy');
});

// Rutas para reportes de pins
Route::middleware(['auth'])->group(function () {
    Route::post('/pins/{pin}/report', [PinReportController::class, 'store'])->name('pins.report');
});

// Rutas para estadísticas de pines
Route::middleware(['auth'])->group(function () {
    Route::get('/pin-stats', [App\Http\Controllers\PinStatsController::class, 'index'])->name('pin-stats');
});

// Rutas para seguir usuarios
Route::middleware(['auth'])->group(function () {
    Route::post('/users/{user}/follow', [FollowController::class, 'store'])->name('users.follow');
    Route::delete('/users/{user}/follow', [FollowController::class, 'destroy'])->name('users.unfollow');
    Route::get('/followers', [FollowController::class, 'followers'])->name('users.followers');

    // Mensajes privados (solo seguidores mutuos)
    Route::get('/messages/threads', [MessageController::class, 'threads'])->name('messages.threads');
    Route::get('/messages/{user}', [MessageController::class, 'index'])->name('messages.index');
    Route::post('/messages/{user}', [MessageController::class, 'store'])->name('messages.store');
});

// Rutas para likes
Route::middleware(['auth'])->group(function () {
    Route::post('/pins/{pin}/like', [LikeController::class, 'store'])->name('likes.store');
    Route::delete('/pins/{pin}/like', [LikeController::class, 'destroy'])->name('likes.destroy');
});

// Rutas para notificaciones
Route::middleware(['auth'])->group(function () {
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/{notification}/read', [NotificationController::class, 'markAsRead'])->name('notifications.read');
    Route::post('/notifications/mark-all-read', [NotificationController::class, 'markAllAsRead'])->name('notifications.markAllRead');
    Route::delete('/notifications/{notification}', [NotificationController::class, 'destroy'])->name('notifications.destroy');
});

// Rutas para obtener detalles de pins (JSON)
Route::get('/pins/{pin}/detail', [PinDetailController::class, 'show'])->name('pins.detail');

// Rutas para editar y eliminar pins
Route::middleware(['auth'])->group(function () {
    Route::get('/pins/{pin}/edit', [PinController::class, 'edit'])->name('pins.edit');
    Route::put('/pins/{pin}', [PinController::class, 'update'])->name('pins.update');
    Route::delete('/pins/{pin}', [PinController::class, 'destroy'])->name('pins.destroy');
});

// Rutas de administrador
Route::middleware(['auth'])->prefix('admin')->group(function () {
    Route::get('/', [App\Http\Controllers\AdminController::class, 'index'])->name('admin.dashboard');
    Route::delete('/users/{id}', [App\Http\Controllers\AdminController::class, 'deleteUser'])->name('admin.deleteUser');
    Route::delete('/pins/{id}', [App\Http\Controllers\AdminController::class, 'deletePin'])->name('admin.deletePin');
    Route::post('/users/{id}/reset-password', [App\Http\Controllers\AdminController::class, 'resetUserPassword'])->name('admin.resetUserPassword');
});
