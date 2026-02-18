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

    // ✅ View profile (default)
    Route::get('/company-profile', [EmployerProfileController::class, 'show'])->name('company-profile');

    // ✅ Edit form
    Route::get('/company-profile/edit', [EmployerProfileController::class, 'edit'])->name('company-profile.edit');

    // ✅ Save update
    Route::post('/company-profile', [EmployerProfileController::class, 'update'])->name('company-profile.update');

    // ✅ Delete employer account
    Route::delete('/delete-account', [EmployerProfileController::class, 'deleteAccount'])->name('delete-account');
    Route::get('/analytics', fn() => view('employer.contents.analytics'))->name('analytics');
    Route::get('/subscription', fn() => view('employer.contents.subscription'))->name('subscription');

    // Job listings (all or active)
    Route::get('/job-postings', [JobController::class, 'index'])->name('job-postings.index');

    // Job creation
    Route::get('/job-postings/create', [JobController::class, 'create'])->name('job-postings.create');
    Route::post('/job-postings', [JobController::class, 'store'])->name('job-postings.store');

    // Closed jobs
    Route::get('/job-postings/closed', [JobController::class, 'closed'])->name('job-postings.closed');
    Route::put('/job-postings/{job}/reopen', [JobController::class, 'reopen'])->name('job-postings.reopen');

    // Job details / edit / update / delete
    Route::get('/job-postings/{job}', [JobController::class, 'show'])->name('job-postings.show');
    Route::get('/job-postings/{job}/edit', [JobController::class, 'edit'])->name('job-postings.edit');
    Route::put('/job-postings/{job}', [JobController::class, 'update'])->name('job-postings.update');
    Route::delete('/job-postings/{job}', [JobController::class, 'destroy'])->name('job-postings.destroy');

    Route::get('/applicants/all', fn() => view('employer.contents.applicants.all'))->name('applicants.all');
    Route::get('/applicants/shortlisted', fn() => view('employer.contents.applicants.shortlisted'))->name('applicants.shortlisted');
    Route::get('/applicants/rejected', fn() => view('employer.contents.applicants.rejected'))->name('applicants.rejected');
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

    // ADMIN AUTH (GUEST ONLY)
    Route::middleware('guest:admin')->group(function () {
        Route::get('/login', [AdminAuthController::class, 'showLogin'])->name('login');
        Route::post('/login', [AdminAuthController::class, 'login'])->name('login.submit');
    });

    // ADMIN PANEL (AUTH ONLY)
    Route::middleware('auth:admin')->group(function () {

        // logout
        Route::post('/logout', [AdminAuthController::class, 'logout'])->name('logout');

        // dashboard
        Route::view('/', 'adminpage.contents.dashboard')->name('dashboard');

        // Manage Users (admin + superadmin)
        Route::get('/users', [UserController::class, 'index'])->name('users.index');
        Route::get('/users/{user}', [UserController::class, 'show'])->name('users.show');
        Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
        Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
        Route::patch('/users/{user}/toggle', [UserController::class, 'toggle'])->name('users.toggle');
        Route::patch('/users/{user}/approve', [UserController::class, 'approveEmployer'])->name('users.approve');
        Route::patch('/users/{user}/archive', [UserController::class, 'archive'])->name('users.archive');
        Route::patch('/users/{user}/restore', [UserController::class, 'restore'])->name('users.restore');

        // other admin pages
        Route::view('/jobs', 'adminpage.contents.jobs')->name('jobs');
        Route::view('/billing', 'adminpage.contents.billing')->name('billing');
        Route::view('/reports', 'adminpage.contents.reports')->name('reports');
        Route::view('/settings', 'adminpage.contents.settings')->name('settings');
        Route::view('/taxonomy', 'adminpage.contents.taxonomy')->name('taxonomy');
    });

    // SUPERADMIN ONLY: Admin Accounts CRUD
    Route::middleware(['auth:admin', 'role:superadmin'])->group(function () {
        Route::get('/admins', [AdminUserController::class, 'index'])->name('admins.index');
        Route::get('/admins/create', [AdminUserController::class, 'create'])->name('admins.create');
        Route::post('/admins', [AdminUserController::class, 'store'])->name('admins.store');
        Route::get('/admins/{user}/edit', [AdminUserController::class, 'edit'])->name('admins.edit');
        Route::put('/admins/{user}', [AdminUserController::class, 'update'])->name('admins.update');
        Route::patch('/admins/{user}/toggle', [AdminUserController::class, 'toggle'])->name('admins.toggle');
        Route::post('/admins/{user}/reset-password', [AdminUserController::class, 'resetPassword'])->name('admins.reset_password');
        Route::patch('/admins/{user}/archive', [AdminUserController::class, 'archive'])->name('admins.archive');
        Route::patch('/admins/{user}/restore', [AdminUserController::class, 'restore'])->name('admins.restore');
    });
});
