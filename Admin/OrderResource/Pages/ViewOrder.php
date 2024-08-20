<?php

namespace Modules\Order\Admin\OrderResource\Pages;

use Filament\Actions;
use Filament\Infolists\Components\Card;
use Filament\Infolists\Components\KeyValueEntry;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Infolist;
use Filament\Resources\Pages\ViewRecord;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Modules\Order\Admin\OrderResource;
use Modules\Order\Models\DeliveryMethod;
use Modules\Order\Models\OrderHistory;
use Modules\Order\Models\PaymentMethod;

class ViewOrder extends ViewRecord
{
    protected static string $resource = OrderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }

    public function infolist(Infolist $infolist): Infolist
    {
        $products = $infolist->record->products;
        $orderHistories = OrderHistory::query()->where('order_id', $infolist->record->id)->get();
        $productOptions = [];
        $productsTable = [];
        $orderHistoryValuesTable = [];
        $historyTable = [];

        foreach ($orderHistories as $orderHistory) {
            $orderHistoryValues = (array) json_decode($orderHistory->values);
            $orderHistoryValuesTable = [];

            if (count($orderHistoryValues) > 0) {
                foreach ($orderHistoryValues as $orderHistoryKey => $orderHistoryValue) {
                    $orderHistoryValuesTableData[__($orderHistoryKey)] = $orderHistoryValue;
                }

                $orderHistoryValuesTable[] = KeyValueEntry::make('order_history_values')
                    ->columnSpanFull()
                    ->hiddenLabel()
                    ->keyLabel(Carbon::parse($orderHistory->created_at)->format('d.m.Y H:i'))
                    ->valueLabel(false)
                    ->getStateUsing(function ($record) use ($orderHistoryValuesTableData) {
                        return $orderHistoryValuesTableData;
                    });

                $historyTable = array_merge($historyTable, [Card::make()->schema($orderHistoryValuesTable)]);
            }
        }

        foreach ($products as $product) {
            $orderProducts = [];
            $orderProducts[] = KeyValueEntry::make('products')
                ->columnSpanFull()
                ->hiddenLabel()
                ->keyLabel(__('Product') . ' ID: ' . $product['id'])
                ->valueLabel(false)
                ->getStateUsing(function ($record) use ($product) {
                    return [
                        __('Name') => $product['name'],
                        __('Price') => $product['price'],
                        __('Quantity') => $product['quantity'],
                        __('Total') => $product['price'],
                        __('Link') => $product['slug'],
                    ];
                });
            if (module_enabled('Options')) {
                $productOptions = [];
                foreach ($product['options'] as $key => $optionValue) {
                    $option = \Modules\Options\Models\Option::query()->find($key);
                    $value = \Modules\Options\Models\OptionValue::query()->find($optionValue);
                    $productValue = DB::table('product_option')
                        ->where('option_value_id', $optionValue)
                        ->where('product_id', $product['id'])
                        ->first();
                    $productOptions[] = KeyValueEntry::make('options')
                        ->columnSpanFull()
                        ->hiddenLabel()
                        ->keyLabel($option->name)
                        ->valueLabel(false)
                        ->getStateUsing(function ($record) use ($value, $productValue) {
                            return [
                                __('Name') => $value->name,
                                __('Sign') => $productValue->sign,
                                __('Price') => $productValue->price,
                            ];
                        });
                }
                $orderProducts = array_merge($orderProducts, $productOptions);
            }
            // if (count($product['options']) > 0) {
            //     foreach ($product['options'] as $option) {
            //         $productOptions = [];
            //         if (gettype($option['selected']) == 'string') {
            //             $option['selected'] = [$option['selected']];
            //         }
            //         if (count($option['selected']) > 0 && count($option['values']) > 0) {
            //             foreach ($option['selected'] as $selected) {
            //                 foreach ($option['values'] as $value) {
            //                     if ($selected == $value['option_value_id']) {
            //                         $productOptions[] = KeyValueEntry::make('options')
            //                             ->columnSpanFull()
            //                             ->hiddenLabel()
            //                             ->keyLabel($option['name'])
            //                             ->valueLabel(false)
            //                             ->getStateUsing(function ($record) use ($value) {
            //                                 return [
            //                                     __('Name') => $value['name'],
            //                                     __('Sign') => $value['sign'],
            //                                     __('Value') => $value['value'],
            //                                     __('Parameter') => $value['parameter'],
            //                                     __('Summary') => $value['summary'] ?? '',
            //                                 ];
            //                             });
            //                     }
            //                 }
            //             }
            //         }
            //         $orderProducts = array_merge($orderProducts, $productOptions);
            //     }
            // }
            $productsTable = array_merge($productsTable, [Section::make()->schema($orderProducts)]);
        }

        return $infolist
            ->schema([
                Section::make(__('Details'))
                    ->schema([
                        KeyValueEntry::make('user_data')
                            ->columnSpanFull()
                            ->hiddenLabel()
                            ->keyLabel(__('Order info'))
                            ->valueLabel(false)
                            ->getStateUsing(function ($record) {
                                $payment = PaymentMethod::query()->withoutGlobalScopes()->where('payment_id', $record->payment_method_id)->first();
                                $delivery = DeliveryMethod::query()->withoutGlobalScopes()->where('delivery_id', $record->delivery_method_id)->first();
                                return array_merge($record->user_data, [
                                    __('Delivery method') => $delivery->name ?? ' - ',
                                    __('Payment method') => $payment->name ?? ' - ',
                                    __('Payment status') => $record->payment_status,
                                    __('Total') => $record->total . ' ' . $record->currency,
                                ]);
                            }),
                    ])->collapsible(),
                Section::make(__('Products'))
                    ->schema($productsTable)->collapsible(),
                // Section::make(__('History of order'))
                //     ->schema($historyTable)->collapsible(),
            ]);
    }
}
