var timerStatusUpdate = 0;

$( document ).delegate("#status", "pageinit", function() {
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

$( document ).delegate("#first-page", "pageinit", function() {
	$.mobile.changePage( "amuleweb-main-status.php", { transition: "none" } );	
});

function showMenu( ) {
	console.log('showMenu');
	$('.menu').trigger('expand');
	//goToByScroll('.menu');
	$('html, body').animate({scrollTop: $(document).height()}, 'slow', function(){ $('body').clearQueue(); });
}

function scrollUp( ) {
	console.log('scrollUp');
	//goToByScroll('body');
	$('body').animate({scrollTop : '0px'}, 'slow', function(){ $('body').clearQueue(); });
}

$(document).bind('pagebeforecreate', function() {
	console.log('pagebeforecreate');
	if( timerStatusUpdate ) { 
		clearInterval(timerStatusUpdate);
		console.log('stop update status timer - id: ' + timerStatusUpdate);
		timerStatusUpdate = 0;
	}
});

$(document).bind('pageinit', function() {
	console.log('pageinit');
});

//$( document ).delegate("#btScrollUp", "vclick", function(e) {
//	goToByScroll('body');
//});
//$( document ).delegate("#btMenu", "click", function(e) {
//	$('#menu').trigger('expand');
//	goToByScroll('#menu');
//});
