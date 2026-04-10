<?php

namespace Database\Seeders;

use App\Models\Costume;
use Illuminate\Database\Seeder;

class CostumeSeeder extends Seeder
{
    public function run(): void
    {
        $costumes = [
            // ── Kebaya ──────────────────────────────────────────────────
            [
                'name'                => 'Kebaya Brokat Akad Putih Gading',
                'category'            => 'Kebaya',
                'stock'               => 4,
                'rental_price'        => 350000,
                'availability_status' => 'Available',
            ],
            [
                'name'                => 'Kebaya Modern Dusty Rose',
                'category'            => 'Kebaya',
                'stock'               => 3,
                'rental_price'        => 420000,
                'availability_status' => 'Available',
            ],
            [
                'name'                => 'Kebaya Encim Biru Tosca',
                'category'            => 'Kebaya',
                'stock'               => 5,
                'rental_price'        => 385000,
                'availability_status' => 'Available',
            ],
            [
                'name'                => 'Kebaya Kartini Kuning Mustard',
                'category'            => 'Kebaya',
                'stock'               => 6,
                'rental_price'        => 310000,
                'availability_status' => 'Available',
            ],
            [
                'name'                => 'Kebaya Pengantin Full Brukat Merah',
                'category'            => 'Kebaya',
                'stock'               => 2,
                'rental_price'        => 500000,
                'availability_status' => 'Available',
            ],
            [
                'name'                => 'Kebaya Kutubaru Hijau Sage',
                'category'            => 'Kebaya',
                'stock'               => 4,
                'rental_price'        => 295000,
                'availability_status' => 'Available',
            ],

            // ── Kostum Adat ─────────────────────────────────────────────
            [
                'name'                => 'Beskap Jawa Klasik Hitam',
                'category'            => 'Kostum Adat',
                'stock'               => 5,
                'rental_price'        => 275000,
                'availability_status' => 'Available',
            ],
            [
                'name'                => 'Baju Adat Bali Couple',
                'category'            => 'Kostum Adat',
                'stock'               => 2,
                'rental_price'        => 560000,
                'availability_status' => 'Available',
            ],
            [
                'name'                => 'Surjan Lurik Coklat Tua',
                'category'            => 'Kostum Adat',
                'stock'               => 7,
                'rental_price'        => 250000,
                'availability_status' => 'Available',
            ],
            [
                'name'                => 'Pakaian Adat Sunda Merah',
                'category'            => 'Kostum Adat',
                'stock'               => 3,
                'rental_price'        => 320000,
                'availability_status' => 'Available',
            ],
            [
                'name'                => 'Baju Adat Minang Gold',
                'category'            => 'Kostum Adat',
                'stock'               => 2,
                'rental_price'        => 480000,
                'availability_status' => 'Available',
            ],
            [
                'name'                => 'Pakaian Adat Betawi Pengantin',
                'category'            => 'Kostum Adat',
                'stock'               => 1,
                'rental_price'        => 600000,
                'availability_status' => 'Not Available',
            ],

            // ── Kostum Tari ─────────────────────────────────────────────
            [
                'name'                => 'Gaun Tari Tradisional Merah Marun',
                'category'            => 'Kostum Tari',
                'stock'               => 6,
                'rental_price'        => 300000,
                'availability_status' => 'Available',
            ],
            [
                'name'                => 'Kostum Tari Topeng Betawi',
                'category'            => 'Kostum Tari',
                'stock'               => 4,
                'rental_price'        => 340000,
                'availability_status' => 'Available',
            ],
            [
                'name'                => 'Gaun Tari Bali Kuning Keemasan',
                'category'            => 'Kostum Tari',
                'stock'               => 5,
                'rental_price'        => 375000,
                'availability_status' => 'Available',
            ],
            [
                'name'                => 'Baju Tari Serimpi Hijau',
                'category'            => 'Kostum Tari',
                'stock'               => 3,
                'rental_price'        => 290000,
                'availability_status' => 'Available',
            ],
            [
                'name'                => 'Kostum Wayang Orang Putih',
                'category'            => 'Kostum Tari',
                'stock'               => 2,
                'rental_price'        => 360000,
                'availability_status' => 'Available',
            ],

            // ── Kostum Event ────────────────────────────────────────────
            [
                'name'                => 'Kostum Wisuda Nusantara',
                'category'            => 'Kostum Event',
                'stock'               => 3,
                'rental_price'        => 240000,
                'availability_status' => 'Available',
            ],
            [
                'name'                => 'Baju Karnaval Batik Pekalongan',
                'category'            => 'Kostum Event',
                'stock'               => 8,
                'rental_price'        => 200000,
                'availability_status' => 'Available',
            ],
            [
                'name'                => 'Kostum Hari Kemerdekaan Pahlawan',
                'category'            => 'Kostum Event',
                'stock'               => 5,
                'rental_price'        => 275000,
                'availability_status' => 'Available',
            ],
            [
                'name'                => 'Setelan Batik Formal Motif Parang',
                'category'            => 'Kostum Event',
                'stock'               => 6,
                'rental_price'        => 225000,
                'availability_status' => 'Available',
            ],
            [
                'name'                => 'Kostum Ondel-Ondel Betawi Mini',
                'category'            => 'Kostum Event',
                'stock'               => 2,
                'rental_price'        => 450000,
                'availability_status' => 'Available',
            ],
        ];

        foreach ($costumes as $costume) {
            Costume::query()->updateOrCreate(
                ['name' => $costume['name']],
                $costume
            );
        }
    }
}
