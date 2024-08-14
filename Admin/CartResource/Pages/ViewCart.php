<?php

namespace App\Filament\Resources\CartResource\Pages;

use App\Models\Checkout;
use Filament\Infolists\Components\KeyValueEntry;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Infolist;
use Filament\Resources\Pages\ViewRecord;
use Modules\Order\Admin\CartResource;
use Modules\Order\Models\PaymentMethod;

class ViewCart extends ViewRecord
{
    protected static string $resource = CartResource::class;

    public function infolist(Infolist $infolist): Infolist
    {
        // $checkout = Checkout::where('cart_uuid', $infolist->record->uuid)->first();
        $checkoutData = $checkout->data ?? [];
        $products = $infolist->record->data;
        $products = (array) json_decode($products);
        $productsTable = [];

        foreach ($products as $product) {
            $product = (array) $product;
            $orderProducts = [];
            $productOptions = [];
            $orderProducts[] = KeyValueEntry::make('products')
                ->columnSpanFull()
                ->hiddenLabel()
                ->keyLabel(__('Product') . ' ID: ' . $product['id'])
                ->valueLabel(false)
                ->getStateUsing(function ($record) use ($product) {
                    return [
                        __('Sku') => $product['sku'] ?? '',
                        __('Name') => $product['name'],
                        __('Price') => $product['price'],
                        __('Quantity') => $product['quantity'],
                        __('Total') => $product['total'],
                        __('Width') => $product['parameters']->width ?? '',
                        __('Height') => $product['parameters']->height ?? '',
                    ];
                });
            if (count($product['options']) > 0) {
                foreach ($product['options'] as $option) {
                    $option = (array) $option;
                    $productOptions = [];
                    if (gettype($option['selected']) == 'string') {
                        $option['selected'] = [$option['selected']];
                    }
                    if (count($option['selected']) > 0 && count($option['values']) > 0) {
                        foreach ($option['selected'] as $selected) {
                            foreach ($option['values'] as $value) {
                                $value = (array) $value;
                                if ($selected == $value['option_value_id']) {
                                    $productOptions[] = KeyValueEntry::make('options')
                                        ->columnSpanFull()
                                        ->hiddenLabel()
                                        ->keyLabel($option['name'])
                                        ->valueLabel(false)
                                        ->getStateUsing(function ($record) use ($value) {
                                            return [
                                                __('Name') => $value['name'],
                                                __('Sign') => $value['sign'],
                                                __('Value') => $value['value'],
                                            ];
                                        });
                                }
                            }
                        }
                    }
                    $orderProducts = array_merge($orderProducts, $productOptions);
                }
            }
            $productsTable = array_merge($productsTable, [Section::make()->schema($orderProducts)]);
        }

        return $infolist
            ->schema([
                Section::make(__('User data'))
                    ->schema([
                        KeyValueEntry::make('products')
                            ->columnSpanFull()
                            ->hiddenLabel()
                            ->keyLabel(__('User'))
                            ->valueLabel(false)
                            ->getStateUsing(function ($record) use ($checkoutData) {
                                $paymentMethod = isset($checkoutData['paymentMethod'])
                                    ? PaymentMethod::PAYMENTS[$checkoutData['paymentMethod']]
                                    : null;

                                return [
                                    __('First name') => $checkoutData['firstName'] ?? '',
                                    __('Last name') => $checkoutData['lastName'] ?? '',
                                    __('Email field') => $checkoutData['email'] ?? '',
                                    __('Phone') => $checkoutData['phone'] ?? '',
                                    __('country') => $checkoutData['country'] ?? '',
                                    __('city') => $checkoutData['city'] ?? '',
                                    __('address') => $checkoutData['address'] ?? '',
                                    __('zip') => $checkoutData['zip'] ?? '',
                                    __('vat') => $checkoutData['vat'] ?? '',
                                    __('deliveryMethod') => __($deliveryMethod ?? ''),
                                    __('paymentMethod') => __($paymentMethod ?? ''),
                                ];
                            }),
                    ])
                    ->collapsible(),
                Section::make(__('Products'))
                    ->schema($productsTable)
                    ->collapsible(),
            ]);
    }
}
