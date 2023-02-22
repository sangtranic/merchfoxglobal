@extends('default')

@section('content')

	@if($errors->any())
		<div class="alert alert-danger">
			@foreach ($errors->all() as $error)
				{{ $error }} <br>
			@endforeach
		</div>
	@endif
	{!! Form::open(['route' => 'users.store']) !!}
        {{ Form::hidden('createBy', '1') }}
        {{ Form::hidden('updateBy', '1') }}
		<div class="mb-3">
			{{ Form::label('userName', 'UserName', ['class'=>'form-label']) }}
			{{ Form::text('userName', null, array('class' => 'form-control')) }}
		</div>
{{--		<div class="mb-3">--}}
{{--			{{ Form::label('password', 'Password', ['class'=>'form-label']) }}--}}
{{--			{{ Form::text('password', null, array('class' => 'form-control')) }}--}}
{{--		</div>--}}
        <div class="mb-3">
            {{ Form::label('password', 'Password', ['class'=>'form-label']) }}
            {{ Form::password('password', ['class' => 'form-control', 'placeholder' => 'Password', 'required' => true, 'minlength' => 8]) }}
            {{ $errors->first('password', '<span class="error">:message</span>') }}
        </div>

{{--        <div class="mb-3">--}}
{{--            {{ Form::label('password_confirmation', 'Confirm Password', ['class'=>'form-label']) }}--}}
{{--            {{ Form::password('password_confirmation', ['class' => 'form-control', 'placeholder' => 'Confirm Password', 'required' => true, 'minlength' => 8]) }}--}}
{{--            {{ $errors->first('password_confirmation', '<span class="error">::message</span>') }}--}}
{{--        </div>--}}
        <div class="mb-3">
            {{ Form::label('fullName', 'FullName', ['class'=>'form-label']) }}
			{{ Form::text('fullName', null, array('class' => 'form-control')) }}
		</div>
        <div class="mb-3">
            {{ Form::label('email', 'Email') }}
            {{ Form::text('email', null, ['class' => 'form-control', 'placeholder' => 'Email', 'required' => true, 'pattern' => '[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$']) }}
            {{ $errors->first('email', '<span class="error">Địa chỉ email không đúng định dạng</span>') }}
        </div>
{{--		<div class="mb-3">--}}
{{--			{{ Form::label('email', 'Email', ['class'=>'form-label']) }}--}}
{{--			{{ Form::text('email', null, array('class' => 'form-control')) }}--}}
{{--		</div>--}}
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
{{--			{{ Form::string('roleId', null, array('class' => 'form-control')) }}--}}
		</div>
		{{ Form::submit('Create', array('class' => 'btn btn-primary')) }}

	{{ Form::close() }}


@stop
