@extends('layouts.base')

@section('title', 'Auth')

@section('script')
    <script type="text/javascript">
        function jumpToAdmin() {
            window.location.href = '{{route('auth')}}/' + $('#access').val();
        }
    </script>
@endsection

@section('content')
    <div class="container">
        <div class="col-md-4 col-md-offset-4 col-lg-4 col-lg-offset-4">
            <div class="panel panel-default" style="margin-top: 30px">
                <div class="panel-heading">
                    <span>Dashboard</span>
                </div>
                <div class="panel-body">
                    <form action="#">
                        <div class="form-group">
                            <input id="access" type="text" class="form-control" placeholder="ACCESS KEY">
                        </div>
                        <div class="">
                            <button type="button" role="button" onclick="jumpToAdmin();"
                                    class="btn btn-primary btn-block">
                                Log in
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection