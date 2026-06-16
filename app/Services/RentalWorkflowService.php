<?php

namespace App\Services;

use App\Enums\PaymentStatus;
use App\Enums\RentalStatus;
use App\Models\Costume;
use App\Models\Payment;
use App\Models\Rental;
use App\Models\RentalReturn;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class RentalWorkflowService
{
    /**
     * Buat booking baru. Satu booking = 1 sesi (SESSION_DAYS hari).
     */
    public function createBooking(User $user, Costume $costume, array $payload): Rental
    {
        return DB::transaction(function () use ($user, $costume, $payload): Rental {
            $eventDate  = Carbon::parse($payload['event_date'])->startOfDay();
            $sessions   = max(Rental::MIN_SESSIONS, (int) ($payload['sessions'] ?? Rental::MIN_SESSIONS));
            $schedule   = Rental::scheduleFromEventDate($eventDate, $sessions);
            $quantity   = (int) $payload['quantity'];
            $totalPrice = $schedule['rental_days'] * $quantity * (float) $costume->rental_price;

            $rental = Rental::query()->create([
                'user_id'        => $user->id,
                'invoice_number' => $this->generateInvoiceNumber(),
                'event_date'     => $schedule['event_date']->toDateString(),
                'rental_date'    => $schedule['booking_start_date']->toDateString(),
                'return_date'    => $schedule['return_date']->toDateString(),
                'total_price'    => $totalPrice,
                'status'         => RentalStatus::Pending,
            ]);

            $rental->details()->create([
                'costume_id' => $costume->id,
                'quantity'   => $quantity,
                'unit_price' => $costume->rental_price,
            ]);

            $rental->payment()->create([
                'payment_type' => 'Midtrans Snap',
                'status'       => PaymentStatus::Pending,
            ]);

            return $rental->load(['user', 'details.costume', 'payment']);
        });
    }

    public function updatePaymentStatus(Payment $payment, PaymentStatus $status): Payment
    {
        $payment->fill([
            'status'  => $status,
            'paid_at' => $status === PaymentStatus::Settlement ? now() : null,
        ])->save();

        return $payment->refresh();
    }

    public function updateAdminRental(Rental $rental, array $payload): Rental
    {
        return DB::transaction(function () use ($rental, $payload): Rental {
            $rental->update([
                'status' => RentalStatus::from($payload['rental_status']),
            ]);

            if ($rental->payment && filled($payload['payment_status'] ?? null)) {
                $this->updatePaymentStatus($rental->payment, PaymentStatus::from($payload['payment_status']));
            }

            if (filled($payload['returned_date'] ?? null)) {
                $this->recordReturn($rental, $payload);
            }

            return $rental->refresh()->load(['payment', 'returnRecord']);
        });
    }

    public function recordReturn(Rental $rental, array $payload): RentalReturn
    {
        $returnedDate = Carbon::parse($payload['returned_date'])->startOfDay();
        $lateDays     = $rental->lateDaysFor($returnedDate);
        $damageFee    = (float) ($payload['damage_fee'] ?? 0);
        $fineAmount   = ($lateDays * Rental::LATE_FEE_PER_DAY) + $damageFee;

        $returnStatus = 'On Time';
        if ($damageFee > 0) {
            $returnStatus = 'Damaged';
        } elseif ($lateDays > 0) {
            $returnStatus = 'Late';
        }

        return DB::transaction(function () use ($rental, $returnedDate, $fineAmount, $returnStatus): RentalReturn {
            $returnRecord = RentalReturn::query()->updateOrCreate(
                ['rental_id' => $rental->id],
                [
                    'returned_date' => $returnedDate->toDateString(),
                    'fine_amount'   => $fineAmount,
                    'return_status' => $returnStatus,
                ],
            );

            $rental->update(['status' => RentalStatus::Completed]);

            return $returnRecord;
        });
    }

    private function generateInvoiceNumber(): string
    {
        $prefix   = 'RNT-' . now()->format('Ymd');
        $sequence = Rental::query()
            ->where('invoice_number', 'like', $prefix . '-%')
            ->count() + 1;

        return $prefix . '-' . str_pad((string) $sequence, 3, '0', STR_PAD_LEFT);
    }
}
