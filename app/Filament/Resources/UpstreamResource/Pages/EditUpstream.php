<?php

namespace App\Filament\Resources\UpstreamResource\Pages;

use App\Filament\Resources\UpstreamResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditUpstream extends EditRecord
{
    protected static string $resource = UpstreamResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
