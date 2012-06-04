<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1" />
	<title>Login - mobileMule</title>
	<link rel="stylesheet" href="http://code.jquery.com/mobile/1.0.1/jquery.mobile-1.0.1.min.css" />
	<link rel="stylesheet" href="mystyle.css" />
	<script src="http://code.jquery.com/jquery-1.6.4.min.js"></script>
	<script src="http://code.jquery.com/mobile/1.0.1/jquery.mobile-1.0.1.min.js"></script>
</head>
<body>
	<div data-role="page" id="login">
	
		<div data-theme="a" data-role="header">
			<h3>Login</h3>
		</div>

		<div data-role="content">
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
			<p>&nbsp;<a href="#about" title="about" data-rel="dialog">mobileMule</a> &copy; 2011-12</p>
		</div><!-- /footer -->
		
		<script>
			$( document ).delegate("#login", "pageinit", function() {
				//$('#pass').focus();
				// worckround...
				setTimeout( function(){ $('#pass').focus(); },0 );
			});
		</script>
	</div>
	<!-- IMPORTANT: Update also amuleweb-main-about.php -->
	<div data-role="page" id="about">
		<div data-theme="a" data-role="header">
			<h2>About</h2>
		</div>
		<div data-role="content">
			<p><strong>mobileMule v1.2b</strong><br/>
			coded by muttley<br/>
			copyright 2012<br/>
			<a href="http://code.google.com/p/unuseful-code/" title="mobileMule project page">code.google.com/p/unuseful-code</a><br/><br/>
			<a href="https://www.paypal.com/cgi-bin/webscr?cmd=_donations&business=muttley%2ebd%40gmail%2ecom&lc=IT&item_name=mobileMule&item_number=aMule%20web%20mobile%20skin&currency_code=EUR&bn=PP%2dDonationsBF%3abtn_donate_LG%2egif%3aNonHosted" title="Donate"><img src="https://www.paypalobjects.com/en_GB/i/btn/btn_donate_LG.gif" /></a></p>
		</div>
		<!-- /content -->
	</div>
</body>
</html>
