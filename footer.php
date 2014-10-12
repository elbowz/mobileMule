<?php
$link = $HTTP_GET_VARS['ed2klink'];
if ($link != '') {
    $target_cat = $HTTP_GET_VARS['selectcat'];
    $target_cat_idx = 0;

    $cats = amule_get_categories();

    foreach ($cats as $i => $c) {
        if ($target_cat == $c) $target_cat_idx = $i;
    }

    if (strlen($link) > 0) {

        $nLink = 0;
        $links = split('ed2k://', $link);
        foreach ($links as $key => $linkn) {

            // Jump the first (nothing here)
            if (!$key) continue;
            $response = amule_do_ed2k_download_cmd('ed2k://' . $linkn, $target_cat_idx);

            if(!$response) $nlink++;
        }
        echo '<h2 style="text-align: center;"><i class="fa fa-child"></i> Added ', $nlink, ' <a href="#page-downloads" class="hash-link">downloads</a>!</h2>';
    }

}
?>

<form action="footer.php" method="post" name="formlink" data-ajax="false">
    <label for="pass">Ed2k link:</label>
    <textarea id="ed2klink" name="ed2klink" rows="6"></textarea>

    <label for="selectcat" class="select">Category:</label>
    <select name="selectcat" id="selectcat" data-native-menu="false">
        <?php
        $cats = amule_get_categories();

        foreach ($cats as $c) {
            echo '<option>', $c, '</option>';
        }
        ?>
    </select>
    <br/>
    <input type="submit" name="Submit" value="Download link"/>
</form>

<script>
    $(document).one('pagecreate', function () {

        $('form[name="formlink"]').on('submit', function (event) {
            event.preventDefault();

            $(this).jQMobileAjaxSubmit();
        });
    });
</script>
