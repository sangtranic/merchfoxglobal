<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductCategoryRequest;
use App\Models\Productcategories;
use App\Repositories\ProductCategories\ProductCategoriesRepository;
use App\Repositories\User\UserRepository;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductCategoriesController extends Controller
{
    protected $ProductCategoriesRepo;
    protected $UsersRepo;

    public function __construct(ProductCategoriesRepository $productCategoriesRepo, UserRepository $userRepository)
    {
        $this->middleware('auth');
        $this->ProductCategoriesRepo = $productCategoriesRepo;
        $this->UsersRepo = $userRepository;
    }

    public function index()
    {
        $users = $this->UsersRepo->getAll();
        $productCates = Productcategories::paginate(20);
        return view('productcategories.index', ['productCates' => $productCates, 'users' => $users]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|max:150',
            'description' => 'nullable|max:255',
            'sizes' => 'nullable|max:255',
            'colors' => 'nullable|max:255',
            'priceMin' => 'nullable|numeric',
            'priceMax' => 'nullable|numeric',
            'keyword' => 'nullable|max:255'
        ]);
        $productCategory = new Productcategories($request->all());
        $productCategory->createBy = Auth::id();
        $productCategory->save();

        return back()->with('status', 'Successfully');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|max:150',
            'description' => 'nullable|max:255',
            'sizes' => 'nullable|max:255',
            'colors' => 'nullable|max:255',
            'priceMin' => 'nullable|numeric',
            'priceMax' => 'nullable|numeric',
            'keyword' => 'nullable|max:255'
        ]);
        $productCategory = Productcategories::findOrFail($id);

        $productCategory->name = $request->input('name');
        $productCategory->description = $request->input('description');
        $productCategory->sizes = $request->input('sizes');
        $productCategory->colors = $request->input('colors');
        $productCategory->priceMin = $request->input('priceMin');
        $productCategory->priceMax = $request->input('priceMax');
        $productCategory->keyword = $request->input('keyword');
        $productCategory->updateBy = Auth::id();

        $productCategory->update($request->all());

        $productCategory->save();

        return back()->with('status', 'Successfully');
    }


    public function edit($id)
    {
        $productCate = new Productcategories();
        $productCate->id = 0;
        if ($id > 0) {
            $productCate = $this->ProductCategoriesRepo->find($id);
        }

        return view('productcategories.editForm', ['productCate' => $productCate]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $productCates = Productcategories::findOrFail($id);
        $productCates->delete();

        return redirect()->route('product-cates.index');
    }
}
