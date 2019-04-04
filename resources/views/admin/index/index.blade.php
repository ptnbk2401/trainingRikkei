@extends('adminlte::page')

@section('title', 'Home Admin')

@section('content_header')
    <h1>Dashboard</h1>
@stop

@section('content')
    @if (session('msg'))
        <div class="alert alert-success" role="alert">
            {{ session('msg') }}
        </div>
    @endif
    <p>You are logged in!</p>
@stop

