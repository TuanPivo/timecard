<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home Page</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/fullcalendar@5.7.2/main.min.css">
    <style>
        body {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            background-color: #f8f9fa;
        }

        .header {
            background-color: #ffffff;
            padding: 10px;
            border-bottom: 1px solid #dee2e6;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .header h2 {
            margin: 0;
        }

        .main {
            display: flex;
            flex: 1;
        }

        .sidebar {
            min-width: 250px;
            max-width: 250px;
            background-color: #ffffff;
            border-right: 1px solid #dee2e6;
            padding: 10px;
        }

        .sidebar a {
            color: #333;
            text-decoration: none;
            padding: 15px;
            display: block;
            border-radius: 4px;
        }

        .sidebar a:hover {
            background-color: #f1f1f1;
        }

        .sidebar .nav-item:last-child {
            border-bottom: none;
        }

        .content {
            flex: 1;
            padding: 20px;
            overflow-y: auto;
        }

        .footer {
            background-color: #ffffff;
            padding: 10px;
            border-top: 1px solid #dee2e6;
            text-align: center;
        }
    </style>
</head>

<body>
    @if (!Auth::check())
        @include('layout.header')
    @endif
    <div class="main">
        @include('layout.sidebar')
        <div class="content">
            @yield('content')
        </div>
    </div>
    @include('layout.footer')
</body>
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.7.2/main.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.7.2/locales-all.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-Piv4xVNRyMGpqkS2by6br4gNJ7DXjqk09RmUpJ8jgGtD7zP9yug3goQfGII0yAns"
            crossorigin="anonymous"></script>
</html>
