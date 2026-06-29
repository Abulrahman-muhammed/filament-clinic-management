<?php

namespace App\Filament\Resources\Patients\RelationManagers;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use App\Enums\PaymentMethod;
use App\Enums\PaymentStatus;
class PaymentsRelationManager extends RelationManager
{
    protected static string $relationship = 'payments';

    public function infolist(Schema $schema): Schema
    {
        return $schema
            ->components([
                    TextEntry::make('visit.visited_at')
                        ->label('Visit Date')
                        ->dateTime('d M Y h:i A'),
                    TextEntry::make('amount')
                    ->money('EGP'),
                TextEntry::make('payment_method')
                    ->badge()
                    ->formatStateUsing(
                        fn ($state) => PaymentMethod::from($state)->label()
                    )
                    ->color(
                        fn ($state) => PaymentMethod::from($state)->color()
                    ),
                TextEntry::make('status')
                    ->badge()
                    ->formatStateUsing(
                        fn ($state) => PaymentStatus::from($state)->label()
                    )
                    ->color(
                        fn ($state) => PaymentStatus::from($state)->color()
                    ),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('amount')
            ->columns([
                TextColumn::make('amount')
                ->money('EGP')
                ->sortable(),
            
            TextColumn::make('visit.visited_at')
                ->label('Visit Date')
                ->dateTime('d M Y h:i A')
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
            ->headerActions([
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
