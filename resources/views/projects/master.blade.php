<!DOCTYPE html>
<html lang="en-gb" dir="ltr">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Roda Dashboard</title>
    <link rel="shortcut icon" href="{{ asset('images/fav.png') }}" type="image/x-icon">
    <link rel="apple-touch-icon-precomposed" href="{{ asset('images/fav.png') }}">
    <link rel="stylesheet" href="{{ asset('css/uikit-2.css') }}">
    <script src="{{ asset('jquery/jquery-3.2.1.min.js') }}"></script>
    <script src="{{ asset('js/uikit.min.js') }}"></script>
    <script src="{{ asset('js/heatmap.min.js') }}"></script>
    @stack('styles')
</head>

<body>

<div class="uk-container uk-container-center uk-margin-top uk-margin-large-bottom">

    <nav class="uk-navbar uk-margin-large-bottom">
        <a class="uk-navbar-brand uk-hidden-small" href="{{ route('dashboard') }}">{{ \Illuminate\Support\Facades\Auth::user()->name }}</a>
        <ul class="uk-navbar-nav uk-hidden-small">
            <li>
                <a href="{{ route('dashboard') }}">Dashboard</a>
            </li>
            <li>
                <a href="{{ route('auth.logout') }}">Logout</a>
            </li>
        </ul>
        <a href="#offcanvas" class="uk-navbar-toggle uk-visible-small" data-uk-offcanvas></a>
        <div class="uk-navbar-brand uk-navbar-center uk-visible-small">Roda</div>
    </nav>
    @yield('content')
</div>
</body>
@stack('scripts')
</html>
