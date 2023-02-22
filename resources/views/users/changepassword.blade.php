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
        @csrf
        {{ Form::hidden('id', $user->id) }}
        {{ Form::hidden('userName', $user->userName) }}
        {{ Form::hidden('fullName', $user->fullName) }}
        {{ Form::hidden('email', $user->email) }}
        {{ Form::hidden('mobile', $user->mobile) }}
        {{ Form::hidden('statusId', $user->statusId) }}
        {{ Form::hidden('roleId', $user->roleId) }}
        {{ Form::hidden('createBy', $user->createBy) }}
        {{ Form::hidden('updateBy', $user->updateBy) }}
        {{ Form::hidden('password', $user->updateBy) }}

            <div class="mb-3">
                {{ Form::label('password', 'Mật khẩu mới', ['class'=>'form-label']) }}
                {{ Form::password('newpassword', ['class' => 'form-control', 'placeholder' => 'Password', 'required' => true, 'minlength' => 8]) }}
                {{ $errors->first('newpassword', '<span class="error">:message</span>') }}
            </div>


            {{ Form::submit('Edit', array('class' => 'btn btn-primary')) }}

    {{ Form::close() }}
@stop
