<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Auth\AuthController; // employer/admin login can stay here
use App\Http\Controllers\Candidate\CandidateAuthController;
use App\Http\Controllers\Employer\EmployerAuthController;
use App\Http\Controllers\Employer\EmployerProfileController;
use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Candidate\CandidateProfileController;
use App\Http\Controllers\Candidate\ResumeController;
use App\Http\Controllers\Employer\JobController;
use App\Http\Controllers\Employer\ApplicantController;
use App\Http\Controllers\Candidate\JobBrowseController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Candidate\SavedJobController;
use App\Http\Controllers\Candidate\JobReportController;
use App\Http\Controllers\Candidate\AgencyController;
use App\Http\Controllers\Admin\IndustryController;
use App\Http\Controllers\Admin\SkillController;
use App\Http\Controllers\Admin\CountryController;
use App\Http\Controllers\Admin\CityController;
use App\Http\Controllers\Admin\AreaController;
use App\Http\Controllers\Admin\LocationSuggestionController;
use App\Http\Controllers\Admin\SubscriptionPlanController;
use App\Http\Controllers\Admin\PaymentController;
use App\Http\Controllers\Admin\SubscriptionController;
/*
|--------------------------------------------------------------------------
| PUBLIC
|--------------------------------------------------------------------------
*/

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::view('/search-jobs', 'mainpage.search-jobs-page.search-jobs')
    ->name('search-jobs');

Route::view('/search-agency', 'mainpage.search-jobs-page.search-agency')
    ->name('search-agency');

Route::view('/search-industries', 'mainpage.search-jobs-page.search-industries')
    ->name('search-industries');

Route::view('/search-country', 'mainpage.search-jobs-page.search-country')
    ->name('search-country');


Route::get('/jobs', [JobBrowseController::class, 'index'])->name('jobs.index');
Route::get('/jobs/{job}', [JobBrowseController::class, 'show'])->name('jobs.show');
Route::get('/agency/{employerProfile}/jobs', [AgencyController::class, 'jobs'])
    ->name('agency.jobs');
Route::get('/agencies/{employerProfile}', [AgencyController::class, 'show'])
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

Route::post('/employer/logout', [EmployerAuthController::class, 'logout'])
    ->middleware('auth')
    ->name('employer.logout');




/*
|--------------------------------------------------------------------------
| CANDIDATE PAGES (AUTH + ROLE)
|--------------------------------------------------------------------------
*/
Route::prefix('candidate')->name('candidate.')->middleware(['auth', 'role:candidate'])->group(function () {
    Route::get('/', fn() => view('candidate.layout'))->name('index');

    Route::get('/home', fn() => view('candidate.contents.home'))->name('home');
    Route::get('/dashboard', fn() => view('candidate.contents.dashboard'))->name('dashboard');
    Route::get('/profile', [CandidateProfileController::class, 'show'])->name('profile.show');
    Route::patch('/profile', [CandidateProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [CandidateProfileController::class, 'updatePassword'])->name('profile.password');
    Route::post('/profile/email/verify', [CandidateProfileController::class, 'verifyEmailCode'])->name('profile.email.verify');
    Route::post('/profile/email/resend', [CandidateProfileController::class, 'resendEmailCode'])->name('profile.email.resend');
    // Save/Unsave
    Route::post('/jobs/{job}/save', [SavedJobController::class, 'toggle'])
        ->name('jobs.save');

    // Optional saved list
    Route::get('/saved-jobs', [SavedJobController::class, 'index'])
        ->name('candidate.saved.index');

    // Report
    Route::get('/jobs/{job}/report', [JobReportController::class, 'create'])
        ->name('jobs.report');

    Route::post('/jobs/{job}/report', [JobReportController::class, 'store'])
        ->name('jobs.report.store');

    #Resume
    Route::get('/my-resume', [ResumeController::class, 'index'])->name('resume.index');

    // Resume/CV file (single)
    Route::post('/my-resume/resume-file', [ResumeController::class, 'uploadResume'])->name('resume.upload');
    Route::delete('/my-resume/resume-file', [ResumeController::class, 'deleteResume'])->name('resume.delete');

    // Attachments (multiple)
    Route::post('/my-resume/attachments', [ResumeController::class, 'uploadAttachments'])->name('resume.attachments.upload');
    Route::delete('/my-resume/attachments/{attachment}', [ResumeController::class, 'deleteAttachment'])->name('resume.attachments.delete');

    // Experience
    Route::post('/my-resume/experience', [ResumeController::class, 'storeExperience'])->name('resume.exp.store');
    Route::delete('/my-resume/experience/{experience}', [ResumeController::class, 'deleteExperience'])->name('resume.exp.delete');

    // Education
    Route::post('/my-resume/education', [ResumeController::class, 'storeEducation'])->name('resume.edu.store');
    Route::delete('/my-resume/education/{education}', [ResumeController::class, 'deleteEducation'])->name('resume.edu.delete');

    Route::get('/my-applied-jobs', fn() => view('candidate.contents.my-applied-jobs'))->name('my-applied-jobs');
    Route::get('/shortlist-jobs', fn() => view('candidate.contents.shortlist-jobs'))->name('shortlist-jobs');
    Route::get('/following-employers', fn() => view('candidate.contents.following-employers'))->name('following-employers');
    Route::get('/job-alerts', fn() => view('candidate.contents.job-alerts'))->name('job-alerts');
    Route::get('/messages', fn() => view('candidate.contents.messages'))->name('messages');
    Route::get('/meetings', fn() => view('candidate.contents.meetings'))->name('meetings');
    Route::get('/delete-profile', fn() => view('candidate.contents.delete-profile'))->name('delete-profile');
});

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
    Route::get('/subscription', fn() => view('employer.contents.subscription'))->name('subscription');

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
    Route::get('/geo/cities', [JobController::class, 'citiesByCountry'])
        ->name('geo.cities');

    Route::get('/geo/areas', [JobController::class, 'areasByCity'])
        ->name('geo.areas');

    // Unified applicant route with optional status filter
    Route::get('/applicants', [ApplicantController::class, 'index'])->name('applicants.index');

    // Route::get('/{candidate}', [ApplicantController::class, 'show'])->name('aplicants.show'); // view applicant

    // Status updates
    Route::put('/{candidate}/shortlist', [ApplicantController::class, 'shortlist'])->name('applicants.shortlist');
    Route::put('/{candidate}/interview', [ApplicantController::class, 'interview'])->name('applicants.interview');
    Route::put('/{candidate}/hire', [ApplicantController::class, 'hire'])->name('applicants.hire');
    Route::put('/{candidate}/reject', [ApplicantController::class, 'reject'])->name('applicants.reject');

    Route::get('/applicants/export', [ApplicantController::class, 'export'])->name('applicants.export');
});

/*
|--------------------------------------------------------------------------
| AUTH REDIRECT (DEFAULT LOGIN HANDLER)
|--------------------------------------------------------------------------
| Required by Laravel auth middleware.
| Redirects unauthenticated users to admin login page.
*/
Route::get('/login', function () {
    return redirect()->route('admin.login');
})->name('login');

Route::prefix('admin')->name('admin.')->group(function () {

    /*
    |--------------------------------------------------------------------------
    | ADMIN AUTH (GUEST ONLY)
    |--------------------------------------------------------------------------
    */
    Route::middleware('guest:admin')->group(function () {
        Route::get('/login', [AdminAuthController::class, 'showLogin'])->name('login');
        Route::post('/login', [AdminAuthController::class, 'login'])->name('login.submit');
    });

    /*
    |--------------------------------------------------------------------------
    | ADMIN PANEL (AUTH + ACTIVE)
    |--------------------------------------------------------------------------
    */
    Route::middleware(['auth:admin', 'active:admin'])->group(function () {

        // logout
        Route::post('/logout', [AdminAuthController::class, 'logout'])->name('logout');

        // dashboard
        Route::view('/', 'adminpage.contents.dashboard')->name('dashboard');

        /*
        |--------------------------------------------------------------------------
        | MANAGE USERS (Candidates + Employers)
        |--------------------------------------------------------------------------
        */
        Route::get('/users', [UserController::class, 'index'])->name('users.index');
        Route::get('/users/{user}', [UserController::class, 'show'])->name('users.show');
        Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
        Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');

        Route::patch('/users/{user}/subscription', [UserController::class, 'updateSubscription'])
            ->name('users.subscription');

        // Status controls
        Route::patch('/users/{user}/toggle', [UserController::class, 'toggle'])->name('users.toggle');
        Route::patch('/users/{user}/status', [UserController::class, 'setStatus'])->name('users.status');

        // Archive controls
        Route::patch('/users/{user}/archive', [UserController::class, 'archive'])->name('users.archive');
        Route::patch('/users/{user}/restore', [UserController::class, 'restore'])->name('users.restore');

        // Employer approval workflow
        Route::patch('/users/{user}/approve', [UserController::class, 'approve'])->name('users.approve');
        Route::patch('/users/{user}/reject', [UserController::class, 'reject'])->name('users.reject');

        /*
        |--------------------------------------------------------------------------
        | ADMIN STATIC PAGES
        |--------------------------------------------------------------------------
        */
        Route::view('/jobs', 'adminpage.contents.jobs')->name('jobs');
        Route::view('/billing', 'adminpage.contents.billing')->name('billing');
        Route::view('/reports', 'adminpage.contents.reports')->name('reports');
        Route::view('/settings', 'adminpage.contents.settings')->name('settings');

        /*
        |--------------------------------------------------------------------------
        | ✅ SUBSCRIPTION & PAYMENT MANAGEMENT
        |--------------------------------------------------------------------------
        */
        Route::prefix('subscriptions')->name('subscriptions.')->group(function () {

            // Plans (CRUD)
            Route::resource('plans', SubscriptionPlanController::class);

            // Payments
            Route::get('payments', [PaymentController::class, 'index'])->name('payments.index');
            Route::get('payments/{payment}', [PaymentController::class, 'show'])->name('payments.show');
            Route::post('payments/{payment}/complete', [PaymentController::class, 'markCompleted'])->name('payments.complete');
            Route::post('payments/{payment}/fail', [PaymentController::class, 'markFailed'])->name('payments.fail');

            // Employer Subscriptions
            Route::get('/', [SubscriptionController::class, 'index'])->name('index');
            Route::get('expired', [SubscriptionController::class, 'expired'])->name('expired');

            Route::post('{subscription}/activate', [SubscriptionController::class, 'activate'])->name('activate');
            Route::post('{subscription}/suspend', [SubscriptionController::class, 'suspend'])->name('suspend');

            // Expired reminders
            Route::post('{subscription}/remind', [SubscriptionController::class, 'sendExpiredReminder'])->name('remind');
        });

        /*
        |--------------------------------------------------------------------------
        | INDUSTRIES (Job Categories)
        |--------------------------------------------------------------------------
        */
        Route::get('/industries', [IndustryController::class, 'index'])->name('industries.index');
        Route::post('/industries', [IndustryController::class, 'store'])->name('industries.store');
        Route::get('/industries/{industry}/edit', [IndustryController::class, 'edit'])->name('industries.edit');
        Route::put('/industries/{industry}', [IndustryController::class, 'update'])->name('industries.update');
        Route::delete('/industries/{industry}', [IndustryController::class, 'destroy'])->name('industries.destroy');
        Route::patch('/industries/{industry}/meta', [IndustryController::class, 'updateMeta'])->name('industries.meta');

        /*
        |--------------------------------------------------------------------------
        | SKILLS
        |--------------------------------------------------------------------------
        */
        Route::get('/skills', [SkillController::class, 'index'])->name('skills.index');
        Route::post('/skills', [SkillController::class, 'store'])->name('skills.store');
        Route::get('/skills/{skill}/edit', [SkillController::class, 'edit'])->name('skills.edit');
        Route::put('/skills/{skill}', [SkillController::class, 'update'])->name('skills.update');
        Route::delete('/skills/{skill}', [SkillController::class, 'destroy'])->name('skills.destroy');
        Route::patch('/skills/{skill}/meta', [SkillController::class, 'updateMeta'])->name('skills.meta');

        /*
        |--------------------------------------------------------------------------
        | LOCATIONS (Countries → Cities → Areas)
        |--------------------------------------------------------------------------
        */
        Route::prefix('locations')->name('locations.')->group(function () {

            // Countries
            Route::get('/countries', [CountryController::class, 'index'])->name('countries.index');
            Route::post('/countries', [CountryController::class, 'store'])->name('countries.store');
            Route::get('/countries/{country}/edit', [CountryController::class, 'edit'])->name('countries.edit');
            Route::put('/countries/{country}', [CountryController::class, 'update'])->name('countries.update');
            Route::delete('/countries/{country}', [CountryController::class, 'destroy'])->name('countries.destroy');
            Route::patch('/countries/{country}/meta', [CountryController::class, 'updateMeta'])->name('countries.meta');

            // Cities
            Route::get('/countries/{country}/cities', [CityController::class, 'index'])->name('cities.index');
            Route::post('/countries/{country}/cities', [CityController::class, 'store'])->name('cities.store');
            Route::get('/countries/{country}/cities/{city}/edit', [CityController::class, 'edit'])->name('cities.edit');
            Route::put('/countries/{country}/cities/{city}', [CityController::class, 'update'])->name('cities.update');
            Route::delete('/countries/{country}/cities/{city}', [CityController::class, 'destroy'])->name('cities.destroy');
            Route::patch('/countries/{country}/cities/{city}/meta', [CityController::class, 'updateMeta'])->name('cities.meta');

            // Areas
            Route::get('/countries/{country}/cities/{city}/areas', [AreaController::class, 'index'])->name('areas.index');
            Route::post('/countries/{country}/cities/{city}/areas', [AreaController::class, 'store'])->name('areas.store');
            Route::get('/countries/{country}/cities/{city}/areas/{area}/edit', [AreaController::class, 'edit'])->name('areas.edit');
            Route::put('/countries/{country}/cities/{city}/areas/{area}', [AreaController::class, 'update'])->name('areas.update');
            Route::delete('/countries/{country}/cities/{city}/areas/{area}', [AreaController::class, 'destroy'])->name('areas.destroy');
            Route::patch('/countries/{country}/cities/{city}/areas/{area}/meta', [AreaController::class, 'updateMeta'])->name('areas.meta');
        });

        /*
        |--------------------------------------------------------------------------
        | LOCATION SUGGESTIONS
        |--------------------------------------------------------------------------
        */
        Route::get('/location-suggestions', [LocationSuggestionController::class, 'index'])
            ->name('location_suggestions.index');

        Route::put('/location-suggestions/{suggestion}', [LocationSuggestionController::class, 'update'])
            ->name('location_suggestions.update');

        Route::delete('/location-suggestions/{suggestion}', [LocationSuggestionController::class, 'destroy'])
            ->name('location_suggestions.destroy');

        // Location suggestions approve flow
        Route::patch('/location-suggestions/{suggestion}/approve', [LocationSuggestionController::class, 'approve'])
            ->name('location_suggestions.approve');

        // Quick meta updates
        Route::patch('/industries/{industry}/meta', [IndustryController::class, 'updateMeta'])->name('industries.meta');
        Route::patch('/skills/{skill}/meta', [SkillController::class, 'updateMeta'])->name('skills.meta');

        Route::prefix('locations')->name('locations.')->group(function () {
            Route::patch('/countries/{country}/meta', [CountryController::class, 'updateMeta'])->name('countries.meta');
            Route::patch('/countries/{country}/cities/{city}/meta', [CityController::class, 'updateMeta'])->name('cities.meta');
            Route::patch('/countries/{country}/cities/{city}/areas/{area}/meta', [AreaController::class, 'updateMeta'])->name('areas.meta');
        });
    });

    /*
    |--------------------------------------------------------------------------
    | SUPERADMIN ONLY — ADMIN ACCOUNTS
    |--------------------------------------------------------------------------
    */
    Route::middleware(['auth:admin', 'active:admin', 'role:superadmin'])->group(function () {

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
