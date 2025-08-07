<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DiagnosisController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application.
|
*/

Route::get('/', function () {
    return view('home');
})->name('home');

// Authentication Routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Upload X-ray image
Route::post('/upload', [DiagnosisController::class, 'uploadXray']);

// Halaman gejala dan proses diagnosis
Route::get('/symptom', [DiagnosisController::class, 'showSymptomForm']);
Route::post('/diagnosis', [DiagnosisController::class, 'processDiagnosis']);

// Halaman hasil diagnosis (untuk akses langsung jika diperlukan)
Route::get('/result', [DiagnosisController::class, 'showResult']);

// Profile update
Route::post('/profile/update', [UserController::class, 'update'])->name('profile.update');

// Routes yang memerlukan authentication
Route::middleware('auth')->group(function () {
    Route::get('/user', [DiagnosisController::class, 'showUserDashboard'])->name('user.dashboard');
    
    // Route untuk riwayat dan pengaturan (jika diperlukan)
    Route::get('/history', function () {
        return view('history'); // Buat view ini jika diperlukan
    })->name('user.history');
    
    Route::get('/settings', function () {
        return view('settings'); // Buat view ini jika diperlukan
    })->name('user.settings');
});

Route::get('/test-functional', [DiagnosisController::class, 'testFunctional']);
