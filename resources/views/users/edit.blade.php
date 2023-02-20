@extends('default')

@section('content')

	@if($errors->any())
		<div class="alert alert-danger">
			@foreach ($errors->all() as $error)
				{{ $error }} <br>
			@endforeach
		</div>
	@endif

	{{ Form::model($user, array('route' => array('users.update', $user->id), 'method' => 'PUT')) }}

		<div class="mb-3">
			{{ Form::label('userName', 'UserName', ['class'=>'form-label']) }}
			{{ Form::text('userName', null, array('class' => 'form-control')) }}
		</div>
		<div class="mb-3">
			{{ Form::label('password', 'Password', ['class'=>'form-label']) }}
			{{ Form::text('password', null, array('class' => 'form-control')) }}
		</div>
		<div class="mb-3">
			{{ Form::label('fullName', 'FullName', ['class'=>'form-label']) }}
			{{ Form::text('fullName', null, array('class' => 'form-control')) }}
		</div>
		<div class="mb-3">
			{{ Form::label('email', 'Email', ['class'=>'form-label']) }}
			{{ Form::text('email', null, array('class' => 'form-control')) }}
		</div>
		<div class="mb-3">
			{{ Form::label('mobile', 'Mobile', ['class'=>'form-label']) }}
			{{ Form::text('mobile', null, array('class' => 'form-control')) }}
		</div>
		<div class="mb-3">
			{{ Form::label('statusId', 'StatusId', ['class'=>'form-label']) }}
			{{ Form::string('statusId', null, array('class' => 'form-control')) }}
		</div>
		<div class="mb-3">
			{{ Form::label('roleId', 'RoleId', ['class'=>'form-label']) }}
			{{ Form::string('roleId', null, array('class' => 'form-control')) }}
		</div>

		{{ Form::submit('Edit', array('class' => 'btn btn-primary')) }}

	{{ Form::close() }}
@stop
