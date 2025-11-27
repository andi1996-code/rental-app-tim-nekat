<?php

namespace App\Filament\Widgets;

use App\Models\Car;
use Filament\Widgets\ChartWidget;

class CarStatusWidget extends ChartWidget
{
    protected static ?string $heading = 'Status Ketersediaan Mobil';

    protected static ?int $sort = 3;

    protected int | string | array $columnSpan = 'full';

    protected function getData(): array
    {
        $available = Car::where('status', 'available')->count();
        $rented = Car::where('status', 'rented')->count();
        $maintenance = Car::where('status', 'maintenance')->count();
        $unavailable = Car::where('status', 'unavailable')->count();

        return [
            'datasets' => [
                [
                    'label' => 'Status Mobil',
                    'data' => [$available, $rented, $maintenance, $unavailable],
                    'backgroundColor' => [
                        'rgb(34, 197, 94)',  // green
                        'rgb(59, 130, 246)',  // blue
                        'rgb(251, 146, 60)',  // orange
                        'rgb(239, 68, 68)',   // red
                    ],
                ],
            ],
            'labels' => ['Tersedia', 'Disewa', 'Maintenance', 'Tidak Tersedia'],
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }

    protected function getOptions(): array
    {
        return [
            'plugins' => [
                'legend' => [
                    'display' => true,
                    'position' => 'bottom',
                ],
            ],
        ];
    }
}
