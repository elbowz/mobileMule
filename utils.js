var bytesToSize = function (bytes) {
    if (bytes == 0) return '0 Byte';
    var k = 1024;
    var sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB'];
    var i = Math.floor(Math.log(bytes) / Math.log(k));
    return (bytes / Math.pow(k, i)).toPrecision(3) + ' ' + sizes[i];
}

var isDesktop = function () {
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
            $.mobile.loading('show', {text: settings.loaderText, textVisible: settings.loaderTextVisible});
        },
        success: function (data) {
            settings.$dest.html(data).enhanceWithin().trigger('pagecreate');
            $.mobile.loading('hide');
        }
    });

    return this;
};

/* Notify Class
 Build on jQuery Mobile Popup*/
var notify = {

    initPopup: function (attrs) {

        attrs = attrs || {};

        _.defaults(attrs, {
            'class': 'notify ui-content',
            'data-role': 'popup'
        });

        if (!this.$el) {

            this.$el = $('<div/>', attrs).appendTo('body');

            this.$el.popup({
                afteropen: _.bind(function () {

                    this.opened = true;
                }, this),
                afterclose: _.bind(function () {

                    this.opened = false;
                    if (this.closedPrevPopup) this.closedPrevPopup.call();
                }, this)
            });
        } else {

            this.$el.attr(attrs);
        }

        return this.$el;
    },

    createPopup: function (html, options, attrs) {

        options = options || {};

        this.initPopup(attrs);

        _.defaults(options, {
            transition: 'slidedown',
            positionTo: '#header',
            corners: false,
            history: false,
            theme: $('div#main').attr('data-theme') || 'a'
        });

        this.$el.html(html);

        this.$el.popup('option', options);

        this.closedPrevPopup = null;

        return this.$el;
    },

    open: function (html, options, attrs) {

        options = options || {};

        var open = _.bind(function () {

            if (options.delay) {

                setTimeout(_.bind(function () {

                    this.$el.popup('close');
                }, this), options.delay);
            }

            _.omit(options, 'delay');

            this.createPopup(html, options, attrs).popup('open');
        }, this);

        if (this.opened) {

            this.closedPrevPopup = _.bind(function () {

                open();
            }, this);

            this.$el.popup('close');
        } else {

            open();
        }
    },
    message: function (html, options) {

        this.open(html, options, {class: 'notify message ui-content'});
    },
    error: function (html, options) {

        this.open('<i class="fa fa-exclamation-triangle"></i> ' + html, options, {class: 'notify error ui-content'});
    }
}

/* Cookie lib
 src:http://www.quirksmode.org/js/cookies.htm */
var createCookie = function (name, value, days) {
    if (days) {
        var date = new Date();
        date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
        var expires = "; expires=" + date.toGMTString();
    }
    else var expires = "";
    document.cookie = name + "=" + value + expires + "; path=/";
}

var readCookie = function (name) {
    var nameEQ = name + "=";
    var ca = document.cookie.split(';');
    for (var i = 0; i < ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0) == ' ') c = c.substring(1, c.length);
        if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length, c.length);
    }
    return null;
}

var eraseCookie = function (name) {
    createCookie(name, "", -1);
}