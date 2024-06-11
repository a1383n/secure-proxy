<?php

namespace App\Filament\Resources;

use App\Filament\Exports\ResolveLogExporter;
use App\Filament\Resources\ResolveLogResource\Pages;
use App\Filament\Resources\ResolveLogResource\Widgets\DnsRequestsByClientIpChart;
use App\Filament\Resources\ResolveLogResource\Widgets\DnsRequestsByStatus;
use App\Filament\Resources\ResolveLogResource\Widgets\DnsRequestsOverTimeChart;
use App\Filament\Resources\ResolveLogResource\Widgets\FilterStatusDistributionChart;
use App\Filament\Resources\ResolveLogResource\Widgets\TopDomainsByRequestsChart;
use App\Models\ResolveLog;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\ExportAction;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;

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
            ->columns([
                Tables\Columns\TextColumn::make('client_ip')
                    ->searchable(),
                Tables\Columns\TextColumn::make('domain')
                    ->searchable(),
                Tables\Columns\TextColumn::make('filter_status')
                    ->searchable(),
                Tables\Columns\TextColumn::make('resolve_status')
                    ->searchable(),
                Tables\Columns\TextColumn::make('resolved_ip')
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                //
            ])
            ->headerActions([
                ExportAction::make()
                    ->exporter(ResolveLogExporter::class)
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\ExportBulkAction::make()
                        ->exporter(ResolveLogExporter::class),
                    Tables\Actions\DeleteBulkAction::make()
                ])
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

    public static function getWidgets(): array
    {
        return [
            ResolveLogResource\Widgets\StatsOverview::class,
            DnsRequestsOverTimeChart::class,
            TopDomainsByRequestsChart::class,
            DnsRequestsByStatus::class,
            FilterStatusDistributionChart::class
        ];
    }
}
