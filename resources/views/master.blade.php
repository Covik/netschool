<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>{{ $title }} - NetSchool</title>
        <link rel="stylesheet" type="text/css" href="/css/style.css">
        <script src="https://code.jquery.com/jquery-latest.min.js"></script>
        <script>
            $.ajaxSetup({
                headers: {
                    'X-CSRF-Token': '<?php echo csrf_token(); ?>'
                },
                dataType: 'json'
            });
        </script>
        <script src="/scripts/master.js"></script>
        @yield('head')
    </head>
    <body>
        <aside id="sidebar">
            <div id="user">
                <div id="user__avatar"><img src="/images/default-user.png" alt="" /></div><!--
                --><div id="user__name">{{ $au->name }}</div>
            </div>
            {!! $navigation->asUl(['id' => 'navigation']) !!}
        </aside>
        <main id="content">
            <div id="error-log"></div>

            @yield('content')
        </main>
    </body>
</html>