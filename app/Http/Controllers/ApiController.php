<?php

namespace App\Http\Controllers;

use App\Models\Orders;
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
                $query =  Products::where('name', 'like', "%$keyword%")
                    ->orWhere('description', 'like', "%$keyword%");
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
}
