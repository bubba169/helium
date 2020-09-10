<!DOCTYPE html>
<html>
    <head>
        <link rel="stylesheet" href="/vendor/helium/css/helium.css">
        <script src="/vendor/helium/js/helium.js" defer></script>
    </head>

    <body>
        <div class="container-fluid">
            <div class="navbar">
                <a class="navbar-brand" href="/admin">Helium CMS</a>
            </div>
            <div class="row">
                <div class="col-auto p-0">
                    @include('helium::partials.navigation')
                </div>
                <div class="col">
                    <div class="container">
                        @yield('content')
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>
