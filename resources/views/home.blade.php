<!doctype html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>VatsimRadar | 📡</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css?family=Raleway:100,600" rel="stylesheet" type="text/css">
        <link href="{{ asset('css/map.css') }}" rel="stylesheet" type="text/css">
        <link href="{{ asset('css/please-wait.css') }}" rel="stylesheet" type="text/css">
        <link href="{{ asset('css/default.css') }}" rel="stylesheet" type="text/css">
        <link href="{{ asset('css/flightinfo.css') }}" rel="stylesheet" type="text/css">
        <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" rel="stylesheet" type="text/css">
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.13/css/all.css" integrity="sha384-DNOHZ68U8hZfKXOrtjWvjxusGo9WQnrNx2sqG0tfsghAvtVlRW3tvkXWZh58N9jp" crossorigin="anonymous">
    </head>
    <body>
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark" style="z-index: 1337">
            <a class="navbar-brand" href="#">VatsimRadar 📡 <span class="badge badge-warning">BETA</span></a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse order-1" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" href="#">Live Map <span class="sr-only">(current)</span></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Features</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">FAQ</a>
                    </li>
                </ul>
            </div>
            <div class="navbar-collapse collapse order-3">
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item">
                        <a id="engine-replace-client_count" class="nav-link"></a>
                    </li>
                </ul>
            </div>
        </nav>

        <div class="wrapper">
            <nav id="sidebar">
                <!-- Sidebar Header -->
                <div class="sidebar-header">
                    <img id="engine-replace-aircraft_image" width="300px" onerror="this.src='http://via.placeholder.com/300x200'" alt="Sorry! The aircraft type of this airline has no image yet!">
                </div>
                <div class="container">
                    <div class="row">
                        <div class="col-md callsign-background" align="center">
                            <span id="engine-replace-callsign" class="callsign">AAL1520</span>
                        </div>
                    </div>
                    <div class="row row-spacer" align="center">
                        <div class="col-md">
                            <span id="engine-replace-departure_airport" class="airport-title">EHGG</span><br>
                            <span id="engine-replace-departure_airport_iata" class="airport-small">GRQ</span>
                        </div>
                        <div class="col-md">
                            <i class="fas fa-plane fa-2x"></i>
                        </div>
                        <div class="col-md">
                            <span id="engine-replace-destination_airport" class="airport-title">EHAM</span><br>
                            <span id="engine-replace-destination_airport_iata" class="airport-small">AMS</span>
                        </div>
                    </div>
                    <div class="row row-spacer" align="center">
                        <div class="col-md">
                            <table class="table schedule">
                                <thead>
                                    <tr>
                                        <th scope="col"><span class="badge badge-success" style="width:100%">On Schedule</span></th>
                                        <th scope="col">Estimated</th>
                                        <th scope="col">Actual</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <th>Departure</th>
                                        <th id="engine-replace-departure_planned">--:--</th>
                                        <th id="engine-replace-departure_actual">--:--</th>
                                    </tr>
                                    <tr>
                                        <th>Arrival</th>
                                        <th id="engine-replace-destination_planned">--:--</th>
                                        <th id="engine-replace-destination_actual">--:--</th>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="row row-spacer">
                        //TODO Plane info :D
                    </div>
                </div>
            </nav>
            <div class="container-fluid">
                <div id="map"></div>
            </div>
        </div>


        <script type="text/javascript" src="{{ asset('js/please-wait.js') }}"></script>
        <script type="text/javascript">
            let message = ['Firing up the APU...', 'Requesting clearance...', 'Priming the engine...', 'Taking off...', 'On final...'];
            let loader = window.loading_screen = window.pleaseWait({
                logo: "{{ asset('img/loading.gif') }}",
                backgroundColor: '#3199da',
                loadingHtml: "<p class='loading-message'>" + message[Math.floor((Math.random() * message.length))] + "</p>"
            });
            let loadingTimer = setInterval(function(){
                if(loaded){
                    loader.finish();
                    window.clearInterval(loadingTimer);
                }
            }, 1000);
        </script>
        <script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js" integrity="sha384-smHYKdLADwkXOn1EmN1qk/HfnUcbVRZyYmZ4qpPea6sjB/pTJ0euyQp0Mk8ck+5T" crossorigin="anonymous"></script>
        <script src="{{ asset('js/map.js') }}"></script>
        <script src='https://maps.googleapis.com/maps/api/js?key=AIzaSyBmfdNGwHi4CTLG4b8CQs0WrgCLecOETVs&callback=startRenderer' async defer></script>
        <script>
            window.onload = function(){
                google.maps.event.addListener(map, 'bounds_changed', function() {
                    updateMarkers();
                });
            };
        </script>
    </body>
</html>
