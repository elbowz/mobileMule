mobileMule 
==========
coded by muttley © 2014 - https://github.com/elbowz/mobileMule

![Alt text](http://s13.postimg.org/iahsdd8br/responsive_showcase_mockup.jpg)

What it is
----------
mobileMule is aMule web server template/skin for mobile and desktop. Based on jQuery mobile library.  
The focus is on the speed and the lightweight for reduce the footprint and increase the speed perfromance.  
It use a modern graphic with a responsive design template.

See [Changelog](CHANGELOG.md) for the features

Download - Latest v2.0.0b (16.10.2014)
--------------------------------------
 * [Standard Version on GoogleDrive](https://drive.google.com/folderview?id=0BzaXzhTPJkC7WFFIM09uYm4zSk0&usp=sharing#list)
 * [Donation Package on GoogleDrive](https://drive.google.com/folderview?id=0BzaXzhTPJkC7SnpOVG11OF9ITlE&usp=sharing) (* do a donation for get it)

Compatibility
-------------
Tested on Android (Default browser, Chrome, Firefox, Opera, Boat browser, etc), Linux (Chrome, Firefox), Windows (Chrome, Firefox), iOS (Safari, Chrome).  
You can also add it on home (Android, iOS) and run as a *webapp* like a *native app*.  
Support for [GetEd2k](https://play.google.com/store/apps/details?id=org.anacletus.geted2k) (Donation Package).

How to install
--------------
1. Extract and move the entire direcotry in "/usr/share/amule/webserver/" (or /usr/local/share/amule/webserver, $HOME/.aMule/webserver/), default directory for amule web skins
2. Now you must have "/usr/share/amule/webserver/mobileMule"
3. If you want this skin as default, rename 'mobileMule' dir in "default" (not good idea if you have automatic update with some like repository), else continue 
4. Edit row 'Template=' in "$HOME/.aMule/amule.conf" (or if present "remote.conf") in 'Template=mobileMule'
5. restart amule (or only amuleweb)

**or**

Use the internal script ```push2server.sh```. For do that, you must have rsync on server.

> es. ```push2server.sh user@host:/usr/share/amule/webserver/mobileMule/```

Support
-------
If you want help me, better support, more features, get speedy fix and so on, consider offer me a coffee or a beer with a [Donation with PayPal](https://www.paypal.com/cgi-bin/webscr?cmd=_donations&business=muttley%2ebd%40gmail%2ecom&lc=IT&item_name=mobileMule&item_number=aMule%20web%20mobile%20skin&currency_code=EUR&bn=PP%2dDonationsBF%3abtn_donate_LG%2egif%3aNonHosted)

With a free donation, you'll get the **Donation Package** that adds three panels:

* Search
* Configurations
* Add ed2k

Thanks
------

* *Emanuele Ruzza* for support, suggestions, feedback and his time for debuging app on Apple devices :)