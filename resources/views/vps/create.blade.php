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
    <div class="bg-light p-4 rounded">
        <h2>Thêm VPS</h2>
        <div class="container mt-4">
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
            <a href="{{ url()->previous() }}" class="btn btn-secondary">Back</a>
            {{ Form::close() }}
        </div>
    </div>
@stop
