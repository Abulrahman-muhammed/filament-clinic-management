<?php

namespace App\Filament\Resources\Prescriptions\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;

class PrescriptionsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
            TextColumn::make('visit.patient.name')
                ->label('Patient')
                ->searchable(),

            TextColumn::make('items_count')
                ->counts('items')
                ->label('Medicines'),

            TextColumn::make('created_at')
                ->dateTime('d M Y h:i A'),
                ])
            ->filters([
                //
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
