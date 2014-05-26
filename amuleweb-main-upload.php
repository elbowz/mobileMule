<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1" />
	<title>Uploads - mobileMule</title>
	<link rel="stylesheet" href="//ajax.googleapis.com/ajax/libs/jquerymobile/1.4.2/jquery.mobile.min.css" />
	<link rel="stylesheet" href="mystyle.css" />
	<script src="//ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
    <script src="myscript.js"></script>
    <script src="//ajax.googleapis.com/ajax/libs/jquerymobile/1.4.2/jquery.mobile.min.js"></script>
</head>
<body>
	<div data-role="page" id="uploads" class="type-interior">

		<div data-role="header" data-position="inline">
			<a href="#" data-rel="back" data-icon="arrow-l">Back</a>
			<h1>Uploads</h1>
			<a href="#" id="btMenu" class="hideOnDesktop" data-icon="home" onClick="showMenu();">Menu</a>
		</div><!-- /header -->

		<div data-role="content">
			<div class="content-primary">
               <?php
					function CastToXBytes($size)
					{
						if ( $size < 1024 ) {
							$result = $size . " b";
						} elseif ( $size < 1048576 ) {
							$result = ($size / 1024.0) . " kb";
						} elseif ( $size < 1073741824 ) {
							$result = ($size / 1048576.0) . " mb";
						} else {
							$result = ($size / 1073741824.0) . " gb";
						}
						return $result;
					}
					$uploads = amule_load_vars("uploads");
					echo '<ul data-role="listview" data-filter="true" data-filter-placeholder="Search file..." data-divider-theme="d"">';
					echo '<li data-role="list-divider">Uploads<span class="ui-li-count">'.count($uploads).'</span></li>';
					foreach ($uploads as $file) {
						echo '<li data-filtertext="',$file->short_name, '">';
						
						echo "<h3>", $file->short_name, "</h3>";
						
						echo "<p>", $file->user_name, "&nbsp;-&nbsp;", CastToXBytes($file->xfer_up), "up&nbsp;-&nbsp;",
							CastToXBytes($file->xfer_down),  "down&nbsp;-&nbsp;", ($file->xfer_speed > 0) ? (CastToXBytes($file->xfer_speed) . "/s") : "", "</p>";
							
						echo "</li>";
					}
					echo '</ul>';
				?>
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
		<script>
		</script>
	</div>
	<!-- /page -->
</body>
</html>
