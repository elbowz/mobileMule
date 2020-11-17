<ul data-role="listview" data-inset="true">
    <li data-role="list-divider"><i class="fa fa-rocket fa-fw"></i> Speed</li>
    <li>
        <a href="#page-downloads" class="hash-link">
            <h2><i class="fa fa-download fa-fw"></i> <span id="speed_down">n / n</span></h2>
            <p><strong>Download <small>/ Limit</small></strong></p>
        </a>
    </li>
    <li>
        <a href="#page-uploads" class="hash-link">
            <h2><i class="fa fa-upload fa-fw"></i> <span id="speed_up">n / n</span></h2>
            <p><strong>Upload <small>/ Limit</small></strong></p>
        </a>
    </li>
    <li>
        <h2><i class="fa fa-tachometer fa-fw"></i> Turtle mode</h2>
        <p><strong>
                <span id="turtlemode-limits">n / n</span>
                <small>Bandwith (<a href="#page-mobilemule" class="hash-link">change it</a>)</small>
            </strong>
        </p>
        <div class="ui-li-aside" style="right: 1.33em;">
            <input type="checkbox" data-role="flipswitch" name="turtle-mode-toggle" id="turtle-mode-toggle">
            <p class="d-none d-md-block m-0">
                <strong><small>enable/disable on the same browser or use the same values of limited bandwidth.</small></strong>
            </p>
        </div>
    </li>
    <li data-role="list-divider"><i class="fa fa-database fa-fw"></i> <span id="server">Server (init ...)</span></li>
    <li>
        <a href="#page-servers" class="hash-link">
            <h2><span id="server-name">init</span></h2>
            <p><strong><span id="server-address">x.x.x.x</span></strong></p>
            <span class="ui-li-count"><i class="fa fa-users fa-fw"></i> <span id="server-users">n</span></span>
        </a>
    </li>
    <li data-role="list-divider"><i class="fa fa-share-alt fa-fw"></i> <span id="kad">Kad (init ...)</span></li>
    <li data-role="list-divider"><i class="fa fa-line-chart fa-fw"></i> Real-time Speed Chart</li>
    <li>
        <canvas id="speed-chart" height="150" width="300"></canvas>
    </li>
    <li><p id="refresh">this info is refreshed each <strong>xxx</strong> seconds (<a href="#page-mobilemule"
                                                                                     class="hash-link">change it</a>)
        </p></li>
</ul>

<script>
$(document).one('pagecreate', function () {

    var count = 6;
    var speedChart;
    var chartMaxXtick = mm.settings.page.status.tickChart;
    var $turtleMode = $('input#turtle-mode-toggle');

    $('#refresh > strong').text(mm.settings.page.status.refresh / 1000);
    $('#turtlemode-limits').text(mm.settings.turtleMode.maxDownLimit + ' / ' + mm.settings.turtleMode.maxUpLimit + ' KB/s');
    speedChart = initSpeedChart('speed-chart', count);

    $.getJSON('utils-ajax.php', { 'command': 'get-config' })
        .done(function (json) {
            var isTurtleMode =
                json.connection.max_down_limit == mm.settings.turtleMode.maxDownLimit &&
                json.connection.max_up_limit == mm.settings.turtleMode.maxUpLimit;

            $turtleMode.prop('checked', isTurtleMode).flipswitch('refresh');

            // enable event bind after init (avoid the handler execution)
            $turtleMode.on("change", turtleModeOnChange);

            // disalbe if we don't have the limited bandwidth to restore
            if(isTurtleMode && !mm.localStorage.get('tm-max-down-limit-bak', 0)) {
                $turtleMode.flipswitch( 'disable' );
            }
        });


    updateStatus();
    globalTimer = setInterval(updateStatus, mm.settings.page.status.refresh);

    function updateStatus() {

        $.getJSON('status-ajax.php', function (data) {

            var now = new Date().toTimeString().replace(/.*(\d{2}:\d{2}).*/, "$1");

            // Add data to Graph
            speedChart.addData([(data['speed_up'] / 1024).toFixed(2), (data['speed_down'] / 1024).toFixed(2)], now);

            if (count > chartMaxXtick) speedChart.removeData();

            for (var key in data) {
                switch (key) {
                    case 'speed_down':
                        $('#' + key).html(
                            bytesToSize(data[key]) + '/s' +
                            '<small> / ' + bytesToSize(data['speed_limit_down']) + '/s</small>');
                        break;
                    case 'speed_up':
                        $('#' + key).html(
                            bytesToSize(data[key]) + '/s' +
                            '<small> / ' + bytesToSize(data['speed_limit_up']) + '/s</small>');
                        break;
                    case 'id':
                        var connectionStatus = null;
                        if (data.id == 0) {
                            connectionStatus = 'Not connected <i class="fa fa-plug fa-fw"></i>';
                            $('.server-child').hide();
                        } else if (data.id == Number('0xffffffff')) {
                            connectionStatus = 'Connecting ...';
                            $('.server-child').hide();
                        } else {
                            connectionStatus = 'Connected with ';
                            if (data.id < 16777216)
                                connectionStatus += 'low ID <i class="fa fa-thumbs-o-down fa-fw"></i>';
                            else
                                connectionStatus += 'high ID <i class="fa fa-thumbs-o-up fa-fw"></i>';

                            $('#server-address').html(data.serv_addr.substring(1, data.serv_addr.length - 1));
                            $('#server-name').html(data.serv_name);
                            $('#server-users').html(data.serv_users);

                        }
                        $('#server').html('Server (' + connectionStatus + ')');
                        break;
                    case 'kad_connected':
                        var connectionStatus = null;
                        if (data.kad_connected == 1) {
                            connectionStatus = 'Connected ';
                            if (data.kad_firewalled == 1) {
                                connectionStatus += 'but Firewalled <i class="fa fa-thumbs-o-down fa-fw"></i>';
                            } else {

                                connectionStatus += 'OK <i class="fa fa-thumbs-o-up fa-fw"></i>';
                            }
                        } else {
                            connectionStatus = 'Not connected <i class="fa fa-plug fa-fw"></i>';
                        }
                        $('#kad').html('Kad (' + connectionStatus + ')');
                        break;
                }
            }

            count++;
        });
    };

    function initSpeedChart(idCanvasChart, nInitValue) {
        var lineChartData = {
            labels: [],
            datasets: [
                {
                    label: "Speed Upload",
                    fillColor: "rgba(220,220,220,0.2)",
                    strokeColor: "rgba(220,220,220,1)",
                    pointColor: "rgba(220,220,220,1)",
                    pointStrokeColor: "#fff",
                    pointHighlightFill: "#fff",
                    pointHighlightStroke: "rgba(220,220,220,1)",
                    data: []
                },
                {
                    label: "Speed Download",
                    fillColor: "rgba(151,187,205,0.2)",
                    strokeColor: "rgba(151,187,205,1)",
                    pointColor: "rgba(151,187,205,1)",
                    pointStrokeColor: "#fff",
                    pointHighlightFill: "#fff",
                    pointHighlightStroke: "rgba(151,187,205,1)",
                    data: []
                }
            ]

        };

        var ctx = document.getElementById(idCanvasChart).getContext("2d");
        speedChart = new Chart(ctx).Line(lineChartData, {
            responsive: true
        });

        var now = new Date().toTimeString().replace(/.*(\d{2}:\d{2}).*/, "$1");

        while (nInitValue) {
            speedChart.addData([0, 0], now);
            nInitValue--;
        }
        ;

        return speedChart;
    };

    function turtleModeOnChange () {

        var maxDownLimit, maxUpLimit;
        var enable = $(this).is(':checked');

        if (enable) {
            $.getJSON('utils-ajax.php', { 'command': 'get-config' })
                .done(function (json) {
                    mm.localStorage.set('tm-max-down-limit-bak', json.connection.max_down_limit);
                    mm.localStorage.set('tm-max-up-limit-bak', json.connection.max_up_limit);
                });

            maxDownLimit = mm.settings.turtleMode.maxDownLimit;
            maxUpLimit = mm.settings.turtleMode.maxUpLimit;
        } else {
            maxDownLimit = mm.localStorage.get('tm-max-down-limit-bak', 100);
            maxUpLimit = mm.localStorage.get('tm-max-up-limit-bak', 10);
        }

        $.getJSON('utils-ajax.php',
            {
                'command': 'set-config',
                'max_down_limit': maxDownLimit,
                'max_up_limit': maxUpLimit
            })
            .done(function (json) {
            });
    };
});
</script>