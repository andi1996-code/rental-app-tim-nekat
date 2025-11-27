<?php

namespace App\Filament\Widgets;

use App\Models\Car;
use App\Models\Rental;
use App\Models\Payment;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Number;

class StatsOverviewWidget extends BaseWidget
{
    protected function getStats(): array
    {
        // Total Mobil
        $totalCars = Car::count();
        $availableCars = Car::where('status', 'available')->count();

        // Rental Aktif (ongoing)
        $activeRentals = Rental::where('status', 'ongoing')->count();
        $pendingRentals = Rental::where('status', 'pending')->count();

        // Total Pendapatan Bulan Ini
        $currentMonthRevenue = Payment::where('status', 'completed')
            ->whereMonth('payment_date', now()->month)
            ->whereYear('payment_date', now()->year)
            ->sum('amount');

        // Total Pendapatan Keseluruhan
        $totalRevenue = Payment::where('status', 'completed')->sum('amount');

        // Total Customer
        $totalCustomers = User::where('user_type', 'customer')->count();

        // Rental Bulan Ini
        $monthlyRentals = Rental::whereMonth('start_date', now()->month)
            ->whereYear('start_date', now()->year)
            ->count();

        return [
            Stat::make('Total Mobil', $totalCars)
                ->description($availableCars . ' mobil tersedia')
                ->descriptionIcon('heroicon-m-check-circle')
                ->color('success')
                ->chart([7, 3, 4, 5, 6, 3, 5, 3]),

            Stat::make('Rental Aktif', $activeRentals)
                ->description($pendingRentals . ' pending approval')
                ->descriptionIcon('heroicon-m-clock')
                ->color('warning')
                ->chart([3, 5, 7, 6, 4, 8, 9, 7]),

            Stat::make('Pendapatan Bulan Ini', 'Rp ' . Number::format($currentMonthRevenue, locale: 'id'))
                ->description('Total: Rp ' . Number::format($totalRevenue, locale: 'id'))
                ->descriptionIcon('heroicon-m-currency-dollar')
                ->color('success')
                ->chart([7, 2, 10, 3, 15, 4, 17]),

            Stat::make('Total Customer', $totalCustomers)
                ->description($monthlyRentals . ' rental bulan ini')
                ->descriptionIcon('heroicon-m-users')
                ->color('primary')
                ->chart([10, 12, 15, 14, 18, 20, 22]),
        ];
    }
}
