@extends('default')

@section('content')

	<div class="d-flex justify-content-end mb-3"><a href="{{ route('users.create') }}" class="btn btn-info">Create</a></div>

	<table class="table table-bordered">
		<thead>
			<tr>
				<th>id</th>
				<th>userName</th>
				<th>password</th>
				<th>fullName</th>
				<th>email</th>
				<th>mobile</th>
				<th>statusId</th>
				<th>roleId</th>

				<th>Action</th>
			</tr>
		</thead>
		<tbody>
			@foreach($users as $user)

				<tr>
					<td>{{ $user->id }}</td>
					<td>{{ $user->userName }}</td>
					<td>{{ $user->password }}</td>
					<td>{{ $user->fullName }}</td>
					<td>{{ $user->email }}</td>
					<td>{{ $user->mobile }}</td>
					<td>{{ $user->statusId }}</td>
					<td>{{ $user->roleId }}</td>

					<td>
						<div class="d-flex gap-2">
                            <a href="{{ route('users.show', [$user->id]) }}" class="btn btn-info">Show</a>
                            <a href="{{ route('users.edit', [$user->id]) }}" class="btn btn-primary">Edit</a>
                            {!! Form::open(['method' => 'DELETE','route' => ['users.destroy', $user->id]]) !!}
                                {!! Form::submit('Delete', ['class' => 'btn btn-danger']) !!}
                            {!! Form::close() !!}
                        </div>
					</td>
				</tr>

			@endforeach
		</tbody>
	</table>

@stop
