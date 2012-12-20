       	var latitude = 0;
		var longitude = 0;
		if (navigator.geolocation) {
			var timeoutVal = 10 * 1000 * 1000;
			navigator.geolocation.getCurrentPosition(
				setLatAndLong, 
				displayError,
				{ enableHighAccuracy: true, timeout: timeoutVal, maximumAge: 0 }
			);
		}
		else {
			alert("Geolocation is not supported by this browser");
		}
			
		function setLatAndLong(position) {
			latitude = position.coords.latitude;
			longitude = position.coords.longitude;
			document.getElementById("latitude").value = latitude;
			document.getElementById("longitude").value = longitude;
		}
			
		function displayError(error) {
			var errors = { 
				1: 'Permission denied',
				2: 'Position unavailable',
				3: 'Request timeout'
			};
			alert("Error: " + errors[error.code]);
		}	