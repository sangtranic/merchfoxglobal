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
        {{ Form::hidden('password', $user->password) }}
        {{ Form::hidden('createBy', '1') }}
        {{ Form::hidden('updateBy', '1') }}
		<div class="mb-3">
			{{ Form::label('userName', 'UserName', ['class'=>'form-label']) }}
			{{ Form::text('userName', null, array('class' => 'form-control', 'readonly' => true)) }}
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
            {{ Form::label('statusId', 'Trạng thái', ['class'=>'form-label']) }}
            {{ Form::select('statusId', $listStatus, null, ['class' => 'form-control']) }}
        </div>
        <div class="mb-3">
            {{ Form::label('roleId', 'Quyền', ['class'=>'form-label']) }}
            {{ Form::select('roleId', $listRole, null, ['class' => 'form-control']) }}
        </div>
{{--		<div class="mb-3">--}}
{{--			{{ Form::label('statusId', 'StatusId', ['class'=>'form-label']) }}--}}
{{--			{{ Form::string('statusId', null, array('class' => 'form-control')) }}--}}
{{--		</div>--}}
{{--		<div class="mb-3">--}}
{{--			{{ Form::label('roleId', 'RoleId', ['class'=>'form-label']) }}--}}
{{--			{{ Form::string('roleId', null, array('class' => 'form-control')) }}--}}
{{--		</div>--}}

		{{ Form::submit('Edit', array('class' => 'btn btn-primary')) }}

	{{ Form::close() }}
@stop
