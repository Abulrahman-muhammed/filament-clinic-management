<?php

namespace App\Filament\Resources\DoctorSchedules\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use App\Enums\DayOfWeek;
class DoctorSchedulesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('day_of_week')
                    ->label('Day')
                    ->formatStateUsing(
                        fn ($state) => $state ? DayOfWeek::from($state)->label() : null
                    )
                    ->sortable(),
                TextColumn::make('start_time')
                    ->time('H:i A')
                    ->sortable(),
                TextColumn::make('end_time')
                    ->time('H:i A')
                    ->sortable(),
                TextColumn::make('slot_duration')
                    ->numeric()
                    ->suffix(' minutes')
                    ->sortable(),
                TextColumn::make('is_active')
                    ->badge()
                    ->formatStateUsing(
                        fn ($state) => $state ? 'Active' : 'Inactive'
                    )
                    ->color(
                        fn ($state) => $state ? 'success' : 'danger'
                    ),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
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
