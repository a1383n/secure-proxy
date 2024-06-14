<?php

namespace App\Services;

use Net_DNS2_Exception;
use Net_DNS2_Resolver;

class ResolverService
{
    public function resolve(string $domain): false|string
    {
        try {
            if ($ip = cache()->get('domain$' . $domain)) {
                return $ip;
            }

            $resolver = new Net_DNS2_Resolver(['nameservers' => ['8.8.8.8']]);
            $response = $resolver->query($domain);

            $ips = [];
            $ttl = 0;
            foreach ($response->answer as $record) {
                if ($record->type === 'A') {
                    $ttl = $record->ttl;
                    $ips[] = $record->address;
                    break;
                }
            }

            if (empty($ips)) {
                return false;
            }

            $ip = $ips[0];
            cache()->put('domain$' . $domain, $ip, $ttl);

            return $ip;
        } catch (Net_DNS2_Exception $e) {
            report($e);
            return false;
        }
    }
}
