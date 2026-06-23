<?php

namespace Database\Factories;

use App\Enums\RentalStatus;
use App\Models\Rental;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

/**
 * @extends Factory<Rental>
 */
class RentalFactory extends Factory
{
    protected $model = Rental::class;

    private static int $invoiceCounter = 1;

    public function definition(): array
    {
        $eventDate = Carbon::instance($this->faker->dateTimeBetween('-2 months', '+1 month'))->startOfDay();
        $schedule = Rental::scheduleFromEventDate($eventDate);

        $invoiceNumber = 'RNT-' . now()->format('Ymd') . '-' . str_pad(self::$invoiceCounter++, 3, '0', STR_PAD_LEFT);

        return [
            'user_id'        => User::factory(),
            'invoice_number' => $invoiceNumber,
            'event_date'     => $schedule['event_date']->toDateString(),
            'rental_date'    => $schedule['booking_start_date']->toDateString(),
            'return_date'    => $schedule['return_date']->toDateString(),
            'total_price'    => $this->faker->randomElement([240000, 275000, 300000, 350000, 420000, 480000, 560000, 700000, 900000]),
            'status'         => $this->faker->randomElement(RentalStatus::cases()),
        ];
    }

    public function pending(): static
    {
        return $this->state(function () {
            $bookingStartDate = now()->addDays($this->faker->numberBetween(1, 5))->startOfDay();
            $schedule = Rental::scheduleFromEventDate($bookingStartDate->copy()->addDays(Rental::BOOKING_BUFFER_DAYS));

            return [
                'event_date' => $schedule['event_date']->toDateString(),
                'rental_date' => $schedule['booking_start_date']->toDateString(),
                'return_date' => $schedule['return_date']->toDateString(),
                'status' => RentalStatus::Pending,
            ];
        });
    }

    public function active(): static
    {
        return $this->state(function () {
            $returnDate = now()->addDays($this->faker->numberBetween(1, 5))->startOfDay();
            $schedule = Rental::scheduleFromEventDate($returnDate->copy()->subDay());

            return [
                'event_date' => $schedule['event_date']->toDateString(),
                'rental_date' => $schedule['booking_start_date']->toDateString(),
                'return_date' => $schedule['return_date']->toDateString(),
                'status' => RentalStatus::Active,
            ];
        });
    }

    public function completed(): static
    {
        return $this->state(function () {
            $eventDate = now()->subDays($this->faker->numberBetween(8, 28))->startOfDay();
            $schedule = Rental::scheduleFromEventDate($eventDate);

            return [
                'event_date' => $schedule['event_date']->toDateString(),
                'rental_date' => $schedule['booking_start_date']->toDateString(),
                'return_date' => $schedule['return_date']->toDateString(),
                'status' => RentalStatus::Completed,
            ];
        });
    }

    public function cancelled(): static
    {
        return $this->state(fn () => [
            'status' => RentalStatus::Cancelled,
        ]);
    }
}
