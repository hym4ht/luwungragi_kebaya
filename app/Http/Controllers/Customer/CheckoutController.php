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

class CheckoutController extends Controller
{
    public function __construct(
        private readonly AvailabilityService $availabilityService,
        private readonly RentalWorkflowService $rentalWorkflowService,
    ) {}

    /**
     * Tampilkan halaman checkout dengan summary pemesanan.
     * Datanya berasal dari GET params yang dikirim form produk.
     */
    public function show(Request $request): View|RedirectResponse
    {
        $validated = $request->validate([
            'costume_id' => ['required', 'exists:costumes,id'],
            'event_date' => ['required', 'date', 'after_or_equal:' . now()->addDays(Rental::BOOKING_BUFFER_DAYS)->toDateString()],
            'sessions'   => ['required', 'integer', 'in:1'],
            'quantity'   => ['required', 'integer', 'min:1'],
        ]);

        $costume  = Costume::query()->findOrFail($validated['costume_id']);
        $sessions = (int) $validated['sessions'];

        $selectedCostume = $this->availabilityService
            ->getCatalog($validated['event_date'], sessions: $sessions)
            ->firstWhere('id', $costume->id);

        if (! $selectedCostume || $selectedCostume->available_stock < (int) $validated['quantity']) {
            return redirect()
                ->route('catalog.show', $costume)
                ->withErrors(['quantity' => 'Stok tidak mencukupi untuk tanggal yang dipilih.'])
                ->withInput();
        }

        $schedule   = Rental::scheduleFromEventDate($validated['event_date'], $sessions);
        $quantity   = (int) $validated['quantity'];
        $totalPrice = $sessions * $quantity * (float) $costume->rental_price;

        return view('customer.checkout.show', compact(
            'costume',
            'validated',
            'schedule',
            'quantity',
            'sessions',
            'totalPrice',
        ));
    }

    /**
     * Proses pembayaran + simpan rental dengan KTP yang diupload.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'costume_id'    => ['required', 'exists:costumes,id'],
            'event_date'    => ['required', 'date', 'after_or_equal:' . now()->addDays(Rental::BOOKING_BUFFER_DAYS)->toDateString()],
            'sessions'      => ['required', 'integer', 'in:1'],
            'quantity'      => ['required', 'integer', 'min:1'],
            'identity_card' => ['required', 'image', 'mimes:jpeg,png,jpg,webp', 'max:5120'],
        ]);

        $costume  = Costume::query()->findOrFail($validated['costume_id']);
        $sessions = (int) $validated['sessions'];

        $selectedCostume = $this->availabilityService
            ->getCatalog($validated['event_date'], sessions: $sessions)
            ->firstWhere('id', $costume->id);

        if (! $selectedCostume || $selectedCostume->available_stock < (int) $validated['quantity']) {
            return back()
                ->withInput()
                ->withErrors(['quantity' => 'Stok tidak mencukupi untuk tanggal yang dipilih.']);
        }

        $validated['identity_card'] = $request->file('identity_card')->store('identity_cards', 'public');

        $rental = $this->rentalWorkflowService->createBooking($request->user(), $costume, $validated);

        return redirect()
            ->route('customer.rentals.show', $rental)
            ->with('success', 'Pemesanan berhasil! Lakukan pelunasan maksimal H-2 dan pengambilan kostum offline H-1. Jangan lupa bawa identitas asli saat pengambilan.');
    }
}
