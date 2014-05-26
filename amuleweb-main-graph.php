<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1" />
	<title>Graphs - mobileMule</title>
	<link rel="stylesheet" href="//ajax.googleapis.com/ajax/libs/jquerymobile/1.4.2/jquery.mobile.min.css" />
	<link rel="stylesheet" href="mystyle.css" />
	<script src="//ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
    <script src="myscript.js"></script>
    <script src="//ajax.googleapis.com/ajax/libs/jquerymobile/1.4.2/jquery.mobile.min.js"></script>
</head>
	<?php amule_load_vars("stats_graph"); ?>
<body>
	<div data-role="page" id="graphs" class="type-interior">

		<div data-role="header" data-position="inline">
			<a href="#" data-rel="back" data-icon="arrow-l">Back</a>
			<h1>Graphs</h1>
			<a href="#" id="btMenu" class="hideOnDesktop" data-icon="home" onClick="showMenu();">Menu</a>
		</div><!-- /header -->

		<div data-role="content">
			<div class="content-primary">
				<div data-role="collapsible-set">
					<div data-role="collapsible"  data-content-theme="c" data-collapsed="false">
						<h3>Download-Speed</h3>
						<img src="amule_stats_download.png" width="100%" border="0" />
					</div>
					<div data-role="collapsible"  data-content-theme="c">
						<h3>Upload-Speed</h3>
			        	<img src="amule_stats_upload.png" width="100%" border="0" />
			        </div>
			        <div data-role="collapsible"  data-content-theme="c">
			      		<h3>Connections</h3>
			          	<img src="amule_stats_conncount.png" width="100%" border="0" />
			        </div>
		        </div>
			</div>
			<!--/content-primary -->

            <div id="menu" class="content-secondary">
                <!-- here is injected menù from pagebeforecreate event -->
            </div>
		</div>
		<!-- /content -->

		<div data-role="footer" data-theme="c">
			<p>&nbsp;<a href="amuleweb-main-about.php" title="about" data-rel="dialog" data-transition="pop">mobileMule</a> &copy; 2014</p>
		</div>
		<!-- /footer -->
	</div>
	<!-- /page -->

	<script>
            //App custom javascript
        </script>
</body>
</html>
