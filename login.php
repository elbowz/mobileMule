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
</head>
<body>
<div data-role="page" id="login">

    <div data-role="content">
        <a id="btNewVersion" href="https://github.com/elbowz/mobileMule" class="ui-btn ui-corner-all ui-btn-b">checking for new version available...</a>

        <div style="text-align:center; padding: 0px 20px;">
            <a href="https://github.com/elbowz/mobileMule" title="about" data-rel="dialog">
                <img src="login-icon.png" width="100%" style="max-width:260px;" border="0"/>
            </a>
        </div>
        <form action="" method="post" name="login" data-ajax="false">
            <div data-role="fieldcontain">
                <!-- force Chrome to save password for autocomplete -->
                <input type='text' name='username' style="display: none;">
                <label for="pass">Password</label>
                <input name="pass" id="pass" value="" type="password"/>
            </div>
            <input name="submit" type="submit" value="Submit"/>
        </form>
    </div>
    <!-- /content -->

    <div data-role="footer">
        <p>&nbsp;<a href="https://github.com/elbowz/mobileMule" title="about" data-rel="dialog" data-transition="pop">mobileMule</a> &copy; 2014</p>
    </div>
</div>
<script>
    // IMPORTANT: Update also login.php#version, latestVersion.js, main.php
    var currentVersion = 'v2.0.0b';

    $(document).on('pagecreate', "#login", function () {
        $.ajax({
            url: "https://cdn.rawgit.com/elbowz/mobileMule/master/latestVersion.js",
            dataType: 'script',
            beforeSend: function () {
                $.mobile.loading('show');
            },
            success: function () {
                $.mobile.loading('hide');
                if (latestVersion != currentVersion) {
                    $('#btNewVersion').html(latestVersion + ' version is available!');
                } else {
                    $('#btNewVersion').html('you are updated!');
                }
            },
            error: function () {
                $.mobile.loading('hide');
                $('#btNewVersion').html('Something go wrong during the new version check');
            }
        });

        $('#pass').focus()
            .trigger('tap')
            .trigger('click');
    });
</script>
</body>
</html>
