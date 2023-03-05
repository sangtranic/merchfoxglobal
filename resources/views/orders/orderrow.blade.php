<tr data-order="{{$order->id}}" data-index="{{$index}}" class="{{$index % 2 == 0 ?'odd':'even'}}">
    <td rowspan="2" class="text-center">
        {{$index}}
    </td>
    <td>
        <p>{{$userCr->fullName}}</p>
        <p><span class="badge badge-secondary">{{$seller->sellerName}}</span></p>
        <p>{{$vps->name}}</p>
    </td>
    <td>
        <p><span class="badge badge-success">{{$order->orderNumber}}</span></p>
        <p><small>{{  \Carbon\Carbon::parse($order->created_at)->timezone('Asia/Ho_Chi_Minh')->format('d-m-y')}}</small></p>
    </td>
    <td>
        <p>{{$order->shipToAddressName}}<br>
            {{$order->shipToAddressLine1}}<br>
            {{$order->shipToAddressLine2}}<br>
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
    </td>
    <td class="project-actions text-center" rowspan="2">
        <a class="btn btn-default btn-sm text-info openPopup" data-width="100%"  title="Edit" href="javascript:void(0)" data-href="{{route('orders.editForm',['productCate'=>$order->categoryId,'id'=>$order->id,'layout'=>'layouts.appblank','callBack'=>'callBackPopop'])}}">
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
$$$
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
            <h5><a href="{{strlen($order->itemId) >0 ?'https://www.ebay.com/itm/'.$order->itemId:'#'}}"><b class="text-info">{{$product->name}}</b></a></h5>
        @endif
        <div class="form-group row">
            <span class="col-sm-2">SKU</span>
            <span class="col-sm-4">
                                            <b>{{$order->sku}}</b>
                                        </span>
            <span class="col-sm-2">Style</span>
            <span class="col-sm-4">
                                            <b>{{$cate->name}}</b>
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
