<?php

namespace Modules\Order\Services\Payment;

use App\Payment\PaymentStatus\StatusDontNeed;
use Modules\Order\Models\Order;
use Modules\Order\Services\Interfaces\PaymentMethodInterface;
use Modules\Order\Services\Interfaces\PaymentStatusInterface;
use Modules\Order\Services\PaymentRequestData;

class Cash implements PaymentMethodInterface
{

    public function getId(): int
    {
        return 1;
    }

    public function shouldProceed(): bool
    {
        return false;
    }

    public function createUrl(): string
    {
        return '';
    }

    public function statusUrl(Order $order): string
    {
        return '';
    }

    public function createMethod(): string
    {
        return 'get';
    }

    public function statusMethod(): string
    {
        return 'get';
    }

    public function createOrderData(Order $order): PaymentRequestData
    {
        return new PaymentRequestData();
    }

    public function statusOrderData(Order $order): PaymentRequestData
    {
        return new PaymentRequestData();
    }

    public function getRedirectLinkFromCreateResponse(array $response): string
    {
        return '';
    }

    public function getOrderIdFromCreateResponse(array $response): string
    {
        return '';
    }

    public function getStatusFromStatusResponse(array $response): PaymentStatusInterface
    {
        return new StatusDontNeed();
    }

    public static function getSchema(): array
    {
        return [];
    }

    public function getFields(): array
    {
        return [];
    }
}
