var t;

function initializeTimer(sec, callback){

		if(isNaN(sec)){

			alert("Invalid starting time");
			return;
		}

		time = sec * 1000;

		t = setInterval(function() {
			timer(callback)
		}, 1000);

		return t;

}

function stopTimer() {
	clearInterval(t);
}

function timer(callback){

	var ending = time;
	var remaining = ending - 1000;
	time = remaining;

	clock = document.getElementById('timer');

	// If there is any tme remaining, it displays it
	if(remaining > 0){

		if(clock){
				display = displayTime(remaining);
				clock.innerHTML = display;
		}

	}

	// If no time is left, the timer is set to display zero
	else {
		clearInterval(t);
		if(clock){
				clock.innerHTML = '00:00';
		}

		if (typeof callback === "function") {
			callback();
		}
	}

}




function displayTime(timer){

	var theTime = new Date(timer);

	var minutesDisplay = pad(theTime.getMinutes(), 2);
	var secondsDisplay = pad(theTime.getSeconds(), 2);

	return minutesDisplay+':'+secondsDisplay;

}


function pad(number, length) {

	var str = '' + number;
	while (str.length < length) {
		str = '0' + str;
	}

	return str;

}
