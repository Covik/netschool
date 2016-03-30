<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>{{ $title }} - NetSchool</title>
        <link rel="stylesheet" type="text/css" href="/css/style.css">
        <script src="https://code.jquery.com/jquery-latest.min.js"></script>
        @yield('head')
    </head>
    <body>
        <aside id="sidebar">
            <div id="user">
                <div id="user__avatar"><img src="http://www.adtechnology.co.uk/images/UGM-default-user.png" alt="" /></div><!--
                --><div id="user__name">{{ $user->name }}</div>
            </div>
            <ul id="navigation">
                @yield('menu')
            </ul>
        </aside>
        @yield('content')
    </body>
</html>