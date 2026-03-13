<?php

namespace App\Services\Employer;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use App\Models\JobApplication;

class ApplicantExportService
{
    public function exportCsv(Request $request)
    {
        $status = $request->query('status','all');

        $applications = JobApplication::query()
            ->with(['jobPost','candidateProfile.user'])
            ->when($status !== 'all',
                fn($q)=>$q->where('status',$status))
            ->latest()
            ->get();

        $csv = "Name,Email,Status,Applied Position\n";

        foreach ($applications as $app) {

            $csv .= sprintf(
                "\"%s\",\"%s\",\"%s\",\"%s\"\n",
                $app->candidateProfile?->user?->name ?? '',
                $app->candidateProfile?->user?->email ?? '',
                ucfirst($app->status),
                $app->jobPost?->title ?? ''
            );
        }

        return Response::make($csv,200,[
            'Content-Type'=>'text/csv',
            'Content-Disposition'=>'attachment; filename=applicants.csv'
        ]);
    }
}