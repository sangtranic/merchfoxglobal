<?php

namespace App\Http\Controllers;

use App\Helper\Helper;
use App\Models\Orders;
use App\Models\Productcategories;
use App\Models\Products;
use App\Models\Seller;
use App\Models\Vps;
use http\Exception;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\DB;
use Nette\Utils\Html;

class RestApiController extends BaseController
{

    public function orderImport(Request $request)
    {
        $response = ["status"=>false, "message"=>"", 'data'=> null];
        try {
            if ($request->has('orderId')
                && strlen($request->input('orderId'))
                && $request->has('sellerID')
                && strlen($request->input('sellerID')
                    && $request->has('userIdImport')
                    && $request->integer('userIdImport') > 0)
            ) {
                $order = new Orders();
                $order->orderNumber = $request->input('orderId');
                if (Orders::where('orderNumber',$request->input('orderId'))->get()->count() > 0){
                    $response['status'] = false;
                    $response['message'] = 'Đơn hàng '.$order->orderNumber.' đã tồn tại.';
                    $response['data'] = $order->orderNumber;
                    return response()->json($response);

                }
                $seller = DB::table('seller')->where('sellerName', $request->input('sellerID'))->first();
                $userImport = DB::table('users')->where('id', $request->integer('userIdImport'))->first();
                if ($seller && $seller->id > 0 && $userImport && $userImport-> id > 0) {
                    $order->sellerId = $seller->id;
                    if ($seller->userId){
                      $vps =  Vps::where('userId', $seller->userId)->first();
                      if ($vps){
                          $order->vpsId = $vps->id;
                      }
                    }
                    $order->createBy = $userImport->id;
                    if ($request->has('createdDate')) {
                        $order->created_at = strtotime($request->input('createdDate'));
                    }
                    if ($request->has('channelID')) {
                        $order->channelId = $request->input('channelID');
                    }
                    if ($request->has('shipToAddressName')){
                        $order->shipToAddressName = $request->input('shipToAddressName');
                    }
                    if ($request->has('shipToAddressPhone')){
                        $order->shipToAddressPhone = $request->input('shipToAddressPhone');
                    }
                    if ($request->has('shipToAddressLine1')){
                        $order->shipToAddressLine1 = $request->input('shipToAddressLine1');
                    }
                    if ($request->has('shipToAddressLine2')){
                        $order->shipToAddressLine2 = $request->input('shipToAddressLine2');
                    }
                    if ($request->has('shipToAddressCity')){
                        $order->shipToAddressCity = $request->input('shipToAddressCity');
                    }
                    if ($request->has('shipToAddressCounty')){
                        $order->shipToAddressCounty = $request->input('shipToAddressCounty');
                    }
                    if ($request->has('shipToAddressStateOrProvince')){
                        $order->shipToAddressStateOrProvince = $request->input('shipToAddressStateOrProvince');
                    }
                    if ($request->has('shipToAddressPostalCode')){
                        $order->shipToAddressPostalCode = $request->input('shipToAddressPostalCode');
                    }
                    if ($request->has('shipToAddressCountry')){
                        $order->shipToAddressCountry = $request->input('shipToAddressCountry');
                    }
                    $product = null;
                    if ($request->has('itemID')){
                        $order->itemId = $request->input('itemID');
                        $product = DB::table('products')->where('itemId', $order->itemId)->first();
                    }
                    $productName = '';
                    $productSize = '';
                    $productColor = '';

                    $inputString = $request->input('title');
                    $regex = "/(.*)\[(.*?)\](.*)/";

                    if (preg_match($regex, $inputString, $matches)) {
                        if (strlen($matches[1])){
                            $productName = $matches[1];
                        }
                        if (strlen($matches[2])){
                            $productSize = $matches[2];
                        }
                    }
                    $categoryId = 0;
                    $productCategories = Productcategories::all();
                    foreach ($productCategories as $category) {
                        if (!Helper::IsNullOrEmptyString($category->keyword)) {
                            $keys = explode(',', $category->keyword);
                            if (count($keys) > 0) {
                                foreach ($keys as $key) {
                                    if (str_contains(strtolower($productName), strtolower($key))) {
                                        $categoryId = $category->id;
                                        break;
                                    }
                                }
                            }
                        }
                        if (!Helper::IsNullOrEmptyString($category->colors)){
                            $colors = explode(',', $category->colors);
                            if (count($colors) > 0) {
                                foreach ($colors as $itemColor) {
                                    if (str_contains(strtolower($productName), strtolower($itemColor))) {
                                        $productColor = $itemColor;
                                        break;
                                    }
                                }
                            }
                        }
                        if ($categoryId > 0 && !Helper::IsNullOrEmptyString($productColor)){
                            break;
                        }

                    }
                    $order->categoryId = $categoryId;
                    $order->size = $productSize;
                    $order->color = $productColor;
                    if ($product){
                        $order->productId = $product->id;
                    }else if (!Helper::IsNullOrEmptyString($order->itemId) && strlen($productName) && $categoryId > 0){
                        $product = new Products();
                        $product->itemId =  $order->itemId;
                        $product->categoryId = $categoryId;
                        $product->name = $productName;
                        $product->createBy = $userImport->id;
                        $product->save();
                        $order->productId = $product->id;
                    }

                    if ($request->has('note')){
                        $order->note = $request->input('note');
                    }
                    if ($request->has('SKU')){
                        $order->sku = $request->input('SKU');
                    }
                    if ($request->has('quantity')){
                        $order->quantity = $request->integer('quantity');
                    }else{
                        $order->quantity = 0;
                    }

                    if ($request->has('unitPrice')){
                        $order->price = $request->input('unitPrice');
                    }else{
                        $order->price = 0;
                    }

                    if ($request->has('ship')){
                        $order->ship = $request->input('ship');
                    }else{
                        $order->ship = 0;
                    }
                    if ($request->has('orderSumTotal')){
                        $order->cost = $request->input('orderSumTotal');
                    }else{
                        $order->cost = 0;
                    }
                    $order->statusId = 1;
                    $order->trackingStatusId = 0;
                    $order->carrierStatusId = 0;
                    $order->syncStoreStatusId = 0;
                    if ($order->categoryId > 0 && $order->sellerId > 0 && $order->vpsId > 0){
                        $order->save();
                        $response['status'] = true;
                        $response['message'] = 'Import thành công';
                        $response['data'] = $order;
                    }else{

                        $response['status'] = false;
                        $response['message'] = 'Không tìm thấy thông tin hãy kiểm tra lại Title và sellerId';
                        $response['data'] = $order;
                    }

                } else {
                    $response['status'] = false;
                    $response['message'] = 'Hãy kiểm tra lại thông tin seller và user import hoặc thông báo quản trị viên.';
                }
            } else {
                $response['status'] = false;
                $response['message'] = 'Dữ liệu orderId, sellerID không được để trống.';
            }
        }catch (Exception $ex){

            $response['status'] = false;
            $response['message'] = $ex->getMessage();
        }
        return response()->json($response);
    }
}
