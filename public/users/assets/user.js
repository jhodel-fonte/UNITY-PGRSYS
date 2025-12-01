document.addEventListener("DOMContentLoaded", () => {

    /* ---------------------------------------------
       SIDEBAR TOGGLE
    --------------------------------------------- */
    const toggleBtn = document.querySelector(".sidebar-toggle");
    const sidebar = document.querySelector(".sidebar");

    if (toggleBtn && sidebar) {
        toggleBtn.addEventListener("click", () => {
            sidebar.classList.toggle("active");
        });
    }

    /* ---------------------------------------------
       PROFILE DROPDOWN
    --------------------------------------------- */
    window.toggleProfileMenu = function () {
        const menu = document.getElementById("profileDropdown");
        if (menu) {
            menu.style.display = menu.style.display === "flex" ? "none" : "flex";
        }
    };

    document.addEventListener("click", function (event) {
        const profileMenu = document.getElementById("profileDropdown");
        const profileImg = document.querySelector(".profile-img");

        if (!profileMenu) return;

        if (!profileMenu.contains(event.target) && event.target !== profileImg) {
            profileMenu.style.display = "none";
        }
    });

    /* ---------------------------------------------
       NOTIFICATIONS DROPDOWN
    --------------------------------------------- */

    const demoNotifications = [
        { id: 1, message: "New user registered", time: "2 min ago" },
        { id: 2, message: "Server backup completed", time: "10 min ago" },
        { id: 3, message: "New report submitted", time: "1 hour ago" },
        { id: 4, message: "System maintenance scheduled", time: "3 hours ago" }
    ];

    function populateNotifications() {
        const dropdown = document.getElementById("notificationDropdown");
        const badge = document.getElementById("notificationCount");

        if (!dropdown || !badge) return;

        dropdown.innerHTML = "";

        demoNotifications.forEach(notif => {
            const item = document.createElement("div");
            item.classList.add("notification-item");
            item.innerHTML = `
                <strong>${notif.message}</strong><br>
                <small>${notif.time}</small>
            `;
            dropdown.appendChild(item);
        });

        badge.textContent = demoNotifications.length;
    }

    // Toggle dropdown visibility
    window.toggleNotifications = function () {
        const dropdown = document.getElementById("notificationDropdown");
        if (dropdown) {
            dropdown.style.display = dropdown.style.display === "block" ? "none" : "block";
        }
    };

    // Close dropdown if clicked outside
    document.addEventListener("click", function (event) {
        const bell = document.querySelector(".notification-bell");
        const dropdown = document.getElementById("notificationDropdown");

        if (!bell || !dropdown) return;

        if (!bell.contains(event.target)) {
            dropdown.style.display = "none";
        }
    });

    populateNotifications();

    /* ---------------------------------------------
       CLOCK
    --------------------------------------------- */
    function updateClock() {
        const dateElement = document.getElementById("dateDisplay");
        if (!dateElement) return;

        const now = new Date();
        const options = {
            weekday: "short",
            year: "numeric",
            month: "short",
            day: "numeric",
            hour: "2-digit",
            minute: "2-digit"
        };

        dateElement.textContent = now.toLocaleString("en-US", options);
    }

    updateClock();
    setInterval(updateClock, 1000);

}); // END DOMContentLoaded
