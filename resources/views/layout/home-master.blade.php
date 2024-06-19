<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <meta name="referrer" content="no-referrer">
    <link rel="icon" type="image/x-icon" href="assets/img/renthubicon.png">
    <title>RentHub</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
</head>
<body class="bg-gradient-to-r from-blue-200 via-blue-100 to-blue-50">
    @include('layout.home-header')

    <main>
        @yield('content')
    </main>

    @include('layout.home-footer')
</body>
</html>
