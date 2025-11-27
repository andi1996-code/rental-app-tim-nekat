<?php

namespace App\Filament\Widgets;

use App\Models\Rental;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class RecentRentalsWidget extends BaseWidget
{
    protected int | string | array $columnSpan = 'full';

    protected static ?int $sort = 2;

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Rental::query()
                    ->with(['customer', 'car', 'pickupLocation'])
                    ->latest()
                    ->limit(5)
            )
            ->columns([
                Tables\Columns\TextColumn::make('uuid')
                    ->label('ID Rental')
                    ->searchable()
                    ->copyable()
                    ->size('sm')
                    ->limit(10),

                Tables\Columns\TextColumn::make('customer.name')
                    ->label('Customer')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('car.brand')
                    ->label('Mobil')
                    ->formatStateUsing(fn (Rental $record) =>
                        $record->car->brand . ' ' . $record->car->model
                    )
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('start_date')
                    ->label('Tanggal Mulai')
                    ->date('d M Y')
                    ->sortable(),

                Tables\Columns\TextColumn::make('end_date')
                    ->label('Tanggal Selesai')
                    ->date('d M Y')
                    ->sortable(),

                Tables\Columns\TextColumn::make('total_days')
                    ->label('Durasi')
                    ->suffix(' hari')
                    ->alignCenter(),

                Tables\Columns\TextColumn::make('final_amount')
                    ->label('Total')
                    ->money('IDR', locale: 'id')
                    ->sortable(),

                Tables\Columns\BadgeColumn::make('status')
                    ->label('Status')
                    ->colors([
                        'warning' => 'pending',
                        'primary' => 'confirmed',
                        'success' => 'ongoing',
                        'info' => 'completed',
                        'danger' => 'cancelled',
                    ])
                    ->icons([
                        'heroicon-m-clock' => 'pending',
                        'heroicon-m-check-circle' => 'confirmed',
                        'heroicon-m-play' => 'ongoing',
                        'heroicon-m-check-badge' => 'completed',
                        'heroicon-m-x-circle' => 'cancelled',
                    ]),

                Tables\Columns\BadgeColumn::make('payment_status')
                    ->label('Pembayaran')
                    ->colors([
                        'warning' => 'pending',
                        'success' => 'paid',
                        'danger' => 'failed',
                        'info' => 'refunded',
                    ]),
            ]);
    }

    protected function getTableHeading(): string
    {
        return 'Rental Terbaru';
    }
}
