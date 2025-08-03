<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>K2 Computer - @yield('title')</title>
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body>
<!-- Navbar -->
@include('partials.navbar')

<!-- Main Content -->
<div class="container mt-4">
    @yield('content')
</div>

<!-- Footer -->
@include('partials.footer')

<script src="{{ asset('js/app.js') }}"></script>
</body>
</html>
