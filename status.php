<ul data-role="listview" data-inset="true">
    <li data-role="list-divider"><i class="fa fa-rocket fa-fw"></i> Speed</li>
    <li>
        <a href="#page-downloads" class="hash-link">
            <h2><i class="fa fa-download fa-fw"></i> <span id="speed_down">n / n</span></h2>
            <p class="ui-li-aside"><strong>Download <small>/ Limit</small></strong></p>
        </a>
    </li>
    <li>
        <a href="#page-uploads" class="hash-link">
            <h2><i class="fa fa-upload fa-fw"></i> <span id="speed_up">n / n</span></h2>
            <p class="ui-li-aside"><strong>Upload <small>/ Limit</small></strong></p>
        </a>
    </li>
    <li data-role="list-divider"><i class="fa fa-database fa-fw"></i> <span id="server">Server (init ...)</span></li>
    <li>
        <a href="#page-servers" class="hash-link">
            <h2><span id="server-name">init</span></h2>
            <p><strong><span id="server-address">x.x.x.x</span></strong></p>
            <span class="ui-li-count"><small>users: </small><span id="server-users">n</span></span>
        </a>
    </li>
    <li data-role="list-divider"><i class="fa fa-share-alt fa-fw"></i> <span id="kad">Kad (init ...)</span></li>
    <li><p><strong>note:</strong> this info is refreshed each 3 seconds...wait the chart plotting.</p></li>
    <li data-role="list-divider"><i class="fa fa-line-chart fa-fw"></i> Real-time Speed Chart</li>
    <li>
        <canvas id="speed-chart" height="150" width="300"></canvas>
    </li>
</ul>

<script>
    var myLine;
    $(document).one('pagecreate', function () {
        myLine = initSpeedChart('speed-chart');

        updateStatus();
        globalTimer = setInterval(updateStatus, 3000);
    });

    count = 0;
    chartMaxXtick = 10;
    updateStatus = function () {
        $.getJSON('ajax-status.php', function (data) {
            // Add data to Graph
            var now = new Date();
            myLine.addData([(data['speed_up'] / 1024).toFixed(2), (data['speed_down'] / 1024).toFixed(2)], count);
            if (count > chartMaxXtick) myLine.removeData();

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
    }

    initSpeedChart = function (idCanvasChart) {
        var lineChartData = {
            labels: [],
            datasets: [
                {
                    label: "My First dataset",
                    fillColor: "rgba(220,220,220,0.2)",
                    strokeColor: "rgba(220,220,220,1)",
                    pointColor: "rgba(220,220,220,1)",
                    pointStrokeColor: "#fff",
                    pointHighlightFill: "#fff",
                    pointHighlightStroke: "rgba(220,220,220,1)",
                    data: []
                },
                {
                    label: "My Second dataset",
                    fillColor: "rgba(151,187,205,0.2)",
                    strokeColor: "rgba(151,187,205,1)",
                    pointColor: "rgba(151,187,205,1)",
                    pointStrokeColor: "#fff",
                    pointHighlightFill: "#fff",
                    pointHighlightStroke: "rgba(151,187,205,1)",
                    data: []
                }
            ]

        }

        var ctx = document.getElementById(idCanvasChart).getContext("2d");
        return new Chart(ctx).Line(lineChartData, {
            responsive: true
        });
    }
</script>