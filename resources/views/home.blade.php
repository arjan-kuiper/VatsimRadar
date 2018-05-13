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
    </head>
    <body>
        <div style="height:100%; width:100%;">
            <div id="map"></div>
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
