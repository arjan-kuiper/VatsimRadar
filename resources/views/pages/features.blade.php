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
                        <a class="nav-link active" href="#">Features</a>
                    </li>--}}
                    <li class="nav-item">
                        <a class="nav-link" href="/faq">FAQ</a>
                    </li>
                </ul>
            </div>
        </nav>
    </body>
</html>