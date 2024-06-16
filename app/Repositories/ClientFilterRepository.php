<?php

namespace App\Repositories;

use App\Models\ClientFilterItems;
use App\Repositories\Attributes\Cache;
use Illuminate\Database\Query\JoinClause;

class ClientFilterRepository extends Repository
{
    #[Cache]
    protected function getIPs(string $type): array
    {
        return ClientFilterItems::query()
            ->join('client_filters', function (JoinClause $join) {
                $join->on('client_filter_items.filter_id', '=', 'client_filters.id')
                    ->where('client_filters.enabled', '=', true);
            })
            ->where('client_filter_items.type', $type)
            ->distinct()
            ->pluck('client_filter_items.ip_address')
            ->toArray();
    }
}
