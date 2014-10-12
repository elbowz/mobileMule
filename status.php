<ul data-role="listview" data-inset="true">
    <li data-role="list-divider">Speed</li>
    <li>
        <h2 id="speed_down">n / n</h2>

        <p class="ui-li-aside"><strong>Download / Limit</strong></p>
    </li>
    <li>
        <h2 id="speed_up">n / n</h2>

        <p class="ui-li-aside"><strong>Upload / Limit</strong></p>
    </li>
    <li id="server" data-role="list-divider">Server (init ...)</li>
    <li>
        <h2 id="server-address">x.x.x.x</h2>

        <p><strong>Address</strong></p>
    </li>
    <li>
        <h2 id="server-name">init</h2>

        <p><strong>Name</strong></p>
    </li>
    <li id="kad" data-role="list-divider">Kad (init ...)</li>
    <li><p><strong>note:</strong> this info is refreshed each 3 seconds.</p></li>
</ul>

<script>
    $(document).one('pagecreate', function () {
        updateStatus();
        globalTimer = setInterval(updateStatus, 3000);
    });

    updateStatus = function () {
        $.getJSON('ajax-status.php', function (data) {
            for (var key in data) {
                switch (key) {
                    case 'speed_down':
                        $('#' + key).html(
                                (data[key] / 1024).toFixed(2) + ' / '
                                + data['speed_limit_down'] / 1024
                                + ' Kb');
                        break;
                    case 'speed_up':
                        $('#' + key).html(
                                (data[key] / 1024).toFixed(2) + ' / '
                                + data['speed_limit_up'] / 1024
                                + ' Kb');
                        break;
                    case 'id':
                        var connectionStatus = null;
                        if (data.id == 0) {
                            connectionStatus = 'Not connected';
                            $('.server-child').hide();
                        } else if (data.id == Number('0xffffffff')) {
                            connectionStatus = 'Connecting ...';
                            $('.server-child').hide();
                        } else {
                            connectionStatus = 'Connected with ';
                            if (data.id < 16777216)
                                connectionStatus += 'low ID';
                            else
                                connectionStatus += 'high ID';

                            $('#server-address').html(data.serv_addr);
                            $('#server-name').html(data.serv_name);
                            //$('#server-users').html(data.serv_users);

                        }
                        $('#server').html(
                                'Server (' + connectionStatus + ')');
                        break;
                    case 'kad_connected':
                        var connectionStatus = null;
                        if (data.kad_connected == 1) {
                            connectionStatus = 'Connected ';
                            if (data.kad_firewalled == 1) {
                                connectionStatus += 'but Firewalled';
                            } else {
                                connectionStatus += 'OK';
                            }
                        } else {
                            connectionStatus = 'Not connected';
                        }
                        $('#kad').html('Kad (' + connectionStatus + ')');
                        break;
                }
            }
        });
    }
</script>