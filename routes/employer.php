<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Employer\EmployerAuthController;
use App\Http\Controllers\Employer\EmployerProfileController;
use App\Http\Controllers\Employer\JobController;
use App\Http\Controllers\Employer\ApplicantController;
use App\Http\Controllers\Employer\SubscriptionController as EmployerSubscriptionController;

/*
|--------------------------------------------------------------------------
| AUTH - EMPLOYER (GUEST ONLY)
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->prefix('employer')->name('employer.')->group(function () {
    Route::get('/register', [EmployerAuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [EmployerAuthController::class, 'register'])->name('register.store');

    Route::get('/login', [EmployerAuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [EmployerAuthController::class, 'login'])->name('login.store');
});

Route::post('/employer/logout', [EmployerAuthController::class, 'logout'])
    ->middleware('auth')
    ->name('employer.logout');

/*
|--------------------------------------------------------------------------
| EMPLOYER PAGES (AUTH + ROLE)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:employer'])->prefix('employer')->name('employer.')->group(function () {

    Route::view('/dashboard', 'employer.contents.dashboard')->name('dashboard');

    // Company profile
    Route::get('/company-profile', [EmployerProfileController::class, 'show'])->name('company-profile');
    Route::get('/company-profile/edit', [EmployerProfileController::class, 'edit'])->name('company-profile.edit');
    Route::post('/company-profile', [EmployerProfileController::class, 'update'])->name('company-profile.update');
    Route::delete('/delete-account', [EmployerProfileController::class, 'deleteAccount'])->name('delete-account');

    Route::get('/analytics', fn() => view('employer.contents.analytics'))->name('analytics');

    // Job postings
    Route::get('/job-postings', [JobController::class, 'index'])->name('job-postings.index');
    Route::get('/job-postings/create', [JobController::class, 'create'])->name('job-postings.create');
    Route::post('/job-postings', [JobController::class, 'store'])->name('job-postings.store');
    Route::get('/job-postings/closed', [JobController::class, 'closed'])->name('job-postings.closed');
    Route::put('/job-postings/{job}/reopen', [JobController::class, 'reopen'])->name('job-postings.reopen');
    Route::get('/job-postings/{job}', [JobController::class, 'show'])->name('job-postings.show');
    Route::get('/job-postings/{job}/edit', [JobController::class, 'edit'])->name('job-postings.edit');
    Route::put('/job-postings/{job}', [JobController::class, 'update'])->name('job-postings.update');
    Route::delete('/job-postings/{job}', [JobController::class, 'destroy'])->name('job-postings.destroy');

    // Geo + skills helpers
    Route::get('/geo/cities', [JobController::class, 'citiesByCountry'])->name('geo.cities');
    Route::get('/geo/areas', [JobController::class, 'areasByCity'])->name('geo.areas');
    Route::get('/industries/{industry}/skills', [JobController::class, 'skillsByIndustry'])->name('industries.skills');

    // Applicants
    Route::get('/applicants', [ApplicantController::class, 'index'])->name('applicants.index');
    Route::get('/applicants/export', [ApplicantController::class, 'export'])->name('applicants.export');
    Route::get('/applicants/{application}', [ApplicantController::class, 'show'])->name('applicants.show');

    Route::put('/applicants/{application}/shortlist', [ApplicantController::class, 'shortlist'])->name('applicants.shortlist');
    Route::put('/applicants/{application}/interview', [ApplicantController::class, 'interview'])->name('applicants.interview');
    Route::put('/applicants/{application}/hire', [ApplicantController::class, 'hire'])->name('applicants.hire');
    Route::put('/applicants/{application}/reject', [ApplicantController::class, 'reject'])->name('applicants.reject');

    // Subscription (employer side)
    Route::get('/subscription', [EmployerSubscriptionController::class, 'dashboard'])->name('subscription.dashboard');
    Route::get('/subscription/select/{plan}', [EmployerSubscriptionController::class, 'selectPlan'])->name('subscription.select');
    Route::get('/subscription/pay/{subscription}', [EmployerSubscriptionController::class, 'payment'])->name('subscription.payment');
    Route::post('/subscription/pay/{subscription}', [EmployerSubscriptionController::class, 'processPayment'])->name('subscription.pay');
});