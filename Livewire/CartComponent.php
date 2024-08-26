<?php

namespace Modules\Order\Livewire;

use App\Actions\GetCart;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Redirect;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Modules\Order\Models\Cart;
use Modules\Product\Models\Product;

class CartComponent extends Component
{
    public Cart $cart;
    public $open = false;
    public $event = 'openCart';

    protected $listeners = ['openCart' => 'open', 'closeCart' => 'close', 'addToCart' => 'addToCart', 'removeFromCart'];
    public function mount()
    {
        $this->cart = GetCart::run();
    }
    public function render()
    {
        return view(
            'template::' . setting(config('settings.cart.design'), 'cart.default'),
            [
                'products' => $this->cart->products,
            ]
        );
    }
    public function open()
    {
        $this->cart = GetCart::run();
        $this->open = true;
    }
    public function close()
    {
        $this->open = false;
        $this->dispatch('close' . $this->event);
    }

    public function addToCart($id, array $options = [])
    {
        $product = Product::query()->find($id);
        if ($product) {
            $products = $this->products;
            if (isset($products[$id])) {
                $products[$id]['quantity'] += 1;
            } else {
                $product->quantity = 1;
                $product->image = $product->images[0] ?? '';
                $product->options = $options;
                $products[$id] = $product->only([
                    'id',
                    'name',
                    'slug',
                    'price',
                    'quantity',
                    'image',
                    'options',
                ]);
            }
        }
        $this->cart->products = $products;
        $this->calculateTotal();
        $this->dispatch($this->event);
        $this->open();
    }
    #[Computed]
    public function products()
    {
        return GetCart::run()->products;
    }


    public function calculateTotal()
    {
        $total = 0;
        foreach ($this->cart->products as $product) {
            $total += $product['price'] * $product['quantity'];
            if (module_enabled('Options') && isset($product['options'])) {
                $productModel = Product::query()->find($product['id']);
                foreach ($product['options'] as $option) {
                    $option = $productModel->optionValues()->where('option_value_id', $option)->first()->pivot;
                    if ($option->sign == '+') {
                        $total += $option->price;
                    } else {
                        $total -= $option->price;
                    }
                }
            }
        }
        $this->cart->total = $total;
        $this->cart->save();
    }
    public function removeFromCart($id)
    {
        if (isset($this->cart->products[$id])) {
            $products = $this->cart->products;
            unset($products[$id]);
            $this->cart->products = $products;
        }
        $this->calculateTotal();
    }


    public function checkout()
    {
        return Redirect::to(order_slug());
    }

    public function changeQuantity($id, $quantity)
    {
        if ($quantity <= 0) {
            $quantity = 1;
        }
        if (isset($this->cart->products[$id])) {
            $products = $this->cart->products;
            $products[$id]['quantity'] = $quantity;
            $this->cart->products = $products;
        }
        $this->calculateTotal();
    }
}
