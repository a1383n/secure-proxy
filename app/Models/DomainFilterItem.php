<?php

namespace App\Models;

use App\Enums\FilterItemPatternType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DomainFilterItem extends Model
{
    protected $fillable = [
        'filter_id',
        'pattern',
        'pattern_type',
    ];

    protected static function booted(): void
    {
        $purgeCacheClosure = function () {
            cache()->tags(['App\Repositories\DomainFilterRepository'])->flush();
        };

        static::created($purgeCacheClosure);
        static::updated($purgeCacheClosure);
        static::deleted($purgeCacheClosure);
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
            FilterItemPatternType::DOMAIN   => self::isPassedPattern(FilterItemPatternType::EXACT, $pattern, $value) || self::isPassedPattern(FilterItemPatternType::WILDCARD, '*.'.$pattern, $value)
        };
    }

    public function pass(string $domain): bool
    {
        return self::isPassedPattern($this->pattern_type, $this->pattern, $domain);
    }
}
