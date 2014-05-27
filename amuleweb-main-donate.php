<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1" />
	<title>Status - mobileMule</title>
	<link rel="stylesheet" href="//ajax.googleapis.com/ajax/libs/jquerymobile/1.4.2/jquery.mobile.min.css" />
	<link rel="stylesheet" href="mystyle.css" />
	<script src="//ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
    <script src="myscript.js"></script>
    <script src="//ajax.googleapis.com/ajax/libs/jquerymobile/1.4.2/jquery.mobile.min.js"></script>
</head>
<body>
	<div data-role="page" id="status" class="type-interior">

        <div data-role="panel" id="menu-panel" data-display="push">
            <!-- here is injected menù from pagebeforecreate event -->
        </div>

        <div data-role="header" data-position="inline">
            <a href="#menu-panel" data-rel="close" id="btMenu" data-icon="home">Menu</a>
            <h1>Donate</h1>
            <a href="#" data-rel="back" data-icon="arrow-l">Back</a>
        </div><!-- /header -->

		<div data-role="content">
				If you like mobileMule please consider to make a donation
                <div style="text-align: center; margin-top: 15px;">
                    <a href="https://www.paypal.com/cgi-bin/webscr?cmd=_donations&business=muttley%2ebd%40gmail%2ecom&lc=IT&item_name=mobileMule&item_number=aMule%20web%20mobile%20skin&currency_code=EUR&bn=PP%2dDonationsBF%3abtn_donate_LG%2egif%3aNonHosted" title="Donate"><img src="paypal.png" /></a></p>
                </div>
                You'll receive <strong>mobileMule Donation Pack</strong> with new panels:
                <ul>
                    <li>Search</li>
                    <li>Configurations</li>
                    <li>Add ed2k</li>
                </ul>

                ...and you can help and support the development of project.
		</div>
		<!-- /content -->

        <div data-role="footer" role="contentinfo" class="ui-footer" data-theme="c">
            <a href="#" id="btScrollUp" data-role="button" data-icon="arrow-u" data-iconpos="right">scroll up</a>
            <p>&nbsp;<a href="amuleweb-main-about.php" title="about" data-rel="dialog" data-transition="pop">mobileMule</a> &copy; 2014</p>
        </div>
		<!-- /footer -->
	</div>
	<!-- /page -->
</body>
</html>
