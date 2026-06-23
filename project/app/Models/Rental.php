<?php

namespace App\Models;

use App\Enums\RentalStatus;
use Carbon\CarbonInterface;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Rental extends Model
{
    use HasFactory;

    public const BOOKING_BUFFER_DAYS = 3;
    public const PAYMENT_BUFFER_DAYS = 2;
    public const PICKUP_BUFFER_DAYS = 1;
    public const RETURN_BUFFER_DAYS = 1;
    public const MIN_RENTAL_DAYS = 1;
    public const MAX_RENTAL_DAYS = 5;
    public const LATE_FEE_PER_DAY = 15000;

    protected $fillable = [
        'user_id',
        'invoice_number',
        'event_date',
        'rental_date',
        'return_date',
        'total_price',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'event_date' => 'date',
            'rental_date' => 'date',
            'return_date' => 'date',
            'total_price' => 'decimal:2',
            'status' => RentalStatus::class,
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function details(): HasMany
    {
        return $this->hasMany(RentalDetail::class);
    }

    public function payment(): HasOne
    {
        return $this->hasOne(Payment::class);
    }

    public function returnRecord(): HasOne
    {
        return $this->hasOne(RentalReturn::class, 'rental_id');
    }

    public static function scheduleFromEventDate(Carbon|string $eventDate, int $rentalDays = self::MIN_RENTAL_DAYS): array
    {
        $usageDate = $eventDate instanceof Carbon
            ? $eventDate->copy()->startOfDay()
            : Carbon::parse($eventDate)->startOfDay();
        $rentalDays = self::normalizeRentalDays($rentalDays);

        $bookingStartDate = $usageDate->copy()->subDays(self::BOOKING_BUFFER_DAYS);
        $paymentDueDate = $usageDate->copy()->subDays(self::PAYMENT_BUFFER_DAYS);
        $pickupDate = $usageDate->copy()->subDays(self::PICKUP_BUFFER_DAYS);
        $usageEndDate = $usageDate->copy()->addDays($rentalDays - 1);
        $returnDate = $usageEndDate->copy()->addDays(self::RETURN_BUFFER_DAYS);

        return [
            'event_date' => $usageDate,
            'booking_start_date' => $bookingStartDate,
            'usage_end_date' => $usageEndDate,
            'pickup_date' => $pickupDate,
            'payment_due_date' => $paymentDueDate,
            'return_date' => $returnDate,
            'rental_days' => $rentalDays,
            'process_duration_days' => max($bookingStartDate->diffInDays($returnDate), 0) + 1,
        ];
    }

    public function getUsageDateAttribute(): Carbon
    {
        return ($this->event_date?->copy() ?? $this->rental_date->copy()->addDays(self::BOOKING_BUFFER_DAYS))
            ->startOfDay();
    }

    public function getBookingStartDateAttribute(): Carbon
    {
        return ($this->rental_date?->copy() ?? $this->usage_date->copy()->subDays(self::BOOKING_BUFFER_DAYS))
            ->startOfDay();
    }

    public function getPickupDateAttribute(): Carbon
    {
        return $this->usage_date->copy()->subDays(self::PICKUP_BUFFER_DAYS);
    }

    public function getPaymentDueDateAttribute(): Carbon
    {
        return $this->usage_date->copy()->subDays(self::PAYMENT_BUFFER_DAYS);
    }

    public function getUsageEndDateAttribute(): Carbon
    {
        return $this->return_due_date->copy()->subDays(self::RETURN_BUFFER_DAYS);
    }

    public function getReturnDueDateAttribute(): Carbon
    {
        return ($this->return_date?->copy() ?? $this->usage_date->copy()->addDays(self::RETURN_BUFFER_DAYS))
            ->startOfDay();
    }

    public function getRentalDurationDaysAttribute(): int
    {
        return max($this->usage_date->diffInDays($this->return_due_date, false), self::MIN_RENTAL_DAYS);
    }

    public function getProcessDurationDaysAttribute(): int
    {
        return max($this->booking_start_date->diffInDays($this->return_due_date), 0) + 1;
    }

    public function lateDaysFor(CarbonInterface $returnedDate): int
    {
        return max($this->return_due_date->diffInDays($returnedDate->copy()->startOfDay(), false), 0);
    }

    public static function normalizeRentalDays(int $rentalDays): int
    {
        return max(self::MIN_RENTAL_DAYS, min(self::MAX_RENTAL_DAYS, $rentalDays));
    }
}
