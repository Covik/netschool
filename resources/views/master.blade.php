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
                <div id="user__avatar"><img src="http://www.adtechnology.co.uk/images/UGM-default-user.png" alt="" /></div><!--
                --><div id="user__name">{{ $au->name }}</div>
            </div>
            <ul id="navigation">
                @if($au->isAdmin())
                    <li class="navigation--active">
                        <div class="navigation__icon">
                            <i class="glyphicon glyphicon-home"></i>
                        </div>
                        <a href="/">Početna</a>
                    </li>
                    <li>
                        <div class="navigation__icon">
                            <i class="glyphicon glyphicon-file"></i>
                        </div>
                        <a href="/files">Datoteke</a>
                    </li>
                    <li>
                        <div class="navigation__icon">
                            <i class="glyphicon glyphicon-user"></i>
                        </div>
                        <a href="/professors">Nastavnici</a>
                    </li>
                    <li>
                        <div class="navigation__icon">
                            <i class="glyphicon glyphicon-education"></i>
                        </div>
                        <a href="/students">Učenici</a>
                    </li>
                    <li>
                        <div class="navigation__icon">
                            <i class="glyphicon glyphicon-send"></i>
                        </div>
                        <a href="/courses">Smjerovi</a>
                    </li>
                @endif
            </ul>
        </aside>
        <main id="content">
            <div id="error-log"></div>
            @yield('content')
        </main>
    </body>
</html>