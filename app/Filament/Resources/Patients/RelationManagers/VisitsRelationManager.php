<?php

namespace App\Filament\Resources\Patients\RelationManagers;

use Filament\Actions\AssociateAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\DissociateAction;
use Filament\Actions\DissociateBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use App\Enums\VisitStatus;
use App\Enums\AppointmentSource;
use App\Enums\AppointmentStatus;
use App\Models\Appointment;


class VisitsRelationManager extends RelationManager
{
    protected static string $relationship = 'visits';


    public function infolist(Schema $schema): Schema
    {
        return $schema
            ->components([
                
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('patient')
            ->columns([
                TextColumn::make('visited_at')
                ->label('Visit Date')
                ->dateTime('d M Y h:i A')
                ->sortable(),
            
                TextColumn::make('status')
                    ->badge()
                    ->formatStateUsing(
                        fn ($state) => VisitStatus::from($state)->label()
                    )
                    ->color(
                        fn ($state) => VisitStatus::from($state)->color()
                    ),
            
                TextColumn::make('complaint')
                    ->limit(40)
                    ->searchable(),
            
                TextColumn::make('diagnosis')
                    ->limit(50),
                    
                    ])
            ->filters([
                //
            ])
            ->toolbarActions([
                // ViewAction::make(),
                // EditAction::make(),
            ]);
    }
}
