@extends('layouts.base')

@section('title', 'Class Show')

@section('content')
    <div class="container">
        <div>
            <h1 class="page-header">
                Dash Board
            </h1>
        </div>

        <div class="panel panel-default">
            <div class="panel-heading">Detail</div>

            <div class="panel-body">
                <h3>Class: {{$className}}</h3>
            </div>

            <table class="table">
                <thead>
                <tr>
                    <th>#</th>
                    <th>User ID</th>
                    <th>User Name</th>
                </tr>
                </thead>
                
                <tbody>
                @foreach($list as $key => $value)
                <tr>
                    <td>{{$idx = (isset($idx) ? $idx + 1 : 1)}}</td>
                    <td>{{$key}}</td>
                    <td>{{$value}}</td>
                </tr>
                @endforeach
                </tbody>
            </table>

            <div class="panel-footer"></div>
        </div>
    </div>
@endsection