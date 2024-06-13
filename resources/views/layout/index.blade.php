<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Uruca K.K</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css">
    <style>
        body {
            display: flex;
            height: 100vh;
            overflow: hidden;
        }

        .sidebar {
            min-width: 250px;
            max-width: 250px;
            background-color: #f8f9fa;
            color: #000000;
        }

        .sidebar a {
            color: #000000;
            text-decoration: none;
        }

        .sidebar .nav-link:hover {
            background-color: #cfcfcf;
        }

        .content {
            flex: 1;
            padding: 20px;
        }

      .header {
            background-color: #f8f9fa;
            padding: 10px;
            display: flex;
            align-items: center;
            border-bottom: 2px solid #dee2e6;
        }
    </style>
</head>

<body>
    @include('layout.sidebar')
    <div class="content">
        @include('layout.header')
        @yield('content')
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>
