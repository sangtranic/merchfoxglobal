<?php

namespace App\Http\Controllers;

use App\Helper\FileUploadHelper;
use App\Helper\Helper;
use App\Models\Productcategories;
use App\Models\Products;
use App\Repositories\Product\ProductRepository;
use App\Repositories\User\UserRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Intervention\Image\Facades\Image as Image;

class ProductController extends Controller
{
    protected $productRepo;
    protected $UsersRepo;

    public function __construct(ProductRepository $productRepo, UserRepository $userRepository)
    {
        $this->middleware('auth');
        $this->productRepo = $productRepo;
        $this->UsersRepo = $userRepository;
    }

    public function index()
    {
        $users = $this->UsersRepo->getAll();
        $productCates = Productcategories::all();
        $query = Products::query();
        $query->orderBy('id','desc');
        $products = $query->paginate(20);
        $filter_productCateId = 0;
        $filter_search = '';
        $filter_user = 0;
        $filter_isFileDesign = false;
        return view('products.index', [
            'products' => $products,
            'users' => $users,
            'productCates' => $productCates,
            'productCate' => $filter_productCateId,
            'search' => $filter_search,
            'user' => $filter_user,
            'isFileDesign' => $filter_isFileDesign
        ]);
    }

    public function search(Request $request)
    {
        $query = Products::query();
        $filter_productCateId = 0;
        $filter_search = '';
        $filter_user = 0;
        $filter_isFileDesign = false;
        if ($request->input('productCate')) {
            $filter_productCateId = $request->input('productCate');
            $query->where('categoryId', $filter_productCateId);
        }

        if ($request->input('search')) {
            $filter_search = $request->input('search');
            $query->where('name', 'like', '%' . $filter_search . '%');
        }

        if ($request->input('user')) {
            $filter_user = $request->input('user');
            $query->where('createBy', $request->input('user'));
        }
        if ($request->has('isFileDesign')) {
            $filter_isFileDesign = true;
            $query->whereRaw('isFileDesign & b\'1\' = b\'0\'');
        }
        $query->orderBy('id','desc');
        $products = $query->paginate(20);

        $users = $this->UsersRepo->getAll();
        $productCates = Productcategories::all();

        return view('products.index', [
            'products' => $products,
            'users' => $users,
            'productCates' => $productCates,
            'productCate' => $filter_productCateId,
            'search' => $filter_search,
            'user' => $filter_user,
            'isFileDesign' => $filter_isFileDesign
        ]);
    }

    public function edit($id, Request $request)
    {
        $product = new Products();
        $product->id = 0;
        if ($id > 0) {
            $product = $this->productRepo->find($id);
        } else if ($request->has('cate')) {
            $product->categoryId = $request->integer('cate');
        }
        $callBack = '';
        if($request->has('callBack'))  {
          $callBack = $request->input('callBack');
        }
        $productColors =[];
        $productCates = Productcategories::pluck('name', 'id');
        if ($product->categoryId > 0){
            $productCategory = Productcategories::where('id', $product->categoryId )->first();
        }else{
            $productCategory = Productcategories::first();
        }
        if ($productCategory){
            $productColors = $productCategory->color_list;
        }
        return view('products.editForm', [
            'product' => $product,
            'productCates' => $productCates,
            'productColors' => $productColors,
            'callBack'=> $callBack]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'categoryId' => 'required',
            'name' => 'required|max:255',
            'description' => 'nullable|max:255',
            'url' => 'nullable|max:255',
            'urlImagePreviewOne' => 'nullable|max:255',
            'urlImagePreviewTwo' => 'nullable|max:255',
            'urlImageDesignOne' => 'nullable|max:255',
            'urlImageDesignTwo' => 'nullable|max:255',
            'color' => 'nullable|max:50'
        ]);
        $product = new Products($request->all());
        if ($request->has('imageDesignOne')) {
            $product->urlImageDesignOne = FileUploadHelper::saveImage($request->file('imageDesignOne'));
        }
        if ($request->has('imageDesignTwo')) {
            $product->urlImageDesignTwo = FileUploadHelper::saveImage($request->file('imageDesignTwo'));
        }
        $product->createBy = Auth::id();
        $product->isFileDesign = $request->has('isFileDesign') ? 1 : 0;
        if($product->isFileDesign == 0 &&
            (!Helper::IsNullOrEmptyString($product->urlImageDesignOne) || !Helper::IsNullOrEmptyString($product->urlImageDesignTwo)))
        {
            $product->isFileDesign = 1;
        }else if($product->isFileDesign == 1 && Helper::IsNullOrEmptyString($product->urlImageDesignOne) &&
            Helper::IsNullOrEmptyString($product->urlImageDesignTwo))
        {
            $product->isFileDesign = 0;
        }

        $product->save();

        return back()->with('status', 'Successfully')->with('productId',$product->id);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'categoryId' => 'required',
            'name' => 'required|max:255',
            'description' => 'nullable|max:255',
            'url' => 'nullable|max:255',
            'urlImagePreviewOne' => 'nullable|max:255',
            'urlImagePreviewTwo' => 'nullable|max:255',
            'urlImageDesignOne' => 'nullable|max:255',
            'urlImageDesignTwo' => 'nullable|max:255',
            'color' => 'nullable|max:50'
        ]);
        $product = Products::findOrFail($id);
        //$product->update($request->all());
        $product->name = $request->input('name');
        $product->itemId = $request->input('itemId');
        $product->description = $request->input('description');
        $product->url = $request->input('url');
        $product->urlImagePreviewOne = $request->input('urlImagePreviewOne');
        $product->urlImagePreviewTwo = $request->input('urlImagePreviewTwo');
        $product->urlImageDesignOne = $request->input('urlImageDesignOne');
        $product->urlImageDesignTwo = $request->input('urlImageDesignTwo');
        $product->isFileDesign = $request->has('isFileDesign') ? 1 : 0;
        $product->color = $request->input('color');
        $product->updateBy = Auth::id();
        if ($request->has('imageDesignOne')) {
            $product->urlImageDesignOne = FileUploadHelper::saveImage($request->file('imageDesignOne'));
        }
        if ($request->has('imageDesignTwo')) {
            $product->urlImageDesignTwo = FileUploadHelper::saveImage($request->file('imageDesignTwo'));
        }
        if($product->isFileDesign == 0 &&
            (!Helper::IsNullOrEmptyString($product->urlImageDesignOne) || !Helper::IsNullOrEmptyString($product->urlImageDesignTwo)))
        {
            $product->isFileDesign = 1;
        }else if($product->isFileDesign == 1 && Helper::IsNullOrEmptyString($product->urlImageDesignOne) &&
            Helper::IsNullOrEmptyString($product->urlImageDesignTwo))
        {
            $product->isFileDesign = 0;
        }
        $product->save();

        return back()->with('status', 'Successfully');
    }

    public function destroy($id)
    {
        $products = Products::findOrFail($id);
        $products->delete();

        return redirect()->route('products.index');
    }
}
