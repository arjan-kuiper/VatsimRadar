<html>
<head>
    @include('includes.header')

    <style>
        .partner-img{
            display: flex;
            align-items: center;
            flex-wrap: wrap;
            filter: grayscale(100%);
        }
        .partner-img:hover{
            filter: grayscale(0%);
        }
    </style>
</head>
<body style="background-color: #ecf0f1;">
<nav class="navbar navbar-expand-lg navbar-dark bg-dark" style="z-index: 1337">
    <img class="d-none d-sm-block" width="15%" src="img/logo.svg">
    <img class="d-block d-sm-none" width="50%" src="img/logo.svg">
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse collapse order-1 order-md-0 dual-collapse2" id="navbarNav">
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" href="/">Live Map</a>
            </li>
            <li class="nav-item">
                <a class="nav-link active" href="#">About</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="/faq">FAQ</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="/changelog">Changelog</a>
            </li>
        </ul>
    </div>
</nav>
<div class="container" style="margin-top: 20px; margin-bottom: 20px;">
    <div class="row">
        <div class="col-md">
            <div class="alert alert-warning alert-dismissible fade show" role="alert" align="center">
                Please keep in mind that VatsimRadar is BETA software at the moment
            </div>
        </div>
    </div>
    <hr>
    <div class="row">
        <div class="col-md">
            <p>
                VatsimRadar is a live flight tracker for the VATSIM network. You can view a live map, track your flights
                and view information about flights. VatsimRadar is a work in progress website which means that things
                get added along the way.
            </p>
            <p>
                VatsimRadar was (and is) built using the latest technologies in order to deliver high speed, realtime
                flight tracking. VatsimRadar is inspired by FlightRadar24 and aims to deliver as much information as
                cleanly as possible. Features are being added along the way and requests to add new things are always
                welcome. VatsimRadar strongly depends upon a well founded community and couldn't exist and won't exist
                without enthusiastic hobbyists.
            </p>
        </div>
    </div>
    <hr>
    <h5><strong>Powered By</strong></h5>
    <div class="row text-center">
        <div class="col-2 partner-img">
            <a href="https://laravel.com/" rel="nofollow" target="_blank">
                <img width="100%" src="{{ asset('img/powered-by/laravel.png') }}">
            </a>
        </div>
        <div class="col-2 partner-img">
            <a href="https://vuejs.org/" rel="nofollow" target="_blank">
                <img width="100%" src="{{ asset('img/powered-by/vue.png') }}">
            </a>
        </div>
        <div class="col-2 partner-img">
            <a href="https://www.mysql.com/" rel="nofollow" target="_blank">
                <img width="100%" src="{{ asset('img/powered-by/mysql.png') }}">
            </a>
        </div>
        <div class="col-2 partner-img">
            <a href="https://redis.io/" rel="nofollow" target="_blank">
                <img width="100%" src="{{ asset('img/powered-by/redis.png') }}">
            </a>
        </div>
        <div class="col-2 partner-img">
            <a href="https://leafletjs.com/" rel="nofollow" target="_blank">
                <img width="100%" src="{{ asset('img/powered-by/leaflet.png') }}">
            </a>
        </div>
        <div class="col-2 partner-img">
            <a href="https://www.mapbox.com/" rel="nofollow" target="_blank">
                <img width="100%" src="{{ asset('img/powered-by/mapbox.png') }}">
            </a>
        </div>
    </div>
    <hr>
    <h5><strong>Partners</strong></h5>
    <div class="row text-center">
        <div class="col-md-2 partner-img">
            <a href="http://www.deltavirtual.org/" rel="nofollow" target="_blank">
                <img width="100%" src="{{ asset('img/partners/deltavirtual.png') }}">
            </a>
        </div>
        <div class="col-md-2 partner-img">
            <a href="http://www.canadianxpress.ca/" rel="nofollow" target="_blank">
                <img width="100%" src="{{ asset('img/partners/canadianexpress.png') }}">
            </a>
        </div>
    </div>
</div>
</body>
</html>