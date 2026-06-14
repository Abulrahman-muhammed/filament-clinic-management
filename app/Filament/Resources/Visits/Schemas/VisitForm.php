<?php

namespace App\Filament\Resources\Visits\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;
use Filament\Forms\Components\Select;
use App\Enums\VisitStatus;

class VisitForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('patient_id')
                    ->relationship('patient', 'name')
                    ->disabled()
                    ->dehydrated(),
                Select::make('appointment_id')
                    ->relationship('appointment', 'id')
                    ->disabled()
                    ->dehydrated(),
                Select::make('status')
                    ->options(VisitStatus::class)
                    ->required(),
                DateTimePicker::make('visited_at')
                    ->default(now())
                    ->required(),
                Textarea::make('complaint')
                    ->rows(3)
                    ->required()
                    ->columnSpanFull(),
                Textarea::make('diagnosis')
                    ->rows(5)
                    ->columnSpanFull(),
                Textarea::make('notes')
                    ->rows(3)
                    ->columnSpanFull(),

            ]);
    }
}
