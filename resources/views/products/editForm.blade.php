@extends('layouts.appblank')
@section('title', 'Sản phẩm')
@section('content')
    @if(session('status'))
        <script>
            setTimeout(function () {
                $('#myModal .close', window.parent.document).click();
                @if(!\App\Helper\Helper::IsNullOrEmptyString($callBack))
                window.parent.callBackPopop({{session('productId')}});
                @endif
                @if(\App\Helper\Helper::IsNullOrEmptyString($callBack))
                    window.parent.document.location.reload();
                @endif
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
        @if($product->id == 0)
            {{ Form::model($product, array('route' => array('products.store'), 'method' => 'POST','enctype'=>'multipart/form-data')) }}
        @endif

        @if($product->id > 0)
            {{ Form::model($product, array('route' => array('products.update', $product->id), 'method' => 'PUT','enctype'=>'multipart/form-data')) }}
        @endif
        <div class="form-group row">
            {{ Form::label('name','Chuyên mục *',['class'=>'col-sm-3 col-form-label'])}}
            <div class="col-sm-9">
                {!! Form::select('categoryId', $productCates, null, ['class' => 'form-control']) !!}
            </div>
        </div>
        <div class="form-group row">
            {{ Form::label('name','Tên *',['class'=>'col-sm-3 col-form-label'])}}
            <div class="col-sm-9">
                {{ Form::text('name', null, array('class' => 'form-control')) }}
            </div>
        </div>
        <div class="form-group row">
            {{ Form::label('description','Mô tả',['class'=>'col-sm-3 col-form-label'])}}
            <div class="col-sm-9">
                {{ Form::textarea('description', null, array('class' => 'form-control', 'rows' => 2)) }}
            </div>
        </div>
        <div class="form-group row">
            {{ Form::label('url','Url',['class'=>'col-sm-3 col-form-label'])}}
            <div class="col-sm-9">
                {{ Form::text('url', null, array('class' => 'form-control')) }}
            </div>
        </div>
        <div class="form-group row">
            {{ Form::label('urlImagePreviewOne','Url Mặt trước',['class'=>'col-sm-3 col-form-label'])}}
            <div class="col-sm-9">
                {{ Form::text('urlImagePreviewOne', null, array('class' => 'form-control')) }}
            </div>
        </div>
        <div class="form-group row">
            {{ Form::label('urlImagePreviewTwo','Url Mặt sau',['class'=>'col-sm-3 col-form-label'])}}
            <div class="col-sm-9">
                {{ Form::text('urlImagePreviewTwo', null, array('class' => 'form-control')) }}
            </div>
        </div>

        <div class="form-group row">
            {{ Form::label('urlImageDesignOne','Thiết kế Mặt trước',['class'=>'col-sm-3 col-form-label'])}}
            <div class="col-sm-9">
                {{ Form::text('urlImageDesignOne', null, array('class' => 'form-control')) }}
                <div class="input-group mt-2">
                    <div class="custom-file">
                        <input type="file" class="custom-file-input" id="imageDesignOne" name="imageDesignOne">
                        <label class="custom-file-label" for="imageDesignOne">Choose file</label>
                    </div>
                </div>
            </div>
        </div>
        <div class="form-group row">
            {{ Form::label('urlImageDesignTwo','Thiết kế Mặt sau',['class'=>'col-sm-3 col-form-label'])}}
            <div class="col-sm-9">
                {{ Form::text('urlImageDesignTwo', null, array('class' => 'form-control')) }}
                <div class="input-group mt-2">
                    <div class="custom-file">
                        <input type="file" class="custom-file-input" id="imageDesignTwo" name="imageDesignTwo">
                        <label class="custom-file-label" for="imageDesignTwo">Choose file</label>
                    </div>
                </div>
            </div>
        </div>

        <div class="form-group row">
            {{ Form::label('isFileDesign','File Thiết kế',['class'=>'col-sm-3 col-form-label'])}}
            <div class="col-sm-9">
                {{ Form::checkbox('isFileDesign') }}
            </div>
        </div>
        <div class="form-group text-center">
            {{ Form::submit('Lưu', array('class' => 'btn btn-info')) }}
        </div>

        {{ Form::close() }}
    </div>
@stop
