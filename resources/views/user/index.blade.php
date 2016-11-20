@extends('layouts.base')

@section('title', 'User')

@section('css')
    <style type="text/css">
        .jumb-user {
            min-height: 400px;
            background: url("/images/jumbotron-bg-2.jpg") center;
            background-size: cover;
        }
    </style>
    <link rel="stylesheet" href="/css/lato.css" type="text/css">
@endsection

@section('script')
    <script type="text/javascript" charset="UTF-8">
        function query(id) {
            var tmp = $(id).val();
            if (tmp.trim() == '') {
                $(id).parent().addClass('has-error');
            } else {
                $(id).parent().removeClass('has-error');
                window.location.href = '{{route('user-query', ['id'=>''])}}/' + tmp;
            }
        }
        function compare(id1, id2) {
            var tmp1 = $(id1).val(), tmp2 = $(id2).val();
            if(tmp1.trim() == '') {
                $(id1).parent().addClass('has-error');
            }
            if(tmp2.trim() == '') {
                $(id2).parent().addClass('has-error');
            }
            if(tmp1.trim() != '' && tmp2.trim() != '') {
                $(id1).parent().removeClass('has-error');
                $(id2).parent().removeClass('has-error');
                window.location.href = '{{route('user-compare', ['id1' => '', 'id2' => ''])}}/' + tmp1 + '/' + tmp2;
            }
        }
    </script>
@endsection

@section('content')
    <div class="jumbotron jumb-user vertical-center">
        <div class="container">
            <div class="col-md-6 col-lg-6">
                <h3 class="text-white text-center">Query Something</h3>
                <form action="#" role="form">
                    <div class="form-group">
                        <input id="input-query" type="text" class="form-control" placeholder="Your ZSC - OJ ID">
                    </div>
                    <div class="form-group hidden-xs hidden-sm">
                        <input type="text" class="form-control" style="opacity: 0;filter: Alpha(opacity:0)">
                    </div>
                    <div class="form-group">
                        <button type="button" role="button" onclick="query('#input-query')"
                                class="btn btn-primary btn-block">Query
                        </button>
                    </div>
                </form>
            </div>
            <div class="col-md-6 col-lg-6">
                <h3 class="text-white text-center">Compare To Another Account</h3>
                <form action="#" role="form">
                    <div class="form-group">
                        <input id="input-cmp-1" type="text" class="form-control" placeholder="Your ZSC - OJ ID">
                    </div>
                    <div class="form-group">
                        <input id="input-cmp-2" type="text" class="form-control" placeholder="Another ZSC - OJ ID">
                    </div>
                    <div class="form-group">
                        <button type="button" role="button"
                                onclick="compare('#input-cmp-1', '#input-cmp-2')"
                                class="btn btn-primary btn-block">Compare
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection