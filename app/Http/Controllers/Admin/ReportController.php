<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Admin\ReportRequest;
use App\Services\ReportService;
use App\Exports\ReportExport;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
class ReportController extends Controller
{
    public function index()
    {
        return view('adminpage.contents.reports.index');
    }

    public function generate(ReportRequest $request, ReportService $reportService)
{
    $data = $reportService->generate(
        $request->type,
        $request->date_from,
        $request->date_to
    );

    return view('adminpage.contents.reports.index', [
        'reportData' => $data,
        'filters' => $request->only(['type', 'date_from', 'date_to']),
    ]);
}
   public function exportExcel(ReportRequest $request, ReportService $reportService)
{
    $data = $reportService->generate(
        $request->type,
        $request->date_from,
        $request->date_to
    );

    $fileName = 'report_' . $request->type . '_' . now()->format('Ymd_His') . '.xlsx';

    return Excel::download(new ReportExport($data), $fileName);
}

public function exportPdf(ReportRequest $request, ReportService $reportService)
{
    $data = $reportService->generate(
        $request->type,
        $request->date_from,
        $request->date_to
    );

    $fileName = 'report_' . $request->type . '_' . now()->format('Ymd_His') . '.pdf';

    $pdf = Pdf::loadView('adminpage.contents.reports.pdf', [
        'data' => $data,
    ])->setPaper('a4', 'landscape');

    return $pdf->download($fileName);
}
}