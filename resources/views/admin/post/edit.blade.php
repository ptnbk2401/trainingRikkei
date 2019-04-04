@extends('adminlte::page')

@section('title', 'AdminLTE')

@section('content_header')
  <h1>Sửa Bài viết</h1>
@stop

@section('content')
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
	<div class="row">
		<div class="col-md-10 col-md-offset-1">
		    <div class="box box-primary">
		      <div class="box-header with-border">
		        <h3 class="box-title">Thêm Mới</h3>
		      </div>
			<form action="{{ route('post.update',$old_item->id) }}" method="post" class="form-horizontal" role="form" enctype="multipart/form-data" >
			{{ csrf_field() }}
			@method('PUT')
				<div class="form-group">
					<label for="name" class="col-sm-2 control-label">Name</label>
					<div class="col-sm-10">
						<input type="text" name="name" id="name" class="form-control" value="{{ old('name',$old_item->pname) }}">
					</div>
				</div> 
				<div class="form-group">
					<label for="cat_id" class="col-sm-2 control-label">Category</label>
					<div class="col-sm-5">
						<select name="cat_id" id="cat_id" class="form-control">
						@php
							$arCat = [1=>'Giải trí',2=>'Thời sự',3=>'Thể thao'];
							$cat_id_old = empty(old('cat_id'))? $old_item->cat_id : old('cat_id');
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
						@php
						$src = asset('/storage/media/files/posts/' .$old_item->picture) ;
						@endphp
						<a href="{{ $src }}" target="_blank"><img src="{{ $src }}" height="100px" ></a>
					</div>
				</div>
				<div class="form-group">
					<label for="preview_text" class="col-sm-2 control-label">Preview</label>
					<div class="col-sm-10">
						<textarea name="preview_text" id="preview_text" class="form-control" rows="3">{{ old('preview_text',$old_item->preview_text) }}</textarea>
					</div>
				</div>                  
				<div class="form-group">
					<div class="col-sm-10 col-sm-offset-2">
						<button type="submit" class="btn btn-success">Save</button>
						<a href="{{ route('post.index') }}" class="btn btn-primary" onclick="return confirm('Các thay đổi chưa được lưu. Tiếp tục?') ">Back</a>
					</div>
				</div>
			</form>
		</div>
	</div>
</div>
@stop
