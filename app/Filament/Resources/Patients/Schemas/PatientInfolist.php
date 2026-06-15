<?php

namespace App\Filament\Resources\Patients\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;
use App\Enums\Gender;
class PatientInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('name'),
                TextEntry::make('phone'),
                TextEntry::make('gender')
                    ->formatStateUsing(fn ($state) => $state ? Gender::from($state)->label() : null),
                TextEntry::make('birth_date')
                    ->label('Age')
                    ->formatStateUsing(fn ($state) => $state?->age)
                    ->suffix(' years'),

                TextEntry::make('address')
                    ->placeholder('-'),
                TextEntry::make('notes')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('creator.name')
                    ->label('Created By')
                    ->placeholder('-'),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }
}
