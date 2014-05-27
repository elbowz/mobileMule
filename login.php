<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1" />
	<title>Login - mobileMule</title>
    <link rel="stylesheet" href="//ajax.googleapis.com/ajax/libs/jquerymobile/1.4.2/jquery.mobile.min.css" />
    <script src="//ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
    <script src="//ajax.googleapis.com/ajax/libs/jquerymobile/1.4.2/jquery.mobile.min.js"></script>
</head>
<body>
	<div data-role="page" id="login">
	
		<div data-theme="a" data-role="header">
			<h3>Login</h3>
		</div>

		<div data-role="content">
            <a id="btNewVersion" href="https://github.com/elbowz/mobileMule" class="ui-btn ui-corner-all ui-btn-b" style="display: none;"></a>

			<div style="text-align:center; padding: 0px 20px;">
				<a href="#about" title="about" data-rel="dialog"><img src="amule_logo.png" width="100%" style="max-width:260px;" border="0" /></a>
			</div>
			<form action="" method="post" name="login" data-ajax="false">
				<div data-role="fieldcontain">
					<label for="pass">Password</label> <input name="pass" id="pass" value="" type="password" />
				</div>
				<input name="submit" type="submit" value="Submit" />
			</form>
		</div><!-- /content -->

        <div data-role="footer" data-theme="c">
            <p>&nbsp;<a href="https://github.com/elbowz/mobileMule" title="about" data-rel="dialog" data-transition="pop">mobileMule</a> &copy; 2014</p>
        </div>
	</div>
    <script>
        var currentVersion = '1.5b';

        $(document).on('pagebeforecreate', "#login", function() {
            $.ajax({
                url: "https://rawgithub.com/elbowz/mobileMule/master/latestVersion.js",
                dataType: 'script',
                success: function( ) {
                    if(latestVersion != currentVersion) {
                        console.log('NEW VERSION AVVIABLE')
                        $('#btNewVersion').text(latestVersion + ' version is available!').show();
                    }
                }
            });
        });

        $( document ).on("pageinit", "#login", function() {
            //$('#pass').focus();
            // worckround...
            setTimeout( function(){ $('#pass').focus(); },0 );
        });
    </script>
</body>
</html>
