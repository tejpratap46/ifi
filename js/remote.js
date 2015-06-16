$("#wifi").bootstrapSwitch();
$("#bluetooth").bootstrapSwitch();
$("#silent").bootstrapSwitch();
$("#data").bootstrapSwitch();
$("#rotation").bootstrapSwitch();
$("#airplane").bootstrapSwitch();

$('#refresh').click(function(event) {
	/* Act on the event */
	AnimateRotate(360);
	sendpush('getStatus');
});

function wifiToggle () {
	checked = $('#wifi').prop("checked");
	if(checked){
		sendpush('Turn On Wifi');
	}else{
		sendpush('Turn Off Wifi');
	}
}

function AnimateRotate(angle) {
// caching the object for performance reasons
var $elem = $('#refresh');

// we use a pseudo object for the animation
// (starts from `0` to `angle`), you can name it as you want
$({deg: 0}).animate({deg: angle}, {
        duration: 1000,
        step: function(now) {
            // in the step-callback (that is fired each step of the animation),
            // you can use the `now` paramter which contains the current
            // animation-position (`0` up to `angle`)
            $elem.css({
                transform: 'rotate(' + now + 'deg)'
            });
        }
    });
}

function sendpush(message) {
	$('.notification').text('Loading...').show(100);
	$.getJSON('api/push/pushnotification.php?apikey=tejpratap&email=' + <?php echo "'".$_COOKIE['ifiusername']."'" ?> + '&message=' + message, function(json, textStatus) {
		if(json.success == 1){
			$('.notification').stop().text('Request Sent, Will Be Responded In A Minute.').show(100).delay(3000).hide(100);
		}else{
			$('.notification').stop().text('Not Sent').show(100).delay(3000).hide(100);
		}
	});
}