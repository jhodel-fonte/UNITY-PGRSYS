document.addEventListener("DOMContentLoaded", function () {

    // Initialize map
    const map = L.map("userMap").setView([13.8794, 121.2160], 14);

    L.tileLayer(`https://maps.geoapify.com/v1/tile/osm-bright/{z}/{x}/{y}.png?apiKey=6cbe5b314ed44817b7e1e51d35b6ec27`, {
        maxZoom: 20
    }).addTo(map);

    // PHP → JS transfer
    const reports = window.demoReports || [];

    const bounds = [];

    reports.forEach(r => {
        const markerIcon = L.divIcon({
            className: r.status === "Pending" ? "marker-pending" : "marker-resolved"
        });

        L.marker([r.lat, r.lng], { icon: markerIcon })
            .addTo(map)
            .bindPopup(`
                <strong>Report #${r.id}</strong><br>
                Status: ${r.status}
            `);

        bounds.push([r.lat, r.lng]);
    });

    if (bounds.length) {
        map.fitBounds(bounds, { padding: [40, 40] });
    }
});
