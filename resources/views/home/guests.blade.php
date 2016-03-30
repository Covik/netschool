<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <title>Prijava - NetSchool</title>
        <link rel="stylesheet" type="text/css" href="/css/login-style.css" />
        <script src="https://code.jquery.com/jquery-latest.min.js"></script>
        <script>
            $.ajaxSetup({
                headers: {
                    'X-CSRF-Token': '<?php echo csrf_token(); ?>'
                }
            });
        </script>
        <script src="/scripts/login.js"></script>
    </head>
    <body>
        <div id="error-log"></div>
        <div id="login-box">
            <ul>
                <li><button class="login__tab login__tab--active">Prijava</button></li><li><button class="login__tab">Registracija</button></li>
            </ul>
            <div id="login__tabs__content">
                <section class="login__tab__content">
                    <form class="lr__form" action="/login">
                        <label>
                            <div class="input-name">E-mail:</div>
                            <input type="email" name="email" autocomplete />
                        </label>
                        <label>
                            <div class="input-name">Lozinka:</div>
                            <input type="password" name="password" />
                        </label>
                        <label>
                            <div class="input-name">Zapamti prijavu:</div>
                            <input type="checkbox" class="input--auto" name="remember" />
                        </label>
                        <div class="form__submit">
                            <input type="submit" value="Prijavi se" class="input--auto" />
                        </div>
                    </form>
                </section>
                <section class="login__tab__content">
                    <form class="lr__form" action="/register">
                        <label>
                            <div class="input-name">Ime i prezime:</div>
                            <input type="text" name="name" />
                        </label>
                        <label>
                            <div class="input-name">E-mail:</div>
                            <input type="email" name="email" autocomplete="off" />
                        </label>
                        <label style="position: relative;">
                            <div id="registration__passsword__hider"><i class="glyphicon glyphicon-eye-open"></i></div>
                            <div class="input-name">Lozinka:</div>
                            <input type="password" name="password" id="registration__password" />
                        </label>
                        <div class="form__submit">
                            <input type="submit" value="Registiraj se" class="input--auto" />
                        </div>
                    </form>
                </section>
            </div>
        </div>
    </body>
</html>