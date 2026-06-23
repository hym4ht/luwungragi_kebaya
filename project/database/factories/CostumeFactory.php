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
        ['name' => 'Kebaya Tile Champagne Payet', 'category' => 'Kebaya', 'price' => 465000],
        ['name' => 'Kebaya Resepsi Lavender Embroidery', 'category' => 'Kebaya', 'price' => 440000],
        ['name' => 'Kebaya Organza Pearl Nude', 'category' => 'Kebaya', 'price' => 390000],
        ['name' => 'Beskap Jawa Klasik Hitam', 'category' => 'Kostum Adat', 'price' => 275000],
        ['name' => 'Baju Adat Bali Couple', 'category' => 'Kostum Adat', 'price' => 560000],
        ['name' => 'Surjan Lurik Coklat Tua', 'category' => 'Kostum Adat', 'price' => 250000],
        ['name' => 'Pakaian Adat Sunda Merah', 'category' => 'Kostum Adat', 'price' => 320000],
        ['name' => 'Baju Adat Minang Gold', 'category' => 'Kostum Adat', 'price' => 480000],
        ['name' => 'Pakaian Adat Betawi Pengantin', 'category' => 'Kostum Adat', 'price' => 600000],
        ['name' => 'Bodo Makassar Orange Sunset', 'category' => 'Kostum Adat', 'price' => 355000],
        ['name' => 'Ulos Batak Ceremony Set', 'category' => 'Kostum Adat', 'price' => 425000],
        ['name' => 'Teluk Belanga Melayu Emerald', 'category' => 'Kostum Adat', 'price' => 335000],
        ['name' => 'Gaun Tari Tradisional Merah Marun', 'category' => 'Kostum Tari', 'price' => 300000],
        ['name' => 'Kostum Tari Topeng Betawi', 'category' => 'Kostum Tari', 'price' => 340000],
        ['name' => 'Gaun Tari Bali Kuning Keemasan', 'category' => 'Kostum Tari', 'price' => 375000],
        ['name' => 'Baju Tari Serimpi Hijau', 'category' => 'Kostum Tari', 'price' => 290000],
        ['name' => 'Kostum Wayang Orang Putih', 'category' => 'Kostum Tari', 'price' => 360000],
        ['name' => 'Kostum Tari Saman Aceh Black Gold', 'category' => 'Kostum Tari', 'price' => 345000],
        ['name' => 'Kostum Tari Piring Minang Ruby', 'category' => 'Kostum Tari', 'price' => 355000],
        ['name' => 'Kostum Tari Jaipong Fuchsia', 'category' => 'Kostum Tari', 'price' => 325000],
        ['name' => 'Kostum Tari Merak Royal Blue', 'category' => 'Kostum Tari', 'price' => 410000],
        ['name' => 'Kostum Wisuda Nusantara', 'category' => 'Kostum Event', 'price' => 240000],
        ['name' => 'Baju Karnaval Batik Pekalongan', 'category' => 'Kostum Event', 'price' => 200000],
        ['name' => 'Kostum Hari Kemerdekaan Pahlawan', 'category' => 'Kostum Event', 'price' => 275000],
        ['name' => 'Setelan Batik Formal Motif Parang', 'category' => 'Kostum Event', 'price' => 225000],
        ['name' => 'Kostum Ondel-Ondel Betawi Mini', 'category' => 'Kostum Event', 'price' => 450000],
        ['name' => 'Kostum MC Adat Nusantara Ivory', 'category' => 'Kostum Event', 'price' => 260000],
        ['name' => 'Setelan Kartini Family Day Navy', 'category' => 'Kostum Event', 'price' => 235000],
        ['name' => 'Kostum Parade Sekolah Majapahit', 'category' => 'Kostum Event', 'price' => 280000],
        ['name' => 'Costume Gala Heritage Black Gold', 'category' => 'Kostum Event', 'price' => 520000],
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
            'availability_status' => $this->faker->randomElement(['Available', 'Available', 'Available', 'Out of Stock']),
        ];
    }

    public function available(): static
    {
        return $this->state(['availability_status' => 'Available']);
    }

    public function unavailable(): static
    {
        return $this->state(['availability_status' => 'Out of Stock']);
    }
}
