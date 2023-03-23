@extends('layouts.app')
@section('title', 'Danh sách đơn hàng')
@section('head')
    <link rel="stylesheet" href="{{asset('plugins/select2/css/select2.min.css')}}">
@endsection
@section('content')
    <?php use App\Helper\Helper;$index = 0;?>
    <div class="content-wrapper">
        <!-- Main content -->
        <section class="content mt-2">
            <!-- Default box -->
            <div class="card">

                @if(session('status') && session('message'))
                    <div class="alert {{session('status') == 'Error' ? 'alert-danger':'alert-success'}}">
                        {{session('message')}}
                    </div>
                @endif

                <form action="{{ route('orders.search') }}" method="GET" style="margin-bottom: 0px;">
                    <div class="card-body table-responsive form-filter" style="padding-bottom: 10px;">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group row">
                                    <label for="dateFrom" class="col-sm-2 col-form-label">Từ ngày</label>
                                    <div class="col-sm-4">
                                        <input type="date" class="form-control" id="dateFrom" name="dateFrom"
                                               value="{{$dateFrom}}">
                                    </div>
                                    <label for="dateTo" class="col-sm-2 col-form-label">Đến ngày</label>
                                    <div class="col-sm-4">
                                        <input type="date" class="form-control" id="dateTo" name="dateTo"
                                               value="{{$dateTo}}">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="productCate" class="col-sm-2 col-form-label">Category</label>
                                    <div class="col-sm-4">

                                            <select class="form-control" id="productCate" name="productCate" onchange="this.form.submit()" >
                                                <option value="">Tất cả</option>
                                                @foreach ($productCates as $cate)
                                                    <option
                                                        value="{{ $cate->id }}" {{ $productCate == $cate->id ? 'selected' : '' }}>{{ $cate->name }}</option>
                                                @endforeach
                                            </select>
                                    </div>
                                    <label for="user" class="col-sm-2 col-form-label">Nhân viên</label>
                                    <div class="col-sm-4">
                                            <select class="form-control select2" id="user" name="user" onchange="this.form.submit()" >
                                                @if($isAdmin)
                                                    <option value="">Tất cả</option>
                                                @endif
                                                @foreach ($users as $itemUser)
                                                    <option
                                                        value="{{ $itemUser->id }}" {{ $user == $itemUser->id ? 'selected' : '' }}>{{ $itemUser->fullName }}({{$itemUser->userName}})
                                                    </option>
                                                @endforeach
                                            </select>
                                    </div>
{{--                                    <label for="user" class="col-sm-2 col-form-label">Seller</label>--}}
{{--                                    <div class="col-sm-4">--}}
{{--                                        <select class="form-control select2" id="seller" name="seller" onchange="this.form.submit()" >--}}
{{--                                            <option value="">Tất cả</option>--}}
{{--                                            @foreach ($sellers as $itemSeller)--}}
{{--                                                <option--}}
{{--                                                    value="{{ $itemSeller->id }}" {{ $seller == $itemSeller->id ? 'selected' : '' }}>{{ $itemSeller->sellerName }}--}}
{{--                                                </option>--}}
{{--                                            @endforeach--}}
{{--                                        </select>--}}
{{--                                    </div>--}}
                                </div>
                                <div class="form-group row">
                                    <label for="vps" class="col-sm-2 col-form-label">Vps</label>
                                    <div class="col-sm-10">
                                            <select class="form-control select2" id="vps" name="vps" onchange="this.form.submit()" >
                                                @if($isAdmin)
                                                    <option value="">Tất cả</option>
                                                @endif
                                                @foreach ($vpses as $itemVps)
                                                    <option
                                                        value="{{ $itemVps->id }}" {{ $vps == $itemVps->id ? 'selected' : '' }}>{{ $itemVps->name }}</option>
                                                @endforeach
                                            </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group row">
                                    <label for="orderNumber" class="col-sm-2 col-form-label">Số Order</label>
                                    <div class="col-sm-10">

                                        <input type="text" class="form-control" id="orderNumber" name="orderNumber" value="{{$orderNumber}}"
                                               placeholder="Số order">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="product" class="col-sm-2 col-form-label">Sản phẩm</label>
                                    <div class="col-sm-10">
                                        <select class="form-control select2-auto"
                                                data-href="{{route('api-products-search',[])}}"
                                                id="product"
                                                name="product" onchange="this.form.submit()" >
                                            @if($productSelect)
                                                <option value="{{$productSelect->id}}" selected>{{$productSelect->name}}</option>
                                            @endif
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="keyword" class="col-sm-2 col-form-label">Keyword</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="keyword" name="keyword" value="{{$keyword}}" placeholder="TrackCode, fulfill,...">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group row">
                                    <label for="customer" class="col-sm-2 col-form-label">Khách</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="customer" name="customer" value="{{$customer}}"
                                               placeholder="Khách hàng">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="trackingStatus" class="col-sm-2 col-form-label">Track</label>
                                    <div class="col-sm-2">
                                            <select class="form-control" id="trackingStatus" name="trackingStatus" onchange="this.form.submit()" >
                                                <option value="0">...</option>
                                                <option value="2" {{ $track == 2 ? 'selected' : '' }}>Không</option>
                                                <option value="1" {{ $track == 1 ? 'selected' : '' }}>Có</option>
                                            </select>
                                    </div>
                                    <label for="carrieStatus" class="col-sm-2 col-form-label">Carrie</label>
                                    <div class="col-sm-2">
                                            <select class="form-control" id="carrieStatus" name="carrieStatus" onchange="this.form.submit()" >
                                                <option value="0">...</option>
                                                <option value="2" {{ $carrie == 2 ? 'selected' : '' }}>Không</option>
                                                <option value="1" {{ $carrie == 1 ? 'selected' : '' }}>Có</option>
                                            </select>
                                    </div>

                                    <label for="fulfillStatus" class="col-sm-2 col-form-label">Fulfill</label>
                                    <div class="col-sm-2">
                                        <select class="form-control" id="fulfillStatus" name="fulfillStatus" onchange="this.form.submit()" >
                                            <option value="0">...</option>
                                            <option value="2" {{ $fulfill == 2 ? 'selected' : '' }}>Chưa</option>
                                            <option value="1" {{ $fulfill == 1 ? 'selected' : '' }}>Đã Fulfill</option>
                                        </select>
                                    </div>
{{--                                    <label for="isFB" class="col-sm-2 col-form-label">isFB</label>--}}
{{--                                    <div class="col-sm-2">--}}
{{--                                            <select class="form-control" id="isFB" name="isFB" onchange="this.form.submit()" >--}}
{{--                                                <option value="0">...</option>--}}
{{--                                                <option value="2"{{ $isFB == 2 ? 'selected' : '' }}>Chưa có</option>--}}
{{--                                                <option value="1"{{ $isFB == 1 ? 'selected' : '' }}>Đã có</option>--}}
{{--                                            </select>--}}
{{--                                    </div>--}}
                                </div>
                                <div class="form-group row">
                                    <label for="orderId" class="col-sm-2 col-form-label">ID</label>
                                    <div class="col-sm-4">
                                        <input type="number" class="form-control" id="orderId" name="orderId"  value="{{$orderId}}" placeholder="ID">
                                    </div>
                                    <label for="syncStoreStatus" class="col-sm-2 col-form-label">Up Ebay</label>
                                    <div class="col-sm-4">
                                            <select class="form-control" id="syncStoreStatus" name="syncStoreStatus" onchange="this.form.submit()" >
                                                <option value="0">...</option>
                                                <option value="2" {{ $ebay == 2 ? 'selected' : '' }}>Chưa</option>
                                                <option value="1" {{ $ebay == 1 ? 'selected' : '' }}>Đã up</option>
                                            </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-header" style="padding: 0px 10px 5px 0px;">
                            <div class="card-tools">
                                <button class="btn btn-sm btn-default" type="submit" name="submitOrder" value="Search">
                                    <i class="fa fa-search"></i> Tìm kiếm
                                </button>

                                <a class="btn btn-sm btn-primary" href="{{route('orders.index')}}" title="Reset">
                                    Reset
                                </a>

                                <button class="btn btn-sm btn-default" type="button" onclick="exprortUpEbay(this.form)">
                                    <i class="fas fa-file-export"></i> Up Track Ebay
                                </button>
                                <button class="btn btn-sm btn-default" type="button" onclick="exprortCSV(this.form)">
                                    <i class="fas fa-file-export"></i> Xuất CSV
                                </button>
                                <button class="btn btn-sm btn-default" type="button" onclick="exprortOrders(this.form)">
                                    <i class="fas fa-file-export"></i> Xuất Order
                                </button>
                                <a href="{{route('orders.editForm',['productCate'=>$productCate,'id'=>0])}}"
                                   title="Thêm mới đơn hàng">
                                    <div class="btn btn-sm btn-primary"><i class="fa fa-plus-square"></i> Thêm mới</div>
                                </a>
                                <a href="#" onclick="submitDeleteAlls()" title="Xóa các đơn đã chọn" >
                                    <div class="btn btn-sm btn-danger"><i class="fas fa-trash"></i> Delete Đơn</div>
                                </a>
                            </div>
                        </div>
                    </div>
                </form>
                <div class="card-header" style="padding-bottom: 10px; padding-top: 0px;">
                    <h3 class="card-title">Tổng cộng: <b>{{$counter}}</b> đơn hàng</h3>&nbsp;
                    <div class="card-tools">
                        <form method="POST" action="{{ route('import-csv') }}" enctype="multipart/form-data">
                            @csrf
                            <button class="btn btn-sm btn-default mr-2" type="button" onclick="$('[name=file]').click()">
                                <i class="fas fa-upload"></i> Import CSV
                            </button>
                            <input type="file" name="file" accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel" required style="display: none"/>
                        </form>
                    </div>
                </div>

                <div class="card-body table-responsive p-0">
                    <table class="table  projects table-p2 table-o-v">
                        <thead>
                        <tr>
                            <th width="4%">
                                No
                            </th>
                            <th width="10%">
                                Seller
                            </th>
                            <th>
                                Order
                            </th>
                            <th width="20%">
                                Guest
                            </th>
                            <th width="150px">
                                Fulfill
                            </th>
                            <th width="150px">
                                Tracking
                            </th>
                            <th width="20%">

                            </th>
                            <th width="40px">

                            </th>
                            <th width="40px">
                                <input type="checkbox" name="checkedAll" onclick="SelectAll(this)">
                            </th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($orders as $order)
                            <?php
                            $userCr = $users->where('id', $order->createBy)->first();
                            $userUp = $users->where('id', $order->updateBy)->first();
                            $seller = $sellers->where('id', $order->sellerId)->first();
                            $userSeller = $users->where('id', $seller->userId)->first();
                            $vps = $vpses->where('id', $order->vpsId)->first();
                            $cate = $productCates->where('id', $order->categoryId)->first();
                            $product = null;
                            $index++;
                            if($order->productId > 0 && $showProducts && !$showProducts->isEmpty()){
                                $product = $showProducts->where('id', $order->productId)->first();
                            }
                            ?>
                            <tr data-order="{{$order->id}}" data-index="{{$index}}" class="{{$index % 2 == 0 ?'odd':'even'}}">
                                <td rowspan="2" class="text-center">
                                    {{$order->id}}
                                </td>
                                <td>
                                    <p>{{$userSeller ? $userSeller->fullName:''}}</p>
                                    <p><span class="badge badge-info">{{$seller ? $seller->sellerName:''}}</span></p>
                                    <p>{{$vps ? $vps->name : ''}}</p>
                                </td>
                                <td>
                                    <p><span class="badge badge-success">{{$order->orderNumber}}</span></p>
                                    <p><small>{{  \Carbon\Carbon::parse($order->created_at)->timezone('Asia/Ho_Chi_Minh')->format('d/m/Y')}}</small></p>
                                </td>
                                <td>
                                    <p>{{$order->shipToFirstName}} {{$order->shipToLastName}}<br>
                                        {{$order->shipToAddressLine1}} {{$order->shipToAddressLine2}}<br>
                                        {{$order->shipToAddressCity}} - {{$order->shipToAddressStateOrProvince}} - {{$order->shipToAddressPostalCode}} - {{$order->shipToAddressCountry}}<br>
                                        {{$order->shipToAddressPhone}}</p>
                                </td>
                                <td>
                                    @if($order->fulfillStatusId ==0)
                                        <span class="badge badge-secondary">Chưa fulfill</span>
                                    @else
                                        <p>{{$order->fulfillCode}}</p>
                                        <span class="badge badge-info">Đã fulfill</span>
                                    @endif
                                </td>
                                <td>

                                    @if($order->trackingStatusId ==0)
                                        <span class="badge badge-secondary">Chưa Tracking</span>
                                    @else
                                        <p>{{$order->trackingCode}}</p>
                                        <span class="badge badge-info">Đã Tracking</span>
                                    @endif

                                    @if($order->carrierStatusId ==0)
                                        <span class="badge badge-secondary">Chưa Carrie</span>
                                    @else
                                        <p>{{$order->carrier}}</p>
                                        <span class="badge badge-info">Đã Carrie</span>
                                    @endif
                                </td>
                                <td>
                                    <p>Quantity: <b>{{$order->quantity}}</b></p>
                                    <p>Price: <b>{{$order->price}} $</b></p>
                                    <p>Fee Ship: <b>{{$order->ship}} $</b></p>
                                    <p>Total: <b>{{$order->cost}} $</b></p>

                                    @if($order->syncStoreStatusId ==0)
                                        <span class="badge badge-warning">Chưa Up Ebay</span>
                                        <button class="btn btn-sm btn-outline-info btn-xs" type="button" onclick="updateSyncStoreStatus({{$order->id}},'{{$order->trackingCode}}')">
                                            <i class="fas fa-check"></i>
                                        </button>
                                    @else
                                        <span class="badge badge-success">Đã cập nhật lên Ebay</span>
                                    @endif
                                </td>
                                <td class="project-actions text-center" rowspan="2">
                                    <a class="btn btn-default btn-sm text-info openPopup" data-width="100%"  title="Edit" href="javascript:void(0)  " data-href="{{route('orders.editForm',['productCate'=>$order->categoryId,'id'=>$order->id,'layout'=>'layouts.appblank','callBack'=>'callBackPopop'])}}">
                                        <i class="fas fa-pencil-alt">
                                        </i>
                                    </a>
                                    {!! Form::open(['method' => 'DELETE','route' => ['orders.destroy', $order->id],'id' => 'delete-form'.$order->id, 'style' =>'display: inline-block;']) !!}
                                    {{ Form::button('<i class="fas fa-trash"></i>', ['type' => 'submit', 'class' => 'btn btn-default btn-sm text-danger', 'onclick' => 'return confirmDelete(event,"delete-form'.$order->id.'");']) }}
                                    {!! Form::close() !!}
                                </td>
                                <td rowspan="2" class="text-center">
                                    <input id="chkAction" type="checkbox" name="chkActionIds" value="{{$order->id}}">
                                </td>
                            </tr>

                            <tr data-order="{{$order->id}}" data-index="{{$index}}" class="{{$index % 2 == 0 ?'odd':'even'}}">
                                <td colspan="3">
                                    @if($product)

                                        <div class="form-group row img-product">
                                            <div class="col-sm-3">
                                                <div class="img-p imp-p-list">
                                                    <img width="100%" src="{{strlen($product->urlImagePreviewOne) > 0 ?$product->urlImagePreviewOne:\App\Helper\Helper::$IMG_DEFAULT }}"/>
                                                </div>
                                                <input type="text" class="form-control" placeholder="Path" value="{{$product->urlImagePreviewOne}}">
                                            </div>
                                            <div class="col-sm-3">
                                                <div class="img-p imp-p-list">
                                                    <img width="100%" src="{{strlen($product->urlImagePreviewTwo) > 0 ?$product->urlImagePreviewTwo:\App\Helper\Helper::$IMG_DEFAULT }}"/>
                                                </div>
                                                <input type="text" class="form-control" placeholder="Path" value="{{$product->urlImagePreviewTwo}}">
                                            </div>
                                            <div class="col-sm-3">
                                                <div class="img-p imp-p-list">
                                                    <img width="100%" src="{{strlen($product->url_img_design1) > 0 ?$product->url_img_design1:\App\Helper\Helper::$IMG_DEFAULT }}"/>
                                                </div>
                                                <input type="text" class="form-control" placeholder="Path" value="{{\App\Helper\Helper::getImageUrlPath($product->url_img_design1,'original',true) }}">
                                            </div>
                                            <div class="col-sm-3">
                                                <div class="img-p imp-p-list">
                                                    <img width="100%" src="{{strlen($product->url_img_design2) > 0 ?$product->url_img_design2:\App\Helper\Helper::$IMG_DEFAULT }}"/>
                                                </div>
                                                <input type="text" class="form-control" placeholder="Path" value="{{\App\Helper\Helper::getImageUrlPath($product->url_img_design2,'original',true)}}">
                                            </div>
                                        </div>
                                    @endif
                                </td>

                                <td colspan="3">
                                    @if($product)
                                        <h5>
                                            <?php if(!Helper::IsNullOrEmptyString($order->itemId)){?>
                                                <a href="{{'https://www.ebay.com/itm/'.$order->itemId}}" target="_blank><b class="text-info">{{$product->name}}</b></a>
                                            <?php }else if(!Helper::IsNullOrEmptyString($product->url)){?>
                                                <a href="{{$product->url}}" target="_blank"><b class="text-info">{{$product->name}}</b></a>
                                            <?php }else{ ?>
                                                <span><b class="text-info">{{$product->name}}</b></span>
                                            <?php } ?>
                                        </h5>
                                    @endif
                                    <div class="form-group row">
                                        <span class="col-sm-2">SKU</span>
                                        <span class="col-sm-4">
                                            <b>{{$order->sku}}</b>
                                        </span>
                                        <span class="col-sm-2">Style</span>
                                        <span class="col-sm-4">
                                            <b>{{$cate ? $cate->name:''}}</b>
                                        </span>
                                    </div>
                                    <div class="form-group row">
                                        <span  class="col-sm-2">Size</span>
                                        <span class="col-sm-4">
                                            <b>{{$order->size}}</b>
                                        </span>
                                        <span class="col-sm-2">Color</span>
                                        <span class="col-sm-4">
                                            <b>{{$order->color}}</b>
                                        </span>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-sm-12">
                                            <textarea rows="2" class="form-control" style="resize: none;">{{$order->note}}</textarea>
                                        </div>
                                    </div>

                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
                <!-- /.card-body -->
                <div class="card-footer">
                    {{ $orders->appends(request()->query())->links('pagination::bootstrap-4', ['link_limit' => 3]) }}</div>
            </div>
            <!-- /.card -->

        </section>
        <!-- /.content -->
    </div>
@stop
@section('footer')
    <style>
        .backtotop {
            position: fixed;
            bottom: 35px;
            right: 10px;
            z-index: 9999;
            background: #ff8551;
            border-radius: 100%;
            height: 48px;
            width: 48px;
            text-align: center;
            box-shadow: 0 1px 6px 0 rgba(32,33,36,0.28);
        }
        .backtotop i {
            color: #fff;
            font-size: 24px;
            line-height: 46px;
        }
    </style>
    <a id="backtotop" href="#" class="backtotop"><i class="fas fa-chevron-up"></i></a>
    <!-- Select2 -->
    <script src="{{asset('plugins/select2/js/select2.full.min.js')}}"></script>

    <script>
        function callBackPopop(orderId) {
            if (orderId != null && orderId > 0) {
                $.get('{{route('api-order-row')}}?id=' + orderId+'&index='+$('tr[data-order='+orderId+']:first').attr('data-index'), function (data, status) {
                    if (data != null && data.indexOf('$$$')>0) {
                        var dataArrs = data.split('$$$');
                        $('tr[data-order='+orderId+']:first').focus();
                        $('tr[data-order='+orderId+']:first').replaceWith(dataArrs[0]);
                        $('tr[data-order='+orderId+']:last').replaceWith(dataArrs[1]);
                    }
                });
            }
        }

        function exprortCSV(form) {
            if($('tr[data-order]').length == 0){
                alert('Không có đơn hàng nào, vui lòng tìm kiếm các đơn hàng trước khi xuất file.')
            }else{
                $.ajax({
                    url: "{{route('export-csv')}}",
                    type: "get",
                    data: $(form).serialize(),xhr: function () {
                        var xhr = new XMLHttpRequest();
                        xhr.onreadystatechange = function () {
                            if (xhr.readyState == 2) {
                                if (xhr.status == 200) {
                                    xhr.responseType = "blob";
                                } else {
                                    xhr.responseType = "text";
                                }
                            }
                        };
                        return xhr;
                    },
                    success: function (data, status, xhr) {
                        let filename = "";
                        let disposition = xhr.getResponseHeader('Content-Disposition');
                        if (disposition && disposition.indexOf('attachment') !== -1) {
                            let filenameRegex = /filename[^;=\n]*=((['"]).*?\2|[^;\n]*)/;
                            let matches = filenameRegex.exec(disposition);
                            if (matches != null && matches[1]) filename = matches[1].replace(/['"]/g, '');
                        }
                        let a = document.createElement('a');
                        let url = window.URL.createObjectURL(data);
                        a.href = url;
                        a.download = filename.replace('UTF-8', '');;
                        document.body.append(a);
                        a.click();
                        a.remove();
                        window.URL.revokeObjectURL(url);
                    },
                    error: function (xhr) {
                        console.log(xhr.responseText);
                    }
                });
            }
        }
        function exprortOrders(form) {
            if($('tr[data-order]').length == 0){
                alert('Không có đơn hàng nào, vui lòng tìm kiếm các đơn hàng trước khi xuất file.')
            }else{
                $.ajax({
                    url: "{{route('export-orders')}}",
                    type: "get",
                    data: $(form).serialize(),xhr: function () {
                        var xhr = new XMLHttpRequest();
                        xhr.onreadystatechange = function () {
                            if (xhr.readyState == 2) {
                                if (xhr.status == 200) {
                                    xhr.responseType = "blob";
                                } else {
                                    xhr.responseType = "text";
                                }
                            }
                        };
                        return xhr;
                    },
                    success: function (data, status, xhr) {
                        let filename = "";
                        let disposition = xhr.getResponseHeader('Content-Disposition');
                        if (disposition && disposition.indexOf('attachment') !== -1) {
                            let filenameRegex = /filename[^;=\n]*=((['"]).*?\2|[^;\n]*)/;
                            let matches = filenameRegex.exec(disposition);
                            if (matches != null && matches[1]) filename = matches[1].replace(/['"]/g, '');
                        }
                        let a = document.createElement('a');
                        let url = window.URL.createObjectURL(data);
                        a.href = url;
                        a.download = filename.replace('UTF-8', '');;
                        document.body.append(a);
                        a.click();
                        a.remove();
                        window.URL.revokeObjectURL(url);
                    },
                    error: function (xhr) {
                        console.log(xhr.responseText);
                    }
                });
            }
        }
        function exprortUpEbay(form) {
            if($('tr[data-order]').length == 0 || $('#syncStoreStatus').val() != '2'|| $('#vps').val() == '' || parseInt($('#vps').val())  == 0){
                alert('Không có đơn hàng nào, hoặc các đơn hàng chưa phải là các đơn hàng chưa Up Ebay, hoặc chưa chọn seller vui lòng tìm kiếm các đơn hàng trước khi xuất file.')
            }else{
                $.ajax({
                    url: "{{route('export-up-ebay')}}",
                    type: "get",
                    data: $(form).serialize(),
                    success: function (data, status, xhr) {
                         if(data != null){
                             alert(data.message);
                         }
                    },
                    error: function (xhr) {
                        alert('Đã có lỗi export file.')
                    }
                });
            }
        }
        function updateSyncStoreStatus(orderId, trackingCode) {
            if(trackingCode == null || trackingCode.length == 0){
                alert('Đơn hàng chưa Tracking hãy kiểm tra lại thông tin.')
            } else{
                $.ajax({
                    url: "{{route('update-status-up-ebay')}}",
                    type: "get",
                    data: {'orderId':orderId},
                    success: function (data, status, xhr) {
                        if(data != null){
                            if(!data.status){
                                alert(data.message);
                            }else{
                                navigator.clipboard.writeText(data.data);
                                $.get('{{route('api-order-row')}}?id=' + orderId+'&index='+$('tr[data-order='+orderId+']:first').attr('data-index'), function (data, status) {
                                    if (data != null && data.indexOf('$$$')>0) {
                                        var dataArrs = data.split('$$$');
                                        $('tr[data-order='+orderId+']:first').focus();
                                        $('tr[data-order='+orderId+']:first').replaceWith(dataArrs[0]);
                                        $('tr[data-order='+orderId+']:last').replaceWith(dataArrs[1]);
                                    }
                                });

                            }
                        }
                    },
                    error: function (xhr) {
                        alert('Đã có lỗi cập nhật trạng thái.')
                    }
                });
            }
        }
        function confirmDelete(event, id) {
            event.preventDefault();
            if (confirm('Bạn có chắc chắn xóa đơn này không?')) {
                document.getElementById(id).submit();
            }
        }

        function submitForm() {
            document.forms['myForm'].submit();
        }
        $(function () {
            //Initialize Select2 Elements
            $('.select2').select2();
            if ($(".select2-auto").length) {
                $(".select2-auto").each(function () {
                    var title = $(this).attr('title');
                    var url = $(this).attr('data-href');
                    $(this).select2({
                        ajax: {
                            delay: 250,
                            dataType: 'json',
                            url: url,
                            data: function (params) {
                                return {
                                    q: params.term
                                };
                            },
                            processResults: function (data) {
                                return {
                                    results: data
                                };
                            },
                        },
                        placeholder: title,
                        minimumInputLength: 2
                    });
                });
            }


            $(document).on('select2:open', () => {
                document.querySelector('.select2-search__field').focus();
            });
            $('[name=file]').on('change', function () {
                $(this).closest('form').submit();
            });
        });
        function SelectAll(CheckBoxControl) {
            if (CheckBoxControl.checked == true) {
                $('[name="chkActionIds"]').each(function() {
                    this.checked = true;
                });
            }
            else {
                $('[name="chkActionIds"]').each(function() {
                    this.checked = false;
                });
            }
        }
        function submitDeleteAlls() {
            if($('[name="chkActionIds"]:checked').length){
                if(confirm("Bạn có chắc chắn muốn xóa nhưng đơn hàng đã chọn không?")){
                    var selected = [];
                    $('[name="chkActionIds"]:checked').each(function() {
                        selected.push($(this).val());
                    });
                    $.get("{{route('api-orders-remove')}}",
                        {
                            ids: selected.join(',')
                        },
                        function(data,status){
                            $('[name="checkedAll"]').prop('checked',false);
                            if(data != null){
                                alert(data.message);
                                if(data.status == 'success' && data.data != null && data.data.length){
                                    for (var i = 0; i < data.data.length; i++) {
                                        $('tr[data-order="'+data.data[i]+'"]').remove();
                                    }
                                }
                                //location.reload();
                            }else{
                                alert('Đã có lỗi xảy ra, xin vui lòng liên hệ kỹ thuật để được hỗ trợ.');
                            }
                        });
                }
            }else{
                alert('Hãy chọn đơn hàng!');
            }
        }
    </script>
@endsection

