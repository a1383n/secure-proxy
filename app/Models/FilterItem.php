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
        'pattern_type',
    ];

    protected static function booted()
    {
        static::created(function ($filterItem) {
            cache()->deleteMultiple(['allowed_domains_patterns', 'bypassed_domains_patterns', 'blocked_domains_patterns']);
        });
    }

    protected function casts(): array
    {
        return [
            'pattern_type' => FilterItemPatternType::class,
        ];
    }

    public function filterList(): BelongsTo
    {
        return $this->belongsTo(Filter::class, 'filter_id', 'id');
    }

    public function pass(string $domain): bool
    {
        $pattern = $this->getAttribute('pattern');

        return match ($this->pattern_type) {
            FilterItemPatternType::WILDCARD => fnmatch($pattern, $domain),
            FilterItemPatternType::REGEX    => preg_match($pattern, $domain),
            FilterItemPatternType::EXACT    => $pattern === $domain,
        };
    }
}
