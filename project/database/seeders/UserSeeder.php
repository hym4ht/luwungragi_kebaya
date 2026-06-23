<?php

namespace Database\Seeders;

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Admin
        User::query()->updateOrCreate(
            ['email' => 'admin@luwungragi.test'],
            [
                'name'     => 'Admin Luwungragi',
                'password' => Hash::make('password123'),
                'role'     => UserRole::Admin,
            ]
        );

        // Owner
        User::query()->updateOrCreate(
            ['email' => 'owner@luwungragi.test'],
            [
                'name'     => 'Owner Luwungragi',
                'password' => Hash::make('password123'),
                'role'     => UserRole::Owner,
            ]
        );

        // Customers dengan data realistis nama Jawa/Indonesia
        $customers = [
            ['name' => 'Ratri Kusuma Dewi',     'email' => 'ratri@luwungragi.test'],
            ['name' => 'Dewi Larasati',          'email' => 'dewi@luwungragi.test'],
            ['name' => 'Bagas Pradipta Nugroho', 'email' => 'bagas@luwungragi.test'],
            ['name' => 'Sari Wulandari',         'email' => 'sari@luwungragi.test'],
            ['name' => 'Andi Kurniawan',         'email' => 'andi@luwungragi.test'],
            ['name' => 'Mega Putri Lestari',     'email' => 'mega@luwungragi.test'],
            ['name' => 'Rizky Aditya Pratama',   'email' => 'rizky@luwungragi.test'],
            ['name' => 'Nurul Hidayah',          'email' => 'nurul@luwungragi.test'],
            ['name' => 'Fajar Santoso',          'email' => 'fajar@luwungragi.test'],
            ['name' => 'Indah Permatasari',      'email' => 'indah@luwungragi.test'],
        ];

        foreach ($customers as $customer) {
            User::query()->updateOrCreate(
                ['email' => $customer['email']],
                [
                    'name'     => $customer['name'],
                    'password' => Hash::make('password123'),
                    'role'     => UserRole::Customer,
                ]
            );
        }
    }
}
