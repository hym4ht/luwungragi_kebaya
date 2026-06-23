<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Costume;
use App\Models\Rental;
use App\Services\AvailabilityService;
use App\Services\RentalWorkflowService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

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
            'costume_id' => ['required', 'exists:costumes,id'],
            'event_date' => ['required', 'date', 'after_or_equal:' . now()->addDays(Rental::BOOKING_BUFFER_DAYS)->toDateString()],
            'sessions'   => ['required', 'integer', 'in:1'],
            'quantity'   => ['required', 'integer', 'min:1'],
        ]);

        $sessions = (int) $validated['sessions'];
        $costume  = Costume::query()->findOrFail($validated['costume_id']);

        $selectedCostume = $this->availabilityService
            ->getCatalog($validated['event_date'], sessions: $sessions)
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

    public function downloadPdf(Request $request, Rental $rental): Response
    {
        abort_unless($rental->user_id === $request->user()->id, 403);

        $rental->load(['details.costume', 'payment', 'returnRecord', 'user']);

        $pdf = Pdf::loadView('customer.rentals.pdf', ['rental' => $rental])
            ->setPaper('a5', 'portrait');

        $filename = 'struk-' . $rental->invoice_number . '.pdf';

        return $pdf->download($filename);
    }
}
