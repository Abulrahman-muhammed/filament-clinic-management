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
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Infolists\Components\TextEntry;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TimePicker;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use App\Enums\AppointmentSource;
use App\Enums\AppointmentStatus;
use App\Models\Appointment;
use Filament\Actions\ViewAction;


class AppointmentsRelationManager extends RelationManager
{
    protected static string $relationship = 'appointments';
    // view appointments in the patient profile
    
    public function infolist(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('patient.name')
                    ->label('Patient Name'),
                TextEntry::make('appointment_date')
                    ->label('Appointment Date')
                    ->date('d M Y'),
                TextEntry::make('appointment_time')
                    ->label('Appointment Time')
                    ->time('h:i A'),
                TextEntry::make('service.name')
                    ->label('Service'),
                TextEntry::make('source')
                    ->label('Source')
                    ->formatStateUsing(
                        fn ($state) => AppointmentSource::from($state)->label()
                    ),
                TextEntry::make('status')
                    ->label('Status')
                    ->badge()
                    ->formatStateUsing(
                        fn ($state) => AppointmentStatus::from($state)->label()
                    )
                    ->color(
                        fn ($state) => AppointmentStatus::from($state)->color()
                    ),
                TextEntry::make('creator.name')
                    ->label('Created By'),
                TextEntry::make('created_at')
                    ->label('Created At')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->label('Updated At')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }
    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('amount')
            ->columns([
                TextColumn::make('appointment_date')
                ->date('d M Y')
                ->sortable(),
            
            TextColumn::make('appointment_time')
                ->time('h:i A')
                ->sortable(),
            
            TextColumn::make('service.name')
                ->label('Service'),
            
            TextColumn::make('source')
                ->badge()
                ->formatStateUsing(
                    fn ($state) => AppointmentSource::from($state)->label()
                ),
            TextColumn::make('status')
                ->badge()
                ->formatStateUsing(
                    fn ($state) => AppointmentStatus::from($state)->label()
                )
                ->color(
                    fn ($state) => AppointmentStatus::from($state)->color()
                ),
            
            TextColumn::make('creator.name')
                ->label('Created By'),
            ])
            ->filters([
                //
            ])
            ->headerActions([

            ])
            ->recordActions([
                EditAction::make(),
                ViewAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                ]),
            ]);
    }
}
