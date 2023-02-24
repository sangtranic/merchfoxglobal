@extends('layouts.appblank')
@section('title', 'Chuyên mục sản phẩm')
@section('content')
    @if(session('status'))
        <script>
            setTimeout(function () {
                $('#myModal .close', window.parent.document).click();
                window.parent.document.location.reload();
            }, 1000);
        </script>
    @endif
    @if($errors->any())
        <div class="alert alert-danger">
            @foreach ($errors->all() as $error)
                {{ $error }} <br>
            @endforeach
        </div>
    @endif
    <div class="modal-body">
        @if($productCate->id == 0)
            {!! Form::open(['route' => 'product-cates.store']) !!}
        @endif

        @if($productCate->id > 0)
                {{ Form::model($productCate, array('route' => array('product-cates.update', $productCate->id), 'method' => 'PUT')) }}
        @endif

        <div class="form-group row">
            {{ Form::label('name','Tên *',['class'=>'col-sm-2 col-form-label'])}}
            <div class="col-sm-10">
                {{ Form::text('name', null, array('class' => 'form-control')) }}
            </div>
        </div>
        <div class="form-group row">
            {{ Form::label('description','Mô tả',['class'=>'col-sm-2 col-form-label'])}}
            <div class="col-sm-10">
                {{ Form::text('description', null, array('class' => 'form-control')) }}
            </div>
        </div>
        <div class="form-group row">
            {{ Form::label('sizes','Sizes',['class'=>'col-sm-2 col-form-label'])}}
            <div class="col-sm-10">
                {{ Form::text('sizes', null, array('class' => 'form-control')) }}
            </div>
        </div>
        <div class="form-group row">
            {{ Form::label('colors','Colors',['class'=>'col-sm-2 col-form-label'])}}
            <div class="col-sm-10">
                {{ Form::text('colors', null, array('class' => 'form-control')) }}
            </div>
        </div>
        <div class="form-group row">
            {{ Form::label('priceMin','Giá Min',['class'=>'col-sm-2 col-form-label'])}}
            <div class="col-sm-4">
                {{ Form::text('priceMin', null, array('class' => 'form-control')) }}
            </div>
            {{ Form::label('priceMax','Giá Max',['class'=>'col-sm-2 col-form-label'])}}
            <div class="col-sm-4">
                {{ Form::text('priceMax', null, array('class' => 'form-control')) }}
            </div>
        </div>
        <div class="form-group row">
            {{ Form::label('keyword','Từ khóa',['class'=>'col-sm-2 col-form-label'])}}
            <div class="col-sm-10">
                {{ Form::text('keyword', null, array('class' => 'form-control')) }}
            </div>
        </div>
        <div class="form-group text-center">
            {{ Form::submit('Lưu', array('class' => 'btn btn-info')) }}
        </div>

        {{ Form::close() }}
    </div>
@stop
