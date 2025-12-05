<?php

$lifetime = 1296000;

session_set_cookie_params([
    'lifetime' => $lifetime,
    'path' => '/',
    'domain' => '',
    'secure' => true,
    'httponly' => true
]);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$collectedValues = [];

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UNITY PGSRS - Padre Garcia Service Report System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link rel="stylesheet" href="uploads/this/landing.css">
</head>
<body>

<!-- NAVBAR -->
<nav class="navbar navbar-expand-lg fixed-top navbar-dark shadow-sm custom-navbar">
    <div class="container">
        <a class="navbar-brand fw-bold" href="login.php">UNITY: PGSRS</a>

        <button class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">

                <li class="nav-item">
                    <a class="nav-link" href="#home">Home</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="#about">About</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="#services">Report Categories</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="#how">How it works</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="#contact">Contact</a>
                </li>

                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">Portal</a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="./public/auth/login.php">Login</a></li>
                        <li><a class="dropdown-item" href="./public/auth/register.php">Register</a></li>
                    </ul>
                </li>

            </ul>
        </div>
    </div>
</nav>

<!-- Home SECTION -->
<section id="home" class="home-section">
    <div id="homeCarousel" class="carousel slide" data-bs-ride="carousel">
        <div class="carousel-inner">

            <div class="carousel-item active">
                <img src="uploads/this/pgsrsBG.jpg" class="carousel-img" alt="">
                <div class="carousel-caption d-flex flex-column justify-content-center align-items-center">
                    <h1 class="display-4 fw-bold text-shadow">Padre Garcia Service Report System</h1>
                    <p class="lead text-shadow">Seamless reporting for public service.</p>
                </div>
            </div>

            <div class="carousel-item">
                <img src="uploads/this/Batangas-PadreGarcia-Most-Holy-Rosary-Parish-Church-1024.jpg" class="carousel-img" alt="">
                <div class="carousel-caption d-flex flex-column justify-content-center align-items-center">
                    <h1 class="display-4 fw-bold text-shadow">Fast. Reliable. Transparent.</h1>
                    <p class="lead text-shadow">Your reports matter — together we build a better community.</p>
                </div>
            </div>

            <div class="carousel-item">
                <img src="uploads/this/pg.jpg" class="carousel-img" alt="">
                <div class="carousel-caption d-flex flex-column justify-content-center align-items-center">
                    <h1 class="display-4 fw-bold text-shadow">UNITY PGSRS</h1>
                    <p class="lead text-shadow">Official service reporting system of Padre Garcia.</p>
                </div>
            </div>

        </div>

        <button class="carousel-control-prev" type="button" data-bs-target="#homeCarousel" data-bs-slide="prev">
            <span class="carousel-control-prev-icon"></span>
        </button>

        <button class="carousel-control-next" type="button" data-bs-target="#homeCarousel" data-bs-slide="next">
            <span class="carousel-control-next-icon"></span>
        </button>
    </div>
</section>


<!-- ABOUT SECTION -->
<section id="about" class="section-padding bg-light">
    <div class="container">
        <h2 class="section-title text-center">About UNITY PGSRS</h2>

        <p class="lead text-center mx-auto about-text">
            The UNITY Padre Garcia Service Report System (PGSRS) is a digital platform designed to streamline
            reporting, documentation, and response tracking within the municipality. It ensures transparent,
            fast, and organized communication between departments and citizens.
        </p>
    </div>
</section>

<!-- SERVICES SECTION -->
<section id="services" class="section-padding bg-white">
    <div class="container">
        <h2 class="section-title text-center">Report Categories</h2>

        <p class="text-center lead mb-5">
            These are the official classifications for reports submitted within the Padre Garcia Service Report System.
        </p>

        <div class="row g-4 mt-4">

            <div class="col-md-4">
                <div class="service-card">
                    <i class="bi bi-heart-pulse service-icon text-danger"></i>
                    <h5>Medical</h5>
                    <p>Emergency medical concerns, injuries, accidents, or health-related incidents.</p>
                </div>
            </div>

            <div class="col-md-4">
                <div class="service-card">
                    <i class="bi bi-fire service-icon text-danger"></i>
                    <h5>Fire Rescue</h5>
                    <p>Fire incidents, smoke reports, and requests for fire response.</p>
                </div>
            </div>

            <div class="col-md-4">
                <div class="service-card">
                    <i class="bi bi-life-preserver service-icon text-primary"></i>
                    <h5>Search and Rescue</h5>
                    <p>Missing persons, flood rescue, trapped individuals, and emergency retrieval.</p>
                </div>
            </div>

            <div class="col-md-4">
                <div class="service-card">
                    <i class="bi bi-truck service-icon text-warning"></i>
                    <h5>Logistics</h5>
                    <p>Transportation assistance, equipment needs, or supply movement requests.</p>
                </div>
            </div>

            <div class="col-md-4">
                <div class="service-card">
                    <i class="bi bi-gear service-icon text-info"></i>
                    <h5>Technical Support</h5>
                    <p>System issues, technical malfunctions, or LGU system support requests.</p>
                </div>
            </div>

            <div class="col-md-4">
                <div class="service-card">
                    <i class="bi bi-three-dots service-icon text-secondary"></i>
                    <h5>Other</h5>
                    <p>Reports that do not fall under the main categories but still require LGU attention.</p>
                </div>
            </div>

        </div>
    </div>
</section>

<!-- HOW IT WORKS SECTION -->
<section id="how" class="section-padding bg-light">
    <div class="container">
        <h2 class="section-title text-center">How It Works</h2>

        <div class="row mt-5 text-center g-4">

            <div class="col-md-3">
                <div class="how-card">
                    <span class="how-number">1</span>
                    <h5>Create an Account</h5>
                    <p>Register using your mobile number and basic information.</p>
                </div>
            </div>

            <div class="col-md-3">
                <div class="how-card">
                    <span class="how-number">2</span>
                    <h5>Submit a Report</h5>
                    <p>Fill out the form, attach a photo, and choose a category.</p>
                </div>
            </div>

            <div class="col-md-3">
                <div class="how-card">
                    <span class="how-number">3</span>
                    <h5>LGU Reviews & Responds</h5>
                    <p>Your report is forwarded to the responsible department.</p>
                </div>
            </div>

            <div class="col-md-3">
                <div class="how-card">
                    <span class="how-number">4</span>
                    <h5>Track Progress</h5>
                    <p>Monitor updates until your report is resolved.</p>
                </div>
            </div>

        </div>
    </div>
</section>

<!-- CONTACT SECTION -->
<section id="contact" class="section-padding">
    <div class="container">
        <h2 class="section-title text-center">Contact Us</h2>

        <div class="row justify-content-center">
            <div class="col-md-6">
                <form class="contact-form shadow-sm p-4 rounded">

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Full Name</label>
                        <input type="text" class="form-control">
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Email Address</label>
                        <input type="email" class="form-control">
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Message</label>
                        <textarea class="form-control" rows="4"></textarea>
                    </div>

                    <button class="btn btn-primary w-100">Send Message</button>

                </form>
            </div>
        </div>
    </div>
</section>


<!-- FOOTER -->
<footer class="footer-bg text-white text-center py-3">
    <p class="mb-0">© 2025 UNITY PGSRS • Padre Garcia Service Report System</p>
</footer>

<script>
document.addEventListener("DOMContentLoaded", function () {

    const sections = document.querySelectorAll("section");
    const navLinks = document.querySelectorAll(".nav-link");

    function activateLink() {
        let scrollPos = window.scrollY + 150;

        sections.forEach((section) => {
            if (section.offsetTop <= scrollPos && 
                section.offsetTop + section.offsetHeight > scrollPos) {

                navLinks.forEach((link) => link.classList.remove("active"));

                let targetLink = document.querySelector('.nav-link[href="#' + section.id + '"]');
                if (targetLink) targetLink.classList.add("active");
            }
        });
    }

    window.addEventListener("scroll", activateLink);
    activateLink();
});
</script>



<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
