<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FilterItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'filter_list_id',
        'pattern',
        'filter_type',
    ];

    public function filterList(): BelongsTo
    {
        return $this->belongsTo(Filter::class);
    }
}
