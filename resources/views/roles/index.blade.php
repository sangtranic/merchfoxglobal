@extends('layouts.app')
@section('title', 'Quyền')
@section('content')
    <div class="content-wrapper">
        <div class="content-header">
            <div class="container">
                <div class="row mb-2">
                    <div class="col-sm-12">
                        <h1 class="m-0">Danh sách quyền truy cập</h1>
                    </div>
                </div>
            </div>
        </div>
        <!-- Main content -->
        <section class="content">
            <div class="card">
                <div class="card-header">
                    <div class="card-tools">
                        <a href="{{ route('roles.create') }}" class="btn btn-info"><div class="btn btn-sm btn-primary"><i class="fa fa-plus-square"></i> Thêm mới</div></a>
                    </div>
                </div>
                <div class="card-body table-responsive p-0">
                    <table class="table table-striped projects">
                        <thead>
                        <tr>
                            <th>id</th>
                            <th>name</th>
                            <th>code</th>
                            <th style="width: 10%" class="text-center">Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($roles as $role)
                            <tr>
                                <td>{{ $role->id }}</td>
                                <td>{{ $role->name }}</td>
                                <td>{{ $role->code }}</td>
                                <td class="project-actions text-center">
                                    <a class="btn btn-default btn-sm text-info" title="Edit" href="{{ route('roles.edit', [$role->id]) }}">
                                        <i class="fas fa-pencil-alt"></i>
                                    </a>
                                    {!! Form::open(['method' => 'DELETE','route' => ['roles.destroy', $role->id],'id' => 'delete-form'.$role->id, 'style' =>'display: inline-block;']) !!}
                                    {{ Form::button('<i class="fas fa-trash"></i>', ['type' => 'submit', 'class' => 'btn btn-default btn-sm text-danger', 'onclick' => 'return confirmDelete(event,"delete-form'.$role->id.'");']) }}
                                    {!! Form::close() !!}
                                </td>
                            </tr>
                        @endforeach
                    </table>
                </div>
            </div>
        </section>
    </div>
    <script>
        function confirmDelete(event,id) {
            event.preventDefault();
            if (confirm('Bạn có chắc chắn xóa VPS này không?')) {
                document.getElementById(id).submit();
            }
        }
    </script>
@stop
