<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('main');
});

Route::get('/login', function () {
    return view('auth.login');
})->name('login');

Route::get('/register', function () {
    return view('auth.register');
})->name('register');



Route::view('/admin/adminlogin', 'adminpage.contents.adminlogin')->name('admin.adminlogin');

Route::view('/admin', 'adminpage.contents.dashboard')->name('admin.dashboard');
Route::view('/admin/users', 'adminpage.contents.users')->name('admin.users');
Route::view('/admin/jobs', 'adminpage.contents.jobs')->name('admin.jobs');
Route::view('/admin/billing', 'adminpage.contents.billing')->name('admin.billing');
Route::view('/admin/reports', 'adminpage.contents.reports')->name('admin.reports');
Route::view('/admin/settings', 'adminpage.contents.settings')->name('admin.settings');
Route::view('/admin/taxonomy', 'adminpage.contents.taxonomy')->name('admin.taxonomy');

