@extends('adminlte::page')

@section('title', 'AdminLTE')

@section('content_header')
  <h1>Thêm Bài viết</h1>
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
  <div class="col-md-10 col-md-offset-1">
    <div class="box box-primary">
      <div class="box-header with-border">
        <h3 class="box-title">Thêm Mới</h3>
      </div>
      <form action="{{ route('post.store') }}" method="POST" class="form-horizontal" role="form" enctype="multipart/form-data" >
        {{ csrf_field() }}
        <div class="box-body">
          <div class="form-group">
             <label for="name" class="col-sm-2 control-label">Name</label>
              <div class="col-sm-8">
                 <input type="text" name="name" id="name" class="form-control" value="{{ old('name') }}">
              </div>
         </div> 
         <div class="form-group">
             <label for="cat_id" class="col-sm-2 control-label">Category</label>
             <div class="col-sm-5">
                 <select name="cat_id[]" id="cat_id" class="form-control" multiple>
                  @php
                      $cat_id_old = empty(old('cat_id'))? '' : old('cat_id');
                  @endphp
                      @foreach ($objCatItems as $val)
                          @php
                              $selected = $cat_id_old == $val->id ? 'selected' : '';
                          @endphp
                          <option {{ $selected }} value="{{ $val->id }}">{{ $val->name }}</option>
                      @endforeach
                 </select>
             </div>
         </div>
         <div class="form-group">
             <label for="tags" class="col-sm-2 control-label">Tags</label>
             <div class="col-sm-5">
                  @php
                    $tags_old = empty(old('tags'))? [] : old('tags');
                  @endphp
                 <select name="tags[]" id="tags" class="form-control" multiple>
                    @foreach ($objTagsItems as $tag)
                        @php
                            $selected = in_array($tag->id,$tags_old) ? 'selected' : '';
                        @endphp
                        <option {{ $selected }} value="{{ $tag->tag }}">{{ $tag->tag }}</option>
                    @endforeach
                 </select>
             </div>
         </div>

         <div class="form-group">
             <label for="cat_id" class="col-sm-2 control-label">Thumbnail</label>
             <div class="col-sm-5">
                  <label class="custom-file-upload">
                      <input type="file" name="picture" >
                  </label>
             </div>
         </div>
         <div class="form-group">
             <label for="cat_id" class="col-sm-2 control-label">Status</label>
             <div class="col-sm-5">
                <div class="checkbox">
                  <label>
                    <input type="checkbox" value="1" name="status">
                    Public
                  </label>
                </div>
             </div>
         </div>
         <div class="form-group">
             <label for="preview_text" class="col-sm-2 control-label">Preview</label>
             <div class="col-sm-8">
                 <textarea name="preview_text" id="preview_text" class="form-control" rows="3">{{ old('preview_text') }}</textarea>
              </div>
          </div>
         <div class="form-group">
             <label for="content" class="col-sm-2 control-label">Content</label>
          </div>
         <div class="form-group">
             <div class="col-sm-10 col-sm-offset-1">
                 <textarea name="content" id="content" class="form-control" rows="7">{{ old('content') }}</textarea>
              </div>
          </div>
        </div>         
        <div class="box-footer">
            <button type="submit" class="btn btn-primary">Save</button>
            <a href="{{ route('post.index') }}" class="btn btn-primary" onclick="return confirm('Các thay đổi chưa được lưu. Tiếp tục?') ">Back</a>
        </div>         
    </form>
      
    </div>
    
  </div>
</div>
@stop

@section('js')
  <script src="/vendor/laravel-filemanager/js/lfm.js"></script>
  <script src="//cdn.ckeditor.com/4.11.3/full/ckeditor.js"></script>
  <script>
    $(function () {
      $('#cat_id').select2({
        placeholder: 'Chọn danh mục',
      });

      $('#tags').select2({
        placeholder: 'Nhập thẻ tags',
        tags: true,
        tokenSeparators: [',',';'],        
      });

      var options = {
        height: 500,   
        uiColor : '#C0C0C0',
        toolbarCanCollapse : true,
        entities: false,

        basicEntities: false,
        // Pressing Enter will create a new &lt;div&gt; element.
        enterMode: CKEDITOR.ENTER_BR,
        // Pressing Shift+Enter will create a new &lt;p&gt; element.
        shiftEnterMode: CKEDITOR.ENTER_P,
        filebrowserImageBrowseUrl: '/laravel-filemanager?type=Images',
        filebrowserImageUploadUrl: '/laravel-filemanager/upload?type=Images&_token=',
        filebrowserBrowseUrl: '/laravel-filemanager?type=Files',
        filebrowserUploadUrl: '/laravel-filemanager/upload?type=Files&_token='
      };
      CKEDITOR.replace('content',options);
    })
  </script>
@stop