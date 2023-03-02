<?php

namespace App\Http\Controllers;

use App\Helper\FileUploadHelper;
use App\Helper\Helper;
use App\Models\Objectstatus;
use App\Models\Orders;
use App\Models\Productcategories;
use App\Models\Products;
use App\Models\Seller;
use App\Models\Users;
use App\Models\Vps;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use League\Csv\Reader;
use Illuminate\Http\Response;
use League\Csv\Writer;
use const http\Client\Curl\AUTH_ANY;

class OrdersController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $model = $this->getIndexModel();
        return view('orders.index', $model);
    }

    public function indexPost(Request $request)
    {
        dump($request);
    }

    public function search(Request $request)
    {
        $model = $this->getIndexModel($request);
        return view('orders.index', $model);
    }

    private function getIndexModel(Request $request = null)
    {

        $productCates = Productcategories::all();
        $users = Users::all();
        $vpses = null;
        $sellers = null;
        $query = Orders::query();
        $products = null;
        $filter_dateFrom = '';
        $filter_dateTo = '';
        $filter_productCateId = 0;
        $filter_seller = 0;
        $filter_vps = 0;
        $filter_orderNumber = '';
        $filter_product = '';
        $filter_keyword = '';
        $filter_customer = '';
        $filter_trackStatusId = 0;
        $filter_carrieStatusId = 0;
        $filter_orderId = 0;
        $filter_syncStoreStatusId =0;
        $filter_isFB = 0;
        $filter_sellerIds = [];
        $filter_vpsIds = [];
        if (Auth::user()->role == 'admin') {
            $sellers = Seller::all();
            $vpses = Vps::all();
        } else {
            $sellers = Seller::where('userId', Auth::id())->get();
            $vpses = Vps::where('userId', Auth::id())->get();
            $filter_sellerIds = $sellers->pluck('id')->toArray();
            $filter_vpsIds = $vpses->pluck('id')->toArray();
        }
        if ($request != null) {
            if ($request->input('productCate')) {
                $filter_productCateId = $request->integer('productCate');
            }
            if ($request->input('dateFrom')) {
                $filter_dateFrom = $request->input('dateFrom');
            }
            if ($request->input('dateTo')) {
                $filter_dateTo = $request->input('dateTo');
            }
            if ($request->input('vps')) {
                $filter_vps = $request->integer('vps');
            }
            if ($request->input('seller')) {
                $filter_seller = $request->integer('seller');
            }
            if ($request->input('orderNumber')) {
                $filter_orderNumber = $request->input('orderNumber');
            }
            if ($request->input('product')) {
                $filter_product = $request->integer('product');
            }
            if ($request->input('keyword')) {
                $filter_keyword = $request->input('keyword');
            }
            if ($request->input('customer')) {
                $filter_customer = $request->input('customer');
            }
            if ($request->input('trackingStatus')) {
                $filter_trackStatusId = $request->integer('trackingStatus');
            }
            if ($request->input('carrieStatus')) {
                $filter_carrieStatusId = $request->integer('carrieStatus');
            }
            if ($request->input('syncStoreStatus')) {
                $filter_syncStoreStatusId = $request->integer('syncStoreStatus');
            }
            if ($request->input('isFB')) {
                $filter_isFB = $request->integer('isFB');
            }
            if ($request->input('orderId')) {
                $filter_orderId = $request->integer('orderId');
            }
        }
        if ($filter_dateFrom && $filter_dateTo) {
            $query->whereBetween('created_at', [$filter_dateFrom, $filter_dateTo]);
        } else if ($filter_dateFrom) {
            $query->whereDate('created_at', '>=', $filter_dateFrom);
        } else if ($filter_dateTo) {
            $query->whereDate('created_at', '<=', $filter_dateTo);
        }
        if ($filter_productCateId > 0) {
            $query->where('categoryId', $filter_productCateId);
        }
        if ($filter_seller == 0 && count($filter_sellerIds) > 0) {
            $query->whereIn('sellerId', $filter_sellerIds);
        } else if ($filter_seller > 0) {
            $query->where('sellerId', $filter_seller);
        }

        if ($filter_vps == 0 && count($filter_vpsIds) > 0) {
            $query->whereIn('vpsId', $filter_vpsIds);
        } else if ($filter_vps > 0) {
            $query->where('vpsId', $filter_vps);
        }
        if (strlen($filter_orderNumber)) {
            $query->where('orderNumber', 'like', '%' . $filter_orderNumber . '%');
        }
        if ($filter_product > 0) {
            $query->where('productId', $filter_product);
        }
        if (strlen($filter_keyword)) {
            $query->where(function ($_query) use ($filter_keyword) {
                $_query->where('fulfillCode', 'like', '%' . $filter_keyword . '%')
                    ->orWhere('trackingCode', 'like', '%' . $filter_keyword . '%')
                    ->orWhere('carrier', 'like', '%' . $filter_keyword . '%');
            });
        }

        if (strlen($filter_customer)) {
            $query->where(function ($_query) use ($filter_customer) {
                $_query->where('shipToAddressID', 'like', '%' . $filter_customer . '%')
                    ->orWhere('shipToAddressName', 'like', '%' . $filter_customer . '%')
                    ->orWhere('shipToAddressPhone', 'like', '%' . $filter_customer . '%')
                    ->orWhere('shipToAddressLine1', 'like', '%' . $filter_customer . '%')
                    ->orWhere('shipToAddressLine2', 'like', '%' . $filter_customer . '%')
                    ->orWhere('shipToAddressCity', 'like', '%' . $filter_customer . '%')
                    ->orWhere('shipToAddressCounty', 'like', '%' . $filter_customer . '%')
                    ->orWhere('shipToAddressStateOrProvince', 'like', '%' . $filter_customer . '%')
                    ->orWhere('shipToAddressPostalCode', 'like', '%' . $filter_customer . '%')
                    ->orWhere('shipToAddressCountry', 'like', '%' . $filter_customer . '%');
            });
        }
        if ($filter_trackStatusId > 0) {
            $query->where('trackingStatusId', $filter_trackStatusId == 2 ? 0 : $filter_trackStatusId);
        }
        if ($filter_carrieStatusId > 0) {
            $query->where('carrierStatusId', $filter_carrieStatusId == 2 ? 0 : $filter_carrieStatusId);
        }
        if ($filter_syncStoreStatusId > 0) {
            $query->where('syncStoreStatusId', $filter_syncStoreStatusId == 2 ? 0 : $filter_syncStoreStatusId);
        }
        if ($filter_isFB > 0) {
            $query->where('isFB', $filter_isFB == 2 ? 0 : $filter_isFB);
        }


        $counter = $query->count();
        $orders = $query->paginate(2);

        $showProducts = [];
        if (!($orders->isEmpty())) {
            $productIds = $orders->pluck('productId')->toArray();
            $showProducts = Products::whereIn('id', $productIds)->get();
        }
        return [
            'orders' => $orders,
            'users' => $users,
            'vpses' => $vpses,
            'sellers' => $sellers,
            'counter' => $counter,
            'productCates' => $productCates,
            'showProducts' => $showProducts,
            'dateFrom' => $filter_dateFrom,
            'dateTo' => $filter_dateTo,
            'productCate' => $filter_productCateId,
            'seller' => $filter_seller,
            'vps' => $filter_vps,
            'orderNumber' => $filter_orderNumber,
            'product' => $filter_product,
            'keyword'=>$filter_keyword,
            'customer' => $filter_customer,
            'track' => $filter_trackStatusId,
            'carrie' => $filter_carrieStatusId,
            'orderId' => $filter_orderId,
            'ebay' => $filter_syncStoreStatusId,
            'isFB'=>$filter_isFB
        ];
    }


    public function editForm(Request $request)
    {
        $productCates = Productcategories::all();
        $vpses = null;
        $sellers = null;
        $product = null;
        $statusList = Objectstatus::where('tableName', 'products')->get();
        $order = new Orders();
        $productCategory = null;
        $productCate = 0;
        $productSizes = [];
        $productColors = [];
        $id = 0;
        if ($request->input('productCate')) {
            $productCate = $request->integer('productCate');
        }
        if ($productCate == 0) {
            $productCategory = $productCates->first();
            $productCate = $productCategory->id;
        } else {
            $productCategory = $productCates->where('id', $productCate)->first();
        }
        if ($productCategory) {
            $productSizes = $productCategory->size_list;
            $productColors = $productCategory->color_list;
        }
        if ($request->input('id')) {
            $id = $request->integer('id');
        }
        if ($id > 0) {
            $order = Orders::findOrFail($id);
            if ($order == null) {
                $order = new Orders();
            } else {
                $product = Products::findOrFail($order->productId);
                $product->imageDesign1 = $product->url_img_design1;
                $product->imageDesign2 = $product->url_img_design2;
                $vpses = Vps::where('userId', $order->userId)->get();
                $sellers = Seller::where('userId', $order->userId)->get();
            }
        } else {
            $order->userId = Auth::id();
            $order->categoryId = $productCate;
            $order->statusId = $statusList->first()->statusId;
        }
        if ($vpses == null) {
            $vpses = Vps::where('userId', Auth::id())->get();
        }
        if ($sellers == null) {
            $sellers = Seller::where('userId', Auth::id())->get();
        }
        return view('orders.editForm', [
            'order' => $order,
            'vpses' => $vpses,
            'sellers' => $sellers,
            'productCates' => $productCates,
            'product' => $product,
            'productSizes' => $productSizes,
            'productColors' => $productColors,
            'statusList' => $statusList
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
        if ($order->productId == 0) {
            if ($request->has('productName') && Helper::IsNullOrEmptyString($request->input('productName'))) {
                $product = new Products($request->all());
                $product->name = $request->input('productName');
                if ($request->has('imageDesignOne')) {
                    $product->urlImageDesignOne = FileUploadHelper::saveImage($request->file('imageDesignOne'));
                }
                if ($request->has('imageDesignTwo')) {
                    $product->urlImageDesignTwo = FileUploadHelper::saveImage($request->file('imageDesignTwo'));
                }
                $product->createBy = Auth::id();
                $product->isFileDesign = $request->has('isFileDesign') ? 1 : 0;
                $product->save();
                $order->productId = $product->id;
            }
        } else {
            $new_product = Products::findOrFail($order->productId);
            if ($request->has('itemId')) {
                $new_product->itemId = $request->input('itemId');
            }
            if ($request->has('urlImagePreviewOne')) {
                $new_product->urlImagePreviewOne = $request->input('urlImagePreviewOne');
            }
            if ($request->has('urlImagePreviewTwo')) {
                $new_product->urlImagePreviewOne = $request->input('urlImagePreviewTwo');
            }
            $new_product->updateBy = Auth::id();
            if ($request->has('imageDesignOne')) {
                $new_product->urlImageDesignOne = FileUploadHelper::saveImage($request->file('imageDesignOne'));
            }
            if ($request->has('imageDesignTwo')) {
                $new_product->urlImageDesignTwo = FileUploadHelper::saveImage($request->file('imageDesignTwo'));
            }
            $new_product->isFileDesign = $request->has('isFileDesign') ? 1 : 0;
            $new_product->save();
        }
        $order->isFB = $request->has('isFB') ? 1 : 0;
        $order->createBy = Auth::id();
        $order->fulfillStatusId = Helper::IsNullOrEmptyString($order->fulfillCode) ? 0 : 1;
        $order->trackingStatusId = Helper::IsNullOrEmptyString($order->trackingCode) ? 0 : 1;
        $order->carrierStatusId = Helper::IsNullOrEmptyString($order->carrier) ? 0 : 1;
        $order->save();
        $SubmitButton = $request->input('SubmitButton');
        if ($SubmitButton == 'Save') {
            return back()->with('status', 'Successfully');
        } else {
            return redirect()->route('orders.search', ['productCate' => $order->categoryId]);
        }
    }

    public function update(Request $request, $id)
    {

        $request->validate([
            'categoryId' => 'required',
            'sellerId' => 'required',
            'userId' => 'required'
        ]);
        $order = Orders::findOrFail($id);
        $order->update($request->all());
        if ($order->productId == 0) {
            if ($request->has('productName') && Helper::IsNullOrEmptyString($request->input('productName'))) {
                $product = new Products($request->all());
                $product->name = $request->input('productName');
                if ($request->has('imageDesignOne')) {
                    $product->urlImageDesignOne = FileUploadHelper::saveImage($request->file('imageDesignOne'));
                }
                if ($request->has('imageDesignTwo')) {
                    $product->urlImageDesignTwo = FileUploadHelper::saveImage($request->file('imageDesignTwo'));
                }
                $product->createBy = Auth::id();
                $product->isFileDesign = $request->has('isFileDesign') ? 1 : 0;
                $product->save();
                $order->productId = $product->id;
            }
        } else {
            $new_product = Products::findOrFail($order->productId);
            $new_product->update($request->all());
            $new_product->updateBy = Auth::id();
            if ($request->has('imageDesignOne')) {
                $new_product->urlImageDesignOne = FileUploadHelper::saveImage($request->file('imageDesignOne'));
            }
            if ($request->has('imageDesignTwo')) {
                $new_product->urlImageDesignTwo = FileUploadHelper::saveImage($request->file('imageDesignTwo'));
            }
            $new_product->isFileDesign = $request->has('isFileDesign') ? 1 : 0;
            $new_product->save();
        }
        $order->updateBy = Auth::id();
        $order->isFB = $request->has('isFB') ? 1 : 0;
        $order->fulfillStatusId = Helper::IsNullOrEmptyString($order->fulfillCode) ? 0 : 1;
        $order->trackingStatusId = Helper::IsNullOrEmptyString($order->trackingCode) ? 0 : 1;
        $order->carrierStatusId = Helper::IsNullOrEmptyString($order->carrier) ? 0 : 1;
        $order->save();
        return back()->with('status', 'Successfully');
    }

    public function destroy($id)
    {
        $orders = Orders::findOrFail($id);
        if ($orders) {
            if (Auth::id() != $orders->createBy && strtolower(Auth::user()->role) == 'user') {
                return back()->with('status', 'Error')->with('message', 'Bạn không có quyền xóa đơn hàng này.');
            }
        }
        $orders->delete();
        return back()->with('status', 'Successfully')->with('message', 'Xóa đơn hàng thành công.');
    }

    //
    public function exportCSV(Request $request)
    {
        $model = $this->getIndexModel($request);
        //dump($model);
        $data = [
            ['order_number', 'fullfi_number', 'track_code', 'carrier', 'update_ebay', 'note']
        ];
        $listOrderPluck = $model['orders']->map(function ($user) {
            return collect($user->toArray())
                ->only(['orderNumber', 'fulfillCode', 'trackingCode', 'carrier', 'syncStoreStatusId', 'note'])
                ->all();
        });
        foreach ($listOrderPluck as $row) {
            $row['syncStoreStatusId'] = $row['syncStoreStatusId'] == 1 ? "yes" : "no";
            array_push($data, $row);
        }

        // Create a new CSV writer
        $csv = Writer::createFromFileObject(new \SplTempFileObject());
        // Insert the data into the CSV
        $csv->insertAll($data);

        return response((string)$csv, 200, [
            'Content-Type' => 'text/plain; charset=UTF-8',
            'Content-Encoding' => 'UTF-8',
            'Content-Transfer-Encoding' => 'binary',
            'Content-Disposition' => 'attachment; filename="proposed_file_name.csv"',
        ]);
    }
    public function exportUpToEbay(Request $request)
    {
        $model = $this->getIndexModel($request);
        //dump($model);
        $data = [
            ['Order ID', 'Line Item ID', 'Logistics Status', 'Shipment Carrier', 'Shipment Tracking', 'Remove this column']
        ];
        $listOrderPluck = $model['orders']->map(function ($user) {
            return collect($user->toArray())
                ->only(['orderNumber', 'itemId', 'carrier', 'trackingCode'])
                ->all();
        });
        foreach ($listOrderPluck as $row) {
            $row['syncStoreStatusId'] = $row['syncStoreStatusId'] == 1 ? "yes" : "no";
            array_push($data, [$row['orderNumber'], $row['itemId'], '', $row['carrier'], $row['trackingCode'],'' ]);
        }

        // Create a new CSV writer
        $csv = Writer::createFromFileObject(new \SplTempFileObject());
        // Insert the data into the CSV
        $csv->insertAll($data);

        return response((string)$csv, 200, [
            'Content-Type' => 'text/plain; charset=UTF-8',
            'Content-Encoding' => 'UTF-8',
            'Content-Transfer-Encoding' => 'binary',
            'Content-Disposition' => 'attachment; filename="proposed_file_name.csv"',
        ]);
    }

    public function exportToCsv()
    {
        //$dateFrom, $dateTo, $productCate, $userId, $vps, $orderNumber, $productName, $keyword,
        //                                       $customer, $slTrack, $slVps, $slEbayStatus
        // Array data to export
        $query = Orders::query();
        $filter_dateFrom = request('dateFrom') ? request('dateFrom') : "";
        $filter_dateTo = request('dateTo') ? request('dateTo') : "";
        $filter_productCateId = request('productCate') ? request('productCate') : 0;
        $filter_user = request('userId') ? request('userId') : 0;
        $filter_vps = request('vps') ? request('vps') : 0;
        $filter_orderNumber = request('orderNumber') ? request('orderNumber') : '';
        $filter_product = request('productName') ? request('productName') : '';
        $filter_customer = request('customer') ? request('customer') : '';
        $filter_track = request('slTrack') ? request('slTrack') : 0;
        //$filter_carrie = request('dateTo')?request('dateTo'):0;
        $filter_orderid = 0;
        $filter_ebay = request('slEbayStatus') ? request('slEbayStatus') : 0;
        $statusFilter = request('status');
        $data = [
            ['order_number', 'fullfi_number', 'track_code', 'carrier', 'update_ebay', 'note']
        ];
        if (request('productCate')) {
            $filter_productCateId = request('productCate');
        }

        if ($filter_dateFrom && $filter_dateTo) {
            $query->whereBetween('created_at', [$filter_dateFrom, $filter_dateTo]);
        } else if ($filter_dateFrom) {
            $query->whereDate('created_at', '>=', $filter_dateFrom);
        } else if ($filter_dateTo) {
            $query->whereDate('created_at', '<=', $filter_dateTo);
        }
        $counter = $query->count();
        $orders = $query->get();
        $listOrderPluck = $orders->pluck(['orderNumber', 'fulfillCode', 'trackingCode', 'carrier', 'syncStoreStatusId', 'note']);
        foreach ($listOrderPluck as $row) {
            $row->syncStoreStatusId = $row->syncStoreStatusId == 1 ? "yes" : "no";
            array_push($data, $row);
        }
        // Create a new CSV writer
        $csv = Writer::createFromFileObject(new \SplTempFileObject());

        // Insert the data into the CSV
        $csv->insertAll($data);

        // Set the response headers
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="proposed_file_name.csv"',
        ];

        // Create the HTTP response with the CSV file
        $response = new Response($csv->__toString(), 200, $headers);

        return $response;
    }

    public function importCsv(Request $request)
    {
        $request->validate([
            //'file' => 'required|mimes:xlsx,xls,csv,ods'
            'file' => 'required|mimetypes:text/plain,text/csv,text/tsv,application/vnd.ms-excel,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
        ]);

        $file = $request->file('file');
        $csv = Reader::createFromPath($file->getRealPath(), 'r');
        $header = $csv->fetchOne();
        $results = $csv->getRecords();
        $index = 0;
        $numberOrderUpdate = 0;
        $orderNumberError = [];
        if (count($header) == 6) {

            foreach ($results as $row) {
                if ($index++ > 0) {
                    $order = Orders::where('orderNumber', $row[0])->get()->first();
                    if ($order) {
                        $order->fulfillCode = $row[1];
                        $order->fulfillStatusId = Helper::IsNullOrEmptyString($order->fulfillCode) ? 0 : 1;
                        $order->trackingCode = $row[2];
                        $order->trackingStatusId = Helper::IsNullOrEmptyString($order->trackingCode) ? 0 : 1;
                        $order->carrier = $row[3];
                        $order->carrierStatusId = Helper::IsNullOrEmptyString($order->carrier) ? 0 : 1;
                        $order->syncStoreStatusId = !Helper::IsNullOrEmptyString($row[4]) && strpos($row[4], 'yes') ? 1 : 0;
                        $order->note = $row[5];
                        $order->save();
                        $numberOrderUpdate++;
                    } else {
                        array_push($orderNumberError, $row[0]);
                    }
                }
            };
        }
        return back()->with('status', 'Successfully')->with('message', 'Cập nhật ' . $numberOrderUpdate . ' đơn hàng');
    }
}
