<?php

namespace App\Repositories;

use App\Models\DomainFilterItem;
use App\Repositories\Attributes\Cache;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Query\JoinClause;

class DomainFilterRepository extends Repository
{
    #[Cache]
    public function getDomains(string $type): Collection
    {
        return DomainFilterItem::query()
            ->join('domain_filters', function (JoinClause $join) {
                $join->on('domain_filters.id', '=', 'domain_filter_items.filter_id')
                    ->where('domain_filters.enabled', true);
            })
            ->where('domain_filters.type', '=', $type)
            ->distinct()
            ->get(['domain_filter_items.pattern', 'domain_filter_items.pattern_type']);
    }
}
