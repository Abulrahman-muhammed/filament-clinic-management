<?php

namespace App\Filament\Resources\Payments\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;
use Filament\Forms\Components\Select;
use App\Enums\PaymentMethod;
use App\Enums\PaymentStatus;

class PaymentForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('visit_id')
                    ->relationship('visit', 'id')
                    ->disabled()
                    ->dehydrated(),

                TextInput::make('amount')
                    ->numeric()
                    ->required()
                    ->prefix('EGP'),

                Select::make('payment_method')
                    ->options(PaymentMethod::class)
                    ->required(),

                Select::make('status')
                    ->options(PaymentStatus::class)
                    ->default(PaymentStatus::Pending->value)
                    ->required(),

                Textarea::make('notes')
                    ->columnSpanFull(),
            ]);
    }
}
