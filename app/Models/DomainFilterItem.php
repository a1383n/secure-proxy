<?php

namespace App\Models;

use App\Enums\FilterItemPatternType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DomainFilterItem extends Model
{
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

    public function filter(): BelongsTo
    {
        return $this->belongsTo(DomainFilter::class, 'filter_id', 'id');
    }

    protected static function isPassedPattern(FilterItemPatternType $patternType, string $pattern, string $value): bool
    {
        return match ($patternType) {
            FilterItemPatternType::WILDCARD => fnmatch($pattern, $value),
            FilterItemPatternType::REGEX    => preg_match($pattern, $value),
            FilterItemPatternType::EXACT    => $pattern === $value,
            FilterItemPatternType::DOMAIN   => self::isPassedPattern(FilterItemPatternType::EXACT, $pattern, $value) || self::isPassedPattern(FilterItemPatternType::WILDCARD, '*.' . $pattern, $value)
        };
    }

    public function pass(string $domain): bool
    {
        return self::isPassedPattern($this->pattern_type, $this->pattern, $domain);
    }
}
