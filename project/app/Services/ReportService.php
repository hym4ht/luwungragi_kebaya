<?php

namespace App\Services;

use App\Enums\PaymentStatus;
use App\Enums\RentalStatus;
use App\Models\Costume;
use App\Models\Payment;
use App\Models\Rental;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class ReportService
{
    public function adminSummary(): array
    {
        $upcomingReturns = Rental::query()
            ->with(['user', 'details.costume', 'payment'])
            ->whereIn('status', [RentalStatus::Pending->value, RentalStatus::Active->value])
            ->orderBy('return_date')
            ->take(5)
            ->get();

        return [
            'costumes_count' => Costume::query()->count(),
            'pending_payments' => Payment::query()->where('status', PaymentStatus::Pending->value)->count(),
            'active_rentals' => Rental::query()->where('status', RentalStatus::Active->value)->count(),
            'monthly_revenue' => (float) Payment::query()
                ->where('status', PaymentStatus::Settlement->value)
                ->whereBetween('paid_at', [now()->startOfMonth(), now()->endOfMonth()])
                ->with('rental.returnRecord')
                ->get()
                ->sum(fn (Payment $payment) => (float) $payment->rental->total_price + (float) ($payment->rental->returnRecord->fine_amount ?? 0)),
            'upcoming_returns' => $upcomingReturns,
        ];
    }

    public function ownerSummary(): array
    {
        $monthlyReport = $this->monthlyReport(now()->format('Y-m'));
        $recentMonths = collect(range(5, 1))->map(function (int $monthsAgo): array {
            $date = now()->subMonths($monthsAgo)->startOfMonth();

            return [
                'value' => $date->format('Y-m'),
                'label' => $date->translatedFormat('M Y'),
            ];
        })->push([
            'value' => now()->format('Y-m'),
            'label' => now()->translatedFormat('M Y'),
        ]);

        $revenueTrend = Payment::query()
            ->with('rental.returnRecord')
            ->where('status', PaymentStatus::Settlement->value)
            ->whereBetween('paid_at', [now()->subMonths(5)->startOfMonth(), now()->endOfMonth()])
            ->get()
            ->groupBy(fn (Payment $payment) => optional($payment->paid_at)->format('Y-m'))
            ->map(function (Collection $payments, string $month): array {
                return [
                    'month' => $month,
                    'label' => Carbon::createFromFormat('Y-m', $month)->translatedFormat('M Y'),
                    'total' => $payments->sum(fn (Payment $payment) => (float) $payment->rental->total_price + (float) ($payment->rental->returnRecord->fine_amount ?? 0)),
                ];
            })
            ->values();

        return [
            'total_customers' => User::query()->where('role', 'customer')->count(),
            'completed_rentals' => Rental::query()->where('status', RentalStatus::Completed->value)->count(),
            'pending_verifications' => Payment::query()->where('status', PaymentStatus::Pending->value)->count(),
            'all_time_revenue' => (float) Payment::query()
                ->with('rental.returnRecord')
                ->where('status', PaymentStatus::Settlement->value)
                ->get()
                ->sum(fn (Payment $payment) => (float) $payment->rental->total_price + (float) ($payment->rental->returnRecord->fine_amount ?? 0)),
            'monthly_report' => $monthlyReport,
            'months' => $recentMonths,
            'revenue_trend' => $revenueTrend,
        ];
    }

    public function monthlyReport(?string $month = null): array
    {
        $selectedMonth = $month ?: now()->format('Y-m');
        $referenceDate = Carbon::createFromFormat('Y-m', $selectedMonth)->startOfMonth();
        $start = $referenceDate->copy()->startOfMonth();
        $end = $referenceDate->copy()->endOfMonth();

        $rentals = Rental::query()
            ->with(['user', 'details.costume', 'payment', 'returnRecord'])
            ->whereBetween('created_at', [$start, $end])
            ->latest()
            ->get();

        $paymentsByMethod = $rentals
            ->filter(fn (Rental $rental) => $rental->payment !== null)
            ->groupBy(fn () => 'Midtrans')
            ->map->count()
            ->sortDesc();

        $topCostumes = $rentals
            ->flatMap->details
            ->groupBy(fn ($detail) => $detail->costume->name)
            ->map(fn (Collection $details) => $details->sum('quantity'))
            ->sortDesc()
            ->take(5);

        return [
            'selected_month' => $referenceDate,
            'rentals' => $rentals,
            'total_transactions' => $rentals->count(),
            'active_transactions' => $rentals->filter(fn (Rental $rental) => $rental->status === RentalStatus::Active)->count(),
            'completed_transactions' => $rentals->filter(fn (Rental $rental) => $rental->status === RentalStatus::Completed)->count(),
            'pending_transactions' => $rentals->filter(fn (Rental $rental) => $rental->status === RentalStatus::Pending)->count(),
            'gross_revenue' => $rentals->sum('total_price'),
            'fine_revenue' => $rentals->sum(fn (Rental $rental) => (float) ($rental->returnRecord->fine_amount ?? 0)),
            'payments_by_method' => $paymentsByMethod,
            'top_costumes' => $topCostumes,
        ];
    }
}
