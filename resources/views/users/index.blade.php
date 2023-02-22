@extends('default')

@section('content')

	<div class="d-flex justify-content-end mb-3"><a href="{{ route('users.create') }}" class="btn btn-info">Create</a></div>

	<table class="table table-bordered">
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

                    $role = $listRole->where('id', $user->roleId)->first();
                    $roleName = $role ? $role['name'] : '';
                ?>
				<tr>
					<td>{{ $user->id }}</td>
					<td>{{ $user->userName }}</td>
					<td>{{ $user->fullName }}</td>
					<td>{{ $user->email }}</td>
					<td>{{ $user->mobile }}</td>
					<td>{{ $statusName }}</td>
					<td>{{ $roleName }}</td>

					<td>
						<div class="d-flex gap-2">
                            <a href="{{ route('users.changepassword', [$user->id]) }}" class="btn btn-info">Đổi mật khẩu</a>
                            <a href="{{ route('users.edit', [$user->id]) }}" class="btn btn-primary">Sửa</a>
                            {!! Form::open(['method' => 'DELETE','route' => ['users.destroy', $user->id],'id' => 'delete-form'.$user->id]) !!}
                                {{ Form::submit('Xóa', ['class' => 'btn btn-danger', 'onclick' => 'return confirmDelete(event,"delete-form'.$user->id.'");']) }}
{{--                                {!! Form::submit('Delete', ['class' => 'btn btn-danger']) !!}--}}
                            {!! Form::close() !!}
                        </div>
					</td>
				</tr>

			@endforeach
		</tbody>
	</table>
    <script>
        function confirmDelete(event,id) {
            event.preventDefault();
            if (confirm('Bạn có chắc chắn xóa tài khoản này không?')) {
                document.getElementById(id).submit();
            }
        }
    </script>
@stop
