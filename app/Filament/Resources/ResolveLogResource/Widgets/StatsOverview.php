<?php

namespace App\Filament\Resources\ResolveLogResource\Widgets;

use App\Models\ResolveLog;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\DB;

class StatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        $totalRequests = ResolveLog::count();
        $successfulRequests = ResolveLog::where('resolve_status', 'resolved')->count();
        $failureRequests = ResolveLog::where('resolve_status', 'failed')->count();
        $uniqueResolvedIPs = ResolveLog::count(DB::raw('distinct `resolved_ip`'));
        $uniqueClientIPs = ResolveLog::count(DB::raw('distinct `client_ip`'));

        return [
            Stat::make('Successful Requests', $successfulRequests)
                ->description('Success Rate: '.number_format(($successfulRequests / $totalRequests) * 100, 2).'%'),
            Stat::make('Failure Requests', $failureRequests)
                ->description('Failure Rate: '.number_format(($failureRequests / $totalRequests) * 100, 2).'%'),
            Stat::make('Unique Resolved IPs', $uniqueResolvedIPs)
                ->description('Number of unique resolved IP addresses that fetch for DNS answers'),
            Stat::make('Unique Client IPs', $uniqueClientIPs)
                ->description('Number of unique client IP addresses making DNS requests'),
        ];
    }
}
