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
                        <a class="nav-link" href="/faq">FAQ</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="#">Changelog</a>
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
                            <h5 class="card-title">V1.2.0 (?? June 2018) - <b>Current</b></h5>
                            <p class="card-text">
                                <p>
                                    <b>Added</b><br>
                                <ul>
                                    <li>Flightpaths - TTL (Time To Live) of waypoints is 6 hours after creation</li>
                                    <li>Favicon - Support for all devices</li>
                                    <li>About page - Requests for your virtual airline to be shown there can be send to
                                        <a href="mailto:arjanforgames52@gmail.com?SUBJECT=Virtual Airline">VatsimRadar</a>
                                    </li>
                                    <li>Changelog page - Keep track of all updates</li>
                                </ul>
                                </p>
                                <p>
                                    <b>Fixed</b><br>
                                <ul>
                                    <li>Sidebar not scaling well on some devices</li>
                                </ul>
                                </p>
                            </p>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">V1.1.0 (26 May 2018)</h5>
                            <p class="card-text">
                                <p>
                                    <b>Added</b><br>
                                    <ul>
                                        <li>Additional flight information</li>
                                        <li>Show which FIR regions (Center Controllers) are online</li>
                                    </ul>
                                </p>
                                <p>
                                    <b>Changed</b><br>
                                <ul>
                                    <li>Selected flight is now orange instead of yellowish</li>
                                    <li>ATC regions are now more transparent</li>
                                </ul>
                                </p>
                                <p>
                                    <b>Fixed</b><br>
                                    <ul>
                                        <li>Fixed an issue where the selected flight would be deselected upon receiving an update</li>
                                        <li>ATC layer rendering</li>
                                    </ul>
                                </p>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>