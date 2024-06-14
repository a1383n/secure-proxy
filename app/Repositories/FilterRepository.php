<?php

namespace App\Repositories;

use App\Models\DomainFilterItem;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Query\JoinClause;

class FilterRepository
{
    public function getPatternsQuery(): Builder
    {
        return DomainFilterItem::query()
            ->join('domain_filters', function (JoinClause $join) {
                $join->on('domain_filters.id', '=', 'domain_filter_items.filter_id')
                    ->where('domain_filters.enabled', true);
            })
            ->select(['domain_filter_items.pattern', 'domain_filter_items.pattern_type', 'domain_filters.type']);
    }

    /**
     * @return Collection<DomainFilterItem>
     */
    public function getAllowedDomainsPatterns(): Collection
    {
        return cache()->rememberForever('allowed_domains_patterns', function () {
            return $this->getPatternsQuery()
                ->where('domain_filters.type', 'allow')
                ->get();
        });
    }

    /**
     * @return Collection<DomainFilterItem>
     */
    public function getBypassedDomainsPatterns(): Collection
    {
        return cache()->rememberForever('bypassed_domains_patterns', function () {
            return $this->getPatternsQuery()
                ->where('domain_filters.type', 'bypass')
                ->get();
        });
    }

    public function getBlockedDomainsPatterns(): Collection
    {
        return cache()->rememberForever('blocked_domains_patterns', function () {
            return $this->getPatternsQuery()
                ->where('domain_filters.type', 'block')
                ->get();
        });
    }
}
