<?php

namespace App\Http\Controllers;

use App\Models\Orders;
use App\Models\Productcategories;
use App\Models\Products;
use App\Models\Vps;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class ApiController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function vpsSearch(Request $request)
    {
        $vpses = [];
        if ($request->input('q')) {
            $keyword = $request->input('q');
            if (Str::length($keyword) > 1){
                $vpses = Vps::where('name', 'like', "%$keyword%")
                    ->orderBy('name')
                    ->select('id', 'name')
                    ->take(10)
                    ->get();

                if ($vpses && !($vpses->isEmpty())){
                    $new_vpses = array_map(function ($vps) {
                      return  [
                            "id" => $vps["id"],
                            "text" => $vps["name"]
                        ];
                    }, $vpses->toArray());
                    return response()->json($new_vpses);
                }
            }
        }
        return response()->json($vpses);
    }
    public function ordersSearch(Request $request)
    {
        $orders = [];
        if ($request->input('q')) {
            $keyword = $request->input('q');
            if (Str::length($keyword) > 1){
                $role = Auth::user()->role;
                $query =  Orders::where('orderNumber', 'like', "%$keyword%");
                if ($role == 'user'){
                    $query->where('createBy', Auth::id());
                }
                $orders = $query
                    ->select('id', 'orderNumber')
                    ->orderBy('orderNumber')
                    ->take(10)
                    ->get();
                if ($orders && !($orders->isEmpty())){
                    $new_orders = array_map(function ($order) {
                        return  [
                            "id" => $order["id"],
                            "text" => $order["orderNumber"]
                        ];
                    }, $orders->toArray());
                    return response()->json($new_orders);
                }
            }
        }
        return response()->json($orders);
    }
    public function productsSearch(Request $request)
    {
        $products = [];
        if ($request->input('q')) {
            $keyword = $request->input('q');
            if (Str::length($keyword) > 1){
                $role = Auth::user()->role;
                $query =  Products::where(function ($_q) use ($keyword) {
                    $_q->where( 'name', 'like', '%'.$keyword.'%')->orWhere('description', 'like', '%'.$keyword.'%');
                });

                if ($role == 'user'){
                    $query->where('createBy', Auth::id());
                }
                $products = $query
                    ->select('id', 'name')
                    ->orderBy('name')
                    ->take(10)
                    ->get();
                if ($products && !($products->isEmpty())){
                    $new_products= array_map(function ($product) {
                        return  [
                            "id" => $product["id"],
                            "text" => $product["name"]
                        ];
                    }, $products->toArray());
                    return response()->json($new_products);
                }
            }
        }
        return response()->json($products);
    }
    public function productDetail(Request $request)
    {
        $product = null;
        if ($request->input('id')) {
            $id = $request->integer('id');
            $product = Products::findOrFail($id);
            $product->imageDesign1 = $product->url_img_design1;
            $product->imageDesign2 = $product->url_img_design2;
        }
        return response()->json($product);
    }
    public function productCategoryDetail(Request $request)
    {
        $productCate = null;
        if ($request->input('id')) {
            $id = $request->integer('id');
            $productCate = Productcategories::findOrFail($id);
            if ($productCate){
                $productCate -> listSizes = $productCate->size_list;
                $productCate-> listColors = $productCate->color_list;
            }
        }
        return response()->json($productCate);
    }
    public function removeOrders(Request $request)
    {
        if ($request->input('ids')) {
            $str_ids = $request->input('ids');
            $ids = explode(',',$str_ids );
            $orders = Orders::wherein('id', $ids)->get();
            if (strtolower(Auth::user()->role) == 'admin'){
                foreach ($orders as $order) {
                    $order->delete();
                }
                return response()->json(["status"=>'success','data'=>$ids,'message'=>'X??a th??nh c??ng c??c ????n h??ng.']);
            }else{
                $userId = Auth::id();
                $orderNotDeletes = [];
                $orderDeletes = [];
                foreach ($orders as $order){
                    if ($userId != $order->createBy){
                        array_push($orderNotDeletes, $order->orderNumber);
                    }else{
                        array_push($orderDeletes, $order->id);
                        $order->delete();
                    }
                }
                if (count($orderNotDeletes) > 0 &&count($orderDeletes) > 0){
                    return response()->json(["status"=>'success','data'=>$orderDeletes,'message'=>'C??c ????n h??ng '.join('; ',$orderDeletes). ' ???? ???????c x??a v?? c??c ????n h??ng '.join('; ',$orderNotDeletes). ' b???n kh??ng c?? quy???n x??a.']);
                }else if (count($orderNotDeletes) > 0){
                    return response()->json(["status"=>'false','data'=>null,'message'=>'C??c ????n h??ng '.join('; ',$orderNotDeletes). ' b???n kh??ng c?? quy???n x??a.']);
                }
                return response()->json(["status"=>'success','data'=>$orderDeletes,'message'=>'X??a th??nh c??ng c??c ????n h??ng.']);
            }
        }
        return response()->json(["status"=>'false','data'=>null,'message'=>'X??a ????n h??ng x???y ra l???i.']);
    }
}
