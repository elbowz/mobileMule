<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <title>Servers - mobileMule</title>
    <link rel="stylesheet" href="//netdna.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" />
    <link rel="stylesheet" href="//ajax.googleapis.com/ajax/libs/jquerymobile/1.4.2/jquery.mobile.min.css" />
    <link rel="stylesheet" href="mystyle.css" />
    <script src="//ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
    <script src="myscript.js"></script>
    <script src="//ajax.googleapis.com/ajax/libs/jquerymobile/1.4.2/jquery.mobile.min.js"></script>
</head>
<body>
<div data-role="page" id="uploads" class="type-interior">

    <div data-role="panel" id="menu-panel" data-display="reveal" data-position-fixed="true">
        <!-- here is injected menù from pagebeforecreate event -->
    </div>

    <div data-role="header" data-position="fixed">
        <a href="#menu-panel" data-rel="close" id="btMenu" data-icon="home" class="ui-btn-left">Menu</a>
        <h1><i class="fa fa-database fa-fw"></i> Servers</h1>
        <a href="#" data-rel="back" data-icon="arrow-l" class="ui-btn-right">Back</a>
    </div><!-- /header -->

    <div data-role="content">
        <form action="amuleweb-main-servers.php" method="get" name="mainform">
            <?php
            if ($_SESSION["guest_login"] != 0) {
                echo "<b>&nbsp;You logged in as guest - commands are disabled</b>";
            }
            ?>
            <legend>Sort: </legend>
            <div data-role="controlgroup">
                <select name="sort" id="sort" data-native-menu="false">
                    <option value="name"<?php echo (('name' == $HTTP_GET_VARS["sort"]) ? ' selected' : ''); ?>>Server Name</option>
                    <option value="desc"<?php echo (('desc' == $HTTP_GET_VARS["sort"]) ? ' selected' : ''); ?>>Description</option>
                    <option value="users"<?php echo (('users' == $HTTP_GET_VARS["sort"]) ? ' selected' : ''); ?>>Users</option>
                    <option value="files"<?php echo (('files' == $HTTP_GET_VARS["sort"]) ? ' selected' : ''); ?>>Files</option>
                </select>
                <?php
                if( $HTTP_GET_VARS["download_sort_reverse"] )
                    echo '<a id="sort_reverse" href="#" data-value="0" data-role="button" data-theme="d" data-icon="arrow-u" data-iconpos="right" style="padding-right: 10px;">Ascendent</a>';
                else
                    echo '<a id="sort_reverse" href="#" data-value="1" data-role="button" data-theme="d" data-icon="arrow-d" data-iconpos="right" style="padding-right: 10px;">Descendent</a>';
                ?>
            </div>
        </form>

        <br/>

        <?php
        //
        // declare it here, before any function reffered it in "global"
        //
        $sort_order;$sort_reverse;

        function my_cmp($a, $b)
        {
            global $sort_order, $sort_reverse;
            switch ( $sort_order) {
                case "name": $result = $a->name > $b->name; break;
                case "desc": $result = $a->desc > $b->desc; break;
                case "users": $result = $a->users > $b->users; break;
                case "files":$result = $a->files > $b->files; break;
            }

            if ( $sort_reverse ) {
                $result = !$result;
            }
            return $result;
        }

        $servers = amule_load_vars("servers");

        $sort_order = $HTTP_GET_VARS["sort"];

        //
        // perform command before processing content
        //
        if ( ($HTTP_GET_VARS["cmd"] != "") and ($HTTP_GET_VARS["ip"] != "") and ($HTTP_GET_VARS["port"] != "")) {
            if ($_SESSION["guest_login"] == 0) {
                amule_do_server_cmd($HTTP_GET_VARS["ip"], $HTTP_GET_VARS["port"], $HTTP_GET_VARS["cmd"]);
            }
        }

        if ( $sort_order == "" ) {
            $sort_order = $_SESSION["servers_sort"];
        } else {
            if ( $_SESSION["sort_reverse"] == "" ) {
                $_SESSION["sort_reverse"] = 0;
            } else {
                $_SESSION["sort_reverse"] = !$_SESSION["sort_reverse"];
            }
        }

        $sort_reverse = $_SESSION["sort_reverse"];
        if ( $sort_order != "" ) {
            $_SESSION["servers_sort"] = $sort_order;
            usort(&$servers, "my_cmp");
        }

        echo '<ul data-role="listview" data-split-icon="delete" data-filter="true" data-filter-placeholder="Search file...">';
        echo '<li data-role="list-divider">Servers<span class="ui-li-count">'.count($servers).'</span></li>';
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

            echo '<li data-filtertext="', $srv->name, '">';
            if ($_SESSION["guest_login"] == 0)
                echo '<a href="amuleweb-main-servers.php?cmd=connect&ip=', $srv->ip, '&port=', $srv->port, '">';
            echo '<h3>', $srv->name, '</h3>';
            echo '<p><strong>', $srv->desc, '</strong></p>';
            echo '<p>', $srv->addr, ' - ';
            echo '<i>users: ', $srv->users, '</i></p>';
            echo '<span class="ui-li-count">', $srv->files, ' files</span>';
            if ($_SESSION["guest_login"] == 0)
                echo '<a href="amuleweb-main-servers.php?cmd=remove&ip=', $srv->ip, '&port=', $srv->port, '" data-rel="popup" data-position-to="window" data-transition="pop">Purchase album</a>';

            echo "</li>";
        }
        echo '</ul>';
        ?>
        <!--/content-primary -->
    </div>
    <!-- /content -->

    <div data-role="footer" role="contentinfo" class="ui-footer" data-theme="c">
        <a href="#" id="btScrollUp" data-role="button" data-icon="arrow-u" class="ui-btn-right">scroll up</a>
        <p>&nbsp;<a href="amuleweb-main-about.php" title="about" data-rel="dialog" data-transition="pop">mobileMule</a> &copy; 2014</p>
    </div>
    <!-- /footer -->
    <script>
        $(document).ready(function () {
            $('select[name="sort"]').change(function () {
                setTimeout(function(){$('form[name="mainform"]').submit();},500)
            });

            $('#sort_reverse').bind("click",function () {
                event.preventDefault();

                var value = $('#sort_reverse').attr('data-value');
                $('<input>').attr({
                    type: 'hidden',
                    name: 'download_sort_reverse',
                    value: value
                }).appendTo('form[name="mainform"]');

                $(this).closest('form').submit();
            });
        });
    </script>
</div>
<!-- /page -->
</body>
</html>
