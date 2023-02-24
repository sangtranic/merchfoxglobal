<?php
namespace App\Repositories\ProductCategories;

use App\Repositories\RepositoryInterface;

interface ProductCategoriesRepositoryInterface extends RepositoryInterface
{
    public function getAllProductCategories();
}
