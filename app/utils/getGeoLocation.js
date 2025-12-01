
    function getLocation() {
  if (navigator.geolocation) {
    navigator.geolocation.getCurrentPosition(success, error);
  } else { 
    alert( "Geolocation is not supported by this browser.");
  }
}

function success(position) {
    alert(    "Latitude: " + position.coords.latitude + 
  " Longitude: " + position.coords.longitude);

}

function error() {
  alert("Sorry, no position available.");
}
