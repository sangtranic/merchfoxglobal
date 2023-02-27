@extends('layouts.app')
@section('title', 'Users')
@section('content')

    <div class="content-wrapper">
        <div class="content-header">
            <div class="container">
                <div class="row mb-2">
                    <div class="col-sm-12">
                        <h1 class="m-0">Danh sách Seller</h1>
                    </div><!-- /.col -->

                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <!-- Main content -->
        <section class="content">
            <!-- Default box -->
            <div class="card">
                <div class="card-body table-responsive form-filter">
                    <div class="row card-header">
                        <div class="col-md-6">
                            {!! Form::open(['route' => 'users.index', 'method' => 'GET','class' => 'form-group row','name'=>'myForm']) !!}
                            {!! Form::label('status', 'Trạng thái:', ['class' => 'col-sm-2 col-form-label text-right']) !!}
                            <div class="col-sm-4">
                                {!! Form::select('status', $listStatusPluck, request('status'), ['class' => 'form-control','onchange' => 'submitForm()']) !!}
                            </div>
                            {!! Form::label('role', 'Quyền:', ['class' => 'col-sm-2 col-form-label text-right']) !!}
                            <div class="col-sm-4">
                                {!! Form::select('role', $listRolePluck, request('role'), ['class' => 'form-control','onchange' => 'submitForm()']) !!}
                            </div>
{{--                            <div class="col-sm-2">--}}
{{--                                {!! Form::submit('Filter', ['class' => 'btn btn-primary']) !!}--}}
{{--                            </div>--}}

                            {!! Form::close() !!}
                        </div>
                        <div class="col-md-6">
                            <div class="text-right">
                                <div class="card-tools">
                                    <a href="{{ route('export-to-csv') }}" class="btn btn-info"><div class="btn btn-sm btn-primary"><i class="fa fa-plus-square"></i> Xuất Exel</div></a>
                                    <a href="{{ route('users.create') }}" class="btn btn-info"><div class="btn btn-sm btn-primary"><i class="fa fa-plus-square"></i> Thêm mới</div></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card-body table-responsive p-0">
                    <table class="table table-striped projects">
                        <thead>
                        <tr>
                            <th>Id</th>
                            <th>UserName</th>
                            <th>Họ và tên</th>
                            <th>Email</th>
                            <th>Số điện thoại</th>
                            <th>Trạng thái</th>
                            <th>Quyền</th>
                            <th>Chức năng</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($users as $user)
                            <?php
                            $status = $listStatus->where('id', $user->statusId)->first();
                            $statusName = $status ? $status['name'] : '';
                            $statusClass = '';
                            if($status)
                            {
                                if($status['id']==1)
                                {
                                    $statusClass = 'text-yellow';
                                }else if($status['id']==2)
                                {
                                    $statusClass = 'text-red';
                                }else
                                {
                                    $statusClass = 'text-green';
                                }
                            }

                            $role = $listRole->where('id', $user->roleId)->first();
                            $roleName = $role ? $role['name'] : '';
                            ?>
                            <tr>
                                <td>{{ $user->id }}</td>
                                <td>{{ $user->userName }}</td>
                                <td>{{ $user->fullName }}</td>
                                <td>{{ $user->email }}</td>
                                <td>{{ $user->mobile }}</td>
                                <td><span class="{{ $statusClass }}">{{ $statusName }}</span></td>
                                <td><span class="badge bg-primary">{{ $roleName }}</span></td>

                                <td>
                                    <div class="d-flex gap-2">
{{--                                        <a href="{{ route('users.changepassword', [$user->id]) }}" class="btn btn-info">Đổi mật khẩu</a>--}}
{{--                                        <a href="{{ route('users.edit', [$user->id]) }}" class="btn btn-primary">Sửa</a>--}}
{{--                                        {!! Form::open(['method' => 'DELETE','route' => ['users.destroy', $user->id],'id' => 'delete-form'.$user->id]) !!}--}}
{{--                                        {{ Form::submit('Xóa', ['class' => 'btn btn-danger', 'onclick' => 'return confirmDelete(event,"delete-form'.$user->id.'");']) }}--}}
{{--                                        --}}{{--                                {!! Form::submit('Delete', ['class' => 'btn btn-danger']) !!}--}}
{{--                                        {!! Form::close() !!}--}}

                                        <a class="btn btn-default btn-sm text-info" title="Đổi mật khẩu" href="{{ route('users.changepassword', [$user->id]) }}">
                                            <i class="fas fa-user-shield"></i>
                                        </a>
                                        <a class="btn btn-default btn-sm text-info" title="Sửa" href="{{ route('users.edit', [$user->id]) }}">
                                            <i class="fas fa-pencil-alt"></i>
                                        </a>
                                        {!! Form::open(['method' => 'DELETE','route' => ['users.destroy', $user->id],'id' => 'delete-form'.$user->id, 'style' =>'display: inline-block;']) !!}
                                        {{ Form::button('<i class="fas fa-trash"></i>', ['type' => 'submit', 'class' => 'btn btn-default btn-sm text-danger', 'onclick' => 'return confirmDelete(event,"delete-form'.$user->id.'");']) }}
                                        {!! Form::close() !!}
                                    </div>
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


{{--	<div class="d-flex justify-content-end mb-3"><a href="{{ route('users.create') }}" class="btn btn-info">Create</a></div>--}}

{{--	<table class="table table-bordered">--}}
{{--		<thead>--}}
{{--			<tr>--}}
{{--				<th>Id</th>--}}
{{--				<th>UserName</th>--}}
{{--				<th>Họ và tên</th>--}}
{{--				<th>Email</th>--}}
{{--				<th>Số điện thoại</th>--}}
{{--				<th>Trạng thái</th>--}}
{{--				<th>Quyền</th>--}}
{{--				<th>Chức năng</th>--}}
{{--			</tr>--}}
{{--		</thead>--}}
{{--		<tbody>--}}
{{--			@foreach($users as $user)--}}
{{--                <?php--}}
{{--                    $status = $listStatus->where('id', $user->statusId)->first();--}}
{{--                    $statusName = $status ? $status['name'] : '';--}}

{{--                    $role = $listRole->where('id', $user->roleId)->first();--}}
{{--                    $roleName = $role ? $role['name'] : '';--}}
{{--                ?>--}}
{{--				<tr>--}}
{{--					<td>{{ $user->id }}</td>--}}
{{--					<td>{{ $user->userName }}</td>--}}
{{--					<td>{{ $user->fullName }}</td>--}}
{{--					<td>{{ $user->email }}</td>--}}
{{--					<td>{{ $user->mobile }}</td>--}}
{{--					<td>{{ $statusName }}</td>--}}
{{--					<td>{{ $roleName }}</td>--}}

{{--					<td>--}}
{{--						<div class="d-flex gap-2">--}}
{{--                            <a href="{{ route('users.changepassword', [$user->id]) }}" class="btn btn-info">Đổi mật khẩu</a>--}}
{{--                            <a href="{{ route('users.edit', [$user->id]) }}" class="btn btn-primary">Sửa</a>--}}
{{--                            {!! Form::open(['method' => 'DELETE','route' => ['users.destroy', $user->id],'id' => 'delete-form'.$user->id]) !!}--}}
{{--                                {{ Form::submit('Xóa', ['class' => 'btn btn-danger', 'onclick' => 'return confirmDelete(event,"delete-form'.$user->id.'");']) }}--}}
{{--                                {!! Form::submit('Delete', ['class' => 'btn btn-danger']) !!}--}}
{{--                            {!! Form::close() !!}--}}
{{--                        </div>--}}
{{--					</td>--}}
{{--				</tr>--}}

{{--			@endforeach--}}
{{--		</tbody>--}}
{{--	</table>--}}
    <script>
        function confirmDelete(event,id) {
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
