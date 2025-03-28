<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>@yield('title', 'Dokumen PDF')</title>
    @yield('head')
    <style>
        /* Styling dasar layout */
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        h2 {
            text-align: center;
        }
    </style>
    <!-- Sertakan Toastr CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet"/>
</head>
<body>
    @yield('content')

    @stack('scripts')
</body>
</html>
