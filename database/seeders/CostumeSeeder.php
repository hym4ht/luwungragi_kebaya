<?php

namespace Database\Seeders;

use App\Models\Costume;
use Illuminate\Database\Seeder;

class CostumeSeeder extends Seeder
{
    public function run(): void
    {
        foreach ($this->catalogSeedData() as $costume) {
            Costume::query()->updateOrCreate(
                ['name' => $costume['name']],
                array_merge($costume, [
                    'description' => $this->buildDescription($costume['name'], $costume['category']),
                    'materials' => $this->buildMaterials($costume['category']),
                    'care_instructions' => $this->buildCareInstructions($costume['category']),
                    'sizes' => $this->buildSizes($costume['category']),
                ])
            );
        }
    }

    private function catalogSeedData(): array
    {
        return [
            ['name' => 'Kebaya Brokat Akad Putih Gading', 'category' => 'Kebaya', 'stock' => 4, 'rental_price' => 350000, 'availability_status' => 'Available'],
            ['name' => 'Kebaya Modern Dusty Rose', 'category' => 'Kebaya', 'stock' => 3, 'rental_price' => 420000, 'availability_status' => 'Available'],
            ['name' => 'Kebaya Encim Biru Tosca', 'category' => 'Kebaya', 'stock' => 5, 'rental_price' => 385000, 'availability_status' => 'Available'],
            ['name' => 'Kebaya Kartini Kuning Mustard', 'category' => 'Kebaya', 'stock' => 6, 'rental_price' => 310000, 'availability_status' => 'Available'],
            ['name' => 'Kebaya Pengantin Full Brukat Merah', 'category' => 'Kebaya', 'stock' => 2, 'rental_price' => 500000, 'availability_status' => 'Available'],
            ['name' => 'Kebaya Kutubaru Hijau Sage', 'category' => 'Kebaya', 'stock' => 4, 'rental_price' => 295000, 'availability_status' => 'Available'],
            ['name' => 'Kebaya Tile Champagne Payet', 'category' => 'Kebaya', 'stock' => 3, 'rental_price' => 465000, 'availability_status' => 'Available'],
            ['name' => 'Kebaya Resepsi Lavender Embroidery', 'category' => 'Kebaya', 'stock' => 2, 'rental_price' => 440000, 'availability_status' => 'Available'],
            ['name' => 'Kebaya Organza Pearl Nude', 'category' => 'Kebaya', 'stock' => 4, 'rental_price' => 390000, 'availability_status' => 'Available'],

            ['name' => 'Beskap Jawa Klasik Hitam', 'category' => 'Kostum Adat', 'stock' => 5, 'rental_price' => 275000, 'availability_status' => 'Available'],
            ['name' => 'Baju Adat Bali Couple', 'category' => 'Kostum Adat', 'stock' => 2, 'rental_price' => 560000, 'availability_status' => 'Available'],
            ['name' => 'Surjan Lurik Coklat Tua', 'category' => 'Kostum Adat', 'stock' => 7, 'rental_price' => 250000, 'availability_status' => 'Available'],
            ['name' => 'Pakaian Adat Sunda Merah', 'category' => 'Kostum Adat', 'stock' => 3, 'rental_price' => 320000, 'availability_status' => 'Available'],
            ['name' => 'Baju Adat Minang Gold', 'category' => 'Kostum Adat', 'stock' => 2, 'rental_price' => 480000, 'availability_status' => 'Available'],
            ['name' => 'Pakaian Adat Betawi Pengantin', 'category' => 'Kostum Adat', 'stock' => 0, 'rental_price' => 600000, 'availability_status' => 'Out of Stock'],
            ['name' => 'Bodo Makassar Orange Sunset', 'category' => 'Kostum Adat', 'stock' => 4, 'rental_price' => 355000, 'availability_status' => 'Available'],
            ['name' => 'Ulos Batak Ceremony Set', 'category' => 'Kostum Adat', 'stock' => 3, 'rental_price' => 425000, 'availability_status' => 'Available'],
            ['name' => 'Teluk Belanga Melayu Emerald', 'category' => 'Kostum Adat', 'stock' => 5, 'rental_price' => 335000, 'availability_status' => 'Available'],

            ['name' => 'Gaun Tari Tradisional Merah Marun', 'category' => 'Kostum Tari', 'stock' => 6, 'rental_price' => 300000, 'availability_status' => 'Available'],
            ['name' => 'Kostum Tari Topeng Betawi', 'category' => 'Kostum Tari', 'stock' => 4, 'rental_price' => 340000, 'availability_status' => 'Available'],
            ['name' => 'Gaun Tari Bali Kuning Keemasan', 'category' => 'Kostum Tari', 'stock' => 5, 'rental_price' => 375000, 'availability_status' => 'Available'],
            ['name' => 'Baju Tari Serimpi Hijau', 'category' => 'Kostum Tari', 'stock' => 3, 'rental_price' => 290000, 'availability_status' => 'Available'],
            ['name' => 'Kostum Wayang Orang Putih', 'category' => 'Kostum Tari', 'stock' => 2, 'rental_price' => 360000, 'availability_status' => 'Available'],
            ['name' => 'Kostum Tari Saman Aceh Black Gold', 'category' => 'Kostum Tari', 'stock' => 4, 'rental_price' => 345000, 'availability_status' => 'Available'],
            ['name' => 'Kostum Tari Piring Minang Ruby', 'category' => 'Kostum Tari', 'stock' => 3, 'rental_price' => 355000, 'availability_status' => 'Available'],
            ['name' => 'Kostum Tari Jaipong Fuchsia', 'category' => 'Kostum Tari', 'stock' => 5, 'rental_price' => 325000, 'availability_status' => 'Available'],
            ['name' => 'Kostum Tari Merak Royal Blue', 'category' => 'Kostum Tari', 'stock' => 2, 'rental_price' => 410000, 'availability_status' => 'Available'],

            ['name' => 'Kostum Wisuda Nusantara', 'category' => 'Kostum Event', 'stock' => 3, 'rental_price' => 240000, 'availability_status' => 'Available'],
            ['name' => 'Baju Karnaval Batik Pekalongan', 'category' => 'Kostum Event', 'stock' => 8, 'rental_price' => 200000, 'availability_status' => 'Available'],
            ['name' => 'Kostum Hari Kemerdekaan Pahlawan', 'category' => 'Kostum Event', 'stock' => 5, 'rental_price' => 275000, 'availability_status' => 'Available'],
            ['name' => 'Setelan Batik Formal Motif Parang', 'category' => 'Kostum Event', 'stock' => 6, 'rental_price' => 225000, 'availability_status' => 'Available'],
            ['name' => 'Kostum Ondel-Ondel Betawi Mini', 'category' => 'Kostum Event', 'stock' => 2, 'rental_price' => 450000, 'availability_status' => 'Available'],
            ['name' => 'Kostum MC Adat Nusantara Ivory', 'category' => 'Kostum Event', 'stock' => 4, 'rental_price' => 260000, 'availability_status' => 'Available'],
            ['name' => 'Setelan Kartini Family Day Navy', 'category' => 'Kostum Event', 'stock' => 6, 'rental_price' => 235000, 'availability_status' => 'Available'],
            ['name' => 'Kostum Parade Sekolah Majapahit', 'category' => 'Kostum Event', 'stock' => 4, 'rental_price' => 280000, 'availability_status' => 'Available'],
            ['name' => 'Costume Gala Heritage Black Gold', 'category' => 'Kostum Event', 'stock' => 1, 'rental_price' => 520000, 'availability_status' => 'Available'],
        ];
    }

    private function buildDescription(string $name, string $category): string
    {
        return match ($category) {
            'Kebaya' => $name . ' menghadirkan siluet anggun bernuansa tradisi modern, ideal untuk akad, wisuda, sesi foto, dan acara keluarga dengan tampilan yang tetap ringan dipakai.',
            'Kostum Adat' => $name . ' dirancang untuk penampilan adat yang rapi dan berwibawa, cocok untuk prosesi budaya, pawai sekolah, pentas daerah, hingga event seremonial.',
            'Kostum Tari' => $name . ' memberi ruang gerak yang nyaman dengan detail panggung yang menonjol, pas untuk pertunjukan tari, lomba, dokumentasi, dan penampilan tematik.',
            'Kostum Event' => $name . ' cocok untuk kegiatan seremonial, karnaval, graduation, dan event tematik yang membutuhkan outfit standout namun tetap praktis saat digunakan.',
            default => $name . ' merupakan koleksi unggulan Luwungragi dengan tampilan elegan dan nyaman untuk berbagai acara.',
        };
    }

    private function buildMaterials(string $category): string
    {
        return match ($category) {
            'Kebaya' => "Brokat premium\nFuring satin adem\nAksen payet dan bordir halus",
            'Kostum Adat' => "Kain tenun pilihan\nLapisan satin berstruktur\nAksesori pelengkap sesuai set",
            'Kostum Tari' => "Satin premium ringan\nTile fleksibel untuk gerak\nOrnamen panggung tahan tampil",
            'Kostum Event' => "Katun premium nyaman\nSatin kombinasi rapi\nDetail motif dekoratif",
            default => "Bahan pilihan premium\nJahitan rapi\nFinishing halus",
        };
    }

    private function buildCareInstructions(string $category): string
    {
        return match ($category) {
            'Kebaya' => "Dry clean lebih disarankan\nSimpan dengan hanger di garment bag\nHindari parfum langsung pada payet",
            'Kostum Adat' => "Lipat rapi setelah digunakan\nSimpan di tempat sejuk dan kering\nBersihkan aksesori secara terpisah",
            'Kostum Tari' => "Angin-anginkan setelah pentas\nJangan diperas untuk menjaga bentuk\nGunakan laundry khusus kostum bila perlu",
            'Kostum Event' => "Cuci lembut atau dry clean sesuai detail\nSetrika suhu rendah di bagian dalam\nSimpan terpisah dari aksesori tajam",
            default => "Simpan di tempat kering\nHindari panas berlebih\nGunakan perawatan profesional bila perlu",
        };
    }

    private function buildSizes(string $category): string
    {
        return match ($category) {
            'Kebaya' => 'XS, S, M, L, XL',
            'Kostum Adat' => 'S, M, L, XL',
            'Kostum Tari' => 'S, M, L',
            'Kostum Event' => 'S, M, L, XL, XXL',
            default => 'S, M, L, XL',
        };
    }
}
