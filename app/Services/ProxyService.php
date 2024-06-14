<?php

namespace App\Services;

use App\Enums\FilterMode;
use App\Models\FilterItem;
use App\Models\Upstream;
use App\Repositories\FilterRepository;

class ProxyService
{
    public function __construct(protected readonly FilterRepository $filterRepository)
    {
        //
    }

    public function getDomainFilterMode(string $domain): FilterMode|null
    {
        foreach ([FilterMode::ALLOW, FilterMode::BLOCK, FilterMode::BYPASS] as $filterMode) {
            if ($this->isDomainMatchFilterMode($filterMode, $domain)) {
                return $filterMode;
            }
        }

        return null;
    }

    public function isDomainMatchFilterMode(FilterMode $filterMode, string $domain): bool
    {
        $method = match ($filterMode) {
            FilterMode::ALLOW => 'getAllowedDomainsPatterns',
            FilterMode::BLOCK => 'getBlockedDomainsPatterns',
            FilterMode::BYPASS => 'getBypassedDomainsPatterns',
        };

        $result = $this->filterRepository->{$method}()
            ->first(fn(FilterItem $item) => $item->pass($domain));

        return $result !== null;
    }

    public function getUpstream(): string|null
    {
        return cache()->remember('upstream', 60, function () {
            return Upstream::query()
                ->where('enabled', true)
                ->first()
                ?->address;
        });
    }
}
