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


        <div data-role="panel" id="menu-panel" data-display="reveal" data-position-fixed="true">
            <!-- here is injected menù from pagebeforecreate event -->
        </div>

        <div data-role="header" data-position="fixed">
            <a href="#menu-panel" data-rel="close" id="btMenu" data-icon="home" class="ui-btn-left">Menu</a>
            <h1>Add ed2k</h1>
            <a href="#" data-rel="back" data-icon="arrow-l" class="ui-btn-right">Back</a>
        </div><!-- /header -->

		<div data-role="content">
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
		<!-- /content -->

        <div data-role="footer" role="contentinfo" class="ui-footer" data-theme="c">
            <a href="#" id="btScrollUp" data-role="button" data-icon="arrow-u" class="ui-btn-right">scroll up</a>
            <p>&nbsp;<a href="amuleweb-main-about.php" title="about" data-rel="dialog" data-transition="pop">mobileMule</a> &copy; 2014</p>
        </div>
        <!-- /footer -->
		<script>
		</script>
	</div>
	<!-- /page -->
</body>
</html>
