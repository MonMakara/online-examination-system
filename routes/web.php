<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ClassroomController;
use App\Http\Controllers\ExamController;
use App\Http\Controllers\QuestionController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\TeacherController;
use Illuminate\Support\Facades\Route;
use Laravel\Socialite\Facades\Socialite;

// Root to login when opening system
Route::get('/', fn() => redirect()->route('show-login'));

// Auth Routes
Route::controller(AuthController::class)->group(function () {

    Route::get('/login', 'showLogin')->name('show-login');
    Route::get('/register', 'showRegister')->name('show-register');

    Route::get('/verify-otp', [AuthController::class, 'showOtpForm'])->name('otp.verify');
    Route::post('/verify-otp', [AuthController::class, 'verifyOtp'])->name('otp.verify.submit');

    // Forgot Password Flow
    Route::get('/forgot-password', [AuthController::class, 'showForgotPasswordForm'])->name('password.request');
    Route::post('/forgot-password/send', [AuthController::class, 'sendResetOtp'])->name('password.email');

    Route::get('/forgot-password/verify', [AuthController::class, 'showResetOtpForm'])->name('password.otp.form');
    Route::post('/forgot-password/verify', [AuthController::class, 'verifyResetOtp'])->name('password.otp.verify');

    Route::get('/reset-password', [AuthController::class, 'showNewPasswordForm'])->name('password.reset.form');
    Route::post('/reset-password', [AuthController::class, 'updatePassword'])->name('password.update');

    Route::get('auth/google', [AuthController::class, 'redirectToGoogle'])->name('auth.google');
    Route::get('auth/google/callback', [AuthController::class, 'handleGoogleCallback']);

    Route::post('/register', 'register')->name('register');
    Route::post('/login', 'login')->name('login');
    Route::post('/logout', 'logout')->name('logout');

    Route::get('/account/delete', [AuthController::class, 'showDeleteAccount'])->name('account.delete.show');

    Route::delete('/account/delete', [AuthController::class, 'destroyAccount'])->name('account.delete');
});

// Admin Routes
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {

    // Dashboard page
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');

    // Teachers management
    Route::get('/teachers', [AdminController::class, 'indexTeachers'])->name('teachers.index');
    Route::get('/teachers/create', [AdminController::class, 'createTeachers'])->name('teachers.create');
    Route::post('/teachers', [AdminController::class, 'storeTeachers'])->name('teachers.store');
    Route::get('/teacher/edit/{id}', [AdminController::class, 'editTeachers'])->name('teachers.edit');
    Route::patch('/teacher/update/{id}', [AdminController::class, 'updateTeachers'])->name('teachers.update');
    Route::delete('/teacher/delete/{id}', [AdminController::class, 'destroyTeachers'])->name('teachers.destroy');

    // Classes management
    Route::get('/classes', [AdminController::class, 'indexClasses'])->name('classes.index');
    Route::get('/classes/create', [AdminController::class, 'createClasses'])->name('classes.create');
    Route::post('/classes', [AdminController::class, 'storeClasses'])->name('classes.store');
    Route::get('/classes/edit/{id}', [AdminController::class, 'editClasses'])->name('classes.edit');
    Route::patch('/classes/update/{id}', [AdminController::class, 'updateClasses'])->name('classes.update');
    Route::delete('/classes/delete/{id}', [AdminController::class, 'destroyClasses'])->name('classes.destroy');

    // Profile setting
    Route::get('/profile', [AdminController::class, 'profile'])->name('profile');
    Route::put('/profile/update', [AdminController::class, 'updateProfile'])->name('profile.update');
});

// Teacher Routes
Route::middleware(['auth', 'role:teacher'])->prefix('teacher')->name('teacher.')->group(function () {

    // Dashboard page
    Route::get('/dashboard', [TeacherController::class, 'dashboard'])->name('dashboard');

    // Classrooms management
    Route::get('/classes', [TeacherController::class, 'indexClasses'])->name('classes.index');
    Route::get('/classes/{id}', [ClassroomController::class, 'showManageClass'])->name('classes.show');

    // Exams management
    Route::resource('exams', ExamController::class);
    Route::get('/exams/create', [ExamController::class, 'createExams'])->name('exams.create');
    Route::post('/exams/store', [ExamController::class, 'storeExams'])->name('exams.store');
    Route::get('/exams/edit/{id}', [ExamController::class, 'editExams'])->name('exams.edit');
    Route::patch('/exams/update/{id}', [ExamController::class, 'updateExams'])->name('exams.update');
    Route::delete('/exams/delete/{id}', [ExamController::class, 'destroyExams'])->name('exams.destroy');

    // Questions management
    Route::get('/exam/{exam}/questions/create', [QuestionController::class, 'createQuestions'])->name('exams.questions.create');
    Route::post('/exams/{exam}/questions/store', [QuestionController::class, 'storeQuestions'])->name('exam.questions.store');

    // Profile Settings
    Route::get('/profile', [TeacherController::class, 'profile'])->name('profile');
    Route::put('/profile/update', [TeacherController::class, 'updateProfile'])->name('profile.update');

    // Student Grades/Reports
    Route::get('/teacher/grades', [TeacherController::class, 'gradesIndex'])->name('grades.index');
    Route::get('/teacher/grades/class/{id}', [TeacherController::class, 'showClassGrades'])->name('grades.class');
});

// Student Routes
Route::middleware(['auth', 'role:student'])->prefix('student')->name('student.')->group(function () {
    // Dashboard page
    Route::get('/dashboard', [StudentController::class, 'dashboard'])->name('dashboard');
    Route::post('/join-class', [StudentController::class, 'joinClass'])->name('join.class');
    Route::get('/classes/{id}', [StudentController::class, 'showClass'])->name('classes.show');

    // Exam page
    Route::get('/exams', [StudentController::class, 'activeExams'])->name('exams.index');
    Route::get('/student/exams/{id}/start', [StudentController::class, 'startExam'])->name('exams.start');
    Route::post('/student/exams/{id}/submit', [StudentController::class, 'submitExam'])->name('exams.submit');

    // Results page
    Route::get('/my-results', [StudentController::class, 'myResults'])->name('results.index');

    // Profile setting
    Route::get('/profile', [StudentController::class, 'profile'])->name('profile');
    Route::put('/profile/update', [StudentController::class, 'updateProfile'])->name('profile.update');
});
