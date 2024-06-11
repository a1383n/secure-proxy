<?php

namespace App\Models;

use App\Enums\FilterMode;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Filter extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'type',
        'enabled',
    ];

    protected function casts(): array
    {
        return [
            'type' => FilterMode::class,
            'enabled' => 'bool'
        ];
    }

    public function filterItems(): HasMany
    {
        return $this->hasMany(FilterItem::class);
    }
}
