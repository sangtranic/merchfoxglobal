@extends('layouts.app')
@section('title', 'Danh sách sản phẩm')
@section('content')
    <?php use App\Helper\Helper;$index = 1;?>
    <div class="content-wrapper">
        <section class="content  mt-2">
            <!-- Default box -->
            <div class="card">
                <div class="card-body table-responsive form-filter">
                    <form action="{{ route('products.search') }}" method="GET">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group row">
                                    <label for="productCate" class="col-sm-4 col-form-label">Chuyên mục</label>
                                    <div class="col-sm-8">
                                        <select class="form-control" name="productCate" id="productCate"  onchange="this.form.submit()" >
                                            <option value="">Tất cả</option>
                                            @foreach ($productCates as $cate)
                                                <option value="{{ $cate->id }}" {{ $productCate == $cate->id ? 'selected' : '' }}>{{ $cate->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group row">
                                    <label for="user" class="col-sm-3 col-form-label">Người tạo</label>
                                    <div class="col-sm-9">
                                        <select class="form-control" name="user" id="user" onchange="this.form.submit()" >
                                            <option value="">Tất cả</option>
                                            @foreach ($users as $item_user)
                                                <option value="{{ $item_user->id }}" {{ $user == $item_user->id ? 'selected' : '' }}>{{ $item_user->fullName }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group row">
                                    <label for="txtCustomer" class="col-sm-3 col-form-label">Từ khóa</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control enter-submit" id="search" value="{{$search}}" name="search" placeholder="Tên sản phẩm">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group row">
                                    <div class="col-sm-6">
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input" name="isFileDesign" id="isFileDesign"  onchange="this.form.submit()"  {{ $isFileDesign ? 'checked' : '' }}>
                                            <label class="form-check-label" for="isFileDesign">Chưa có File thiết kế</label>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <button type="submit" class="btn btn-sm btn-default"><i class="fa fa-search"></i> Tìm kiếm</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="card-header">
                    <div class="card-tools">
                        <a class="openPopup" data-href="{{route('products.edit',['product'=>0])}}"
                           data-width="800px" title="Thêm mới sản phẩm">
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
                            <th style="width: 100px">

                            </th>
                            <th>
                                Name
                            </th>
                            <th>
                                Category
                            </th>
                            <th>
                                File Design
                            </th>
                            <th>
                                Color
                            </th>
                            <th>
                                Time
                            </th>
                            <th style="width: 20%">
                            </th>
                        </tr>
                        </thead>
                        <tbody>

                        @foreach($products as $product)
                            <?php
                            $userCr = $users->where('id', $product->createBy)->first();
                            $userUp = $users->where('id', $product->updateBy)->first();
                            $cate = $productCates->where('id', $product->categoryId)->first();
                            ?>
                            <tr/>
                            <td>
                                {{$index++}}
                            </td>
                            <td>
                                @if(\App\Helper\Helper::IsNullOrEmptyString($product->urlImagePreviewOne) == false)
                                    <img width="100%" src="{{$product->urlImagePreviewOne}}" style="max-height: 100px">
                                @endif
                            </td>
                            <td>
                                <?php if(!Helper::IsNullOrEmptyString($product->itemId)){?>
                                    <a href="https://www.ebay.com/itm/{{$product->itemId}}" target="_blank" title="{{$product->name}}">{{$product->name}}</a>
                                <?php }else if(!Helper::IsNullOrEmptyString($product->url)){?>
                                    <a href="{{$product->url}}" target="_blank" title="{{$product->name}}">{{$product->name}}</a>
                                <?php }else{ ?>
                                    <span>{{$product->name}}</span>
                                <?php } ?>
                                <p>{{$product->itemId}}</p>
                            </td>
                            <td>
                                <p>{{$cate->name}}</b></p>
                            </td>
                            <td>
                                {!! Form::checkbox('isFileDesign'.$product->id, 1, $product->isFileDesign,["disabled"=>true]) !!}
                            </td>
                            <td>
                                {{$product->color}}
                            </td>
                            <td>
                                <p>Create:
                                    @if($userCr)
                                        <b>{{$userCr->fullName}}</b>  {{ \Carbon\Carbon::parse($product->created_at)->timezone('Asia/Ho_Chi_Minh')->format('d-m-y H:i:s')}}
                                    @endif
                                </p>
                                <p>Update:
                                    @if($userUp)
                                        <b>{{$userUp->fullName}}</b> {{  \Carbon\Carbon::parse($product->updated_at)->timezone('Asia/Ho_Chi_Minh')->format('d-m-y H:i:s')}}
                                    @endif
                                </p>
                            </td>
                            <td class="project-actions text-right">
                                <a class="btn btn-default btn-sm text-info openPopup" href="javascript:void(0)"
                                   data-href="{{route('products.edit',['product'=>$product->id])}}"
                                   data-width="800px" title="Sửa sản phẩm">
                                    <i class="fas fa-pencil-alt">
                                    </i>
                                </a>
                                {!! Form::open(['method' => 'DELETE','route' => ['products.destroy', $product->id],'id' => 'delete-form'.$product->id, 'style' =>'display: inline-block;']) !!}
                                {{ Form::button('<i class="fas fa-trash"></i>', ['type' => 'submit', 'class' => 'btn btn-default btn-sm text-danger', 'onclick' => 'return confirmDelete(event,"delete-form'.$product->id.'");']) }}
                                {!! Form::close() !!}
                            </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
                <!-- /.card-body -->
                <div class="card-footer">
                    {{ $products->appends(request()->query())->links('pagination::bootstrap-4', ['link_limit' => 3]) }}</div>
            </div>
            <!-- /.card -->

        </section>
        <!-- /.content -->
    </div>
    <script>
        function confirmDelete(event, id) {
            event.preventDefault();
            if (confirm('Bạn có chắc chắn xóa sản phẩm này không?')) {
                document.getElementById(id).submit();
            }
        }

        function submitForm() {
            document.forms['myForm'].submit();
        }
        if($('form .enter-submit').length){
            $('form .enter-submit').on('keyup', function(e) {
                if(e.which == 13) {
                    $(this).closest('form')[0].submit();
                }
            })
        }
    </script>
@stop
