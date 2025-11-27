<?php

namespace App\Filament\Widgets;

use App\Models\Maintenance;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class UpcomingMaintenanceWidget extends BaseWidget
{
    protected int | string | array $columnSpan = 'full';

    protected static ?int $sort = 5;

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Maintenance::query()
                    ->with(['car'])
                    ->whereIn('status', ['scheduled', 'in_progress'])
                    ->orderBy('start_date', 'asc')
                    ->limit(5)
            )
            ->columns([
                Tables\Columns\TextColumn::make('car.brand')
                    ->label('Mobil')
                    ->formatStateUsing(fn (Maintenance $record) =>
                        $record->car->brand . ' ' . $record->car->model . ' (' . $record->car->license_plate . ')'
                    )
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('maintenance_type')
                    ->label('Tipe Maintenance')
                    ->badge()
                    ->colors([
                        'primary' => 'routine',
                        'warning' => 'repair',
                        'danger' => 'accident',
                    ]),

                Tables\Columns\TextColumn::make('start_date')
                    ->label('Tanggal Mulai')
                    ->date('d M Y')
                    ->sortable(),

                Tables\Columns\TextColumn::make('expected_end_date')
                    ->label('Target Selesai')
                    ->date('d M Y')
                    ->sortable(),

                Tables\Columns\TextColumn::make('description')
                    ->label('Deskripsi')
                    ->limit(50)
                    ->wrap(),

                Tables\Columns\TextColumn::make('cost')
                    ->label('Biaya')
                    ->money('IDR', locale: 'id')
                    ->sortable(),

                Tables\Columns\BadgeColumn::make('status')
                    ->label('Status')
                    ->colors([
                        'info' => 'scheduled',
                        'warning' => 'in_progress',
                        'success' => 'completed',
                        'danger' => 'cancelled',
                    ])
                    ->icons([
                        'heroicon-m-clock' => 'scheduled',
                        'heroicon-m-wrench-screwdriver' => 'in_progress',
                        'heroicon-m-check-circle' => 'completed',
                        'heroicon-m-x-circle' => 'cancelled',
                    ]),
            ]);
    }

    protected function getTableHeading(): string
    {
        return 'Maintenance Terjadwal';
    }
}
