@extends('layouts.app')
@section('title', 'VPS')
@section('content')

	@if($errors->any())
		<div class="alert alert-danger">
			@foreach ($errors->all() as $error)
				{{ $error }} <br>
			@endforeach
		</div>
	@endif

	{!! Form::open(['route' => 'vps.store']) !!}

		<div class="mb-3">
			{{ Form::label('name', 'Name', ['class'=>'form-label']) }}
			{{ Form::text('name', null, array('class' => 'form-control')) }}
		</div>
		<div class="mb-3">
			{{ Form::label('description', 'Mô tả', ['class'=>'form-label']) }}
			{{ Form::text('description', null, array('class' => 'form-control')) }}
		</div>
        <div class="mb-3">
            {{ Form::label('userId', 'Seller', ['class'=>'form-label']) }}
            {{ Form::select('userId', $listUser, null, ['class' => 'form-control']) }}
        </div>

		{{ Form::submit('Create', array('class' => 'btn btn-primary')) }}

	{{ Form::close() }}


@stop
