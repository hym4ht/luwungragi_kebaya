<?php

namespace App\Http\Controllers;

use App\Enums\PaymentStatus;
use App\Enums\RentalStatus;
use App\Models\Costume;
use App\Models\Payment;
use App\Models\Rental;
use App\Services\AvailabilityService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class HomeController extends Controller
{
    public function __construct(private readonly AvailabilityService $availabilityService)
    {
    }

    public function index(Request $request): mixed
    {
        $legacyRentalDate = $request->date('rental_date')?->toDateString();
        $defaultEventDate = now()->addDays(Rental::BOOKING_BUFFER_DAYS)->toDateString();

        $filters = [
            'search'      => $request->string('search')->toString(),
            'category'    => $request->string('category')->toString(),
            'event_date'  => $request->date('event_date')?->toDateString()
                ?: ($legacyRentalDate ? Carbon::parse($legacyRentalDate)->addDays(Rental::BOOKING_BUFFER_DAYS)->toDateString() : $defaultEventDate),
        ];

        $allCatalog = $this->availabilityService->getCatalog(
            $filters['event_date'],
            $filters['search'],
            $filters['category'],
        );

        $totalItems = $allCatalog->count();

        $partialData = [
            'catalog'     => $allCatalog,
            'fullCatalog' => $allCatalog,
            'totalItems'  => $totalItems,
            'totalPages'  => 1,
            'currentPage' => 1,
            'filters'     => $filters,
        ];

        // AJAX: kembalikan hanya partial HTML (tanpa layout penuh)
        if ($request->ajax()) {
            return response(view('home.partials.catalog-grid', $partialData)->render());
        }

        return view('home.index', array_merge($partialData, [
            'categories' => Costume::query()->select('category')->distinct()->orderBy('category')->pluck('category'),
            'heroStats'  => [
                'catalog_count'         => Costume::query()->count(),
                'active_rentals'        => Rental::query()->where('status', RentalStatus::Active->value)->count(),
                'pending_verifications' => Payment::query()->where('status', PaymentStatus::Pending->value)->count(),
            ],
        ]));
    }

    public function show(Costume $costume): View
    {
        return view('home.show', [
            'costume' => $costume,
        ]);
    }
}