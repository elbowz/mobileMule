<form action="downloads-ajax.php" method="post" name="mainform">
<input type="hidden" name="command">
<input type="hidden" name="download_sort_reverse" value=0>

<div data-role="navbar">
    <ul>
        <li><a href="javascript:formCommandSubmit('pause');"><i class="fa fa-pause"></i><span>Pause</span></a></li>
        <li><a href="javascript:formCommandSubmit('resume');"><i class="fa fa-play"></i><span>Resume</span></a></li>
        <li><a href="javascript:formCommandSubmit('prioup');"><i class="fa fa-chevron-up"></i><span>Prio. up</span></a></li>
        <li><a href="javascript:formCommandSubmit('priodown');"><i class="fa fa-chevron-down"></i><span>Prio. down</span></a></li>
        <li><a href="javascript:formCommandSubmit('cancel');"><i class="fa fa-trash"></i><span>Cancel</span></a></li>
    </ul>
</div>

<br/>

<div id="list-downloads" class="ui-content">
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
    <li data-filtertext="{{short_name}}">
        <a data-hash="{{hash}}" class="file-check">
            <h3>{{short_name}}</h3>
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
            <span class="ui-li-count">{{percentual_progress}}%</span>
        </a>
        <a id="{{hash}}" data-hash="{{hash}}" class="file-check {{fileCheckClass}}">Select file</a>
    </li>
    {{/each}}
</ul>
</div>

<script>
    $(document).one('pagecreate', function () {

        $mainForm = $('form[name="mainform"]');

        // AUTO REFRESH DOWNLOADS LIST
        submitFormAndUpdate();

        globalTimer = setInterval(function () {

            submitFormAndUpdate();
        }, 3000);

        // EVENT HANDLING

        $('#list-downloads').on('vclick', '.file-check', function (event) {
            event.stopPropagation();
            console.log('ciao')
            var checkboxHashId = $(this).attr('data-hash');
            var $inputHidden = $('input[name="'+ checkboxHashId +'"]', $mainForm);

            if(!$inputHidden.length) {

                $('<input>').attr({
                    type: 'hidden',
                    name: checkboxHashId,
                    value: 'on'
                }).appendTo($mainForm);
            } else {

                $inputHidden.remove();
            }

            $('#' + checkboxHashId).toggleClass('ui-btn-active');
        });

        $('select[name="status"], select[name="category"]').change(function () {

            $('input[name="command"]').attr('value', 'filter');
            submitFormAndUpdate();
        });

        $('select[name="sort"]').change(function () {

            submitFormAndUpdate();
        });

        $('#sort_reverse').on('vclick', function () {
            event.preventDefault();

            var $inputSortRevrse = $('input[name="download_sort_reverse"]');
            var value = $inputSortRevrse.val();
            $inputSortRevrse.val(value == '1' ? '0' : '1');

            submitFormAndUpdate();

            $('#sort_reverse').attr({'data-icon': value == '1' ? 'arrow-u' : 'arrow-d'})
                .toggleClass('ui-icon-arrow-d ui-icon-arrow-u')
                .text(value == '1' ? 'Descendent' : 'Ascendent');
        });
    });

    var formCommandSubmit = function (command) {
        if (command == "cancel") {
            var res = confirm("Delete selected files ?")
            if (res == false) {
                return;
            }
        }
        if (command != "filter") {
            <?php
                if ($_SESSION["guest_login"] != 0) {
                        echo 'alert("You logged in as guest - commands are disabled");';
                        echo "return;";
                }
            ?>
        }

        $('input[name="command"]').attr('value', command);

        submitFormAndUpdate();
    }

    var submitFormAndUpdate = function () {

        this.listDowndload = this.listDowndload || $("#list-downloads-tpl").html();
        this.listDowndloadHb = this.listDowndloadHb || Handlebars.compile(listDowndload);

        $($mainForm).ajaxForm(_.bind(function (data) {

            this.listDowndloadHb(data.downloads)
            $('#list-downloads').html(this.listDowndloadHb(data.downloads));
        }, this));
    }

    Handlebars.registerHelper('fileCheckClass', function() {
        return $('input[name="'+ this.hash +'"]', $mainForm).length ? ' ui-btn-active' : '';
    });
</script>

