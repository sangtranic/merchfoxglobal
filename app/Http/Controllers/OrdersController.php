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
use Illuminate\View\View;
use League\Csv\Reader;
use Illuminate\Http\Response;
use League\Csv\Writer;
use const http\Client\Curl\AUTH_ANY;

class OrdersController extends Controller
{
    public $ROWAMOUNT = 20;

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

    private function getIndexModel(Request $request = null, $isAll = false)
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
        $filter_syncStoreStatusId = 0;
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
            $query->where('trackingStatusId',"=", $filter_trackStatusId == 2 ? 0 : $filter_trackStatusId);
        }
        if ($filter_carrieStatusId > 0) {
            $query->where('carrierStatusId',"=", $filter_carrieStatusId == 2 ? 0 : $filter_carrieStatusId);
        }
        if ($filter_syncStoreStatusId > 0) {
            $query->where('syncStoreStatusId',"=", $filter_syncStoreStatusId == 2 ? 0 : $filter_syncStoreStatusId);
        }
        if ($filter_isFB > 0) {
            $query->where('isFB',"=", $filter_isFB == 2 ? 0 : $filter_isFB);
        }


        $counter = $query->count();

        if ($isAll){
            $orders = $query->get();
        }else{
            $orders = $query->paginate($this->ROWAMOUNT);
        }

        $productSelect = null;
        $showProducts = [];
        if (!($orders->isEmpty())) {
            $productIds = $orders->pluck('productId')->toArray();
            $showProducts = Products::whereIn('id', $productIds)->get();
            if ($filter_product > 0){
                $productSelect = $showProducts->where('id', $filter_product)->first();
            }
        }
        return [
            'orders' => $orders,
            'users' => $users,
            'vpses' => $vpses,
            'sellers' => $sellers,
            'counter' => $counter,
            'productCates' => $productCates,
            'showProducts' => $showProducts,
            'productSelect' => $productSelect,
            'dateFrom' => $filter_dateFrom,
            'dateTo' => $filter_dateTo,
            'productCate' => $filter_productCateId,
            'seller' => $filter_seller,
            'vps' => $filter_vps,
            'orderNumber' => $filter_orderNumber,
            'product' => $filter_product,
            'keyword' => $filter_keyword,
            'customer' => $filter_customer,
            'track' => $filter_trackStatusId,
            'carrie' => $filter_carrieStatusId,
            'orderId' => $filter_orderId,
            'ebay' => $filter_syncStoreStatusId,
            'isFB' => $filter_isFB
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
        $layout_name = 'layouts.app';
        $callBack = '';
        if ($request->has('callBack')) {
            $callBack = $request->input('callBack');
        }
        $id = 0;
        if ($request->has('layout')) {
            $layout_name = $request->input('layout');
        }
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
                $vpsItem = Vps::findOrFail($order->vpsId);
                if ($order->productId && $order->productId > 0){

                    $product = Products::findOrFail($order->productId);
                    $product->imageDesign1 = $product->url_img_design1;
                    $product->imageDesign2 = $product->url_img_design2;
                }
                if ($vpsItem){
                    $vpses = Vps::where('userId', $vpsItem->userId)->get();
                    $sellers = Seller::where('userId', $vpsItem->userId)->get();
                }
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
            'layoutName' => $layout_name,
            'order' => $order,
            'vpses' => $vpses,
            'sellers' => $sellers,
            'productCates' => $productCates,
            'product' => $product,
            'productSizes' => $productSizes,
            'productColors' => $productColors,
            'statusList' => $statusList,
            'callBack' => $callBack
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'categoryId' => 'required',
            'sellerId' => 'required',
            'vpsId' => 'required',
            'orderNumber' => 'required|unique:orders,orderNumber'
        ],['orderNumber.unique' => 'Đơn hàng đã tồn tại']);
        $order = new Orders($request->all());
        if (Orders::where('orderNumber',$request->input('orderNumber'))->get()->count() > 0){
            return back()->withErrors(["Đơn hàng ".$request->input('orderNumber').' đã tồn tại.']);
        }
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
        $order->syncStoreStatusId = 0;
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
            'vpsId' => 'required',
            'orderNumber' => 'required'
        ]);
        $order = Orders::findOrFail($id);
        if ($request->has('orderNumber')){
            $order->orderNumber = $request->input('orderNumber');
        }
        if ($request->has('vpsId')){
            $order->vpsId = $request->integer('vpsId');
        }
        if ($request->has('sellerId')){
            $order->sellerId = $request->integer('sellerId');
        }
        if ($request->has('shipToAddressID')){
            $order->shipToAddressID = $request->input('shipToAddressID');
        }
        if ($request->has('shipToFirstName')){
            $order->shipToFirstName = $request->input('shipToFirstName');
        }
        if ($request->has('shipToLastName')){
            $order->shipToLastName = $request->input('shipToLastName');
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
        if ($request->has('statusId')){
            $order->statusId = $request->integer('statusId');
        }
        if ($request->has('note')){
            $order->note = $request->input('note');
        }
        if ($request->has('categoryId')){
            $order->categoryId = $request->integer('categoryId');
        }
        if ($request->has('productId')){
            $order->productId = $request->integer('productId');
        }
        if ($request->has('sku')){
            $order->sku = $request->input('sku');
        }
        if ($request->has('size')){
            $order->size = $request->input('size');
        }
        if ($request->has('color')){
            $order->color = $request->input('color');
        }

        if ($request->has('quantity')){
            $order->quantity = $request->integer('quantity');
        }

        if ($request->has('price')){
            $order->price = $request->input('price');
        }
        if ($request->has('ship')){
            $order->ship = $request->input('ship');
        }

        if ($request->has('cost')){
            $order->cost = $request->input('cost');
        }

        if ($request->has('profit')){
            $order->profit = $request->input('profit');
        }

        if ($request->has('fulfillCode')){
            $order->fulfillCode = $request->input('fulfillCode');
        }

        if ($request->has('trackingCode')){
            $order->trackingCode = $request->input('trackingCode');
        }

        if ($request->has('carrier')){
            $order->carrier = $request->input('carrier');
        }

        if ($request->has('itemId')){
            $order->itemId = $request->input('itemId');
        }
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
            //$new_product->update($request->all());
            $new_product->itemId = $request->input('itemId');
            $new_product->description = $request->input('description');
            $new_product->url = $request->input('url');
            $new_product->urlImagePreviewOne = $request->input('urlImagePreviewOne');
            $new_product->urlImagePreviewTwo = $request->input('urlImagePreviewTwo');
            $new_product->isFileDesign = $request->has('isFileDesign') ? 1 : 0;
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
        return back()->with('statusUpdate', 'Successfully')->with('orderId', $order->id);
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

    public function orderRow(Request $request)
    {
        $index = 1;
        if ($request->has('index')){
            $index = $request->integer('index');
        }
        if ($request->has('id')){
            $id = $request->integer('id');
            $order = Orders::findOrFail($id);
            $product = null;
            if($order->productId > 0){
                $product = Products::findOrFail($order->productId);
                $product->imageDesign1 = $product->url_img_design1;
                $product->imageDesign2 = $product->url_img_design2;
            }
            $users = Users::all();
            $vps = Vps::where('id', $order->vpsId)->first();
            $seller = Seller::where('id', $order->sellerId)->first();
            $userCr = $users->where('id', $order->createBy)->first();
            $userUp = $users->where('id', $order->updateBy)->first();
            $cate = Productcategories::where('id', $order->categoryId)->first();
            return view('orders.orderrow', [
                'order' => $order,
                'product'=>$product,
                'index' => $index,
                'userCr' => $userCr,
                'userUp' => $userUp,
                'cate' => $cate,
                'vps' => $vps,
                'seller' => $seller
            ]);
        }
        return '';
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
        $model = $this->getIndexModel($request, true);
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
            array_push($data, [$row['orderNumber'], $row['itemId'], '', $row['carrier'], $row['trackingCode'],'' ]);
        }
        DB::table('orders')-> whereIn('id', $model['orders']-> pluck('id'))->update(['syncStoreStatusId'=>1]);
        // Create a new CSV writer
        $csv = Writer::createFromFileObject(new \SplTempFileObject());
        // Insert the data into the CSV
        $csv->insertAll($data);

        return response((string)$csv, 200, [
            'Content-Type' => 'text/plain; charset=UTF-8',
            'Content-Encoding' => 'UTF-8',
            'Content-Transfer-Encoding' => 'binary',
            'Content-Disposition' => 'attachment; filename="order-fulfilment-'. Carbon::parse(Carbon::now())->timezone('Asia/Ho_Chi_Minh')->format('dmY-Hi').'.csv"',
        ]);
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
