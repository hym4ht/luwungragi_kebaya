<?php

namespace App\Enums;

enum UserRole: string
{
    case Admin = 'admin';
    case Customer = 'customer';
    case Owner = 'owner';

    public function label(): string
    {
        return match ($this) {
            self::Admin => 'Admin',
            self::Customer => 'Penyewa',
            self::Owner => 'Owner',
        };
    }

    public function badgeClass(): string
    {
        return match ($this) {
            self::Admin => 'primary',
            self::Customer => 'warning',
            self::Owner => 'dark',
        };
    }
}
