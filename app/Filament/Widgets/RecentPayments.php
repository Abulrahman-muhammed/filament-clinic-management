<?php

namespace App\Filament\Widgets;

use App\Models\Payment;
use Filament\Actions\BulkActionGroup;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Database\Eloquent\Builder;
use App\Enums\PaymentStatus;
use App\Enums\PaymentMethod;

class RecentPayments extends TableWidget
{
    protected static ?int $sort = 3;
    public function table(Table $table): Table
    {
        return $table
            ->query(fn (): Builder => Payment::query() 
                ->with('visit.patient')
                ->orderByDesc('created_at')
                ->limit(10)
            )
            ->columns([
                TextColumn::make('visit.patient.name')
                    ->label('Patient')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('amount')
                    ->numeric()
                    ->money('EGP')
                    ->sortable(),
                TextColumn::make('payment_method')
                    ->label('Payment Method')
                    ->formatStateUsing(function ($state) {
                        return PaymentMethod::from($state)->label();    
                    })
                    ->sortable(),
                TextColumn::make('status')
                    ->label('Status')
                    ->formatStateUsing(function ($state) {
                        return PaymentStatus::from($state)->label();    
                    })
                    ->color(
                        fn ($state) => PaymentStatus::from($state)->color()
                    )
                    ->sortable(),
                TextColumn::make('created_at')
                    ->label('Paid At')
                    ->dateTime('d M Y h:i A')
                    ->sortable(),

            ])
            ->filters([
                //
            ])
            ->headerActions([
                //
            ])
            ->recordActions([
                //
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    //
                ]),
            ]);
    }
}
