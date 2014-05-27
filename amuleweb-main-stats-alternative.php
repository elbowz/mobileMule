<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1" />
	<title>Statistics - mobileMule</title>
	<link rel="stylesheet" href="//ajax.googleapis.com/ajax/libs/jquerymobile/1.4.2/jquery.mobile.min.css" />
	<link rel="stylesheet" href="mystyle.css" />
	<script src="//ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
    <script src="myscript.js"></script>
    <script src="//ajax.googleapis.com/ajax/libs/jquerymobile/1.4.2/jquery.mobile.min.js"></script>
</head>
<body>
	<div data-role="page" id="stats" class="type-interior">

        <div data-role="panel" id="menu-panel" data-display="push">
            <!-- here is injected menù from pagebeforecreate event -->
        </div>

        <div data-role="header" data-position="inline">
            <a href="#menu-panel" data-rel="close" id="btMenu" data-icon="home">Menu</a>
            <h1>Statistics</h1>
            <a href="#" data-rel="back" data-icon="arrow-l">Back</a>
        </div><!-- /header -->

		<div data-role="content">
				<div data-role="collapsible"  data-content-theme="c" data-collapsed="false">
					<h3>Statistics</h3>
					<?php	
					function print_item( $it )
					{
							//echo "<table><tr><th>".$it."</th></tr></table>";
							// must be re-inizialized because $node IS NOT local variabile?!!?! a sort of static or global
							$node = true;
							$node = split(': ', $it, 3);
							echo  "<table><tr><th>".$node[0].'</th><td>'.$node[1].$node[2]."</td></tr></table>";
					}
			
					function print_folder( $key, &$arr )
					{
						echo '<div data-role="collapsible"  data-content-theme="c">';
						echo "<h3>".$key."</h3>";
						
						// child of folder (first sub-level)
						foreach ( $arr as $k => $v )
						{
							if( count( &$v ) ) print_folder( $k, $v );
							else print_item( $k );
						}
						echo '</div>';
					}
					
					function recursiveViewOnStatsTree( &$tree , $nodeName)
					{
						foreach ($tree as $k => $v) {
							if ( count(&$v) ) print_folder($k, $v);
							else print_item($k);
						}
					}
			
					$statsTree = amule_load_vars("stats_tree");
					recursiveViewOnStatsTree($statsTree, $HTTP_GET_VARS['statsnode']);
					?>
				</div>
				<br/>
				<div style="text-align:center;">
					<div data-role="controlgroup" data-type="horizontal">
						<a href="amuleweb-main-stats-alternative.php" data-role="button" data-theme="a">collapsible</a>
						<a href="amuleweb-main-stats.php" data-role="button">listed</a>
					</div>
				</div>
		</div>
		<!-- /content -->

        <div data-role="footer" role="contentinfo" class="ui-footer" data-theme="c">
            <a href="#" id="btScrollUp" data-role="button" data-icon="arrow-u" data-iconpos="right">scroll up</a>
            <p>&nbsp;<a href="amuleweb-main-about.php" title="about" data-rel="dialog" data-transition="pop">mobileMule</a> &copy; 2014</p>
        </div>
        <!-- /footer -->
	</div>
	<!-- /page -->
</body>
</html>
