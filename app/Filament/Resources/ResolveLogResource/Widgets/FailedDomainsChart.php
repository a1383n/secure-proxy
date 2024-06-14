<?php

namespace App\Filament\Resources\ResolveLogResource\Widgets;

use App\Models\ResolveLog;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class FailedDomainsChart extends ChartWidget
{
    protected static ?string $heading = 'Failed Domains';

    protected int | string | array $columnSpan = 'full';

    protected function getData(): array
    {
        $data = ResolveLog::query()
            ->select([
                'domain',
                DB::raw('count(*) as total'),
            ])
            ->groupBy('domain')
            ->where('resolve_status', 'failed')
            ->whereIn('filter_status', ['allow', 'bypass'])
            ->latest('total')
            ->get();

        return [
            'datasets' => [
                [
                    'label' => 'Failed Domains',
                    'data' => $data->pluck('total'),
                ]
            ],
            'labels' => $data->pluck('domain'),
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
