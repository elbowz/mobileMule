<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
	<title>Statistics (list) - mobileMule</title>
    <link rel="stylesheet" href="//netdna.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" />
    <link rel="stylesheet" href="//ajax.googleapis.com/ajax/libs/jquerymobile/1.4.2/jquery.mobile.min.css" />
	<link rel="stylesheet" href="mystyle.css" />
	<script src="//ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
    <script src="myscript.js"></script>
    <script src="//ajax.googleapis.com/ajax/libs/jquerymobile/1.4.2/jquery.mobile.min.js"></script>
</head>
<body>
	<div data-role="page" id="stats-list" class="type-interior">

        <div data-role="panel" id="menu-panel" data-display="reveal" data-position-fixed="true">
            <!-- here is injected menù from pagebeforecreate event -->
        </div>

        <div data-role="header" data-position="fixed">
            <a href="#menu-panel" data-rel="close" id="btMenu" data-icon="home" class="ui-btn-left">Menu</a>
            <h1><i class="fa fa-tachometer fa-fw"></i> Statistics</h1>
            <a href="#" data-rel="back" data-icon="arrow-l" class="ui-btn-right">Back</a>
        </div><!-- /header -->

		<div data-role="content">
				<form action="amuleweb-main-stats.php" method="post" name="stats">
					<input type="hidden" name="statsnode" value="">
					<ul data-role="listview" data-filter="true" data-filter-placeholder="Search in visible stats..." data-theme="c" data-divider-theme="d">
						<?php	
							function print_item( $it, $ident )
							{
									// must be re-inizialized because $node IS NOT local variabile?!!?! a sort of static or global
									$node = true;
									$node = split(': ', $it, 3);
									echo '<li>'.$node[0].'<p class="ui-li-aside"><strong>'.$node[1].$node[2].'</strong></p></li>';
							}
					
							function print_folder( $key, &$arr, $ident )
							{
								// title of folder
								echo '<li data-role="list-divider">'.$key.'<span class="ui-li-count">'.count($arr).'</span></li>';
					
								// child of folder (first sub-level)
								foreach ( $arr as $k => $v )
								{
									if( count( &$v ) ) echo "<li><a href=\"javascript:subMenu('".$k."')\">".$k.'</a></li>';
									else print_item( $k, $ident );
								}
								
							}
							
							function recursiveViewOnStatsTree( &$tree , $nodeName)
							{
								
								foreach ($tree as $k => $v) {
									if( $nodeName == $k | !strlen( $nodeName ) )
									{ 
										if ( count(&$v) ) print_folder($k, $v, $ident+1);
										else print_item($k, $ident+1);
									}
									else if( count(&$v) ) recursiveViewOnStatsTree($v, $nodeName);
								}
							}
					
							$statsTree = amule_load_vars("stats_tree");
							recursiveViewOnStatsTree($statsTree, $HTTP_GET_VARS['statsnode']);
						?>
					</ul>
				</form>
				<br/>
				<div style="text-align:center;">
					<div data-role="controlgroup" data-type="horizontal">
						<a href="amuleweb-main-stats-alternative.php" data-role="button">collapsible</a>
						<a href="amuleweb-main-stats.php" data-role="button" data-theme="a">listed</a>
					</div>
				</div>
		</div>
		<!-- /content -->

        <div data-role="footer" role="contentinfo" class="ui-footer" data-theme="c">
            <a href="#" id="btScrollUp" data-role="button" data-icon="arrow-u" class="ui-btn-right">scroll up</a>
            <p>&nbsp;<a href="amuleweb-main-about.php" title="about" data-rel="dialog" data-transition="pop">mobileMule</a> &copy; 2014</p>
        </div>
        <!-- /footer -->
		<script>
			function subMenu(node)
			{
				$.mobile.changePage( "amuleweb-main-stats.php", {
					type: "get", 
					data: {statsnode: node}
					/*dataUrl: node*/
				});	
			}
		</script>
	</div>
	<!-- /page -->
</body>
</html>
