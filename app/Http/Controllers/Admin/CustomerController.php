<?php

namespace App\Http\Controllers\Admin;

use App\Enums\RentalStatus;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Contracts\View\View;

class CustomerController extends Controller
{
    public function index(): View
    {
        return view('admin.customers.index', [
            'customers' => User::query()
                ->where('role', 'customer')
                ->withCount([
                    'rentals',
                    'rentals as active_rentals_count' => fn ($query) => $query->whereIn('status', [RentalStatus::Pending->value, RentalStatus::Active->value]),
                    'rentals as completed_rentals_count' => fn ($query) => $query->where('status', RentalStatus::Completed->value),
                ])
                ->withSum('rentals as total_spent', 'total_price')
                ->latest()
                ->get(),
        ]);
    }
}
