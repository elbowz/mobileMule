<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1" />
	<title>Uploads - mobileMule</title>
    <link rel="stylesheet" href="//netdna.bootstrapcdn.com/font-awesome/4.1.0/css/font-awesome.min.css" />
    <link rel="stylesheet" href="//ajax.googleapis.com/ajax/libs/jquerymobile/1.4.2/jquery.mobile.min.css" />
	<link rel="stylesheet" href="mystyle.css" />
	<script src="//ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
    <script src="myscript.js"></script>
    <script src="//ajax.googleapis.com/ajax/libs/jquerymobile/1.4.2/jquery.mobile.min.js"></script>
</head>
<body>
	<div data-role="page" id="uploads" class="type-interior">

        <div data-role="panel" id="menu-panel" data-display="reveal" data-position-fixed="true">
            <!-- here is injected menù from pagebeforecreate event -->
        </div>

		<div data-role="header" data-position="fixed">
            <a href="#menu-panel" data-rel="close" id="btMenu" data-icon="home" class="ui-btn-left">Menu</a>
            <h1><i class="fa fa-upload fa-fw"></i> Uploads</h1>
            <a href="#" data-rel="back" data-icon="arrow-l" class="ui-btn-right">Back</a>
        </div><!-- /header -->

		<div data-role="content">
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
					echo '<ul data-role="listview" data-filter="true" data-filter-placeholder="Search file...">';
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
			<!--/content-primary -->
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
