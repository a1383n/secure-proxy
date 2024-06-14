<?php

namespace App\Filament\Resources\ResolveLogResource\Pages;

use App\Filament\Resources\ResolveLogResource;
use Filament\Actions;
use Filament\Facades\Filament;
use Filament\Resources\Pages\ListRecords;

class ListResolveLogs extends ListRecords
{
    protected static string $resource = ResolveLogResource::class;

    protected function getHeaderActions(): array
    {
        return [

        ];
    }
}
