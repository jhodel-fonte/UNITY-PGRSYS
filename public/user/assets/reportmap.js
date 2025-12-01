document.addEventListener("DOMContentLoaded", () => {
    const apiKey = "6cbe5b314ed44817b7e1e51d35b6ec27";

    // Create map
    const map = L.map("map").setView([13.876, 121.215], 14);

    // Geoapify tiles
    L.tileLayer(`https://maps.geoapify.com/v1/tile/osm-bright/{z}/{x}/{y}.png?apiKey=${apiKey}`, {
        maxZoom: 20
    }).addTo(map);

    let marker;

    // On map click → place marker + update input values
    map.on("click", function(e) {

        if (marker) {
            marker.remove();
        }

        marker = L.marker(e.latlng).addTo(map);

        document.getElementById("latitude").value = e.latlng.lat;
        document.getElementById("longitude").value = e.latlng.lng;
        document.getElementById("location").value =
            `${e.latlng.lat.toFixed(5)}, ${e.latlng.lng.toFixed(5)}`;
    });

});
