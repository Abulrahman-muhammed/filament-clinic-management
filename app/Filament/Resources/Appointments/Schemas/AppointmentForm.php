<?php

namespace App\Filament\Resources\Appointments\Schemas;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TimePicker;
use Filament\Forms\Components\DatePicker;
use Filament\Schemas\Schema;
use BackedEnum;
use App\Enums\AppointmentSource;
use App\Enums\AppointmentStatus;
use Filament\Forms\Components\Select;
use App\Services\SlotGeneratorService;

class AppointmentForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
            Select::make('patient_id')
                ->relationship('patient', 'name')
                ->searchable()
                ->preload()
                ->required(),
                Select::make('service_id')
                    ->relationship('service', 'name')
                    ->searchable()
                    ->preload(),
                    DatePicker::make('appointment_date')
                        ->required()
                        ->native(false)
                        ->minDate(today())
                        ->live(),

            Select::make('appointment_time')
                ->options(function (Get $get) {

                    $date = $get('appointment_date');

                    if (! $date) {
                        return [];
                    }

                    return app(SlotGeneratorService::class)
                        ->generate($date);
                })
                ->required(),
                Select::make('source')
                    ->required()
                    ->options(AppointmentSource::class),
                Select::make('status')
                    ->required()
                    ->options(AppointmentStatus::class),
                Textarea::make('notes')
                    ->default(null)
                    ->columnSpanFull(),
            ]);
    }
}
