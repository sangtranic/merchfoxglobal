@extends('layouts.app')
@section('title', 'Seller Insert')
@section('content')

	@if($errors->any())
		<div class="alert alert-danger">
			@foreach ($errors->all() as $error)
				{{ $error }} <br>
			@endforeach
		</div>
	@endif
    <div class="bg-light p-4 rounded">
        <h2>Thêm Seller</h2>
        <div class="container mt-4">
            {!! Form::open(['route' => 'seller.store']) !!}
            <div class="mb-3">
                {{ Form::label('sellerName', 'sellerName', ['class'=>'form-label']) }}
                {{ Form::text('sellerName', null, array('class' => 'form-control')) }}
            </div>
            <div class="mb-3">
                {{ Form::label('userId', 'Tài khoản', ['class'=>'form-label']) }}
                {{ Form::select('userId', $listUser, null, ['class' => 'form-control']) }}
            </div>
            {{ Form::submit('Create', array('class' => 'btn btn-primary')) }}
            <a href="{{ url()->previous() }}" class="btn btn-secondary">Back</a>
            {{ Form::close() }}
        </div>
    </div>
@stop
