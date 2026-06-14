<?php

namespace App\Filament\Resources\Visits\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class VisitInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('patient.name'),
                TextEntry::make('appointment_id')
                    ->prefix('#')
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('status')
                    ->numeric(),
                TextEntry::make('complaint')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('diagnosis')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('notes')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('visited_at')
                    ->label('Visited At')
                    ->dateTime(),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }
}
