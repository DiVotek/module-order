<?php

namespace Modules\Order\Services\Interfaces;

use Modules\Order\Models\Order;
use Modules\Order\Services\PaymentRequestData;

interface PaymentMethodInterface extends SchemaInterface, FieldsInterface, IdInterface
{
   public function shouldProceed(): bool;

   public function createUrl(): string;

   public function statusUrl(Order $order): string;

   public function createMethod(): string;

   public function statusMethod(): string;

   public function createOrderData(Order $order): PaymentRequestData;

   public function statusOrderData(Order $order): PaymentRequestData;

   public function getRedirectLinkFromCreateResponse(array $response): string;

   public function getOrderIdFromCreateResponse(array $response): string;

   public function getStatusFromStatusResponse(array $response): PaymentStatusInterface;

}
