@extends('layouts.base')

@section('title', 'User')

@section('content')
    <div class="container">
        <div class="col-md-12 col-lg-12">
            <table class="table table-responsive table-striped">
                <thead>
                <tr>
                    <th class="col-xs-6 col-sm-6 col-md-3 col-lg-3">User</th>
                    <th class="col-xs-6 col-sm-6 col-md-1 col-lg-1" id="user">loading...</th>
                    <th class="hidden-xs hidden-sm col-md-8 col-lg-8">&nbsp;</th>
                </tr>
                </thead>

                <tbody>
                <tr>
                    <td>Rank</td>
                    <td id="rank">loading...</td>
                    <td class="hidden-xs hidden-sm" rowspan="11">
                        <div id="echart-user" style="height: 400px"></div>
                    </td>
                </tr>
                <tr>
                    <td>Solved</td>
                    <td id="solved">loading...</td>
                </tr>
                <tr>
                    <td>Submit</td>
                    <td id="submit">loading...</td>
                </tr>
                <tr>
                    <td>Accept</td>
                    <td id="ac">loading...</td>
                </tr>
                <tr>
                    <td>Presentation Error</td>
                    <td id="pe">loading...</td>
                </tr>
                <tr>
                    <td>Wrong Answer</td>
                    <td id="wa">loading...</td>
                </tr>
                <tr>
                    <td>Time Limit Exceeded</td>
                    <td id="tle">loading...</td>
                </tr>
                <tr>
                    <td>Memory Limit Exceeded</td>
                    <td id="mle">loading...</td>
                </tr>
                <tr>
                    <td>Output Limit Exceeded</td>
                    <td id="ole">loading...</td>
                </tr>
                <tr>
                    <td>Runtime Error</td>
                    <td id="re">loading...</td>
                </tr>
                <tr>
                    <td>Compilation Error</td>
                    <td id="ce">loading...</td>
                </tr>
                <tr>
                    <td colspan="3">
                        <div>
                            <button role="button" class="btn btn-default collapsed" data-toggle="collapse"
                                    data-target="#problems">Solved List <span class="caret"></span></button>
                        </div>
                        <div id="problems" class="collapse">loading...</div>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="msgModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span
                                aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                    <h4 class="modal-title" id="myModalLabel">Error</h4>
                </div>
                <div class="modal-body">
                    <div id="msgContent"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

@endsection


@section('script')
    <script src="/js/echarts.min.js"></script>

    <script type="text/javascript">
        var echart1 = new echarts.init(document.getElementById('echart-user'));

        echart1.showLoading();
        $.get('{{route('api-user-data', ['id' => $id])}}').done(function (data) {
            // check data
            if (data == null || data == undefined || !data.hasOwnProperty('id')) {
                $('#msgContent').html('Network error, please try later.');
                $("#msgModal").modal('show');
                return;
            }
            if (data.hasOwnProperty('error')) {
                $('#msgContent').html('No Such User.');
                $("#msgModal").modal('show');
                return;
            }
            // time
            var time_ = (function (tmp) {
                var res = [];
                tmp.forEach(function (e) {
                    var date_ = new Date(e[0]);
                    res.push(date_.toLocaleDateString());
                });
                return res;
            })(data['time_line']);
            // submit
            var submit_ = (function (tmp) {
                var res = [];
                tmp.forEach(function (e) {
                    res.push(e[1]);
                });
                return res;
            })(data['time_line']);
            // accept
            var accept_ = (function (tmp) {
                var res = [];
                tmp.forEach(function (e) {
                    res.push(e[2]);
                });
                return res;
            })(data['time_line']);

            $("#user").html(data['id']);
            $("#rank").html(data['rank']);
            $("#solved").html(data['solved']);
            $("#submit").html(data['submit']);
            $("#ac").html(data['ac']);
            $("#pe").html(data['pe']);
            $("#wa").html(data['wa']);
            $("#tle").html(data['tle']);
            $("#mle").html(data['mle']);
            $("#ole").html(data['ole']);
            $("#re").html(data['re']);
            $("#ce").html(data['ce']);

            $("#problems").html('');
            data['problems'].forEach(function (e) {
                var pageName = 'page' + (Math.floor(e / 100) % 10 + 1);
                if (document.getElementById(pageName) == undefined) {
                    $("#problems").append('<div id="' + pageName + '"><div>Volume ' + (Math.floor(e / 100) % 10 + 1) + '</div></div>');
                }
                $("#" + pageName).append('<a href="http://acm.two.moe:808/JudgeOnline/problem.php?id=' + e + '">' + e + '</a> ');
            });

            var avgTime = 2000 / time_.length;

            echart1.hideLoading();
            echart1.setOption({
                title: {
                    show: false
                },
                tooltip: {
                    show: true,
                    formatter: "{a}<br/>{b} : {c}"
                },
                xAxis: [{
                    show: false,
                    data: time_
                }, {
                    show: false,
                    data: time_
                }],
                visualMap: {
                    show: false,
                    min: 0,
                    max: time_.length,
                    dimension: 0,
                    inRange: {
                        color: ['#4a657a', '#308e92', '#b1cfa5', '#f5d69f', '#f5898b', '#ef5055']
                    }
                },
                yAxis: {
                    axisLine: {
                        show: false
                    },
                    axisLabel: {
                        textStyle: {
                            color: '#4a657a'
                        }
                    },
                    splitLine: {
                        show: true
                    },
                    axisTick: {
                        show: false
                    }
                },
                series: [{
                    name: 'Submit',
                    type: 'bar',
                    data: submit_,
                    z: 1,
                    itemStyle: {
                        normal: {
                            opacity: 0.4,
                            barBorderRadius: 5,
                            shadowBlur: 3,
                            shadowColor: '#111'
                        }
                    }
                }, {
                    name: 'Accept',
                    type: 'bar',
                    data: accept_,
                    xAxisIndex: 1,
                    z: 3,
                    itemStyle: {
                        normal: {
                            barBorderRadius: 5
                        }
                    }
                }],
                animationEasing: 'elasticOut',
                animationEasingUpdate: 'elasticOut',
                animationDelay: function (idx) {
                    return idx * avgTime;
                },
                animationDelayUpdate: function (idx) {
                    return idx * avgTime;
                }
            });
        });
    </script>

    <script type="text/javascript">
        window.onresize = echart1.resize;
    </script>
@endsection