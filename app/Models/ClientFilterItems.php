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
        'type',
    ];

    protected function casts(): array
    {
        return [
            'type' => ClientFilterType::class,
        ];
    }

    protected static function booted(): void
    {
        $purgeCacheClosure = function () {
            cache()->tags(['App\Repositories\ClientFilterRepository'])->flush();
        };

        static::created($purgeCacheClosure);
        static::updated($purgeCacheClosure);
        static::deleted($purgeCacheClosure);
    }

    public function filter(): BelongsTo
    {
        return $this->belongsTo(ClientFilter::class, 'id', 'filter_id');
    }
}
