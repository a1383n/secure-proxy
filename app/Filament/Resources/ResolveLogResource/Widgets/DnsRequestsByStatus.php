<?php

namespace App\Filament\Resources\ResolveLogResource\Widgets;

use App\Models\ResolveLog;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class DnsRequestsByStatus extends ChartWidget
{
    protected static ?string $heading = 'DNS Requests by Status';

    protected function getData(): array
    {
        $data = ResolveLog::query()
            ->select([
                'resolve_status',
                DB::raw('count(*) as count'),
            ])
            ->groupBy('resolve_status')
            ->get();

        $statusColors = $data->pluck('resolve_status')->map(function ($status) {
            return $status === 'failed' ? 'rgba(255, 99, 132, 0.6)' : 'rgba(75, 192, 192, 0.6)'; // red for failed, teal for others
        });

        $statusBorderColors = $data->pluck('resolve_status')->map(function ($status) {
            return $status === 'failed' ? 'rgba(255, 99, 132, 1)' : 'rgba(75, 192, 192, 1)'; // red for failed, teal for others
        });

        return [
            'datasets' => [
                [
                    'label'           => 'Resolve status',
                    'data'            => $data->pluck('count'),
                    'backgroundColor' => $statusColors,
                    'borderColor'     => $statusBorderColors,
                    'borderWidth'     => 1,
                ],
            ],
            'labels' => $data->pluck('resolve_status'),
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }
}
