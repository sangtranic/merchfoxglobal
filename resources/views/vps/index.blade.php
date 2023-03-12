@extends('layouts.app')
@section('title', 'Vps')
@section('content')

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <div class="content-header">
            <div class="container">
                <div class="row mb-2">
                    <div class="col-sm-12">
                        <h1 class="m-0">Danh sách máy chủ (VPS) </h1>
                    </div>
                </div>
            </div>
        </div>
        <!-- Main content -->
        <section class="content">
            <!-- Default box -->
            <div class="card">
                <div class="card-body table-responsive form-filter">
                    <div class="row card-header">
                        <div class="col-md-6">
                            {!! Form::open(['route' => 'vps.index', 'method' => 'GET','class' => 'form-group row','name'=>'myForm']) !!}
                            {!! Form::label('user', 'Tài khoản:', ['class' => 'col-sm-2 col-form-label text-right']) !!}
                            <div class="col-sm-4">
                                {!! Form::select('userId', $listUserPluck, request('userId'), ['class' => 'form-control','onchange' => 'submitForm()']) !!}
                            </div>
                            {!! Form::close() !!}
                        </div>
                        <div class="col-md-6">
                            <div class="text-right">
                                <div class="card-tools">
                                    <a href="{{ route('vps.create') }}" class="btn btn-info"><div class="btn btn-sm btn-primary"><i class="fa fa-plus-square"></i> Thêm mới</div></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
{{--                <div class="card-header">--}}
{{--                    <div class="card-tools">--}}
{{--                        <a href="{{ route('vps.create') }}" class="btn btn-info"><div class="btn btn-sm btn-primary"><i class="fa fa-plus-square"></i> Thêm mới</div></a>--}}
{{--                    </div>--}}
{{--                </div>--}}
                <div class="card-body table-responsive p-0">
                    <table class="table table-striped projects">
                        <thead>
                        <tr>
                            <th>id</th>
                            <th>Tên</th>
                            <th>Mô tả</th>
                            <th>Tài khoản</th>
                            <th>Seller</th>
                            <th style="width: 10%" class="text-center">Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($listVps as $vps)
                            <?php
                            $user = $listUser->where('id', $vps->userId)->first();
                            $userName = $user ? $user['userName'] : '';
                            $seller = $listSeller->where('id', $vps->sellerId)->first();
                            $sellerName = $seller ? $seller['sellerName'] : '';
                            ?>
                            <tr>
                                <td>{{ $vps->id }}</td>
                                <td><span>{{ $vps->name }}</span></td>
                                <td>{{ $vps->description }}</td>
                                <td>{{ $userName }}</td>
                                <td>{{ $sellerName }}</td>
                                <td class="project-actions text-center">

                                    <a class="btn btn-default btn-sm text-info" title="Edit" href="{{ route('vps.edit', [$vps->id]) }}">
                                        <i class="fas fa-pencil-alt"></i>
                                    </a>
                                    {!! Form::open(['method' => 'DELETE','route' => ['vps.destroy', $vps->id],'id' => 'delete-form'.$vps->id, 'style' =>'display: inline-block;']) !!}
{{--                                    {{ Form::submit('<i class="fas fa-trash"></i>', ['class' => 'btn btn-default btn-sm text-danger', 'onclick' => 'return confirmDelete(event,"delete-form'.$vps->id.'");']) }}--}}
                                    {{ Form::button('<i class="fas fa-trash"></i>', ['type' => 'submit', 'class' => 'btn btn-default btn-sm text-danger', 'onclick' => 'return confirmDelete(event,"delete-form'.$vps->id.'");']) }}
                                    {!! Form::close() !!}

                                </td>
                            </tr>

                        @endforeach
                    </table>
                </div>
            </div>
            <!-- /.card -->

        </section>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->

    <script>
        function confirmDelete(event,id) {
            event.preventDefault();
            if (confirm('Bạn có chắc chắn xóa VPS này không?')) {
                document.getElementById(id).submit();
            }
        }
        function submitForm() {
            document.forms['myForm'].submit();
        }
    </script>
@stop
