<?php

namespace App\Filament\Resources\DoctorSchedules\Schemas;

use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class DoctorScheduleInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('day_of_week')
                    ->label('Day')
                    ->formatStateUsing(
                        fn ($state) => $state ? \App\Enums\DayOfWeek::from($state)->label() : null
                    ),
                TextEntry::make('start_time')
                    ->time('H:i A'),
                TextEntry::make('end_time')
                    ->time('H:i A'),
                TextEntry::make('slot_duration')
                    ->suffix(' minutes')
                    ->numeric(),
                IconEntry::make('is_active')
                    ->boolean(),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }
}
