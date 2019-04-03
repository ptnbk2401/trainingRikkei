@extends('layouts.app')
@section('css')
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <style>
    </style>
@stop
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">Thêm Bài viết</div>
                <div class="card-body">
                    @if ($errors->any())
                    <div class="alert alert-danger" role="alert">
                      <ul>
                        @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                      </ul>
                    </div>
                    @endif
                    @if (session('msg-er'))
                        <div class="alert alert-danger" role="alert">
                            {{ session('msg-er') }}
                        </div>
                    @endif
                </div>
                <div class="container-fluid">
                	<form action="{{ route('post.store') }}" method="POST" class="form-horizontal" role="form" enctype="multipart/form-data" >
                        {{ csrf_field() }}
                        <div class="form-group">
                           <label for="name" class="col-sm-2 control-label">Name</label>
                            <div class="col-sm-10">
                               <input type="text" name="name" id="name" class="form-control" value="{{ old('name') }}">
                            </div>
                       </div> 
                       <div class="form-group">
                           <label for="cat_id" class="col-sm-2 control-label">Category</label>
                           <div class="col-sm-5">
                               <select name="cat_id" id="cat_id" class="form-control">
                                @php
                                    $arCat = [1=>'Giải trí',2=>'Thời sự',3=>'Thể thao'];
                                    $cat_id_old = empty(old('cat_id'))? '' : old('cat_id');
                                @endphp
                                    @foreach ($arCat as $id=>$val)
                                        @php
                                            $selected = $cat_id_old == $id ? 'selected' : '';
                                        @endphp
                                        <option {{ $selected }} value="{{ $id }}">{{ $val }}</option>
                                    @endforeach
                               </select>
                           </div>
                       </div>

                       <div class="form-group">
                           <label for="cat_id" class="col-sm-2 control-label">Picture</label>
                           <div class="col-sm-5">
                                <label class="custom-file-upload">
                                    <input type="file" name="picture" >
                                </label>
                           </div>
                       </div>
                       <div class="form-group">
                           <label for="preview_text" class="col-sm-2 control-label">Preview</label>
                           <div class="col-sm-10">
                               <textarea name="preview_text" id="preview_text" class="form-control" rows="3">{{ old('preview_text') }}</textarea>
                            </div>
                        </div>                  
                        <div class="form-group">
                            <div class="col-sm-10 col-sm-offset-2">
                                <button type="submit" class="btn btn-primary">Save</button>
                                <a href="{{ route('post.index') }}" class="btn btn-primary" onclick="return confirm('Các thay đổi chưa được lưu. Tiếp tục?') ">Back</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
