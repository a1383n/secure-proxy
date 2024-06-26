<?php

namespace App\Filament\Resources\ResolveLogResource\Widgets;

use App\Models\ResolveLog;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class TopClientsRequestsChart extends ChartWidget
{
    protected static ?string $heading = 'Top Clients Requests';

    protected int|string|array $columnSpan = 'full';

    public ?string $filter = '10';

    protected function getFilters(): ?array
    {
        return [
            '5'  => 'Top 5',
            '10' => 'Top 10',
            '20' => 'Top 20',
            '30' => 'Top 30',
            '40' => 'Top 40',
            '50' => 'Top 50',
        ];
    }

    protected function getData(): array
    {
        $todayData = ResolveLog::query()
            ->select([
                'client_ip',
                DB::raw('count(*) as total'),
            ])
            ->groupBy('client_ip')
            ->orderByDesc('total')
            ->take($this->filter)
            ->get();

        return [
            'datasets' => [
                [
                    'label' => 'Top Clients',
                    'data'  => $todayData->pluck('total'),
                ],
            ],
            'labels' => $todayData->pluck('client_ip'),
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
