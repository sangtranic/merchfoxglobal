@extends($layoutName)
@section('title', 'Thông tin sản phẩm')
@section('head')
    <link rel="stylesheet" href="{{asset('plugins/select2/css/select2.min.css')}}">
@stop

@section('content')
    @if(session('statusUpdate'))
        <script>
            setTimeout(function () {
                $('#myModal .close', window.parent.document).click();
                @if(!\App\Helper\Helper::IsNullOrEmptyString($callBack))
                    window.parent.callBackPopop({{session('orderId')}});
                @endif
                @if(\App\Helper\Helper::IsNullOrEmptyString($callBack))
                window.parent.document.location.reload();
                @endif
            }, 1000);
        </script>
    @endif
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <div class="content-header">
            <div class="container">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Order Form </h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <a class="openPopup"
                           id="btnAddProduct"
                           data-href="{{route('products.edit',['product'=>0,'cate'=> $order->categoryId,'callBack'=>'callBackPopop'])}}"
                           data-width="800px" title="Thêm mới sản phẩm">
                            <div class="btn btn-sm btn-primary"><i class="fa fa-plus-square"></i> Thêm mới</div>
                        </a>
                    </div>
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <!-- Main content -->
        <section class="content form-filter">

            @if($errors->any())
                <div class="alert alert-danger">
                    @foreach ($errors->all() as $error)
                        {{ $error }} <br>
                    @endforeach
                </div>
            @endif
            @if($order->id == 0)
                {{ Form::model($order, array('route' => array('orders.store'), 'method' => 'POST','enctype'=>'multipart/form-data')) }}
            @endif

            @if($order->id > 0)
                {{ Form::model($order, array('route' => array('orders.update', $order->id), 'method' => 'PUT','enctype'=>'multipart/form-data')) }}
            @endif
            <div class="row">
                {{ Form::hidden('userId') }}
                {{ Form::hidden('layoutName') }}
                <div class="col-md-6">
                    <div class="form-group row">
                        <label for="vpsId" class="col-sm-2 col-form-label">VPS</label>
                        <div class="col-sm-2">
                            <select class="form-control" id="vpsId" name="vpsId">
                                @if($vpses && !($vpses->isEmpty()))
                                    @foreach ($vpses as $itemVps)
                                        <option
                                            value="{{ $itemVps->id }}" {{ $order->vpsId == $itemVps->id ? 'selected' : '' }}>{{ $itemVps->name }}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                        <div class="col-sm-2">
                            <select class="form-control" id="sellerId" name="sellerId">
                                @if($sellers && !($sellers->isEmpty()))
                                    @foreach ($sellers as $itemSeller)
                                        <option
                                            value="{{ $itemSeller->id }}" {{ $order->sellerId == $itemSeller->id ? 'selected' : '' }}>{{ $itemSeller->sellerName }}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                        {{ Form::label('orderNumber','Order Id',['class'=>'col-sm-2 col-form-label'])}}
                        <div class="col-sm-4">
                            {{ Form::text('orderNumber', null, array('class' => 'form-control')) }}
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-6 col-form-label">Thông tin vận chuyển</label>
                        {{ Form::label('itemId','item Id',['class'=>'col-sm-2 col-form-label'])}}
                        <div class="col-sm-4">
                            {{ Form::text('itemId', null, array('class' => 'form-control')) }}
                        </div>
                    </div>
                    <div class="form-group row">
                        {{ Form::label('shipToFirstName','First name',['class'=>'col-sm-2 col-form-label'])}}
                        <div class="col-sm-4">
                            {{ Form::text('shipToFirstName', null, array('class' => 'form-control')) }}
                        </div>
						{{ Form::label('shipToFirstName','Last name',['class'=>'col-sm-2 col-form-label'])}}
                        <div class="col-sm-4">
                            {{ Form::text('shipToLastName', null, array('class' => 'form-control')) }}
                        </div>
						 {{ Form::label('shipToAddressLine1','Address Ln 1',['class'=>'col-sm-2 col-form-label'])}}
                        <div class="col-sm-4">
                            {{ Form::text('shipToAddressLine1', null, array('class' => 'form-control')) }}
                        </div>
						 {{ Form::label('shipToAddressLine2','Address Ln 2',['class'=>'col-sm-2 col-form-label'])}}
                        <div class="col-sm-4">
                            {{ Form::text('shipToAddressLine2', null, array('class' => 'form-control')) }}
                        </div>
                    </div>
                    <div class="form-group row">
                        {{ Form::label('shipToAddressCity','City',['class'=>'col-sm-2 col-form-label'])}}
                        <div class="col-sm-4">
                            {{ Form::text('shipToAddressCity', null, array('class' => 'form-control')) }}
                        </div>
                       {{ Form::label('shipToAddressStateOrProvince','State',['class'=>'col-sm-2 col-form-label'])}}
                                <div class="col-sm-4">
                                    {{ Form::text('shipToAddressStateOrProvince', null, array('class' => 'form-control')) }}
                                </div>
                    </div>
                    <div class="form-group row">
						{{ Form::label('shipToAddressPostalCode','ZipCode',['class'=>'col-sm-2 col-form-label'])}}
                                <div class="col-sm-4">
                                    {{ Form::text('shipToAddressPostalCode', null, array('class' => 'form-control')) }}
                                </div>
								{{ Form::label('shipToAddressCountry','Country',['class'=>'col-sm-2 col-form-label'])}}
                                <div class="col-sm-4">
                                    {{ Form::text('shipToAddressCountry', null, array('class' => 'form-control')) }}
                                </div>
						{{ Form::label('shipToAddressPhone','Phone',['class'=>'col-sm-2 col-form-label'])}}
                        <div class="col-sm-4">
                            {{ Form::text('shipToAddressPhone', null, array('class' => 'form-control')) }}
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-6">
                            <div class="form-group row">

                            </div>
                        </div>
                        <div class="col-sm-6">
                            {{ Form::label('note','Note')}}
                            {{ Form::textarea('note', null, array('class' => 'form-control', 'rows' => 3)) }}
                        </div>
                    </div>
                    <div class="form-group row">
                        {{ Form::label('sku','SKU',['class'=>'col-sm-1 col-form-label'])}}
                        <div class="col-sm-2">
                            {{ Form::text('sku', null, array('class' => 'form-control')) }}
                        </div>
                        {{ Form::label('fulfillCode','Fulfill Code',['class'=>'col-sm-1 col-form-label'])}}
                        <div class="col-sm-2">
                            {{ Form::text('fulfillCode', null, array('class' => 'form-control')) }}
                        </div>
                        {{ Form::label('trackingCode','Tracking Code',['class'=>'col-sm-1 col-form-label'])}}
                        <div class="col-sm-2">
                            {{ Form::text('trackingCode', null, array('class' => 'form-control')) }}
                        </div>
                        {{ Form::label('carrier','Carrier',['class'=>'col-sm-1 col-form-label'])}}
                        <div class="col-sm-2">
                            {{ Form::text('carrier', null, array('class' => 'form-control')) }}
                        </div>
                    </div>
                    <div class="form-group row">
                        {{ Form::label('quantity','Quantity',['class'=>'col-sm-1 col-form-label'])}}
                        <div class="col-sm-2">
                            {{ Form::number('quantity', null, array('class' => 'form-control')) }}
                        </div>
                        {{ Form::label('price','Price',['class'=>'col-sm-1 col-form-label'])}}
                        <div class="col-sm-2">
                            {{ Form::number('price', null, array('class' => 'form-control','step' => 'any')) }}
                        </div>
                        {{ Form::label('cost','Cost',['class'=>'col-sm-1 col-form-label'])}}
                        <div class="col-sm-2">
                            {{ Form::number('cost', null, array('class' => 'form-control','step' => 'any')) }}
                        </div>
                        {{ Form::label('profit','Profit',['class'=>'col-sm-1 col-form-label'])}}
                        <div class="col-sm-2">
                            {{ Form::number('profit', null, array('class' => 'form-control','step' => 'any')) }}
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-2"></div>
                        <div class="col-sm-4 ">
                            <div class="icheck-primary d-inline">
                                <input type="checkbox" id="isFB"
                                       name="isFB" {{$order->isFB ? 'checked' : '' }}>
                                <label for="isFB">
                                    isFB
                                </label>
                            </div>
                        </div>

                        <label for="statusId" class="col-sm-2 col-form-label">Trạng thái</label>
                        <div class="col-sm-4">
                            <select class="form-control" id="statusId" name="statusId">

                                @foreach ($statusList as $itemStatus)
                                    <option
                                        value="{{ $itemStatus->id }}" {{ $order->statusId == $itemStatus->id ? 'selected' : '' }}>{{ $itemStatus->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Phải -->
                <div class="col-md-6">
                    <div class="form-group row">
                        <label for="categoryId" class="col-sm-2 col-form-label">Chuyên mục</label>
                        <div class="col-sm-10">
                            <select class="form-control" id="categoryId" name="categoryId"
                                    onchange="onChangeProductCate()">
                                @foreach ($productCates as $itemProductCate)
                                    <option
                                        value="{{ $itemProductCate->id }}" {{ $order->categoryId == $itemProductCate->id ? 'selected' : '' }}>{{ $itemProductCate->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="productName" class="col-sm-2 col-form-label">Tên sản phẩm</label>
                        <div class="col-sm-10">
                            <div class="input-group">
                                <div class="custom-file">
                                    <input type="text" class="form-control" id="productName" name="productName"
                                           value="{{$product ?$product->name:'' }}" placeholder="Tên sản phẩm">
                                    <div class="input-group-append" style="cursor: pointer;" id="copyProductName">
                                        <span class="input-group-text m-0">Copy</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="productId" class="col-sm-2 col-form-label">Tên sản phẩm</label>
                        <div class="col-sm-10">
                            <select class="form-control select2-auto"
                                    data-href="{{route('api-products-search')}}"
                                    data-url="{{route('api-product-detail')}}"
                                    id="productId"
                                    name="productId"
                                    tyle="width: 100%;">
                                @if($product)
                                    <option value="{{$product->id}}">{{$product->name}}</option>
                                @endif
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="slOrderType" class="col-sm-2 col-form-label">Ảnh mặt trước</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="urlImagePreviewOne"
                                   name="urlImagePreviewOne" value="{{ $product ? $product->urlImagePreviewOne : ''}}" placeholder="Ảnh mặt trước">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="slOrderType" class="col-sm-2 col-form-label">Ảnh mặt sau</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="urlImagePreviewTwo"
                                   name="urlImagePreviewTwo"  value="{{$product ? $product->urlImagePreviewTwo : ''}}"  placeholder="Ảnh mặt sau">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="slOrderType" class="col-sm-2 col-form-label">Thiết kế mặt trước</label>
                        <div class="col-sm-10">
                            <div class="input-group">
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input" id="imageDesignOne"
                                           name="imageDesignOne">
                                    <label class="custom-file-label" for="imageDesignOne">Choose file</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="slOrderType" class="col-sm-2 col-form-label">Thiết kế mặt sau</label>
                        <div class="col-sm-10">

                            <div class="input-group">
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input" id="imageDesignTwo"
                                           name="imageDesignTwo">
                                    <label class="custom-file-label" for="imageDesignTwo">Choose file</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row img-product">
                        <label class="col-sm-6 col-form-label">Ảnh</label>
                        <label class="col-sm-6 col-form-label">Thiết kế</label>
                        <div class="col-sm-3">
                            <div class="img-p">
                                <img width="100%" id="imgPreview1" src="{{$product ? $product->urlImagePreviewOne :""}}"/>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="img-p">
                                <img width="100%" id="imgPreview2" src="{{$product ? $product->urlImagePreviewTwo :""}}"/>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="img-p">
                                <img width="100%" id="imgDesign1" src="{{$product ? $product->imageDesign1 :""}}"/>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="img-p">
                                <img width="100%" id="imgDesign2" src="{{$product ? $product->imageDesign2 :""}}"/>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="slOrderType" class="col-sm-2 col-form-label">Size</label>
                        <div class="col-sm-4">
                            <div class="form-group clearfix" id="view-productSizes">
                                @if($productSizes)
                                    @foreach($productSizes as $item_productSize)
                                        <div class="icheck-primary d-inline">
                                            <input type="radio" id="size_{{$item_productSize}}"
                                                   name="size" {{$item_productSize == $order->size ? 'checked':''}} value="{{$item_productSize}}">
                                            <label for="size_{{$item_productSize}}">{{$item_productSize}}</label>
                                        </div>
                                    @endforeach
                                @endif
                            </div>
                        </div>
                        <label for="color" class="col-sm-1 col-form-label">Màu</label>
                        <div class="col-sm-2">
                            <select class="form-control" id="color" name="color" style="width: 100%;">
                                @if($productColors)
                                    @foreach($productColors as $item_productColor)
                                        <option
                                            value="{{$item_productColor}}" {{$item_productColor == $order->color ? 'selected':''}}>{{$item_productColor}}</option>
                                    @endforeach
                                @endif

                            </select>
                        </div>
                        <div class="col-sm-3">
                            <div class="icheck-primary d-inline">
                                <input type="checkbox" id="isFileDesign"
                                       name="isFileDesign" {{$product && $product->isFileDesign ? 'checked' : '' }}>
                                <label for="isFileDesign">
                                    File Thiết kế
                                </label>
                            </div>
                        </div>

                    </div>
                </div>
            </div>{{----}}
            <div class="cmstoolbarcontainerEdit">
                <div class="cmstoolbarcontent">
                    <div class="row justify-content-right">
                        <div class="form-group" style="padding: 0 10px">
                            <button id="btSaveArticle" type="submit" name="SubmitButton" value="Save"
                                    class="btn btn-sm btn-primary valid" aria-invalid="false"><i
                                    class="fa fa-save"></i>
                                Lưu thông tin
                            </button>
                            @if($order->id == null || $order->id == 0)
                            <button id="btSaveArticle" type="submit" name="SubmitButton" value="SaveBack"
                                    class="btn btn-sm btn-primary valid" aria-invalid="false"><i
                                    class="fa fa-save"></i>
                                Lưu thông tin và quay lại
                            </button>
                            <a class="btn btn-sm btn-default" href="{{route('orders.index')}}"><i
                                    class="fa fa-times"></i> Quay lại
                            </a>@endif
                        </div>
                    </div>
                </div>
            </div>

            {{ Form::close() }}
        </section>
        <!-- /.content -->
    </div>
@stop

@section('footer')

    <!-- Select2 -->
    <script src="{{asset('plugins/select2/js/select2.full.min.js')}}"></script>
    <script>
        $(function () {
            $('#vpsId').change(function() {
                var vpsId = $(this).val();

                $.ajax({
                    url: '/getListSellerByVpsId',
                    data: {'vpsId':vpsId},
                    type: 'GET',
                    dataType: 'json',
                    success: function(data) {
                        var sellerSelect = $('#sellerId');
                        sellerSelect.empty();
                        $.each(data, function(index, seller) {
                            sellerSelect.append('<option value="' + seller.sellerId + '">' + seller.sellerName + '</option>');
                        });
                    }
                });
            });
            //Initialize Select2 Elements
            $('.select2').select2();
            if ($(".select2-auto").length) {
                $(".select2-auto").each(function () {
                    var domSelect2 = $(this);
                    var title = domSelect2.attr('title');
                    var url = domSelect2.attr('data-href');
                    var urlDetail = domSelect2.attr('data-url');
                    domSelect2.select2({
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
                    domSelect2.on("select2:selecting", function (e) {
                        console.log(e);
                        if (typeof urlDetail !== 'undefined' && urlDetail !== false) {
                            $.get(urlDetail + '?id=' + e.params.args.data.id, function (data, status) {
                                if (status == 'success' && data != null) {
                                    fillProductByJson(data);
                                }
                            });
                        }
                    });
                });
            }


            $(document).on('select2:open', () => {
                document.querySelector('.select2-search__field').focus();
            });
            $(document).on("click", "#copyProductName", function () {
                $('#copyProductName span').html('Copied');
                var inputValue = $('#productName').val();
                var tempTextarea = document.createElement("textarea");
                document.body.appendChild(tempTextarea);
                tempTextarea.value = inputValue;
                tempTextarea.select();
                document.execCommand("copy");
                document.body.removeChild(tempTextarea);
                setTimeout(function () {
                    $('#copyProductName span').html('Copy');
                }, 1000);
            });
        });

        function fillProductByJson(product) {
            $('#productName').val(product.name);
            $('#urlImagePreviewOne').val(product.urlImagePreviewOne);
            $('#itemId').val(product.itemId);
            $('#urlImagePreviewTwo').val(product.urlImagePreviewTwo);
            $('#imgPreview1').attr('src', product.urlImagePreviewOne);
            $('#imgPreview2').attr('src', product.urlImagePreviewTwo);
            $('#imgDesign1').attr('src', product.imageDesign1);
            $('#imgDesign2').attr('src', product.imageDesign2);
            $('#isFileDesign').prop('checked', product.isFileDesign);
            $('#color').val(product.color);
        }

        function callBackPopop(productId) {
            if (productId != null && productId > 0) {
                $.get('{{route('api-product-detail')}}?id=' + productId, function (data, status) {
                    if (status == 'success' && data != null) {
                        fillProductByJson(data);
                        if ($('#productId').val() != productId) {
                            $('#productId').html('<option value="' + productId + '">' + data.name + '</option>');
                        }
                    }
                });
            }
        }

        function onChangeProductCate() {
            var cateId = $('#categoryId').val();
            var data_href = $('#btnAddProduct').attr('data-href');
            data_href = updateQueryStringParameter(data_href, 'cate', cateId);
            $('#btnAddProduct').attr('data-href', data_href);
            updateSizeAndColor(cateId);
        }

        function updateSizeAndColor(categoryId) {
            if (categoryId != null && categoryId > 0) {
                $.get('{{route('api-product-category-detail')}}?id=' + categoryId, function (data, status) {
                    if (status == 'success' && data != null) {
                        var html_product_sizes = '', html_product_colors='';
                        if (data.listSizes != null && data.listSizes.length > 0) {
                            for (var i = 0; i < data.listSizes.length; i++) {
                                html_product_sizes += '<div class="icheck-primary d-inline">\n<input type="radio" id="size_'+data.listSizes[i]+'" name="size" value="'+data.listSizes[i]+'">\n<label for="size_'+data.listSizes[i]+'">'+data.listSizes[i]+'</label>\n</div>\n'
                            }
                        }
                        if (data.listColors != null && data.listColors.length > 0) {
                            for (var i = 0; i < data.listColors.length; i++) {
                                html_product_colors += '<option value="'+data.listColors[i]+'">'+data.listColors[i]+'</option>'
                            }
                        }
                        $('#view-productSizes').html(html_product_sizes);
                        $('#color').html(html_product_colors);
                    }
                });
            }
        }

        function updateQueryStringParameter(uri, key, value) {
            var re = new RegExp("([?&])" + key + "=.*?(&|$)", "i");
            var separator = uri.indexOf('?') !== -1 ? "&" : "?";
            if (uri.match(re)) {
                return uri.replace(re, '$1' + key + "=" + value + '$2');
            } else {
                return uri + separator + key + "=" + value;
            }
        }
    </script>
@endsection
