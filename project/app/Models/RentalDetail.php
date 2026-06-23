<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RentalDetail extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'rental_id',
        'costume_id',
        'quantity',
        'unit_price',
    ];

    protected function casts(): array
    {
        return [
            'unit_price' => 'decimal:2',
        ];
    }

    public function rental(): BelongsTo
    {
        return $this->belongsTo(Rental::class);
    }

    public function costume(): BelongsTo
    {
        return $this->belongsTo(Costume::class);
    }
}
