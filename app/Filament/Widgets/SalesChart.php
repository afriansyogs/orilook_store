<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Filament\Widgets\ChartWidget;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;


class SalesChart extends ChartWidget
{
    protected static ?string $heading = 'Sales Chart';

    protected int | string | array $columnSpan = 1;

    protected function getData(): array
    {
        $data = Trend::model(Order::class)
            ->between(
                start: now()->subYear()->startOfYear(),
                end: now(),
            )
            ->perMonth()
            ->sum('qty');

        $labels = $data->map(fn ($value) => \Carbon\Carbon::parse($value->date)->format('M'))->toArray();
        $values = $data->map(fn ($value) => $value->aggregate)->toArray();

        return [
            'datasets' => [
                [
                    'label' => 'Total Qty Orders per Month',
                    'data' => $values,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
