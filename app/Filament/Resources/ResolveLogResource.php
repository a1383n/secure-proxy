<?php

namespace App\Filament\Resources;

use App\Filament\Exports\ResolveLogExporter;
use App\Filament\Resources\ResolveLogResource\Pages;
use App\Models\ResolveLog;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\ExportAction;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Stevebauman\Location\Facades\Location;

class ResolveLogResource extends Resource
{
    protected static ?string $model = ResolveLog::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function canCreate(): bool
    {
        return false;
    }

    public static function canEdit(Model $record): bool
    {
        return false;
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                Tables\Columns\TextColumn::make('client_ip')
                    ->icon(fn (string $state): string => 'flag-country-'.Str::lower(Location::fetch($state)['country_code'] ?? 'XX'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('domain')
                    ->searchable(),
                Tables\Columns\TextColumn::make('filter_status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'bypass' => 'info',
                        'allow'  => 'success',
                        'block'  => 'warning',
                        default  => 'primary'
                    }),
                Tables\Columns\TextColumn::make('resolve_status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'resolved' => 'success',
                        'failed'   => 'danger',
                        default    => 'primary'
                    }),
                Tables\Columns\TextColumn::make('resolved_ip')
                    ->icon(fn (string $state): string => 'flag-country-'.Str::lower(Location::fetch($state)['country_code'] ?? 'XX'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->since()
                    ->sortable()
                    ->toggleable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('filter_status')
                    ->multiple()
                    ->options([
                        'bypass' => 'Bypassed',
                        'allow'  => 'Accepted',
                        'block'  => 'Blocked',
                    ]),
                Tables\Filters\SelectFilter::make('resolve_status')
                    ->options([
                        'resolved' => 'Resolved',
                        'failed'   => 'Failed',
                    ]),
            ])
            ->actions([
                //
            ])
            ->headerActions([
                ExportAction::make()
                    ->exporter(ResolveLogExporter::class),
                Tables\Actions\Action::make('truncate')
                    ->label('Truncate')
                    ->icon('heroicon-o-trash')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->action(function () {
                        ResolveLog::truncate();

                        Notification::make()
                            ->title('Table truncated')
                            ->success()
                            ->send();
                    }),

            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\ExportBulkAction::make()
                        ->exporter(ResolveLogExporter::class),
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListResolveLogs::route('/'),
        ];
    }
}
