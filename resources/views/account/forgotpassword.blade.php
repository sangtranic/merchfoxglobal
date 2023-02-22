@extends('layouts.applogin')
@section('title', 'Forgot my password')
@section('content')

    <div class="login-box">
        <div class="login-logo">
            <a href="index.html"><b>Admin</b>SELLER</a>
        </div>
        <!-- /.login-logo -->
        <div class="card">
            <div class="card-body login-card-body">
                <p class="login-box-msg">You forgot your password? Here you can easily retrieve a new password.</p>
                @if ($errors)
                    <ul>
                        @foreach ($errors->all() as $error)
                            @foreach ($error->getMessages() as $messages)
                                @foreach ($messages as $message)
                                    <li><span class="text-danger">{{ $message }}</li>
                                @endforeach
                            @endforeach
                        @endforeach
                    </ul>
                @endif
                <form method="POST" action="{{ route('sendResetLinkEmail') }}">
                    @csrf
                    <div class="input-group mb-3">
                        <input type="email" name="email" id="email" required class="form-control" placeholder="Email">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-envelope"></span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary btn-block">Request new password</button>
                        </div>
                        <!-- /.col -->
                    </div>
                </form>
                <p class="mt-3 mb-1">
                    <a href="{{route('login')}}">Login</a>
                </p>
            </div>
            <!-- /.login-card-body -->
        </div>
    </div>
    <!-- /.login-box -->

@endsection
