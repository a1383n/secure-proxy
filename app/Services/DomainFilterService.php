<?php

namespace App\Services;

use App\Enums\DomianFilterType;
use App\Models\DomainFilterItem;
use App\Repositories\DomainFilterRepository;
use Illuminate\Support\Str;

readonly class DomainFilterService
{
    public function __construct(protected DomainFilterRepository $repository)
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
        $result = $this->repository->getDomains(Str::lower($filterMode->value))
            ->first(fn (DomainFilterItem $item) => $item->pass($domain));

        return $result !== null;
    }
}
