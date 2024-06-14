<?php

namespace App\Filament\Resources\ResolveLogResource\Widgets;

use App\Filament\Resources\ResolveLogResource;
use App\Models\ResolveLog;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;
use Stevebauman\Location\Facades\Location;

class ResolvedDomainsLocationChart extends ChartWidget
{
    protected static ?string $heading = 'Resolved Domains Location Chart';

    protected int | string | array $columnSpan = 'full';

    protected function getData(): array
    {
        $data = ResolveLog::query()
            ->leftJoin('ip_geolocation_temp', 'resolved_ip', '=', 'ip')
            ->selectRaw('COALESCE(`ip_geolocation_temp`.`country_name`, `resolved_ip`) AS cname')
            ->selectRaw('COUNT(IF(`ip_geolocation_temp`.`country_name` IS NOT NULL, 1, NULL)) AS count_with_country')
            ->selectRaw('COUNT(IF(`ip_geolocation_temp`.`country_name` IS NULL, 1, NULL)) AS count_without_country')
            ->where('resolve_status', '=', 'resolved')
            ->whereNotNull('resolved_ip')
            ->groupBy(DB::raw('COALESCE(`ip_geolocation_temp`.`country_name`, `resolved_ip`)'))
            ->get();

        $ipsToResolve = $data
            ->filter(fn($value) => $value->count_with_country == 0)
            ->pluck('cname')
            ->toArray();

        $resolvedPositions = Location::fetchMany($ipsToResolve);

        $resolvedPositions = collect($resolvedPositions)
            ->mapWithKeys(function ($position, $ip) {
                return [$ip => $position['country_name']];
            });

        $result = $data->map(function ($value) use ($resolvedPositions) {
            if ($value->count_with_country == 0) {
                $value->cname = $resolvedPositions[$value->cname];
            }

            return [
                'country_name' => $value->cname,
                'total' => $value->count_with_country + $value->count_without_country,
            ];
        })
            ->groupBy('country_name')
            ->mapWithKeys(function ($value, $key) {
                return [$key => $value->sum(fn($item) => $item['total'])];
            })
            ->sortDesc();

        return [
            'datasets' => [
                [
                    'label' => 'Resolved Locations',
                    'data' => $result->values(),
                ]
            ],
            'labels' => $result->keys(),
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
