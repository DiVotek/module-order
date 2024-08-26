<?php

namespace Modules\Order\Admin\OrderResource\Widgets;

use Carbon\Carbon;
use Filament\Widgets\ChartWidget;
use Modules\Order\Models\Order;

class OrderChart extends ChartWidget
{
    protected static ?string $heading = 'Orders for last week';

    protected static string $color = 'info';

    protected static ?string $maxHeight = '500px';

    protected static ?int $sort = 2;

    protected int|string|array $columnSpan = 'full';

    protected function getData(): array
    {
        $activeFilter = $this->filter;
        $labels = [];
        $datasets = [];
        switch ($activeFilter) {
            case 'week':
                $labels = [
                    now()->startOfWeek()->format('D'),
                    now()->startOfWeek()->addDay()->format('D'),
                    now()->startOfWeek()->addDays(2)->format('D'),
                    now()->startOfWeek()->addDays(3)->format('D'),
                    now()->startOfWeek()->addDays(4)->format('D'),
                    now()->startOfWeek()->addDays(5)->format('D'),
                    now()->startOfWeek()->addDays(6)->format('D'),
                ];
                $datasets = $this->getWeekData();
                break;
            case 'month':
                $labels = [];
                for ($i = 0; $i < Carbon::now()->daysInMonth; $i++) {
                    $labels[] = now()->startOfMonth()->addDays($i)->format('d');
                }
                $datasets = $this->getMonthData();
                break;
            case 'year':
                $labels = [
                    now()->startOfYear()->format('M'),
                    now()->startOfYear()->addMonth()->format('M'),
                    now()->startOfYear()->addMonths(2)->format('M'),
                    now()->startOfYear()->addMonths(3)->format('M'),
                    now()->startOfYear()->addMonths(4)->format('M'),
                    now()->startOfYear()->addMonths(5)->format('M'),
                    now()->startOfYear()->addMonths(6)->format('M'),
                    now()->startOfYear()->addMonths(7)->format('M'),
                    now()->startOfYear()->addMonths(8)->format('M'),
                    now()->startOfYear()->addMonths(9)->format('M'),
                    now()->startOfYear()->addMonths(10)->format('M'),
                    now()->startOfYear()->addMonths(11)->format('M'),
                ];
                $datasets = $this->getYearData();
                break;
            default:
                $labels = [
                    now()->startOfWeek()->format('D'),
                    now()->startOfWeek()->addDay()->format('D'),
                    now()->startOfWeek()->addDays(2)->format('D'),
                    now()->startOfWeek()->addDays(3)->format('D'),
                    now()->startOfWeek()->addDays(4)->format('D'),
                    now()->startOfWeek()->addDays(5)->format('D'),
                    now()->startOfWeek()->addDays(6)->format('D'),
                ];
                $datasets = $this->getWeekData();
                break;
        }

        return [
            'labels' => $labels,
            'datasets' => $datasets,
        ];
    }

    protected function getFilters(): ?array
    {
        return [
            'week' => __('Last week'),
            'month' => __('Last month'),
            'year' => __('This year'),
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }

    public function getWeekData(): array
    {
        return [
            [
                'label' => 'Orders',
                'data' => [
                    Order::query()->whereDate('created_at', now()->startOfWeek())->count(),
                    Order::query()->whereDate('created_at', now()->startOfWeek()->addDay())->count(),
                    Order::query()->whereDate('created_at', now()->startOfWeek()->addDays(2))->count(),
                    Order::query()->whereDate('created_at', now()->startOfWeek()->addDays(3))->count(),
                    Order::query()->whereDate('created_at', now()->startOfWeek()->addDays(4))->count(),
                    Order::query()->whereDate('created_at', now()->startOfWeek()->addDays(5))->count(),
                    Order::query()->whereDate('created_at', now()->startOfWeek()->addDays(6))->count(),
                ],
            ],
        ];
    }

    public function getMonthData(): array
    {
        $data = [];
        for ($i = 0; $i < Carbon::now()->daysInMonth; $i++) {
            $data[] = Order::query()->whereDate('created_at', now()->startOfMonth()->addDays($i))->count();
        }

        return [
            [
                'label' => 'Orders',
                'data' => $data,
            ],
        ];
    }

    public function getYearData(): array
    {
        $data = [];
        for ($i = 0; $i < 12; $i++) {
            $data[] = Order::query()->whereMonth('created_at', now()->startOfYear()->addMonths($i))->count();
        }

        return [
            [
                'label' => 'Orders',
                'data' => $data,
            ],
        ];
    }
}
