<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1" />
	<title>Add ed2k - mobileMule</title>
	<link rel="stylesheet" href="//ajax.googleapis.com/ajax/libs/jquerymobile/1.4.2/jquery.mobile.min.css" />
	<link rel="stylesheet" href="mystyle.css" />
	<script src="//ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
    <script src="myscript.js"></script>
    <script src="//ajax.googleapis.com/ajax/libs/jquerymobile/1.4.2/jquery.mobile.min.js"></script>
</head>
<body>
	<div data-role="page" id="footer" class="type-interior">

		<div data-role="header" data-position="inline">
			<a href="#" data-rel="back" data-icon="arrow-l">Back</a>
			<h1>Add ed2k</h1>
			<a href="#" id="btMenu" class="hideOnDesktop" data-icon="home" onClick="showMenu();">Menu</a>
		</div><!-- /header -->

		<div data-role="content">
			<div class="content-primary">
	            <form action="footer.php" method="post" name="formlink" data-ajax="false">
					
					<label for="pass">Ed2k link:</label> <input name="ed2klink" type="text" id="ed2klink">
					
					<label for="selectcat" class="select">Category:</label>
					<select name="selectcat" id="selectcat" data-native-menu="false">
			        <?php
						$cats = amule_get_categories();
						if ( $HTTP_GET_VARS["Submit"] != "" ) {
							$link = $HTTP_GET_VARS["ed2klink"];
							$target_cat = $HTTP_GET_VARS["selectcat"];
							$target_cat_idx = 0;
				
							foreach($cats as $i => $c) {
								if ( $target_cat == $c) $target_cat_idx = $i;
							}
				
							if ( strlen($link) > 0 ) {
								$links = split("ed2k://", $link);
								foreach($links as $linkn) {
								    amule_do_ed2k_download_cmd("ed2k://" . $linkn, $target_cat_idx);
								}
							}
							echo '<p><strong>Added ed2k link</strong></p>';
						}
				
						foreach($cats as $c) {
							echo  '<option>', $c, '</option>';
						}
					?>
			        </select>
				     <br/>
					<input type="submit" name="Submit" value="Download link" />
				</form>
			</div>
			<!--/content-primary -->

			<div class="content-secondary">
				<div class="menu" data-role="collapsible" data-collapsed="true" data-theme="b" data-content-theme="d">
					<h3>Menu</h3>
					<ul data-role="listview" data-theme="c" data-dividertheme="d" data-inset="true">
						<li><a href="amuleweb-main-status.php">Status</a></li>
						<li><a href="amuleweb-main-dload-real.php">Downloads</a></li>
						<li><a href="amuleweb-main-upload.php">Uploads</a></li>
						<li><a href="amuleweb-main-search.php">Search</a></li>
						<li><a href="amuleweb-main-prefs.php">Configurations</a></li>
						<li><a href="amuleweb-main-stats.php">Statistics</a></li>
						<li><a href="amuleweb-main-graph.php">Graphs</a></li>
						<li><a href="amuleweb-main-log.php">Log</a></li>
						<li data-theme="a"><a href="footer.php">Add ed2k</a></li>
						<li><a href="login.php">Logout</a></li>
					</ul>
				</div>
				<a href="#" id="btScrollUp" class="hideOnDesktop" data-role="button" data-icon="arrow-u" data-iconpos="right" onClick="scrollUp();">scroll up</a>
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
