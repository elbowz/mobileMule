/* GLOBAL CONFIGURATION */

var mm = mm || {};

// IMPORTANT: Update also main.js#version check, latestVersion.js, main.php
mm.version = '2.2.0b';

mm.settings = mm.settings || {};

// Default page to view (see also index.html)
mm.settings.mainHash = '#page-status'

mm.settings.page = mm.settings.page || {};

mm.settings.page.downloads = mm.settings.page.downloads || {};
mm.settings.page.downloads.refreshList = _.isUndefined(mm.settings.page.downloads.refreshList) ? 3000 : mm.settings.page.downloads.refreshList;


/* JQUERY MOBILE EVENTS */

$(document).one('mobileinit', function () {

    // Disable send form through AJAX (default behavior)
    $.extend($.mobile, {
        ajaxEnabled: false
    });
});


var globalTimer = 0;

$(document).one('pagecreate', function () {
    // Stop timer (es. status page)
    if (globalTimer) {
        clearInterval(globalTimer);
        globalTimer = 0;
    }

    // Force JQuery mobile to set hash in the url (ancor)
    $(document).on('vclick', 'a.hash-link', function (event) {
        event.preventDefault();

        location.hash = $(this).attr('href');

        if (!isDesktop()) {
            $panel.panel('close');
        }
    });

    // Update page (not menu) with link.href through ajax
    $(document).on('vclick', 'a.ajax-link', function (event) {
        event.preventDefault();

        var loaderText = $(this).data('text');

        var options = {};
        if (loaderText) {
            options = {loaderText: loaderText, loaderTextVisible: true};
        }

        $(this).jQMobileAjaxLink(options);
    });

    $('#pnMenuLogout').on('vclick', function (event) {
        event.preventDefault();

        eraseCookie('auth');
        window.location = $(event.currentTarget).attr('href');
    });

    $('#btScrollUp').on('vclick', function (event) {
        event.preventDefault();

        $('body').animate({scrollTop: '0px'});
    });

    // Support for open panel menu with swipe
    $(document).on('swipeleft swiperight', 'div[data-role="page"]', function (event) {
        // We check if there is no open panel on the page because otherwise
        // a swipe to close the left panel would also open the right panel (and v.v.).
        // We do this by checking the data that the framework stores on the page element (panel: open).
        if ($('.ui-page-active').jqmData('panel') !== 'open') {
            if (event.type === 'swiperight') {
                $panel.panel('open');
            }
        }
    });

    // Preventing Links In Standalone iPhone Applications From Opening In Mobile Safari
    /*if (('standalone' in window.navigator) && window.navigator.standalone) {
        $('a').bind('vclick', function (event) {

                var newLocation = $(event.currentTarget).attr("href");

                if (newLocation != undefined && newLocation.substr(0, 1) != '#') {
                    event.preventDefault();

                    window.location = newLocation;
                }
            }
        );
    }*/
});


$(document).one('pagebeforeshow', function () {

    $panel = $('#menu-panel');
});


$(document).one('pageshow', function () {

    // Call the first hashchange
    $(window).hashchange();

    $panel.panel().enhanceWithin();

    if (isDesktop()) {
        $panel.panel('open');
    }

    // VERSION CHECK

    $.ajax({
        url: "https://rawgit.com/elbowz/mobileMule/master/latestVersion.js",
        dataType: 'script',
        beforeSend: function () {
            $.mobile.loading('show');
        },
        success: function () {
            $.mobile.loading('hide');
            if (latestVersion != mm.version) {
                notify.message('<a href="https://github.com/elbowz/mobileMule">New version (v' + latestVersion + ') is available!</a>');
            }
        },
        error: function () {
            $.mobile.loading('hide');
            notify.error('Something go wrong during the new version check');
        }
    });

    addToHomescreen({ maxDisplayCount: 4 });
});


var oldHash;

$(window).on('hashchange', function () {

    location.hash = location.hash || mm.settings.mainHash
    var hash = location.hash;

    if (oldHash && hash.search(oldHash) == 0) return;

    // Check if a ajax call for change the page
    if (hash.search('#page-') == 0) {
        var file = hash.substr(6) + '.php'

        $.mobile.loading('show');

        if (!mm.donate) {
            if (_.contains(['#page-search', '#page-config', '#page-mobilemule', '#page-footer'], hash)) {
                hash = '#page-donate';
                file = hash.substr(6) + '.php'
            }
        }

        if (hash == '#page-donate') {
            $('#header > h1').html('Donation Pack. <i class="fa fa-thumbs-o-up fa-fw"></i> ');

        } else {
            // Manipulate menu link text
            var head = $panel.find('a[href="' + hash + '"]').clone();

            if (head.length) {
                head.find('i').removeClass('pull-right');

                // Set page header
                $('#header > h1').html(head[0].innerHTML);
            }
        }

        // Set page content
        $('#container').load(file, function () {
            $('#main').trigger('pagebeforecreate').enhanceWithin().trigger('pagecreate');
            $.mobile.loading('hide');
        });
    }

    oldHash = hash;
});
