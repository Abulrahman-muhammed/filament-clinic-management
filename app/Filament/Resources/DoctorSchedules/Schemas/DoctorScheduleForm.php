<?php

namespace App\Filament\Resources\DoctorSchedules\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\TimePicker;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;
use BackedEnum;
use App\Enums\DayOfWeek;
use Filament\Forms\Components\Select;
class DoctorScheduleForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('day_of_week')
                    ->required()
                    ->options(DayOfWeek::class),
                TimePicker::make('start_time')
                    ->seconds(false)
                    ->required(),
                TimePicker::make('end_time')
                    ->seconds(false)
                    ->after('start_time')
                    ->required(),
                TextInput::make('slot_duration')
                    ->required()
                    ->numeric()
                    ->suffix(' minutes')
                    ->default(30),
                Toggle::make('is_active')
                    ->default(true),
            ]);
    }
}
