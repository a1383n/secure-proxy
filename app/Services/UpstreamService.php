<?php

namespace App\Services;

use App\Models\Upstream;

class UpstreamService
{
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
