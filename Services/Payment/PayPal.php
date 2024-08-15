<?php

namespace Modules\Order\Services\Payment;

use Modules\Order\Services\PaymentStatus\StatusFailed;
use Modules\Order\Services\PaymentStatus\StatusPending;
use Modules\Order\Services\PaymentStatus\StatusSuccess;
use Modules\Order\Services\PaymentStatus\StatusUnexpected;
use Exception;
use Filament\Forms\Components\TextInput;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Modules\Order\Models\Order;
use Modules\Order\Models\PaymentMethod;
use Modules\Order\Services\Interfaces\PaymentMethodInterface;
use Modules\Order\Services\Interfaces\PaymentStatusInterface;
use Modules\Order\Services\PaymentRequestData;

class PayPal implements PaymentMethodInterface
{
    private int $id = 7;

    private string $clientId;

    private string $clientSecret;

    private string $apiUrl;

    private string $accessToken;

    public function __construct()
    {
        $method = PaymentMethod::query()->where('payment_id', $this->id)->first();
        $settings = $method->settings ?? [];
        $this->clientId = $settings['client_id'] ?? '';
        $this->clientSecret = $settings['client_secret'] ?? '';
        $this->apiUrl = 'https://api-m.paypal.com';
        $this->accessToken = '';
    }

    public function authorize(): void
    {
        try {
            $base64Credentials = base64_encode("{$this->clientId}:{$this->clientSecret}");
            $resp = Http::withHeaders([
                'Authorization' => 'Basic ' . $base64Credentials,
                'Content-Type' => 'application/x-www-form-urlencoded',
            ])
                ->asForm()
                ->post($this->apiUrl . '/v1/oauth2/token', [
                    'grant_type' => 'client_credentials',
                ]);

            $this->accessToken = $resp->json()['access_token'] ?? '';
        } catch (Exception $e) {
            Log::channel('payment')->error('Can not authorize PayPal: ' . $e->getMessage());
        }
    }

    public function getId(): int
    {
        return 7;
    }

    public function shouldProceed(): bool
    {
        return true;
    }

    public function createUrl(): string
    {
        return $this->apiUrl . '/v2/checkout/orders';
    }

    public function statusUrl(Order $order): string
    {
        return $this->apiUrl . '/v2/checkout/orders/' . ($order->details['payment_invoice'] ?? '');
    }

    public function createMethod(): string
    {
        return 'POST';
    }

    public function statusMethod(): string
    {
        return 'GET';
    }

    public function createOrderData(Order $order): PaymentRequestData
    {
        $this->authorize();

        $data =  [
            'intent' => 'CAPTURE',
            'purchase_units' => [[
                'amount' => [
                    'currency_code' => app('currency')->code,
                    'value' => $order->total,
                ],
            ]],
            'application_context' => [
                'return_url' => route('slug', [
                    'number' => $order->number,
                    'slug' => order_slug(),
                ]),
                'cancel_url' => route('slug', [
                    'number' => $order->number,
                    'slug' => order_slug(),
                ]),
            ],
        ];
        return new PaymentRequestData([
            'Content-type' => 'application/json',
            'Authorization' => 'Bearer ' . $this->accessToken,
        ], $data);
        return new PaymentRequestData();
    }

    public function statusOrderData(Order $order): PaymentRequestData
    {
        $this->authorize();

        return new PaymentRequestData([
            'Content-type' => 'application/json',
            'Authorization' => 'Bearer ' . $this->accessToken,
        ], []);
    }

    public function getRedirectLinkFromCreateResponse(array $response): string
    {
        return $response['links'][1]['href'] ?? null;
    }

    public function getOrderIdFromCreateResponse(array $response): string
    {
        return $response['id'] ?? '';
    }

    public function getStatusFromStatusResponse(array $response): PaymentStatusInterface
    {
        $status = 1;
        return match ($status) {
            'COMPLETED' => new StatusSuccess,
            'APPROVED' => new StatusSuccess,
            'CREATED' => new StatusPending,
            'VOIDED' => new StatusFailed,
            'PENDING' => new StatusPending,
            default => new StatusUnexpected,
        };
    }

    public static function getSchema(): array
    {
        return [
            TextInput::make('settings.client_id')->label('Client ID')->required(),
            TextInput::make('settings.client_secret')->label('Client Secret')->required(),
        ];
    }

    public function getFields(): array
    {
        return [];
    }
}
