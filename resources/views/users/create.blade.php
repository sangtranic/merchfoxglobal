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
		<div class="mb-3">
			{{ Form::label('password', 'Password', ['class'=>'form-label']) }}
			{{ Form::text('password', null, array('class' => 'form-control')) }}
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
			{{ Form::label('statusId', 'StatusId', ['class'=>'form-label']) }}
            {{ Form::select('statusId', $listStatus, null, ['class' => 'form-control']) }}
		</div>
		<div class="mb-3">
			{{ Form::label('roleId', 'RoleId', ['class'=>'form-label']) }}
            {{ Form::select('roleId', $listRole, null, ['class' => 'form-control']) }}
{{--			{{ Form::string('roleId', null, array('class' => 'form-control')) }}--}}
		</div>
{{--        <label for="image"><?php echo \Common\Languages::news()['imageNews'] ?>--}}
{{--            <a data-toggle="tooltip"--}}
{{--               title="<?php echo \Common\Languages::news()['uploadAvatar']; ?>"--}}
{{--               href="javascript:void(0)"--}}
{{--               onclick="news.openUpload()"--}}
{{--               style="margin-left: 10px;font-size: 22px;"><i--}}
{{--                    class="fa fa-file-image-o"></i></i>--}}
{{--            </a>--}}
{{--        </label>--}}
{{--        <div class="image" style="position: relative;text-align: center" data-width="200" data-height="150">--}}
{{--            <?php if (isset($data['image']) && !empty($data['image'])): ?>--}}
{{--            <img src="<?php echo IMG_URL . $data['image']; ?>" width="200px"--}}
{{--                 height="150px"/>--}}
{{--            <div class="avatar-remove-ico"><a title="Xóa"--}}
{{--                                              href="javascript:void(0)"--}}
{{--                                              onclick="news.removeAvatar(document.getElementById('image'))"><i--}}
{{--                        class="pointer fa fa-trash"></i> </a></div>--}}
{{--            <?php endif; ?>--}}
{{--        </div>--}}
{{--        <input type="text" style="width: 100%" id="image" name="image" value="<?php if (isset($data['image'])): echo $data['image']; endif; ?>">--}}
{{--        <?php if (isset($valid) && $valid->filter("image")) : ?>--}}
{{--        <span class="parsley-error"><?php echo $valid->filter("image")[0] ?></span>--}}
{{--        <?php endif; ?>--}}

		{{ Form::submit('Create', array('class' => 'btn btn-primary')) }}

	{{ Form::close() }}


@stop
