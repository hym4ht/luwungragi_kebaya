<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Costume;
use App\Models\Rental;
use App\Services\AvailabilityService;
use App\Services\RentalWorkflowService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class RentalController extends Controller
{
    public function __construct(
        private readonly AvailabilityService $availabilityService,
        private readonly RentalWorkflowService $rentalWorkflowService,
    ) {
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'costume_id'  => ['required', 'exists:costumes,id'],
            'event_date' => ['required', 'date', 'after_or_equal:'.now()->addDays(Rental::BOOKING_BUFFER_DAYS)->toDateString()],
            'rental_days' => ['required', 'integer', 'min:'.Rental::MIN_RENTAL_DAYS, 'max:'.Rental::MAX_RENTAL_DAYS],
            'quantity'    => ['required', 'integer', 'min:1'],
        ]);

        $costume = Costume::query()->findOrFail($validated['costume_id']);
        $selectedCostume = $this->availabilityService
            ->getCatalog($validated['event_date'], rentalDays: (int) $validated['rental_days'])
            ->firstWhere('id', $costume->id);

        if (! $selectedCostume || $selectedCostume->available_stock < (int) $validated['quantity']) {
            return back()
                ->withInput()
                ->withErrors(['quantity' => 'Stok tidak mencukupi untuk rentang tanggal yang dipilih.']);
        }

        $rental = $this->rentalWorkflowService->createBooking($request->user(), $costume, $validated);

        return redirect()
            ->route('customer.rentals.show', $rental)
            ->with('success', 'Pemesanan berhasil dibuat. Pelunasan maksimal H-2 dan pengambilan offline H-1.');
    }

    public function show(Request $request, Rental $rental): View
    {
        abort_unless($rental->user_id === $request->user()->id, 403);

        return view('customer.rentals.show', [
            'rental' => $rental->load(['details.costume', 'payment', 'returnRecord', 'user']),
        ]);
    }
}
