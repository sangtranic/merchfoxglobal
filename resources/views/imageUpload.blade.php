@extends('default')

@section('content')
<div class="container">

    <div class="panel panel-primary">

        <div class="panel-heading">
            <h2>Laravel 9 Image Upload Example - ItSolutionStuff.com</h2>
        </div>

        <div class="panel-body">

            @if ($message = Session::get('success'))
                <div class="alert alert-success alert-block">
                    <button type="button" class="close" data-dismiss="alert">×</button>
                    <strong>{{ $message }}</strong>
                </div>
                <img src="upload/original/{{ Session::get('image') }}">
            @endif

            <form action="{{ route('image.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="mb-3">
                    <label class="form-label" for="inputImage">Image:</label>
                    <input
                        type="file"
                        name="image"
                        id="inputImage"
                        class="form-control @error('image') is-invalid @enderror">

                    @error('image')
                    <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div class="mb-3">
                    <button type="submit" class="btn btn-success">Upload</button>
                </div>

            </form>
            <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script>
            <script src="https://code.jquery.com/ui/1.11.1/jquery-ui.min.js"></script>

            <link rel="stylesheet" href="https://code.jquery.com/ui/1.11.1/themes/smoothness/jquery-ui.css" />
            <script src="{{ asset('/Assets/js/merchfox.js') }}"></script>

        </div>
    </div>
</div>
