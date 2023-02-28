@extends('layouts.app')
@section('title', 'Danh sách đơn hàng')
@section('head')
    <link rel="stylesheet" href="{{asset('plugins/select2/css/select2.min.css')}}">
@endsection
@section('content')
    <?php $index = 1;?>
    <div class="content-wrapper">
        <!-- Main content -->
        <section class="content mt-2">
            <!-- Default box -->
            <div class="card">
                <div class="card-body table-responsive form-filter">
                    <form action="{{ route('orders.search') }}" method="GET">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group row">
                                    <label for="dateFrom" class="col-sm-2 col-form-label">Từ ngày</label>
                                    <div class="col-sm-4">
                                        <input type="date" class="form-control" id="dateFrom" name="dateFrom" value="{{$dateFrom}}">
                                    </div>
                                    <label for="dateTo" class="col-sm-2 col-form-label">Đến ngày</label>
                                    <div class="col-sm-4">
                                        <input type="date" class="form-control" id="dateTo" name="dateTo" value="{{$dateTo}}">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="productCate" class="col-sm-2 col-form-label">Category</label>
                                    <div class="col-sm-4">
                                        <select class="form-control" id="productCate" name="productCate">
                                            <option value="">Tất cả</option>
                                            @foreach ($productCates as $cate)
                                                <option value="{{ $cate->id }}" {{ $productCate == $cate->id ? 'selected' : '' }}>{{ $cate->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <label for="user" class="col-sm-2 col-form-label">Seller</label>
                                    <div class="col-sm-4">
                                        <select class="form-control select2" id="user" name="user">
                                            <option value="">Tất cả</option>
                                            @foreach ($users as $itemUser)
                                                <option value="{{ $itemUser->id }}" {{ $user == $itemUser->id ? 'selected' : '' }}>{{ $itemUser->fullName }}({{ $itemUser->userName }})</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="vps" class="col-sm-2 col-form-label">Vps</label>
                                    <div class="col-sm-10">
{{--                                        <select class="form-control select2" id="vps" name="vps">--}}
{{--                                            <option value="">Tất cả</option>--}}
{{--                                            @foreach ($vpses as $itemVps)--}}
{{--                                                <option value="{{ $itemVps->id }}" {{ $vps == $itemVps->id ? 'selected' : '' }}>{{ $itemVps->name }}</option>--}}
{{--                                            @endforeach--}}
{{--                                        </select>--}}
                                        <select class="form-control select2-auto" data-href="{{route('api-vpses-search')}}" title="Chọn vps" id="vps" name="vps">
                                            @if($vpses && !($vpses->isEmpty()))
                                                @foreach ($vpses as $itemVps)
                                                    <option value="{{ $itemVps->id }}" {{ $vps == $itemVps->id ? 'selected' : '' }}>{{ $itemVps->name }}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group row">
                                    <label for="txtOrderNumber" class="col-sm-2 col-form-label">Số Order</label>
                                    <div class="col-sm-10">

                                        <input type="text" class="form-control" id="txtOrderNumber"
                                               placeholder="Số order">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="txtProduct" class="col-sm-2 col-form-label">Sản phẩm</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="txtProduct" placeholder="Sản phẩm">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="txtKeyword" class="col-sm-2 col-form-label">Keyword</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="txtKeyword"
                                               placeholder="TrackCode, fulfill,...">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group row">
                                    <label for="txtCustomer" class="col-sm-2 col-form-label">Khách</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="txtCustomer"
                                               placeholder="Khách hàng">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="slTrack" class="col-sm-2 col-form-label">Track</label>
                                    <div class="col-sm-4">
                                        <select class="form-control" id="slTrack">
                                            <option>...</option>
                                            <option>Chưa Add</option>
                                            <option>Chưa Có</option>
                                            <option>Đã Add</option>
                                            <option>Đã Có</option>
                                        </select>
                                    </div>
                                    <label for="slFB" class="col-sm-2 col-form-label">Xin FB</label>
                                    <div class="col-sm-4">
                                        <select class="form-control" id="slVps">
                                            <option>...</option>
                                            <option>Chưa lấy</option>
                                            <option>Đã lấy</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="txtId" class="col-sm-2 col-form-label">ID</label>
                                    <div class="col-sm-4">
                                        <input type="text" class="form-control" id="txtId" placeholder="ID">
                                    </div>
                                    <label for="slEbayStatus" class="col-sm-2 col-form-label">Ebay</label>
                                    <div class="col-sm-4">
                                        <select class="form-control" id="slEbayStatus">
                                            <option>...</option>
                                            <option>Đã up</option>
                                            <option>Chưa up</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="card-header">
                    <div class="card-tools">
                        <a href="{{route('orders.editForm',['productCate'=>$productCate,'id'=>0])}}" title="Thêm mới đơn hàng">
                            <div class="btn btn-sm btn-primary"><i class="fa fa-plus-square"></i> Thêm mới</div>
                        </a>
                    </div>
                </div>
                <div class="card-body table-responsive p-0">
                    <table class="table table-striped projects">
                        <thead>
                        <tr>
                            <th style="width: 1%">
                                #
                            </th>
                            <th>
                                Name
                            </th>
                            <th>
                                Info
                            </th>
                            <th>
                                Time
                            </th>
                            <th style="width: 20%">
                            </th>
                        </tr>
                        </thead>
                        <tbody>

                        @foreach($orders as $item_order)
                            <?php
                            $userCr = $users->where('id', $item_order->createBy)->first();
                            $userUp = $users->where('id', $item_order->updateBy)->first();
                            ?>
                            <tr/>
                            <td>
                                {{$index++}}
                            </td>
                            <td>
                                <span>{{$cate->name}}</span>
                            </td>
                            <td>
                                <p>Sizes: <b>{{$cate->sizes}}</b></p>
                                <p>Colors: <b>{{$cate->colors}}</b></p>
                                <p>Price Range: <b>{{$cate->priceMin}}$ - {{$cate->priceMax}}$</b></p>
                                <p>Keywords: <b>{{$cate->keyword}}</b></p>
                            </td>
                            <td>
                                <p>Create:
                                    @if($userCr)
                                        <b>{{$userCr->fullName}}</b>  {{ \Carbon\Carbon::parse($cate->created_at)->timezone('Asia/Ho_Chi_Minh')->format('d-m-y H:i:s')}}
                                    @endif
                                </p>
                                <p>Update:
                                    @if($userUp)
                                        <b>{{$userUp->fullName}}</b> {{  \Carbon\Carbon::parse($cate->updated_at)->timezone('Asia/Ho_Chi_Minh')->format('d-m-y H:i:s')}}
                                    @endif
                                </p>
                            </td>
                            <td class="project-actions text-right">
                                <a class="btn btn-default btn-sm text-info openPopup" href="#"
                                   data-href="{{route('product-cates.edit',['product_cate'=>$cate->id])}}"
                                   data-width="800px" title="Sửa chuyên mục sản phẩm">
                                    <i class="fas fa-pencil-alt">
                                    </i>
                                </a>
                                {!! Form::open(['method' => 'DELETE','route' => ['product-cates.destroy', $cate->id],'id' => 'delete-form'.$cate->id, 'style' =>'display: inline-block;']) !!}
                                {{ Form::button('<i class="fas fa-trash"></i>', ['type' => 'submit', 'class' => 'btn btn-default btn-sm text-danger', 'onclick' => 'return confirmDelete(event,"delete-form'.$cate->id.'");']) }}
                                {!! Form::close() !!}
                            </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
                <!-- /.card-body -->
                <div class="card-footer">
                    {{ $orders->links('pagination::bootstrap-4', ['link_limit' => 3]) }}</div>
            </div>
            <!-- /.card -->

        </section>
        <!-- /.content -->
    </div>
@stop
@section('footer')

    <!-- Select2 -->
    <script src="{{asset('plugins/select2/js/select2.full.min.js')}}"></script>
    <script>
        function confirmDelete(event, id) {
            event.preventDefault();
            if (confirm('Bạn có chắc chắn xóa đơn này không?')) {
                document.getElementById(id).submit();
            }
        }

        function submitForm() {
            document.forms['myForm'].submit();
        }
    </script>
    <script>
        $(function () {
            //Initialize Select2 Elements
            $('.select2').select2();
            if($(".select2-auto").length){
                $(".select2-auto").each(function () {
                    var title = $(this).attr('title');
                    var url =  $(this).attr('data-href');
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
        });
    </script>
@endsection
