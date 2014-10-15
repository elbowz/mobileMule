<?php
//
// declare it here, before any function reffered it in "global"
//
$sort_order;
$sort_reverse;

function my_cmp($a, $b)
{
    global $sort_order, $sort_reverse;
    switch ($sort_order) {
        case "name":
            $result = $a->name > $b->name;
            break;
        case "desc":
            $result = $a->desc > $b->desc;
            break;
        case "users":
            $result = $a->users > $b->users;
            break;
        case "files":
            $result = $a->files > $b->files;
            break;
    }

    if ($sort_reverse) {
        $result = !$result;
    }
    return $result;
}

$servers = amule_load_vars("servers");

$sort_order = $HTTP_GET_VARS["sort"];

//
// perform command before processing content
//
if (($HTTP_GET_VARS["cmd"] != "") and ($HTTP_GET_VARS["ip"] != "") and ($HTTP_GET_VARS["port"] != "")) {
    if ($_SESSION["guest_login"] == 0) {
        amule_do_server_cmd($HTTP_GET_VARS["ip"], $HTTP_GET_VARS["port"], $HTTP_GET_VARS["cmd"]);
    }
}

if ($sort_order == "") {
    $sort_order = $_SESSION["servers_sort"];
} else {
    if ($_SESSION["sort_reverse"] == "") {
        $_SESSION["sort_reverse"] = 0;
    } else {
        $_SESSION["sort_reverse"] = !$_SESSION["sort_reverse"];
    }
}

$sort_reverse = $_SESSION["sort_reverse"];
if ($sort_order != "") {
    $_SESSION["servers_sort"] = $sort_order;
    usort(&$servers, "my_cmp");
}

// Wait the server is connected or disconnect ( $status['id'] == '0')
$status;
$nTryToConnect = 0;
do {
    $nTryToConnect++;
    $status = amule_get_stats();
    // there is not a sleep()...sorry
    usort($servers, "my_cmp");
} while ($status['serv_name'] == '' && $status['id'] != '0' && $nTryToConnect < 10000);

echo '<ul data-role="listview" data-split-icon="delete" data-filter="true" data-filter-placeholder="Filter servers...">';
echo '<li data-role="list-divider">Servers<span class="ui-li-count">' . count($servers) . '</span></li>';
foreach ($servers as $srv) {
    /* if ($_SESSION["guest_login"] != 0) {
         echo "";
     } else {
         echo '<a href="amuleweb-main-servers.php?cmd=connect&ip=', $srv->ip,
         '&port=', $srv->port, '">',
         '<img src="images/connect.gif" width="16" height="16" border="0">','</a>',
         '<a href="amuleweb-main-servers.php?cmd=remove&ip=', $srv->ip,
         '&port=', $srv->port, '">',
         '<img src="images/cancel.gif" width="16" height="16" border="0">','</a>';
     }*/

    if ($status['serv_name'] == $srv->name) {
        $linkClass = ' bg-green';
        $srvNameAppend = ' (Connected)';
    } else {
        $linkClass = '';
        $srvNameAppend = '';
    }

    echo '<li data-filtertext="', $srv->name, '">';
    if ($_SESSION["guest_login"] == 0)
        echo '<a href="servers.php?cmd=connect&ip=', $srv->ip, '&port=', $srv->port, '" data-text="Connecting to server..." class="ajax-link' , $linkClass, '">';
    echo '<h3>', $srv->name, $srvNameAppend, '</h3>';
    echo '<p><strong>', $srv->desc, '</strong></p>';
    echo '<p>', $srv->addr, ' - ';
    echo '<i>files: ', $srv->files, '</i></p>';
    echo '<span class="ui-li-count"><i class="fa fa-users fa-fw"></i> ', $srv->users, '</span>';
    if ($_SESSION["guest_login"] == 0)
        echo '<a href="servers.php?cmd=remove&ip=', $srv->ip, '&port=', $srv->port, '" class="ajax-link">Remove Server</a>';

    echo "</li>";
}
echo '</ul>';
?>
<br/>
<form action="servers.php" method="get" name="mainform">
    <?php
    if ($_SESSION["guest_login"] != 0) {
        echo "<b>&nbsp;You logged in as guest - commands are disabled</b>";
    }
    ?>
    <legend>Sort:</legend>
    <div data-role="controlgroup">
        <select name="sort" id="sort" data-native-menu="false">
            <option value="name"<?php echo(('name' == $HTTP_GET_VARS["sort"]) ? ' selected' : ''); ?>>Server Name</option>
            <option value="desc"<?php echo(('desc' == $HTTP_GET_VARS["sort"]) ? ' selected' : ''); ?>>Description</option>
            <option value="users"<?php echo(('users' == $HTTP_GET_VARS["sort"]) ? ' selected' : ''); ?>>Users</option>
            <option value="files"<?php echo(('files' == $HTTP_GET_VARS["sort"]) ? ' selected' : ''); ?>>Files</option>
        </select>
        <?php
        if ($HTTP_GET_VARS["download_sort_reverse"])
            echo '<a id="sort_reverse" href="#" data-value="0" data-role="button" data-icon="arrow-u" data-iconpos="right" style="padding-right: 38px;">Ascendent</a>';
        else
            echo '<a id="sort_reverse" href="#" data-value="1" data-role="button" data-icon="arrow-d" data-iconpos="right" style="padding-right: 38px;">Descendent</a>';
        ?>
    </div>
</form>

<script>
    $(document).one('pagecreate', function () {

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
</script>
