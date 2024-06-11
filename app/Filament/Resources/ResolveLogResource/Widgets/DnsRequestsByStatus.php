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

        return [
            'datasets' => [
                [
                    'label' => 'DNS Requests',
                    'data' => $data->pluck('count'),
                ]
            ],
            'labels' => $data->pluck('resolve_status')
        ];
    }

    protected function getType(): string
    {
        return 'pie';
    }
}
