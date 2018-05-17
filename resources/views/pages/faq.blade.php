<html>
<head>
    @include('includes.header')
</head>
    <body style="background-color: #ecf0f1;">
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark" style="z-index: 1337">
            <a class="navbar-brand" href="/">VatsimRadar 📡 <span class="badge badge-warning">BETA</span></a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse collapse order-1 order-md-0 dual-collapse2" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" href="/">Live Map</a>
                    </li>
                    {{--<li class="nav-item">
                        <a class="nav-link" href="/features">Features</a>
                    </li>--}}
                    <li class="nav-item">
                        <a class="nav-link active" href="#">FAQ</a>
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
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">How often does the live map get updated?</h5>
                            <p class="card-text">VATSIM updates are limiting our refresh speeds which means that the
                                map will be updated approximately every 1-2 minutes.</p>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">What software do you use for all of this?</h5>
                            <p class="card-text">The backend is powered by Laravel which supplies data to Vue for the frontend.
                            The library we are using for the map is <a href="https://leafletjs.com/">Leaflet</a>.
                            For display layers we use <a href="https://www.openstreetmap.org">OpenStreetMap</a> and
                            <a href="https://www.mapbox.com/">Mapbox</a></p>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">What are these red, blue and gray circles?</h5>
                            <p class="card-text">Active air traffic controllers. The radius is an estimation, but proves to be fairly accurate.
                            If you hover over the circles you will see information regarding that specific air traffic controller.</p>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">What are all those '<i>Sorry! The aircraft type of this airline has no image yet!</i>' ?</h5>
                            <p class="card-text">VatsimRadar is devoting serious effort to supply as much aircraft imagery as possible
                            but there are <strong>a lot</strong> of different airlines and <strong>a lot</strong> of different aircraft.
                            VatsimRadar is working together with <a href="https://www.aviationimagenetwork.com/">Aviation Image Network</a> to
                            hopefully provide all the required imagery in the near future.</p>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">What do you mean by '<i>We couldn't retrieve any information about this flight.</i>' ?</h5>
                            <p class="card-text">Every piece of data regarding aircraft and ATC is pulled from VATSIM's publicly available
                            data file. As a pilot you can connect to the VATSIM network without needing to fill in your flightplan beforehand.
                            You will get this message when too much information is missing, because there will be nothing worth showing.</p>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Why can I not see flight information on my mobile device?</h5>
                            <p class="card-text">We are still working on a mobile version for the sidebar. For now, performance on
                            mobile devices is our main priority but it will definitely get added in the near future.</p>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Can I view my flightpath somewhere?</h5>
                            <p class="card-text">No. As of now you can not view your flightpath. This is one of the highest priority
                            features that we want to add next.</p>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Can I plan flights on this website?</h5>
                            <p class="card-text">No. You are not, and won't be, able to plan flights on this website. This is solely for
                            the purpose of viewing live VATSIM aircraft and ATC.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>