<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Organizer\EventController;
use App\Http\Controllers\EventRegistrationController;
use App\Http\Controllers\Organizer\DashboardController as OrganizerDashboardController;
use App\Http\Controllers\Participant\DashboardController as ParticipantDashboardController;
use App\Http\Controllers\Participant\EventDirectoryController;
use App\Http\Controllers\Participant\MeetingController;
use App\Http\Controllers\Participant\MeetingSurveyController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('welcome');
});

// --- ENRUTADOR PRINCIPAL DE DASHBOARD ---
// Esta ruta decide qué panel mostrar basado en el rol
Route::get('/dashboard', function () {
    $role = auth()->user()->role;

    if ($role === 'organizer') {
        return app(OrganizerDashboardController::class)->index();
    } elseif ($role === 'participant') {
        return app(ParticipantDashboardController::class)->index();
    }

    // Fallback para la vista genérica (que no deberíamos usar)
    return view('dashboard');

})->middleware(['auth', 'verified'])->name('dashboard');

// --- RUTAS DE PERFIL DE BREEZE ---
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// =========================================================================
// --- GRUPO DE RUTAS DEL ORGANIZADOR ---
// =========================================================================
Route::middleware(['auth', 'role:organizer'])->prefix('organizer')->name('organizer.')->group(function () {

    // NOTA: Eliminamos el '/organizer/dashboard' duplicado.
    // El '/dashboard' principal ya maneja esto.

    Route::resource('events', EventController::class)->names('events');

    Route::patch('events/{event}/update-status', [EventController::class, 'updateStatus'])
        ->name('events.updateStatus');
});

// =========================================================================
// --- GRUPO DE RUTAS DEL PARTICIPANTE ---
// =========================================================================
Route::middleware(['auth', 'role:participant'])->prefix('participant')->name('participant.')->group(function () {

    // Ver el directorio de un evento
    Route::get('event/{event}/directory', [EventDirectoryController::class, 'index'])
        ->middleware('event.status:SchedulingActive')
        ->name('event.directory');

    // --- RUTAS DE REUNIONES (AHORA PROTEGIDAS) ---

    // VER la lista de solicitudes RECIBIDAS
    Route::get('meetings', [MeetingController::class, 'index'])
        ->name('meetings.index');

    // PROCESAR una solicitud de reunión
    Route::post('meeting/request', [MeetingController::class, 'store'])
        ->name('meeting.store');

    // ACEPTAR una solicitud
    Route::patch('meetings/{meeting}/confirm', [MeetingController::class, 'confirm'])
        ->name('meetings.confirm');

    // RECHAZAR una solicitud
    Route::patch('meetings/{meeting}/reject', [MeetingController::class, 'reject'])
        ->name('meetings.reject');

        // --- RUTAS DE GESTIÓN DE AGENDA (AÑADIR ESTAS) ---

// 1. Ruta para VER la agenda del día
Route::get('my-agenda', [MeetingController::class, 'myAgenda'])
    ->name('meetings.myAgenda');

// 2. Ruta para "Iniciar" una reunión
Route::patch('meetings/{meeting}/start', [MeetingController::class, 'start'])
    ->name('meetings.start');

// 3. Ruta para "Completar" una reunión
Route::patch('meetings/{meeting}/complete', [MeetingController::class, 'complete'])
    ->name('meetings.complete');

// 4. Ruta para "Cancelar" una reunión
Route::patch('meetings/{meeting}/cancel', [MeetingController::class, 'cancel'])
    ->name('meetings.cancel');

    // --- RUTAS DE ENCUESTA 

// 1. Ruta para MOSTRAR el formulario de la encuesta
Route::get('meetings/{meeting}/survey', [MeetingSurveyController::class, 'show'])
    ->name('survey.show');

// 2. Ruta para GUARDAR la respuesta de la encuesta
Route::post('meetings/{meeting}/survey', [MeetingSurveyController::class, 'store'])
    ->name('survey.store');
});


// =========================================================================
// --- RUTAS PÚBLICAS (Inscripción) ---
// =========================================================================
Route::get('/register/event/{link}', [EventRegistrationController::class, 'showRegistrationForm'])
    ->name('event.register.form');

Route::post('/register/event/{link}', [EventRegistrationController::class, 'storeRegistration'])
    ->name('event.register.store');


// --- AUTENTICACIÓN DE BREEZE ---
require __DIR__.'/auth.php';
