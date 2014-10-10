<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
	<title>mobileMule</title>
    <link href="icon-192x192.png" rel="icon" sizes="192x192" />
    <link href="apple-touch-icon.png" rel="apple-touch-icon" />
    <link href="apple-touch-icon-76x76.png" rel="apple-touch-icon" sizes="76x76" />
    <link href="apple-touch-icon-120x120.png" rel="apple-touch-icon" sizes="120x120" />
    <link href="apple-touch-icon-152x152.png" rel="apple-touch-icon" sizes="152x152" />
    <link rel="stylesheet" href="//netdna.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" />
    <link rel="stylesheet" href="//ajax.googleapis.com/ajax/libs/jquerymobile/1.4.2/jquery.mobile.min.css" />
	<link rel="stylesheet" href="mystyle.css" />
	<script src="//ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
    <script src="myscript.js"></script>
    <script src="//ajax.googleapis.com/ajax/libs/jquerymobile/1.4.2/jquery.mobile.min.js"></script>
    <link rel="stylesheet" type="text/css" href="addtohomescreen.css">
    <script src="addtohomescreen.min.js"></script>
    <script> addToHomescreen({ maxDisplayCount: 4 }); </script>
</head>
<body>
	<div data-role="page" id="status" class="type-interior">

        <div data-role="panel" id="menu-panel" data-display="reveal" data-position-fixed="true">
            <!-- here is injected menù from pagebeforecreate event -->
        </div>

        <div data-role="header" data-position="fixed">
            <a href="#menu-panel" data-rel="close" id="btMenu" data-icon="home" class="ui-btn-left">Menu</a>
            <h1><i class="fa fa-home fa-fw"></i> Status</h1>
            <a href="#" data-rel="back" data-icon="arrow-l" class="ui-btn-right">Back</a>
        </div><!-- /header -->

		<div data-role="content">
            <ul data-role="listview" data-inset="true">
                <li data-role="list-divider">Speed</li>
                <li>
                    <h2 id="speed_down">n / n</h2>
                    <p class="ui-li-aside"><strong>Download / Limit</strong></p>
                </li>
                <li>
                    <h2 id="speed_up">n / n</h2>
                    <p class="ui-li-aside"><strong>Upload / Limit</strong></p>
                </li>
                <li id="server" data-role="list-divider">Server (init ...)</li>
                <li>
                    <h2 id="server-address">x.x.x.x</h2>
                    <p><strong>Address</strong></p>
                </li>
                <li>
                    <h2 id="server-name">init</h2>
                    <p><strong>Name</strong></p>
                </li>
                <li id="kad" data-role="list-divider">Kad (init ...)</li>
                <li><p><strong>note:</strong> this info is refreshed each 3 seconds.</p></li>
            </ul>
		</div>
		<!-- /content -->

        <div data-role="footer" role="contentinfo" class="ui-footer" data-theme="c">
            <a href="#" id="btScrollUp" data-role="button" data-icon="arrow-u" class="ui-btn-right">scroll up</a>
            <p>&nbsp;<a href="amuleweb-main-about.php" title="about" data-rel="dialog" data-transition="pop">mobileMule</a> &copy; 2014</p>
        </div>
        <!-- /footer -->
	</div>
	<!-- /page -->
</body>
</html>
