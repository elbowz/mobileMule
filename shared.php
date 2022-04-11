<?php
function CastToXBytes($size)
{
    if ($size < 1024) {
        $result = $size . " b";
    } elseif ($size < 1048576) {
        $result = ($size / 1024.0) . " kb";
    } elseif ($size < 1073741824) {
        $result = ($size / 1048576.0) . " mb";
    } else {
        $result = ($size / 1073741824.0) . " gb";
    }
    return $result;
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

$shared = amule_load_vars("shared");

echo '<form>';
echo  '<input id="filter-shared-input" data-type="search" type="search" autocomplete="off">';
echo '</form>';
echo '<br/>';
echo '<ul data-role="listview" data-divider-theme="d" data-filter="true" data-input="#filter-shared-input">';
echo '<li data-role="list-divider">Shared<span class="ui-li-count">' . count($shared) . '</span></li>';
foreach ($shared as $file) {
    echo '<li data-filtertext="', $file->name, '">';
        echo "<h3>", $file->name, "</h3>";
        echo "<p><strong>", CastToXBytes($file->xfer), " (", CastToXBytes($file->xfer_all), ")</strong> - transferred (total)</p>";
        echo "<p>", $file->req, " (", $file->req_all, ") / ", $file->accept, " (", $file->accept_all, ") - request / accepted</p>";
        echo "<p>", PrioString($file), "</p>";
        echo '<span class="ui-li-count">', CastToXBytes($file->size), '</span>';
    echo "</li>";
}
echo '</ul>';
?>