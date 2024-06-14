<?php

namespace App\Services;

use App\Enums\DomianFilterType;
use App\Models\DomainFilterItem;
use App\Models\Upstream;
use App\Repositories\FilterRepository;

class ProxyService
{
    public function __construct(protected readonly FilterRepository $filterRepository)
    {
        //
    }

    public function getDomainFilterMode(string $domain): DomianFilterType|null
    {
        foreach ([DomianFilterType::ALLOW, DomianFilterType::BLOCK, DomianFilterType::BYPASS] as $filterMode) {
            if ($this->isDomainMatchFilterMode($filterMode, $domain)) {
                return $filterMode;
            }
        }

        return null;
    }

    public function isDomainMatchFilterMode(DomianFilterType $filterMode, string $domain): bool
    {
        $method = match ($filterMode) {
            DomianFilterType::ALLOW  => 'getAllowedDomainsPatterns',
            DomianFilterType::BLOCK  => 'getBlockedDomainsPatterns',
            DomianFilterType::BYPASS => 'getBypassedDomainsPatterns',
        };

        $result = $this->filterRepository->{$method}()
            ->first(fn (DomainFilterItem $item) => $item->pass($domain));

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
