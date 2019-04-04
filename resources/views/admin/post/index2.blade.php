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
                <div class="card-header">Bài viết</div>
                <div class="card-body">
                    @if (session('msg'))
                        <div class="alert alert-success" role="alert">
                            {{ session('msg') }}
                        </div>
                    @endif
                </div>
                <div class="container-fluid">
                    <form action="{{ route('post.create') }}" method="get" style="display: inline;" id="form-create" >
                        {{-- <button class="btn btn-success" type="submit" ><i style="font-size:20px" class="material-icons">add_box</i> Add</button> --}}
                    </form>
                    {{-- <div class="col-md-6 top-right" style="display: inline-block;">
                        <form action="{{ route('post.search') }}" method="get" class="form-inline" role="form">
                            {{ csrf_field() }}
                            <div class="form-group">
                                @php
                                    $search = !empty(request('search'))? request('search') : '';
                                @endphp
                                <input type="text" value="{{ $search }}" name="search" class="form-control" placeholder="Search">
                            </div>
                            <div class="form-group">
                                 <button type="submit" class="btn btn-primary btn-add">Tìm kiếm</button>
                            </div>
                        </form>
                    </div> --}}
                    {!! $grid !!}
                    {{-- <table class="table table-bordered table-hover" style="font-size: 13px">
                		<thead>
                			<tr>
                				<th>ID</th>
                				<th>Tên bài viết</th>
                				<th>Danh mục</th>
                				<th>Mô tả</th>
                				<th>Ngày tạo</th>
                                <th>Picture</th>
                                <th>Action</th>
                			</tr>
                		</thead>
                		<tbody>
                			@if (!empty($objItems))
                                @php
                                    $arCat = [1=>'Giải trí',2=>'Thời sự',3=>'Thể thao'];
                                @endphp
                				@foreach ($objItems as $item)
                					<tr>
		                				<td class="text-center">{{ $item->id }}</td>
		                				<td><a href="{{ route('post.show',$item->id) }}">{{ $item->pname }}</a></td>
		                				<td class="text-center">{{ $arCat[$item->cat_id] }}</td>
		                				<td>{{ $item->preview_text }}</td>
		                				<td class="text-center">{{ date('H:i d/m/Y ', strtotime($item->created_at)) }}</td>
                                        <td class="text-center">
                                            @php
                                              $src = asset('/storage/media/files/posts/' .$item->picture) ;
                                            @endphp
                                            <img src="{{ $src }}" style="width: 110px; height: 80px">
                                        </td>
                                        <td style="width: 140px">
                                            <form action="{{ route('post.edit',$item->id) }}" method="get" style="display: inline;" >
                                                <button class="btn btn-primary" type="submit" ><i style="font-size:20px" class="material-icons">edit</i></button>
                                            </form>
                                            <form action="{{ route('post.destroy',$item->id) }}" method="post" style="display: inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button class="btn btn-danger" type="submit" onclick="return confirm('Bạn có chắc muốn xóa?') "><i style="font-size:20px" class="material-icons">delete</i></button>
                                            </form>
                                        </td>
		                			</tr>
                				@endforeach
                			@endif
                		</tbody>
                	</table>
                    <ul class="pagination">
                        {{ $objItems->appends(Request::except('page'))->links() }}
                    </ul> --}}
                </div>
                
            </div>
        </div>
    </div>
</div>

    <!-- AmiGridView -->
@endsection
@section('js')
{{-- <script src="{{ asset('vendor/grid-view/js/amigrid.js') }}"></script> --}}
<!-- AmiGridView -->
@stack('scripts')
@stop