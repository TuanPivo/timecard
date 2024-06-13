<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bootstrap Sidebar Example</title>
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
            background-color: #343a40;
            color: #fff;
        }

        .sidebar a {
            color: #fff;
            text-decoration: none;
        }

        .sidebar .nav-link:hover {
            background-color: #495057;
        }

        .content {
            flex: 1;
            padding: 20px;
        }

        .header {
            background-color: #f8f9fa;
            padding: 10px;
        }
    </style>
</head>

<body>
    @include('layout.sidebar')
    <div class="content">
        <div class="header">
            <h2>Header</h2>
        </div>
        <div class="main-content">
            <p>Welcome to the main content area!</p>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>
