<form name="mainform" action="config.php" method="post" data-ajax="false">
    <ul data-role="listview" data-inset="true">

        <li data-role="list-divider">Webserver<span class="ui-li-count">1</span></li>
        <!--<li class="ui-field-contain">
            <label for="autorefresh_time7">Page refresh interval:</label>
            <input name="autorefresh_time" type="number" id="autorefresh_time7" />
        </li>-->
        <li class="ui-field-contain">
            <label for="use_gzip5">Use gzip compression</label>
            <input type="checkbox" data-role="flipswitch" name="use_gzip" id="use_gzip5">
        </li>

        <li data-role="list-divider">Line capacity (for statistics only)<span class="ui-li-count">2</span></li>
        <li class="ui-field-contain">
            <label for="max_line_down_cap6">Max download rate:</label>
            <input name="max_line_down_cap" type="number" id="max_line_down_cap6"/>
        </li>
        <li class="ui-field-contain">
            <label for="max_line_up_cap7">Max upload rate:</label>
            <input name="max_line_up_cap" type="number" id="max_line_up_cap7"/>
        </li>

        <li data-role="list-divider">Bandwidth limits<span class="ui-li-count">3</span></li>
        <li class="ui-field-contain">
            <label for="max_down_limit6">Max download rate:</label>
            <input name="max_down_limit" type="number" id="max_down_limit6"/>
        </li>
        <li class="ui-field-contain">
            <label for="max_up_limit6">Max upload rate:</label>
            <input name="max_up_limit" type="number" id="max_up_limit6"/>
        </li>
        <li class="ui-field-contain">
            <label for="slot_alloc6">Slot allocation:</label>
            <input name="slot_alloc" type="number" id="slot_alloc6"/>
        </li>

        <li data-role="list-divider">File settings<span class="ui-li-count">9</span></li>
        <li class="ui-field-contain">
            <label for="check_free_space5">Check free space</label>
            <input name="check_free_space" type="checkbox" id="check_free_space5" data-role="flipswitch"/>
        </li>
        <li class="ui-field-contain">
            <label for="min_free_space4">Minimum free space (Mb)</label>
            <input name="min_free_space" type="number" id="min_free_space4"/>
        </li>
        <li class="ui-field-contain">
            <label for="new_files_auto_dl_prio4">Added download files have auto priority</label>
            <input name="new_files_auto_dl_prio" type="checkbox" id="new_files_auto_dl_prio4" data-role="flipswitch"/>
        </li>
        <li class="ui-field-contain">
            <label for="new_files_auto_ul_prio4">New shared files have auto priority</label>
            <input name="new_files_auto_ul_prio" type="checkbox" id="new_files_auto_ul_prio4" data-role="flipswitch"/>
        </li>
        <li class="ui-field-contain">
            <label for="ich_en5">I.C.H. active</label>
            <input name="ich_en" type="checkbox" id="ich_en5" data-role="flipswitch"/>
        </li>
        <li class="ui-field-contain">
            <label for="aich_trust4">AICH trusts every hash (not recommended)</label>
            <input name="aich_trust" type="checkbox" id="aich_trust4" data-role="flipswitch"/>
        </li>
        <li class="ui-field-contain">
            <label for="alloc_full_chunks4">Alloc full chunks of .part files</label>
            <input name="alloc_full_chunks" type="checkbox" id="alloc_full_chunks4" data-role="flipswitch"/>
        </li>
        <li class="ui-field-contain">
            <label for="alloc_full4">Alloc full disk space for .part files</label>
            <input name="alloc_full" type="checkbox" id="alloc_full4" data-role="flipswitch"/>
        </li>
        <li class="ui-field-contain">
            <label for="new_files_paused4">Add files to download queue in pause mode</label>
            <input name="new_files_paused" type="checkbox" id="new_files_paused4" data-role="flipswitch"/>
        </li>
        <li class="ui-field-contain">
            <label for="extract_metadata4">Extract metadata tags</label>
            <input name="extract_metadata" type="checkbox" id="extract_metadata4" data-role="flipswitch"/>
        </li>

        <li data-role="list-divider">Connection settings<span class="ui-li-count">4</span></li>
        <li class="ui-field-contain">
            <label for="max_conn_total8">Max total connections (total):</label>
            <input name="max_conn_total" type="number" id="max_conn_total8"/>
        </li>
        <li class="ui-field-contain">
            <label for="max_file_src7">Max sources per file:</label>
            <input name="max_file_src" type="number" id="max_file_src7"/>
        </li>
        <li class="ui-field-contain">
            <label for="autoconn_en6">Autoconnect at startup</label>
            <input name="autoconn_en" type="checkbox" id="autoconn_en6" data-role="flipswitch"/>
        </li>
        <li class="ui-field-contain">
            <label for="reconn_en6">Reconnect when connection lost</label>
            <input name="reconn_en" type="checkbox" id="reconn_en6" data-role="flipswitch"/>
        </li>

        <li data-role="list-divider">Network settings<span class="ui-li-count">4</span></li>
        <li class="ui-field-contain">
            <label for="tcp_port6">TCP port:</label>
            <input name="tcp_port" type="number" id="tcp_port6"/>
        </li>
        <li class="ui-field-contain">
            <label for="udp_port6">UDP port:</label>
            <input name="udp_port" type="number" id="udp_port6"/>
        </li>
        <li class="ui-field-contain">
            <label for="udp_dis5">Disable UDP connections</label>
            <input name="udp_dis" type="checkbox" id="udp_dis5" data-role="flipswitch"/>
        </li>
        <li class="ui-field-contain">
            <?php
            if ($_SESSION["guest_login"] == 0) {
                echo '<button type="submit" name="Submit" value="Apply">Apply</button>';
            } else {
                echo "<b>&nbsp;You can not change options - logged in as guest</b>";
            }
            ?>
        </li>
        <input name="command" type="hidden" id="command">
</form>

<script>
    var initvals = new Object;

    <?php
        // apply new options before proceeding
        //var_dump($HTTP_GET_VARS);
        if ( ($HTTP_GET_VARS["Submit"] == "Apply") && ($_SESSION["guest_login"] == 0) ) {
            $file_opts = array("check_free_space", "extract_metadata",
                "ich_en","aich_trust", "preview_prio","save_sources", "resume_same_cat",
                "min_free_space", "new_files_paused", "alloc_full", "alloc_full_chunks",
                "new_files_auto_dl_prio", "new_files_auto_ul_prio"
            );
            $conn_opts = array("max_line_up_cap","max_up_limit",
                "max_line_down_cap","max_down_limit", "slot_alloc",
                "tcp_port","udp_dis","max_file_src","max_conn_total","autoconn_en");
            $webserver_opts = array("use_gzip", "autorefresh_time");

            $all_opts;
            foreach ($conn_opts as $i) {
                $curr_value = $HTTP_GET_VARS[$i];
                if ( $curr_value == "on") $curr_value = 1;
                if ( $curr_value == "") $curr_value = 0;

                $all_opts["connection"][$i] = $curr_value;
            }
            foreach ($file_opts as $i) {
                $curr_value = $HTTP_GET_VARS[$i];
                if ( $curr_value == "on") $curr_value = 1;
                if ( $curr_value == "") $curr_value = 0;

                $all_opts["files"][$i] = $curr_value;
            }
            foreach ($webserver_opts as $i) {
                $curr_value = $HTTP_GET_VARS[$i];
                if ( $curr_value == "on") $curr_value = 1;
                if ( $curr_value == "") $curr_value = 0;

                $all_opts["webserver"][$i] = $curr_value;
            }
            //var_dump($all_opts);
            amule_set_options($all_opts);
        }
        $opts = amule_get_options();
        //var_dump($opts);
        $opt_groups = array("connection", "files", "webserver");
        //var_dump($opt_groups);
        foreach ($opt_groups as $group) {
            $curr_opts = $opts[$group];
            //var_dump($curr_opts);
            foreach ($curr_opts as $opt_name => $opt_val) {
                echo 'initvals["', $opt_name, '"] = "', $opt_val, '";';
            }
        }
    ?>
    $(document).one('pagecreate', function () {
        $('input[data-role="flipswitch"]').trigger('create');
        $('form[name="mainform"]').on('submit', function (event) {
            event.preventDefault();

            $('<input>').attr({
                type: 'hidden',
                name: 'Submit',
                value: 'Apply'
            }).appendTo('form[name="mainform"]');

            $(this).jQMobileAjaxSubmit();
        });

        // Assign php generated data to controls
        var frm = document.forms.mainform

        var str_param_names = new Array(
            "max_line_down_cap", "max_line_up_cap",
            "max_up_limit", "max_down_limit", "max_file_src",
            "slot_alloc", "max_conn_total",
            "tcp_port", "udp_port",
            "min_free_space",
            "autorefresh_time"
        );

        for (i = 0; i < str_param_names.length; i++) {
            $('input[name="' + str_param_names[i] + '"]').val(initvals[str_param_names[i]]);
            //frm[str_param_names[i]].value = initvals[str_param_names[i]];
        }

        var check_param_names = new Array(
            "autoconn_en", "reconn_en", "udp_dis", "new_files_paused",
            "aich_trust", "alloc_full", "alloc_full_chunks",
            "check_free_space", "extract_metadata", "ich_en",
            "new_files_auto_dl_prio", "new_files_auto_ul_prio",
            "use_gzip"
        );

        for (i = 0; i < check_param_names.length; i++) {
            //frm[check_param_names[i]].checked = initvals[check_param_names[i]] == "1" ? true : false;
            if (initvals[check_param_names[i]] == "1")
                $("input[name='" + check_param_names[i] + "']").attr("checked", true).flipswitch().flipswitch( "refresh" );
        }
    });
</script>

