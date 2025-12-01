/* =====================================================
   GEOAPIFY SMALL MAP + TRUE FULLSCREEN ON CLICK
===================================================== */

document.addEventListener("DOMContentLoaded", () => {

    const mapDiv = document.getElementById("map");
    if (!mapDiv) return;

    // Initialize small map
    var map = L.map("map").setView([13.9333, 121.1167], 13);

    L.tileLayer(
        "https://maps.geoapify.com/v1/tile/osm-bright/{z}/{x}/{y}.png?apiKey=6cbe5b314ed44817b7e1e51d35b6ec27",
        { maxZoom: 19 }
    ).addTo(map);

    L.marker([13.9333, 121.1167]).addTo(map).bindPopup("Test Pin");

    /* =====================================================
       CLICK MAP → ENTER FULLSCREEN
    ===================================================== */
    mapDiv.addEventListener("click", () => {

        // Enter fullscreen
        if (mapDiv.requestFullscreen) {
            mapDiv.requestFullscreen();
        } else {
            // Backup for Safari/iOS
            mapDiv.classList.add("fullscreen-fix");
        }

        // Allow time for transition
        setTimeout(() => {
            map.invalidateSize();
        }, 300);
    });

    /* =====================================================
       EXIT FULLSCREEN → FIX MAP SIZE
    ===================================================== */
    document.addEventListener("fullscreenchange", () => {
        if (!document.fullscreenElement) {
            mapDiv.classList.remove("fullscreen-fix");
            setTimeout(() => {
                map.invalidateSize();
            }, 300);
        }
    });
});



document.addEventListener("DOMContentLoaded", () => {

    const toggleBtn = document.querySelector(".sidebar-toggle");
    const sidebar = document.querySelector(".sidebar");

    if (toggleBtn && sidebar) {
        toggleBtn.addEventListener("click", () => {
            sidebar.classList.toggle("active");
        });
    }
    
      // DASHBOARD CHART

    const chartCanvas = document.getElementById("monthlyChart");

    if (chartCanvas && typeof Chart !== "undefined") {
        new Chart(chartCanvas, {
            type: "bar",
            data: {
                labels: chartMonths,
                datasets: [{
                    label: "Reports Submitted",
                    data: chartTotals,
                    backgroundColor: "rgba(64, 0, 255, 0.5)",
                    borderColor: "#1900ffff",
                    borderWidth: 1,
                    borderRadius: 5
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    y: { beginAtZero: true },
                    x: { ticks: { color: "#0800ffff" } }
                }
            }
        });
    }


    
       //COUNTER ANIMATION
    const counters = document.querySelectorAll(".count");

    counters.forEach(el => {
        const target = Number(el.dataset.value || 0);
        let current = 0;
        const speed = Math.max(1, Math.floor(target / 50));

        const interval = setInterval(() => {
            current += speed;

            if (current >= target) {
                current = target;
                clearInterval(interval);
            }

            el.textContent = current;
        }, 20);
    });


  
      // MODAL REPORT VIEWER
    const detailsModalEl = document.getElementById("detailsModal");

    if (detailsModalEl) {
        const detailsModal = new bootstrap.Modal(detailsModalEl);
        const modalTitle = document.getElementById("modalTitle");
        const modalCategory = document.getElementById("modalCategory");
        const modalDescription = document.getElementById("modalDescription");
        const modalLocation = document.getElementById("modalLocation");
        const modalImage = document.getElementById("modalImage");
        const mapContainer = document.getElementById("mapContainer");

        document.querySelectorAll(".view-details").forEach(btn => {
            btn.addEventListener("click", () => {
                const report = JSON.parse(reportData.data || "{}");

                modalTitle.textContent = report.title || "Untitled Report";
                modalCategory.textContent = "Category: " + (report.category || "Unknown");
                modalDescription.textContent = report.description || "No description provided.";
                modalLocation.textContent = report.location || "Location not specified.";
                modalImage.src = "../uploads/reports/" + (report.image || "default.png");

                if (report.latitude && report.longitude) {
                    mapContainer.innerHTML = `
                        <iframe
                            width="100%"
                            height="100%"
                            style="border:0;"
                            loading="lazy"
                            allowfullscreen
                            src="https://www.google.com/maps?q=${report.latitude},${report.longitude}&z=14&output=embed">
                        </iframe>`;
                } else {
                    mapContainer.innerHTML = `<p class="text-center text-muted">No map location available.</p>`;
                }

                detailsModal.show();
            });
        });
    }


  
      // AUTO-DISMISS FLASH MESSAGES
    const alerts = document.querySelectorAll('.alert');

    alerts.forEach(alert => {
        setTimeout(() => {
            const bsAlert = bootstrap.Alert.getOrCreateInstance(alert);
            bsAlert.close();
        }, 5000);
    });
});

const RESPONSE_TEAM_ENDPOINT = "__DIR__ .'./../../request/responseTeam.php";
let responseTeamsCache = null;

async function fetchResponseTeams() {
    if (responseTeamsCache !== null) {
        return responseTeamsCache;
    }

    const response = await fetch(RESPONSE_TEAM_ENDPOINT, {
        headers: { "Accept": "application/json" }
    });

    if (!response.ok) {
        throw new Error(`Unable to load response teams (status ${response.status}).`);
    }

    const payload = await response.json();
    if (!payload.success) {
        throw new Error(payload.message || "Failed to load response teams.");
    }

    responseTeamsCache = payload.data.teamId || [];
    return responseTeamsCache;
}

function confirmAction(action, id) {
    const messages = {
        approve: "Approve this reports?",
        reject: "Reject this report? (This action will delete it.)",
        delete: "Permanently delete this report?"
    };

    const ResponseTeam = fetchResponseTeams();
    swal.fire({
        title: "Assign Response Team!",
        icon: "warning",
        input: 'select',
        showCancelButton: true,
        confirmButtonText: 'Continue',
        inputPlaceholder: 'Select a Response Team',
        inputOptions: {
            ResponseTeam
        },
        inputValidator: (teamId) => {
            return new Promise((resolve) => {
                if (teamId === '') {
                    resolve('You need to select a !');
                } else {
                    resolve();
                }
            });
        }
    }).then((result) => {
        // FIX: The selected value is available as result.value here.
        const selectedTeamId = result.value; 

        if (result.isConfirmed) {
            Swal.fire({
                title: messages[action] || "Are you sure?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#198754",
                cancelButtonColor: "#6c757d",
                confirmButtonText: "Yes, continue"
            }).then(innerResult => {
                    if (innerResult.isConfirmed) {
                        Swal.fire(`You selected the team ID: ${selectedTeamId}`);
                        
                        // window.location.href = `?action=${action}&id=${id}&teamId=${selectedTeamId}`;
                    }
                });
        }
    });




}


/* =========================================
   SWEETALERT CONFIRMATION (BASE)
========================================= */
/* function confirmAction(action, id) {
    const messages = {
        approve: "Approve this report?",
        reject: "Reject this report? (This action will delete it.)",
        delete: "Permanently delete this report?",
        edit: "Edit this records?"
    };

    Swal.fire({
        title: messages[action] || "Are you sure?",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#198754",
        cancelButtonColor: "#6c757d",
        confirmButtonText: "Yes, continue"
    }).then(result => {
        if (!result.isConfirmed) {
            return;
        }

        try {
            const url = new URL(window.location.href);
            url.searchParams.set("action", action);
            url.searchParams.set("id", id);
            window.location.href = url.toString();
        } catch (error) {
            console.error("Unable to update URL parameters:", error);
            window.location.href = `${window.location.pathname}?action=${encodeURIComponent(action)}&id=${encodeURIComponent(id)}`;
        }
    });
} */


/* =========================================
   RETURN TO USER MODAL
========================================= */
function returnToUserModal(id) {
    const idModal = document.getElementById("idModal" + id);
    const userModal = document.getElementById("userModal" + id);

    if (!idModal || !userModal) return;

    const modalID = bootstrap.Modal.getInstance(idModal);
    modalID?.hide();

    setTimeout(() => {
        new bootstrap.Modal(userModal).show();
    }, 300);
}

// Live Search Reports
document.addEventListener('DOMContentLoaded', () => {
    const searchInput = document.getElementById('reportSearch');
    const table = document.querySelector('table tbody');

    if (searchInput) {
        searchInput.addEventListener('keyup', () => {
            const query = searchInput.value.toLowerCase();

            table.querySelectorAll('tr').forEach(row => {
                const user = row.children[1].textContent.toLowerCase();
                const title = row.children[2].textContent.toLowerCase();
                const category = row.children[3].textContent.toLowerCase();

                if (user.includes(query) || title.includes(query) || category.includes(query)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });
    }
});

// Live Search Users
document.addEventListener('DOMContentLoaded', () => {
    const searchInput = document.getElementById('userSearch');
    const table = document.querySelector('table tbody');
    if (searchInput) {
        searchInput.addEventListener('keyup', () => {
            const query = searchInput.value.toLowerCase();
        
        table.querySelectorAll('tr').forEach(row => {
                const name = row.children[1].textContent.toLowerCase();
                const email = row.children[2].textContent.toLowerCase();
                const mobile = row.children[3].textContent.toLowerCase();
            
            if (name.includes(query) || email.includes(query) || mobile.includes(query)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });
    }
});

function toggleProfileMenu() {
    const menu = document.getElementById("profileDropdown");
    menu.style.display = menu.style.display === "flex" ? "none" : "flex";
}

document.addEventListener("click", function (event) {
    const profileMenu = document.getElementById("profileDropdown");
    const profileImg = document.querySelector(".profile-img");

    if (!profileMenu.contains(event.target) && event.target !== profileImg) {
        profileMenu.style.display = "none";
    }
});
// ito yung sa notification bell
function toggleNotifications() {
    // Example: open a dropdown or modal for notifications
    alert("Show notifications panel here!");
}

// Temporary demo notifications array
const demoNotifications = [
    { id: 1, message: "New user registered", time: "2 min ago" },
    { id: 2, message: "Server backup completed", time: "10 min ago" },
    { id: 3, message: "New report submitted", time: "1 hour ago" },
    { id: 4, message: "System maintenance scheduled", time: "3 hours ago" }
];

// Populate dropdown
function populateNotifications() {
    const dropdown = document.getElementById('notificationDropdown');
    dropdown.innerHTML = ''; // Clear existing

    demoNotifications.forEach(notif => {
        const item = document.createElement('div');
        item.classList.add('notification-item');
        item.innerHTML = `
            <strong>${notif.message}</strong><br>
            <small>${notif.time}</small>
        `;
        dropdown.appendChild(item);
    });

    // Update badge count
    const badge = document.getElementById('notificationCount');
    badge.textContent = demoNotifications.length;
}

// Toggle dropdown visibility
function toggleNotifications() {
    const dropdown = document.getElementById('notificationDropdown');
    dropdown.style.display = dropdown.style.display === 'block' ? 'none' : 'block';
}

// Close dropdown if clicked outside
document.addEventListener('click', function(event) {
    const bell = document.querySelector('.notification-bell');
    const dropdown = document.getElementById('notificationDropdown');

    if (!bell.contains(event.target)) {
        dropdown.style.display = 'none';
    }
});

// Initialize
populateNotifications();



function updateClock() {
    const dateElement = document.getElementById('dateDisplay');
    const now = new Date();

    // Format date and time
    const options = { 
        weekday: 'short', 
        year: 'numeric', 
        month: 'short', 
        day: 'numeric',
        hour: '2-digit', 
        minute: '2-digit', 
    };
    
    dateElement.textContent = now.toLocaleString('en-US', options);
}

// Update clock immediately
updateClock();

// Then update every second
setInterval(updateClock, 1000);

