<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Candidate\CandidateAuthController;
use App\Http\Controllers\Candidate\CandidateProfileController;
use App\Http\Controllers\Candidate\ResumeController;
use App\Http\Controllers\Candidate\SavedJobController;
use App\Http\Controllers\Candidate\JobReportController;
use App\Http\Controllers\Candidate\JobApplicationController;

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
    Route::post('/jobs/{job}/save', [SavedJobController::class, 'toggle'])->name('jobs.save');
    Route::get('/saved-jobs', [SavedJobController::class, 'index'])->name('candidate.saved.index');

    // Report
    Route::get('/jobs/{job}/report', [JobReportController::class, 'create'])->name('jobs.report');
    Route::post('/jobs/{job}/report', [JobReportController::class, 'store'])->name('jobs.report.store');

    // Resume
    Route::get('/my-resume', [ResumeController::class, 'index'])->name('resume.index');
    Route::post('/my-resume/resume-file', [ResumeController::class, 'uploadResume'])->name('resume.upload');
    Route::delete('/my-resume/resume-file', [ResumeController::class, 'deleteResume'])->name('resume.delete');

    Route::post('/my-resume/attachments', [ResumeController::class, 'uploadAttachments'])->name('resume.attachments.upload');
    Route::delete('/my-resume/attachments/{attachment}', [ResumeController::class, 'deleteAttachment'])->name('resume.attachments.delete');

    Route::post('/my-resume/experience', [ResumeController::class, 'storeExperience'])->name('resume.exp.store');
    Route::delete('/my-resume/experience/{experience}', [ResumeController::class, 'deleteExperience'])->name('resume.exp.delete');

    Route::post('/my-resume/education', [ResumeController::class, 'storeEducation'])->name('resume.edu.store');
    Route::delete('/my-resume/education/{education}', [ResumeController::class, 'deleteEducation'])->name('resume.edu.delete');

    // Apply
    Route::post('/jobs/{job}/apply', [JobApplicationController::class, 'store'])->name('jobs.apply');

    // Static views
    Route::get('/my-applied-jobs', fn() => view('candidate.contents.my-applied-jobs'))->name('my-applied-jobs');
    Route::get('/shortlist-jobs', fn() => view('candidate.contents.shortlist-jobs'))->name('shortlist-jobs');
    Route::get('/following-employers', fn() => view('candidate.contents.following-employers'))->name('following-employers');
    Route::get('/job-alerts', fn() => view('candidate.contents.job-alerts'))->name('job-alerts');
    Route::get('/messages', fn() => view('candidate.contents.messages'))->name('messages');
    Route::get('/meetings', fn() => view('candidate.contents.meetings'))->name('meetings');
    Route::get('/delete-profile', fn() => view('candidate.contents.delete-profile'))->name('delete-profile');
});