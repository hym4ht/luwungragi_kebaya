<?php

namespace App\Enums;

enum PaymentStatus: string
{
    case Pending = 'pending';
    case Settlement = 'settlement';
    case Expire = 'expire';
    case Cancel = 'cancel';

    public function label(): string
    {
        return match ($this) {
            self::Pending => 'Pending',
            self::Settlement => 'Lunas',
            self::Expire => 'Kedaluwarsa',
            self::Cancel => 'Dibatalkan',
        };
    }

    public function badgeClass(): string
    {
        return match ($this) {
            self::Pending => 'warning',
            self::Settlement => 'success',
            self::Expire => 'secondary',
            self::Cancel => 'danger',
        };
    }
}
