<form name="mainform" action="search.php" method="post">
<input type="hidden" name="command" value="">

<legend>Filename:</legend>
<input name="searchval" type="text" id="searchval4" size="60">
<input name="Search" type="submit" id="Search4" value="Search" onClick="javascript:formCommandSubmit('search');" class="ui-btn-b">

<div data-role="collapsible">
    <h4>Advanced search</h4>

    <div class="ui-grid-c ui-responsive">
        <div class="ui-block-a">
            <div class="ui-body">
                <legend>Availability:</legend>
                <input name="avail" type="text" id="avail13" size="6">
            </div>
        </div>
        <div class="ui-block-b">
            <div class="ui-body">
                <legend>Min Size:</legend>
                <input name="minsize" type="text" id="minsize2" size="5">
                <select name="minsizeu" id="select8" data-native-menu="false">
                    <option value="Byte">Byte</option>
                    <option value="KByte">KByte</option>
                    <option value="MByte" selected>MByte</option>
                    <option value="GByte">GByte</option>
                </select>
            </div>
        </div>
        <div class="ui-block-c">
            <div class="ui-body">
                <legend>Max Size:</legend>
                <input name="maxsize" type="text" id="maxsize4" size="5">
                <select name="maxsizeu" id="select10" data-native-menu="false">
                    <option value="Byte">Byte</option>
                    <option value="KByte">KByte</option>
                    <option value="MByte" selected>MByte</option>
                    <option value="GByte">GByte</option>
                </select>
            </div>
        </div>
        <div class="ui-block-d">
            <div class="ui-body">
                <legend>Search type:</legend>
                <select name="searchtype" id="select" data-native-menu="false">
                    <option value="Local">Local</option>
                    <option value="Global" selected>Global</option>
                    <option value="Kad">Kad</option>
                </select>
            </div>
        </div>
    </div>
</div>
<div class="ui-grid-a ui-responsive">
    <div class="ui-block-a">
        <div class="ui-body">
            <a href="search.php?search_sort=<?php echo($HTTP_GET_VARS["sort"]); ?>"
               class="ui-btn ui-icon-refresh ui-btn-icon-right ui-shadow ui-corner-all ui-btn-b ajax-link"
               style="padding-right: 38px;">Update the search results</a>
        </div>
    </div>
    <div class="ui-block-b">
        <div class="ui-body">
            <div style="text-align:center;">
                <div data-role="controlgroup" data-type="horizontal">
                    <a href="search.php?sort=name" data-role="button" class="ui-btn ui-btn-inline ui-corner-all ui-mini ajax-link">File Name</a>
                    <a href="search.php?sort=size" data-role="button" class="ui-btn ui-btn-inline ui-corner-all ui-mini ajax-link">Size</a>
                    <a href="search.php?sort=sources" data-role="button" class="ui-btn ui-btn-inline ui-corner-all ui-mini ajax-link">Sources</a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
function CastToXBytes($size)
{
    if ($size < 1024) {
        $result = $size . " b";
    } elseif ($size < 1048576) {
        $result = ($size / 1024.0) . "kb";
    } elseif ($size < 1073741824) {
        $result = ($size / 1048576.0) . "mb";
    } else {
        $result = ($size / 1073741824.0) . "gb";
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
        case "name":
            $result = $a->name > $b->name;
            break;
        case "sources":
            $result = $a->sources > $b->sources;
            break;
    }

    if ($sort_reverse) {
        $result = !$result;
    }

    return $result;
}

function str2mult($str)
{
    $result = 1;
    switch ($str) {
        case "Byte":
            $result = 1;
            break;
        case "KByte":
            $result = 1024;
            break;
        case "MByte":
            $result = 1012 * 1024;
            break;
        case "GByte":
            $result = 1012 * 1024 * 1024;
            break;
    }
    return $result;
}

function cat2idx($cat)
{
    $cats = amule_get_categories();
    $result = 0;
    foreach ($cats as $i => $c) {
        if ($cat == $c) $result = $i;
    }
    return $result;
}

if ($_SESSION["guest_login"] == 0) {
    if ($HTTP_GET_VARS["command"] == "search") {
        $search_type = -1;
        switch ($HTTP_GET_VARS["searchtype"]) {
            case "Local":
                $search_type = 0;
                break;
            case "Global":
                $search_type = 1;
                break;
            case "Kad":
                $search_type = 2;
                break;
        }
        $min_size = $HTTP_GET_VARS["minsize"] == "" ? 0 : $HTTP_GET_VARS["minsize"];
        $max_size = $HTTP_GET_VARS["maxsize"] == "" ? 0 : $HTTP_GET_VARS["maxsize"];

        $min_size *= str2mult($HTTP_GET_VARS["minsizeu"]);
        $max_size *= str2mult($HTTP_GET_VARS["maxsizeu"]);

        amule_do_search_start_cmd($HTTP_GET_VARS["searchval"],
            //$HTTP_GET_VARS["ext"], $HTTP_GET_VARS["filetype"],
            "", "",
            $search_type, $HTTP_GET_VARS["avail"], $min_size, $max_size);
    } elseif ($HTTP_GET_VARS["command"] == "download") {
        foreach ($HTTP_GET_VARS as $name => $val) {
            // this is file checkboxes
            if ((strlen($name) == 32) and ($val == "on")) {
                $cat = $HTTP_GET_VARS["targetcat"];
                $cat_idx = cat2idx($cat);
                amule_do_search_download_cmd($name, $cat_idx);
            }
        }
    } else {
    }
}
$search = amule_load_vars("searchresult");

$sort_order = $HTTP_GET_VARS["sort"];

if ($sort_order == "") {
    $sort_order = $_SESSION["search_sort"];
} else {
    if ($_SESSION["search_sort_reverse"] == "") {
        $_SESSION["search_sort_reverse"] = 0;
    } else {
        $_SESSION["search_sort_reverse"] = !$_SESSION["search_sort_reverse"];
    }
}

$sort_reverse = $_SESSION["search_sort_reverse"];
if ($sort_order != "") {
    $_SESSION["search_sort"] = $sort_order;
    usort(&$search, "my_cmp");
}

echo '<ul data-role="listview" data-filter="true" data-filter-placeholder="Search file..." data-divider-theme="d" data-split-icon="check">';
echo '<li data-role="list-divider">Search results<span class="ui-li-count">' . count($search) . '</span></li>';
foreach ($search as $file) {
    echo '<li data-filtertext="', $file->short_name, '"><a data-hash="', $file->hash, '" class="file-check">';
    echo "<h3>", $file->short_name, "</h3>";
    echo "<p>", CastToXBytes($file->size), "</p>";
    echo '<span class="ui-li-count">', $file->sources, '</span>';
    echo "</a>";

    echo '<a id="', $file->hash, '" data-hash="', $file->hash, '" class="file-check">Select file</a>';
    echo "</li>";
}
echo '</ul>';
?>
<button id="Download6" onClick="javascript:formCommandSubmit('download');" class="ui-btn ui-corner-all ui-icon-plus ui-btn-icon-right"
        style="padding-right: 10px; margin-top: 30px;">
    Download
</button>
<select name="targetcat" id="select32" data-native-menu="false">
    <?php
    $cats = amule_get_categories();
    foreach ($cats as $c) {
        echo "<option value=\"" . $c . "\">", $c, "</option>";
    }
    ?>
</select>
</form>

<script>
    $(document).one('pagecreate', function () {

        $('.ui-listview').on('vclick', '.file-check', function () {
            event.stopPropagation();
            console.log('click')
            var checkboxHashId = $(this).attr('data-hash');

            $('<input>').attr({
                type: 'hidden',
                name: checkboxHashId,
                value: 'on'
            }).appendTo('form[name="mainform"]');

            $('#' + checkboxHashId).toggleClass('ui-btn-active');
        });

        $('form[name="mainform"]').on('submit', function (event) {
            event.preventDefault();

            $(this).jQMobileAjaxSubmit();
        });
    });

    formCommandSubmit = function (command) {
        <?php
            if ($_SESSION["guest_login"] != 0) {
                    echo 'alert("You logged in as guest - commands are disabled");';
                    echo "return;";
            }
        ?>

        $('input[name="command"]').attr('value', command);
        $('form[name="mainform"]').submit();
    }
</script>

