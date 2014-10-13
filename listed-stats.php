<ul data-role="listview" data-filter="true" data-filter-placeholder="Search in visible stats...">
    <?php
    $statsnode;
    function print_item($it, $ident)
    {
        // must be re-inizialized because $node IS NOT local variabile?!!?! a sort of static or global
        $node = true;
        $node = split(': ', $it, 3);
        echo '<li><div class="ui-grid-a ui-responsive"><div class="ui-block-a">' . $node[0] . '</div><div class="ui-block-b"><strong>' . $node[1] . $node[2] . '</strong></div></div></li>';
    }

    function print_folder($key, &$arr, $ident)
    {
        global $statsnode;

        // title of folder
        echo '<li data-role="list-divider">';
        if($statsnode != '') echo '<a href="listed-stats.php" class="ajax-link"><i class="fa fa-chevron-left"></i> ';
        echo $key . '<span class="ui-li-count">' . count($arr) . '</span>';
        if($statsnode != '') echo '</a>';
        echo '</li>';

        // child of folder (first sub-level)
        foreach ($arr as $k => $v) {
            if (count(&$v)) echo '<li><a href="listed-stats.php?statsnode=' . $k . '" class="ajax-link">' . $k . '</a></li>';
            else print_item($k, $ident);
        }

    }

    function recursiveViewOnStatsTree(&$tree, $nodeName)
    {
        foreach ($tree as $k => $v) {
            if ($nodeName == $k | !strlen($nodeName)) {
                if (count(&$v)) print_folder($k, $v, $ident + 1);
                else print_item($k, $ident + 1);
            } else if (count(&$v)) recursiveViewOnStatsTree($v, $nodeName);
        }
    }

    $statsnode = $HTTP_GET_VARS['statsnode'];
    $statsTree = amule_load_vars("stats_tree");
    recursiveViewOnStatsTree($statsTree, $statsnode);
    ?>
</ul>
<br/>
<div style="text-align:center;">
    <div data-role="controlgroup" data-type="horizontal">
        <a href="#page-collapsible-stats" class="hash-link" data-role="button">collapsible</a>
        <a href="#page-listed-stats" class="hash-link" data-role="button">listed</a>
    </div>
</div>

