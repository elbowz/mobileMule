<!DOCTYPE html>
<html>
<head>
    <title>mobileMule</title>

    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />

    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="theme-color" content="#545454">

    <link rel="manifest" href="manifest.json" crossorigin="use-credentials">
    <link href="icon-192x192.png" rel="icon" sizes="192x192"/>
    <link href="apple-touch-icon.png" rel="apple-touch-icon"/>
    <link href="apple-touch-icon-76x76.png" rel="apple-touch-icon" sizes="76x76"/>
    <link href="apple-touch-icon-120x120.png" rel="apple-touch-icon" sizes="120x120"/>
    <link href="apple-touch-icon-152x152.png" rel="apple-touch-icon" sizes="152x152"/>

    <link rel="stylesheet" href="//stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" media="screen"/>
    <link rel="stylesheet" href="//ajax.googleapis.com/ajax/libs/jquerymobile/1.4.5/jquery.mobile.min.css" media="screen"/>

    <meta id="mobile-mule-theme"/>
    <!-- append the theme css here -->

    <link rel="stylesheet" type="text/css" href="addtohomescreen.css" media="screen">
    <link rel="stylesheet" type="text/css" href="main.css" media="screen"/>

    <script async>
        (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
            (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
            m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
        })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

        ga('create', 'UA-50409066-3', { 'cookieDomain': 'none' });
        ga('set', 'anonymizeIp', true);
    </script>
    
    <script src="local-storage.js"></script>

    <script src="underscore-min.js"></script>
    <script src="//ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
    <script src="//ajax.googleapis.com/ajax/libs/jquerymobile/1.4.5/jquery.mobile.min.js"></script>
    <script src="handlebars.min.js"></script>
    <script src="idb.min.js"></script>
    <script src="chart.min.js"></script>
    <script src="addtohomescreen.min.js"></script>

    <script src="utils.js"></script>
    <script src="main.js"></script>
</head>

<body>
<div id="main" data-role="page" data-title="mobileMule">

    <div id="header" data-role="header" data-position="fixed">
        <a href="#menu-panel" class="ui-btn-left"><i class="fa fa-bars fa-fw"></i><span> Menu</span></a>

        <h1><i class="fa fa-home fa-fw"></i> Status</h1>
        <a href="#" data-rel="back" data-icon="arrow-l" class="ui-btn-right">Back</a>
    </div>
    <!-- /header -->

    <div id="container" data-role="content">
        <!-- Content loaded by ajax -->
    </div>
    <!-- /content -->

    <div id="footer" data-role="footer">
        <a id="btScrollUp" href="#" data-icon="arrow-u" class="ui-btn-right">scroll up</a>
        <p>&nbsp;<a href="#about" data-rel="popup" data-position-to="window" data-transition="pop">mobileMule</a> <i class="fa fa-copyright fa-fw"></i> 2023</p>
    </div>
    <!-- /footer -->

    <div data-role="panel" id="menu-panel" data-display="reveal" data-position-fixed="true">
        <ul data-role="listview">
            <li data-icon="false"><a href="#page-status" class="hash-link" accesskey="s"><u>S</u>tatus <i class="fa fa-home pull-right fa-fw"></i></a></li>
            <li data-icon="false"><a href="#page-downloads" class="hash-link" accesskey="d"><u>D</u>ownloads <i class="fa fa-download pull-right fa-fw"></i></a></li>
            <li data-icon="false"><a href="#page-finished" class="hash-link" accesskey="f"><u>F</u>inished <i class="fa fa-cloud-download pull-right fa-fw"></i></a></li>
            <li data-icon="false"><a href="#page-uploads" class="hash-link" accesskey="p">U<u>p</u>loads <i class="fa fa-upload pull-right fa-fw"></i></a></li>
            <li data-icon="false"><a href="#page-shared" class="hash-link" accesskey="h">S<u>h</u>ared <i class="fa fa-share-alt pull-right fa-fw"></i></a></li>
            <li data-icon="false"><a href="#page-search" class="hash-link" accesskey="e"><u>S</u>earch <i class="fa fa-search pull-right fa-fw"></i></a></li>
            <li data-icon="false"><a href="#page-config" class="hash-link" accesskey="t">Se<u>t</u>tings <i class="fa fa-cogs pull-right fa-fw"></i></a></li>
            <li data-icon="false"><a href="#page-mobilemule" class="hash-link" accesskey="o">M<u>o</u>bileMule <i class="fa fa-cog pull-right fa-fw"></i></a></li>
            <li data-icon="false"><a href="#page-servers" class="hash-link" accesskey="r">Se<u>r</u>vers <i class="fa fa-database pull-right fa-fw"></i></a></li>
            <li data-icon="false"><a href="#page-listed-stats" class="hash-link" accesskey="a">St<u>a</u>tistics <i class="fa fa-tachometer pull-right fa-fw"></i></a></li>
            <li data-icon="false"><a href="#page-graph" class="hash-link" accesskey="g"><u>G</u>raphs <i class="fa fa-bar-chart-o pull-right fa-fw"></i></a></li>
            <li data-icon="false"><a href="#page-log" class="hash-link" accesskey="l"><u>L</u>og <i class="fa fa-bars pull-right fa-fw"></i></a></li>
            <li data-icon="false"><a href="#page-footer" class="hash-link" accesskey="d">A<u>d</u>d ed2k <i class="fa fa-plus pull-right fa-fw"></i></a></li>
            <li data-icon="false"><a href="login.php" id="pnMenuLogout" accesskey="u">Logo<u>u</u>t <i class="fa fa-unlock-alt pull-right fa-fw"></i></a></li>
        </ul>
    </div>
    <!-- /panel menu -->

    <div data-role="popup" id="about">
        <div data-role="header">
            <a href="#" data-rel="back"
               class="ui-btn ui-corner-all ui-shadow ui-btn-a ui-icon-delete ui-btn-icon-notext ui-btn-right">Close</a>

            <h2>About</h2>
        </div>
        <div role="main" class="ui-content">
            <!-- IMPORTANT: Update also main.js#version check, latestVersion.js, main.php -->
            <p><strong>mobileMule v3.6.6b</strong><br/>

                coded by muttley <i class="fa fa-copyright fa-fw"></i> 2023<br/><br/>
                <i class="fa fa-book fa-fw"></i>
                <a href="https://raw.githubusercontent.com/elbowz/mobileMule/master/CHANGELOG.md" title="changelog">Changelog</a> &bull;
                <i class="fa fa-github-alt fa-fw"></i>
                <a href="https://github.com/elbowz/mobileMule" title="mobileMule project page"> Project page</a> &bull;
                <i class="fa fa-paypal fa-fw"></i>
                <a href="#page-donate" class="hash-link" title="Donation Package"> Donation Package</a><br/><br/>
            </p>
        </div>
    </div>
    <!-- /about -->
</div>
<!-- /page -->
</body>
</html>
