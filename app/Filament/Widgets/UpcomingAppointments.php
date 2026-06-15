<?php

namespace App\Filament\Widgets;

use Filament\Actions\BulkActionGroup;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Database\Eloquent\Builder;
use App\Models\Appointment;
use App\Models\Visit;
use App\Models\Payment;
use App\Models\Patient;
use App\Enums\PaymentStatus;
use App\Enums\AppointmentStatus;
use Filament\Tables\Columns\TextColumn;
class UpcomingAppointments extends TableWidget
{
    protected static ?int $sort = 2;
    public function table(Table $table): Table
    {

        return $table
                ->query(
                    Appointment::query()
                        ->whereDate('appointment_date', '>=', today())
                    ->whereNotIn('status', [
                        AppointmentStatus::Cancelled->value,
                        AppointmentStatus::NoShow->value,
                    ])
                    ->orderBy('appointment_date')
                    ->orderBy('appointment_time')
                    ->limit(10)
            )

            ->columns([
                TextColumn::make('patient.name')->label('Patient')->sortable()->searchable(),
                TextColumn::make('service.name')
                    ->label('Service'),
                TextColumn::make('appointment_date')
                    ->label('Date')
                    ->date('d M Y')
                    ->sortable(),
                TextColumn::make('appointment_time')
                    ->label('Time')
                    ->time('h:i A')
                    ->sortable(),
                TextColumn::make('status')
                    ->badge()
                    ->formatStateUsing(
                        fn ($state) => AppointmentStatus::from($state)->label()
                    )
                    ->color(
                        fn ($state) => AppointmentStatus::from($state)->color()
                    ),
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
