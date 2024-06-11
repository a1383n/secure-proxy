<?php

namespace App\Filament\Resources\ResolveLogResource\Widgets;

use App\Models\ResolveLog;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        $totalRequests = ResolveLog::count();
        $successfulRequests = ResolveLog::where('resolve_status', 'resolved')->count();
        $failureRequests = ResolveLog::where('resolve_status', 'failed')->count();
//        $averageResolutionTime = ResolveLog::avg('resolution_time');
        $uniqueClientIPs = ResolveLog::distinct('client_ip')->count();

        return [
            Stat::make('Total Requests', $totalRequests),
            Stat::make('Successful Requests', $successfulRequests)
                ->description('Success Rate: ' . number_format(($successfulRequests / $totalRequests) * 100, 2) . '%'),
            Stat::make('Failure Requests', $failureRequests)
                ->description('Failure Rate: ' . number_format(($failureRequests / $totalRequests) * 100, 2) . '%'),
//            Stat::make('Average Resolution Time', round($averageResolutionTime, 2) . ' seconds')
//                ->description('Average resolution time for all requests'),
            Stat::make('Unique Client IPs', $uniqueClientIPs)
                ->description('Number of unique client IP addresses making DNS requests'),
        ];
    }

}
