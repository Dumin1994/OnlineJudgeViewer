@extends('layouts.base')

@section('title', 'Home')

@section('css')
    <style type="text/css">
        .jb-bg-image1 {
            background: url("images/bg1.jpg") center;
            background-size: cover;
        }
    </style>
@endsection

@section('script')
    <script src="js/echarts.min.js"></script>

    <script type="text/javascript">
        var echart1 = new echarts.init(document.getElementById('echart-rank'));

        echart1.showLoading();
        $.get('{{route('api-summary')}}').done(function (data) {
            var checkData = true;
            var res = {'name': [], 'slug': [], 'avg': []};
            data.forEach(function (item) {
                if (!item.hasOwnProperty('className')
                        || !item.hasOwnProperty('classSlug')
                        || !item.hasOwnProperty('solvedCount')
                        || !item.hasOwnProperty('studentCount')) {
                    alert('data error.');
                    checkData = false;
                    return false;
                }
                res['name'].push(item['className']);
                res['slug'].push(item['classSlug']);
                res['avg'].push((item['solvedCount'] / item['studentCount']).toFixed(2));
            });
            if (!checkData) return false;

            var rank = (function (e) {
                var tmp = [];
                var cnt = Math.min(e.name.length, e.slug.length, e.avg.length);
                for (var i = 0; i < cnt; ++i)
                    tmp.push({'name': e.name[i], 'slug': e.slug[i], 'avg': parseFloat(e.avg[i])});
                return tmp;
            })(res);

            rank.sort(function (a, b) {
                if(a.avg != b.avg)
                    return b.avg - a.avg;
                return a.name > b.name;
            });
            for (var i = 0; i < rank.length; ++i) {
                $('#class-list').append('<tr><td>' + (i + 1) + '</td><td>' + rank[i]['name'] + '</td><td>' + rank[i]['avg'] + '</td></tr>');
            }

            var option = {
                title: {
                    text: '平均解题数'
                },
                tooltip: {},
                visualMap: {
                    show: false,
                    min: 0,
                    max: data.length,
                    dimension: 0,
                    inRange: {
                        color: ['#4a657a', '#308e92', '#b1cfa5', '#f5d69f', '#f5898b', '#ef5055']
                    }
                },
                xAxis: {
                    data: res.slug
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
                    name: '题数',
                    type: 'bar',
                    data: res.avg,
                    itemStyle: {
                        normal: {
                            label: {
                                show: true,
                                position: "top"
                            }
                        }
                    }
                }],
                animationEasing: 'elasticOut',
                animationEasingUpdate: 'elasticOut',
                animationDelay: function (idx) {
                    return idx * 100;
                },
                animationDelayUpdate: function (idx) {
                    return idx * 100;
                }
            };

            echart1.hideLoading();
            echart1.setOption(option);
        });
    </script>

    <script type="text/javascript">
        window.onresize = echart1.resize;
    </script>
@endsection

@section('content')
    <div class="jumbotron jb-bg-image1 vertical-center" style="min-height: 400px;">
        <div class="container">
            <div class="row vertical-align">
                <div class="col-lg-7 col-md-7">
                    <h1><span class="text-white">Get your AC number</span></h1>
                    <p class="text-white">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Ab at dolores maxime
                        nisi
                        quam quibusdam sint
                        totam? Alias dicta dignissimos, est explicabo neque, numquam rerum tempora tempore, vel vitae
                        voluptate?</p>
                </div>

                <div class="col-lg-5 col-md-5 hidden-xs">
                    <form action="#" role="form">
                        <div class="form-group">
                            <input class="form-control" placeholder="Your ZSC - OJ ID" type="text"
                                   id="search-data-jumb">
                        </div>
                        <div class="form-group">
                            <button type="button" role="button"
                                    onclick="navToUrl('#search-data-jumb');"
                                    class="btn btn-primary btn-block">
                                Search
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="table-responsive col-lg-6 col-md-6">
            <table class="table table-striped table-striped">
                <thead>
                <tr>
                    <th>排名</th>
                    <th>班级</th>
                    <th>平均解题数</th>
                </tr>
                </thead>
                <tbody id="class-list"></tbody>
            </table>
        </div>

        <div class="col-lg-6 col-md-6" style="height: 300px" id="echart-rank"></div>

        <div class="pull-right">
            <span class="text-info small">* 数据每20分钟更新一次</span>
        </div>
    </div>
@endsection