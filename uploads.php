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

$uploads = amule_load_vars("uploads");

echo '<ul data-role="listview">';
echo '<li data-role="list-divider">Uploads<span class="ui-li-count">' . count($uploads) . '</span></li>';
foreach ($uploads as $file) {
    echo '<li data-filtertext="', $file->name, '">';

    echo "<h3>", $file->name, "</h3>";

    echo "<p>", $file->user_name, "&nbsp;-&nbsp;", CastToXBytes($file->xfer_up), "up&nbsp;-&nbsp;",
    CastToXBytes($file->xfer_down), "down&nbsp;-&nbsp;", ($file->xfer_speed > 0) ? (CastToXBytes($file->xfer_speed) . "/s") : "", "</p>";

    echo "</li>";
}
echo '</ul>';
?>