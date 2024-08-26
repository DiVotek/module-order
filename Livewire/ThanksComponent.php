<?php

namespace Modules\Order\Livewire;

use App\Actions\GetCart;
use App\Models\StaticPage;
use App\Models\SystemPage;
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
        $page = SystemPage::query()->where('page_id', $this->page->id)->first();
        $design = 'page.default';
        if($page && $page->design){
            $design = $page->setting_key . '.' . $page->design;
        }
        return view('template::' . $design);
    }
}
