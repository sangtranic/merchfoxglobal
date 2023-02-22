@extends('default')

@section('content')

	<div class="d-flex justify-content-end mb-3"><a href="{{ route('roles.create') }}" class="btn btn-info">Create</a></div>

	<table class="table table-bordered">
		<thead>
			<tr>
				<th>id</th>
				<th>name</th>
				<th>code</th>

				<th>Action</th>
			</tr>
		</thead>
		<tbody>
			@foreach($roles as $role)

				<tr>
					<td>{{ $role->id }}</td>
					<td>{{ $role->name }}</td>
					<td>{{ $role->code }}</td>

					<td>
						<div class="d-flex gap-2">
                            <a href="{{ route('roles.show', [$role->id]) }}" class="btn btn-info">Show</a>
                            <a href="{{ route('roles.edit', [$role->id]) }}" class="btn btn-primary">Edit</a>
                            {!! Form::open(['method' => 'DELETE','route' => ['roles.destroy', $role->id]]) !!}
                                {!! Form::submit('Delete', ['class' => 'btn btn-danger']) !!}
                            {!! Form::close() !!}
                        </div>
					</td>
				</tr>

			@endforeach
		</tbody>
	</table>

@stop
