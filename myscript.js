var timerStatusUpdate = 0;

// Disable send form through AJAX (default behavior)
$(document).on("mobileinit", function(){
    $.extend(  $.mobile , {
        ajaxEnabled: false
    });
});

$(document).on('pageinit', function() {

    var width = $(window).width();
    if(width > 768){
        $("#menu-panel").panel("open"); /* nav-panel is the id of the panel div*/
    }
});

$(document).on('pagebeforecreate', function() {
    if( timerStatusUpdate ) {
        clearInterval(timerStatusUpdate);
        console.log('stop update status timer - id: ' + timerStatusUpdate);
        timerStatusUpdate = 0;
    }

    $('#menu-panel').html('<ul data-role="listview">\
                            <li data-icon="false"><a href="amuleweb-main-status.php">Status <i class="fa fa-home pull-right fa-fw"></i></a> </li>\
                            <li data-icon="false"><a href="amuleweb-main-dload.php">Downloads <i class="fa fa-download pull-right fa-fw"></i></a></li>\
                            <li data-icon="false"><a href="amuleweb-main-upload.php">Uploads <i class="fa fa-upload pull-right fa-fw"></i></a></li>\
                            <li data-icon="false"><a href="amuleweb-main-search.php">Search <i class="fa fa-search pull-right fa-fw"></i></a></li>\
                            <li data-icon="false"><a href="amuleweb-main-prefs.php">Configurations <i class="fa fa-cogs pull-right fa-fw"></i></a></li>\
                            <li data-icon="false"><a href="amuleweb-main-servers.php">Servers <i class="fa fa-database pull-right fa-fw"></i></a></li>\
                            <li data-icon="false"><a href="amuleweb-main-stats.php">Statistics <i class="fa fa-tachometer pull-right fa-fw"></i></a></li>\
                            <li data-icon="false"><a href="amuleweb-main-graph.php">Graphs <i class="fa fa-bar-chart-o pull-right fa-fw"></i></a></li>\
                            <li data-icon="false"><a href="amuleweb-main-log.php">Log <i class="fa fa-bars pull-right fa-fw"></i></a></li>\
                            <li data-icon="false"><a href="footer.php">Add ed2k <i class="fa fa-plus pull-right fa-fw"></i></a></li>\
                            <li data-icon="false"><a href="login.php">Logout <i class="fa fa-unlock-alt pull-right fa-fw"></i></a></li>\
                           </ul>');
});

$( document ).on( "pagecreate", function() {
    // Support for open panel menu with swipe
    $( document ).on( "swipeleft swiperight", "div[data-role='page']", function( e ) {
        // We check if there is no open panel on the page because otherwise
        // a swipe to close the left panel would also open the right panel (and v.v.).
        // We do this by checking the data that the framework stores on the page element (panel: open).
        if ( $( ".ui-page-active" ).jqmData( "panel" ) !== "open" ) {
            if ( e.type === "swiperight" ) {
                $( "#menu-panel" ).panel( "open" );
            }
        }
    });

    // Preventing Links In Standalone iPhone Applications From Opening In Mobile Safari
    if (("standalone" in window.navigator) && window.navigator.standalone) {
        $('a').bind('click', function (event) {

                event.preventDefault();

                var newLocation =  $(event.currentTarget).attr("href");

                if (newLocation != undefined && newLocation.substr(0, 1) != '#'){
                    console.log('ciao')
                    window.location = newLocation;
                }
            }
        );
    }
});

$( document ).on("pageinit", "#status", function() {
	updateStatus();
	timerStatusUpdate = setInterval(updateStatus, 3000);
	console.log('start update status timer - id: ' + timerStatusUpdate);
	
	var counter = 0;

	function updateStatus() {
		$.getJSON('ajax-status.php', function(data) {
			for ( var key in data) {
				switch (key) {
				case 'speed_down':
					$('#' + key).html(
							(data[key] / 1024).toFixed(2) + ' / '
									+ data['speed_limit_down'] / 1024
									+ ' Kb');
					break;
				case 'speed_up':
					$('#' + key).html(
							(data[key] / 1024).toFixed(2) + ' / '
									+ data['speed_limit_up'] / 1024
									+ ' Kb');
					break;
				case 'id':
					var connectionStatus = null;
					if (data.id == 0) {
						connectionStatus = 'Not connected';
						$('.server-child').hide();
					} else if (data.id == Number('0xffffffff')) {
						connectionStatus = 'Connecting ...';
						$('.server-child').hide();
					} else {
						connectionStatus = 'Connected with ';
						if (data.id < 16777216)
							connectionStatus += 'low ID';
						else
							connectionStatus += 'high ID';

						$('#server-address').html(data.serv_addr);
						$('#server-name').html(data.serv_name);
						//$('#server-users').html(data.serv_users);

					}
					$('#server').html(
							'Server (' + connectionStatus + ')');
					break;
				case 'kad_connected':
					var connectionStatus = null;
					if (data.kad_connected == 1) {
						connectionStatus = 'Connected ';
						if (data.kad_firewalled == 1) {
							connectionStatus += 'but Firewalled';
						} else {
							connectionStatus += 'OK';
						}
					} else {
						connectionStatus = 'Not connected';
					}
					$('#kad').html('Kad (' + connectionStatus + ')');
					break;
				}
			}
			counter++;
		});
	}
});

$(document).on('click','#btScrollUp', function(event) {
    event.preventDefault();

    $('body').animate({scrollTop : '0px'});
});