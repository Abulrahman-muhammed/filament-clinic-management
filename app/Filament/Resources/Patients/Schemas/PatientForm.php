<?php

namespace App\Filament\Resources\Patients\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Select;
use Filament\Schemas\Schema;
use BackedEnum;
use App\Enums\Gender;
class PatientForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                
                TextInput::make('name')
                    ->required(),
                TextInput::make('phone')
                    ->tel()
                    ->unique(ignoreRecord: true)
                    ->required(),
                Select::make('gender')
                    ->required()
                    ->options(Gender::class),

                DatePicker::make('birth_date')
                    ->required(),

                Textarea::make('address')
                    ->nullable()
                    ->columnSpanFull(),


                Textarea::make('notes')
                    ->nullable()
                    ->columnSpanFull(),

            ]);
    }
}
