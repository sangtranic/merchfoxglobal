<?php

namespace App\Http\Controllers;

use App\Models\Objectstatus;
use App\Models\Orders;
use App\Models\Productcategories;
use App\Models\Products;
use App\Models\Seller;
use App\Models\Users;
use App\Models\Vps;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrdersController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $productCates = Productcategories::all();
        $users = Users::all();
        $orders = Orders::paginate(1);
        $filter_dateFrom = '';
        $filter_dateTo = '';
        $filter_productCateId = 0;
        $filter_user = 0;
        $filter_vps = 1;
        $filter_orderNumber = '';
        $filter_product = '';
        $filter_customer = '';
        $filter_track = 0;
        $filter_carrie = 0;
        $filter_orderid = 0;
        $filter_ebay = 0;
        $showProducts = [];
        $vpses = Vps::where('id',$filter_vps)->get();
        if (!($orders->isEmpty())) {
            $productIds = $orders->pluck('productId')->toArray();
            $showProducts = Products::whereIn('id', $productIds)->get();
        }
        return view('orders.index', [
            'orders' => $orders,
            'users'=> $users,
            'vpses'=> $vpses,
            'productCates' => $productCates,
            'showProducts'=>$showProducts,
            'dateFrom'=>$filter_dateFrom,
            'dateTo'=>$filter_dateTo,
            'productCate'=>$filter_productCateId,
            'user'=>$filter_user,
            'vps'=>$filter_vps,
            'orderNumber'=>$filter_orderNumber,
            'product'=>$filter_product,
            'customer'=>$filter_customer,
            'track'=>$filter_track,
            'carrie'=>$filter_carrie,
            'orderid'=>$filter_orderid,
            'ebay'=>$filter_ebay
        ]);
    }
    public function search(Request $request)
    {
        $productCates = Productcategories::all();
        $users = Users::all();
        $vpses = Vps::all();
        $query = Orders::query();
        $filter_dateFrom = '';
        $filter_dateTo = '';
        $filter_productCateId = 0;
        $filter_user = 0;
        $filter_vps = 0;
        $filter_orderNumber = '';
        $filter_product = '';
        $filter_customer = '';
        $filter_track = 0;
        $filter_carrie = 0;
        $filter_orderid = 0;
        $filter_ebay = 0;
        if ($request->input('productCate')) {
            $filter_productCateId = $request->integer('productCate');
        }
        if ($request->input('dateFrom')) {
            $filter_dateFrom = $request->input('dateFrom');
        }
        if ($request->input('dateTo')) {
            $filter_dateTo = $request->input('dateTo');
        }
        if ($filter_dateFrom && $filter_dateTo) {
            $query->whereBetween('created_at', [$filter_dateFrom, $filter_dateTo]);
        }else if ($filter_dateFrom){
            $query->whereDate('created_at', '>=', $filter_dateFrom);
        }else if ($filter_dateTo){
            $query->whereDate('created_at', '<=', $filter_dateTo);
        }
        $orders = $query->paginate(1);

        $showProducts = [];
        if (!($orders->isEmpty())) {
            $productIds = $orders->pluck('productId')->toArray();
            $showProducts = Products::whereIn('id', $productIds)->get();
        }
        return view('orders.index', [
            'orders' => $orders,
            'users'=> $users,
            'vpses'=> $vpses,
            'productCates' => $productCates,
            'showProducts'=>$showProducts,
            'dateFrom'=>$filter_dateFrom,
            'dateTo'=>$filter_dateTo,
            'productCate'=>$filter_productCateId,
            'user'=>$filter_user,
            'vps'=>$filter_vps,
            'orderNumber'=>$filter_orderNumber,
            'product'=>$filter_product,
            'customer'=>$filter_customer,
            'track'=>$filter_track,
            'carrie'=>$filter_carrie,
            'orderid'=>$filter_orderid,
            'ebay'=>$filter_ebay
        ]);
    }
    public function editForm(Request $request){
        $productCates = Productcategories::all();
        $vpses = null;
        $sellers = null;
        $product = null;
        $statusList = Objectstatus::where('tableName','products')->get();
        $order = new Orders();
        $productCategory = null;
        $productCate = 0;
        $productSizes = [];
        $productColors = [];
        $id = 0;
        if ($request->input('productCate')) {
            $productCate = $request->integer('productCate');
        }
        if ($productCate == 0){
            $productCategory = $productCates->first();
            $productCate = $productCategory->id;
        }else{
            $productCategory = $productCates->where('id',$productCate)->first();
        }
        if ($productCategory){
            $productSizes = $productCategory->size_list;
            $productColors = $productCategory->color_list;
        }
        if ($request->input('id')) {
            $id = $request->integer('id');
        }
        if ($id > 0){
            $order = Orders::findOrFail($id);
            if ($order == null){
                $order = new Orders();
            }else{
                $product = Products::findOrFail($order->productId);
                $vpses = Vps::where('userId', $order->userId)->get();
                $sellers = Seller::where('userId', $order->userId)->get();
            }
        }else{
            $order->userId = Auth::id();
            $order->categoryId = $productCate;
            $order->statusId = $statusList->first()->statusId;
        }
        if ($vpses == null) {
            $vpses = Vps::where('userId', Auth::id())->get();
        }
        if ($sellers == null){
            $sellers = Seller::where('userId', Auth::id())->get();
        }
        return view('orders.editForm',[
            'order' => $order,
            'vpses'=> $vpses,
            'sellers'=> $sellers,
            'productCates' => $productCates,
            'product'=>$product,
            'productSizes'=>$productSizes,
            'productColors'=>$productColors,
            'statusList'=>$statusList
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'categoryId' => 'required',
            'sellerId' => 'required',
            'userId' => 'required'
        ]);
        $order = new Orders($request->all());
        dump($order);
        if ($order->productId > 0){
            $product = new Products($request->all());
            $product->id = $order->productId;
            dump($product);
        }
    }

    public function update(Request $request, $id)
    {

        $request->validate([
            'categoryId' => 'required',
            'sellerId' => 'required',
            'userId' => 'required'
        ]);
    }
}
