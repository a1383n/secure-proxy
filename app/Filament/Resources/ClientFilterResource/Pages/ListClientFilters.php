<?php

namespace App\Filament\Resources\ClientFilterResource\Pages;

use App\Filament\Resources\ClientFilterResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListClientFilters extends ListRecords
{
    protected static string $resource = ClientFilterResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
