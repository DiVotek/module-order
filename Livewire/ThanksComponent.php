<?php

namespace Modules\Order\Livewire;

use App\Actions\GetCart;
use App\Models\StaticPage;
use Livewire\Component;
use Modules\Order\Models\Order;

class ThanksComponent extends Component
{
    public StaticPage $page;
    public ?Order $order;

    public function mount(StaticPage $entity)
    {
        $this->page = $entity;
        $this->order = Order::find(request()->get('number',0));
        $cart = GetCart::run();
        if($cart && $cart->products == $this->order->products){
            $cart->delete();
        }
    }

    public function render()
    {
        return view('order::livewire.thanks-component');
    }
}
