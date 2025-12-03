/* =====================================================
    GEOAPIFY SMALL MAP + TRUE FULLSCREEN ON CLICK
===================================================== */

document.addEventListener("DOMContentLoaded", () => {
    
    const mapDiv = document.getElementById("map");
    // Only initialize map if the element exists
    if (mapDiv) {
        // Initialize small map
        var map = L.map("map").setView([13.9333, 121.1167], 13);

        L.tileLayer(
            "https://maps.geoapify.com/v1/tile/osm-bright/{z}/{x}/{y}.png?apiKey=6cbe5b314ed44817b7e1e51d35b6ec27",
            { maxZoom: 19 }
        ).addTo(map);

        L.marker([13.9333, 121.1167]).addTo(map).bindPopup("Test Pin");

        /* CLICK MAP → ENTER FULLSCREEN */
        mapDiv.addEventListener("click", () => {
            if (mapDiv.requestFullscreen) {
                mapDiv.requestFullscreen();
            } else {
                mapDiv.classList.add("fullscreen-fix"); // Backup for Safari/iOS
            }
            setTimeout(() => { map.invalidateSize(); }, 300);
        });

        /* EXIT FULLSCREEN → FIX MAP SIZE */
        document.addEventListener("fullscreenchange", () => {
            if (!document.fullscreenElement) {
                mapDiv.classList.remove("fullscreen-fix");
                setTimeout(() => { map.invalidateSize(); }, 300);
            }
        });
    }
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
                labels: typeof chartMonths !== 'undefined' ? chartMonths : [],
                datasets: [{
                    label: "Reports Submitted",
                    data: typeof chartTotals !== 'undefined' ? chartTotals : [],
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

    // COUNTER ANIMATION
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
    if (detailsModalEl && typeof reportData !== 'undefined') {
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
                            src="https://www.google.com/maps?q=$${report.latitude},${report.longitude}&z=14&output=embed">
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

    // =================================================================
    // NEW: DYNAMIC USER MANAGEMENT (Search, Filter, Actions) 🚀
    // =================================================================
    const userTableBody = document.getElementById('userTableBody');
    const userSearchInput = document.getElementById('userSearch');
    const statusFilterButtons = document.querySelectorAll('.d-flex.justify-content-center.mb-3.gap-2 a.btn');

    // Check if we are on the 'manage users' page
    if (userTableBody && userSearchInput) {
        
        if (typeof allUsers === 'undefined') {
             console.error("FATAL ERROR: 'allUsers' array is not defined. Ensure it is defined in PHP/HTML before loading admin.js.");
             return; 
        }

        // Initialize Filter: Check URL, then check PHP variable, then default to All
        let currentFilterStatus = new URLSearchParams(window.location.search).get('status');
        if (!currentFilterStatus && typeof initialFilterStatus !== 'undefined') {
            currentFilterStatus = initialFilterStatus;
        } else if (!currentFilterStatus) {
            currentFilterStatus = 'All';
        }

        function renderUserRow(u) {
            // Helper to determine the badge class
            const statusClass = u.status === 'Active' ? 'success' : 
                                u.status === 'Rejected' ? 'danger' : 'warning text-dark';

            // Helper to determine the displayed status name
            const displayStatus = u.status === 'NoOtpReg' ? 'Pending' : (u.status || 'N/A');

            // Helper for action buttons
            let actionButtons = '';
            if (u.status === 'Pending' || u.status === 'NoOtpReg') {
                actionButtons = `
                    <button class="btn btn-sm btn-success action-btn" data-action="approve" data-userid="${u.userId}">Approve</button>
                    <button class="btn btn-sm btn-danger action-btn" data-action="reject" data-userid="${u.userId}">Reject</button>
                `;
            } else if (u.status === 'Approved' || u.status === 'Active') {
                actionButtons = `<button class="btn btn-sm btn-danger action-btn" data-action="delete" data-userid="${u.userId}">Delete</button>`;
            } else if (u.status === 'Rejected') {
                actionButtons = `
                    <button class="btn btn-sm btn-success action-btn" data-action="approve" data-userid="${u.userId}">Approve</button>
                    <button class="btn btn-sm btn-danger action-btn" data-action="delete" data-userid="${u.userId}">Delete</button>
                `;
            } else {
                 actionButtons = `<span>No Action</span>`;
            }

            return `
                <tr data-name="${u.firstName} ${u.lastName}" data-email="${u.email}" data-mobile="${u.mobileNum}" data-status="${u.status}">
                    <td>${u.userId}</td>
                    <td>${u.firstName} ${u.lastName}</td>
                    <td>${u.mobileNum}</td>
                    <td>${u.email}</td>
                    <td>
                        <span class="badge rounded-pill bg-${statusClass}">
                            ${displayStatus}
                        </span>
                    </td>
                    <td>
                        <button class="btn btn-sm btn-info"
                            data-bs-toggle="modal"
                            data-bs-target="#userModal${u.userId}">
                            View
                        </button>
                    </td>
                    <td>${actionButtons}</td>
                </tr>
            `;
        }

    function filterAndRenderUsers(searchTerm, filterStatus) {
        if (!Array.isArray(allUsers)) {
            console.error("Data error: allUsers is not a valid array.");
            userTableBody.innerHTML = `<tr><td colspan="7">Error loading user data from the server.</td></tr>`;
            return;
        }
        
        const lowerSearchTerm = searchTerm.toLowerCase().trim();
        const validUsers = allUsers.filter(u => u && typeof u.userId !== 'undefined');

        const filteredUsers = validUsers.filter(u => {
            
            // 1. Status Filter
            const userActualStatus = u.status === 'NoOtpReg' ? 'Pending' : u.status;
            const matchesStatus = filterStatus === 'All' || userActualStatus === filterStatus;

            // 2. Search Filter
            const pgid = String(u.userId ?? ''); // Ensure PG-ID is treated as a string for searching
            const firstName = u.firstName ?? '';
            const lastName = u.lastName ?? '';
            const email = u.email ?? '';
            const mobileNum = u.mobileNum ?? '';
            const fullName = `${firstName} ${lastName}`.toLowerCase();
            
            // FIX: Combine all search conditions using ||
            const matchesSearch = fullName.includes(lowerSearchTerm) ||
                                email.toLowerCase().includes(lowerSearchTerm) ||
                                mobileNum.includes(lowerSearchTerm) ||
                                pgid.includes(lowerSearchTerm); // Now correctly checks for User ID

            return matchesStatus && matchesSearch;
        });

        userTableBody.innerHTML = ''; 

        if (filteredUsers.length === 0) {
            userTableBody.innerHTML = `<tr><td colspan="7">No users found for the selected filter and search term.</td></tr>`;
            return;
        }

        const rows = filteredUsers.map(u => renderUserRow(u)).join('');
        userTableBody.innerHTML = rows;
    }

        // --- Event Listeners ---
        userSearchInput.addEventListener('input', (e) => {
            filterAndRenderUsers(e.target.value, currentFilterStatus);
        });

        statusFilterButtons.forEach(button => {
            button.addEventListener('click', (e) => {
                e.preventDefault();
                const href = button.getAttribute('href');
                const newStatus = new URLSearchParams(href).get('status');
                
                currentFilterStatus = newStatus;
                
                statusFilterButtons.forEach(btn => btn.classList.remove('active'));
                button.classList.add('active');
                
                filterAndRenderUsers(userSearchInput.value, currentFilterStatus);
                history.pushState(null, '', href);
            });
        });

        document.addEventListener('click', handleUserAction);
        filterAndRenderUsers(userSearchInput.value, currentFilterStatus);
    }
    
    // Live Search Reports
    const reportSearchInput = document.getElementById('reportSearch');
    const reportTable = document.querySelector('table tbody');

    if (reportSearchInput && reportTable) {
        reportSearchInput.addEventListener('keyup', () => {
            const query = reportSearchInput.value.toLowerCase();
            reportTable.querySelectorAll('tr').forEach(row => {
                const user = row.children[1]?.textContent.toLowerCase() || '';
                const title = row.children[2]?.textContent.toLowerCase() || '';
                const category = row.children[3]?.textContent.toLowerCase() || '';
                if (user.includes(query) || title.includes(query) || category.includes(query)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });
    }

}); // End of main DOMContentLoaded


// =================================================================
// ACTION BUTTON FUNCTIONALITY
// =================================================================

async function handleUserAction(event) {
    const button = event.target.closest('.action-btn');
    if (!button) return;

    const userId = button.getAttribute('data-userid');
    const action = button.getAttribute('data-action');
    const actionText = action.charAt(0).toUpperCase() + action.slice(1);

    const result = await Swal.fire({
        title: `${actionText} User?`,
        text: `Are you sure you want to ${action} user ID ${userId}?`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: action === 'delete' ? '#d33' : '#3085d6',
        cancelButtonColor: '#aaa',
        confirmButtonText: `Yes, ${actionText} it!`
    });

    if (result.isConfirmed) {
        Swal.fire({
            title: 'Processing...',
            text: 'Updating user status. Please wait.',
            allowOutsideClick: false,
            didOpen: () => { Swal.showLoading(); }
        });

        try {
            const formData = new FormData();
            formData.append('userId', userId);
            formData.append('action', action);

            // Ensure this path is correct relative to where admin.js is called
            const response = await fetch('../app/controllers/userAction.php', { 
                method: 'POST',
                body: formData
            });

            if (!response.ok) {
                throw new Error(`HTTP Error: ${response.status}`);
            }

            const data = await response.json();
            Swal.close(); 

            if (data.success) {
                Swal.fire('Success!', data.message, 'success');
                
                // Update the data model (allUsers array)
                const userIndex = allUsers.findIndex(u => u.userId == userId);
                if (userIndex !== -1 && action !== 'delete') {
                    allUsers[userIndex].status = data.newStatus;
                } else if (userIndex !== -1 && action === 'delete') {
                    allUsers.splice(userIndex, 1);
                }

                // Re-render
                const userSearchInput = document.getElementById('userSearch');
                const currentSearchTerm = userSearchInput ? userSearchInput.value : '';
                
                // location.reload();
                setTimeout(() => location.reload(), 1500); 

            } else {
                Swal.fire('Error!', data.message, 'error');
            }
        } catch (error) {
            Swal.close();
            console.error('Action Fetch Error:', error);
            Swal.fire('Connection Error!', `Could not perform action. Details: ${error.message}`, 'error');
        }
    }
}

// FIX: Do NOT use __DIR__ in JS. Use relative URL path.
const RESPONSE_TEAM_ENDPOINT = "../../app/request/responseTeam.php"; 
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

    responseTeamsCache = payload.data.map(team => team.teamId) || [];
    return responseTeamsCache;
}

function confirmAction(action, id) {
    const messages = {
        approve: "Approve this reports?",
        reject: "Reject this report? (This action will delete it.)",
        delete: "Permanently delete this report?"
    };

    fetchResponseTeams().then(responseTeams => {
        const inputOptions = responseTeams.reduce((obj, teamId) => {
            obj[teamId] = `Team ID ${teamId}`; 
            return obj;
        }, {});

        Swal.fire({
            title: "Assign Response Team!",
            icon: "warning",
            input: 'select',
            showCancelButton: true,
            confirmButtonText: 'Continue',
            inputPlaceholder: 'Select a Response Team',
            inputOptions: inputOptions,
            inputValidator: (teamId) => {
                return teamId === '' ? 'You need to select a team!' : null;
            }
        }).then((result) => {
            if (result.isConfirmed) {
                const selectedTeamId = result.value; 
                Swal.fire({
                    title: messages[action] || "Are you sure?",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonText: "Yes, continue"
                }).then(innerResult => {
                    if (innerResult.isConfirmed) {
                        Swal.fire(`Report Action: ${action} | Team ID: ${selectedTeamId}`);
                    }
                });
            }
        });
    }).catch(error => {
        console.error("Error fetching response teams:", error);
        Swal.fire('Error', 'Could not load response teams.', 'error');
    });
}


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


function toggleProfileMenu() {
    const menu = document.getElementById("profileDropdown");
    menu.style.display = menu.style.display === "flex" ? "none" : "flex";
}

document.addEventListener("click", function (event) {
    const profileMenu = document.getElementById("profileDropdown");
    const profileImg = document.querySelector(".profile-img");

    if (profileMenu && profileImg && !profileMenu.contains(event.target) && event.target !== profileImg) {
        profileMenu.style.display = "none";
    }
});


// Notification Logic
const demoNotifications = [
    { id: 1, message: "New user registered", time: "2 min ago" },
    { id: 2, message: "Server backup completed", time: "10 min ago" },
    { id: 3, message: "New report submitted", time: "1 hour ago" },
    { id: 4, message: "System maintenance scheduled", time: "3 hours ago" }
];

function populateNotifications() {
    const dropdown = document.getElementById('notificationDropdown');
    const bell = document.querySelector('.notification-bell');

    if (!dropdown || !bell) return;

    dropdown.innerHTML = ''; 

    demoNotifications.forEach(notif => {
        const item = document.createElement('div');
        item.classList.add('notification-item');
        item.innerHTML = `
            <strong>${notif.message}</strong><br>
            <small>${notif.time}</small>
        `;
        dropdown.appendChild(item);
    });

    const badge = document.getElementById('notificationCount');
    if (badge) {
        badge.textContent = demoNotifications.length;
    }
}

function toggleNotifications() {
    const dropdown = document.getElementById('notificationDropdown');
    if(dropdown) {
        dropdown.style.display = dropdown.style.display === 'block' ? 'none' : 'block';
    }
}

document.addEventListener('click', function(event) {
    const bell = document.querySelector('.notification-bell');
    const dropdown = document.getElementById('notificationDropdown');

    if (bell && dropdown && !bell.contains(event.target) && !dropdown.contains(event.target)) {
        dropdown.style.display = 'none';
    }
});

document.addEventListener('DOMContentLoaded', populateNotifications);


function updateClock() {
    const dateElement = document.getElementById('dateDisplay');
    if (!dateElement) return;

    const now = new Date();
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

updateClock();
setInterval(updateClock, 1000);
