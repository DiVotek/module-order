<?php

namespace Modules\Order\Livewire;

use App\Actions\GetCart;
use App\Models\StaticPage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Modules\Order\Models\Cart;
use Modules\Order\Models\DeliveryMethod;
use Modules\Order\Models\Order;
use Modules\Order\Models\OrderStatus;
use Modules\Order\Models\PaymentMethod;
use Modules\Order\Services\CheckoutField;
use Modules\Order\Services\PaymentService;
use Modules\Order\Services\PaymentStatus\StatusDontNeed;

class CheckoutComponent extends Component
{
    public StaticPage $page;
    public Cart $cart;
    public array $products;
    public StaticPage $policy;
    public $paymentMethods;
    public $deliveryMethods;
    public $deliveryMethod;
    public $paymentMethod;
    public array $userData = [];

    public function mount(StaticPage $entity)
    {
        $this->page = $entity;
        $this->cart = GetCart::run();
        $this->products = $this->cart->products ?? [];
        if (empty($this->products)) {
            return redirect()->to(home());
        }
        $this->policy = StaticPage::query()->find(setting(config('settings.order.policy'), 0));
        $this->deliveryMethods = DeliveryMethod::query()->get();
        $this->paymentMethods = PaymentMethod::query()->get();
        $this->deliveryMethod = setting(config('settings.payment.default'), 0);
        $this->paymentMethod = setting(config('settings.payment.default'), 0);
    }
    public function render()
    {
        return view('order::livewire.checkout-component');
    }

    #[Computed]
    public function total()
    {
        $total = 0;
        foreach ($this->products as $product) {
            $total += $product['price'] * $product['quantity'];
        }
        $payment = PaymentMethod::query()->find($this->paymentMethod);
        if ($payment) {
            $total = $total + $total * $payment->comission;
        }
        $delivery = DeliveryMethod::query()->find($this->deliveryMethod);
        if ($delivery) {
            if ($delivery->free_from > $total) {
                $total += $delivery->price;
            }
        }
        return $total;
    }
    #[Computed]
    public function fields()
    {
        $fields = [];
        if (setting(config('settings.order.fields.name'), false)) {
            $fields[] = new CheckoutField('name', is_required: true);
        }
        if (setting(config('settings.order.fields.surname'), false)) {
            $fields[] = new CheckoutField('surname', is_required: true);
        }
        if (setting(config('settings.order.fields.email'), false)) {
            $fields[] = new CheckoutField('email', is_required: true, type: 'email', rules: ['email']);
        }
        if (setting(config('settings.order.fields.phone'), false)) {
            $fields[] = new CheckoutField(
                'phone',
                is_required: true,
                mask: '+7 (999) 999-99-99',
                //  rules: ['regex:/^\+7 \(\d{3}\) \d{3}-\d{2}-\d{2}$/']
            );
        }
        if (setting(config('settings.order.fields.comment'), false)) {
            $fields[] = new CheckoutField('comment');
        }

        return $fields;
    }

    public function removeFromCart($id)
    {
        $products = $this->cart->products;
        unset($products[$id]);
        $this->cart->products = $products;
        $this->cart->save();
        $this->products = $this->cart->products;
        if (count($this->products) == 0) {
            return redirect()->to(home());
        }
        $this->total();
    }

    public function changeQuantity($id, $quantity)
    {
        $products = $this->cart->products;
        $products[$id]['quantity'] = $quantity;
        if ($products[$id]['quantity'] <= 0) {
            $products[$id]['quantity'] = 1;
        }
        $this->cart->products = $products;
        $this->cart->save();
        $this->products = $this->cart->products;
        $this->total();
    }

    public function changedPaymentMethod($id)
    {
        $this->paymentMethod = $id;
    }

    public function submit()
    {
        $fields =  $this->fields();
        $validation = [];
        foreach ($fields as $field) {
            $validation = array_merge($validation, $field->validate());
        }
        $this->validate(array_filter($validation));
        $order = Order::query()->create([
            'user_id' => Auth::id(),
            'delivery_method_id' => $this->deliveryMethod,
            'user_data' => $this->userData,
            'payment_method_id' => $this->paymentMethod,
            'status' => OrderStatus::first()->id ?? 0,
            'products' => $this->products,
            'total' => $this->total(),
            'currency' => app('currency')->code,
            'tax' => 0,
            'payment_status' => (new StatusDontNeed())->getName(),
        ]);
        return (new PaymentService($order))->pay();
    }
}
