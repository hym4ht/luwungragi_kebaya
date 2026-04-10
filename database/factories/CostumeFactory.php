<?php

namespace Database\Factories;

use App\Models\Costume;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Costume>
 */
class CostumeFactory extends Factory
{
    protected $model = Costume::class;

    private static array $costumeData = [
        ['name' => 'Kebaya Brokat Akad Putih Gading', 'category' => 'Kebaya', 'price' => 350000],
        ['name' => 'Kebaya Modern Dusty Rose', 'category' => 'Kebaya', 'price' => 420000],
        ['name' => 'Kebaya Encim Biru Tosca', 'category' => 'Kebaya', 'price' => 385000],
        ['name' => 'Kebaya Kartini Kuning Mustard', 'category' => 'Kebaya', 'price' => 310000],
        ['name' => 'Kebaya Pengantin Full Brukat Merah', 'category' => 'Kebaya', 'price' => 500000],
        ['name' => 'Kebaya Kutubaru Hijau Sage', 'category' => 'Kebaya', 'price' => 295000],
        ['name' => 'Beskap Jawa Klasik Hitam', 'category' => 'Kostum Adat', 'price' => 275000],
        ['name' => 'Baju Adat Bali Couple', 'category' => 'Kostum Adat', 'price' => 560000],
        ['name' => 'Surjan Lurik Coklat Tua', 'category' => 'Kostum Adat', 'price' => 250000],
        ['name' => 'Pakaian Adat Sunda Merah', 'category' => 'Kostum Adat', 'price' => 320000],
        ['name' => 'Baju Adat Minang Gold', 'category' => 'Kostum Adat', 'price' => 480000],
        ['name' => 'Gaun Tari Tradisional Merah Marun', 'category' => 'Kostum Tari', 'price' => 300000],
        ['name' => 'Kostum Tari Topeng Betawi', 'category' => 'Kostum Tari', 'price' => 340000],
        ['name' => 'Gaun Tari Bali Kuning Keemasan', 'category' => 'Kostum Tari', 'price' => 375000],
        ['name' => 'Baju Tari Serimpi Hijau', 'category' => 'Kostum Tari', 'price' => 290000],
        ['name' => 'Kostum Wayang Orang Putih', 'category' => 'Kostum Tari', 'price' => 360000],
        ['name' => 'Kostum Wisuda Nusantara', 'category' => 'Kostum Event', 'price' => 240000],
        ['name' => 'Baju Karnaval Batik Pekalongan', 'category' => 'Kostum Event', 'price' => 200000],
        ['name' => 'Kostum Hari Kemerdekaan Pahlawan', 'category' => 'Kostum Event', 'price' => 275000],
        ['name' => 'Setelan Batik Formal Motif Parang', 'category' => 'Kostum Event', 'price' => 225000],
    ];

    private static int $dataIndex = 0;

    public function definition(): array
    {
        $data = self::$costumeData[self::$dataIndex % count(self::$costumeData)];
        self::$dataIndex++;

        return [
            'name'                => $data['name'],
            'category'            => $data['category'],
            'stock'               => $this->faker->numberBetween(1, 8),
            'rental_price'        => $data['price'],
            'availability_status' => $this->faker->randomElement(['Available', 'Available', 'Available', 'Not Available']),
        ];
    }

    public function available(): static
    {
        return $this->state(['availability_status' => 'Available']);
    }

    public function unavailable(): static
    {
        return $this->state(['availability_status' => 'Not Available']);
    }
}
