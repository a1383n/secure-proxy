<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ResolveLog extends Model
{
    const UPDATED_AT = null;

    protected $fillable = [
        'client_ip',
        'domain',
        'resolve_status',
        'resolved_ip',
        'filter_status'
    ];
}
