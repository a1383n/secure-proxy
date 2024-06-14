<?php

namespace App\Repositories;

use App\Models\FilterItem;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Query\JoinClause;

class FilterRepository
{
    public function getPatternsQuery(): Builder
    {
        return FilterItem::query()
            ->join('filters', function (JoinClause $join) {
                $join->on('filters.id', '=', 'filter_items.filter_id')
                    ->where('filters.enabled', true);
            })
            ->select(['filter_items.pattern', 'filter_items.pattern_type', 'filters.type']);
    }

    /**
     * @return Collection<FilterItem>
     */
    public function getAllowedDomainsPatterns(): Collection
    {
        return cache()->rememberForever('allowed_domains_patterns', function () {
            return $this->getPatternsQuery()
                ->where('filters.type', 'allow')
                ->get();
        });
    }

    /**
     * @return Collection<FilterItem>
     */
    public function getBypassedDomainsPatterns(): Collection
    {
        return cache()->rememberForever('bypassed_domains_patterns', function () {
            return $this->getPatternsQuery()
                ->where('filters.type', 'bypass')
                ->get();
        });
    }

    public function getBlockedDomainsPatterns(): Collection
    {
        return cache()->rememberForever('blocked_domains_patterns', function () {
            return $this->getPatternsQuery()
                ->where('filters.type', 'block')
                ->get();
        });
    }
}
