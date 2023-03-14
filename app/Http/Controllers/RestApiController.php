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
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use League\Csv\Reader;
use Nette\Utils\Html;

class RestApiController extends BaseController
{

    public function orderImport(Request $request)
    {
        $response = ["status" => false, "message" => "", 'data' => null];
        try {
            $validatedData = $request->validate([
                'orderId' => 'required|unique:orders,orderNumber',
                'sellerID' => 'required',
                'userIdImport' => 'required',
                'title' => 'required',
            ]);
            if (!$validatedData) {
                $response['status'] = false;
                $response['message'] = 'Hãy kiểm tra lại thông tin đơn hàng.';
                $response['data'] = $validatedData;
                return response()->json($response);
            }

            if ($request->has('orderId')
                && strlen($request->input('orderId'))
                && $request->has('sellerID')
                && strlen($request->input('sellerID')
                    && $request->has('userIdImport')
                    && $request->integer('userIdImport') > 0)
            ) {
                $order = new Orders();
                $order->orderNumber = $request->input('orderId');
                if (Orders::where('orderNumber', $request->input('orderId'))->get()->count() > 0) {
                    $response['status'] = false;
                    $response['message'] = 'Đơn hàng ' . $order->orderNumber . ' đã tồn tại.';
                    $response['data'] = $order->orderNumber;
                    return response()->json($response);

                }
                $seller = DB::table('seller')->where('sellerName', $request->input('sellerID'))->first();
                $userImport = DB::table('users')->where('id', $request->integer('userIdImport'))->first();
                if ($seller && $seller->id > 0 && $userImport && $userImport->id > 0) {
                    $order->sellerId = $seller->id;
                    if ($seller->userId) {
                        $vps = Vps::where('userId', $seller->userId)->first();
                        if ($vps) {
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
                    if ($request->has('shipToAddressName')) {
                        $order->shipToAddressName = $request->input('shipToAddressName');
                    }
                    if ($request->has('shipToAddressPhone')) {
                        $order->shipToAddressPhone = $request->input('shipToAddressPhone');
                    }
                    if ($request->has('shipToAddressLine1')) {
                        $order->shipToAddressLine1 = $request->input('shipToAddressLine1');
                    }
                    if ($request->has('shipToAddressLine2')) {
                        $order->shipToAddressLine2 = $request->input('shipToAddressLine2');
                    }
                    if ($request->has('shipToAddressCity')) {
                        $order->shipToAddressCity = $request->input('shipToAddressCity');
                    }
                    if ($request->has('shipToAddressCounty')) {
                        $order->shipToAddressCounty = $request->input('shipToAddressCounty');
                    }
                    if ($request->has('shipToAddressStateOrProvince')) {
                        $order->shipToAddressStateOrProvince = $request->input('shipToAddressStateOrProvince');
                    }
                    if ($request->has('shipToAddressPostalCode')) {
                        $order->shipToAddressPostalCode = $request->input('shipToAddressPostalCode');
                    }
                    if ($request->has('shipToAddressCountry')) {
                        $order->shipToAddressCountry = $request->input('shipToAddressCountry');
                    }

                    if ($request->has('note')) {
                        $order->note = $request->input('note');
                    }
                    if ($request->has('SKU')) {
                        $order->sku = $request->input('SKU');
                    }
                    if ($request->has('quantity')) {
                        $order->quantity = $request->integer('quantity');
                    } else {
                        $order->quantity = 0;
                    }

                    if ($request->has('unitPrice')) {
                        $order->price = $request->input('unitPrice');
                    } else {
                        $order->price = 0;
                    }

                    if ($request->has('ship')) {
                        $order->ship = $request->input('ship');
                    } else {
                        $order->ship = 0;
                    }
                    if ($request->has('orderSumTotal')) {
                        $order->cost = $request->input('orderSumTotal');
                    } else {
                        $order->cost = 0;
                    }

                    $product = null;
                    if ($request->has('itemID')) {
                        $order->itemId = $request->input('itemID');
                        $product = DB::table('products')->where('itemId', $order->itemId)->first();
                    }
                    $productName = '';
                    $productSize = '';
                    $productColor = '';

                    $inputString = $request->input('title');
                    $regex = "/(.*)\[(.*?)\](.*)/";

                    if (preg_match($regex, $inputString, $matches)) {
                        if (strlen($matches[1])) {
                            $productName = $matches[1];
                        }
                        if (strlen($matches[2])) {
                            $productSize = $matches[2];
                        }
                    }
                    $categoryId = 0;
                    $productCategories = Productcategories::all();
                    foreach ($productCategories as $category) {
                        if ($order->price > 0
                            && ($category->priceMin && $category->priceMin >= 0 && $category->priceMax && $category->priceMax > 0)
                            && ($order->price >= $category->priceMin && $order->price <= $category->priceMax)) {
                            $categoryId = $category->id;
                            break;
                        }
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
//                        if (!Helper::IsNullOrEmptyString($category->colors)) {
//                            $colors = explode(',', $category->colors);
//                            if (count($colors) > 0) {
//                                foreach ($colors as $itemColor) {
//                                    if (str_contains(strtolower($productName), strtolower($itemColor))) {
//                                        $productColor = $itemColor;
//                                        break;
//                                    }
//                                }
//                            }
//                        }
//                        if ($categoryId > 0 && !Helper::IsNullOrEmptyString($productColor)) {
//                            break;
//                        }

                    }
                    $order->categoryId = $categoryId;
                    $order->size = $productSize;
                    $order->color = $productColor;
                    if ($product) {
                        $order->productId = $product->id;
                        if (!Helper::IsNullOrEmptyString($product->color)){
                            $order->color = $product->color;
                        }
                    } else if (!Helper::IsNullOrEmptyString($order->itemId) && strlen($productName) && $categoryId > 0) {
                        $product = new Products();
                        $product->itemId = $order->itemId;
                        $product->categoryId = $categoryId;
                        $product->name = $productName;
                        $product->createBy = $userImport->id;
                        $product->save();
                        $order->productId = $product->id;
                    }
                    $order->statusId = 1;
                    $order->trackingStatusId = 0;
                    $order->carrierStatusId = 0;
                    $order->syncStoreStatusId = 0;
                    if ($order->categoryId > 0 && $order->sellerId > 0 && $order->vpsId > 0) {
                        $order->save();
                        $response['status'] = true;
                        $response['message'] = 'Import thành công';
                        $response['data'] = $order;
                    } else {

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
        } catch (Exception $ex) {

            $response['status'] = false;
            $response['message'] = $ex->getMessage();
        }
        return response()->json($response);
    }

    public function orderCSVImport(Request $request)
    {
        $response = ["status" => false, "message" => "", 'data' => null];
        try {
            $validatedData = $request->validate([
                'file' => 'required|mimetypes:text/plain,text/csv,text/tsv,application/vnd.ms-excel,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                'user' => 'required'
            ]);
            $header = $request->header('Authorization');
            if (!$header || strpos($header, 'Bearer ') !== 0) {
                $response['status'] = false;
                $response['message'] = 'Unauthorized.';
                return response()->json($response, 401);
            }
            $accessToken = substr($header, 7);

            if ($accessToken !== env('ACCEPT_TOKEN')) {
                $response['status'] = false;
                $response['message'] = 'Invalid access token.';
                return response()->json($response, 401);
            }
            if (!$validatedData) {
                $response['status'] = false;
                $response['message'] = 'Hãy kiểm tra lại thông tin user và file import.';
                $response['data'] = $validatedData;
                return response()->json($response);
            }

            $userImport = DB::table('users')->where('id', $request->integer('user'))->first();
            if ($userImport && $userImport->id > 0) {
                $file = $request->file('file');
                $csv = Reader::createFromPath($file->getRealPath(), 'r');
                $header = $csv->fetchOne();
                $results = $csv->getRecords();
                $index = 0;
                $numberOrderUpdate = 0;
                $orderNumberError = [];
                if (count($header) >= 103) {
                    $now = Carbon::now();
                    $twoDaysAgo = Carbon::now()->subDays(2);
                    $list_orders = DB::table('orders')->whereBetween('updated_at', [$twoDaysAgo, $now])->get();

                    foreach ($results as $row) {
                        if ($index++ > 0) {
                            if (strlen($row[0]) > 0 && strlen($row[19]) > 0) {
                                $exists_order = false;
                                foreach ($list_orders as $order) {
                                    if ($order->orderNumber == $row[0] && $order->itemId == $row[63]) {
                                        $exists_order = true;
                                        break;
                                    }
                                }
                                //DB::table('orders')->where('orderNumber', $row[0])->exists()
                                if ($exists_order) {
                                    array_push($orderNumberError, ['orderNumber' =>  $row[0], 'message' => 'Đơn hàng đã tồn tại trong hệ thống, '.$row[63]]);
                                } else {
                                    $seller = DB::table('seller')->where('sellerName', $row[19])->first();
                                    if ($seller && $seller->id > 0) {
                                        $order = new Orders();
                                        $order->orderNumber = $row[0];
                                        $order->sellerId = $seller->id;
                                        $order->createBy = $userImport->id;
                                        if ($seller->userId) {
                                            $vps = Vps::where('userId', $seller->userId)->first();
                                            if ($vps) {
                                                $order->vpsId = $vps->id;
                                            }
                                        }
                                        if (strlen($row[3]) > 0) {
                                            $order->created_at = strtotime($row[3]);
                                        }
                                        if (strlen($row[1]) > 0) {
                                            $order->channelId = $row[1];
                                        }
                                        if (strlen($row[27]) > 0) {
                                            $order->shipToAddressID = $row[27];
                                        }

                                        if (strlen($row[28]) > 0) {
                                            $firstName = $row[28];
                                            $lastName = '';
                                            if (str_contains($row[28], ' ')) {
                                                $nameParts = explode(' ', $row[28]);
                                                $firstName = $nameParts[0];
                                                $lastName = implode(' ', array_slice($nameParts, 1));
                                            }
                                            $order->shipToFirstName = $firstName;
                                            $order->shipToLastName = $lastName;
                                        }
                                        if (strlen($row[29]) > 0) {
                                            $order->shipToAddressPhone = $row[29];
                                        }
                                        if (strlen($row[30]) > 0) {
                                            $order->shipToAddressLine1 = $row[30];
                                        }
                                        if (strlen($row[31]) > 0) {
                                            $order->shipToAddressLine2 = $row[31];
                                        }
                                        if (strlen($row[32]) > 0) {
                                            $order->shipToAddressCity = $row[32];
                                        }
                                        if (strlen($row[33]) > 0) {
                                            $order->shipToAddressCounty = $row[33];
                                        }
                                        if (strlen($row[34]) > 0) {
                                            $order->shipToAddressStateOrProvince = $row[34];
                                        }
                                        if (strlen($row[35]) > 0) {
                                            $order->shipToAddressPostalCode = $row[35];
                                        }
                                        if (strlen($row[36]) > 0) {
                                            $order->shipToAddressCountry = $row[36];
                                        }
                                        $product = null;
                                        if (strlen($row[63]) > 0) {
                                            $order->itemId = $row[63];
                                            $product = DB::table('products')->where('itemId', $order->itemId)->first();
                                        }
                                        $productName = '';
                                        $productSize = '';
                                        $productColor = '';
                                        $inputString = '';
                                        if (strlen($row[65]) > 0) {
                                            $inputString = $row[65];
                                            $regex = "/(.*)\[(.*?)\](.*)/";

                                            if (preg_match($regex, $inputString, $matches)) {
                                                if (strlen($matches[1])) {
                                                    $productName = $matches[1];
                                                }
                                                if (strlen($matches[2])) {
                                                    $productSize = $matches[2];
                                                }
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
//                                            if (!Helper::IsNullOrEmptyString($category->colors)) {
//                                                $colors = explode(',', $category->colors);
//                                                if (count($colors) > 0) {
//                                                    foreach ($colors as $itemColor) {
//                                                        if (str_contains(strtolower($productName), strtolower($itemColor))) {
//                                                            $productColor = $itemColor;
//                                                            break;
//                                                        }
//                                                    }
//                                                }
//                                            }
//                                            if ($categoryId > 0 && !Helper::IsNullOrEmptyString($productColor)) {
//                                                break;
//                                            }

                                        }
                                        $order->categoryId = $categoryId;
                                        $order->size = $productSize;
                                        $order->color = $productColor;
                                        if ($product) {
                                            $order->productId = $product->id;
                                            if (!Helper::IsNullOrEmptyString($product->color)){
                                                $order->color = $product->color;
                                            }
                                        } else if (!Helper::IsNullOrEmptyString($order->itemId) && strlen($productName) && $categoryId > 0) {
                                            $product = new Products();
                                            $product->itemId = $order->itemId;
                                            $product->categoryId = $categoryId;
                                            $product->name = $productName;
                                            $product->createBy = $userImport->id;
                                            $product->save();
                                            $order->productId = $product->id;
                                        }
                                        if (strlen($row[64]) > 0) {
                                            $order->sku = $row[64];
                                        }
                                        if (strlen($row[66]) > 0) {
                                            $order->quantity = (int)$row[66];
                                        } else {
                                            $order->quantity = 0;
                                        }

                                        if (strlen($row[67]) > 0) {
                                            $order->price = (double)$row[67];
                                        } else {
                                            $order->price = 0;
                                        }

                                        if (strlen($row[69]) > 0) {
                                            try {
                                                $priceData = json_decode($row[69]);
                                                $order->ship = (double)$priceData[1]->amount;
                                            } catch (\Exception $e) {

                                            }
                                        } else {
                                            $order->ship = 0;
                                        }

                                        if (strlen($row[70]) > 0) {
                                            $order->cost = (double)$row[70];
                                        } else {
                                            $order->cost = 0;
                                        }
                                        if (strlen($row[92]) > 0) {
                                            $order->note = $row[92];
                                        }
                                        $order->statusId = 1;
                                        $order->trackingStatusId = 0;
                                        $order->carrierStatusId = 0;
                                        $order->syncStoreStatusId = 0;
                                        if ($order->categoryId > 0 && $order->sellerId > 0 && $order->vpsId > 0) {
                                            $order->save();
                                            $numberOrderUpdate += 1;
                                        } else {
                                            if ($order->categoryId == 0){
                                                array_push($orderNumberError, ['orderNumber' =>  $row[0], 'message' => 'Không nhận diện được đợn hàng thuộc chuyên mục sản phẩm nào.']);
                                            }else {
                                                array_push($orderNumberError, ['orderNumber' =>  $row[0], 'message' => 'Seller: '.$row[19].' không nhận diện được Seller hoặc VPS trong hệ thống.']);
                                            }
                                        }
                                    } else {
                                        array_push($orderNumberError, ['orderNumber' =>  $row[0], 'message' => 'Seller: '.$row[19].' không tìm thấy trong hệ thống.']);
                                    }
                                }
                            }
                        }
                    };
                }
                if ($numberOrderUpdate > 0) {
                    $response['status'] = true;
                    $response['message'] = 'Đã thêm ' . $numberOrderUpdate . ' đơn hàng';
                    if (count($orderNumberError) > 0) {
                        $response['data'] = $orderNumberError;
                    }
                } else {
                    $response['status'] = false;
                    $response['message'] = 'Đã có lỗi trong nội dung nên không thêm được.';
                    if (count($orderNumberError) > 0) {
                        $response['data'] = $orderNumberError;
                    }
                }
            } else {
                $response['status'] = false;
                $response['message'] = 'user không tồn tại.';
            }

        } catch (\Exception $ex) {

            $response['status'] = false;
            $response['message'] = $ex->getMessage();
        }
        return response()->json($response);
    }
}
