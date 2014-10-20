<form action="downloads.php" method="post" name="mainform">
<input type="hidden" name="command">

<div data-role="navbar">
    <ul>
        <li><a href="javascript:formCommandSubmit('pause');"><i class="fa fa-pause"></i><span>Pause</span></a></li>
        <li><a href="javascript:formCommandSubmit('resume');"><i class="fa fa-play"></i><span>Resume</span></a></li>
        <li><a href="javascript:formCommandSubmit('prioup');"><i class="fa fa-chevron-up"></i><span>Prio. up</span></a></li>
        <li><a href="javascript:formCommandSubmit('priodown');"><i class="fa fa-chevron-down"></i><span>Prio. down</span></a></li>
        <li><a href="javascript:formCommandSubmit('cancel');"><i class="fa fa-trash"></i><span>Cancel</span></a></li>
    </ul>
</div>

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
echo '<ul data-role="listview" data-filter="true" data-filter-placeholder="Search file..." data-divider-theme="d" data-split-icon="check">';
echo '<li data-role="list-divider">Downloads<span class="ui-li-count">' . count($downloads) . '</span></li>';
foreach ($downloads as $file) {
    $filter_status_result = ($_SESSION["filter_status"] == "all") or
    ($_SESSION["filter_status"] == StatusString($file));

    $filter_cat_result = ($_SESSION["filter_cat"] == "all") or
    ($cat_idx[$_SESSION["filter_cat"]] == $file->category);

    if ($filter_status_result and $filter_cat_result) {
        echo '<li data-filtertext="', $file->short_name, '"><a data-hash="', $file->hash, '" class="file-check">';

        echo "<h3>", $file->short_name, "</h3>";

        if ($file->speed > 0) echo "<p><strong>", CastToXBytes($file->speed), "/s</strong>&nbsp;-&nbsp;";
        else echo "<p>";

        echo StatusString($file), "&nbsp;-&nbsp;", PrioString($file), "</p>";


        // Heavy for amule webserver
        //$imgSrc = true;
        //$imgSrc = split('"',  $file->progress, 2);
        //echo '<p><div class="ui-li-desc" style="background-color: #CCCCCC; background-image:url(', $imgSrc[1], '); background-repeat:no-repeat; text-align: center; background-size:100% 100%; text-shadow: 0px 0px 2px black; color: #CCCCCC; padding: 2px 2px; font-weight: bold;">', CastToXBytes($file->size_done),"&nbsp;/&nbsp;", CastToXBytes($file->size), '</div></p>';
        $percentual_progress = ((float)$file->size_done * 100) / ((float)$file->size);


        echo "<p>";
        if ($file->src_count_not_curr != 0) {
            echo $file->src_count - $file->src_count_not_curr, " / ";
        }
        echo $file->src_count, " ( ", $file->src_count_xfer, " ) ";
        if ($file->src_count_a4af != 0) {
            echo "+ ", $file->src_count_a4af;
        }
        echo "&nbsp;-&nbsp;", $file->last_seen_complete ? "" : "<i>never</i>&nbsp;", "seen completed</p>";

        echo '<p><div class="ui-li-desc bar"><div class="ui-li-desc completed-bar ', StatusCompltedClass($file), '" style="width: ', $percentual_progress, '%;">', CastToXBytes($file->size_done), "&nbsp;/&nbsp;", CastToXBytes($file->size), '</div></div></p>';

        echo '<span class="ui-li-count">', $percentual_progress, '%</span>';

        echo "</a>";

        echo '<a id="', $file->hash, '" data-hash="', $file->hash, '" class="file-check">Select file</a>';

        echo "</li>";
    }
}
echo '</ul>';
?>

<br/>

<div class="ui-grid-a ui-responsive">
    <div class="ui-block-a">
        <fieldset data-role="controlgroup">
            <legend>Filter (status / category):</legend>
            <?php
            $all_status = array("all", "Waiting", "Paused", "Downloading");
            if ($HTTP_GET_VARS["command"] == "filter") {
                $_SESSION["filter_status"] = $HTTP_GET_VARS["status"];
                $_SESSION["filter_cat"] = $HTTP_GET_VARS["category"];
            }
            if ($_SESSION["filter_status"] == '') $_SESSION["filter_status"] = 'all';
            if ($_SESSION["filter_cat"] == '') $_SESSION["filter_cat"] = 'all';

            echo '<select name="status" id="status" data-native-menu="false"> ';
            foreach ($all_status as $s) {
                echo '<option value="' . $s . '"', (($s == $_SESSION["filter_status"]) ? ' selected>' : '>'), $s, '</option>';
            }
            echo '</select>';
            //var_dump($_SESSION["filter_cat"]);
            echo '<select name="category" id="category" data-native-menu="false">';
            $cats = amule_get_categories();
            foreach ($cats as $c) {
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
    </div>
    <div class="ui-block-b">
        <fieldset data-role="controlgroup">
            <legend>Sort:</legend>
            <select name="sort" id="sort" data-native-menu="false">
                <option
                    value="name"<?php echo(('name' == $HTTP_GET_VARS["sort"]) ? ' selected' : ''); ?>>
                    File name
                </option>
                <option
                    value="size"<?php echo(('size' == $HTTP_GET_VARS["sort"]) ? ' selected' : ''); ?>>
                    Size
                </option>
                <option
                    value="size_done"<?php echo(('size_done' == $HTTP_GET_VARS["sort"]) ? ' selected' : ''); ?>>
                    Completed
                </option>
                <option
                    value="speed"<?php echo(('speed' == $HTTP_GET_VARS["sort"]) ? ' selected' : ''); ?>>
                    Download speed
                </option>
                <option
                    value="progress"<?php echo(('progress' == $HTTP_GET_VARS["sort"]) ? ' selected' : ''); ?>>
                    Progress
                </option>
                <option
                    value="srccount"<?php echo(('srccount' == $HTTP_GET_VARS["sort"]) ? ' selected' : ''); ?>>
                    Sources
                </option>
                <option
                    value="status"<?php echo(('status' == $HTTP_GET_VARS["sort"]) ? ' selected' : ''); ?>>
                    Status
                </option>
                <option
                    value="prio"<?php echo(('prio' == $HTTP_GET_VARS["sort"]) ? ' selected' : ''); ?>>
                    Priority
                </option>
                <option
                    value="last_seen_complete"<?php echo(('last_seen_complete' == $HTTP_GET_VARS["sort"]) ? ' selected' : ''); ?>>
                    Last seen completed
                </option>
            </select>
            <?php
            if ($HTTP_GET_VARS["download_sort_reverse"])
                echo '<a id="sort_reverse" href="#" data-value="0" data-role="button" data-theme="d" data-icon="arrow-u" data-iconpos="right" style="padding-right: 38px;">Ascendent</a>';
            else
                echo '<a id="sort_reverse" href="#" data-value="1" data-role="button" data-theme="d" data-icon="arrow-d" data-iconpos="right" style="padding-right: 38px;">Descendent</a>';
            ?>
        </fieldset>
    </div>
</div>
</form>

<script>
    $(document).one('pagecreate', function () {

        // TODO: trig event up to anchestor with context
        $('.ui-listview').on('vclick', '.file-check', function (event) {
            event.stopPropagation();

            var checkboxHashId = $(this).attr('data-hash');
            $('<input>').attr({
                type: 'hidden',
                name: checkboxHashId,
                value: 'on'
            }).appendTo('form[name="mainform"]');

            $('#' + checkboxHashId).toggleClass('ui-btn-active');
        });

        $('select[name="status"], select[name="category"]').change(function () {
            $('input[name="command"]').attr('value', 'filter');
            $('form[name="mainform"]').jQMobileAjaxSubmit();
        });

        $('select[name="sort"]').change(function () {
            $('form[name="mainform"]').jQMobileAjaxSubmit();
        });

        $('#sort_reverse').on('vclick', function () {
            event.preventDefault();

            var value = $('#sort_reverse').attr('data-value');
            $('<input>').attr({
                type: 'hidden',
                name: 'download_sort_reverse',
                value: value
            }).appendTo('form[name="mainform"]');

            $('form[name="mainform"]').jQMobileAjaxSubmit();
        });
    });

    formCommandSubmit = function (command) {
        if (command == "cancel") {
            var res = confirm("Delete selected files ?")
            if (res == false) {
                return;
            }
        }
        if (command != "filter") {
            <?php
                if ($_SESSION["guest_login"] != 0) {
                        echo 'alert("You logged in as guest - commands are disabled");';
                        echo "return;";
                }
            ?>
        }

        $('input[name="command"]').attr('value', command);
        $('form[name="mainform"]').jQMobileAjaxSubmit();
    }

    // Not used for now
    timestampToDate = function (time) {
        var date = new Date(unix_timestamp * 1000);
        return date.toLocaleDateString();
    }
</script>
