<?php

namespace App\Filament\Widgets;

use App\Models\Car;
use App\Models\CarCategory;
use Filament\Widgets\ChartWidget;

class CarsByCategoryWidget extends ChartWidget
{
    protected static ?string $heading = 'Mobil per Kategori';

    protected static ?int $sort = 6;

    protected int | string | array $columnSpan = 'full';

    protected function getData(): array
    {
        $categories = CarCategory::withCount('cars')->get();

        $labels = [];
        $data = [];
        $colors = [
            'rgb(59, 130, 246)',   // blue
            'rgb(34, 197, 94)',    // green
            'rgb(251, 146, 60)',   // orange
            'rgb(168, 85, 247)',   // purple
            'rgb(236, 72, 153)',   // pink
            'rgb(14, 165, 233)',   // sky
            'rgb(245, 158, 11)',   // amber
        ];

        foreach ($categories as $index => $category) {
            $labels[] = $category->name;
            $data[] = $category->cars_count;
        }

        return [
            'datasets' => [
                [
                    'label' => 'Jumlah Mobil',
                    'data' => $data,
                    'backgroundColor' => array_slice($colors, 0, count($data)),
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }

    protected function getOptions(): array
    {
        return [
            'plugins' => [
                'legend' => [
                    'display' => false,
                ],
            ],
            'scales' => [
                'y' => [
                    'beginAtZero' => true,
                    'ticks' => [
                        'stepSize' => 1,
                    ],
                ],
            ],
        ];
    }
}
