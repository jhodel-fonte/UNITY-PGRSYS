// status.js
(function () {
  if (typeof GEOAPIFY_KEY === "undefined") {
    console.warn("GEOAPIFY_KEY not found. Map tiles will not load.");
  }

  // map tile URL for Geoapify (Leaflet)
  const tileUrl = GEOAPIFY_KEY
    ? `https://maps.geoapify.com/v1/tile/osm-bright/{z}/{x}/{y}.png?apiKey=${GEOAPIFY_KEY}`
    : "https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png";

  // utility: find steps number
  const statusToStep = { "Approved": 1, "Dispatched": 2, "Ongoing": 3, "Resolved": 4 };

  function initCard(card) {
    const report = JSON.parse(card.getAttribute("data-report"));
    const step = statusToStep[report.status] || 1;

    // fill progress
    const timeline = card.querySelector(".timeline");
    const fill = timeline.querySelector(".progress-bar-fill");
    // percent = (step-1)/3 * 100
    const pct = ((step - 1) / 3) * 100;
    // animate
    requestAnimationFrame(() => { fill.style.width = pct + "%"; });

    // set step classes
    const steps = card.querySelectorAll(".step");
    steps.forEach((sEl, idx) => {
      sEl.classList.remove("active", "completed");
      const pos = idx + 1;
      if (pos < step) sEl.classList.add("completed");
      if (pos === step) sEl.classList.add("active");
    });

    // click handler to open modal (also allow button inside)
    const open = card.querySelector(".view-status");
    const openHandler = () => openModalWithReport(report);
    card.addEventListener("click", (e) => {
      // avoid capturing clicks on buttons that we don't want
      if (e.target.closest(".btn")) return;
      openHandler();
    });
    if (open) open.addEventListener("click", openHandler);
  }

  // init all cards
  document.querySelectorAll(".status-card").forEach(c => initCard(c));

  // ---------- Modal + MAP ----------
  let modalMap = null;
  let teamMarker = null;
  let userMarker = null;
  let modalInstance = new bootstrap.Modal(document.getElementById("statusModal"));

  function openModalWithReport(r) {
    document.getElementById("modalTitle").textContent = r.category;
    document.getElementById("modalLocation").textContent = r.location;
    document.getElementById("modalDate").textContent = r.date;
    document.getElementById("modalDescription").textContent = (r.description || "No additional details provided.");
    document.getElementById("modalImage").src = "../assets/img/" + (r.image || "placeholder.png");

    modalInstance.show();

    // init map after shown
    setTimeout(() => {
      initOrUpdateModalMap(r);
    }, 250);
  }

  function initOrUpdateModalMap(r) {
    const mapEl = document.getElementById("modalMap");
    if (!mapEl) return;

    if (!modalMap) {
      modalMap = L.map(mapEl).setView([r.team_lat || 13.9333, r.team_lng || 121.1167], 13);
      L.tileLayer(tileUrl, { maxZoom: 19, tileSize: 512, zoomOffset: -1 }).addTo(modalMap);
    }

    // remove old markers
    if (teamMarker) modalMap.removeLayer(teamMarker);
    if (userMarker) modalMap.removeLayer(userMarker);

    // team marker (pulsing style)
    teamMarker = L.circleMarker([r.team_lat, r.team_lng], {
      radius: 9,
      color: "#ff9800",
      fillColor: "#ff9800",
      fillOpacity: 1,
      weight: 2,
      opacity: 1
    }).addTo(modalMap).bindPopup("<strong>Response Team</strong>");

    // user/report location marker
    userMarker = L.marker([r.user_lat, r.user_lng]).addTo(modalMap).bindPopup("<strong>Reported Issue</strong><br/>"+ (r.location || "") );

    // fit bounds
    const group = L.featureGroup([teamMarker, userMarker]);
    modalMap.fitBounds(group.getBounds().pad(0.45));

    // wire zoom buttons
    document.getElementById("zoomTeam").onclick = () => {
      modalMap.setView([r.team_lat, r.team_lng], 16);
      teamMarker.openPopup();
    };
    document.getElementById("zoomUser").onclick = () => {
      modalMap.setView([r.user_lat, r.user_lng], 16);
      userMarker.openPopup();
    };

    setTimeout(()=> modalMap.invalidateSize(), 300);
  }

  // Optional: animate progress on load (slight delay so users see the motion)
  window.addEventListener("load", () => {
    document.querySelectorAll(".timeline .progress-bar-fill").forEach((el, idx) => {
      el.style.transitionDelay = (idx * 60) + "ms";
    });
  });

})();
