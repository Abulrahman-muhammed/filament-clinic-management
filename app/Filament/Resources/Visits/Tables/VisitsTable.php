<?php

namespace App\Filament\Resources\Visits\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use App\Enums\VisitStatus;
use Filament\Actions\Action;
use App\Models\Prescription;
use App\Filament\Resources\Prescriptions\PrescriptionResource;
use App\Models\Payment;
use App\Enums\PaymentStatus;
use App\Enums\PaymentMethod;
use App\Filament\Resources\Payments\PaymentResource;
use App\Filament\Resources\Visits\VisitResource;

class VisitsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('patient.name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('complaint')
                    ->limit(50),
                TextColumn::make('status')
                    ->badge()
                    ->formatStateUsing(
                        fn ($state) => VisitStatus::from($state)->label()
                    )
                    ->color(
                        fn ($state) => VisitStatus::from($state)->color()
                    ),
                TextColumn::make('created_at')
                    ->label('Visited At')
                    ->dateTime('d M Y h:i A')
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
                Action::make('createPrescription')
                    ->label('Prescription')
                    ->icon('heroicon-o-document-text')
                    ->color('success')
                    ->requiresConfirmation()
                    ->visible(fn ($record) => ! $record->prescription)
                    ->action(function ($record) {

                        $prescription = Prescription::create([
                            'visit_id' => $record->id,
                        ]);


                        redirect(
                            PrescriptionResource::getUrl(
                                'edit',
                                ['record' => $prescription]
                            )
                        );
                    }),

            Action::make('createPayment')
            ->label('Payment')
            ->icon('heroicon-o-banknotes')
            ->color('primary')
            ->requiresConfirmation()
            ->visible(fn ($record) => ! $record->payment)
            ->action(function ($record) {

                $payment = Payment::create([
                    'visit_id' => $record->id,
                    'amount' => $record->appointment->service->price ?? 0,
                    'status' => PaymentStatus::Pending->value,
                    'payment_method' => PaymentMethod::Cash->value,
                ]);

                redirect(
                    PaymentResource::getUrl(
                        'edit',
                        ['record' => $payment]
                    )
                );
            }),
            ])

            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
