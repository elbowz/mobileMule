<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
	<title>Configurations - mobileMule</title>
    <link rel="stylesheet" href="//netdna.bootstrapcdn.com/font-awesome/4.1.0/css/font-awesome.min.css" />
	<link rel="stylesheet" href="//ajax.googleapis.com/ajax/libs/jquerymobile/1.4.2/jquery.mobile.min.css" />
	<link rel="stylesheet" href="mystyle.css" />
	<script src="//ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
    <script src="myscript.js"></script>
    <script src="//ajax.googleapis.com/ajax/libs/jquerymobile/1.4.2/jquery.mobile.min.js"></script>
</head>
<body>
	<div data-role="page" id="prefs" class="type-interior">

    <div data-role="panel" id="menu-panel" data-display="reveal" data-position-fixed="true">
        <!-- here is injected menù from pagebeforecreate event -->
    </div>

    <div data-role="header" data-position="fixed">
        <a href="#menu-panel" data-rel="close" id="btMenu" data-icon="home" class="ui-btn-left">Menu</a>
        <h1><i class="fa fa-cogs fa-fw"></i> Configuration</h1>
        <a href="#" data-rel="back" data-icon="arrow-l" class="ui-btn-right">Back</a>
    </div><!-- /header -->

		<div data-role="content">
				<form name="mainform" action="amuleweb-main-prefs.php" method="post" data-ajax="false">
				<ul data-role="listview" data-theme="d" data-divider-theme="d">
					
					<li data-role="list-divider">Webserver<span class="ui-li-count">1</span></li>
					<!--<li data-role="fieldcontain">
			        	<label for="autorefresh_time7">Page refresh interval:</label>
			        	<input name="autorefresh_time" type="number" id="autorefresh_time7" />
					</li>-->
					<li data-role="fieldcontain">
			        	<input name="use_gzip" type="checkbox" id="use_gzip5" class="custom"/>
						<label for="use_gzip5">Use gzip compression</label>
					</li>

					<li data-role="list-divider">Line capacity (for statistics only)<span class="ui-li-count">2</span></li>
					<li data-role="fieldcontain">
			        	<label for="max_line_down_cap6">Max download rate:</label>
			        	<input name="max_line_down_cap" type="number" id="max_line_down_cap6" />
					</li>
					<li data-role="fieldcontain">
			        	<label for="max_line_up_cap7">Max upload rate:</label>
			        	<input name="max_line_up_cap" type="number" id="max_line_up_cap7" />
					</li>

					<li data-role="list-divider">Bandwidth limits<span class="ui-li-count">3</span></li>
					<li data-role="fieldcontain">
			        	<label for="max_down_limit6">Max download rate:</label>
			        	<input name="max_down_limit" type="number" id="max_down_limit6" />
					</li>
					<li data-role="fieldcontain">
			        	<label for="max_up_limit6">Max upload rate:</label>
			        	<input name="max_up_limit" type="number" id="max_up_limit6" />
					</li>
					<li data-role="fieldcontain">
			        	<label for="slot_alloc6">Slot allocation:</label>
			        	<input name="slot_alloc" type="number" id="slot_alloc6" />
					</li>

					<li data-role="list-divider">File settings<span class="ui-li-count">9</span></li>
					<li data-role="fieldcontain">
			        	<input name="check_free_space" type="checkbox" id="check_free_space5" class="custom" />
						<label for="check_free_space5">Check free space =&gt; Minimum free space (Mb)</label>
						<input name="min_free_space" type="number" id="min_free_space4" />
					</li>
					<li data-role="fieldcontain">
			        	<input name="new_files_auto_dl_prio" type="checkbox" id="new_files_auto_dl_prio4" class="custom" />
						<label for="new_files_auto_dl_prio4">Added download files have auto priority</label>
					</li>
					<li data-role="fieldcontain">
			        	<input name="new_files_auto_ul_prio" type="checkbox" id="new_files_auto_ul_prio4" class="custom" />
						<label for="new_files_auto_ul_prio4">New shared files have auto priority</label>
					</li>
					<li data-role="fieldcontain">
			        	<input name="ich_en" type="checkbox" id="ich_en5" class="custom" />
						<label for="ich_en5">I.C.H. active</label>
					</li>
					<li data-role="fieldcontain">
			        	<input name="aich_trust" type="checkbox" id="aich_trust4" class="custom" />
						<label for="aich_trust4">AICH trusts every hash (not recommended)</label>
					</li>
					<li data-role="fieldcontain">
			        	<input name="alloc_full_chunks" type="checkbox" id="alloc_full_chunks4" class="custom" />
						<label for="alloc_full_chunks4">Alloc full chunks of .part files</label>
					</li>
					<li data-role="fieldcontain">
			        	<input name="alloc_full" type="checkbox" id="alloc_full4" class="custom" />
						<label for="alloc_full4">Alloc full disk space for .part files</label>
					</li>
					<li data-role="fieldcontain">
			        	<input name="new_files_paused" type="checkbox" id="new_files_paused4" class="custom" />
						<label for="new_files_paused4">Add files to download queue in pause mode</label>
					</li>
					<li data-role="fieldcontain">
			        	<input name="extract_metadata" type="checkbox" id="extract_metadata4" class="custom" />
						<label for="extract_metadata4">Extract metadata tags</label>
					</li>

					<li data-role="list-divider">Connection settings<span class="ui-li-count">4</span></li>
					<li data-role="fieldcontain">
			        	<label for="max_conn_total8">Max total connections (total):</label>
			        	<input name="max_conn_total" type="number" id="max_conn_total8" />
					</li>
					<li data-role="fieldcontain">
			        	<label for="max_file_src7">Max sources per file:</label>
			        	<input name="max_file_src" type="number" id="max_file_src7" />
					</li>
					<li data-role="fieldcontain">
			        	<input name="autoconn_en" type="checkbox" id="autoconn_en6" class="custom" />
						<label for="autoconn_en6">Autoconnect at startup</label>
					</li>
					<li data-role="fieldcontain">
			        	<input name="reconn_en" type="checkbox" id="reconn_en6" class="custom" />
						<label for="reconn_en6">Reconnect when connection lost</label>
					</li>

					<li data-role="list-divider">Network settings<span class="ui-li-count">4</span></li>
					<li data-role="fieldcontain">
			        	<label for="tcp_port6">TCP port:</label>
			        	<input name="tcp_port" type="number" id="tcp_port6" />
					</li>
					<li data-role="fieldcontain">
			        	<label for="udp_port6">UDP port:</label>
			        	<input name="udp_port" type="number" id="udp_port6" />
					</li>
					<li data-role="fieldcontain">
			        	<input name="udp_dis" type="checkbox" id="udp_dis5" class="custom" />
						<label for="udp_dis5">Disable UDP connections</label>
					</li>
					<li data-role="fieldcontain">
						<?php
							if ($_SESSION["guest_login"] == 0) {
								echo '<button type="submit" name="Submit" value="Apply">Apply</button>';
							} else {
								echo "<b>&nbsp;You can not change options - logged in as guest</b>";
							}
						?>
					</li>
		        	<input name="command" type="hidden" id="command">
				</form>
		</div>
		<!-- /content -->

        <div data-role="footer" role="contentinfo" class="ui-footer" data-theme="c">
            <a href="#" id="btScrollUp" data-role="button" data-icon="arrow-u" class="ui-btn-right">scroll up</a>
            <p>&nbsp;<a href="amuleweb-main-about.php" title="about" data-rel="dialog" data-transition="pop">mobileMule</a> &copy; 2014</p>
        </div>
        <!-- /footer -->
		<script>
			var initvals = new Object;
			
			<?php
				// apply new options before proceeding
				//var_dump($HTTP_GET_VARS);
				if ( ($HTTP_GET_VARS["Submit"] == "Apply") && ($_SESSION["guest_login"] == 0) ) {
					$file_opts = array("check_free_space", "extract_metadata", 
						"ich_en","aich_trust", "preview_prio","save_sources", "resume_same_cat",
						"min_free_space", "new_files_paused", "alloc_full", "alloc_full_chunks",
						"new_files_auto_dl_prio", "new_files_auto_ul_prio"
					);
					$conn_opts = array("max_line_up_cap","max_up_limit",
						"max_line_down_cap","max_down_limit", "slot_alloc", 
						"tcp_port","udp_dis","max_file_src","max_conn_total","autoconn_en");
					$webserver_opts = array("use_gzip", "autorefresh_time");
					
					$all_opts;
					foreach ($conn_opts as $i) {
						$curr_value = $HTTP_GET_VARS[$i];
						if ( $curr_value == "on") $curr_value = 1;
						if ( $curr_value == "") $curr_value = 0;
						
						$all_opts["connection"][$i] = $curr_value;
					}
					foreach ($file_opts as $i) {
						$curr_value = $HTTP_GET_VARS[$i];
						if ( $curr_value == "on") $curr_value = 1;
						if ( $curr_value == "") $curr_value = 0;
						
						$all_opts["files"][$i] = $curr_value;
					}
					foreach ($webserver_opts as $i) {
						$curr_value = $HTTP_GET_VARS[$i];
						if ( $curr_value == "on") $curr_value = 1;
						if ( $curr_value == "") $curr_value = 0;
						
						$all_opts["webserver"][$i] = $curr_value;
					}
					//var_dump($all_opts);
					amule_set_options($all_opts);
				}
				$opts = amule_get_options();
				//var_dump($opts);
				$opt_groups = array("connection", "files", "webserver");
				//var_dump($opt_groups);
				foreach ($opt_groups as $group) {
					$curr_opts = $opts[$group];
					//var_dump($curr_opts);
					foreach ($curr_opts as $opt_name => $opt_val) {
						echo 'initvals["', $opt_name, '"] = "', $opt_val, '";';
					}
				}
			?>
			$( document ).on("pageinit", "#prefs", function() {
				// Assign php generated data to controls
				var frm = document.forms.mainform

				var str_param_names = new Array(
					"max_line_down_cap", "max_line_up_cap",
					"max_up_limit", "max_down_limit", "max_file_src",
					"slot_alloc", "max_conn_total",
					"tcp_port", "udp_port",
					"min_free_space",
					"autorefresh_time"
					);

				for(i = 0; i < str_param_names.length; i++) {
					$('input[name="'+str_param_names[i]+'"]').val(initvals[str_param_names[i]]);
					//frm[str_param_names[i]].value = initvals[str_param_names[i]];
				}

				var check_param_names = new Array(
					"autoconn_en", "reconn_en", "udp_dis", "new_files_paused",
					"aich_trust", "alloc_full", "alloc_full_chunks",
					"check_free_space", "extract_metadata", "ich_en",
					"new_files_auto_dl_prio", "new_files_auto_ul_prio",
					"use_gzip"
					);
				
				for(i = 0; i < check_param_names.length; i++) {
					//frm[check_param_names[i]].checked = initvals[check_param_names[i]] == "1" ? true : false;
					if (initvals[check_param_names[i]] == "1")
						$("input[name='"+check_param_names[i]+"']").attr("checked", true).checkboxradio("refresh");
				}
			});
		</script>
	</div>
	<!-- /page -->
</body>
</html>
