<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Services\ReportService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __construct(private readonly ReportService $reportService)
    {
    }

    public function index(Request $request): View
    {
        $selectedMonth = $request->string('month')->toString() ?: now()->format('Y-m');
        $summary = $this->reportService->ownerSummary();
        $summary['monthly_report'] = $this->reportService->monthlyReport($selectedMonth);
        $summary['selected_month'] = $selectedMonth;

        return view('owner.dashboard.index', [
            'summary' => $summary,
        ]);
    }
}
