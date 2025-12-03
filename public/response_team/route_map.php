<!DOCTYPE html>
<html>
<head>
    <title>Navigation Route</title>

    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css">

    <style>
        body, html {
            margin: 0;
            padding: 0;
            height: 100%;
        }

        #routeMap {
            width: 100%;
            height: 100%;
        }

        .directions-box {
            position: absolute;
            bottom: 10px;
            left: 10px;
            max-width: 300px;
            background: white;
            padding: 10px;
            border-radius: 8px;
            max-height: 40%;
            overflow-y: auto;
            box-shadow: 0 0 10px rgba(0,0,0,0.2);
        }
    </style>
</head>

<body>
    <a href="asreports.php" class="exit-map-btn">
    ← Back
</a>


<div id="routeMap"></div>
<div class="directions-box" id="steps"></div>

<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>

<script>
    const teamLat = <?= $_GET['teamLat'] ?>;
    const teamLng = <?= $_GET['teamLng'] ?>;
    const destLat = <?= $_GET['destLat'] ?>;
    const destLng = <?= $_GET['destLng'] ?>;

    const apiKey = "6cbe5b314ed44817b7e1e51d35b6ec27";

    const map = L.map("routeMap").setView([teamLat, teamLng], 14);

    L.tileLayer(`https://maps.geoapify.com/v1/tile/osm-bright/{z}/{x}/{y}.png?apiKey=${apiKey}`, {
        maxZoom: 20
    }).addTo(map);

    // -------------------------
    // ROUTING REQUEST
    // -------------------------
    fetch(`https://api.geoapify.com/v1/routing?waypoints=${teamLat},${teamLng}|${destLat},${destLng}&mode=drive&apiKey=${apiKey}`)
        .then(res => res.json())
        .then(data => {
            const routeCoords = data.features[0].geometry.coordinates[0].map(coord => [coord[1], coord[0]]);

            // Draw route line
            L.polyline(routeCoords, { color: "blue", weight: 5 }).addTo(map);

            // Fit map to route
            map.fitBounds(routeCoords);

            // Show steps
            const stepsBox = document.getElementById("steps");
            stepsBox.innerHTML = "<h4>Directions</h4>";

            data.features[0].properties.legs[0].steps.forEach(step => {
                stepsBox.innerHTML += `<p>${step.instruction}</p>`;
            });

            // Markers
            L.marker([teamLat, teamLng]).addTo(map).bindPopup("Your Location");
            L.marker([destLat, destLng]).addTo(map).bindPopup("Report Location");
        });
</script>
</body>
</html>
