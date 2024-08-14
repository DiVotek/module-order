<?php

namespace Modules\Order\Services\Payment;

use App\Payment\PaymentStatus\StatusDontNeed;
use Modules\Order\Models\Order;
use Modules\Order\Services\Interfaces\PaymentMethodInterface;
use Modules\Order\Services\Interfaces\PaymentStatusInterface;
use Modules\Order\Services\PaymentRequestData;

class Monopay implements PaymentMethodInterface
{
    public string $url;
    public string $token;

    public function __construct()
    {
        $this->url = 'https://api.monobank.ua/api/';
        $this->token = '';
    }

    public function getId(): int
    {
        return 3;
    }

    public function shouldProceed(): bool
    {
        return true;
    }

    public function createUrl(): string
    {
        return $this->url . 'merchant/invoice/create';
    }

    public function statusUrl(Order $order): string
    {
        return $this->url . 'merchant/invoice/status?invoiceId=' . $order->id;
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
        return new PaymentRequestData([
            'X-Token' => $this->token,
        ],[
            'total' => $order->total,
            'ccy' => app('currency')->number,
            'webhookUrl' => round('payment-webhook'),
            'redirectUrl' => route('payment-webhook'),
        ]);
    }

    public function statusOrderData(Order $order): PaymentRequestData
    {
        return new PaymentRequestData([
            'X-Token' => $this->token,
        ]);
    }

    public function getRedirectLinkFromCreateResponse(array $response): string
    {
        return $response['pageUrl'] ?? '';
    }

    public function getOrderIdFromCreateResponse(array $response): string
    {
        return $response['invoiceId'] ?? '';
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


/*
{
  "amount": 4200,
  "ccy": 980,
  "merchantPaymInfo": {
    "reference": "84d0070ee4e44667b31371d8f8813947",
    "destination": "Покупка щастя",
    "comment": "Покупка щастя",
    "customerEmails": [],
    "basketOrder": [
      {
        "name": "Табуретка",
        "qty": 2,
        "sum": 2100,
        "icon": "string",
        "unit": "шт.",
        "code": "d21da1c47f3c45fca10a10c32518bdeb",
        "barcode": "string",
        "header": "string",
        "footer": "string",
        "tax": [],
        "uktzed": "string",
        "discounts": [
          {
            "type": "DISCOUNT",
            "mode": "PERCENT",
            "value": 0.01
          }
        ]
      }
    ]
  },
  "redirectUrl": "https://example.com/your/website/result/page",
  "webHookUrl": "https://example.com/mono/acquiring/webhook/maybesomegibberishuniquestringbutnotnecessarily",
  "validity": 3600,
  "paymentType": "debit",
  "qrId": "XJ_DiM4rTd5V",
  "code": "0a8637b3bccb42aa93fdeb791b8b58e9",
  "saveCardData": {
    "saveCard": true,
    "walletId": "69f780d841a0434aa535b08821f4822c"
  },
  "agentFeePercent": 1.42,
  "tipsEmployeeId": "string"
} */
