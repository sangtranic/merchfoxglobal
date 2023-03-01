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
use const http\Client\Curl\AUTH_ANY;

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
        $orders = Orders::paginate(2);
        $sellers = Seller::all();
        $counter = Orders::count();
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
        $showProducts = null;
        $vpses = Vps::where('id',$filter_vps)->get();
        if (!($orders->isEmpty())) {
            $productIds = $orders->pluck('productId')->toArray();
            $showProducts = Products::whereIn('id', $productIds)->get();
        }
        return view('orders.index', [
            'orders' => $orders,
            'users'=> $users,
            'vpses'=> $vpses,
            'sellers'=>$sellers,
            'productCates' => $productCates,
            'showProducts'=>$showProducts,
            'counter'=>$counter,
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
    public function indexPost(Request $request){
        dump($request);
    }
    public function search(Request $request)
    {
        $productCates = Productcategories::all();
        $users = Users::all();
        $vpses = Vps::all();
        $sellers =null;
        if (Auth::user()->role == 'admin'){
            $sellers = Seller::all();
        }else{
            $sellers = Seller::where('userId', Auth::id())->get();
        }
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
        $counter = $query->count();
        $orders = $query->paginate(2);

        $showProducts = [];
        if (!($orders->isEmpty())) {
            $productIds = $orders->pluck('productId')->toArray();
            $showProducts = Products::whereIn('id', $productIds)->get();
        }
        return view('orders.index', [
            'orders' => $orders,
            'users'=> $users,
            'vpses'=> $vpses,
            'sellers'=>$sellers,
            'counter'=>$counter,
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
                $product->imageDesign1 = $product->url_img_design1;
                $product->imageDesign2 = $product->url_img_design2;
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
        if ($order->productId == 0){
            if ($request->has('productName') &&  Helper::IsNullOrEmptyString($request->input('productName'))){
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
        }else {
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
        if ($SubmitButton == 'Save'){
            return back()->with('status', 'Successfully');
        }else{
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
        if ($order->productId == 0){
            if ($request->has('productName') &&  Helper::IsNullOrEmptyString($request->input('productName'))){
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
        }else {
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
        if ($orders){
            if (Auth::id() != $orders->createBy && strtolower(Auth::user()->role) == 'user'){
                return back()->with('status', 'Error')->with('message','Bạn không có quyền xóa đơn hàng này.');
            }
        }
        $orders->delete();
        return back()->with('status', 'Successfully')->with('message','Xóa đơn hàng thành công.');
    }
}
