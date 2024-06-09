<?php

namespace App\Models;

use App\Enums\FilterItemPatternType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FilterItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'filter_id',
        'pattern',
        'filter_type',
    ];

    protected function casts(): array
    {
        return [
            'filter_type' => FilterItemPatternType::class,
        ];
    }

    public function filterList(): BelongsTo
    {
        return $this->belongsTo(Filter::class, 'filter_id', 'id');
    }

    public function isAllowed(string $domain): bool
    {
        $pattern = $this->getAttribute('pattern');

        return match ($type = $this->getAttribute('filter_type')) {
            'exact' => $pattern === $domain,
            'regex' => preg_match($pattern, $domain),
            'wildcard' => fnmatch($pattern, $domain),
            default => throw new \Exception("filter type $type not supported")
        };
    }
}
