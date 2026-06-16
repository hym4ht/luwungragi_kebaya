<?php

namespace App\Services;

use App\Enums\RentalStatus;
use App\Models\Costume;
use App\Models\Rental;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

class AvailabilityService
{
    /**
     * Ambil katalog dengan ketersediaan stok untuk satu sesi (SESSION_DAYS).
     */
    public function getCatalog(?string $eventDate, ?string $keyword = null, ?string $category = null, int $sessions = Rental::MIN_SESSIONS): Collection
    {
        $selectedEventDate = Carbon::parse(
            $eventDate ?: now()->addDays(Rental::BOOKING_BUFFER_DAYS)
        )->toDateString();

        $schedule  = Rental::scheduleFromEventDate($selectedEventDate, max(Rental::MIN_SESSIONS, $sessions));
        $startDate = $schedule['booking_start_date']->toDateString();
        $endDate   = $schedule['return_date']->toDateString();

        return Costume::query()
            ->search($keyword)
            ->when($category, fn (Builder $query, string $selectedCategory) => $query->where('category', $selectedCategory))
            ->withSum([
                'rentalDetails as booked_quantity' => function (Builder $query) use ($startDate, $endDate): void {
                    $query->whereHas('rental', function (Builder $rentalQuery) use ($startDate, $endDate): void {
                        $rentalQuery
                            ->whereIn('status', [RentalStatus::Pending->value, RentalStatus::Active->value])
                            ->whereDate('rental_date', '<=', $endDate)
                            ->whereDate('return_date', '>=', $startDate);
                    });
                },
            ], 'quantity')
            ->orderBy('category')
            ->orderBy('name')
            ->get()
            ->map(function (Costume $costume) {
                $bookedQuantity = (int) ($costume->booked_quantity ?? 0);
                $availableStock = max($costume->stock - $bookedQuantity, 0);

                $costume->setAttribute('booked_quantity', $bookedQuantity);
                $costume->setAttribute('available_stock', $availableStock);
                $costume->setAttribute('realtime_status', $availableStock > 0 ? 'Tersedia' : 'Penuh');

                return $costume;
            });
    }
}
