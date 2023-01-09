/* GLOBAL CONFIGURATION */

var mm = mm || {};

// IMPORTANT: Update also main.js#version check, latestVersion.js, main.php
mm.version = '3.6.6b';

mm.localStorage = mm.localStorage || new mMLocalStorage();

mm.settings = mm.settings || {};

// Default page to view
mm.settings.mainHash = mm.settings.mainHash || '#page-status';
mm.settings.checkLatestVersion = _.isUndefined(mm.settings.checkLatestVersion) ? true : mm.settings.checkLatestVersion;
mm.settings.notifyDelay = _.isUndefined(mm.settings.notifyDelay) ? 6000 : mm.settings.notifyDelay;

mm.settings.turtleMode = mm.settings.turtleMode || {};
mm.settings.turtleMode.maxDownLimit = _.isUndefined(mm.settings.turtleMode.maxDownLimit) ? 10 : mm.settings.turtleMode.maxDownLimit;
mm.settings.turtleMode.maxUpLimit = _.isUndefined(mm.settings.turtleMode.maxUpLimit) ? 4 : mm.settings.turtleMode.maxUpLimit;

mm.settings.page = mm.settings.page || {};

mm.settings.page.status = mm.settings.page.status || {};
mm.settings.page.status.refresh = _.isUndefined(mm.settings.page.status.refresh) ? 3000 : mm.settings.page.status.refresh;
mm.settings.page.status.tickChart = _.isUndefined(mm.settings.page.status.tickChart) ? 10 : mm.settings.page.status.tickChart;

mm.settings.page.downloads = mm.settings.page.downloads || {};
mm.settings.page.downloads.refreshList = _.isUndefined(mm.settings.page.downloads.refreshList) ? 3000 : mm.settings.page.downloads.refreshList;
mm.settings.page.downloads.filterStatus = mm.localStorage.get('page-downloads-filterStatus', 'all');
mm.settings.page.downloads.filterCategory = mm.localStorage.get('page-downloads-filterCategory', 'all');
mm.settings.page.downloads.sortOn = mm.localStorage.get('page-downloads-sortOn', 'name');
mm.settings.page.downloads.sortOrder = mm.localStorage.get('page-downloads-sortOrder', '0');

mm.settings.page.graph = mm.settings.page.graph || {};
mm.settings.page.graph.refresh = _.isUndefined(mm.settings.page.graph.refresh) ? 3000 : mm.settings.page.graph.refresh;

/* INIT SERVICE WORKER */

if ('serviceWorker' in navigator) {

    window.addEventListener('load', function() {

        navigator.serviceWorker.register('./service-worker.js', { scope: './' }).then(function(reg) {

            if (reg.installing) {
                console.log('[service worker] Installing...');
            } else if (reg.waiting) {
                console.log('[service worker] Waiting...');
            } else if (reg.active) {
                console.log('[service worker] Active');
            }

        }).catch(function(error) {

            console.log('[service worker] FAILED registration', error);
        });

        window.addEventListener('beforeinstallprompt', event => {

            event.preventDefault();
            //event.prompt();
        });
    });
}

/* JQUERY MOBILE EVENTS */

$(document).one('mobileinit', function() {

    // Disable send form through AJAX (default behavior)
    $.extend($.mobile, {
        ajaxEnabled: false
    });
});

$(document).one('pagebeforecreate', function() {

    $panel = $('#menu-panel');

    idbDownloads.addCurrentDownloads();
});

var globalTimer = 0;

$(document).on('pagebeforecreate', function() {

    // Stop timer (es. status page)
    if (globalTimer) {
        clearInterval(globalTimer);
        globalTimer = 0;
    }
});

$(document).one('pagecreate', function() {

    // Force JQuery mobile to set hash in the url (ancor)
    $(document).on('vclick', 'a.hash-link', function(event) {
        event.preventDefault();

        location.hash = $(this).attr('href');

        if (!isDesktop()) {
            $panel.panel('close');
        }
    });

    // Update page (not menu) with link.href through ajax
    $(document).on('vclick', 'a.ajax-link', function(event) {
        event.preventDefault();

        var loaderText = $(this).data('text');

        var options = {};
        if (loaderText) {
            options = { loaderText: loaderText, loaderTextVisible: true };
        }

        $(this).jQMobileAjaxLink(options);
    });

    $('#pnMenuLogout').on('vclick', function(event) {
        event.preventDefault();

        eraseCookie('auth');
        window.location = $(event.currentTarget).attr('href');
    });

    $('#btScrollUp').on('vclick', function(event) {
        event.preventDefault();

        $('html, body').animate({ scrollTop: '0px' });
    });

    // Support for open panel menu with swipe
    $(document).on('swipeleft swiperight', 'div[data-role="page"]', function(event) {
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

    // Call the first hashchange
    $(window).hashchange();

    $panel.panel().enhanceWithin();

    if (isDesktop()) {
        $panel.panel('open');
    }

    // VERSION CHECK

    var checkLatestVersion = () => setTimeout(function() {

        $.ajax({
            url: "https://rawgit.com/elbowz/mobileMule/master/latestVersion.js",
            dataType: 'script',
            success: function() {
                if (latestVersion != mm.version) {
                    notify.message('<a href="https://github.com/elbowz/mobileMule"> ' + latestVersion + ' release is available!</a>');
                }
            },
            error: function() {
                notify.error('Something go wrong during the new version check');
            }
        });
    }, 1000);

    if (mm.settings.checkLatestVersion) checkLatestVersion();

    if (location.protocol != 'https:') addToHomescreen({ maxDisplayCount: 4 });
});


var oldHash;

$(window).on('hashchange', function() {

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
        $('#container').load(file, function() {
            $('#main').trigger('pagebeforecreate').enhanceWithin().trigger('pagecreate');
            $.mobile.loading('hide');
        });
    }

    oldHash = hash;

    // Google Analytics
    ga('send', 'pageview', { 'page': location.pathname + location.search + location.hash });

});
