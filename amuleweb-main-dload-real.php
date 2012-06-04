<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1" />
	<title>Downloads - mobileMule</title>
	<link rel="stylesheet" href="http://code.jquery.com/mobile/1.0.1/jquery.mobile-1.0.1.min.css" />
	<link rel="stylesheet" href="mystyle.css" />
	<script src="http://code.jquery.com/jquery-1.6.4.min.js"></script>
	<script src="http://code.jquery.com/mobile/1.0.1/jquery.mobile-1.0.1.min.js"></script>
	<script src="myscript.js"></script>
</head>
<body>
	<div data-role="page" id="dload" class="type-interior">

		<div data-role="header" data-position="inline">
			<a href="#" data-rel="back" data-icon="arrow-l">Back</a>
			<h1>Downloads</h1>
			<a href="#" id="btMenu" class="hideOnDesktop" data-icon="home" onClick="showMenu();">Menu</a>
		</div><!-- /header -->

		<div data-role="content">
			<div class="content-primary">
				<form action="amuleweb-main-dload.php" method="post" name="mainform">
					<input type="hidden" name="command">
					
	        		<div data-role="navbar">
						<ul>
							<li><a href="javascript:formCommandSubmit('pause');" data-icon="grid">Pause</a></li>
							<li><a href="javascript:formCommandSubmit('resume');" data-icon="arrow-r">Resume</a></li>
							<li><a href="javascript:formCommandSubmit('prioup');" data-icon="arrow-u">Prio. up</a></li>
							<li><a href="javascript:formCommandSubmit('priodown');" data-icon="arrow-d">Prio. down</a></li>
							<li><a href="javascript:formCommandSubmit('cancel');" data-icon="delete">Cancel</a></li>
						</ul>
					</div>
					
					<fieldset data-role="controlgroup">
						<legend>Filter (status / category): </legend>
	        			<?php
				        	$all_status = array("all", "Waiting", "Paused", "Downloading");	
				 			if ( $HTTP_GET_VARS["command"] == "filter") {
				 				$_SESSION["filter_status"] = $HTTP_GET_VARS["status"];
				 				$_SESSION["filter_cat"] = $HTTP_GET_VARS["category"];
				 			}
				        	if ( $_SESSION["filter_status"] == '') $_SESSION["filter_status"] = 'all';
				        	if ( $_SESSION["filter_cat"] == '') $_SESSION["filter_cat"] = 'all';
				
				        	echo '<select name="status" id="status" data-native-menu="false"> ';
				        	foreach ($all_status as $s) {
				        		echo '<option value="' . $s . '"', (($s == $_SESSION["filter_status"]) ? ' selected>' : '>'), $s, '</option>';
				        	}
				        	echo '</select>';
				        	//var_dump($_SESSION["filter_cat"]);
				        	echo '<select name="category" id="category" data-native-menu="false">';
							$cats = amule_get_categories();
							foreach($cats as $c) {
								echo '<option value="' . $s . '"', (($c == $_SESSION["filter_cat"]) ? ' selected>' : '>'), $c, '</option>';
							}
							echo '</select>';
			        	?>
			        	</fieldset>
		                 
		            <?php
					 	if ($_SESSION["guest_login"] != 0) {
							echo "<b>&nbsp;You logged in as guest - commands are disabled</b>";
						}
				 	?>
				 	<div data-role="controlgroup">
					 	<label for="sort" class="select">Sort:</label>
						<select name="sort" id="sort" data-native-menu="false">
						   <option value="name"<?php echo (('name' == $HTTP_GET_VARS["sort"]) ? ' selected' : ''); ?>>File name</option>
						   <option value="size"<?php echo (('size' == $HTTP_GET_VARS["sort"]) ? ' selected' : ''); ?>>Size</option>
						   <option value="size_done"<?php echo (('size_done' == $HTTP_GET_VARS["sort"]) ? ' selected' : ''); ?>>Completed</option>
						   <option value="speed"<?php echo (('speed' == $HTTP_GET_VARS["sort"]) ? ' selected' : ''); ?>>Download speed</option>
						   <option value="progress"<?php echo (('progress' == $HTTP_GET_VARS["sort"]) ? ' selected' : ''); ?>>Progress</option>
						   <option value="srccount"<?php echo (('srccount' == $HTTP_GET_VARS["sort"]) ? ' selected' : ''); ?>>Sources</option>
						   <option value="status"<?php echo (('status' == $HTTP_GET_VARS["sort"]) ? ' selected' : ''); ?>>Status</option>
						   <option value="prio"<?php echo (('prio' == $HTTP_GET_VARS["sort"]) ? ' selected' : ''); ?>>Priority</option>
						   <option value="last_seen_complete"<?php echo (('last_seen_complete' == $HTTP_GET_VARS["sort"]) ? ' selected' : ''); ?>>Last seen completed</option>
						</select>
						<?php 
						if( $HTTP_GET_VARS["download_sort_reverse"] ) 
							echo '<a id="sort_reverse" href="#" data-value="0" data-role="button" data-theme="d" data-icon="arrow-u" data-iconpos="right" style="padding-right: 10px;">Ascendent</a>';
						else 
							echo '<a id="sort_reverse" href="#" data-value="1" data-role="button" data-theme="d" data-icon="arrow-d" data-iconpos="right" style="padding-right: 10px;">Descendent</a>';
						?>
					</div>
				 	
	                <br/>
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
				
						function StatusString($file)
						{
							if ( $file->status == 7 ) {
								return "Paused";
							} elseif ( $file->src_count_xfer > 0 ) {
								return "Downloading";
							} else {
								return "Waiting";
							}
						}
				
						function PrioString($file)
						{
							$prionames = array(0 => "Low", 1 => "Normal", 2 => "High",
								3 => "Very high", 4 => "Very low", 5=> "Auto", 6 => "Release");
							$result = $prionames[$file->prio];
							if ( $file->prio_auto == 1) {
								$result = $result . "(auto)";
							}
							return $result;
						}
					
						//
						// declare it here, before any function reffered it in "global"
						//
						$sort_order;$sort_reverse;
				
						function my_cmp($a, $b)
						{
							global $sort_order, $sort_reverse;
							
							switch ( $sort_order) {
								case "size": $result = $a->size > $b->size; break;
								case "size_done": $result = $a->size_done > $b->size_done; break;
								case "progress": $result = (((float)$a->size_done)/((float)$a->size)) > (((float)$b->size_done)/((float)$b->size)); break;
								case "name": $result = $a->name > $b->name; break;
								case "speed": $result = $a->speed > $b->speed; break;
								case "srccount": $result = $a->src_count > $b->src_count; break;
								case "status": $result = StatusString($a) > StatusString($b); break;
								case "prio": $result = $a->prio < $b->prio; break;
								case "last_seen_complete": $result = $a->last_seen_complete > $b->last_seen_complete; break;
							}
				
							if ( $sort_reverse ) {
								$result = !$result;
							}
							//var_dump($sort_reverse);
							return $result;
						}
			
						//
						// perform command before processing content
				
						if ( ($HTTP_GET_VARS["command"] != "") && ($_SESSION["guest_login"] == 0) ) {
							foreach ( $HTTP_GET_VARS as $name => $val) {
								// this is file checkboxes
								if ( (strlen($name) == 32) and ($val == "on") ) {
									//var_dump($name);
									amule_do_download_cmd($name, $HTTP_GET_VARS["command"]);
								}
							}
							//
							// check "filter-by-status" settings
							//
							if ( $HTTP_GET_VARS["command"] == "filter") {
								//var_dump($_SESSION);
								$_SESSION["filter_status"] = $HTTP_GET_VARS["status"];
								$_SESSION["filter_cat"] = $HTTP_GET_VARS["category"];
							}
						}
						if ( $_SESSION["filter_status"] == "") $_SESSION["filter_status"] = "all";
						if ( $_SESSION["filter_cat"] == "") $_SESSION["filter_cat"] = "all";
						
						$downloads = amule_load_vars("downloads");
				
						$sort_order = $HTTP_GET_VARS["sort"];
						
						if ( $sort_order == "" ) {
							$sort_order = $_SESSION["download_sort"];
						} 
						
						//if not set download_sort_reverse
						$_SESSION["download_sort_reverse"] = $HTTP_GET_VARS["download_sort_reverse"] ? 1 : 0; 
						
						$sort_reverse = $_SESSION["download_sort_reverse"];
						if ( $sort_order != "" ) {
							$_SESSION["download_sort"] = $sort_order;
							usort(&$downloads, "my_cmp");
						}
				
						//
						// Prepare categories index array
						$cats = amule_get_categories();
						foreach($cats as $i => $c) {
							$cat_idx[$c] = $i;
						}
						echo '<ul data-role="listview" data-filter="true" data-filter-placeholder="Search file..." data-divider-theme="d" data-split-icon="check">';
						echo '<li data-role="list-divider">Downloads<span class="ui-li-count">'.count($downloads).'</span></li>';
						foreach ($downloads as $file) {
							$filter_status_result = ($_SESSION["filter_status"] == "all") or
								($_SESSION["filter_status"] == StatusString($file));
								
							$filter_cat_result = ($_SESSION["filter_cat"] == "all") or
								($cat_idx[ $_SESSION["filter_cat"] ] == $file->category);
				
							if ( $filter_status_result and $filter_cat_result) {
								echo '<li data-filtertext="',$file->short_name, '"><a data-hash="', $file->hash ,'" class="file-check">';
								
								echo "<h3>", $file->short_name, "</h3>";
								
								if ($file->speed > 0) echo "<p><strong>", CastToXBytes($file->speed), "/s</strong>&nbsp;-&nbsp;";
								else echo "<p>";
									
								echo StatusString($file), "&nbsp;-&nbsp;", PrioString($file), "</p>";
								
								$imgSrc = true;
								$imgSrc = split('"',  $file->progress, 2);
								echo '<p><div class="ui-li-desc" style="background-color: #CCCCCC; background-image:url(', $imgSrc[1], '); background-repeat:no-repeat; text-align: center; background-size:100% 100%; text-shadow: 0px 0px 2px black; color: #CCCCCC; padding: 2px 2px; font-weight: bold;">', CastToXBytes($file->size_done),"&nbsp;/&nbsp;", CastToXBytes($file->size), '</div></p>';
								
								echo "<p>";
								if ( $file->src_count_not_curr != 0 ) {
									echo $file->src_count - $file->src_count_not_curr, " / ";
								}
								echo $file->src_count, " ( ", $file->src_count_xfer, " ) ";
								if ( $file->src_count_a4af != 0 ) {
									echo "+ ", $file->src_count_a4af;
								}
								echo "&nbsp;-&nbsp;", $file->last_seen_complete ? "" : "<i>never</i>&nbsp;", "seen completed</p>";
								
								echo '<span class="ui-li-count">',((float)$file->size_done*100)/((float)$file->size), '%</span>';
				
								echo "</a>";
								
								echo '<a id="', $file->hash ,'" data-hash="', $file->hash ,'" class="file-check">Select file</a>';
								
								echo "</li>";
							}
						}
						echo '</ul>';
					?>
				</form>
			</div>
			<!--/content-primary -->

			<div class="content-secondary">
				<div class="menu" data-role="collapsible" data-collapsed="true" data-theme="b" data-content-theme="d">
					<h3>Menu</h3>
					<ul data-role="listview" data-theme="c" data-dividertheme="d" data-inset="true">
						<li><a href="amuleweb-main-status.php">Status</a></li>
						<li data-theme="a"><a href="amuleweb-main-dload-real.php">Downloads</a></li>
						<li><a href="amuleweb-main-upload.php">Uploads</a></li>
						<li><a href="amuleweb-main-search.php">Search</a></li>
						<li><a href="amuleweb-main-prefs.php">Configurations</a></li>
						<li><a href="amuleweb-main-stats.php">Statistics</a></li>
						<li><a href="amuleweb-main-graph.php">Graphs</a></li>
						<li><a href="amuleweb-main-log.php">Log</a></li>
						<li><a href="footer.php">Add ed2k</a></li>
						<li><a href="login.php">Logout</a></li>
					</ul>
				</div>
				<a href="#" id="btScrollUp" class="hideOnDesktop" data-role="button" data-icon="arrow-u" data-iconpos="right" onClick="scrollUp();">scroll up</a>
			</div>
		</div>
		<!-- /content -->

		<div data-role="footer" data-theme="c">
			<p>&nbsp;<a href="amuleweb-main-about.php" title="about" data-rel="dialog">mobileMule</a> &copy; 2011-12</p>
		</div>
		<!-- /footer -->
		<script>
			$( document ).delegate(".file-check", "click", function() {
			/*$(".file-check").click(function() {*/
	  			var checkboxHashId = $(this).attr('data-hash');
	  			$('<input>').attr({
	  			    type: 'hidden',
	  			    name: checkboxHashId,
	  			    value: 'on'
	  			}).appendTo('form[name="mainform"]');
	
	  			$('#'+checkboxHashId).toggleClass('ui-btn-active');
			});
	
			$( 'select[name="status"], select[name="category"]' ).change( function() {
				$('input[name="command"]').attr('value', 'filter');
				$(this).closest('form').submit();
			});
	
			$( 'select[name="sort"], #sort_reverse' ).bind('change click', function() {
				var value = $('#sort_reverse').attr('data-value');
				$('<input>').attr({
	  			    type: 'hidden',
	  			    name: 'download_sort_reverse',
	  			    value: value
	  			}).appendTo('form[name="mainform"]');
	
				$(this).closest('form').submit();
			});
			
			function formCommandSubmit( command )
			{
				if ( command == "cancel" ) {
					var res = confirm("Delete selected files ?")
					if ( res == false ) {
						return;
					}
				}
				if ( command != "filter" ) {
					<?php
						if ($_SESSION["guest_login"] != 0) {
								echo 'alert("You logged in as guest - commands are disabled");';
								echo "return;";
						}
					?>
				}
	
				$('input[name="command"]').attr('value', command);
				$('form[name="mainform"]').submit();
			}
	
			// Not used for now
			function timestampToDate( time ) 
			{
				var date = new Date(unix_timestamp*1000);
				return date.toLocaleDateString();
			}
		</script>
	</div>
	<!-- /page -->
</body>
</html>
