<?php

function optCast($value)
{
    if ($value == 'on' || $value == 'ON') $value = 1;
    if ($value == 'off' || $value == 'OFF') $value = 0;
    if ($value == '') $value = null;

    return $value;
}

$command = $HTTP_GET_VARS["command"];
$isLogged = $_SESSION["guest_login"] == 0;

switch ($command) {
    case 'set-config':

        if (!$isLogged) return;

        // taken opts list from: http://wiki.amule.org/wiki/AMuleWeb_PHP#amule_get_options
        $file_opts = array("ich_en", "aich_trust", "new_files_paused", "new_files_auto_dl_prio", "preview_prio",
            "new_files_auto_ul_prio", "upload_full_chunks", "first_last_chunks_prio", "start_next_paused",
            "resume_same_cat", "save_sources", "extract_metadata", "alloc_full", "check_free_space", "min_free_space"
        );

        $conn_opts = array("max_line_up_cap", "max_line_down_cap", "max_up_limit", "max_down_limit", "slot_alloc",
            "tcp_port", "udp_port", "udp_dis", "max_file_src", "max_conn_total", "autoconn_en", "reconn_en"
        );

        $webserver_opts = array("use_gzip", "autorefresh_time");

        $coretweaks_opts = array("max_conn_5sec");

        $all_opts;

        $jsonResult = '{';
        foreach ($conn_opts as $i) {
            $value = optCast($HTTP_GET_VARS[$i]);

            if ($value != null) {
                $all_opts["connection"][$i] = $value;
                $jsonResult .= '"' . $i . '": "' . $value . '",';
            }
        }

        foreach ($file_opts as $i) {
            $value = optCast($HTTP_GET_VARS[$i]);

            if ($value != null) {
                $all_opts["files"][$i] = $value;

                $jsonResult .= '"' . $i . '": "' . $value . '",';
            }
        }

        foreach ($webserver_opts as $i) {
            $value = optCast($HTTP_GET_VARS[$i]);

            if ($value != null) {
                $all_opts["webserver"][$i] = $value;

                $jsonResult .= '"' . $i . '": "' . $value . '",';
            }
        }

        foreach ($coretweaks_opts as $i) {
            $value = optCast($HTTP_GET_VARS[$i]);

            if ($value != null) {
                $all_opts["coretweaks"][$i] = $value;

                $jsonResult .= '"' . $i . '": "' . $value . '",';
            }
        }
        $jsonResult .= '"lastitem": "null"';
        $jsonResult .= '}';

        amule_set_options($all_opts);

        echo $jsonResult;

        break;

    case 'get-config':

        $all_opts = amule_get_options();

        $opt_groups = array("connection", "files", "webserver", "coretweaks");

        echo '{';
        foreach ($opt_groups as $group) {
            $curr_opts = $all_opts[$group];

            echo '"', $group, '": {';

            foreach ($curr_opts as $opt_name => $opt_val) {
                echo '"' . $opt_name . '": "' . $opt_val . '",';
            }
            echo '"lastitem": "null"';
            echo "},";
        }
        echo '"lastitem": "null"';
        echo '}';

        break;
}
?>