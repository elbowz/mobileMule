var timerStatusUpdate = 0;

// Disable send form through AJAX (default behavior)
$(document).on("mobileinit", function(){
    $.extend(  $.mobile , {
        ajaxEnabled: false
    });
});

$(document).on('pageinit', function() {
    console.log('pageinit');

    var width = $(window).width();
    if(width > 768){
        $("#menu-panel").panel("open"); /* nav-panel is the id of the panel div*/
    }
});

$(document).on('pagebeforecreate', function() {
    console.log('pagebeforecreate');
    if( timerStatusUpdate ) {
        clearInterval(timerStatusUpdate);
        console.log('stop update status timer - id: ' + timerStatusUpdate);
        timerStatusUpdate = 0;
    }

    $('#menu-panel').html('<ul data-role="listview">\
                            <li data-icon="delete"><a href="#" data-rel="close">Close</a></li>\
                            <li data-icon="home"><a href="amuleweb-main-status.php">Status</a></li>\
                            <li><a href="amuleweb-main-dload-real.php">Downloads</a></li>\
                            <li><a href="amuleweb-main-upload.php">Uploads</a></li>\
                            <li><a href="amuleweb-main-search.php">Search</a></li>\
                            <li><a href="amuleweb-main-prefs.php">Configurations</a></li>\
                            <li><a href="amuleweb-main-stats.php">Statistics</a></li>\
                            <li><a href="amuleweb-main-graph.php">Graphs</a></li>\
                            <li><a href="amuleweb-main-log.php">Log</a></li>\
                            <li><a href="footer.php">Add ed2k</a></li>\
                            <li data-icon="power"><a href="login.php">Logout</a></li>\
                           </ul>');
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

$( document ).on("pageinit", "#first-page", function() {
	$.mobile.changePage( "amuleweb-main-status.php", { transition: "none" } );	
});

$(document).on('click','#btScrollUp', function() {
    console.log('scrollUp');
    $('body').animate({scrollTop : '0px'}, 'slow', function(){ $('body').clearQueue(); });
});