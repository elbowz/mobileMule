<div data-role="collapsible" data-collapsed="false">
    <h3>Statistics</h3>
    <?php
    function print_item($it)
    {
        // must be re-inizialized because $node IS NOT local variabile?!!?! a sort of static or global
        $node = true;
        $node = split(': ', $it, 3);
        echo '<tr><td>' . $node[0] . '</td><th>' . $node[1] . $node[2] . '</th></tr>';
    }

    function print_folder($key, &$arr)
    {
        echo '<div data-role="collapsible">';
        echo "<h3>" . $key . "</h3>";

        // child of folder (first sub-level)
        foreach ($arr as $k => $v) {
            if (count(&$v)) print_folder($k, $v);
            else {
                echo '<table class="last-column-right">';
                print_item($k);
                echo '</table>';
            }
        }
        echo '</div>';
    }

    function recursiveViewOnStatsTree(&$tree, $nodeName)
    {
        foreach ($tree as $k => $v) {
            if (count(&$v)) print_folder($k, $v);
            else {
                echo '<table class="last-column-right">';
                print_item($k);
                echo '</table>';
            }
        }
    }

    $statsTree = amule_load_vars("stats_tree");
    recursiveViewOnStatsTree($statsTree, $HTTP_GET_VARS['statsnode']);
    ?>
</div>
<br/>
<div style="text-align:center;">
    <div data-role="controlgroup" data-type="horizontal">
        <a href="#page-collapsible-stats" class="hash-link" data-role="button">collapsible</a>
        <a href="#page-listed-stats" class="hash-link" data-role="button">listed</a>
    </div>
</div>

