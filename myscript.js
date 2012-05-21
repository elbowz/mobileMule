$( document ).delegate("#status", "pageinit", function() {
	updateStatus();
	var timer = setInterval(updateStatus, 3000);

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
						//$('#server-name').html(data.serv_name);
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

//$( document ).delegate("#status, #dload, #uploads", "pageinit", function() {
	$( document ).delegate("#btMenu", "click", function(e) {
		$('#menu').trigger('expand');
		goToByScroll('#menu');
	});

	$( document ).delegate("#btScrollUp", "click", function(e) {
		goToByScroll('body');
	});

	function goToByScroll(id) {
		$('body').animate({scrollTop : $(id).offset().top}, 'slow', function(){ $('body').clearQueue(); });
	}
//});






