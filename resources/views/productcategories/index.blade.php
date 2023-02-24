@extends('layouts.app')
@section('title', 'Chuyên mục sản phẩm')
@section('content')
    <?php $index = 1;?>
    <div class="content-wrapper">
        <div class="content-header">
            <div class="container">
                <div class="row mb-2">
                    <div class="col-sm-12">
                        <h1 class="m-0">Chuyên mục sản phẩm </h1>
                    </div><!-- /.col -->

                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <!-- Main content -->
        <section class="content">
            <!-- Default box -->
            <div class="card">
                <div class="card-header">
                    <div class="card-tools">
                        <a class="openPopup" data-href="{{route('product-cates.edit',['product_cate'=>0])}}" data-width="800px" title="Thêm mới chuyên mục sản phẩm">
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

                        @foreach($productCates as $cate)
                            <?php
                            $userCr = $users->where('id', $cate->createBy)->first();
                            $userUp = $users->where('id', $cate->updateBy)->first();
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
                                <a class="btn btn-default btn-sm text-info openPopup" href="#" data-href="{{route('product-cates.edit',['product_cate'=>$cate->id])}}" data-width="800px" title="Sửa chuyên mục sản phẩm">
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
                    {{ $productCates->links('pagination::bootstrap-4', ['link_limit' => 3]) }}</div>
            </div>
            <!-- /.card -->

        </section>
        <!-- /.content -->
    </div>
    <script>
        function confirmDelete(event, id) {
            event.preventDefault();
            if (confirm('Bạn có chắc chắn xóa tài khoản này không?')) {
                document.getElementById(id).submit();
            }
        }

        function submitForm() {
            document.forms['myForm'].submit();
        }
    </script>
@stop
