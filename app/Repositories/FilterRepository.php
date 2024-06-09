<?php

namespace App\Repositories;

use App\Models\FilterItem;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

class FilterRepository
{

    /**
     * @return Collection<FilterItem>
     */
    public function getAllowedDomainsPatterns() : Collection
    {
        return FilterItem::query()
            ->whereHas('filterList', function (Builder $query) {
                $query->where('type', 'allow')
                    ->where('enabled', true);
            })
            ->get();
    }
}
