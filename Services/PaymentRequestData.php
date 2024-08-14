<?php

namespace Modules\Order\Services;

class PaymentRequestData
{
    private array $headers;

    private array $params;

    public function __construct(array $headers = [], array $params = [])
    {
        $this->headers = $headers;
        $this->params = $params;
    }

    public function getHeaders(): array
    {
        return $this->headers;
    }

    public function getParams(): array
    {
        return $this->params;
    }
}
