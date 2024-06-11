<?php

namespace App\Filament\Resources\ResolveLogResource\Widgets;

use App\Models\ResolveLog;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class DnsRequestsOverTimeChart extends ChartWidget
{
    protected static ?string $heading = 'DNS Requests Over Time';
    protected int | string | array $columnSpan = 'full';
    public ?string $filter = 'minute';

    protected function getFilters(): ?array
    {
        return [
            'minute' => 'Last minute',
            'hour' => 'Last hour',
            'day' => 'Last day',
            'week' => 'Last week',
            'month' => 'Last month',
            'year' => 'This year',
        ];
    }

    protected function getData(): array
    {

        $data = ResolveLog::query()
            ->select([
                'resolve_status',
                DB::raw('count(*) as total')
            ])
            ->groupBy(['resolve_status', 'timestamp'])
            ->oldest('timestamp');

        switch ($this->filter) {
            case 'minute':
                $data->addSelect(DB::raw('DATE_FORMAT(`created_at`, \'%H:%i:%s\') AS timestamp'))
                    ->whereRaw('`created_at` >= NOW() - INTERVAL 1 MINUTE');
                break;
            case 'hour':
                $data->addSelect(DB::raw('DATE_FORMAT(`created_at`, \'%H:%i\') AS timestamp'))
                    ->whereRaw('`created_at` >= NOW() - INTERVAL 1 HOUR');
                break;
            case 'day':
                $data->addSelect(DB::raw('DATE_FORMAT(`created_at`, \'%H\') AS timestamp'))
                    ->whereRaw('`created_at` >= NOW() - INTERVAL 1 DAY');
                break;
            case 'week':
                $data->addSelect(DB::raw('DATE_FORMAT(`created_at`, \'%W\') AS timestamp'))
                    ->whereRaw('`created_at` >= NOW() - INTERVAL 7 DAY');
                break;
            case 'month':
                $data->addSelect(DB::raw('DATE_FORMAT(`created_at`, \'%D\') AS timestamp'))
                    ->whereRaw('`created_at` >= NOW() - INTERVAL 1 MONTH');
                break;
            case 'year':
                $data->addSelect(DB::raw('DATE_FORMAT(`created_at`, \'%M\') AS timestamp'))
                    ->whereRaw('`created_at` >= NOW() - INTERVAL 1 YEAR');
                break;
        }

        $data = $data->get();

        return [
            'datasets' => [
                [
                    'label' => 'Success Requests',
                    'data' => $data->where('resolve_status', 'resolved')->pluck('total'),
                ],
                [
                    'label' => 'Failed Requests',
                    'data' =>  $data->where('resolve_status', 'failed')->pluck('total'),
                ]
            ],
            'labels' => $data->pluck('timestamp'),
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
