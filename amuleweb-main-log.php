<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1" />
	<title>Log - mobileMule</title>
	<link rel="stylesheet" href="//ajax.googleapis.com/ajax/libs/jquerymobile/1.4.2/jquery.mobile.min.css" />
	<link rel="stylesheet" href="mystyle.css" />
	<script src="//ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
    <script src="myscript.js"></script>
    <script src="//ajax.googleapis.com/ajax/libs/jquerymobile/1.4.2/jquery.mobile.min.js"></script>
</head>
<body>
	<div data-role="page" id="log" class="type-interior">

		<div data-role="header" data-position="inline">
			<a href="#" data-rel="back" data-icon="arrow-l">Back</a>
			<h1>Log</h1>
			<a href="#" id="btMenu" class="hideOnDesktop" data-icon="home" onClick="showMenu();">Menu</a>
		</div><!-- /header -->

		<div data-role="content">
			<div class="content-primary">
				<div data-role="collapsible"  data-content-theme="c" data-collapsed="false">
					<h3>Log</h3>
					<!--  <textarea style="height: 200px;" rows="8" name="log" id="log"><?php  echo amule_get_log( 0 ); ?></textarea>-->
					<p style="white-space: normal;"><?php  echo amule_get_log( 0 ); ?></p>
				</div>
				<div data-role="collapsible"  data-content-theme="c">
					<h3>Server info</h3>
					<textarea style="height: 200px;" rows="8" name="serverinfo" id="serverinfo"><?php echo amule_get_serverinfo( 0 );?></textarea>
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
</body>
</html>
