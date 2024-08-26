<?php

namespace Modules\Order\Admin\OrderResource\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Modules\Order\Models\Order;

class Stats extends BaseWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        return [
            Stat::make(__('Orders for today'), Order::query()->whereDate('created_at', now())->count())
                ->description(__('Total number of orders for today'))
                ->descriptionIcon('heroicon-s-calendar'),
            Stat::make(__('Orders for last week'), Order::query()->whereBetween('created_at', [now()->subWeek(), now()])->count())
                ->description(__('Total number of orders for last week'))
                ->descriptionIcon('heroicon-s-calendar'),
            Stat::make(__('Orders for last month'), Order::query()->whereBetween('created_at', [now()->subMonth(), now()])->count())
                ->description(__('Total number of orders for last month'))
                ->descriptionIcon('heroicon-s-calendar'),
            //
        ];
    }
}
