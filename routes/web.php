<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Auth\AuthController; // employer/admin login can stay here
use App\Http\Controllers\Candidate\CandidateAuthController;
use App\Http\Controllers\Employer\EmployerAuthController;

/*
|--------------------------------------------------------------------------
| PUBLIC
|--------------------------------------------------------------------------
*/
Route::get('/', fn () => view('main'))->name('home');

Route::view('/search-jobs', 'mainpage.search-jobs-page.search-jobs')
    ->name('search-jobs');

/*
|--------------------------------------------------------------------------
| AUTH - CANDIDATE (GUEST ONLY)  ✅ separate controller + folder
|--------------------------------------------------------------------------
*/
Route::prefix('candidate')->name('candidate.')->middleware('guest')->group(function () {
    Route::get('/register', [CandidateAuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [CandidateAuthController::class, 'register'])->name('register.store');

    Route::get('/login', [CandidateAuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [CandidateAuthController::class, 'login'])->name('login.store');
});

Route::post('/candidate/logout', [CandidateAuthController::class, 'logout'])
    ->middleware('auth')
    ->name('candidate.logout');

/*
|--------------------------------------------------------------------------
| AUTH - EMPLOYER (GUEST ONLY)  ✅ keep for now (same AuthController)
|--------------------------------------------------------------------------
| Note: You can later make EmployerAuthController similar to candidate.
*/
Route::middleware('guest')->prefix('employer')->group(function () {
    Route::get('/register', [EmployerAuthController::class, 'showRegister'])->name('employer.register');
    Route::post('/register', [EmployerAuthController::class, 'register'])->name('employer.register.store');

    Route::get('/login', [EmployerAuthController::class, 'showLogin'])->name('employer.login');
    Route::post('/login', [EmployerAuthController::class, 'login'])->name('employer.login.store');
});

Route::post('/employer/logout', [EmployerAuthController::class, 'logout'])
    ->middleware('auth')
    ->name('employer.logout');

/*
|--------------------------------------------------------------------------
| AUTH - ADMIN (GUEST ONLY)
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    Route::view('/admin/adminlogin', 'adminpage.contents.adminlogin')->name('admin.adminlogin');

    // Optional: if you have admin login controller method
    // Route::post('/admin/adminlogin', [AuthController::class, 'adminLogin'])->name('admin.login.store');
});

/*
|--------------------------------------------------------------------------
| FALLBACK AUTH ROUTES (optional)
|--------------------------------------------------------------------------
| If you still want /login and /register to exist, point them to candidate.
| Remove these if you don't want them.
*/
Route::get('/login', fn () => redirect()->route('candidate.login'))->name('login');
Route::get('/register', fn () => redirect()->route('candidate.register'))->name('register');

/*
|--------------------------------------------------------------------------
| LOGOUT (AUTH ONLY) - ONE ROUTE ONLY
|--------------------------------------------------------------------------
| Works for all roles (candidate/employer/admin)
*/
Route::post('/logout', [CandidateAuthController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

/*
|--------------------------------------------------------------------------
| CANDIDATE PAGES (AUTH + ROLE)
|--------------------------------------------------------------------------
*/
Route::prefix('candidate')->name('candidate.')->middleware(['auth', 'role:candidate'])->group(function () {
    Route::get('/', fn () => view('candidate.layout'))->name('index');

    Route::get('/dashboard', fn () => view('candidate.contents.dashboard'))->name('dashboard');

    Route::get('/profile', fn () => view('candidate.contents.profile'))->name('profile');
    Route::get('/my-resume', fn () => view('candidate.contents.my-resume'))->name('my-resume');
    Route::get('/my-applied-jobs', fn () => view('candidate.contents.my-applied-jobs'))->name('my-applied-jobs');
    Route::get('/shortlist-jobs', fn () => view('candidate.contents.shortlist-jobs'))->name('shortlist-jobs');
    Route::get('/following-employers', fn () => view('candidate.contents.following-employers'))->name('following-employers');
    Route::get('/job-alerts', fn () => view('candidate.contents.job-alerts'))->name('job-alerts');
    Route::get('/messages', fn () => view('candidate.contents.messages'))->name('messages');
    Route::get('/meetings', fn () => view('candidate.contents.meetings'))->name('meetings');
    Route::get('/change-password', fn () => view('candidate.contents.change-password'))->name('change-password');
    Route::get('/delete-profile', fn () => view('candidate.contents.delete-profile'))->name('delete-profile');
});

/*
|--------------------------------------------------------------------------
| EMPLOYER PAGES (AUTH + ROLE)
|--------------------------------------------------------------------------
*/
Route::prefix('employer')->name('employer.')->middleware(['auth', 'role:employer'])->group(function () {
    Route::view('/dashboard', 'employer.contents.dashboard')->name('dashboard');

    Route::get('/company-profile', fn () => view('employer.contents.profile'))->name('company-profile');
    Route::get('/analytics', fn () => view('employer.contents.analytics'))->name('analytics');
    Route::get('/subscription', fn () => view('employer.contents.subscription'))->name('subscription');

    Route::get('/job-postings/active', fn () => view('employer.contents.job-postings.active'))->name('job-postings.active');
    Route::get('/job-postings/closed', fn () => view('employer.contents.job-postings.closed'))->name('job-postings.closed');

    Route::get('/applicants/all', fn () => view('employer.contents.applicants.all'))->name('applicants.all');
    Route::get('/applicants/shortlisted', fn () => view('employer.contents.applicants.shortlisted'))->name('applicants.shortlisted');
    Route::get('/applicants/rejected', fn () => view('employer.contents.applicants.rejected'))->name('applicants.rejected');
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
