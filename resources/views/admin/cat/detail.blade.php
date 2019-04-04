@extends('layouts.app')
@section('css')
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <style>
    .btn-add {
        margin: 10px;
        color: white;
    }
    </style>
@stop
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">{{ $objItem->pname }}</div>
                <div class="container-fluid">
                    @php
                        $arCat = [1=>'Giải trí',2=>'Thời sự',3=>'Thể thao'];
                    @endphp
                    <div>
                        <p>Danh mục: {{ $arCat[$objItem->cat_id] }}</p>
                        <p>Ngày tạo: {{ date('H:i M, d-Y ', strtotime($objItem->created_at)) }}</p>
                    </div>
                    <div>
                        <p>Mô tả:</p>
                        <p>{!! $objItem->preview_text !!}</p>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-10 col-sm-offset-2">
                        <a href="{{ route('post.index') }}" class="btn btn-primary" >Home</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
