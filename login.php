<!DOCTYPE html>
<html>
<head>
    <title>mobileMule</title>

    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>

    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">

    <link rel="stylesheet" href="//ajax.googleapis.com/ajax/libs/jquerymobile/1.4.3/jquery.mobile.min.css"/>

    <script src="//ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
    <script src="//ajax.googleapis.com/ajax/libs/jquerymobile/1.4.3/jquery.mobile.min.js"></script>
    <script>
        (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
            (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
            m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
        })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

        ga('create', 'UA-50409066-3', { 'cookieDomain': 'none' });
        ga('set', 'anonymizeIp', true);
        ga('send', 'pageview');

    </script>
</head>
<body>
<div data-role="page" id="login">

    <div data-role="content">
        <div style="text-align:center; padding: 0px 20px;">
            <a href="https://github.com/elbowz/mobileMule" title="about" data-rel="dialog">
                <img src="login-icon.png" width="100%" style="max-width:256px;" border="0"/>
            </a>
        </div>
        <form action="main.php" method="post" name="reg" data-ajax="false">
            <!-- force Chrome to save password for autocomplete -->
            <input type="text" name="username" style="display: none;">

            <div data-role="fieldcontain">
                <label for="pass">Password</label>
                <input name="pass" id="password" value="" type="password"/>
            </div>
            <div class="ui-field-contain">
                <label for="remember-me">Remember me:</label>
                <input type="checkbox" data-role="flipswitch" name="remember-me" id="remember-me">
            </div>
            <input id="btLogin" name="submit" type="submit" value="Submit"/>
        </form>
    </div>
    <!-- /content -->

    <div data-role="footer">
        <p>&nbsp;<a href="https://github.com/elbowz/mobileMule" title="about" data-rel="dialog" data-transition="pop">mobileMule</a> &copy; 2018</p>
    </div>
</div>
<script>
    $password = $('#password');
    $rememberme = $("#remember-me");

    // Autologin...
    if (authCookie = readCookie('auth')) {

        $rememberme.attr('checked', true).flipswitch().flipswitch('refresh');
        $password.val(authCookie)
        $('#btLogin').trigger('click');
    }

    $(document).on('pagecreate', "#login", function () {

        $('form[name="reg"]').on('submit', function () {

            if ($rememberme.is(':checked')) {
                createCookie('auth', $password.val(), 365);
            } else {
                eraseCookie('auth');
            }
        });

        $password.focus()
            .trigger('tap')
            .trigger('click');
    });

    /* Cookie lib
     src:http://www.quirksmode.org/js/cookies.htm */
    function createCookie(name, value, days) {
        if (days) {
            var date = new Date();
            date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
            var expires = "; expires=" + date.toGMTString();
        }
        else var expires = "";
        document.cookie = name + "=" + value + expires + "; path=/";
    }

    function readCookie(name) {
        var nameEQ = name + "=";
        var ca = document.cookie.split(';');
        for (var i = 0; i < ca.length; i++) {
            var c = ca[i];
            while (c.charAt(0) == ' ') c = c.substring(1, c.length);
            if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length, c.length);
        }
        return null;
    }

    function eraseCookie(name) {
        createCookie(name, "", -1);
    }
</script>
</body>
</html>
