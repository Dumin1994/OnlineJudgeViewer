@extends('layouts.base')

@section('title', 'Class Edit')

@section('script')
    <script type="text/javascript" charset="UTF-8">
        function del(obj) {
            var modal = $('#msgModal');
            modal.on('show.bs.modal', function () {
                $('#msgModal .modal-body').html('确定要删除该用户?');
            });
            $('#msgModal .btn-default').click(function () {
                modal.on('hidden.bs.modal', function () {
                    $(obj).parent().parent().remove();
                });
                modal.modal('hide');
            });
            modal.modal('show');
            //$(obj).parent().parent().remove();
        }
        function addNewNode() {
            var node = '<tr>' +
                    '<td>*</td>' +
                    '<td><input name="user-id" type="text" class="form-control" title="User ID"></td>' +
                    '<td><input name="user-name" type="text" class="form-control" title="User Name"></td>' +
                    '<td><button class="btn btn-danger btn-block" onclick="del(this);" ' +
                    'type="button" title="Delete"><i class="glyphicon glyphicon-trash"></i>' +
                    '<span class="hidden-xs hidden-sm"> Delete</span></button></td>' +
                    '</tr>';
            $("#name-list").append(node);
        }
        function submitHandle() {
            var res = {};
            var cur;
            var hasError = false;
            // check full name
            cur = $('#class-full-name');
            if (cur.val().trim() == '') {
                cur.parent().addClass('has-error');
                if (!hasError) cur.focus();
                hasError = true;
            } else {
                cur.parent().removeClass('has-error');
                res['name'] = cur.val();
            }
            // check slug name
            cur = $('#class-slug-name');
            if (cur.val().trim() == '') {
                cur.parent().addClass('has-error');
                if (!hasError) cur.focus();
                hasError = true;
            } else {
                cur.parent().removeClass('has-error');
                res["slug"] = cur.val();
            }
            // check user
            var data = {};
            var firstAlert = true;
            $('#name-list tr').each(function (item) {
                var userId = $(this).find('input[name=user-id]');
                if (userId.val().trim() == '') {
                    userId.parent().addClass('has-error');
                    if (!hasError) userId.focus();
                    hasError = true;
                } else {
                    userId.parent().removeClass('has-error');
                }
                var userName = $(this).find('input[name=user-name]');
                if (userName.val().trim() == '') {
                    userName.parent().addClass('has-error');
                    if (!hasError) userName.focus();
                    hasError = true;
                } else {
                    userName.parent().removeClass('has-error');
                }
                if (data.hasOwnProperty(userId.val().trim())) {
                    userId.parent().parent().addClass('danger');
                    if (firstAlert) {
                        $('#errModal').on('show.bs.modal', function () {
                            $('#errModal .modal-body').html('用户ID 重复');
                        });
                        $('#errModal').modal('show');
                    }
                    hasError = true;
                } else {
                    userId.parent().parent().removeClass('danger');
                }
                data[userId.val().trim()] = userName.val().trim();
            });
            res['list'] = data;
            if (hasError)
                return false;

            var obj = $('input[name=data]');
            obj.val(JSON.stringify(res));
        }
    </script>
@endsection

@section('content')
    {{-- Modal Confirm Box --}}
    <div class="modal fade" id="msgModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span
                                aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                    <h4 class="modal-title" id="myModalLabel">Confirm</h4>
                </div>
                <div class="modal-body">
                    ...
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default">OK</button>
                    <button type="button" class="btn btn-primary" data-dismiss="modal">Cancel</button>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal Error Box --}}
    <div class="modal fade" id="errModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span
                                aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                    <h4 class="modal-title" id="myModalLabel">Error</h4>
                </div>
                <div class="modal-body"></div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <div class="container">
        <h1 class="page-header">Dash Board</h1>
    </div>
    <div class="container">
        <div class="panel panel-default">
            <div class="panel-heading">
                Editor
            </div>

            <div class="panel-body">
                <div class="form-group">
                    <label for="class-full-name">Class Full Name</label>
                    <input id="class-full-name" type="text" class="form-control" placeholder="eg: 软件开发技术 14B"
                           value="{{$data['name']}}">
                </div>
                <div class="form-group">
                    <label for="class-slug-name">Class Slug Name</label>
                    <input id="class-slug-name" type="text" class="form-control"
                           value="{{$data['slug']}}"
                           placeholder="eg: 14软B">
                </div>
            </div>

            <table class="table table-responsive">
                <thead>
                <tr>
                    <th>#</th>
                    <th>User ID</th>
                    <th>User Name</th>
                    <th>&nbsp;</th>
                </tr>
                </thead>
                <tbody id="name-list">
                {{--tr*16>td{$}+(td>input.form-control[value='item $',title='User ID'])+(td>input.form-control[value='Name $',title='User Name'])+(td>button.btn.btn-danger.btn-block[type=button,title='Delete']>(i.glyphicon.glyphicon-trash+(span.hidden-xs.hidden-sm{ Delete})))--}}
                @foreach($data['list'] as $key => $value)
                    <tr>
                        <td>{{($idx = (isset($idx) ? $idx + 1 : 1))}}</td>
                        <td><input name="user-id" type="text" class="form-control" value="{{$key}}" title="User ID">
                        </td>
                        <td><input name="user-name" type="text" class="form-control" value="{{$value}}"
                                   title="User Name"></td>
                        <td>
                            <button class="btn btn-danger btn-block" onclick="del(this);" type="button" title="Delete">
                                <i
                                        class="glyphicon glyphicon-trash"></i><span
                                        class="hidden-xs hidden-sm"> Delete</span></button>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>

            <div class="panel-footer clearfix">
                <div class="form-group">
                    <button role="button" type="button" onclick="addNewNode()" class="btn btn-default btn-block">Add
                        New
                    </button>
                </div>
                <form action="{{route('class-update', ['AccessKey' => $accessKey, 'id' => $id])}}" method="POST"
                      onsubmit="return submitHandle()">
                    <input type="hidden" name="_method" value="PATCH">
                    <input type="hidden" name="data">
                    <button role="button" type="submit" class="btn btn-primary btn-block">Save</button>
                </form>
            </div>
        </div>
    </div>
@endsection