<?php

namespace App\Filament\Pages;

use App\Settings\ClinicSettings;
use BackedEnum;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use UnitEnum;

class Settings extends Page implements HasForms
{
    use InteractsWithForms;

    protected static string|UnitEnum|null $navigationGroup = 'Settings';
    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-cog-6-tooth';
    protected static ?string $navigationLabel = 'Settings';

    public function getView(): string
    {
        return 'filament.pages.settings';
    }

    public ?array $data = [];

    public function mount(ClinicSettings $settings): void
    {
        $this->form->fill([
            'clinic_name'        => $settings->clinic_name,
            'logo'         => $settings->logo ? [$settings->logo => $settings->logo] : null,
            'phone'              => $settings->phone,
            'whatsapp'           => $settings->whatsapp,
            'email'              => $settings->email,
            'address'            => $settings->address,
            'google_maps'        => $settings->google_maps,

            'doctor_name'        => $settings->doctor_name,
            'doctor_image' => $settings->doctor_image ? [$settings->doctor_image => $settings->doctor_image] : null,
            'specialization'     => $settings->specialization,
            'experience_years'   => $settings->experience_years,
            'consultation_fee'   => $settings->consultation_fee,
            'working_hours'      => $settings->working_hours,

            'allow_booking'      => $settings->allow_booking,
            'allow_ai'           => $settings->allow_ai,
            'allow_online_payment' => $settings->allow_online_payment,
        ]);
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->statePath('data')
            ->components([

                Section::make('Clinic Information')
                    ->columns(2)
                    ->schema([
                        FileUpload::make('logo')
                            ->image()
                            ->disk('public')
                            ->directory('clinic')
                            ->moveFiles()
                            ->visibility('public')
                            ->columnSpanFull(),

                        TextInput::make('clinic_name')
                            ->required(),

                        TextInput::make('phone'),

                        TextInput::make('whatsapp'),

                        TextInput::make('email')
                            ->email(),

                        Textarea::make('address')
                            ->columnSpanFull(),

                        TextInput::make('google_maps')
                            ->columnSpanFull()
                            ->url()
                            ->placeholder('https://maps.google.com/...'),
                    ]),

                Section::make('Doctor Information')
                    ->columns(2)
                    ->schema([
                        FileUpload::make('doctor_image')
                            ->image()
                            ->disk('public')
                            ->directory('clinic')
                            ->visibility('public')
                            ->moveFiles()
                            ->columnSpanFull(),

                        TextInput::make('doctor_name'),

                        TextInput::make('specialization'),

                        TextInput::make('experience_years')
                            ->numeric(),

                        TextInput::make('consultation_fee')
                            ->numeric()
                            ->prefix('EGP'),

                        TextInput::make('working_hours')
                            ->numeric()
                            ->suffix(' hours')
                            ->helperText('Total working hours per day')
                            ->columnSpanFull(),
                    ]),

                Section::make('Features')
                    ->schema([
                        Toggle::make('allow_booking'),
                        Toggle::make('allow_ai'),
                        Toggle::make('allow_online_payment'),
                    ]),

            ]);
    }

    public function save(ClinicSettings $settings): void
    {
        $data = $this->form->getState();

        $data['logo'] = $this->extractFilePath($data['logo']);
        $data['doctor_image'] = $this->extractFilePath($data['doctor_image']);

        foreach ($data as $key => $value) {
            $settings->{$key} = $value;
        }

        $settings->save();

        Notification::make()
            ->title('Settings Saved Successfully')
            ->success()
            ->send();
    }

    private function extractFilePath(mixed $value): ?string
    {
        if (empty($value)) {
            return null;
        }

        // Already a plain string path
        if (is_string($value)) {
            return $value;
        }

        if (is_array($value)) {
            $first = array_values($value)[0];

            // ['uuid' => 'clinic/path.jpg'] — existing file, value is a string
            if (is_string($first)) {
                return $first;
            }

            // ['uuid' => ['livewire-file:...', ...]] — new upload not yet resolved
            // Let getState() handle it; this shouldn't happen after getState(), but just in case
            if (is_array($first)) {
                return null;
            }
        }

        return null;
    }
}