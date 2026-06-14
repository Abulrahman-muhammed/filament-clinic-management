<?php

namespace App\Filament\Resources\Appointments\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Filters\SelectFilter;

use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use App\Enums\AppointmentSource;
use App\Enums\AppointmentStatus;
use App\Enums\VisitStatus;
use Filament\Actions\Action;
use App\Models\Visit;
use App\Filament\Resources\Visits\VisitResource;

class AppointmentsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('patient.name')
                    ->label('Patient')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('creator.name')
                    ->label('Created By')
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('service.name')
                    ->label('Service')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('appointment_date')
                    ->date('d M Y')
                    ->sortable(),
                TextColumn::make('appointment_time')
                    ->time('h:i A')
                    ->sortable(),

                TextColumn::make('source')
                    ->badge()
                    ->formatStateUsing(
                        fn ($state) => AppointmentSource::from($state)->label()
                    ),
                TextColumn::make('status')
                    ->badge()
                    ->formatStateUsing(
                        fn ($state) => AppointmentStatus::from($state)->label()
                    )
                    ->color(
                        fn ($state) => AppointmentStatus::from($state)->color()
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

                ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
                Action::make('createVisit')
                    ->label('Create Visit')
                    ->icon('heroicon-o-heart')
                    ->color('success')
                    ->requiresConfirmation()
                    ->visible(fn ($record) => ! $record->visit)
                    ->action(function ($record) {

                        $visit = Visit::create([
                            'patient_id'     => $record->patient_id,
                            'appointment_id' => $record->id,
                            'status'         => VisitStatus::Waiting->value,
                            'visited_at'     => now(),
                        ]);

                        $record->update([
                            'status' => AppointmentStatus::CheckedIn->value,
                        ]);

                        redirect(
                            VisitResource::getUrl('edit', [
                                'record' => $visit,
                            ])
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
