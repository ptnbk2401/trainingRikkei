@extends('layouts.app')
@section('css')
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <style>
    .btn-add {
        margin: 10px;
    }
    #example_grid1 td {
        white-space: nowrap;
    }
    </style>
@stop
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">Admin Home</div>
                <div class="card-body">
                    @if (session('msg'))
                        <div class="alert alert-success" role="alert">
                            {{ session('msg') }}
                        </div>
                    @endif
                </div>
                <div class="container-fluid">
                   
                </div>
            </div>
        </div>
    </div>
</div>

    <!-- AmiGridView -->
@endsection
@section('js')
<!-- AmiGridView -->
@stack('scripts')
@stop