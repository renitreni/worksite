<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Candidate\JobBrowseController;
use App\Http\Controllers\Candidate\AgencyController;
use App\Http\Controllers\SearchController;



/*
|--------------------------------------------------------------------------
| PUBLIC
|--------------------------------------------------------------------------
*/
Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/sitemap.xml', function () {
    return response()->view('sitemap')
        ->header('Content-Type', 'application/xml');
});

Route::get('/search-jobs', [SearchController::class, 'jobs'])->name('search-jobs');
Route::get('/search-agency', [SearchController::class, 'agency'])->name('search-agency');
Route::get('/search-industries', [SearchController::class, 'industries'])->name('search-industries');
Route::get('/search-country', [SearchController::class, 'country'])->name('search-country');

Route::get('/jobs', [JobBrowseController::class, 'index'])->name('jobs.index');
Route::get('/jobs/{job}', [JobBrowseController::class, 'show'])->name('jobs.show');

Route::get('/agency/{employerProfile}/jobs', [AgencyController::class, 'jobs'])->name('agency.jobs');
Route::get('/agencies/{employerProfile}', [AgencyController::class, 'show'])->name('agency.details');

/*
|--------------------------------------------------------------------------
| Split route files
|--------------------------------------------------------------------------
*/
require __DIR__ . '/candidate.php';
require __DIR__ . '/employer.php';
require __DIR__ . '/admin.php';