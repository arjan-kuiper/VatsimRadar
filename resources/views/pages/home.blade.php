@extends('layouts.default')

@section('content')
    <link href="{{ asset('css/map.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('css/please-wait.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('css/flightinfo.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('css/leaflet.css') }}" rel="stylesheet" type="text/css">

    <!-- FOR PRODUCTION -->
    {{--<script>
        console.log = function(){}
    </script>--}}

    <div id="app" class="wrapper">
        <sidebar-component></sidebar-component>
        <div class="container-fluid">
            <map-component></map-component>
        </div>
    </div>

    <script src='{{ asset('js/leaflet.js') }}'></script>
    <script src='{{ asset('js/leaflet-rotation.js') }}'></script>

    <script type="text/javascript" src="{{ asset('js/please-wait.js') }}"></script>
    <script type="text/javascript">
        let message = ['Firing up the APU...', 'Requesting clearance...', 'Priming the engine...', 'Taking off...', 'On final...'];
        let loader = window.loading_screen = window.pleaseWait({
            logo: "{{ asset('img/loading.gif') }}",
            backgroundColor: '#3199da',
            loadingHtml: "<p class='loading-message'>" + message[Math.floor((Math.random() * message.length))] + "</p>"
        });
        let loadingTimer = setInterval(function(){
            if('@{{ loaded }}'){
                loader.finish();
                window.clearInterval(loadingTimer);
            }
        }, 1000);
    </script>
    <script src="{{ asset('js/app.js') }}"></script>
@stop