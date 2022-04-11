<input id="filter-finished-input" data-type="search" type="search" autocomplete="off">
<br/>
<div id="list-finished">
    <!-- downloads placeholder -->
</div>
<br/>
<a href="#" class="ui-btn ui-corner-all clear-all"><i class="fa fa-trash fa-fw" aria-hidden="true"></i> Clear all</a>

<div id="list-finished-tpl" class="handlebars-template">
    <ul data-role="listview" data-divider-theme="d">
        <li data-role="list-divider">Finished downloads<span class="ui-li-count">{{count}}</span></li>
        {{#each list}}
        <li data-filtertext="{{name}}">
            <h3 data-hash="{{hash}}">{{name}}</h3>
            <p>{{size}}</p>
        </li>
        {{/each}}
        <li><p><strong>Important:</strong> the listed files was present last time you connected in downloads and no more there</p></li>
    </ul>
</div>

<script>
    $(document).one('pagecreate', function() {

        var $listFinished = $('#list-finished');
        var listDownloadHb = Handlebars.compile($("#list-finished-tpl").html());
        var $filterName = $('#filter-finished-input');

        getList();

        $('.clear-all').on('vclick', function(event) {

            event.preventDefault();

            idbDownloads.clear();
            idbDownloads.addCurrentDownloads();

            var htmlListGenerated = listDownloadHb({ list: [], count: 0 });
            $listFinished.html(htmlListGenerated);
        });

        $filterName.on('input', function () {
            getList();
        });

        function getList() {
            $.getJSON('downloads-ajax.php', function (data) {

                var finished = { list: [], count: 0 };

                idbDownloads.getAll().then(function (downloadsDb) {

                    data.downloads.list.forEach(function (download) {
                        downloadsDb = downloadsDb.filter(function (file) {
                            return download.hash != file.hash;
                        });
                    });

                    finished.list = downloadsDb;
                    finished.count = finished.list.length;

                    filterName = $filterName.val().toUpperCase()
                    finished.list = _.filter(finished.list, function(file) {
                        return file.name.toUpperCase().includes(filterName);
                    });

                    var htmlListGenerated = listDownloadHb(finished);
                    $listFinished.html(htmlListGenerated);
                });
            });
        }
    });
</script>