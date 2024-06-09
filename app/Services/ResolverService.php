<?php

namespace App\Services;

use Net_DNS2_Exception;
use Net_DNS2_Resolver;

class ResolverService
{
    public function resolve(string $domain): false|string
    {
        try {
            $resolver = new Net_DNS2_Resolver(['nameservers' => ['8.8.8.8']]);
            $response = $resolver->query($domain);

            $ips = [];
            foreach ($response->answer as $record) {
                if ($record->type === 'A') {
                    $ips[] = $record->address;
                }
            }

            if (empty($ips)) {
                return false;
            }

            return $ips[0];
        } catch (Net_DNS2_Exception $e) {
            report($e);
            return false;
        }
    }
}
