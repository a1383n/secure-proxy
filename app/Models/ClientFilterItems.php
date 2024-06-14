<?php

namespace App\Models;

use App\Enums\ClientFilterType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ClientFilterItems extends Model
{
    protected $fillable = [
        'filter_id',
        'name',
        'ip_address',
        'type'
    ];

    protected function casts(): array
    {
        return [
            'type' => ClientFilterType::class
        ];
    }

    public function filter(): BelongsTo
    {
        return $this->belongsTo(ClientFilter::class, 'id', 'filter_id');
    }
}
