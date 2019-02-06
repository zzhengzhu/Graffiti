<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Graffiti') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}"></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet" type="text/css">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

    <!-- JQuery -->
    <script src="https://ajax.aspnetcdn.com/ajax/jQuery/jquery-3.3.1.min.js"></script>

    <!-- Leaflet plugins-->
    <!-- font awesome-->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.5.0/css/all.css" 
    integrity="sha384-B4dIYHKNBt8Bc12p+WXckhzcICo0wtJAoU8YZTY5qE0Id1GSseTk6S+L3BlXeVIU" 
    crossorigin="anonymous">
    <link href="{{ asset('css/leaflet-beautify-marker-icon.css') }}" rel="stylesheet">
    <script src="{{ asset('js/leaflet-beautify-marker-icon.js') }}"></script>
    <!-- pulse marker -->
    <link href="{{ asset('css/L.Icon.Pulse.css') }}" rel="stylesheet">
    <script src="{{ asset('js/L.Icon.Pulse.js') }}"></script>
    <!-- icon picker -->
    <link href="{{ asset('css/fontawesome-iconpicker.css') }}" rel="stylesheet">
    <script src="{{ asset('js/fontawesome-iconpicker.js') }}"></script>
</head>
<body>
    <div id="app">
    <!-- Navbar -->
        @include('inc.navbar')
        @include('inc.messages')
        @yield('content')
    </div>
</body>
</html>
