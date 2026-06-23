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

    /** Hari per sesi. */
    public const SESSION_DAYS = 5;

    /** Minimum jumlah sesi yang bisa dipesan. */
    public const MIN_SESSIONS = 1;

    /** Hari sebelum event_date: batas booking dibuka. */
    public const BOOKING_BUFFER_DAYS = 3;

    /** Hari sebelum event_date: batas pelunasan. */
    public const PAYMENT_BUFFER_DAYS = 2;

    /** Hari sebelum event_date: pengambilan offline. */
    public const PICKUP_BUFFER_DAYS = 1;

    /** Hari setelah usage_end_date: deadline pengembalian. */
    public const RETURN_BUFFER_DAYS = 1;

    /** Denda keterlambatan per hari (Rp). */
    public const LATE_FEE_PER_DAY = 15000;

    protected $fillable = [
        'user_id',
        'invoice_number',
        'identity_card',
        'event_date',
        'rental_date',
        'return_date',
        'total_price',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'event_date'  => 'date',
            'rental_date' => 'date',
            'return_date' => 'date',
            'total_price' => 'decimal:2',
            'status'      => RentalStatus::class,
        ];
    }

    // ── Relationships ─────────────────────────────────────────────────────────

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

    // ── Static Schedule Calculator ────────────────────────────────────────────

    /**
     * Hitung semua tanggal jadwal dari event_date dan jumlah sesi.
     *
     * @param  int  $sessions  Jumlah sesi (min 1), total hari = sessions × SESSION_DAYS
     */
    public static function scheduleFromEventDate(Carbon|string $eventDate, int $sessions = self::MIN_SESSIONS): array
    {
        $sessions  = max(self::MIN_SESSIONS, $sessions);
        $totalDays = $sessions * self::SESSION_DAYS;

        $usageDate = $eventDate instanceof Carbon
            ? $eventDate->copy()->startOfDay()
            : Carbon::parse($eventDate)->startOfDay();

        $bookingStartDate = $usageDate->copy()->subDays(self::BOOKING_BUFFER_DAYS);
        $paymentDueDate   = $usageDate->copy()->subDays(self::PAYMENT_BUFFER_DAYS);
        $pickupDate       = $usageDate->copy()->subDays(self::PICKUP_BUFFER_DAYS);
        $usageEndDate     = $usageDate->copy()->addDays($totalDays - 1);
        $returnDate       = $usageEndDate->copy()->addDays(self::RETURN_BUFFER_DAYS);

        return [
            'event_date'            => $usageDate,
            'booking_start_date'    => $bookingStartDate,
            'usage_end_date'        => $usageEndDate,
            'pickup_date'           => $pickupDate,
            'payment_due_date'      => $paymentDueDate,
            'return_date'           => $returnDate,
            'sessions'              => $sessions,
            'rental_days'           => $totalDays,
            'process_duration_days' => max($bookingStartDate->diffInDays($returnDate), 0) + 1,
        ];
    }

    // ── Computed Accessors ────────────────────────────────────────────────────

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
        return ($this->return_date?->copy() ?? $this->usage_date->copy()->addDays(self::SESSION_DAYS - 1 + self::RETURN_BUFFER_DAYS))
            ->startOfDay();
    }

    /**
     * Total hari pakai (dihitung dari tanggal tersimpan di DB).
     * = usage_end_date - usage_date + 1
     */
    public function getRentalDurationDaysAttribute(): int
    {
        return max((int) $this->usage_date->diffInDays($this->usage_end_date) + 1, self::SESSION_DAYS);
    }

    /**
     * Jumlah sesi (dihitung dari total hari pakai / SESSION_DAYS).
     */
    public function getSessionsCountAttribute(): int
    {
        return (int) round($this->rental_duration_days / self::SESSION_DAYS);
    }

    public function getProcessDurationDaysAttribute(): int
    {
        return max($this->booking_start_date->diffInDays($this->return_due_date), 0) + 1;
    }

    /** Hitung hari keterlambatan dari tanggal pengembalian aktual. */
    public function lateDaysFor(CarbonInterface $returnedDate): int
    {
        return max($this->return_due_date->diffInDays($returnedDate->copy()->startOfDay(), false), 0);
    }
}
