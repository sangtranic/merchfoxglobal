<?php

namespace App\View\Components;

use App\Repositories\ProductCategories\ProductCategoriesRepository;
use Illuminate\View\Component;

class Header extends Component
{
    protected $ProductCategoriesRepo;
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(ProductCategoriesRepository $categoriesRepository)
    {
        $this->ProductCategoriesRepo = $categoriesRepository;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        $productCates = $this->ProductCategoriesRepo->getAllProductCategories();
        return view('components.header',['productCates' => $productCates]);
    }
}
