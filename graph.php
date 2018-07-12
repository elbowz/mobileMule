<?php amule_load_vars("stats_graph"); ?>

<div id="img-graphs" data-role="collapsible-set">
    <div data-role="collapsible" data-collapsed="false">
        <h3>Download-Speed</h3>
        <img src="amule_stats_download.png" width="100%" border="0"/>
    </div>
    <div data-role="collapsible">
        <h3>Upload-Speed</h3>
        <img src="amule_stats_upload.png" width="100%" border="0"/>
    </div>
    <div data-role="collapsible">
        <h3>Connections</h3>
        <img src="amule_stats_conncount.png" width="100%" border="0"/>
    </div>
    <p id="refresh">this graph is refreshed each <strong>xxx</strong> milliseconds (<a href="#page-mobilemule" class="hash-link">change it</a>)</p>
</div>

<script>
    $(document).one('pagecreate', function() {

        $('#refresh > strong').text(mm.settings.page.graph.refresh);

        globalTimer = setInterval(function() {

            // Force amule to update the img graphs
            $.get('graph-ajax.php', function() {

                // Reload the img
                $('#img-graphs img').each(function() {
                    this.src = this.src + '?' + (new Date()).getTime();
                })
            });
        }, mm.settings.page.graph.refresh);
    });
</script>