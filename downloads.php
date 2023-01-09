<form action="downloads-ajax.php" method="post" name="mainform">
<input type="hidden" name="command">
<input type="hidden" name="download_sort_reverse" value=0>

<div class="command" data-role="navbar">
    <ul>
        <li><a data-command="pause" data-notify="Paused"><i class="fa fa-pause"></i><span>Pause</span></a></li>
        <li><a data-command="resume" data-notify="Resumed"><i class="fa fa-play"></i><span>Resume</span></a></li>
        <li><a data-command="prioup" data-notify="Priority Up"><i class="fa fa-chevron-up"></i><span>Prio. up</span></a></li>
        <li><a data-command="priodown" data-notify="Priority Down"><i class="fa fa-chevron-down"></i><span>Prio. down</span></a></li>
        <li><a data-command="cancel" data-notify="File deleted"><i class="fa fa-trash"></i><span>Cancel</span></a></li>
    </ul>
</div>

<input id="filter-download-input" data-type="search" type="search" autocomplete="off">

<br/>

<div id="list-downloads">
    <!-- downloads placeholder -->
</div>

<br/>

<div class="ui-grid-a ui-responsive">
    <div class="ui-block-a">
        <fieldset data-role="controlgroup">
            <legend>Filter (status / category):</legend>
            <?php
            $all_status = array("all", "Waiting", "Paused", "Downloading");
            if ($HTTP_GET_VARS["command"] == "filter") {
                $_SESSION["filter_status"] = $HTTP_GET_VARS["status"];
                $_SESSION["filter_cat"] = $HTTP_GET_VARS["category"];
            }
            if ($_SESSION["filter_status"] == '') $_SESSION["filter_status"] = 'all';
            if ($_SESSION["filter_cat"] == '') $_SESSION["filter_cat"] = 'all';

            echo '<select name="status" id="status" data-native-menu="false"> ';
            foreach ($all_status as $s) {
                echo '<option value="' . $s . '"', (($s == $_SESSION["filter_status"]) ? ' selected>' : '>'), $s, '</option>';
            }
            echo '</select>';
            //var_dump($_SESSION["filter_cat"]);
            echo '<select name="category" id="category" data-native-menu="false">';
            $cats = amule_get_categories();
            foreach ($cats as $c) {
                echo '<option value="' . $s . '"', (($c == $_SESSION["filter_cat"]) ? ' selected>' : '>'), $c, '</option>';
            }
            echo '</select>';
            ?>
        </fieldset>

        <?php
        if ($_SESSION["guest_login"] != 0) {
            echo "<b>&nbsp;You logged in as guest - commands are disabled</b>";
        }
        ?>
    </div>
    <div class="ui-block-b">
        <fieldset data-role="controlgroup">
            <legend>Sort:</legend>
            <select name="sort" id="sort" data-native-menu="false">
                <option value="name">File name</option>
                <option value="size">Size</option>
                <option value="size_done">Completed</option>
                <option value="speed">Download speed</option>
                <option value="progress">Progress</option>
                <option value="srccount">Sources</option>
                <option value="status">Status</option>
                <option value="prio">Priority</option>
                <option value="last_seen_complete">Last seen completed</option>
            </select>
            <a id="sort_reverse" href="#" data-value=0 data-role="button" data-theme="d" data-icon="arrow-d" data-iconpos="right" style="padding-right: 38px;">Descendent</a>
        </fieldset>
    </div>
</div>
</form>

<div id="list-downloads-tpl" class="handlebars-template">
<ul data-role="listview" data-divider-theme="d" data-split-icon="check">
    <li data-role="list-divider">Downloads<span class="ui-li-count">{{count}}</span></li>
    {{#each list}}
    <li data-filtertext="{{name}}">
        <a data-hash="{{hash}}" class="file-check {{fileCheckClass 'selected'}}">
            <h3>{{name}}</h3>
            <p>{{#if speed}}<strong>{{speed}}/s</strong> - {{/if}}{{status}} - {{prio}}</p>
            <p>
                {{#if src_count_connected}}{{src_count_connected}} / {{/if}}
                {{src_count}} ( {{src_count_xfer}} )
                {{#if src_count_a4af}} + {{src_count_a4af}}{{/if}} - {{last_seen_complete}}
            </p>
            <p>
            <div class="ui-li-desc bar">
                <div class="ui-li-desc completed-bar {{status_complted_class}}" style="width: {{percentual_progress}}%;">{{size_done}} / {{size}}</div>
            </div>
            </p>
            <div class="file-command">
                <button data-command="resume" class="ui-btn ui-btn-inline"><i class="fa fa-play"></i></button>
                <button data-command="pause" class="ui-btn ui-btn-inline"><i class="fa fa-pause"></i></button>
                <button data-command="prioup" class="ui-btn ui-btn-inline"><i class="fa fa-chevron-up"></i></button>
                <button data-command="priodown" class="ui-btn ui-btn-inline"><i class="fa fa-chevron-down"></i></button>
                <button data-command="cancel" class="ui-btn ui-btn-inline m-1"><i class="fa fa-trash"></i></button>
            </div>
            <span class="ui-li-count">{{percentual_progress}}%</span>
        </a>
        <a id="{{hash}}" data-hash="{{hash}}" class="file-check {{fileCheckClass 'ui-btn-active'}}">Select file</a>
    </li>
    {{/each}}
    <li><p>this info is refreshed each <strong>{{refreshList}}</strong> seconds (<a href="#page-mobilemule" class="hash-link">change it</a>)</p></li>
</ul>
</div>

<script>
    $(document).one('pagecreate', function() {

        var $mainForm = $('form[name="mainform"]');
        var $inputCommand = $('input[name="command"]');
        var $listDownloads = $('#list-downloads');
        var $filterName = $('#filter-download-input');
        var $filterStatus = $('select[name="status"]');
        var $filterCategory = $('select[name="category"]');
        var $sortOn = $('select[name="sort"]');
        var $sortReverse = $('#sort_reverse');
        var $inputSortRevrse = $('input[name="download_sort_reverse"]');

        // AUTO REFRESH DOWNLOADS LIST
        $filterStatus.val(mm.settings.page.downloads.filterStatus).change();
        $filterCategory.val(mm.settings.page.downloads.filterCategory).change();
        $sortOn.val(mm.settings.page.downloads.sortOn).change();

        submitFormAndUpdate();

        if (!!mm.settings.page.downloads.refreshList) {
            globalTimer = setInterval(submitFormAndUpdate, mm.settings.page.downloads.refreshList);
        }

        // EVENT HANDLING

        $('.command a').on('vclick', function() {

            event.stopPropagation();

            var notifyMsg = $(this).data('notify');
            var command = $(this).data('command');

            if (formCommandSubmit(command)) {
                notify.message(notifyMsg);
            }
        });

        $filterName.on('input', function () {
            submitFormAndUpdate();
        });

        $listDownloads.on('vclick', '.file-command button', function(event) {

            event.stopPropagation();

            var command = $(this).data('command');
            var hash = $(this).closest('a[data-hash]').data('hash');

            if (command == 'cancel') {
                var res = confirm("Delete selected files ?")
                if (res == false) {
                    return false;
                }
                idbDownloads.delete(hash)
            }

            $.ajax({
                type: 'GET',
                url: 'downloads-ajax.php',
                data: { 'command': command, [hash]: 'on'},
                success: function () {
                    submitFormAndUpdate();
                }
            });
        });

        $listDownloads.on('vclick', '.file-check', function(event) {

            event.stopPropagation();

            var checkboxHashId = $(this).attr('data-hash');
            var $inputHidden = $('input[name="' + checkboxHashId + '"]', $mainForm);

            if (!$inputHidden.length) {

                $('<input>').attr({
                    type: 'hidden',
                    name: checkboxHashId,
                    value: 'on'
                }).appendTo($mainForm);

            } else {
                $inputHidden.remove();
            }

            $(this).toggleClass('selected');
            $('#' + checkboxHashId).toggleClass('ui-btn-active');
        });

        $filterStatus.change(onFilterChange);
        $filterCategory.change(onFilterChange);

        function onFilterChange() {
            newValue = $(this).val();
            mm.settings.page.downloads.filterStatus = newValue;
            mm.localStorage.set('page-downloads-filterStatus', newValue);

            $inputCommand.attr('value', 'filter');
            submitFormAndUpdate();
        }

        $sortOn.change(function() {
            newValue = $(this).val();
            mm.settings.page.downloads.sortOn = newValue;
            mm.localStorage.set('page-downloads-sortOn', newValue);

            submitFormAndUpdate();
        });

        $sortReverse.on('vclick', function(event) {
            event.preventDefault();
            var value = $inputSortRevrse.val();
            var newValue = value == '1' ? '0' : '1'
            $inputSortRevrse.val(newValue);

            mm.settings.page.downloads.sortOrder = newValue;
            mm.localStorage.set('page-downloads-sortOrder', newValue);

            submitFormAndUpdate();

            $(this).attr({ 'data-icon': value == '1' ? 'arrow-u' : 'arrow-d' })
                .toggleClass('ui-icon-arrow-d ui-icon-arrow-u')
                .text(value == '1' ? 'Descendent' : 'Ascendent');
        });

        if ($inputSortRevrse.val() != mm.settings.page.downloads.sortOrder) {
            $sortReverse.trigger('vclick');
        }

        // UTILS FUNCTIONS

        function formCommandSubmit(command) {

            if (command == "cancel") {
                var res = confirm("Delete selected files ?")
                if (res == false) {
                    return false;
                }

                $('input[type="hidden"][value="on"]').each(function() {
                    idbDownloads.delete($(this).attr('name'))
                });
            }
            if (command != "filter") {
                <?php
                    if ($_SESSION["guest_login"] != 0) {
                            echo 'alert("You logged in as guest - commands are disabled");';
                            echo "return;";
                    }
                ?>
            }

            $inputCommand.attr('value', command);
            submitFormAndUpdate();
            $inputCommand.removeAttr('value');

            return true;
        };

        function submitFormAndUpdate() {

            this.listDowndload = this.listDowndload || $("#list-downloads-tpl").html();
            this.listDownloadHb = this.listDownloadHb || Handlebars.compile(this.listDowndload);

            $mainForm.ajaxForm(_.bind(function(data) {
                filterName = $filterName.val().toUpperCase()
                data.downloads.list = _.filter(data.downloads.list, function(file) {
                    return file.name.toUpperCase().includes(filterName);
                });

                data.downloads.refreshList = mm.settings.page.downloads.refreshList / 1000;
                var htmlListGenerated = this.listDownloadHb(data.downloads);
                $listDownloads.html(htmlListGenerated);
            }, this));
        };

        Handlebars.registerHelper('fileCheckClass', function(cls_active) {
            cls_active = typeof cls_active == 'object' ? 'ui-btn-active' : cls_active
            return $('input[name="' + this.hash + '"]', $mainForm).length ? cls_active : '';
        });
    });
</script>

