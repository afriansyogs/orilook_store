<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\StatsOverviewWidget\Card;

class TotalRevenue extends BaseWidget
{
    protected function getStats(): array
    {
        $totalAmount = Order::sum('total_amount'); // Hitung total amount dari semua order
        $formattedAmount = 'Rp ' . number_format($totalAmount, 0, ',', '.'); // Format amount ke dalam Rupiah (IDR)

        return [
            Stat::make('Total Pemasukan', $formattedAmount)
                // ->description('Total amount dari semua order')
                ->color('success'),
        ];
    }
}
