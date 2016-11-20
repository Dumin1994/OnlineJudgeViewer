@extends('layouts.base')

@section('title', 'Class Create')

@section('script')
    <script type="text/javascript" charset="UTF-8">
        function checkData() {
            var checkOK = true;
            var cur;
            // check id
            cur = $('#c-id');
            if (cur.val().trim() == '') {
                cur.parent().addClass('has-error');
                if (checkOK) cur.focus();
                checkOK = false;
            } else {
                cur.parent().removeClass('has-error');
            }
            // check name
            cur = $('#c-name');
            if (cur.val().trim() == '') {
                cur.parent().addClass('has-error');
                if (checkOK) cur.focus();
                checkOK = false;
            } else {
                cur.parent().removeClass('has-error');
            }
            // check slug
            cur = $('#c-slug');
            if (cur.val().trim() == '') {
                cur.parent().addClass('has-error');
                if (checkOK) cur.focus();
                checkOK = false;
            } else {
                cur.parent().removeClass('has-error');
            }
            if (!checkOK)
                return false;
        }
    </script>
@endsection

@section('content')
    <div class="container">
        <div><h1 class="page-header">Dash Board</h1></div>

        <div class="panel panel-default">
            <div class="panel-heading">Create</div>

            <div class="panel-body">
                <form action="{{route('class-store', ['AccessKey' => $accessKey])}}" class="form-horizontal"
                      method="POST" onsubmit="return checkData()">
                    <div class="form-group">
                        <label class="col-sm-2 col-md-2 col-lg-1 control-label" for="c-id">ID</label>
                        <div class="col-sm-10 col-md-10 col-lg-11">
                            <input id="c-id" name="c-id" type="text" class="form-control"
                                   placeholder="eg: 2014030402 (pre 10 characters in ZSC)" maxlength="20">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 col-md-2 col-lg-1 control-label" for="c-name">Name</label>
                        <div class="col-sm-10 col-md-10 col-lg-11">
                            <input id="c-name" name="c-name" type="text" class="form-control"
                                   placeholder="eg: 软件开发技术14B">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 col-md-2 col-lg-1 control-label" for="c-slug">Slug</label>
                        <div class="col-sm-10 col-md-10 col-lg-11">
                            <input id="c-slug" name="c-slug" type="text" class="form-control" placeholder="eg: 14软B">
                        </div>
                    </div>

                    <button role="button" type="submit" class="btn btn-primary btn-block">Create</button>
                </form>
            </div>

            <div class="panel-footer"></div>
        </div>
    </div>
@endsection