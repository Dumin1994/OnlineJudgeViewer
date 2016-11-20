@extends('layouts.base')

@section('title', 'Class Index')

@section('script')
    <script type="text/javascript" charset="UTF-8">
        var modal = $('#msgModal');
        var form_ = $("#form-delete");
        (function () {
            $('button[name=btn-delete-ok]').click(function () {
                form_.submit();
                modal.modal('hide');
            });
        })();
        function deleteAction(id) {
            form_.attr('action', "{{route('class-destroy', ['AccessKey' => $accessKey, 'id' => '{id}'])}}".replace(/\{id\}/, id));
            modal.modal('show');
        }
        function editAction(id) {
            window.location.href = "{{route('class-edit', ['AccessKey' => $accessKey, 'id' => '{id}'])}}".replace(/\{id\}/, id);
        }
    </script>
@endsection

@section('content')
    <div class="modal fade" id="msgModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span
                                aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                    <h4 class="modal-title" id="myModalLabel">Confirm</h4>
                </div>
                <div class="modal-body">
                    确定要删除该班级 ？
                </div>
                <div class="modal-footer">
                    <button name="btn-delete-ok" type="button" class="btn btn-default">Ok</button>
                    <button type="button" class="btn btn-primary" data-dismiss="modal">Cancel</button>
                </div>
            </div>
        </div>
    </div>

    <form id="form-delete" action="#Test" method="POST">
        <input type="hidden" name="_method" value="DELETE">
    </form>

    <div class="container">
        <div><h1 class="page-header">Dash Board</h1></div>
        <div class="panel panel-default">
            <div class="panel-heading clearfix">
                Class List
                <a href="{{route('class-create', ['AccessKey' => $accessKey])}}" class="btn btn-default btn-sm pull-right"><i class="glyphicon glyphicon-plus-sign"></i> Add New
                    Class
                </a>
            </div>

            <table class="table">
                <thead>
                <tr>
                    <th class="col-xs-1 col-sm-1 col-md-1 col-lg-1">#</th>
                    <th>Class Name</th>
                    <th class="col-xs-2 col-sm-2 col-md-1 col-lg-2">&nbsp;</th>
                    <th class="col-xs-2 col-sm-2 col-md-1 col-lg-2">&nbsp;</th>
                </tr>
                </thead>

                <tbody>
                @foreach($files as $key => $value)
                    <tr>
                        <td>{{$idx = (isset($idx) ? $idx + 1 : 1)}}</td>
                        <td><a href="{{route('class-show', ['AccessKey' => $accessKey, 'id' => $key])}}">{{$value}}</a></td>
                        <td>
                            <button onclick="editAction('{{$key}}')" type="button" role="button"
                                    class="btn btn-block btn-info btn-sm"><i class="glyphicon glyphicon-edit"><span
                                            class="hidden-xs"> Edit</span></i></button>
                        </td>
                        <td>
                            <button onclick="deleteAction('{{$key}}')" type="button" role="button"
                                    class="btn btn-block btn-danger btn-sm"><i class="glyphicon glyphicon-trash"><span
                                            class="hidden-xs"> Delete</span></i></button>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>

            <div class="panel-footer">

            </div>
        </div>
    </div>
@endsection