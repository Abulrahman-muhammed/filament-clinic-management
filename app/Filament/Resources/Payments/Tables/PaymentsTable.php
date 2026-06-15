<?php

namespace App\Filament\Resources\Payments\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use App\Enums\PaymentMethod;
use App\Enums\PaymentStatus;
class PaymentsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('visit.patient.name')
                    ->label('Patient')
                    ->searchable(),

                TextColumn::make('amount')
                    ->money('EGP')
                    ->sortable(),

                TextColumn::make('payment_method')
                    ->badge()
                    ->formatStateUsing(
                        fn ($state) => PaymentMethod::from($state)->label()
                    ),

                TextColumn::make('status')
                    ->badge()
                    ->formatStateUsing(
                        fn ($state) => PaymentStatus::from($state)->label()
                    )
                    ->color(
                        fn ($state) => PaymentStatus::from($state)->color()
                    ),

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
