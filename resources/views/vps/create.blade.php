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
                {{ Form::select('userId', $listUser, null, ['class' => 'form-control','id'=>'ddluser']) }}
            </div>
            <div class="mb-3">
                {{ Form::label('sellerId', 'Seller', ['class'=>'form-label']) }}
                {{ Form::select('sellerId', $listSeller, null, ['class' => 'form-control','id'=>'ddSeller']) }}
            </div>
            {{ Form::submit('Create', array('class' => 'btn btn-primary')) }}
            <a href="{{ url()->previous() }}" class="btn btn-secondary">Back</a>
            {{ Form::close() }}
        </div>
    </div>
@stop
@section('footer')
    <script>
        $(function () {
            $('#ddluser').change(function () {
                var userId = $(this).val();
                $.ajax({
                    url: '/getListSellerByUserId',
                    data: {'userId': userId},
                    type: 'GET',
                    dataType: 'json',
                    success: function (data) {
                        if(data.length)
                        {
                            var sellerSelect = $('#ddSeller');
                            sellerSelect.empty();
                            $.each(data, function (index, seller) {
                                sellerSelect.append('<option value="' + seller.id + '">' + seller.sellerName + '</option>');
                            });
                        }
                    }
                });
            });
        });
    </script>
@endsection
