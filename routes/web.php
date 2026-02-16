<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Auth\AuthController; // employer/admin login can stay here
use App\Http\Controllers\Candidate\CandidateAuthController;
use App\Http\Controllers\Employer\EmployerAuthController;
use App\Http\Controllers\Employer\EmployerController;
use App\Http\Controllers\Admin\AdminAuthController;

/*
|--------------------------------------------------------------------------
| PUBLIC
|--------------------------------------------------------------------------
*/

Route::get('/', fn() => view('main'))->name('home');

Route::view('/search-jobs', 'mainpage.search-jobs-page.search-jobs')
    ->name('search-jobs');

Route::view('/search-agency', 'mainpage.search-jobs-page.search-agency')
    ->name('search-agency');

Route::view('/search-industries', 'mainpage.search-jobs-page.search-industries')
    ->name('search-industries');

Route::view('/search-country', 'mainpage.search-jobs-page.search-country')
    ->name('search-country');

Route::view('/agency-details', 'mainpage.agency-details-page.agency.show')
    ->name('agency.details');
/*
|--------------------------------------------------------------------------
| AUTH - CANDIDATE (GUEST ONLY)  
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->prefix('candidate')->name('candidate.')->group(function () {
    Route::get('/register', [CandidateAuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [CandidateAuthController::class, 'register'])->name('register.store');

    Route::get('/login', [CandidateAuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [CandidateAuthController::class, 'login'])->name('login.store');

    Route::post('/verify-email', [CandidateAuthController::class, 'verifyEmailCode'])
        ->name('verify.email');

    Route::post('/resend-verification', [CandidateAuthController::class, 'resendEmailCode'])
        ->name('verify.resend');
});

Route::post('/candidate/logout', [CandidateAuthController::class, 'logout'])
    ->middleware('auth')
    ->name('candidate.logout');

/*
|--------------------------------------------------------------------------
| AUTH - EMPLOYER (GUEST ONLY) 
|--------------------------------------------------------------------------
| Note: You can later make EmployerAuthController similar to candidate.
*/
Route::middleware('guest')->prefix('employer')->name('employer.')->group(function () {
    Route::get('/register', [EmployerAuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [EmployerAuthController::class, 'register'])->name('register.store');

    Route::get('/login', [EmployerAuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [EmployerAuthController::class, 'login'])->name('login.store');
});

Route::middleware(['auth'])->prefix('employer')->name('employer.')->group(function () {
    Route::get('/company-profile', [EmployerController::class, 'editProfile'])->name('company-profile');
    Route::post('/company-profile', [EmployerController::class, 'updateProfile'])->name('company-profile.update');
    Route::delete('/delete-account', [EmployerController::class, 'deleteAccount'])->name('delete-account');
});

Route::post('/employer/logout', [EmployerAuthController::class, 'logout'])
    ->middleware('auth')
    ->name('employer.logout');

/*
|--------------------------------------------------------------------------
| AUTH - ADMIN (GUEST ONLY)
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->prefix('admin')->name('admin.')->group(function () {
    // Optional: if you have admin login controller method
    Route::get('/login', [AdminAuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AdminAuthController::class, 'login'])->name('login.submit');
    Route::post('/logout', [AdminAuthController::class, 'logout'])->name('logout');
});


/*
|--------------------------------------------------------------------------
| CANDIDATE PAGES (AUTH + ROLE)
|--------------------------------------------------------------------------
*/
Route::prefix('candidate')->name('candidate.')->middleware(['auth', 'role:candidate'])->group(function () {
    Route::get('/', fn() => view('candidate.layout'))->name('index');

    Route::get('/home', fn() => view('candidate.contents.home'))->name('home');
    Route::get('/dashboard', fn() => view('candidate.contents.dashboard'))->name('dashboard');

    Route::get('/profile', fn() => view('candidate.contents.profile'))->name('profile');
    Route::get('/my-resume', fn() => view('candidate.contents.my-resume'))->name('my-resume');
    Route::get('/my-applied-jobs', fn() => view('candidate.contents.my-applied-jobs'))->name('my-applied-jobs');
    Route::get('/shortlist-jobs', fn() => view('candidate.contents.shortlist-jobs'))->name('shortlist-jobs');
    Route::get('/following-employers', fn() => view('candidate.contents.following-employers'))->name('following-employers');
    Route::get('/job-alerts', fn() => view('candidate.contents.job-alerts'))->name('job-alerts');
    Route::get('/messages', fn() => view('candidate.contents.messages'))->name('messages');
    Route::get('/meetings', fn() => view('candidate.contents.meetings'))->name('meetings');
    Route::get('/change-password', fn() => view('candidate.contents.change-password'))->name('change-password');
    Route::get('/delete-profile', fn() => view('candidate.contents.delete-profile'))->name('delete-profile');
});

/*
|--------------------------------------------------------------------------
| EMPLOYER PAGES (AUTH + ROLE)
|--------------------------------------------------------------------------
*/
Route::prefix('employer')->name('employer.')->middleware(['auth', 'role:employer'])->group(function () {
    Route::view('/dashboard', 'employer.contents.dashboard')->name('dashboard');

    // Route::get('/company-profile', fn() => view('employer.contents.profile'))->name('company-profile');
    Route::get('/analytics', fn() => view('employer.contents.analytics'))->name('analytics');
    Route::get('/subscription', fn() => view('employer.contents.subscription'))->name('subscription');

    Route::get('/job-postings/active', fn() => view('employer.contents.job-postings.active'))->name('job-postings.active');
    Route::get('/job-postings/closed', fn() => view('employer.contents.job-postings.closed'))->name('job-postings.closed');

    Route::get('/applicants/all', fn() => view('employer.contents.applicants.all'))->name('applicants.all');
    Route::get('/applicants/shortlisted', fn() => view('employer.contents.applicants.shortlisted'))->name('applicants.shortlisted');
    Route::get('/applicants/rejected', fn() => view('employer.contents.applicants.rejected'))->name('applicants.rejected');
});

/*
|--------------------------------------------------------------------------
| ADMIN PAGES (AUTH + ROLE)
|--------------------------------------------------------------------------
*/
Route::prefix('admin')->name('admin.')->middleware(['auth', 'role:admin'])->group(function () {
    Route::view('/', 'adminpage.contents.dashboard')->name('dashboard');

    Route::view('/users', 'adminpage.contents.users')->name('users');
    Route::view('/jobs', 'adminpage.contents.jobs')->name('jobs');
    Route::view('/billing', 'adminpage.contents.billing')->name('billing');
    Route::view('/reports', 'adminpage.contents.reports')->name('reports');
    Route::view('/settings', 'adminpage.contents.settings')->name('settings');
    Route::view('/taxonomy', 'adminpage.contents.taxonomy')->name('taxonomy');
});
