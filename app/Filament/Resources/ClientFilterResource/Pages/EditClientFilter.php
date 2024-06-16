<?php

namespace App\Filament\Resources\ClientFilterResource\Pages;

use App\Filament\Resources\ClientFilterResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditClientFilter extends EditRecord
{
    protected static string $resource = ClientFilterResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
