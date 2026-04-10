<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RentalReturn extends Model
{
    use HasFactory;

    protected $table = 'returns';

    protected $fillable = [
        'rental_id',
        'returned_date',
        'fine_amount',
        'return_status',
    ];

    protected function casts(): array
    {
        return [
            'returned_date' => 'date',
            'fine_amount' => 'decimal:2',
        ];
    }

    public function rental(): BelongsTo
    {
        return $this->belongsTo(Rental::class);
    }
}
