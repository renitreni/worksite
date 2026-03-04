<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\IndustryController;
use App\Http\Controllers\Admin\SkillController;
use App\Http\Controllers\Admin\CountryController;
use App\Http\Controllers\Admin\CityController;
use App\Http\Controllers\Admin\AreaController;
use App\Http\Controllers\Admin\LocationSuggestionController;
use App\Http\Controllers\Admin\SubscriptionPlanController;
use App\Http\Controllers\Admin\PaymentController;
use App\Http\Controllers\Admin\SubscriptionController;
use App\Http\Controllers\Admin\JobPostAdminController;
use App\Http\Controllers\Admin\System\SystemConfigController;
use App\Http\Controllers\Admin\System\EmailTemplateController;
use App\Http\Controllers\Admin\System\BackupController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\DashboardController;


/*
|--------------------------------------------------------------------------
| AUTH REDIRECT (DEFAULT LOGIN HANDLER)
|--------------------------------------------------------------------------
*/
Route::get('/admin', function () {
    return redirect()->route('admin.login');
})->name('login');

Route::prefix('admin')->name('admin.')->group(function () {

    // ADMIN AUTH (GUEST ONLY)
    Route::middleware('guest:admin')->group(function () {
        Route::get('/login', [AdminAuthController::class, 'showLogin'])->name('login');
        Route::post('/login', [AdminAuthController::class, 'login'])->name('login.submit');
    });

    // ADMIN PANEL (AUTH + ACTIVE)
    Route::middleware(['auth:admin', 'active:admin'])->group(function () {

        Route::post('/logout', [AdminAuthController::class, 'logout'])->name('logout');
       Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
       Route::get('/dashboard/metrics', [DashboardController::class, 'metrics'])->name('dashboard.metrics');

        // Users
        Route::get('/users', [UserController::class, 'index'])->name('users.index');
        Route::get('/users/{user}', [UserController::class, 'show'])->name('users.show');
        Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
        Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
        Route::patch('/users/{user}/subscription', [UserController::class, 'updateSubscription'])->name('users.subscription');

        // Job posts moderation
        Route::prefix('job-posts')->name('job-posts.')->group(function () {
            Route::get('/', [JobPostAdminController::class, 'index'])->name('index');
            Route::get('/{jobPost}', [JobPostAdminController::class, 'show'])->name('show');
            Route::patch('/{jobPost}/hold', [JobPostAdminController::class, 'hold'])->name('hold');
            Route::patch('/{jobPost}/unhold', [JobPostAdminController::class, 'unhold'])->name('unhold');
            Route::patch('/{jobPost}/disable', [JobPostAdminController::class, 'disable'])->name('disable');
            Route::patch('/{jobPost}/enable', [JobPostAdminController::class, 'enable'])->name('enable');
            Route::patch('/{jobPost}/notes', [JobPostAdminController::class, 'updateNotes'])->name('notes');
        });

        // Status / archive / approval / suspension
        Route::patch('/users/{user}/toggle', [UserController::class, 'toggle'])->name('users.toggle');
        Route::patch('/users/{user}/status', [UserController::class, 'setStatus'])->name('users.status');

        Route::patch('/users/{user}/archive', [UserController::class, 'archive'])->name('users.archive');
        Route::patch('/users/{user}/restore', [UserController::class, 'restore'])->name('users.restore');

        Route::patch('/users/{user}/approve', [UserController::class, 'approve'])->name('users.approve');
        Route::patch('/users/{user}/reject', [UserController::class, 'reject'])->name('users.reject');

        Route::patch('/users/{user}/suspend', [UserController::class, 'suspend'])->name('users.suspend');
        Route::patch('/users/{user}/unsuspend', [UserController::class, 'unsuspend'])->name('users.unsuspend');

        // Static
        Route::view('/jobs', 'adminpage.contents.jobs')->name('jobs');
        Route::view('/billing', 'adminpage.contents.billing')->name('billing');
        /*
|--------------------------------------------------------------------------
| REPORTS
|--------------------------------------------------------------------------
*/

Route::get('/reports', [ReportController::class, 'index'])->name('reports');
Route::post('/reports/generate', [ReportController::class, 'generate'])->name('reports.generate');
Route::get('/reports/export/excel', [ReportController::class, 'exportExcel'])->name('reports.export.excel');
Route::get('/reports/export/pdf', [ReportController::class, 'exportPdf'])->name('reports.export.pdf');
           

        // Subscriptions & payments
        Route::prefix('subscriptions')->name('subscriptions.')->group(function () {
            Route::resource('plans', SubscriptionPlanController::class);

            Route::get('payments', [PaymentController::class, 'index'])->name('payments.index');
            Route::get('payments/{payment}', [PaymentController::class, 'show'])->name('payments.show');
            Route::post('payments/{payment}/complete', [PaymentController::class, 'markCompleted'])->name('payments.complete');
            Route::post('payments/{payment}/fail', [PaymentController::class, 'markFailed'])->name('payments.fail');
            Route::get('/', [SubscriptionController::class, 'index'])->name('index');
            Route::get('expired', [SubscriptionController::class, 'expired'])->name('expired');

            Route::post('{subscription}/activate', [SubscriptionController::class, 'activate'])->name('activate');
            Route::post('{subscription}/suspend', [SubscriptionController::class, 'suspend'])->name('suspend');
            Route::post('{subscription}/remind', [SubscriptionController::class, 'sendExpiredReminder'])->name('remind');
        });

        // Industries
        Route::get('/industries', [IndustryController::class, 'index'])->name('industries.index');
        Route::post('/industries', [IndustryController::class, 'store'])->name('industries.store');
        Route::get('/industries/{industry}/edit', [IndustryController::class, 'edit'])->name('industries.edit');
        Route::put('/industries/{industry}', [IndustryController::class, 'update'])->name('industries.update');
        Route::delete('/industries/{industry}', [IndustryController::class, 'destroy'])->name('industries.destroy');
        Route::patch('/industries/{industry}/meta', [IndustryController::class, 'updateMeta'])->name('industries.meta');

        // Skills
        Route::get('/skills', [SkillController::class, 'index'])->name('skills.index');
        Route::post('/skills', [SkillController::class, 'store'])->name('skills.store');
        Route::get('/skills/{skill}/edit', [SkillController::class, 'edit'])->name('skills.edit');
        Route::put('/skills/{skill}', [SkillController::class, 'update'])->name('skills.update');
        Route::delete('/skills/{skill}', [SkillController::class, 'destroy'])->name('skills.destroy');
        Route::patch('/skills/{skill}/meta', [SkillController::class, 'updateMeta'])->name('skills.meta');

        // Locations
        Route::prefix('locations')->name('locations.')->group(function () {
            Route::get('/countries', [CountryController::class, 'index'])->name('countries.index');
            Route::post('/countries', [CountryController::class, 'store'])->name('countries.store');
            Route::get('/countries/{country}/edit', [CountryController::class, 'edit'])->name('countries.edit');
            Route::put('/countries/{country}', [CountryController::class, 'update'])->name('countries.update');
            Route::delete('/countries/{country}', [CountryController::class, 'destroy'])->name('countries.destroy');
            Route::patch('/countries/{country}/meta', [CountryController::class, 'updateMeta'])->name('countries.meta');

            Route::get('/countries/{country}/cities', [CityController::class, 'index'])->name('cities.index');
            Route::post('/countries/{country}/cities', [CityController::class, 'store'])->name('cities.store');
            Route::get('/countries/{country}/cities/{city}/edit', [CityController::class, 'edit'])->name('cities.edit');
            Route::put('/countries/{country}/cities/{city}', [CityController::class, 'update'])->name('cities.update');
            Route::delete('/countries/{country}/cities/{city}', [CityController::class, 'destroy'])->name('cities.destroy');
            Route::patch('/countries/{country}/cities/{city}/meta', [CityController::class, 'updateMeta'])->name('cities.meta');

            Route::get('/countries/{country}/cities/{city}/areas', [AreaController::class, 'index'])->name('areas.index');
            Route::post('/countries/{country}/cities/{city}/areas', [AreaController::class, 'store'])->name('areas.store');
            Route::get('/countries/{country}/cities/{city}/areas/{area}/edit', [AreaController::class, 'edit'])->name('areas.edit');
            Route::put('/countries/{country}/cities/{city}/areas/{area}', [AreaController::class, 'update'])->name('areas.update');
            Route::delete('/countries/{country}/cities/{city}/areas/{area}', [AreaController::class, 'destroy'])->name('areas.destroy');
            Route::patch('/countries/{country}/cities/{city}/areas/{area}/meta', [AreaController::class, 'updateMeta'])->name('areas.meta');
        });

        // Location suggestions
        Route::get('/location-suggestions', [LocationSuggestionController::class, 'index'])->name('location_suggestions.index');
        Route::put('/location-suggestions/{suggestion}', [LocationSuggestionController::class, 'update'])->name('location_suggestions.update');
        Route::delete('/location-suggestions/{suggestion}', [LocationSuggestionController::class, 'destroy'])->name('location_suggestions.destroy');
        Route::patch('/location-suggestions/{suggestion}/approve', [LocationSuggestionController::class, 'approve'])->name('location_suggestions.approve');
    });

    // SUPERADMIN ONLY
    Route::middleware(['auth:admin', 'active:admin', 'role:superadmin'])->group(function () {
             /*
        |------------------------------------------------------------------
        | SYSTEM CONFIGURATION (Workabroad)
        |------------------------------------------------------------------
        */
        Route::prefix('system')->name('system.')->group(function () {
            Route::get('/', [SystemConfigController::class, 'index'])->name('index');

            Route::put('/general', [SystemConfigController::class, 'updateGeneral'])->name('general.update');
            Route::put('/notifications', [SystemConfigController::class, 'updateNotifications'])->name('notifications.update');
            Route::put('/security', [SystemConfigController::class, 'updateSecurity'])->name('security.update');
        });

        Route::prefix('email-templates')->name('email_templates.')->group(function () {
            Route::get('/', [EmailTemplateController::class, 'index'])->name('index');
            Route::get('/{emailTemplate}/edit', [EmailTemplateController::class, 'edit'])->name('edit');
            Route::put('/{emailTemplate}', [EmailTemplateController::class, 'update'])->name('update');
            Route::get('/{emailTemplate}/preview', [EmailTemplateController::class, 'preview'])->name('preview');
        });

       Route::prefix('backups')->name('backups.')->group(function () {
    Route::get('/', [BackupController::class, 'index'])->name('index');
    Route::post('/run', [BackupController::class, 'run'])->name('run');

    
    Route::post('/restore', [BackupController::class, 'restore'])
        ->middleware(['permission:backups.restore', 'password.confirm:admin'])
        ->name('restore');
});
        Route::get('/admins', [AdminUserController::class, 'index'])->name('admins.index');
        Route::get('/admins/create', [AdminUserController::class, 'create'])->name('admins.create');
        Route::post('/admins', [AdminUserController::class, 'store'])->name('admins.store');
        Route::get('/admins/{user}/edit', [AdminUserController::class, 'edit'])->name('admins.edit');
        Route::put('/admins/{user}', [AdminUserController::class, 'update'])->name('admins.update');

        Route::patch('/admins/{user}/toggle', [AdminUserController::class, 'toggle'])->name('admins.toggle');
        Route::patch('/admins/{user}/status', [AdminUserController::class, 'setStatus'])->name('admins.status');
        Route::post('/admins/{user}/reset-password', [AdminUserController::class, 'resetPassword'])->name('admins.reset_password');
        Route::patch('/admins/{user}/archive', [AdminUserController::class, 'archive'])->name('admins.archive');
        Route::patch('/admins/{user}/restore', [AdminUserController::class, 'restore'])->name('admins.restore');
    });
});