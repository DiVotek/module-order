<?php

namespace Modules\Order\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use Modules\Category\Models\Category;

class CategoryComponent extends Component
{
    use WithPagination;

    public Category $category;
    public $categories;
    public string $url;

    public function mount(Category $entity)
    {
        $this->category = $entity;
        $this->url = url()->current();
        $this->categories =  Category::all();
    }

    public function render()
    {
        return view('order::livewire.category-component', [
            'products' => $this->category->products()->paginate(12),
        ]);
    }
}
