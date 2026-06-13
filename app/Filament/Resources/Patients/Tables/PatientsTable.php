<?php

namespace App\Filament\Resources\Patients\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use App\Enums\Gender;
use App\Models\User;
use Carbon\Carbon;
use Filament\Tables\Filters\SelectFilter;
class PatientsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
            TextColumn::make('id')
                ->sortable()
                ->searchable(),
                TextColumn::make('name')
                    ->searchable(),
                TextColumn::make('phone')
                    ->searchable(),
                TextColumn::make('gender')
                    ->formatStateUsing(fn ($state) => $state ? Gender::from($state)->label() : null)
                    ->color(
                        fn ($state) => $state ? Gender::from($state)->color() : null,
                    ),

            TextColumn::make('birth_date')
                ->label('Age')
                ->formatStateUsing(
                    fn ($state) => $state
                        ? Carbon::parse($state)->age
                        : null
                )
                ->suffix(' years')
                ->sortable(),


                TextColumn::make('address')
                    ->searchable(),
                TextColumn::make('creator.name')
                    ->label('Created By')
                    ->sortable(),
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
                SelectFilter::make('gender')
                    ->options(Gender::class),
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
