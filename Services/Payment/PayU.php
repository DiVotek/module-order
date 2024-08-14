<?php

namespace Modules\Order\Services\Payment;

use App\Base\PaymentMethod;
use App\Base\PaymentStatusInterface;
use App\Models\Order;
use App\Models\PaymentMethods\PaymentMethod as PaymentMethodsPaymentMethod;
use App\Models\User;
use App\Payment\PaymentRequestData;
use App\Payment\PaymentStatus\StatusFailed;
use App\Payment\PaymentStatus\StatusPending;
use App\Payment\PaymentStatus\StatusSuccess;
use App\Payment\PaymentStatus\StatusUnexpected;
use App\Service\MultiLang;
use Exception;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PayU extends PaymentMethod
{
    private const TEST_AUTH_HOST = 'https://secure.snd.payu.com/pl/standard/user/oauth/authorize';

    private const TEST_ORDER_HOST = 'https://secure.snd.payu.com/api/v2_1/orders';

    protected string $authHost = 'https://secure.payu.com/pl/standard/user/oauth/authorize';

    protected string $orderHost = 'https://secure.payu.com/api/v2_1/orders';

    protected ?string $accessToken;

    private array $settings;

    private int $id = 3;

    public function getRedirectLinkFromResponse(array $response): ?string
    {
        return '';
    }

    public function getOrderIdFromResponse(array $response): int|string
    {
        return '';
    }

    public function getSchema(): array
    {
        return [];
    }

    public function getStatusParamNameFromStatusResponse(): string
    {
        return 'status';
    }

    public function getNameParamForRedirectUrl(): string
    {
        return 'continueUrl';
    }

    public function getNameParamForWebhookUrl(): string
    {
        return 'notifyUrl';
    }

    public function __construct()
    {
        $this->id = 3;
        $settings = PaymentMethodsPaymentMethod::query()->find($this->id)->settings;
        $this->settings = [
            'client_id' => $settings['client_id'] ?? '',
            'client_secret' => $settings['client_secret'] ?? '',
            'grant_type' => 'client_credentials',
        ];
        $this->accessToken = null;
    }

    public function test(): void
    {
        $this->authHost = self::TEST_AUTH_HOST;
        $this->orderHost = self::TEST_ORDER_HOST;
    }

    public function getInvoiceParamNameFromOrderResponse(): string
    {
        return 'orderId';
    }

    public function getRedirectParamNameFromOrderResponse(): string
    {
        return 'redirectUri';
    }

    public function getCreateOrderRequestData(): PaymentRequestData
    {
        $this->authorize();

        return new PaymentRequestData([
            'Content-type' => 'application/json',
            'Authorization' => 'Bearer ' . $this->accessToken,
        ], []);
    }

    public function getShouldBeProcessed(): bool
    {
        return true;
    }

    public function getUrlForCreateRequest(): string
    {
        return $this->orderHost;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getUrlForStatusRequest(Order $order): string
    {
        $orderId = $order->details['orderId'] ?? 0;

        return $this->orderHost . '/' . $orderId;
    }

    public function getStatusRequestMethod(): string
    {
        return 'GET';
    }

    public function getStatusOrderRequestData(Order $order): PaymentRequestData
    {
        $this->authorize();

        return new PaymentRequestData([
            'Content-Type: application/json',
            'Authorization' => 'Bearer ' . $this->accessToken,
        ], []);
    }

    public function getStatusParamNameFromWebhook(): string
    {
        return 'status';
    }

    public function getOrderIdParamNameFromWebhook(): string
    {
        return 'orderId';
    }

    public function prepareOrder(Order $order): array
    {
        $products = [];
        foreach ($order->products as $product) {
            $products[] = [
                'name' => $product['name'],
                'unitPrice' => (int) $product['price'] * 100,
                'quantity' => $product['quantity'],
            ];
        }
        $preparedOrder = [
            'customerIp' => request()->ip(),
            'merchantPosId' => $this->settings['client_id'] ?? '',
            'description' => app('companyName'),
            'currencyCode' => app('currency')->code,
            'totalAmount' => (int) $order->total * 100,
            'extOrderId' => $order->number,
            'buyer' => [
                'firstName' => explode(' ', $order->name)[0] ?? '',
                'lastName' => explode(' ', $order->name)[1] ?? '',
                'email' => User::find($order->user_id)?->email ?? $order->details['email'],
                'phone' => $order->details['phone'] ?? null,
                'nin' => $order->details['nip'] ?? null,
                'language' => MultiLang::getDefaultLanguage(),
                'delivery' => [
                    'state' => $order->details['state'] ?? $order->details['country'],
                    'city' => $order->details['city'],
                    'street' => $order->details['address'],
                    'postalCode' => $order->details['zip'],
                ],
            ],
            'products' => $products,
        ];

        return $preparedOrder;
    }

    public function getPaymentUrl(): string
    {
        return $this->orderHost;
    }

    public function authorize(): void
    {
        try {
            $resp = Http::asForm()->post($this->authHost, [
                'grant_type' => $this->settings['grant_type'],
                'client_id' => $this->settings['client_id'],
                'client_secret' => $this->settings['client_secret'],
            ]);
            if ($resp->successful()) {
                $this->accessToken = $resp->json()['access_token'];
            } else {
                throw new Exception('Error: ' . $resp->body());
            }
        } catch (Exception $exception) {
            Log::error($exception->getMessage());
        }
    }

    public function orderStatusToPaymentStatus(int|string $status): PaymentStatusInterface
    {
        return match ($status) {
            'QUEUED' => new StatusPending,
            'SUCCESS' => new StatusSuccess,
            'COMPLETED' => new StatusSuccess,
            'IN PROGRESS' => new StatusPending,
            'PENDING' => new StatusPending,
            'WAITING FOR RETRY' => new StatusPending,
            'PENDING FOR APPROVAL' => new StatusPending,
            'REJECTED' => new StatusFailed,
            'CANCELLED' => new StatusFailed,
            'REVERSED' => new StatusFailed,
            'FAILURE' => new StatusFailed,
            default => new StatusUnexpected,
        };
    }
}
