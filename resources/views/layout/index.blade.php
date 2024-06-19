<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home Page</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
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
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    @yield('customJs')

</body>
    <!-- jQuery -->
    <script src="{{ asset('assets/plugins/jquery/jquery.min.js') }}"></script>
    
    <script type="text/javascript">
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
            });
        Dropzone.autoDiscover = false;
    </script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.14/index.global.min.js"></script>
</html>
