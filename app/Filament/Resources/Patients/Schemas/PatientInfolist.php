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
                TextEntry::make('name')->weight('bold'),
                TextEntry::make('phone')->copyable(),
                TextEntry::make('gender')
                    ->badge()
                    ->formatStateUsing(fn ($state) => $state ? Gender::from($state)->label() : null)
                    ->color(fn ($state) => $state ? Gender::from($state)->color() : null),
                TextEntry::make('birth_date')
                    ->label('Age')
                    ->formatStateUsing(fn ($state) => $state?->age)
                    ->suffix(' years'),
                TextEntry::make('appointments_count')
                    ->label('Appointments')
                    ->counts('appointments')
                    ->badge()
                    ->formatStateUsing(fn ($state) => $state ? $state : null)
                    ->color(fn ($state) => $state ? 'primary' : null),
                    // last visit date
                    TextEntry::make('last_visit_date')
                    ->label('Last Visit Date')
                    ->state(fn ($record) => $record->visits()
                        ->latest('visited_at')
                        ->value('visited_at'))
                    ->dateTime('d M Y h:i A'),

                TextEntry::make('visits_count')
                    ->label('Visits')
                    ->counts('visits')
                    ->badge()
                    ->formatStateUsing(fn ($state) => $state ? $state : null)
                    ->color(fn ($state) => $state ? 'primary' : null),
                TextEntry::make('prescriptions_count')
                    ->label('Prescriptions')
                    ->counts('prescriptions')
                    ->badge()
                    ->formatStateUsing(fn ($state) => $state ? $state : null)
                    ->color(fn ($state) => $state ? 'primary' : null),
                TextEntry::make('payments_count')
                    ->label('Payments')
                    ->counts('payments')
                    ->badge()
                    ->formatStateUsing(fn ($state) => $state ? $state : null)
                    ->color(fn ($state) => $state ? 'primary' : null),
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
