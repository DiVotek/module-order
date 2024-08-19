<?php

namespace Modules\Order\Livewire;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Redirect;
use Livewire\Component;
use Modules\Order\Models\Cart;
use Modules\Product\Models\Product;

use function Modules\Order\Providers\order_slug;

class CartComponent extends Component
{
    public Cart $cart;
    public array $products;
    public $open = false;

    protected $listeners = ['openCart' => 'open', 'closeCart' => 'close', 'addToCart' => 'addToCart', 'removeFromCart'];
    public function mount()
    {
        $user_id = Auth::id();
        $uuid = Cookie::get('uuid');
        $cart = null;
        if ($user_id) {
            $cart = Cart::query()->where('user_id', $user_id)->first();
        }
        if ($uuid) {
            $cart = Cart::query()->where('uuid', $uuid)->first();
        }
        if (!$cart) {
            $cart = new Cart();
            $cart->uuid = $uuid;
            $cart->user_id = $user_id;
            $cart->products = [];
            $cart->total = 0;
            $cart->save();
        }
        $this->cart = $cart;
    }
    public function render()
    {
        return view('order::livewire.cart-component', [
            'products' => $this->cart->products,
        ]);
    }
    public function open()
    {
        $this->open = true;
    }
    public function close()
    {
        $this->open = false;
    }

    public function addToCart($id)
    {
        $product = Product::query()->find($id);
        if ($product) {
            $products = $this->cart->products;
            if (isset($products[$id])) {
                $products[$id]['quantity'] += 1;
            } else {
                $product->quantity = 1;
                $product->image = $product->images[0] ?? '';
                $products[$id] = $product->toArray();
            }
        }
        $this->cart->products = $products;
        $this->calculateTotal();
        $this->open();
    }
    public function calculateTotal()
    {
        $total = 0;
        foreach ($this->cart->products as $product) {
            $total += $product['price'] * $product['quantity'];
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
}
