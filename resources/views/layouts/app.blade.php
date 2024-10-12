<!DOCTYPE html>
<html>
<head>
    <title>COVID Vaccine Registration</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <style>
        body {
            background-color: #f4f6f9;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            font-family: Arial, sans-serif;
        }

        .container {
            background-color: #fff;
            padding: 2rem;
            border-radius: 10px;
            box-shadow: 0px 4px 15px rgba(0, 0, 0, 0.1);
            max-width: 500px;
            width: 100%;
            text-align: center;
        }

        h2 {
            color: #333;
            margin-bottom: 1.5rem;
        }

        .form-group {
            margin-bottom: 1rem;
            text-align: left;
        }

        .form-control {
            width: 100%;
            padding: 0.5rem;
            border-radius: 5px;
            border: 1px solid #ddd;
            font-size: 1rem;
        }

        .btn-success {
            background-color: #1b7142;
            border: none;
            padding: 0.5rem 1.5rem;
            border-radius: 5px;
            color: #fff;
            font-size: 1rem;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .btn-success:hover {
            background-color: #239c60;
        }

        .btn-primary {
            background-color: #1360ae;
            border: none;
            padding: 0.5rem 0.5rem;
            border-radius: 5px;
            color: #fff;
            font-size: 0.75rem;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .btn-primary:hover {
            background-color: #3580ca;
        }

        select.form-control {
            appearance: none;
        }

        #loading-message {
            font-size: 0.9rem;
            color: #6c757d;
        }

        #error-message {
            font-size: 0.9rem;
            color: #dc3545;
        }

        .required-marker {
            color: red;
            margin-left: 4px;
        }

        .alert-success, .alert-danger {
            padding: 1rem;
            border-radius: 5px;
            margin-bottom: 1rem;
        }

        .alert-success {
            background-color: #d4edda;
            color: #155724;
        }

        .d-none {
            display: none;
        }

        .alert {
            padding: 1rem;
            border-radius: 5px;
            margin-top: 1rem;
        }

        .alert-danger {
            background-color: #f8d7da;
            color: #721c24;
        }

    </style>
</head>
<body>
<div class="container mt-5">
    @yield('content')
</div>
</body>
</html>
