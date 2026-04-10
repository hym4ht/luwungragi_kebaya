<?php

namespace App\Http\Controllers\Customer;

use App\Enums\PaymentStatus;
use App\Enums\RentalStatus;
use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request): View
    {
        $rentals = $request->user()
            ->rentals()
            ->with(['details.costume', 'payment', 'returnRecord'])
            ->latest()
            ->get();

        return view('customer.dashboard.index', [
            'rentals' => $rentals,
            'summary' => [
                'active' => $rentals->filter(fn ($rental) => $rental->status === RentalStatus::Active)->count(),
                'pending' => $rentals->filter(fn ($rental) => $rental->status === RentalStatus::Pending)->count(),
                'completed' => $rentals->filter(fn ($rental) => $rental->status === RentalStatus::Completed)->count(),
                'total_spent' => $rentals
                    ->filter(fn ($rental) => $rental->payment?->status === PaymentStatus::Settlement)
                    ->sum('total_price'),
            ],
        ]);
    }
}
