<?php

namespace Modules\Order\Services\Payment;

use App\Base\PaymentMethod;
use App\Base\PaymentStatusInterface;
use App\Models\Order;
use App\Models\PaymentMethods\PaymentMethod as PaymentMethodsPaymentMethod;
use App\Payment\PaymentRequestData;
use App\Payment\PaymentStatus\StatusUnexpected;
use Exception;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class Sumup extends PaymentMethod
{
    private int $id = 6;

    private string $apiUrl;

    private string $accessToken;

    private string $clientId;

    private string $clientSecret;

    public function __construct()
    {
        $settings = PaymentMethodsPaymentMethod::query()->id($this->id)->first()->settings;
        $this->clientId = //$settings['client_id'] ?? '';
        'cc_classic_0fScEFmDvX5VvbZqLovD8Ews9Gj07';
        $this->clientSecret = //$settings['client_secret'] ?? '';
        'cc_sk_classic_apFfTpVafaLdC2IP2mlcAVxsXKuyMPAYVRSqzZD4zqCScr2jIG';
        $this->apiUrl = 'https://api.sumup.com';
        $this->accessToken = '';
    }

    public function authorize(): void
    {
        try {
            $response = Http::asForm()->post($this->apiUrl . '/token', [
                'grant_type' => 'client_credentials',
                'client_id' => $this->clientId,
                'client_secret' => $this->clientSecret,
            ]);
            $this->accessToken = $response->json()['access_token'] ?? '';
        } catch (Exception $e) {
            Log::channel('payment')->error($e->getMessage());
        }
    }

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
        return '';
    }

    public function getNameParamForRedirectUrl(): string
    {
        return '';
    }

    public function getNameParamForWebhookUrl(): string
    {
        return '';
    }

    public function getInvoiceParamNameFromOrderResponse(): string
    {
        return '';
    }

    public function getRedirectParamNameFromOrderResponse(): string
    {
        return '';
    }

    public function getCreateOrderRequestData(): PaymentRequestData
    {
        $this->authorize();

        return new PaymentRequestData([
            'Authorization' => 'Bearer ' . $this->accessToken,
        ], []);
    }

    public function getShouldBeProcessed(): bool
    {
        return false;
    }

    public function getUrlForCreateRequest(): string
    {
        return $this->apiUrl . '/v0.1/checkouts';
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getUrlForStatusRequest(Order $order): string
    {
        return '';
    }

    public function getStatusRequestMethod(): string
    {
        return 'GET';
    }

    public function getStatusOrderRequestData(Order $order): PaymentRequestData
    {
        return new PaymentRequestData([], []);
    }

    public function getStatusParamNameFromWebhook(): string
    {
        return '';
    }

    public function getOrderIdParamNameFromWebhook(): string
    {
        return '';
    }

    public function prepareOrder(Order $order): array
    {
        return [
            'amount' => $order->total,
            'checkoutReference' => $order->number,
            'currency' => 'GBP',
            'pay_to_email' => '78ad433ac1d0445b90190e2f6de15fdd@developer.sumup.com',
            'merchant_code' => 'MDASYTPD',
        ];
    }

    public function getPaymentUrl(): string
    {
        return '';
    }

    public function orderStatusToPaymentStatus(int|string $status): PaymentStatusInterface
    {
        return new StatusUnexpected;
    }
}
