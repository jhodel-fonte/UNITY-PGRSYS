<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>File Report | Unity Padre Garcia</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <link rel="stylesheet" href="assets/user.css">
</head>
<body>

<?php include 'sidebar.php'; ?>

<main class="user-main">

    <div class="report-card shadow-sm">
        <h1 class="page-title">File a Report</h1>

        <form method="POST" enctype="multipart/form-data">

            <!-- NAME -->
            <div class="form-group mb-3">
                <label>Name</label>
                <input type="text" value="Juan Dela Cruz" readonly>
            </div>

            <!-- REPORT TYPE -->
            <div class="form-group mb-3">
                <label>Category</label>
                <select required>
                    <option value="">Select Type</option>
                    <option>Fire</option>
                    <option>Rescue</option>
                    <option>Electrical Hazzard</option>
                    <option>Infrastructure</option>
                    <option>Utilities & Public Services</option>
                    <option>Peace & Order</option>
                    <option>Health & Sanitation</option>
                    <option>Environmental Concerns</option>
                    <option>Others</option>
                </select>
            </div>

            <!-- DESCRIPTION -->
            <div class="form-group mb-3">
                <label>Description</label>
                <textarea rows="4" placeholder="Describe your report..." required></textarea>
            </div>

            <!-- PHOTO -->
            <div class="form-group mb-3">
                <label>Upload Photo (optional)</label>
                <input type="file" accept="image/*">
            </div>

            <!-- LOCATION -->
            <div class="form-group mb-3">
                <label>Location</label>
                <input type="text" id="location" readonly required>
            </div>

            <input type="hidden" id="latitude">
            <input type="hidden" id="longitude">

            <!-- MAP CARD -->
            <div id="map" class="map-card"></div>

            <button class="submit-btn" type="submit">Submit Report</button>

        </form>
    </div>
    </div>
</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="/assets/user.js"></script>
<script src="assets/reportmap.js"></script>
</body>
</html>
