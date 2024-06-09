<?php

namespace App\Services;

use App\Models\Upstream;
use App\Repositories\FilterRepository;

class ProxyService
{
    public function __construct(protected readonly FilterRepository $filterRepository)
    {
        //
    }

    public function isDomainAllowed(string $domain): bool
    {
        $allowedDomainPatterns = $this->filterRepository->getAllowedDomainsPatterns();

        foreach ($allowedDomainPatterns as $allowedDomainPattern) {
            if ($allowedDomainPattern->isAllowed($domain)) {
                return true;
            }
        }

        return false;
    }

    public function getUpstream(): string|null
    {
        return Upstream::query()
            ->where('enabled', true)
            ->first()
            ?->address;
    }
}
