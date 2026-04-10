<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Rental;
use App\Enums\RentalStatus;
use App\Services\ReportService;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;

class DashboardController extends Controller
{
    public function __construct(private readonly ReportService $reportService)
    {
    }

    public function index(): View
    {
        $notifications = collect();

        $pendingRentals = Rental::query()
            ->with(['user', 'details.costume'])
            ->where('status', RentalStatus::Pending->value)
            ->latest()
            ->get();

        foreach ($pendingRentals as $rental) {
            $costumes = $rental->details->pluck('costume.name')->join(', ');
            $date = $rental->usage_date->translatedFormat('j M Y');
            $notifications->push([
                'icon' => 'bi-bag-fill',
                'bg_color' => 'bg-danger-subtle text-danger',
                'title' => 'Permintaan Booking Baru',
                'message' => "{$rental->user->name} mengajukan sewa \"{$costumes}\" untuk tanggal {$date}.",
                'time' => $rental->created_at->locale(app()->getLocale())->diffForHumans(),
                'created_at' => $rental->created_at,
            ]);
        }

        $activeRentals = Rental::query()
            ->with(['user', 'details.costume'])
            ->where('status', RentalStatus::Active->value)
            ->where('return_date', '<=', now()->addDays(2))
            ->latest()
            ->get();

        foreach ($activeRentals as $rental) {
            $costumes = $rental->details->pluck('costume.name')->join(', ');
            $notifications->push([
                'icon' => 'bi-box-seam-fill',
                'bg_color' => 'bg-secondary-subtle text-secondary',
                'title' => 'Pengembalian Segera Jatuh Tempo',
                'message' => "{$rental->user->name} dijadwalkan mengembalikan \"{$costumes}\" paling lambat {$rental->return_due_date->translatedFormat('j M Y')}.",
                'time' => $rental->return_due_date->locale(app()->getLocale())->diffForHumans(),
                'created_at' => $rental->return_due_date,
            ]);
        }

        $notifications = $notifications->sortByDesc('created_at')->take(5);

        return view('admin.dashboard.index', [
            'summary' => $this->reportService->adminSummary(),
            'recentRentals' => Rental::query()
                ->with(['user', 'details.costume', 'payment', 'returnRecord'])
                ->latest()
                ->take(6)
                ->get(),
            'notifications' => $notifications,
        ]);
    }
}
