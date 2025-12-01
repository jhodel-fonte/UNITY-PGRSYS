document.addEventListener("DOMContentLoaded", () => {

  
     //  SIDEBAR TOGGLE 
    
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
                    backgroundColor: "rgba(0, 255, 255, 0.5)",
                    borderColor: "#00eaff",
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
                    x: { ticks: { color: "#00eaff" } }
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
                const report = JSON.parse(btn.dataset.report || "{}");

                // Use correct field names from API response
                const title = report.name || report.title || "Untitled Report";
                const category = report.report_type || report.ml_category || report.category || "Unknown";
                const description = report.description || "No description provided.";
                const location = report.location || "Location not specified.";
                const photo = report.photo || report.image || null;

                modalTitle.textContent = title;
                modalCategory.textContent = "Category: " + category;
                modalDescription.textContent = description;
                modalLocation.textContent = location;
                
                if (photo) {
                    modalImage.src = "../uploads/reports/" + photo;
                    modalImage.style.display = "block";
                } else {
                    modalImage.style.display = "none";
                }

                if (report.latitude && report.longitude) {
                    // Escape coordinates to prevent XSS
                    const lat = encodeURIComponent(report.latitude);
                    const lng = encodeURIComponent(report.longitude);
                    mapContainer.innerHTML = `
                        <iframe
                            width="100%"
                            height="100%"
                            style="border:0;"
                            loading="lazy"
                            allowfullscreen
                            src="https://www.google.com/maps?q=${lat},${lng}&z=14&output=embed">
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



   //SWEETALERT CONFIRMATION

function confirmAction(action, id) {
    const messages = {
        approve: "Approve this report?",
        reject: "Reject this report? (This action will delete it.)",
        delete: "Permanently delete this report?"
    };

    Swal.fire({
        title: messages[action] || "Are you sure?",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#198754",
        cancelButtonColor: "#6c757d",
        confirmButtonText: "Yes, continue"
    }).then(result => {
        if (result.isConfirmed) {
            window.location.href = `?action=${action}&id=${id}`;
        }
    });
}



   //RETURN TO USER MODAL

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
