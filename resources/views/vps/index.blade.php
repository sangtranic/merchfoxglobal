@extends('layouts.app')
@section('title', 'Vps')
@section('content')


    <div class="d-flex justify-content-end mb-3"><a href="{{ route('vps.create') }}" class="btn btn-info">Create</a></div>

	<table class="table table-bordered">
		<thead>
			<tr>
				<th>id</th>
				<th>Tên</th>
				<th>Mô tả</th>
                <th>Seller</th>
				<th>Action</th>
			</tr>
		</thead>
		<tbody>
			@foreach($listVps as $vps)
                <?php
                $user = $listUser->where('id', $vps->userId)->first();
                $userName = $user ? $user['userName'] : '';

                ?>
				<tr>
					<td>{{ $vps->id }}</td>
					<td>{{ $vps->name }}</td>
					<td>{{ $vps->description }}</td>
                    <td>{{ $userName }}</td>
					<td>
						<div class="d-flex gap-2">
{{--                            <a href="{{ route('vps.show', [$vps->id]) }}" class="btn btn-info">Show</a>--}}
                            <a href="{{ route('vps.edit', [$vps->id]) }}" class="btn btn-primary">Edit</a>
                            {!! Form::open(['method' => 'DELETE','route' => ['vps.destroy', $vps->id],'id' => 'delete-form'.$vps->id]) !!}
                            {{ Form::submit('Xóa', ['class' => 'btn btn-danger', 'onclick' => 'return confirmDelete(event,"delete-form'.$vps->id.'");']) }}
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
            if (confirm('Bạn có chắc chắn xóa VPS này không?')) {
                document.getElementById(id).submit();
            }
        }
    </script>
@stop
