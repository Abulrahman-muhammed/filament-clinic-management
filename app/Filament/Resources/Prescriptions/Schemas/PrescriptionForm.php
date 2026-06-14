<?php

namespace App\Filament\Resources\Prescriptions\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
class PrescriptionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('visit_id')
                    ->relationship('visit', 'id')
                    ->disabled(),
                Textarea::make('notes')
                    ->columnSpanFull(),
                Repeater::make('items')
                ->relationship()
                ->schema([

                    TextInput::make('medicine_name')
                        ->required(),

                    TextInput::make('dosage')
                        ->placeholder('1 Tablet'),

                    TextInput::make('frequency')
                        ->placeholder('3 Times Daily'),

                    TextInput::make('duration')
                        ->placeholder('5 Days'),

                    Textarea::make('instructions')
                        ->columnSpanFull(),

                ])
                ->columns(2)
                ->defaultItems(1)
                ->collapsible()
                ->cloneable()
                ->columnSpanFull(),
    
    ]);
    }
}
