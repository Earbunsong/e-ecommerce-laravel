<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>K2 Computer</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="d-flex flex-column min-vh-100">
@include('components.header')

<main class="flex-fill container my-4">
    @yield('content')
</main>

@include('components.footer')
</body>
</html>
