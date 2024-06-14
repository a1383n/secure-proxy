<?php

namespace App\Models;

use App\Enums\DomianFilterType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DomainFilter extends Model
{
    protected $fillable = [
        'name',
        'type',
        'enabled',
    ];

    protected function casts(): array
    {
        return [
            'type'    => DomianFilterType::class,
            'enabled' => 'bool',
        ];
    }

    public function items(): HasMany
    {
        return $this->hasMany(DomainFilterItem::class, 'filter_id');
    }
}
