<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    return view('main');
});

Route::get('/login', function () {
    return view('auth.login');
})->name('login');

Route::get('/register', function () {
    return view('auth.register');
})->name('register');

#CANDIDATE ROUTES

Route::get('/candidate', function(){
    return view('candidate.layout');
})->name('candidate');

Route::get('/candidate/dashboard', function(){
    return view('candidate.contents.dashboard');
})->name('candidate.dashboard');

Route::get('/candidate/profile', function () {
    return view('candidate.contents.profile');
})->name('candidate.profile');

Route::get('/candidate/my-resume', function () {
    return view('candidate.contents.my-resume');
})->name('candidate.my-resume');

Route::get('/candidate/my-applied-jobs', function () {
    return view('candidate.contents.my-applied-jobs');
})->name('candidate.my-applied-jobs');

Route::get('/candidate/shortlist-jobs', function () {
    return view('candidate.contents.shortlist-jobs');
})->name('candidate.shortlist-jobs');

Route::get('/candidate/following-employers', function () {
    return view('candidate.contents.following-employers');
})->name('candidate.following-employers');

Route::get('/candidate/job-alerts', function () {
    return view('candidate.contents.job-alerts');
})->name('candidate.job-alerts');

Route::get('/candidate/messages', function () {
    return view('candidate.contents.messages');
})->name('candidate.messages');

Route::get('/candidate/meetings', function () {
    return view('candidate.contents.meetings');
})->name('candidate.meetings');

Route::get('/candidate/change-password', function () {
    return view('candidate.contents.change-password');
})->name('candidate.change-password');

Route::get('/candidate/delete-profile', function () {
    return view('candidate.contents.delete-profile');
})->name('candidate.delete-profile');


Route::post('/logout', function () {
    Auth::logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();

    return redirect('/login');
})->name('logout');


#EMPLOYER ROUTES
Route::view('/employer/dashboard', 'employer.contents.dashboard')
    ->name('employer.dashboard');

Route::get('/employer/company-profile', function () {
    return view('employer.contents.profile');
})->name('employer.company-profile');

Route::get('/employer/analytics', function () {
    return view('employer.contents.analytics');
})->name('employer.analytics');

Route::get('/employer/subscription', function () {
    return view('employer.contents.subscription');
})->name('employer.subscription');

Route::get('/employer/job-postings/active', function () {
    return view('employer.contents.job-postings.active');
})->name('employer.job-postings.active');

Route::get('/employer/job-postings/closed', function () {
    return view('employer.contents.job-postings.closed');
})->name('employer.job-postings.closed');

Route::get('/employer/applicants/all', function () {
    return view('employer.contents.applicants.all');
})->name('employer.applicants.all');

Route::get('/employer/applicants/shortlisted', function () {
    return view('employer.contents.applicants.shortlisted');
})->name('employer.applicants.shortlisted');

Route::get('/employer/applicants/rejected', function () {
    return view('employer.contents.applicants.rejected');
})->name('employer.applicants.rejected');


#ADMIN ROUTESSS
use App\Http\Controllers\Admin\AdminAuthController;

Route::prefix('admin')->name('admin.')->group(function () {

    // Auth
    Route::get('/login', [AdminAuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AdminAuthController::class, 'login'])->name('login.submit');
    Route::post('/logout', [AdminAuthController::class, 'logout'])->name('logout');


    // Pages
    Route::view('/', 'adminpage.contents.dashboard')->name('dashboard');
    Route::view('/users', 'adminpage.contents.users')->name('users');
    Route::view('/jobs', 'adminpage.contents.jobs')->name('jobs');
    Route::view('/billing', 'adminpage.contents.billing')->name('billing');
    Route::view('/reports', 'adminpage.contents.reports')->name('reports');
    Route::view('/settings', 'adminpage.contents.settings')->name('settings');
    Route::view('/taxonomy', 'adminpage.contents.taxonomy')->name('taxonomy');
});
