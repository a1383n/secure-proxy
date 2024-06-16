<?php

namespace App\Services;

use App\Repositories\ClientFilterRepository;
use Symfony\Component\HttpFoundation\IpUtils;

class ClientFilterService
{
    public function __construct(protected readonly ClientFilterRepository $repository)
    {
        //
    }

    public function isClientAllowed(string $clientIpAddress): bool
    {
        $blockedIPs = $this->repository->getIPs('block');
        foreach ($blockedIPs as $blockedIP) {
            if (IpUtils::checkIp($clientIpAddress, $blockedIP)) {
                return false;
            }
        }

        $allowedIPs = $this->repository->getIPs('allow');
        foreach ($allowedIPs as $allowedIP) {
            if (IpUtils::checkIp($clientIpAddress, $allowedIP)) {
                return true;
            }
        }

        return false;
    }
}
