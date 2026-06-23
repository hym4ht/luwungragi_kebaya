<?php

namespace Database\Seeders;

use App\Enums\PaymentStatus;
use App\Enums\RentalStatus;
use App\Models\Costume;
use App\Models\Rental;
use App\Models\User;
use Illuminate\Database\Seeder;

class RentalSeeder extends Seeder
{
    private int $invoiceCounter = 1;

    public function run(): void
    {
        $customers = User::query()->where('role', 'customer')->get();
        $costumes  = Costume::query()->get();

        if ($customers->isEmpty() || $costumes->isEmpty()) {
            $this->command->warn('Jalankan UserSeeder dan CostumeSeeder terlebih dahulu.');
            return;
        }

        // ── 1. Selesai (completed) ──────────────────────────────────────────
        $this->createCompletedRental(
            customer: $customers->where('email', 'ratri@luwungragi.test')->first() ?? $customers->first(),
            costumes: $costumes->where('category', 'Kebaya')->take(2),
            daysAgo:  20,
            paymentType: 'qris',
        );

        $this->createCompletedRental(
            customer: $customers->where('email', 'dewi@luwungragi.test')->first() ?? $customers->get(1),
            costumes: $costumes->where('category', 'Kostum Adat')->take(1),
            daysAgo:  14,
            paymentType: 'va_bni',
            late: true,
            fineAmount: 30000,
        );

        $this->createCompletedRental(
            customer: $customers->where('email', 'sari@luwungragi.test')->first() ?? $customers->get(2),
            costumes: $costumes->where('category', 'Kostum Tari')->take(2),
            daysAgo:  30,
            paymentType: 'gopay',
        );

        $this->createCompletedRental(
            customer: $customers->where('email', 'andi@luwungragi.test')->first() ?? $customers->get(3),
            costumes: $costumes->where('category', 'Kostum Event')->take(1),
            daysAgo:  10,
            paymentType: 'va_bca',
            late: true,
            fineAmount: 15000,
        );

        $this->createCompletedRental(
            customer: $customers->where('email', 'mega@luwungragi.test')->first() ?? $customers->get(4),
            costumes: $costumes->where('category', 'Kebaya')->skip(2)->take(2),
            daysAgo:  45,
            paymentType: 'qris',
        );

        // ── 2. Aktif (active) ───────────────────────────────────────────────
        $this->createActiveRental(
            customer: $customers->where('email', 'dewi@luwungragi.test')->first() ?? $customers->get(1),
            costumes: $costumes->where('category', 'Kostum Adat')->skip(1)->take(1),
            returnInDays: 2,
            paymentType: 'qris',
        );

        $this->createActiveRental(
            customer: $customers->where('email', 'rizky@luwungragi.test')->first() ?? $customers->get(5),
            costumes: $costumes->where('category', 'Kebaya')->take(1),
            returnInDays: 4,
            paymentType: 'gopay',
        );

        $this->createActiveRental(
            customer: $customers->where('email', 'nurul@luwungragi.test')->first() ?? $customers->get(6),
            costumes: $costumes->where('category', 'Kostum Tari')->skip(1)->take(1),
            returnInDays: 3,
            paymentType: 'va_bri',
        );

        // ── 3. Menunggu (pending) ───────────────────────────────────────────
        $this->createPendingRental(
            customer: $customers->where('email', 'bagas@luwungragi.test')->first() ?? $customers->get(2),
            costumes: $costumes->where('category', 'Kostum Adat')->skip(2)->take(1),
            startInDays: 1,
        );

        $this->createPendingRental(
            customer: $customers->where('email', 'fajar@luwungragi.test')->first() ?? $customers->get(7),
            costumes: $costumes->where('category', 'Kostum Event')->skip(1)->take(2),
            startInDays: 3,
            snapToken: 'SNAP-DEMO-FAJAR-001',
        );

        $this->createPendingRental(
            customer: $customers->where('email', 'indah@luwungragi.test')->first() ?? $customers->get(8),
            costumes: $costumes->where('category', 'Kebaya')->skip(3)->take(1),
            startInDays: 5,
            snapToken: 'SNAP-DEMO-INDAH-001',
        );

        // ── 4. Dibatalkan (cancelled) ───────────────────────────────────────
        $this->createCancelledRental(
            customer: $customers->where('email', 'ratri@luwungragi.test')->first() ?? $customers->first(),
            costumes: $costumes->where('category', 'Kebaya')->skip(1)->take(1),
            paymentStatus: PaymentStatus::Cancel,
        );

        $this->createCancelledRental(
            customer: $customers->where('email', 'sari@luwungragi.test')->first() ?? $customers->get(3),
            costumes: $costumes->where('category', 'Kostum Tari')->take(1),
            paymentStatus: PaymentStatus::Expire,
        );
    }

    // ── Helpers ──────────────────────────────────────────────────────────────

    private function nextInvoice(): string
    {
        return 'RNT-' . now()->format('Ymd') . '-' . str_pad($this->invoiceCounter++, 3, '0', STR_PAD_LEFT);
    }

    private function createCompletedRental(
        $customer,
        $costumes,
        int $daysAgo,
        string $paymentType = '',
        bool $late = false,
        float $fineAmount = 0,
    ): void {
        $usageDate    = now()->subDays(max($daysAgo - Rental::BOOKING_BUFFER_DAYS, 0))->startOfDay();
        $schedule     = Rental::scheduleFromEventDate($usageDate);
        $costumesList = collect($costumes);
        $totalPrice   = $costumesList->sum(fn ($c) => (float) $c->rental_price);

        $rental = Rental::query()->create([
            'user_id'        => $customer->id,
            'invoice_number' => $this->nextInvoice(),
            'event_date'     => $schedule['event_date']->toDateString(),
            'rental_date'    => $schedule['booking_start_date']->toDateString(),
            'return_date'    => $schedule['return_date']->toDateString(),
            'total_price'    => $totalPrice,
            'status'         => RentalStatus::Completed,
        ]);

        foreach ($costumesList as $costume) {
            $rental->details()->create([
                'costume_id' => $costume->id,
                'quantity'   => 1,
                'unit_price' => $costume->rental_price,
            ]);
        }

        $rental->payment()->create([
            'payment_type'            => $paymentType ?: 'Midtrans Snap',
            'status'                  => PaymentStatus::Settlement,
            'paid_at'                 => $schedule['payment_due_date']->copy(),
            'snap_token'              => 'SNAP-' . $rental->invoice_number,
            'midtrans_transaction_id' => 'MID-' . $rental->invoice_number,
        ]);

        $lateDays     = $late ? max((int) round($fineAmount / Rental::LATE_FEE_PER_DAY), 1) : 0;
        $returnedDate = $schedule['return_date']->copy()->addDays($lateDays)->toDateString();

        $rental->returnRecord()->create([
            'returned_date' => $returnedDate,
            'fine_amount'   => $fineAmount,
            'return_status' => $late ? 'Late' : 'On Time',
        ]);
    }

    private function createActiveRental(
        $customer,
        $costumes,
        int $returnInDays,
        string $paymentType = '',
    ): void {
        $costumesList = collect($costumes);
        $returnDate   = now()->addDays($returnInDays)->startOfDay();
        $usageDate    = $returnDate->copy()->subDays(1);
        $schedule     = Rental::scheduleFromEventDate($usageDate);
        $totalPrice   = $costumesList->sum(fn ($c) => (float) $c->rental_price);

        $rental = Rental::query()->create([
            'user_id'        => $customer->id,
            'invoice_number' => $this->nextInvoice(),
            'event_date'     => $schedule['event_date']->toDateString(),
            'rental_date'    => $schedule['booking_start_date']->toDateString(),
            'return_date'    => $schedule['return_date']->toDateString(),
            'total_price'    => $totalPrice,
            'status'         => RentalStatus::Active,
        ]);

        foreach ($costumesList as $costume) {
            $rental->details()->create([
                'costume_id' => $costume->id,
                'quantity'   => 1,
                'unit_price' => $costume->rental_price,
            ]);
        }

        $rental->payment()->create([
            'payment_type'            => $paymentType ?: 'Midtrans Snap',
            'status'                  => PaymentStatus::Settlement,
            'paid_at'                 => $schedule['payment_due_date']->copy(),
            'snap_token'              => 'SNAP-' . $rental->invoice_number,
            'midtrans_transaction_id' => 'MID-' . $rental->invoice_number,
        ]);
    }

    private function createPendingRental(
        $customer,
        $costumes,
        int $startInDays,
        ?string $snapToken = null,
    ): void {
        $costumesList    = collect($costumes);
        $bookingStartDate = now()->addDays($startInDays)->startOfDay();
        $usageDate        = $bookingStartDate->copy()->addDays(Rental::BOOKING_BUFFER_DAYS);
        $schedule         = Rental::scheduleFromEventDate($usageDate);
        $totalPrice       = $costumesList->sum(fn ($c) => (float) $c->rental_price);

        $rental = Rental::query()->create([
            'user_id'        => $customer->id,
            'invoice_number' => $this->nextInvoice(),
            'event_date'     => $schedule['event_date']->toDateString(),
            'rental_date'    => $schedule['booking_start_date']->toDateString(),
            'return_date'    => $schedule['return_date']->toDateString(),
            'total_price'    => $totalPrice,
            'status'         => RentalStatus::Pending,
        ]);

        foreach ($costumesList as $costume) {
            $rental->details()->create([
                'costume_id' => $costume->id,
                'quantity'   => 1,
                'unit_price' => $costume->rental_price,
            ]);
        }

        $paymentData = [
            'payment_type' => 'Midtrans Snap',
            'status'       => PaymentStatus::Pending,
        ];

        if ($snapToken) {
            $paymentData['snap_token'] = $snapToken;
        }

        $rental->payment()->create($paymentData);
    }

    private function createCancelledRental(
        $customer,
        $costumes,
        PaymentStatus $paymentStatus,
    ): void {
        $costumesList = collect($costumes);
        $usageDate    = now()->addDays(Rental::BOOKING_BUFFER_DAYS)->startOfDay();
        $schedule     = Rental::scheduleFromEventDate($usageDate);
        $totalPrice   = $costumesList->sum(fn ($c) => (float) $c->rental_price);

        $rental = Rental::query()->create([
            'user_id'        => $customer->id,
            'invoice_number' => $this->nextInvoice(),
            'event_date'     => $schedule['event_date']->toDateString(),
            'rental_date'    => $schedule['booking_start_date']->toDateString(),
            'return_date'    => $schedule['return_date']->toDateString(),
            'total_price'    => $totalPrice,
            'status'         => RentalStatus::Cancelled,
        ]);

        foreach ($costumesList as $costume) {
            $rental->details()->create([
                'costume_id' => $costume->id,
                'quantity'   => 1,
                'unit_price' => $costume->rental_price,
            ]);
        }

        $rental->payment()->create([
            'payment_type'            => 'Midtrans Snap',
            'status'                  => $paymentStatus,
            'snap_token'              => 'SNAP-' . $rental->invoice_number,
            'midtrans_transaction_id' => 'MID-' . $rental->invoice_number,
        ]);
    }
}
