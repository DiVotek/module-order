<?php

namespace Modules\Order\Services\Payment;

use App\Base\PaymentMethod;
use App\Base\PaymentStatusInterface;
use App\Models\Order;
use App\Models\PaymentMethods\PaymentMethod as PaymentMethodsPaymentMethod;
use App\Payment\PaymentRequestData;
use App\Payment\PaymentStatus\StatusFailed;
use App\Payment\PaymentStatus\StatusPending;
use App\Payment\PaymentStatus\StatusSuccess;
use App\Payment\PaymentStatus\StatusUnexpected;
use App\Service\MultiLang;
use Carbon\Carbon;
use Filament\Forms\Components\TextInput;

class WayForPay extends PaymentMethod
{
    private int $id = 9;

    private string $merchantAccount;

    private string $merchantDomainName;

    private string $apiUrl;

    private string $accessToken;

    private string $merchantSecretKey;

    public function __construct()
    {
        $settings = PaymentMethodsPaymentMethod::query()->id($this->id)->first()->settings ?? [];
        $this->merchantAccount = //$settings['merchant_account'] ?? '';
            'test_merch_n1';
        $this->merchantDomainName = // $settings['merchant_domain_name'] ?? '';
            'https://example.com';
        $this->merchantSecretKey = // $settings['merchant_signature'] ?? '';
            'flk3409refn54t54t*FNJRET';
        $this->apiUrl = 'https://api.wayforpay.com/api';
        $this->accessToken = '';
    }

    public function getStatusParamNameFromStatusResponse(): string
    {
        return 'status';
    }

    public function getCreateOrderRequestData(): PaymentRequestData
    {
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
        return $this->apiUrl . '/pay';
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getUrlForStatusRequest(Order $order): string
    {
        return $this->apiUrl . '/v2/checkout/orders/' . ($order->details['payment_invoice'] ?? '');
    }

    public function getStatusRequestMethod(): string
    {
        return 'POST';
    }

    public function getStatusOrderRequestData(Order $order): PaymentRequestData
    {
        $data = [
            'transactionType' => 'CHECK_STATUS',
            'merchantAccount' => $this->merchantAccount,
            'orderReference' => $order->number,
            'apiVersion' => '1',
        ];
        $data['merchantSignature'] = hash_hmac('md5', $this->merchantAccount . ';' . $order->number, $this->merchantSecretKey);
        $data['transactionType'] = 'CHECK_STATUS';

        return new PaymentRequestData([
            'Content-type' => 'application/json',
            'Authorization' => 'Bearer ' . $this->accessToken,
        ], $data);
    }

    public function prepareOrder(Order $order): array
    {
        $productNames = [];
        $productPrice = [];
        $productCount = [];
        foreach ($order->products as $product) {
            $productNames[] = $product['name'];
            $productPrice[] = $product['total'];
            $productCount[] = $product['quantity'] ?? 1;
        }
        if (! in_array(MultiLang::getCurrentLanguage(), [
            'uk', 'en', 'ru',
        ])) {
            $language = 'EN';
        } else {
            $language = strtoupper(MultiLang::getCurrentLanguage());
        }
        $data = [
            'merchantAccount' => $this->merchantAccount,
            'merchantDomainName' => $this->merchantDomainName,
            'merchantAuthType' => 'SimpleSignature',
            'merchantTransactionType' => 'SALE',
            'transactionType' => 'CREATE_INVOICE',
            'language' => $language,
            'return_url' => route('slug', [
                'number' => $order->number,
                'slug' => order_slug(),
            ]),
            'orderReference' => $order->number,
            'orderDate' => Carbon::parse($order->created_at)->timestamp,
            'amount' => $order->total,
            'currency' => app('currency')->code,
            'productName' => $productNames,
            'productPrice' => $productPrice,
            'productCount' => $productCount,
            'apiVersion' => '2',
        ];
        $hash = $this->generateSignature($data);
        $data['merchantSignature'] = $hash;

        return $data;
    }

    protected function generateSignature(array $data): string
    {
        $signatureString = $this->merchantAccount . ';' .
            $data['merchantDomainName'] . ';' .
            $data['orderReference'] . ';' .
            $data['orderDate'] . ';' .
            $data['amount'] . ';' .
            $data['currency'] . ';' .
            implode(';', $data['productName']) . ';' .
            implode(';', $data['productCount']) . ';' .
            implode(';', $data['productPrice']);

        return hash_hmac('md5', $signatureString, $this->merchantSecretKey);
    }

    public function getRedirectLinkFromResponse(array $response): ?string
    {
        return $response['invoiceUrl'] ?? null;
    }

    public function getOrderIdFromResponse(array $response): int|string
    {
        return 0;
    }

    public function orderStatusToPaymentStatus(int|string $status): PaymentStatusInterface
    {
        return match ($status) {
            'Completed' => new StatusSuccess,
            'Approved' => new StatusSuccess,
            'Canceled' => new StatusFailed,
            'Expired' => new StatusFailed,
            'Pending' => new StatusPending,
            default => new StatusUnexpected,
        };
    }

    public function getSchema(): array
    {
        return [
            TextInput::make('client_id')->label('Client ID')->required(),
            TextInput::make('client_secret')->label('Client Secret')->required(),
        ];
    }
}
