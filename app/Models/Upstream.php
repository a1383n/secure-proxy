<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Upstream extends Model
{
    use HasFactory;

    protected $fillable = [
      'name',
      'address',
      'enabled'
    ];

    protected function casts(): array
    {
        return [
            'enabled' => 'bool'
        ];
    }
}
