  window.onload = function() {
  document.getElementById('startLat').value = "no_geolocation";
  document.getElementById('startLon').value = "no_geolocation";
  var startPos;
    var geoOptions = {
  	maximumAge: 5 * 60 * 1000,
    timeout: 10 * 1000
  }
  var geoSuccess = function(position) {
    startPos = position;
    document.getElementById('startLat').value = startPos.coords.latitude;
    document.getElementById('startLon').value = startPos.coords.longitude;
  };
  
   var geoError = function(error) {
    console.log('Error occurred. Error code: ' + error.code);
    
    // error.code can be:
    //   0: unknown error
    //   1: permission denied
    //   2: position unavailable (error response from location provider)
    //   3: timed out
  };
  
  if (navigator.geolocation) {
  
  navigator.geolocation.getCurrentPosition(geoSuccess, geoError, geoOptions);
  }
};