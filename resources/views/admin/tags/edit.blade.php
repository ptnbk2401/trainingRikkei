@extends('adminlte::page')

@section('title', 'AdminLTE')

@section('content_header')
  <h1>Sửa Tags</h1>
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
      <form action="{{ route('tags.update',$old_item->id) }}" method="post" class="form-horizontal" role="form"  >
        {{ csrf_field() }}
        @method('PUT')
        <div class="box-body">
          <div class="form-group">
              <label for="tag" class="col-sm-2 control-label">Tag</label>
              <div class="col-sm-8">
                 <input type="text" name="tag" id="tag" class="form-control" value="{{ old('tag',$old_item->tag) }}">
              </div>
          </div>
        </div>         
        <div class="box-footer text-center ">
            <button type="submit" class="btn btn-success">Save</button>
            <a href="{{ route('tags.index') }}" class="btn btn-primary" onclick="return confirm('Các thay đổi chưa được lưu. Tiếp tục?') ">Back</a>
        </div>         
    </form>
    </div>
  </div>
</div>
@stop
