<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\RentalReturn;
use App\Services\ReportService;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function __construct(private readonly ReportService $reportService)
    {
    }

    /**
     * Laporan Keuangan - revenue, fines, charts
     */
    public function financial(Request $request): View
    {
        $selectedMonth = $request->string('month')->toString() ?: now()->format('Y-m');

        return view('owner.reports.financial', [
            'report' => $this->reportService->monthlyReport($selectedMonth),
            'selectedMonth' => $selectedMonth,
        ]);
    }

    /**
     * Riwayat Semua Transaksi
     */
    public function transactions(Request $request): View
    {
        $selectedMonth = $request->string('month')->toString() ?: now()->format('Y-m');

        return view('owner.reports.transactions', [
            'report' => $this->reportService->monthlyReport($selectedMonth),
            'selectedMonth' => $selectedMonth,
        ]);
    }

    /**
     * Busana Terlaris (Top Rented Items)
     */
    public function topItems(Request $request): View
    {
        $selectedMonth = $request->string('month')->toString() ?: now()->format('Y-m');
        $report = $this->reportService->monthlyReport($selectedMonth);

        // Convert top_costumes collection to array with name & quantity
        $topItems = $report['top_costumes']->map(function ($quantity, $name) {
            return ['name' => $name, 'quantity' => $quantity];
        })->values();

        return view('owner.reports.top-items', [
            'topItems' => $topItems,
            'selectedMonth' => $selectedMonth,
        ]);
    }

    /**
     * Riwayat Pengembalian
     */
    public function returns(Request $request): View
    {
        $selectedMonth = $request->string('month')->toString() ?: now()->format('Y-m');
        $start = Carbon::createFromFormat('Y-m', $selectedMonth)->startOfMonth();
        $end = Carbon::createFromFormat('Y-m', $selectedMonth)->endOfMonth();

        $returns = RentalReturn::query()
            ->with(['rental.user', 'rental.details.costume'])
            ->whereBetween('returned_date', [$start, $end])
            ->latest('returned_date')
            ->get();

        return view('owner.reports.returns', [
            'returns' => $returns,
            'selectedMonth' => $selectedMonth,
        ]);
    }
}
