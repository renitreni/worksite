<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Candidate\JobBrowseController;
use App\Http\Controllers\Candidate\AgencyController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\Candidate\AgencyFollowController;




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

Route::get('/industries/{industry}', [HomeController::class, 'industryJobs'])
    ->name('industries.jobs');

Route::view('/about','mainpage.about-us')->name('about');
Route::view('/contact','mainpage.contact-us')->name('contact');
Route::view('/privacy-policy','mainpage.privacy-policy')->name('privacy-policy');
Route::view('/terms-of-service','mainpage.terms-of-service')->name('terms-of-service');
Route::view('/faqs','mainpage.faqs')->name('faqs');
Route::view('/help-center','mainpage.help-center')->name('help-center');

Route::middleware('auth')->group(function () {
    Route::get('/notifications', [NotificationController::class, 'index']);
    Route::post('/notifications/mark-all', [NotificationController::class, 'markAllRead']);
    Route::post('/notifications/{id}/read', [NotificationController::class, 'markSingleRead']);
    Route::get('/all-notifications', [NotificationController::class, 'all'])
        ->name('notifications.all');
});


Route::middleware('auth')->group(function () {

    Route::post('/agency/{employerProfile}/follow',
        [AgencyFollowController::class,'toggle']
    )->name('agency.follow');

});

Route::get('/help/{category}', function ($category) {
    return view('mainpage.help-category', compact('category'));
})->name('help.category');

/*
|--------------------------------------------------------------------------
| Split route files
|--------------------------------------------------------------------------
*/
require __DIR__ . '/candidate.php';
require __DIR__ . '/employer.php';
require __DIR__ . '/admin.php';