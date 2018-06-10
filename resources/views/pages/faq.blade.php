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
                    <li class="nav-item">
                        <a class="nav-link" href="/about">About</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="#">FAQ</a>
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
                    <p>
                        <h5>How often does the live map get updated?</h5>
                        <p>VATSIM updates are limiting our refresh speeds which means that the
                        map will be updated approximately every 1-2 minutes.</p>
                    </p>
                    <hr>
                    <p>
                    <h5>How can my Virtual Airline get featured on the about page?</h5>
                        <p>If you want your Virtual Airline to be featured on the about page you can send an email
                        to <a href="mailto:arjanforgames52@gmail.com?SUBJECT=Virtual Airline">VatsimRadar</a></p>
                    </p>
                    <hr>
                    <p>
                        <h5>What are these red, blue and gray circles?</h5>
                        <p>Active air traffic controllers. The radius is an estimation, but proves to be fairly accurate.
                        If you hover over the circles you will see information regarding that specific air traffic controller.</p>
                    </p>
                    <hr>
                    <p>
                        <h5>What are all those '<i>Sorry! The aircraft type of this airline has no image yet!</i>' ?</h5>
                        <p>VatsimRadar is devoting serious effort to supply as much aircraft imagery as possible
                        but there are <strong>a lot</strong> of different airlines and <strong>a lot</strong> of different aircraft.
                        VatsimRadar is working together with <a href="https://www.aviationimagenetwork.com/">Aviation Image Network</a> to
                        hopefully provide all the required imagery in the near future. It could also occur that a pilot is using made up airline
                        codes, incorrect formatted codes, or flying an aircraft type that the airline does not have in real life.</p>
                    </p>
                    <hr>
                    <p>
                    <h5>What do you mean by '<i>We couldn't retrieve any information about this flight.</i>' ?</h5>
                        <p>Every piece of data regarding aircraft and ATC is pulled from VATSIM's publicly available
                        data file. As a pilot you can connect to the VATSIM network without needing to fill in your flightplan beforehand.
                        You will get this message when too much information is missing, because there will be nothing worth showing.</p>
                    </p>
                    <hr>
                    <p>
                    <h5>Can I view past flight?</h5>
                        <p>Soon!</p>
                    </p>
                </div>
            </div>
        </div>
    </body>
</html>