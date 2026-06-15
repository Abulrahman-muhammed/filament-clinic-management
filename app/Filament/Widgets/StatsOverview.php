<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\Patient;
use App\Models\Appointment;
use App\Models\Visit;
use App\Models\Payment;
use App\Enums\PaymentStatus;
class StatsOverview extends StatsOverviewWidget
{
    protected static ?int $sort = 1;
    protected function getStats(): array
    {
        return [
            Stat::make(
                'Total Patients',
                Patient::count()
            )->description('Total number of registered patients')
            ->descriptionIcon('heroicon-s-users')
            ->color('success')
            ,
            Stat::make(
                'Today Appointments',
                Appointment::whereDate(
                    'appointment_date',
                    today()
                )->count()
            )->description('Appointments scheduled for today')
            ->descriptionIcon('heroicon-s-calendar')
            ->color('warning')
            ,
            Stat::make(
                'Today Visits',
                Visit::whereDate(
                    'visited_at',
                    today()
                )->count()
            )->description('Visits scheduled for today')
            ->descriptionIcon('heroicon-s-user')
            ->color('info')
            ,
            Stat::make(
                'Revenue Today',
                number_format(
                    Payment::where(
                        'status',
                        PaymentStatus::Paid
                    )
                    ->whereDate(
                        'created_at',
                        today()
                    )
                    ->sum('amount'), 2
                ).' EGP'
            )->description('Total revenue for today')
            ->descriptionIcon('heroicon-s-currency-dollar')
            ->color('success')
        ];
    }
}
