<?php

namespace Modules\Order\Livewire;

use Livewire\Component;
use Modules\Product\Models\Product;

class ProductComponent extends Component
{
    public Product $product;

    public $quantity = 1;

    public function mount(Product $entity)
    {
        $this->product = $entity;
    }

    public function render()
    {
        return view('order::livewire.product-component');
    }

    public function changeQuantity($quantity)
    {
        $this->quantity = $quantity;
        if ($this->quantity < 1) {
            $this->quantity = 1;
        }
    }

    public function addToCart()
    {
        if (module_enabled('Order')) {
            for ($i = 0; $i < $this->quantity; $i++) {
                $this->dispatch('addToCart', $this->product->id);
            }
            $this->quantity = 1;
        }
    }
}
