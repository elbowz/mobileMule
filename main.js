var mainHash = '#page-status'

// Disable send form through AJAX (default behavior)
$(document).on('mobileinit', function () {
    $.extend($.mobile, {
        ajaxEnabled: false
    });

});

var oldHash;
$(window).on('hashchange', function () {
    var hash = location.hash || mainHash;
    if (oldHash && hash.search(oldHash) == 0) return;

    // Check if a ajax call for change the page
    if (hash.search('#page-') == 0) {
        var file = hash.substr(6) + '.php'

        $.mobile.loading('show');

        if (hash == '#page-donate') {
            $('#header > h1').html('Donation Package <i class="fa fa-thumbs-o-up fa-fw"></i> ');

        } else {
            // Manipulate menu link text
            var head = $('#menu-panel').find('a[href="' + hash + '"]').clone();

            if (head.length) {
                head.find('i').removeClass('pull-right');

                // Set page header
                $('#header > h1').html(head[0].innerHTML);
            }
        }

        // Set page content
        $('#container').load(file, function () {
            $(this).enhanceWithin().trigger('pagecreate');
            $.mobile.loading('hide');
        });
    }

    oldHash = hash;
});

var globalTimer = 0;

$(document).on('pagecreate', function () {

    // Stop timer (es. status page)
    if (globalTimer) {
        clearInterval(globalTimer);
        globalTimer = 0;
    }

    // Force JQuery mobile to set hash in the url (ancor)
    $('a.hash-link').on('vclick', function (event) {
        event.preventDefault();

        location.hash = $(this).attr('href');

        if (!isDesktop()) {
            $panel.panel('close');
        }
    });

    // Update page (not menu) with link.href through ajax
    $('a.ajax-link').on('vclick', function (event) {
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

    $('#btScrollUp').on('vclick', function (event) {
        event.preventDefault();

        $('body').animate({scrollTop: '0px'});
    });

    // Support for open panel menu with swipe
    $(document).on('swipeleft swiperight', 'div[data-role="page"]', function (event) {
        // We check if there is no open panel on the page because otherwise
        // a swipe to close the left panel would also open the right panel (and v.v.).
        // We do this by checking the data that the framework stores on the page element (panel: open).
        if ($(".ui-page-active").jqmData("panel") !== "open") {
            if (event.type === "swiperight") {
                $("#menu-panel").panel("open");
            }
        }
    });

    // Preventing Links In Standalone iPhone Applications From Opening In Mobile Safari
//    if (('standalone' in window.navigator) && window.navigator.standalone) {
//        $('a').bind('vclick', function (event) {
//
//                var newLocation = $(event.currentTarget).attr("href");
//
//                if (newLocation != undefined && newLocation.substr(0, 1) != '#') {
//                    event.preventDefault();
//
//                    window.location = newLocation;
//                }
//            }
//        );
//    }
});

$(document).on('pageshow', function () {
    // Call the first hashchange
    $(window).hashchange();

    $panel = $('#menu-panel');

    $panel.panel().enhanceWithin();

    if (isDesktop()) {
        $panel.panel('open');
    }
});

bytesToSize = function (bytes) {
    if (bytes == 0) return '0 Byte';
    var k = 1024;
    var sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB'];
    var i = Math.floor(Math.log(bytes) / Math.log(k));
    return (bytes / Math.pow(k, i)).toPrecision(3) + ' ' + sizes[i];
}

isDesktop = function () {
    var width = $(window).width();
    if (width > 768) {
        return true;
    }
    return false;
}

// Update page (not menu) with form submit through ajax
$.fn.jQMobileAjaxSubmit = function (options) {
    var settings = $.extend({
        // These are the defaults.
        method: this.attr('method'),
        action: this.attr('action'),
        dest: 'div[data-role="content"]'
    }, options);

    settings.$dest = $(settings.dest);

    $.ajax({
        type: settings.method,
        url: settings.action,
        data: this.serialize(),
        beforeSend: function () {
            $.mobile.loading('show');
        },
        success: function (data) {
            settings.$dest.html(data).enhanceWithin().trigger('pagecreate');
            $.mobile.loading('hide');
        }
    });

    return this;
};

// Update page (not menu) with link.href through ajax
$.fn.jQMobileAjaxLink = function (options) {
    var settings = $.extend({
        // These are the defaults.
        type: 'get',
        href: this.attr('href'),
        dest: 'div[data-role="content"]',
        loaderText: '',
        loaderTextVisible: false
    }, options);

    settings.$dest = $(settings.dest);

    $.ajax({
        type: settings.type,
        url: settings.href,
        beforeSend: function () {
            $.mobile.loading('show', { text: settings.loaderText, textVisible: settings.loaderTextVisible });
        },
        success: function (data) {
            settings.$dest.html(data).enhanceWithin().trigger('pagecreate');
            $.mobile.loading('hide');
        }
    });

    return this;
};

/* Cookie lib
 src:http://www.quirksmode.org/js/cookies.htm */
function createCookie(name, value, days) {
    if (days) {
        var date = new Date();
        date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
        var expires = "; expires=" + date.toGMTString();
    }
    else var expires = "";
    document.cookie = name + "=" + value + expires + "; path=/";
}

function readCookie(name) {
    var nameEQ = name + "=";
    var ca = document.cookie.split(';');
    for (var i = 0; i < ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0) == ' ') c = c.substring(1, c.length);
        if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length, c.length);
    }
    return null;
}

function eraseCookie(name) {
    createCookie(name, "", -1);
}
