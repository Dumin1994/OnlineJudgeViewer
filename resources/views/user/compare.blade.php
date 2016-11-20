@extends('layouts.base')

@section('title', 'Compare')

@section('content')
    <div class="container">
        <table class="table table-responsive">
            <thead>
            <tr>
                <th class="col-xs-4 col-sm-4 col-md-4 col-lg-4">User1</th>
                <th class="col-xs-4 col-sm-4 col-md-4 col-lg-4">Common</th>
                <th class="col-xs-4 col-sm-4 col-md-4 col-lg-4">User2</th>
            </tr>
            <tr>
                <td id="user1-solved">
                    {{--a[href=#,class=text-info]{1$$$ }*1--}}
                    loading...
                </td>
                <td id="common-solved">
                    {{--a[href=#,class=text-success]{1$$$ }*1--}}
                    loading...
                </td>
                <td id="user2-solved">
                    {{--a[href=#,class=text-danger]{1$$$ }*1--}}
                    loading...
                </td>
            </tr>
            </thead>
        </table>
    </div>
@endsection

@section('script')
    <script type="text/javascript">
        $.get("{{route('api-user-compare', ['id1' => $id1, 'id2' => $id2])}}").done(function (data) {
            console.log('done.');
            $('#user1-solved').html('');
            data['user1'].forEach(function (e) {
                $('#user1-solved').append('<a href="http://acm.two.moe:808/JudgeOnline/problem.php?id=' + e + '" class="text-info">' + e + '</a> ');
            });

            $('#user2-solved').html('');
            data['user2'].forEach(function (e) {
                $('#user2-solved').append('<a href="http://acm.two.moe:808/JudgeOnline/problem.php?id=' + e + '" class="text-danger">' + e + '</a> ');
            });

            $('#common-solved').html('');
            data['common'].forEach(function (e) {
                $('#common-solved').append('<a href="http://acm.two.moe:808/JudgeOnline/problem.php?id=' + e + '" class="text-success">' + e + '</a> ');
            });
        });
    </script>
@endsection