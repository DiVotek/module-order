<?php

namespace App\Payment\PaymentMethods;

use App\Payment\PaymentStatus\StatusFailed;
use App\Payment\PaymentStatus\StatusPending;
use App\Payment\PaymentStatus\StatusSuccess;
use App\Payment\PaymentStatus\StatusUnexpected;
use Filament\Forms\Components\TextInput;
use Modules\Order\Models\Order;
use Modules\Order\Models\PaymentMethod;
use Modules\Order\Services\Interfaces\PaymentMethodInterface;
use Modules\Order\Services\Interfaces\PaymentStatusInterface;
use Modules\Order\Services\PaymentRequestData;

class Cryptomus implements PaymentMethodInterface
{
    private int $id = 3;

    private string $apiUrl;

    private string $accessToken;

    private string $merchantCode;

    private string $sign;

    public function __construct()
    {
        $settings = PaymentMethod::query()->id($this->id)->first()->settings ?? [];
        $this->apiUrl = 'https://api.cryptomus.com/v1';
        $this->merchantCode = $settings['merchant_code'] ?? '';
        $this->accessToken = $settings['access_token'] ?? '';
        $this->sign = '';
    }

    public function getId(): int
    {
        return 10;
    }

    public function shouldProceed(): bool
    {
        return false;
    }

    public function createUrl(): string
    {
        return $this->apiUrl . '/payment';
    }

    public function statusUrl(Order $order): string
    {
        return $this->apiUrl . '/payment/info';
    }

    public function createMethod(): string
    {
        return 'POST';
    }

    public function statusMethod(): string
    {
        return 'POST';
    }

    public function createOrderData(Order $order): PaymentRequestData
    {
        $data = [
            'amount' => $order->total,
            'order_id' => $order->number,
            'currency' => app('currency')->code,
            'url_success' => route('order', [
                'number' => $order->number,
            ]),
            'url_return' => route('checkout'),
            'url_callback' => route('order', [
                'number' => $order->number,
            ]),
        ];
        $this->sign = md5(base64_encode(json_encode($data)) . $this->accessToken);
        return new PaymentRequestData([
            'merchant' => $this->merchantCode,
            'sign' => $this->sign,
        ], $data);
    }

    public function statusOrderData(Order $order): PaymentRequestData
    {
        $uuid = $order->uuid;
        $this->sign = md5(base64_encode(json_encode([
            'order_id' => $uuid
        ])) . $this->accessToken);

        return new PaymentRequestData([
            'merchant' => $this->merchantCode,
            'sign' => $this->sign,
        ], [
            'order_id' => $uuid,
        ]);
    }

    public function getRedirectLinkFromCreateResponse(array $response): string
    {
        return $response['result']['url'] ?? '';
    }

    public function getOrderIdFromCreateResponse(array $response): string
    {
        return $response['result']['order_id'] ?? '';
    }

    public function getStatusFromStatusResponse(array $response): PaymentStatusInterface
    {
        $status = $response['result']['status'] ?? 0;
        return match ($status) {
            'paid' => new StatusSuccess,
            'check' => new StatusPending,
            'cancel' => new StatusFailed,
            default => new StatusUnexpected,
        };
    }

    public static function getSchema(): array
    {
        return [
            TextInput::make('merchant_code')
                ->label(__('Merchant code'))
                ->required()
                ->string(),
            TextInput::make('access_token')
                ->label(__('Access token'))
                ->required()
                ->string(),
        ];
    }

    public function getFields(): array
    {
        return [];
    }
}
