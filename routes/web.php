<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PatientController;

Route::get('/', function () {
    if (Auth::check()) {
        return redirect()->route('home');
    }
    return redirect()->route('login');
});

Route::get('login', [LoginController::class , 'showLoginForm'])->name('login');
Route::post('login', [LoginController::class , 'login']);
Route::post('logout', [LoginController::class , 'logout'])->name('logout');

Route::get('/home', [HomeController::class , 'index'])->name('home');

Route::middleware('auth')->group(function () {
    Route::post('/user/update-estado', [UserController::class , 'updateEstado'])->name('user.update-estado');
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



    // Rutas AJAX para ubicaciones
    Route::get('/patients/get-departments-by-country', [PatientController::class , 'getDepartmentsByCountry'])->name('patients.get-departments-by-country');
    Route::get('/patients/get-municipalities-by-department', [PatientController::class , 'getMunicipalitiesByDepartment'])->name('patients.get-municipalities-by-department');
    // Rutas AJAX para familiares
    Route::get('/patients/relatives/search', [PatientController::class , 'searchRelatives'])->name('patients.relatives.search');
    Route::post('/patients/relatives/store-ajax', [PatientController::class , 'storeRelativeAjax'])->name('patients.relatives.store-ajax');
    // Rutas Resource de Pacientes
    Route::resource('patients', PatientController::class);
    Route::get('/patients/export/excel', [PatientController::class , 'exportExcel'])->name('patients.export.excel');
    Route::get('/patients/export/csv', [PatientController::class , 'exportCSV'])->name('patients.export.csv');
    Route::get('/patients/export/pdf', [PatientController::class , 'exportPDF'])->name('patients.export.pdf');
    Route::post('/patients/destroy-multiple', [PatientController::class , 'destroyMultiple'])->name('patients.destroy-multiple');
    Route::post('/patients/{id}/restore', [PatientController::class , 'restore'])->name('patients.restore');


    Route::get('/users', [UserController::class , 'index'])->name('users.index');
    Route::get('/users/create', [UserController::class , 'create'])->name('users.create');
    Route::get('/users/{id}/profile', [UserController::class , 'showProfile'])->name('users.profile');
});