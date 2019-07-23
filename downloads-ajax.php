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

function StatusString($file)
{
    if ($file->status == 7) {
        return "Paused";
    } elseif ($file->src_count_xfer > 0) {
        return "Downloading";
    } else {
        return "Waiting";
    }
}

function StatusCompltedClass($file)
{
    if ($file->status == 7) {
        return "pause";
    } elseif ($file->src_count_xfer > 0) {
        return "downloading";
    } else {
        return "waiting";
    }
}

function PrioString($file)
{
    $prionames = array(0 => "Low", 1 => "Normal", 2 => "High",
        3 => "Very high", 4 => "Very low", 5 => "Auto", 6 => "Release");
    $result = $prionames[$file->prio];
    if ($file->prio_auto == 1) {
        $result = $result . "(auto)";
    }
    return $result;
}

//
// declare it here, before any function reffered it in "global"
//
$sort_order;
$sort_reverse;

function my_cmp($a, $b)
{
    global $sort_order, $sort_reverse;

    switch ($sort_order) {
        case "size":
            $result = $a->size > $b->size;
            break;
        case "size_done":
            $result = $a->size_done > $b->size_done;
            break;
        case "progress":
            $result = (((float)$a->size_done) / ((float)$a->size)) > (((float)$b->size_done) / ((float)$b->size));
            break;
        case "name":
            $result = $a->name > $b->name;
            break;
        case "speed":
            $result = $a->speed > $b->speed;
            break;
        case "srccount":
            $result = $a->src_count > $b->src_count;
            break;
        case "status":
            $result = StatusString($a) > StatusString($b);
            break;
        case "prio":
            $result = $a->prio < $b->prio;
            break;
        case "last_seen_complete":
            $result = $a->last_seen_complete > $b->last_seen_complete;
            break;
    }

    if ($sort_reverse) {
        $result = !$result;
    }
    //var_dump($sort_reverse);
    return $result;
}

//
// perform command before processing content

if (($HTTP_GET_VARS["command"] != "") && ($_SESSION["guest_login"] == 0)) {
    foreach ($HTTP_GET_VARS as $name => $val) {
        // this is file checkboxes
        if ((strlen($name) == 32) and ($val == "on")) {
            //var_dump($name);
            amule_do_download_cmd($name, $HTTP_GET_VARS["command"]);
        }
    }
    //
    // check "filter-by-status" settings
    //
    if ($HTTP_GET_VARS["command"] == "filter") {
        //var_dump($_SESSION);
        $_SESSION["filter_status"] = $HTTP_GET_VARS["status"];
        $_SESSION["filter_cat"] = $HTTP_GET_VARS["category"];
    }
}
if ($_SESSION["filter_status"] == "") $_SESSION["filter_status"] = "all";
if ($_SESSION["filter_cat"] == "") $_SESSION["filter_cat"] = "all";

$downloads = amule_load_vars("downloads");

$sort_order = $HTTP_GET_VARS["sort"];

if ($sort_order == "") {
    $sort_order = $_SESSION["download_sort"];
}

//if not set download_sort_reverse
$_SESSION["download_sort_reverse"] = $HTTP_GET_VARS["download_sort_reverse"] ? 1 : 0;

$sort_reverse = $_SESSION["download_sort_reverse"];
if ($sort_order != "") {
    $_SESSION["download_sort"] = $sort_order;
    usort(&$downloads, "my_cmp");
}

//
// Prepare categories index array
$cats = amule_get_categories();
foreach ($cats as $i => $c) {
    $cat_idx[$c] = $i;
}

// GENERATE JSON

$countDownloads = count($downloads);
$i = 0;

echo '{ "downloads": { "count": ' . $countDownloads . ', ';
echo '"list": [';
foreach ($downloads as $file) {

    $filter_status_result = ($_SESSION["filter_status"] == "all") or
    ($_SESSION["filter_status"] == StatusString($file));

    $filter_cat_result = ($_SESSION["filter_cat"] == "all") or
    ($cat_idx[$_SESSION["filter_cat"]] == $file->category);

    if ($filter_status_result and $filter_cat_result) {
        echo '{';

        echo '"name": "' . $file->name . '", "short_name": "' . $file->short_name . '", "hash": "' . $file->hash . '"';

        if ($file->speed > 0) echo ', "speed": "' . CastToXBytes($file->speed) . '"';

        echo ', "status": "' . StatusString($file) . '", "prio": "' . PrioString($file) . '"';

        if ($file->src_count_not_curr != 0) {
            echo ', "src_count_connected": ' . ($file->src_count - $file->src_count_not_curr);
        }

        echo ', "src_count": ' . $file->src_count . ', "src_count_xfer": ' . $file->src_count_xfer;

        if ($file->src_count_a4af != 0) {
            echo ', "src_count_a4af": ' . $file->src_count_a4af;
        }
        echo ', "last_seen_complete": "' . ($file->last_seen_complete ? "" : 'never ') . 'seen completed' . '"';

        $percentual_progress = ((float)$file->size_done * 100) / ((float)$file->size);

        echo ', "status_complted_class": "' . StatusCompltedClass($file) . '", "percentual_progress": "' . $percentual_progress;

        echo '", "size_done": "' . CastToXBytes($file->size_done) . '", "size": "' . CastToXBytes($file->size) . '"';

        if($i == $countDownloads - 1) { echo '}'; } else { echo '}, '; }
    }

    $i++;
}
echo ']';
echo '} }';
?>