<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Candidate\CandidateAuthController;
use App\Http\Controllers\Candidate\CandidateProfileController;
use App\Http\Controllers\Candidate\ResumeController;
use App\Http\Controllers\Candidate\SavedJobController;
use App\Http\Controllers\Candidate\JobReportController;
use App\Http\Controllers\Candidate\JobApplicationController;
use App\Http\Controllers\Candidate\AppliedJobsController;
use App\Http\Controllers\Candidate\FollowingEmployerController;
use App\Http\Controllers\Candidate\DashboardController;
use App\Http\Controllers\Candidate\CandidateChatController;

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

    Route::post('/verify-email', [CandidateAuthController::class, 'verifyEmailCode'])->name('verify.email');
    Route::post('/resend-verification', [CandidateAuthController::class, 'resendEmailCode'])->name('verify.resend');

});

Route::post('/candidate/logout', [CandidateAuthController::class, 'logout'])
    ->middleware('auth')
    ->name('candidate.logout');


/*
|--------------------------------------------------------------------------
| CANDIDATE PAGES (AUTH + ROLE)
|--------------------------------------------------------------------------
*/

Route::prefix('candidate')
    ->name('candidate.')
    ->middleware(['auth', 'role:candidate', 'check.user.status'])
    ->group(function () {

        /*
        |--------------------------------------------------------------------------
        | Dashboard
        |--------------------------------------------------------------------------
        */

        Route::get('/dashboard', [DashboardController::class, 'index'])
            ->name('dashboard');


        /*
        |--------------------------------------------------------------------------
        | Profile
        |--------------------------------------------------------------------------
        */

        Route::get('/profile', [CandidateProfileController::class, 'show'])->name('profile.show');
        Route::patch('/profile', [CandidateProfileController::class, 'update'])->name('profile.update');
        Route::put('/profile/password', [CandidateProfileController::class, 'updatePassword'])->name('profile.password');

        Route::post('/profile/email/verify', [CandidateProfileController::class, 'verifyEmailCode'])->name('profile.email.verify');
        Route::post('/profile/email/resend', [CandidateProfileController::class, 'resendEmailCode'])->name('profile.email.resend');


        /*
        |--------------------------------------------------------------------------
        | Resume
        |--------------------------------------------------------------------------
        */

        Route::get('/my-resume', [ResumeController::class, 'index'])->name('resume.index');

        Route::post('/my-resume/resume-file', [ResumeController::class, 'uploadResume'])->name('resume.upload');
        Route::delete('/my-resume/resume-file', [ResumeController::class, 'deleteResume'])->name('resume.delete');

        Route::post('/my-resume/attachments', [ResumeController::class, 'uploadAttachments'])->name('resume.attachments.upload');
        Route::delete('/my-resume/attachments/{attachment}', [ResumeController::class, 'deleteAttachment'])->name('resume.attachments.delete');

        Route::post('/my-resume/experience', [ResumeController::class, 'storeExperience'])->name('resume.exp.store');
        Route::delete('/my-resume/experience/{experience}', [ResumeController::class, 'deleteExperience'])->name('resume.exp.delete');

        Route::post('/my-resume/education', [ResumeController::class, 'storeEducation'])->name('resume.edu.store');
        Route::delete('/my-resume/education/{education}', [ResumeController::class, 'deleteEducation'])->name('resume.edu.delete');


        /*
        |--------------------------------------------------------------------------
        | Job Actions
        |--------------------------------------------------------------------------
        */

        // Save / Unsave
        Route::post('/jobs/{job}/save', [SavedJobController::class, 'toggle'])
            ->name('jobs.save');

        // Apply
        Route::post('/jobs/{job}/apply', [JobApplicationController::class, 'store'])
            ->name('jobs.apply');

        // Report Job
        Route::get('/jobs/{job}/report', [JobReportController::class, 'create'])
            ->name('jobs.report');

        Route::post('/jobs/{job}/report', [JobReportController::class, 'store'])
            ->name('jobs.report.store');


        /*
        |--------------------------------------------------------------------------
        | Job Pages
        |--------------------------------------------------------------------------
        */

        Route::get('/applied-jobs', function () {
            return view('candidate.contents.my-applied-jobs');
        })->name('applied.jobs');

        Route::get('/applied-jobs/data', [AppliedJobsController::class, 'index'])
            ->name('applied.jobs.data');


        Route::get('/saved-jobs', [SavedJobController::class, 'index'])
            ->name('saved.jobs');

        Route::delete('/saved-jobs/{job}', [SavedJobController::class, 'destroy'])
            ->name('saved.jobs.delete');


        /*
        |--------------------------------------------------------------------------
        | Following Employers
        |--------------------------------------------------------------------------
        */

        Route::get('/following-employers', [FollowingEmployerController::class, 'index'])
            ->name('following.employers');


        /*
        |--------------------------------------------------------------------------
        | Other Pages
        |--------------------------------------------------------------------------
        */

        Route::get('/chat/{application?}', [CandidateChatController::class, 'index'])
            ->name('chat.index');

        Route::post('/chat/{application}', [CandidateChatController::class, 'store'])
            ->name('chat.store');


        Route::get('/meetings', fn() => view('candidate.contents.meetings'))->name('meetings');
        Route::get('/delete-profile', fn() => view('candidate.contents.delete-profile'))->name('delete-profile');

    });
