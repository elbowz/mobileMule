mobileMule 
==========
coded by muttley © 2018 - https://github.com/elbowz/mobileMule

![mobileMule](http://i.imgur.com/JZByzIj.jpg)

What it is
----------
mobileMule is aMule web server template/skin/theme for mobile and desktop. Based on jQuery mobile library.  
The focus is on the speed and the lightweight for reduce the footprint and increase the speed performance.  
It use a modern graphic with a responsive design template.

See [Changelog](CHANGELOG.md) for the features

Download - Latest v3.2.0b (10.08.2018)
--------------------------------------
 * [Standard Version on GoogleDrive](https://drive.google.com/folderview?id=0BzaXzhTPJkC7WFFIM09uYm4zSk0&usp=sharing#list)
 * [Donation Package on GoogleDrive](https://drive.google.com/folderview?id=0BzaXzhTPJkC7SnpOVG11OF9ITlE&usp=sharing) (do a donation for get it)

Compatibility
-------------
Tested on Android (Default browser, Chrome, Firefox, Opera, Boat browser, etc), Linux (Chrome, Firefox), Windows (Chrome, Firefox), iOS (Safari, Chrome).  
You can also add it on homescreen (Android, iOS) and run like a *native app*.  
Support for [GetEd2k](https://play.google.com/store/apps/details?id=org.anacletus.geted2k) Android app (only Donation Package).

Themes
------
mobileMule support jQueryMobile themes. You can do your own theme and share it with others. For make easy the theme creations you can use http://themeroller.jquerymobile.com/

![themes](http://i.imgur.com/AJQ2p0D.jpg)

Install
-------
1. Extract and move the entire direcotry in "/usr/share/amule/webserver/" (or /usr/local/share/amule/webserver, $HOME/.aMule/webserver/), default directory for amule web skins.
2. Now you should be have "/usr/share/amule/webserver/mobileMule"
3. If you want this skin as default, rename 'mobileMule' dir in "default" (not good idea if you have automatic update with something like repository), else continue 
4. Edit row 'Template=' in "$HOME/.aMule/amule.conf" (or if present "remote.conf") in 'Template=mobileMule'
5. restart amule (or only amuleweb)
6. *(optional)* If you like [Progressive Web Apps](https://developers.google.com/web/progressive-web-apps/) consider to install mobileMule under an *https* (eg. [letsencrypt.org](https://letsencrypt.org/)), this enable Service Worker, internal caching and *native* add to home screen.

Update
------
You can repeat point 1 of the above list ([install](#install)) or you can use the internal script ```push2server.sh```. For do that, you must have rsync on server.


> eg. ```push2server.sh user@host:/usr/share/amule/webserver/mobileMule/```

**IMPORTANT**: Remember to clean browser cache if you have some issues (eg. *chrome://settings/clearBrowserData*).

TODOs
-----
 * Better support for android web app (https://developers.google.com/web/fundamentals/app-install-banners/) 

Support me
----------
If you want help me, better support, more features, get speedy fix and so on, consider offer me a coffee or a beer through a [Donation with PayPal](https://www.paypal.com/cgi-bin/webscr?cmd=_donations&business=muttley%2ebd%40gmail%2ecom&lc=IT&item_name=mobileMule&item_number=aMule%20web%20mobile%20skin&currency_code=EUR&bn=PP%2dDonationsBF%3abtn_donate_LG%2egif%3aNonHosted)

With a free donation, you'll get the **Donation Package** that adds three panels:

* Search
* Configurations
* Add ed2k
* MobileMule (allow to change themes, refresh and other stuff)

Thanks
------

* *Emanuele Ruzza* for support, suggestions, feedback and his time for debugging app on Apple devices :)
* To all users that have donate something fot this project 