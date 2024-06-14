<?php

namespace App\Providers;

use Carbon\CarbonInterval;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use Stevebauman\Location\Facades\Location;
use Stevebauman\Location\Position;
use Symfony\Component\HttpFoundation\IpUtils;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        Location::macro('fetchMany', function ($ipAddresses): array {
            if (empty($ipAddresses)) {
                return [];
            }

            $ipAddresses = collect(Arr::wrap($ipAddresses));
            $positions = collect();
            $privateIps = $ipAddresses->filter(fn($ip) => IpUtils::isPrivateIp($ip));
            $publicIps = $ipAddresses->diff($privateIps);

            $privateIps->each(function ($ip) use (&$positions) {
                $position = new Position();
                $position->countryName = 'Private Network';
                $positions->put($ip, $position->toArray());
            });

            $publicIps->chunk(50)
                ->each(function ($publicIps) use (&$positions) {
                    $cacheKeys = $publicIps->mapWithKeys(fn($ip) => [$ip => 'ip_address_geo:' . $ip]);

                    $cachedResults = collect(cache()->many($cacheKeys->values()->all()));

                    $cachedResults->each(function ($result, $cacheKey) use (&$positions, $cacheKeys) {
                        if ($result) {
                            $ip = $cacheKeys->search($cacheKey);
                            $positions->put($ip, $result);
                        }
                    });

                    $ipsToFetch = $publicIps->diff($positions->keys());
                    $results = collect();

                    $ipsToFetch->each(function ($ip) use (&$results, &$positions) {
                        if (($result = $this->get($ip)) !== false) {
                            $results['ip_address_geo:' . $ip] = $positions[$ip] = collect($result->toArray())
                                ->mapWithKeys(fn($value, $key) => [Str::snake($key) => $value])
                                ->toArray();
                        }
                    });

                    if ($results->isNotEmpty()) {
                        DB::table('ip_geolocation_temp')
                            ->insertOrIgnore($results->values()->toArray());

                        cache()->putMany($results->toArray(), CarbonInterval::day()->totalSeconds);
                    }
                });

            return $positions
                ->map(function ($position) {
                    return collect($position)->mapWithKeys(fn($item, $key) => [Str::snake($key) => $item]);
                })
                ->toArray();
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
