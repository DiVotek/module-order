<?php

namespace Modules\Order\Services\Payment;

use App\Base\PaymentMethod;
use App\Base\PaymentStatusInterface;
use App\Models\Order;
use App\Payment\PaymentRequestData;
use App\Payment\PaymentStatus\StatusUnexpected;

class Liqpay extends PaymentMethod
{
    private int $id = 4;

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
        return new PaymentRequestData([], []);
    }

    public function getShouldBeProcessed(): bool
    {
        return false;
    }

    public function getUrlForCreateRequest(): string
    {
        return '';
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
        return [];
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
