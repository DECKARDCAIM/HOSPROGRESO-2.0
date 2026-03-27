<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\CountryController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\MunicipalityController;
use App\Http\Controllers\MetricsController;
use App\Http\Controllers\AllergyController;
use App\Http\Controllers\CivilStatusController;
use App\Http\Controllers\GenderController;
use App\Http\Controllers\EthnicityController;
use App\Http\Controllers\LinguisticCommunityController;
use App\Http\Controllers\DisabilityController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\SpecialtyController;

Route::get('/', function () {
    if (Auth::check()) {
        return redirect()->route('home');
    }
    return redirect()->route('login');
});

Route::get('login', [LoginController::class , 'showLoginForm'])->name('login');
Route::post('login', [LoginController::class , 'login']);
Route::post('logout', [LoginController::class , 'logout'])->name('logout');

Route::get('/home', [HomeController::class , 'index'])->name('home')->middleware('PreventBackHistory');

Route::middleware(['auth', 'PreventBackHistory'])->group(function () {
    Route::post('/session/ping', [UserController::class, 'ping'])->name('session.ping');
    Route::post('/user/update-estado', [UserController::class , 'updateEstado'])->name('user.estado');
    Route::post('/user/update-theme', [UserController::class , 'updateThemePreference'])->name('user.update-theme');


    // Rutas de perfil
    Route::get('/profile', [ProfileController::class , 'index'])->name('profile.index');
    Route::get('/profile/sessions/history', [ProfileController::class , 'sessionHistory'])->name('profile.sessions.history');
    Route::delete('/profile/sessions', [ProfileController::class , 'destroyOtherSessions'])->name('profile.sessions.destroy');
    Route::get('/profile/edit', [ProfileController::class , 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class , 'update'])->name('profile.update');
    Route::post('/profile/update-avatar', [ProfileController::class , 'updateAvatar'])->name('profile.update-avatar');
    Route::post('/profile/update-banner', [ProfileController::class , 'updateBanner'])->name('profile.update-banner');
    Route::delete('/profile/delete-avatar', [ProfileController::class , 'deleteAvatar'])->name('profile.delete-avatar');
    Route::delete('/profile/delete-banner', [ProfileController::class , 'deleteBanner'])->name('profile.delete-banner');

    // IA ISAAC Chat
    Route::post('/isaac/chat', [App\Http\Controllers\IsaacChatController::class, 'chat'])->name('isaac.chat');

    // ==========================================
    // MÓDULO: UBICACIONES (Países, Deptos, Municipios)
    // ==========================================
    Route::get('/patients/get-departments-by-country', [PatientController::class , 'getDepartmentsByCountry'])->name('patients.get-departments-by-country');
    Route::get('/patients/get-municipalities-by-department', [PatientController::class , 'getMunicipalitiesByDepartment'])->name('patients.get-municipalities-by-department');

    // Países
    Route::controller(CountryController::class)->prefix('countries')->name('countries.')->group(function () {
        Route::post('export/excel', 'exportExcel')->name('export.excel');
        Route::post('export/csv', 'exportCSV')->name('export.csv');
        Route::post('export/pdf', 'exportPDF')->name('export.pdf');
        Route::post('export/print', 'print')->name('print');
        Route::post('destroy-multiple', 'destroyMultiple')->name('destroy-multiple');
        Route::post('restore-multiple', 'restoreMultiple')->name('restore-multiple');
        Route::post('{country}/restore', 'restore')->name('restore');
    });
    Route::resource('countries', CountryController::class);

    // Departamentos
    Route::controller(DepartmentController::class)->prefix('departments')->name('departments.')->group(function () {
        Route::post('export/excel', 'exportExcel')->name('export.excel');
        Route::post('export/csv', 'exportCSV')->name('export.csv');
        Route::post('export/pdf', 'exportPDF')->name('export.pdf');
        Route::post('export/print', 'print')->name('print');
        Route::post('destroy-multiple', 'destroyMultiple')->name('destroy-multiple');
        Route::post('restore-multiple', 'restoreMultiple')->name('restore-multiple');
        Route::post('{department}/restore', 'restore')->name('restore');
    });
    Route::resource('departments', DepartmentController::class);

    // Municipios
    Route::controller(MunicipalityController::class)->prefix('municipalities')->name('municipalities.')->group(function () {
        Route::post('export/excel', 'exportExcel')->name('export.excel');
        Route::post('export/csv', 'exportCSV')->name('export.csv');
        Route::post('export/pdf', 'exportPDF')->name('export.pdf');
        Route::post('export/print', 'print')->name('print');
        Route::post('destroy-multiple', 'destroyMultiple')->name('destroy-multiple');
        Route::post('restore-multiple', 'restoreMultiple')->name('restore-multiple');
        Route::post('{municipality}/restore', 'restore')->name('restore');
    });
    Route::resource('municipalities', MunicipalityController::class);

    // ==========================================
    // MÓDULO: PACIENTES Y FAMILIARES
    // ==========================================
    Route::get('/patients/relatives/search', [PatientController::class , 'searchRelatives'])->name('patients.relatives.search');
    Route::post('/patients/relatives/store-ajax', [PatientController::class , 'storeRelativeAjax'])->name('patients.relatives.store-ajax');
    // Rutas Resource de Pacientes
    Route::resource('patients', PatientController::class);
    Route::get('/patients/export/excel', [PatientController::class , 'exportExcel'])->name('patients.export.excel');
    Route::get('/patients/export/csv', [PatientController::class , 'exportCSV'])->name('patients.export.csv');
    Route::get('/patients/export/pdf', [PatientController::class , 'exportPDF'])->name('patients.export.pdf');
    Route::post('/patients/destroy-multiple', [PatientController::class , 'destroyMultiple'])->name('patients.destroy-multiple');
    Route::post('/patients/{id}/restore', [PatientController::class , 'restore'])->name('patients.restore');


    Route::resource('users', UserController::class);
    Route::post('/users/{id}/restore', [UserController::class, 'restore'])->name('users.restore');

    // ==========================================
    // MÓDULO: MÉTRICAS
    // ==========================================
    Route::prefix('metrics')->group(function () {
        Route::get('/system', [MetricsController::class, 'system'])->name('metrics.system.index');
        Route::get('/system/expand/{chart}', [MetricsController::class, 'expandSystem'])->name('metrics.system.expand');
    });

    // ==========================================
    // MÓDULO: CATÁLOGOS DE PACIENTES
    // ==========================================

    // Alergias
    Route::controller(AllergyController::class)->prefix('allergies')->name('allergies.')->group(function () {
        Route::post('export/excel', 'exportExcel')->name('export.excel');
        Route::post('export/csv', 'exportCSV')->name('export.csv');
        Route::post('export/pdf', 'exportPDF')->name('export.pdf');
        Route::post('export/print', 'print')->name('print');
        Route::post('destroy-multiple', 'destroyMultiple')->name('destroy-multiple');
        Route::post('restore-multiple', 'restoreMultiple')->name('restore-multiple');
        Route::post('{allergy}/restore', 'restore')->name('restore');
    });
    Route::resource('allergies', AllergyController::class);

    // Estados Civiles
    Route::controller(CivilStatusController::class)->prefix('civil-statuses')->name('civil-statuses.')->group(function () {
        Route::post('export/excel', 'exportExcel')->name('export.excel');
        Route::post('export/csv', 'exportCSV')->name('export.csv');
        Route::post('export/pdf', 'exportPDF')->name('export.pdf');
        Route::post('export/print', 'print')->name('print');
        Route::post('destroy-multiple', 'destroyMultiple')->name('destroy-multiple');
        Route::post('restore-multiple', 'restoreMultiple')->name('restore-multiple');
        Route::post('{civilStatus}/restore', 'restore')->name('restore');
    });
    Route::resource('civil-statuses', CivilStatusController::class);

    // Géneros
    Route::controller(GenderController::class)->prefix('genders')->name('genders.')->group(function () {
        Route::post('export/excel', 'exportExcel')->name('export.excel');
        Route::post('export/csv', 'exportCSV')->name('export.csv');
        Route::post('export/pdf', 'exportPDF')->name('export.pdf');
        Route::post('export/print', 'print')->name('print');
        Route::post('destroy-multiple', 'destroyMultiple')->name('destroy-multiple');
        Route::post('restore-multiple', 'restoreMultiple')->name('restore-multiple');
        Route::post('{gender}/restore', 'restore')->name('restore');
    });
    Route::resource('genders', GenderController::class);

    // Etnias
    Route::controller(EthnicityController::class)->prefix('ethnicities')->name('ethnicities.')->group(function () {
        Route::post('export/excel', 'exportExcel')->name('export.excel');
        Route::post('export/csv', 'exportCSV')->name('export.csv');
        Route::post('export/pdf', 'exportPDF')->name('export.pdf');
        Route::post('export/print', 'print')->name('print');
        Route::post('destroy-multiple', 'destroyMultiple')->name('destroy-multiple');
        Route::post('restore-multiple', 'restoreMultiple')->name('restore-multiple');
        Route::post('{ethnicity}/restore', 'restore')->name('restore');
    });
    Route::resource('ethnicities', EthnicityController::class);

    // Idiomas (Comunidades Lingüísticas)
    Route::controller(LinguisticCommunityController::class)->prefix('linguistic-communities')->name('linguistic-communities.')->group(function () {
        Route::post('export/excel', 'exportExcel')->name('export.excel');
        Route::post('export/csv', 'exportCSV')->name('export.csv');
        Route::post('export/pdf', 'exportPDF')->name('export.pdf');
        Route::post('export/print', 'print')->name('print');
        Route::post('destroy-multiple', 'destroyMultiple')->name('destroy-multiple');
        Route::post('restore-multiple', 'restoreMultiple')->name('restore-multiple');
        Route::post('{linguisticCommunity}/restore', 'restore')->name('restore');
    });
    Route::resource('linguistic-communities', LinguisticCommunityController::class);

    // Discapacidades
    Route::controller(DisabilityController::class)->prefix('disabilities')->name('disabilities.')->group(function () {
        Route::post('export/excel', 'exportExcel')->name('export.excel');
        Route::post('export/csv', 'exportCSV')->name('export.csv');
        Route::post('export/pdf', 'exportPDF')->name('export.pdf');
        Route::post('export/print', 'print')->name('print');
        Route::post('destroy-multiple', 'destroyMultiple')->name('destroy-multiple');
        Route::post('restore-multiple', 'restoreMultiple')->name('restore-multiple');
        Route::post('{disability}/restore', 'restore')->name('restore');
    });
    Route::resource('disabilities', DisabilityController::class);

    // ==========================================
    // MÓDULO: CATÁLOGOS MÉDICOS
    // ==========================================

    // Turnos
    Route::controller(ScheduleController::class)->prefix('schedules')->name('schedules.')->group(function () {
        Route::post('export/excel', 'exportExcel')->name('export.excel');
        Route::post('export/csv', 'exportCSV')->name('export.csv');
        Route::post('export/pdf', 'exportPDF')->name('export.pdf');
        Route::post('export/print', 'print')->name('print');
        Route::post('destroy-multiple', 'destroyMultiple')->name('destroy-multiple');
        Route::post('restore-multiple', 'restoreMultiple')->name('restore-multiple');
        Route::post('{schedule}/restore', 'restore')->name('restore');
    });
    Route::resource('schedules', ScheduleController::class);

    // Especialidades
    Route::controller(SpecialtyController::class)->prefix('specialties')->name('specialties.')->group(function () {
        Route::post('export/excel', 'exportExcel')->name('export.excel');
        Route::post('export/csv', 'exportCSV')->name('export.csv');
        Route::post('export/pdf', 'exportPDF')->name('export.pdf');
        Route::post('export/print', 'print')->name('print');
        Route::post('destroy-multiple', 'destroyMultiple')->name('destroy-multiple');
        Route::post('restore-multiple', 'restoreMultiple')->name('restore-multiple');
        Route::post('{specialty}/restore', 'restore')->name('restore');
    });
    Route::resource('specialties', SpecialtyController::class);

});