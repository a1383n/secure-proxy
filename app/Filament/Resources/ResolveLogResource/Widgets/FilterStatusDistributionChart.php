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

        $statusColors = $data->pluck('filter_status')->map(function ($status) {
            return match ($status) {
                'blocked' => 'rgba(255, 99, 132, 0.6)',
                'allowed' => 'rgba(75, 192, 192, 0.6)',
                'pending' => 'rgba(255, 206, 86, 0.6)',
                default   => 'rgba(201, 203, 207, 0.6)',
            };
        });

        $statusBorderColors = $data->pluck('filter_status')->map(function ($status) {
            return match ($status) {
                'block'  => 'rgba(255, 99, 132, 1)',
                'allow'  => 'rgba(75, 192, 192, 1)',
                'bypass' => 'rgba(255, 206, 86, 1)',
                default  => 'rgba(201, 203, 207, 1)',
            };
        });

        return [
            'datasets' => [
                [
                    'label'           => 'DNS Requests',
                    'data'            => $data->pluck('count'),
                    'backgroundColor' => $statusColors,
                    'borderColor'     => $statusBorderColors,
                    'borderWidth'     => 1,
                ],
            ],
            'labels' => $data->pluck('filter_status'),
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }
}
