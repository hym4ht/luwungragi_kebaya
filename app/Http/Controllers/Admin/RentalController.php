<?php

namespace App\Http\Controllers\Admin;

use App\Enums\PaymentStatus;
use App\Enums\RentalStatus;
use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\Rental;
use App\Services\RentalWorkflowService;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class RentalController extends Controller
{
    public function __construct(private readonly RentalWorkflowService $rentalWorkflowService)
    {
    }

    public function index(Request $request): View
    {
        $filters = $this->filters($request);

        return view('admin.rentals.index', [
            'rentals' => $this->filteredRentals($filters),
            'filters' => $filters,
            'editingRental' => null,
        ]);
    }

    public function show(Request $request, Rental $rental): View
    {
        $filters = $this->filters($request);

        return view('admin.rentals.index', [
            'rentals' => $this->filteredRentals($filters),
            'filters' => $filters,
            'editingRental' => $rental->load(['user', 'details.costume', 'payment', 'returnRecord']),
        ]);
    }

    public function update(Request $request, Rental $rental): RedirectResponse
    {
        $validated = $request->validate([
            'rental_status' => ['required', Rule::in(array_column(RentalStatus::cases(), 'value'))],
            'payment_status' => ['nullable', Rule::in(array_column(PaymentStatus::cases(), 'value'))],
            'returned_date' => [
                'nullable',
                'date',
                Rule::requiredIf(fn (): bool => (float) $request->input('damage_fee', 0) > 0),
            ],
            'damage_fee' => ['nullable', 'numeric', 'min:0'],
        ]);

        $this->rentalWorkflowService->updateAdminRental($rental, $validated);

        return back()->with('success', 'Perubahan rental berhasil disimpan.');
    }

    public function updateStatus(Request $request, Rental $rental): RedirectResponse
    {
        $validated = $request->validate([
            'status' => ['required', Rule::in(array_column(RentalStatus::cases(), 'value'))],
        ]);

        $rental->update([
            'status' => RentalStatus::from($validated['status']),
        ]);

        return back()->with('success', 'Status penyewaan berhasil diperbarui.');
    }

    public function updatePaymentStatus(Request $request, Payment $payment): RedirectResponse
    {
        $validated = $request->validate([
            'status' => ['required', Rule::in(array_column(PaymentStatus::cases(), 'value'))],
        ]);

        $this->rentalWorkflowService->updatePaymentStatus($payment, PaymentStatus::from($validated['status']));

        return back()->with('success', 'Status pembayaran berhasil diperbarui.');
    }

    public function storeReturn(Request $request, Rental $rental): RedirectResponse
    {
        $validated = $request->validate([
            'returned_date' => ['required', 'date'],
            'damage_fee' => ['nullable', 'numeric', 'min:0'],
        ]);

        $this->rentalWorkflowService->recordReturn($rental, $validated);

        return back()->with('success', 'Data pengembalian dan denda berhasil diproses.');
    }

    private function filters(Request $request): array
    {
        return [
            'search' => $request->string('search')->toString(),
            'status' => $request->string('status')->toString(),
            'payment_status' => $request->string('payment_status')->toString(),
        ];
    }

    private function filteredRentals(array $filters): Collection
    {
        return Rental::query()
            ->with(['user', 'details.costume', 'payment', 'returnRecord'])
            ->when($filters['search'], function ($query, string $search): void {
                $query->where(function ($builder) use ($search): void {
                    $builder
                        ->where('invoice_number', 'like', '%'.$search.'%')
                        ->orWhereHas('user', fn ($userQuery) => $userQuery->where('name', 'like', '%'.$search.'%'));
                });
            })
            ->when($filters['status'], fn ($query, string $status) => $query->where('status', $status))
            ->when($filters['payment_status'], fn ($query, string $paymentStatus) => $query->whereHas('payment', fn ($paymentQuery) => $paymentQuery->where('status', $paymentStatus)))
            ->latest()
            ->get();
    }
}
