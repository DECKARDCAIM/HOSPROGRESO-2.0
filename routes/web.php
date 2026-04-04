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
use App\Http\Controllers\ReleaseController;
use App\Http\Controllers\WorkDepartmentController;
use App\Http\Controllers\UnityExecutionController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\RelationshipTypeController;
use App\Http\Controllers\PatientRelativeController;

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
    Route::controller(PatientController::class)->prefix('patients')->name('patients.')->group(function () {
        Route::get('export/excel', 'exportExcel')->name('export.excel');
        Route::get('export/csv', 'exportCSV')->name('export.csv');
        Route::get('export/pdf', 'exportPDF')->name('export.pdf');
        Route::post('destroy-multiple', 'destroyMultiple')->name('destroy-multiple');
        Route::post('{id}/restore', 'restore')->name('restore');
        Route::get('relatives/search', 'searchRelatives')->name('relatives.search');
        Route::post('relatives/store-ajax', 'storeRelativeAjax')->name('relatives.store-ajax');
    });
    Route::resource('patients', PatientController::class);

    Route::controller(PatientRelativeController::class)->prefix('patient-relatives')->name('patient-relatives.')->group(function () {
        Route::post('export/excel', 'exportExcel')->name('export.excel');
        Route::post('export/csv', 'exportCSV')->name('export.csv');
        Route::post('export/pdf', 'exportPDF')->name('export.pdf');
        Route::post('print', 'print')->name('print');
        Route::post('destroy-multiple', 'destroyMultiple')->name('destroy-multiple');
    });
    Route::resource('patient-relatives', PatientRelativeController::class);


    // ==========================================
    // MÓDULO: ADMINISTRACIÓN (Usuarios, Comunicados)
    // ==========================================
    Route::prefix('administration')->group(function () {
        Route::resource('users', UserController::class);
        Route::post('/users/{id}/restore', [UserController::class, 'restore'])->name('users.restore');
        
        Route::post('/releases/generate-ai', [ReleaseController::class, 'generateAiContent'])->name('releases.generate-ai');
        Route::post('/releases/{id}/mark-as-read', [ReleaseController::class, 'markAsRead'])->name('releases.mark-as-read');
        Route::post('/releases/mark-all-as-read', [ReleaseController::class, 'markAllAsRead'])->name('releases.mark-all-as-read');
        Route::get('/releases/unread-notifications', [ReleaseController::class, 'getUnread'])->name('releases.get-unread');
        
        Route::resource('releases', ReleaseController::class);
    });

    // ==========================================
    // MÓDULO: MÉTRICAS
    // ==========================================
    Route::prefix('metrics')->name('metrics.')->group(function () {
        Route::get('/system', [MetricsController::class, 'system'])->name('system.index');
        Route::get('/system/expand/{chart}', [MetricsController::class, 'expandSystem'])->name('system.expand');
        Route::post('/system/export/pdf', [MetricsController::class, 'exportPDF'])->name('system.export.pdf');
        Route::post('/system/export/print', [MetricsController::class, 'print'])->name('system.export.print');
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

    // ==========================================
    // MÓDULO: MANTENIMIENTO GENERAL
    // ==========================================
    Route::controller(WorkDepartmentController::class)->prefix('work-departments')->name('work-departments.')->group(function () {
        Route::post('export/excel', 'exportExcel')->name('export.excel');
        Route::post('export/csv', 'exportCSV')->name('export.csv');
        Route::post('export/pdf', 'exportPDF')->name('export.pdf');
        Route::post('print', 'print')->name('print');
        Route::post('destroy-multiple', 'destroyMultiple')->name('destroy-multiple');
        Route::post('restore-multiple', 'restoreMultiple')->name('restore-multiple');
        Route::post('{work_department}/restore', 'restore')->name('restore');
    });
    Route::resource('work-departments', WorkDepartmentController::class);

    Route::controller(UnityExecutionController::class)->prefix('unity-executions')->name('unity-executions.')->group(function () {
        Route::post('export/excel', 'exportExcel')->name('export.excel');
        Route::post('export/csv', 'exportCSV')->name('export.csv');
        Route::post('export/pdf', 'exportPDF')->name('export.pdf');
        Route::post('print', 'print')->name('print');
    });
    Route::resource('unity-executions', UnityExecutionController::class)->only(['index']);

    Route::controller(RoleController::class)->prefix('roles')->name('roles.')->group(function () {
        Route::post('export/excel', 'exportExcel')->name('export.excel');
        Route::post('export/csv', 'exportCSV')->name('export.csv');
        Route::post('export/pdf', 'exportPDF')->name('export.pdf');
        Route::post('print', 'print')->name('print');
    });
    Route::resource('roles', RoleController::class)->only(['index']);

    Route::controller(RelationshipTypeController::class)->prefix('relationship-types')->name('relationship-types.')->group(function () {
        Route::post('export/excel', 'exportExcel')->name('export.excel');
        Route::post('export/csv', 'exportCSV')->name('export.csv');
        Route::post('export/pdf', 'exportPDF')->name('export.pdf');
        Route::post('print', 'print')->name('print');
        Route::post('destroy-multiple', 'destroyMultiple')->name('destroy-multiple');
        Route::post('restore-multiple', 'restoreMultiple')->name('restore-multiple');
        Route::post('{relationship_type}/restore', 'restore')->name('restore');
    });
    Route::resource('relationship-types', RelationshipTypeController::class);

});