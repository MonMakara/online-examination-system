<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

use App\Http\Controllers\Api\AuthApiController;
use App\Http\Controllers\Api\StudentApiController;
use App\Http\Controllers\Api\TeacherApiController;
use App\Http\Controllers\Api\AdminApiController;

// Public Auth Routes
Route::post('/login', [AuthApiController::class, 'login']);
Route::post('/register', [AuthApiController::class, 'register']);
Route::post('/verify-otp', [AuthApiController::class, 'verifyOtp']);
Route::post('/forgot-password', [AuthApiController::class, 'forgotPassword']);
Route::post('/reset-password', [AuthApiController::class, 'resetPassword']);

// Protected Routes
Route::middleware('auth:sanctum')->group(function () {
    // Auth & Profile
    Route::post('/logout', [AuthApiController::class, 'logout']);
    Route::get('/user', [AuthApiController::class, 'user']);
    Route::post('/user/update', [AuthApiController::class, 'updateProfile']);
    Route::delete('/account', [AuthApiController::class, 'deleteAccount']);

    // Student Routes
    Route::prefix('student')->group(function () {
        Route::get('/dashboard', [StudentApiController::class, 'dashboard']);
        Route::get('/classes', [StudentApiController::class, 'classes']);
        Route::get('/exams', [StudentApiController::class, 'exams']); # exams to take
        Route::get('/results', [StudentApiController::class, 'results']);
        Route::get('/results/{id}', [StudentApiController::class, 'reviewExam']); # Deep dive
        Route::post('/join-class', [StudentApiController::class, 'joinClass']);
        Route::post('/exams/{id}/submit', [StudentApiController::class, 'submitExam']);
    });

    // Teacher Routes
    Route::prefix('teacher')->group(function () {
        Route::get('/dashboard', [TeacherApiController::class, 'dashboard']);
        Route::get('/classes', [TeacherApiController::class, 'classes']);
        Route::get('/classes/{id}/grades', [TeacherApiController::class, 'getClassGrades']); 
        Route::get('/students', [TeacherApiController::class, 'students']);
        
        // Exam Management
        Route::get('/exams', [TeacherApiController::class, 'getExams']);
        Route::post('/exams', [TeacherApiController::class, 'storeExam']);
        Route::put('/exams/{id}', [TeacherApiController::class, 'updateExam']);
        Route::delete('/exams/{id}', [TeacherApiController::class, 'destroyExam']);

        // Question Management
        Route::post('/exams/{id}/questions', [TeacherApiController::class, 'storeQuestion']);
        Route::put('/questions/{id}', [TeacherApiController::class, 'updateQuestion']);
        Route::delete('/questions/{id}', [TeacherApiController::class, 'destroyQuestion']);
    });

    // Admin Routes
    Route::prefix('admin')->group(function () {
        Route::get('/dashboard', [AdminApiController::class, 'dashboard']);
        Route::get('/teachers', [AdminApiController::class, 'teachers']);
        Route::post('/teachers', [AdminApiController::class, 'storeTeacher']);
        Route::put('/teachers/{id}', [AdminApiController::class, 'updateTeacher']);
        Route::delete('/teachers/{id}', [AdminApiController::class, 'destroyTeacher']);

        Route::get('/classes', [AdminApiController::class, 'classes']);
        Route::post('/classes', [AdminApiController::class, 'storeClass']);
        Route::put('/classes/{id}', [AdminApiController::class, 'updateClass']);
        Route::delete('/classes/{id}', [AdminApiController::class, 'destroyClass']);
    });
});
