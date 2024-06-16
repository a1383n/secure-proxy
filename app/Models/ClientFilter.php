<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ClientFilter extends Model
{
    protected $fillable = [
        'name',
        'enabled',
    ];

    protected function casts(): array
    {
        return [
            'enabled' => 'boolean',
        ];
    }

    public function items(): HasMany
    {
        return $this->hasMany(ClientFilterItems::class, 'filter_id', 'id');
    }
}
