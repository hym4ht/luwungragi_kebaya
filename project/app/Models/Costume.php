<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Costume extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'category',
        'stock',
        'rental_price',
        'availability_status',
        'image',
        'image_2',
        'image_3',
        'image_4',
        'description',
        'materials',
        'care_instructions',
        'sizes',
    ];

    protected function casts(): array
    {
        return [
            'rental_price' => 'decimal:2',
        ];
    }

    public function rentalDetails(): HasMany
    {
        return $this->hasMany(RentalDetail::class);
    }

    public function scopeSearch(Builder $query, ?string $keyword): Builder
    {
        return $query->when($keyword, function (Builder $builder, string $search): void {
            $builder->where(function (Builder $nestedBuilder) use ($search): void {
                $nestedBuilder
                    ->where('name', 'like', '%'.$search.'%')
                    ->orWhere('category', 'like', '%'.$search.'%');
            });
        });
    }
}
