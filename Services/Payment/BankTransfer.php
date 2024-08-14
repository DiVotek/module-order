<?php

namespace Modules\Order\Services\Payment;

use App\Payment\PaymentStatus\StatusDontNeed;
use Carbon\Carbon;
use Filament\Forms\Components\TextInput;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Modules\Order\Models\Order;
use Modules\Order\Models\PaymentMethod;
use Modules\Order\Services\Interfaces\PaymentMethodInterface;
use Modules\Order\Services\Interfaces\PaymentStatusInterface;
use Modules\Order\Services\PaymentRequestData;
use Mpdf\Mpdf;

class BankTransfer implements PaymentMethodInterface
{
    private int $id = 2;

    public string $fop;

    public string $inn;

    public string $pp;

    public string $mfo;

    public string $where;

    public string $info;

    public function __construct()
    {
        // fio/name reg_number director(not required) iban bank_name address phone email
        // pdf format
        $method = PaymentMethod::query()->where('id', $this->id)->first()->settings ?? [];
        $this->fop = $method['fop'] ?? '';
        $this->inn = $method['inn'] ?? '';
        $this->pp = $method['pp'] ?? '';
        $this->mfo = $method['mfo'] ?? '';
        $this->where = $method['where'] ?? '';
        $this->info = $method['info'] ?? '';
    }

    public function getId(): int
    {
        return 0;
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
        return [
            TextInput::make('fop')->label(__('FOP')),
            TextInput::make('inn')->label(__('INN')),
            TextInput::make('pp')->label(__('PP')),
            TextInput::make('mfo')->label(__('MFO')),
            TextInput::make('where')->label(__('Where')),
            TextInput::make('info')->label(__('Info')),
        ];
    }

    public function getFields(): array
    {
        return [];
    }

}
