<?php

namespace App\Http\Controllers;

use App\Models\Orders;
use App\Models\Productcategories;
use App\Models\Products;
use App\Models\Users;
use App\Models\Vps;
use Illuminate\Http\Request;

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
        $vpses = Vps::all();
        $orders = Orders::paginate(1);

        $filter_dateFrom = '';
        $filter_dateTo = '';
        $filter_productCateId = 0;
        $filter_user = 0;
        $filter_vps = 0;
        $filter_orderNumber = '';
        $filter_product = '';
        $filter_customer = '';
        $filter_track = 0;
        $filter_orderid = 0;
        $filter_ebay = 0;
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
        $filter_orderid = 0;
        $filter_ebay = 0;
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
            'orderid'=>$filter_orderid,
            'ebay'=>$filter_ebay
        ]);
    }
    public function searchByKey(Request $request)
    {
        $orders = [];
        $keyword = '';
        if ($request->input('q')) {
            $orders = Orders::where('orderNumber', 'like', "%$keyword%")
                ->take(10)
                ->get();
        }
        return response()->json($orders);
    }
}
