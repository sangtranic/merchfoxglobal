@extends('default')

@section('content')

    @if($errors->any())
        <div class="alert alert-danger">
            @foreach ($errors->all() as $error)
                {{ $error }} <br>
            @endforeach
        </div>
    @endif

    {{ Form::model($user, array('route' => array('users.updatepassword', $user->id), 'method' => 'PUT')) }}
    <div class="mb-3">
        {{ Form::label('password', 'Mật khẩu mới', ['class'=>'form-label']) }}
        {{ Form::password('password', ['class' => 'form-control', 'placeholder' => 'Password', 'required' => true, 'minlength' => 8]) }}
        {{ $errors->first('password', '<span class="error">:message</span>') }}
    </div>


    {{ Form::submit('Edit', array('class' => 'btn btn-primary')) }}

    {{ Form::close() }}
@stop
