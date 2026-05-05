<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\QuizController;
use App\Http\Controllers\QuestionController;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ResultController;
use App\Http\Controllers\StudentController;

/*
|--------------------------------------------------------------------------
| 🌐 PUBLIC ROUTE
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    return redirect()->route('dashboard'); // direct to dashboard after login
});


/*
|--------------------------------------------------------------------------
| 🔐 AUTH ROUTES
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {

    /*
    |--------------------------------------------------------------------------
    | 📊 DASHBOARD
    |--------------------------------------------------------------------------
    */
    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');


    /*
    |--------------------------------------------------------------------------
    | ▶️ QUIZ FLOW
    |--------------------------------------------------------------------------
    */

    // Start Quiz
    Route::get('/quiz/start/{quiz}', [QuizController::class, 'start'])
        ->name('quiz.start');

    // Submit Quiz
    Route::post('/quiz/submit/{quiz}', [QuizController::class, 'submit'])
        ->name('quiz.submit');

    // Result Page (IMPORTANT)
    Route::get('/quiz/{quiz}/result', [QuizController::class, 'results'])
        ->name('quiz.result');


    /*
    |--------------------------------------------------------------------------
    | 🏆 LEADERBOARD
    |--------------------------------------------------------------------------
    */
    Route::get('/leaderboard', [ResultController::class, 'leaderboard'])
        ->name('leaderboard');


    /*
    |--------------------------------------------------------------------------
    | 🎓 CERTIFICATE
    |--------------------------------------------------------------------------
    */
    Route::get('/certificate/{id}', [ResultController::class, 'downloadCertificate'])
        ->name('certificate.download');


    /*
    |--------------------------------------------------------------------------
    | 🔐 ADMIN PANEL
    |--------------------------------------------------------------------------
    */
    Route::middleware(['isAdmin'])->prefix('admin')->group(function () {

        Route::resource('students', StudentController::class);
        Route::resource('categories', CategoryController::class);
        Route::resource('subjects', SubjectController::class);
        Route::resource('results', ResultController::class);

        Route::resource('quizzes', QuizController::class)->except(['show']);
        Route::resource('questions', QuestionController::class)->except(['show']);
    });

});


/*
|--------------------------------------------------------------------------
| ❌ FALLBACK (SAFE FIX)
|--------------------------------------------------------------------------
*/
Route::fallback(function () {
    return redirect()->route('dashboard');
});


/*
|--------------------------------------------------------------------------
| 🔑 AUTH SYSTEM (LOGIN / REGISTER)
|--------------------------------------------------------------------------
*/
require __DIR__.'/auth.php';