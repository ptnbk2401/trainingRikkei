@extends('adminlte::page')

@section('title', 'AdminLTE')
@section('css')
    <style>
        table th,.column-pid,.column-status {
            text-align: center;
        }
        th.column-pid:last-child {
            width: 130px;
        }
    </style>
@stop
@section('content_header')
    <h1>Bài viết</h1>
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
        <form action="{{ route('post.create') }}" method="get" style="display: inline;" id="form-create" >
        </form>
        <form id="delete-form" action="" method="POST" style="display: none;">
            @csrf
            @method('DELETE')
        </form>
        {!! $grid !!}
    </div>
@stop
@section('js')
<script>
    function deletePost(post_id) {
        if (confirm('Bạn có chắc muốn xóa?')) {
            var route = '/admin/post/'+post_id;
            $('#delete-form').attr('action',route);
            $('#delete-form').submit();
        }
    }
    ajax_sendding = false;
    function changeStatus(post_id) {
        if (ajax_sendding == true){
            alert('Vui lòng chờ trong giây lát!');
            return false;
        }
        ajax_sendding = true;
        $('#stt'+post_id).html('<i class="fa fa-fw fa-spinner" style="font-size: 20px; color: blue"></i>');
        $.ajax({
            url: "{{ route('post.status') }}",
            type: 'GET',
            cache: false,
            data: {post_id:post_id},
            success: function(data){
               $('#stt'+post_id).html(data);
            },
            error: function (){
                alert('Có lỗi xảy ra');
            }
        }).always(function(){
            ajax_sendding = false;
        });
        return false;
        // alert(tags);
    }


</script>
@stop
