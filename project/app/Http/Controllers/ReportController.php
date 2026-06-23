<?php

namespace App\Http\Controllers;

use App\Services\ReportService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function __construct(private readonly ReportService $reportService)
    {
    }

    public function index(Request $request): View
    {
        $month = $request->string('month')->toString() ?: now()->format('Y-m');

        return view('reports.index', [
            'report' => $this->reportService->monthlyReport($month),
            'selectedMonth' => $month,
        ]);
    }
}
