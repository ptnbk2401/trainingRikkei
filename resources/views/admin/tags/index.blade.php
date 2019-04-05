@extends('adminlte::page')

@section('title', 'AdminLTE - Danh mục')

@section('content_header')
    <h1>Tags Bài viết</h1>
@stop

@section('content')
    <div class="card-body">
        @if (session('msg'))
            <div class="alert alert-success" role="alert">
                {{ session('msg') }}
            </div>
        @endif
        @if (session('msg-er'))
            <div class="alert alert-danger" role="alert">
                {{ session('msg-er') }}
            </div>
        @endif
    </div>
    <div class="container-fluid">
        <form action="{{ route('tags.create') }}" method="get" style="display: inline;" id="form-create" >
            <button type="submit" class="btn bg-orange margin"><i class="fa fa-fw fa-plus-square"></i> Thêm</button>
        </form>
        <form id="delete-form" action="" method="POST" style="display: none;">
            @csrf
            @method('DELETE')
        </form>
        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <div class="box-header">
                        <h3 class="box-title">Danh mục bài viết</h3>
                        <form id="search-form" action="{{ route('tags.search') }}" method="get" style="display: none;">
                            @csrf
                        </form>
                        <div class="box-tools">
                            <div class="input-group input-group-sm" style="width: 150px;">
                                <input type="text" name="tags_search" class="form-control pull-right" placeholder="Search" form="search-form" value="{{ request('tags_search') ? request('tags_search') : '' }}">
                                <div class="input-group-btn">
                                    <button type="submit" class="btn btn-default" form="search-form"><i class="fa fa-search"></i></button>
                                </div>
                            </div>
                        </div>
                    </div>
                <!-- /.box-header -->
                    <div class="box-body table-responsive no-padding">
                    <table class="table table-hover table-bordered">
                        <tbody><tr>
                            <th class="text-center">ID</th>
                            <th>Tên Tag</th>
                            <th>Bài viết</th>
                            <th>Action</th>
                        </tr>
                        @if (!empty($objItems))
                            @foreach ($objItems as $item)
                            <tr>
                                <td class="text-center">{{ $item->id }}</td>
                                <td>{{ $item->tag }}</td>
                                <td>{{ $item->posts->count() }}</td>
                                <td style="width: 140px">
                                    <form id="edit{{ $item->id }}" action="{{ route('tags.edit',$item->id) }}" method="get" style="display: inline;" >
                                        <a href="javascript:void(0)" class="btn btn-info" onclick="editCat({{ $item->id }})" ><i class="fa fa-fw fa-pencil-square"></i></a>
                                    </form>
                                    <a class="btn btn-danger" href="javascript:void(0)"  onclick="deleteCat({{ $item->id }},{{ $item->posts->count() }})"><i class="fa fa-fw fa-trash"></i></a>
                                </td>
                            </tr>
                            @endforeach
                        @endif
                        </tbody></table>
                    </div>
                <!-- /.box-body -->
                </div>
            <!-- /.box -->
            </div>
        </div>
        <ul class="pagination">
            {{ $objItems->appends(Request::except('page'))->links() }}
        </ul>
    </div>
@stop
@section('js')
<script>
    function deleteCat(cid,count) {
        if(count>0){
            if (confirm('Một số bài viết còn chứa Tags này! Tiếp tục xóa?')) {
                var route = '/admin/tags/'+cid;
                $('#delete-form').attr('action',route);
                $('#delete-form').submit();
            } 
        }else if(confirm('Bạn có chắc muốn xóa?')) {
            var route = '/admin/tags/'+cid;
            $('#delete-form').attr('action',route);
            $('#delete-form').submit();
        }
    }
    function editCat(cid) {
        var editform = $('#edit'+cid);
        $(editform).submit();
    }
</script>
@stop
