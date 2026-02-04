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



#ADMIN ROUTESSS
Route::view('/admin/adminlogin', 'adminpage.contents.adminlogin')->name('admin.adminlogin');

Route::view('/admin', 'adminpage.contents.dashboard')->name('admin.dashboard');
Route::view('/admin/users', 'adminpage.contents.users')->name('admin.users');
Route::view('/admin/jobs', 'adminpage.contents.jobs')->name('admin.jobs');
Route::view('/admin/billing', 'adminpage.contents.billing')->name('admin.billing');
Route::view('/admin/reports', 'adminpage.contents.reports')->name('admin.reports');
Route::view('/admin/settings', 'adminpage.contents.settings')->name('admin.settings');
Route::view('/admin/taxonomy', 'adminpage.contents.taxonomy')->name('admin.taxonomy');

