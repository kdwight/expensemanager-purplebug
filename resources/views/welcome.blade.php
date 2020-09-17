<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>PurpleBug-Exam</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">

    <!-- Styles -->
    <style>
        .full-height {
            height: 50px;
        }

        .top-right {
            position: absolute;
            right: 10px;
            top: 18px;
        }

        .links>a {
            color: #636b6f;
            padding: 0 25px;
            font-size: 13px;
            font-weight: 600;
            letter-spacing: .1rem;
            text-decoration: none;
            text-transform: uppercase;
        }
    </style>
</head>

<body>
    <div class="full-height">
        @if (Route::has('login'))
        <div class="top-right links">
            @auth
            <a href="{{ url('/dashboard') }}">Home</a>
            @else
            <a href="{{ route('login') }}">Login</a>

            @if (Route::has('register'))
            <a href="{{ route('register') }}">Register</a>
            @endif
            @endauth
        </div>
        @endif
    </div>

    <div>
        <h4>Terminal Codes</h4>
        <li>
            <code>`composer install`</code>
        </li>
        <li>
            <code>`npm install`</code>
        </li>
        <li>
            <code>`cp .env.example .env`</code>
        </li>
        <li>
            <code>`php artisan key:generate`</code>
        </li>
        <li>
            <code>`php artisan migrate:fresh --seed`</code>
        </li>

        <h4>Login Credentials</h4>

        <li>
            Url: <a href="http://expensemanager.test/login" target="__blank">http://expensemanager.test/login</a>
        </li>
        <li>
            Email: purplebug@exam.test
        </li>
        <li>
            Password: password
        </li>
    </div>

    <br>
    <div>
        <h4>
            Softwares Used:
        </h4>

        <li>
            Laragon 4.0.16
        </li>
        <li>
            Laragon's HeidiSQL
        </li>
        <li>
            VSCode
        </li>
        <li>
            Chrome with Vue.js devtools extension
        </li>
    </div>

    <br>
    <div>
        <h4>
            Specs
        </h4>

        <li>
            Laravel 5.8
        </li>
        <li>
            php 7.2.19
        </li>
        <li>
            mysql 5.7.24
        </li>
        <li>
            vue 2.5.17
        </li>
        <li>
            bootstrap-vue 2.17.1,
        </li>
        <li>
            chart.js 2.9.3
        </li>
    </div>

    <br>
    <div>
        <h4>
            Timestamp
        </h4>

        <span>
            Time started: 9:00 am Sep. 17, 2020
        </span>
        <br>
        <span>
            Time finished: 23:13 Sep. 17, 2020
        </span>
    </div>
</body>

</html>