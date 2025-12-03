// -------------------------
// FILTER FUNCTION
// -------------------------
const filterButtons = document.querySelectorAll(".filter-btn");
const tableBody = document.getElementById("reportTableBody");
const searchInput = document.getElementById("searchInput");

filterButtons.forEach(btn => {
    btn.addEventListener("click", () => {
        document.querySelector(".filter-btn.active").classList.remove("active");
        btn.classList.add("active");

        filterTable();
    });
});

// -------------------------
// SEARCH FUNCTION
// -------------------------
searchInput.addEventListener("keyup", filterTable);

function filterTable() {
    const filter = document.querySelector(".filter-btn.active").dataset.filter;
    const search = searchInput.value.toLowerCase();

    [...tableBody.rows].forEach(row => {
        const category = row.cells[1].innerText.toLowerCase();
        const status = row.dataset.status;
        const matchesSearch = category.includes(search);

        const matchesFilter = filter === "All" || status === filter;

        row.style.display = (matchesFilter && matchesSearch) ? "" : "none";
    });
}


// -------------------------
// VIEW MODAL WITH MAP
// -------------------------

let modalMap;
let marker;

document.querySelectorAll(".view-btn").forEach(button => {
    button.addEventListener("click", () => {
        const r = JSON.parse(button.dataset.report);

        document.getElementById("modalCategory").textContent = r.category;
        document.getElementById("modalStatus").textContent = r.status;
        document.getElementById("modalDate").textContent = r.date;
        document.getElementById("modalLocation").textContent = r.location;
        document.getElementById("modalDescription").textContent = r.description;
        document.getElementById("modalImage").src = "../assets/img/" + r.image;

        const modal = new bootstrap.Modal(document.getElementById("viewModal"));
        modal.show();

        // Initialize map after modal fully appears
        setTimeout(() => {
            if (!modalMap) {
                modalMap = L.map("modalMap").setView([13.834, 121.218], 14);
                L.tileLayer("https://maps.geoapify.com/v1/tile/osm-bright/{z}/{x}/{y}.png?apiKey=6cbe5b314ed44817b7e1e51d35b6ec27").addTo(modalMap);
            }

            if (marker) marker.remove();

            // temporary location coords
            marker = L.marker([13.834, 121.218]).addTo(modalMap);

            modalMap.invalidateSize();
        }, 300);
    });
});

// =======================================
// EDIT FUNCTION (using ajax send a id, action, approve, and delete
// =======================================
document.querySelectorAll(".edit-btn").forEach((btn, index) => {
    btn.addEventListener("click", () => {
        const row = btn.closest("tr");
        const id = row.cells[0].innerText;
        const category = row.cells[1].innerText;
        const status = row.cells[2].innerText.trim();
        const date = row.cells[3].innerText;

        // Simple editable prompt demo
        const newCategory = prompt("Update report category:", category);
        if (!newCategory) return;

        // Update row in table
        row.cells[1].innerText = newCategory;

        Swal.fire({
            title: messages[action] || "Edit this report?",
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: "Yes, continue"
        }).then(innerResult => {
            if (innerResult.isConfirmed) {
                Swal.fire(`Report Action: ${action} | Team ID: ${selectedTeamId}`);
            }
        })
    });
})
