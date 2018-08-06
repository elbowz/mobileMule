self.importScripts('latestVersion.js');

// Set cache name to mobileMule latestVersion
var CACHE_NAME = latestVersion;

// Resource to cache
// All others (requested resources) will be cached on first request (see 'fetch'
var urlsToCache = [
    '//ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js',
    '//ajax.googleapis.com/ajax/libs/jquerymobile/1.4.5/jquery.mobile.min.js',
    '//ajax.googleapis.com/ajax/libs/jquerymobile/1.4.5/jquery.mobile.min.css',
    '//ajax.googleapis.com/ajax/libs/jquerymobile/1.4.5/images/ajax-loader.gif',
    '//netdna.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css',
    '//netdna.bootstrapcdn.com/font-awesome/4.3.0/fonts/fontawesome-webfont.woff2?v=4.3.0',
    './fallback.html',
    './fallback-icon.png'
];

// TODO: Cache also downloads and use form method GET (not cache with query string)

var urlsToNoCahe = [
    '/collapsible-stats.php',
    '/downloads.php',
    '/downloads-ajax.php',
    '/graph.php',
    '/graph-ajax.php',
    '/listed-stats.php',
    '/log.php',
    '/servers.php',
    '/status.php',
    '/status-ajax.php',
    '/uploads.php',
    '/amule_stats_download.png',
    '/amule_stats_upload.png',
    '/amule_stats_conncount.png',
    /* Donation package resources */
    '/config.php',
    '/footer.php',
    '/search-ajax.php',
    '/search.php'
];

// Add resource to cache
self.addEventListener('install', function(event) {

    event.waitUntil(
        caches.open(CACHE_NAME)
            .then(function(cache) {

                return cache.addAll(urlsToCache.map(function(url) {

                    return new Request(url, { credentials: 'same-origin' });
                }));
            })
    );
});

// Delete old cache when rename CACHE_NAME
self.addEventListener('activate', function(event) {

    var cacheWhitelist = [CACHE_NAME];

    event.waitUntil(
        caches.keys().then(function(keyList) {

            return Promise.all(keyList.map(function(key) {

                if (!cacheWhitelist.includes(key)) {
                    return caches.delete(key);
                }
            }));
        })
    );
});

// Respond with cached and not cached resource
self.addEventListener('fetch', function(event) {


    // TODO: ignore query string:
    // caches.match(event.request, {
    //       **ignoreSearch: true**
    //     })
    event.respondWith(
        caches.match(event.request).then(function(response) {

            // Cached
            if (response !== undefined) {

                return response;
            }

            // Not cached yet
            return fetch(event.request)
                .then(function(response) {

                        // Bad response => Avoid caching
                        if (!response || response.status !== 200 || response.type !== 'basic') {

                            return response;
                        }

                        // Black listed resource => Avoid caching
                        var resourceName = response.url.split('?')[0].substring(response.url.lastIndexOf('/'));
                        if (urlsToNoCahe.includes(resourceName)) {

                            return response;
                        }

                        // Cache it
                        return caches.open(CACHE_NAME)
                            .then(function(cache) {

                                // ...and respond
                                cache.put(event.request, response.clone());
                                return response;
                            });
                    }
                ).catch(function() {

                    // No cache and no internet connection
                    return caches.match('./fallback.html');
                });

        })
    );
});
