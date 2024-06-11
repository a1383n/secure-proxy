<?php

namespace App\Filament\Resources\ResolveLogResource\Widgets;

use App\Models\ResolveLog;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class FilterStatusDistributionChart extends ChartWidget
{
    protected static ?string $heading = 'Filter Status Distribution';

    protected function getData(): array
    {
        $data = ResolveLog::query()
            ->select([
                'filter_status',
                DB::raw('count(*) as count'),
            ])
            ->groupBy('filter_status')
            ->get();

        return [
            'datasets' => [
                [
                    'label' => 'DNS Requests',
                    'data' => $data->pluck('count'),
                ]
            ],
            'labels' => $data->pluck('filter_status')
        ];
    }

    protected function getType(): string
    {
        return 'pie';
    }
}
