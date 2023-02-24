<?php


namespace App\Repositories\ProductCategories;


use App\Models\Productcategories;
use App\Repositories\BaseRepository;

class ProductCategoriesRepository extends BaseRepository implements ProductCategoriesRepositoryInterface
{

    public function getModel()
    {
        return  Productcategories::class;
    }

    public function getAllProductCategories()
    {
        return Productcategories::all();
    }
}
