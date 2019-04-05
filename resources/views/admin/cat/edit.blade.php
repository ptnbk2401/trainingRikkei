@extends('adminlte::page')

@section('title', 'AdminLTE')

@section('content_header')
  <h1>Sửa Danh Mục</h1>
@stop

@section('content')
<div class="row">
  <div class="col-md-10 col-md-offset-1">
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
  <div class="col-md-8 col-md-offset-2">
    <div class="box box-primary">
      <div class="box-header with-border">
        <h3 class="box-title">Thay đổi</h3>
      </div>
      <form action="{{ route('cat.update',$old_item->id) }}" method="post" class="form-horizontal" role="form"  >
        {{ csrf_field() }}
        @method('PUT')
        <div class="box-body">
          <div class="form-group">
             <label for="name" class="col-sm-2 control-label">Danh mục</label>
              <div class="col-sm-8">
                 <input type="text" name="name" id="name" class="form-control" value="{{ old('name',$old_item->name) }}">
              </div>
         </div>
         <div class="form-group">
             <label for="sort" class="col-sm-2 control-label">Sắp xếp</label>
              <div class="col-sm-8">
                 <input type="number" name="sort" id="sort" class="form-control" value="{{ old('sort',$old_item->sort) }}">
              </div>
         </div>  
        </div>         
        <div class="box-footer text-center ">
            <button type="submit" class="btn btn-success">Save</button>
            <a href="{{ route('cat.index') }}" class="btn btn-primary" onclick="return confirm('Các thay đổi chưa được lưu. Tiếp tục?') ">Back</a>
        </div>         
    </form>
      
    </div>
    
  </div>
</div>
@stop
