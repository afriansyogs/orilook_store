<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\User;
use App\Models\Product;
use App\Models\Review;

class StatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('User', User::query()->count())
                ->url(route('filament.admin.resources.users.index'))
                ->description('Total User')
                ->descriptionIcon('heroicon-m-user')
                ->chart([7, 2, 10, 3, 15, 4, 17])
                ->color('white'),
            Stat::make('Product', Product::query()->count())
                ->url(route('filament.admin.resources.products.index'))
                ->description('Total Product')
                ->descriptionIcon('heroicon-m-inbox')
                ->chart([7, 2, 10, 3, 15, 4, 17])
                ->color('warning'),
            Stat::make('Order', Order::query()->count())
                ->url(route('filament.admin.resources.orders.index'))
                ->description('Total Order')
                ->descriptionIcon('heroicon-m-clipboard-document-list')
                ->chart([7, 2, 10, 3, 15, 4, 17])
                ->color('success'),
            Stat::make('Review', Review::query()->count())
                ->url(route('filament.admin.resources.reviews.index'))
                ->description('Review')
                ->descriptionIcon('heroicon-m-chat-bubble-bottom-center-text')
                ->chart([7, 2, 10, 3, 15, 4, 17])
                ->color('primary'),
        ];
    }
}
