<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>OnlineJudgeViewer for ZSC | @yield('title')</title>
    <link rel="stylesheet" href="/css/bootstrap.min.css">
    <style type="text/css">
        body {
            margin-top: 50px;
        }

        .text-white {
            color: #fff;
            font-weight: 200;
        }

        @media (min-width: 992px) {
            .vertical-center {
                min-height: 100%; /* Fallback for browsers do NOT support vh unit */
                min-height: 100vh; /* These two lines are counted as one :-)       */

                display: flex;
                align-items: center;
            }

            .vertical-align {
                display: flex;
                align-items: center;
            }
        }
    </style>
    @yield('css')
</head>
<body>
<nav class="navbar navbar-default navbar-fixed-top" role="navigation">
    <div class="container">
        <div class="navbar-header">
            <button type="button" role="button" class="navbar-toggle collapsed" data-toggle="collapse"
                    data-target="#bs-navbar-collapse-1">
                <span class="sr-only">Toggle nav</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a href="{{route('home')}}" class="navbar-brand">O<span class="hidden-xs">nline </span>J<span
                        class="hidden-xs">udge</span>
                Viewer <sup>for ZSC</sup></a>
        </div>

        <div class="collapse navbar-collapse" id="bs-navbar-collapse-1">
            <ul class="nav navbar-nav">
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">ZSC OJ <span class="caret"></span></a>
                    <ul class="dropdown-menu" role="menu">
                        <li><a href="#">软件开发 14B (Only Test)</a></li>
                        <li><a href="#">软件开发 16A</a></li>
                        <li><a href="#">软件开发 16B</a></li>
                    </ul>
                </li>
                @yield('nav-extend-left')
            </ul>
            <ul class="nav navbar-nav navbar-right">
                @yield('nav-extend-right')
                @if(isset($admin) ? $admin : false)
                    @include('admin.share.extend')
                @else
                    <li><a href="{{route('auth')}}">Log in</a></li>
                @endif
                <li class="visible-xs">
                    <form action="/" role="search" class="navbar-form navbar-right"
                          style="margin-left: 0; margin-right: 0">
                        <div class="input-group">
                            <input type="text" class="form-control" placeholder="Your ZSC - OJ ID"
                                   id="search-data-nav">
                            <span class="input-group-btn">
                                <button type="button" role="button" onclick="navToUrl('#search-data-nav');"
                                        class="btn btn-primary">Search</button>
                            </span>
                        </div>
                    </form>
                </li>
            </ul>
        </div>
    </div>
</nav>

@yield('content')

<footer>
    <div>
        <p class="text-center">&copy; 2016 <a href="http://www.LiuACG.com">LiuACG</a></p>
    </div>
</footer>

<script src="/js/jquery-3.1.1.min.js"></script>
<script src="/js/bootstrap.min.js"></script>
<script charset="UTF-8" type="text/javascript">
    function navToUrl(str) {
        var tmp = $(str).val().trim();
        window.location.href = '{{route('user-query',['id'=>''])}}' + '/' + tmp;
    }
</script>

@yield('script')

</body>
</html>