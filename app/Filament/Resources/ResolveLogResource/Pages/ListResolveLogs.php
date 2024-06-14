<?php

namespace App\Filament\Resources\ResolveLogResource\Pages;

use App\Filament\Resources\ResolveLogResource;
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
