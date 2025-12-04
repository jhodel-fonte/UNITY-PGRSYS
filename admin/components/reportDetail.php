<?php

$imageDir = __DIR__ .'../../../assets/uploads/reports';

if (!isset($reports) || !is_array($reports)) {
    // Assuming dataProcess.php returns the reports array
    // Note: If this file is executed directly, the include path might need adjustment
    // For this context, we'll keep the logic as provided.
    $reports = include __DIR__ . '/../dataProcess.php'; 
}

if (!is_array($reports) || (isset($reports['success']) && $reports['success'] === false)) {
    return;
}

foreach ($reports as $report):
    $reportId = htmlspecialchars($report['id'] ?? '');
    $title = htmlspecialchars($report['name'] ?? 'Untitled Report');
    $category = htmlspecialchars($report['report_type'] ?? $report['ml_category'] ?? 'Unknown');
    $description = htmlspecialchars($report['description'] ?? 'No description provided.');
    $location = htmlspecialchars($report['location'] ?? 'Location not specified.');
    $userFullName = htmlspecialchars(($report['firstName'] ?? '') . ' ' . ($report['lastName'] ?? ''));
    $userId = htmlspecialchars($report['user_id'] ?? 'N/A');
    $status = htmlspecialchars($report['status'] ?? 'Unknown');
    $createdAt = $report['created_at'] ?? null;
    if ($createdAt && strpos($createdAt, 'T') !== false) {
        // Convert ISO 8601 string to a readable format
        $createdAt = date('Y-m-d H:i:s', strtotime($createdAt));
    }
    $createdAt = htmlspecialchars($createdAt ?? 'N/A');
    $lat = $report['latitude'] ?? null;
    $lng = $report['longitude'] ?? null;
    $images = $report['images'] ?? [];
?>

<div class="modal fade" id="reportModal<?= $reportId ?>" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <!-- Light Theme: modal-content default background is white/light -->
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-primary">Report Details</h5>
                <!-- Default close button is fine for light theme -->
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row mb-4">
                    <!-- General Info Card -->
                    <div class="col-md-6 border-end">
                        <h6 class="text-primary mb-3">Report Information</h6>
                        <dl class="row mb-0 small">
                            <dt class="col-4 text-muted fw-normal">ID:</dt>
                            <dd class="col-8 fw-bold text-break"><?= $reportId ?></dd>

                            <dt class="col-4 text-muted fw-normal">Title:</dt>
                            <dd class="col-8 fw-bold"><?= $title ?></dd>

                            <dt class="col-4 text-muted fw-normal">Category:</dt>
                            <dd class="col-8"><?= $category ?></dd>

                            <dt class="col-4 text-muted fw-normal">Status:</dt>
                            <dd class="col-8">
                                <span class="badge rounded-pill bg-<?= match($status){
                                    'Approved'=>'success',
                                    'Pending'=>'warning',
                                    'Ongoing'=>'info',
                                    'Resolved'=>'primary',
                                    default=>'secondary'
                                } ?>"><?= $status ?></span>
                            </dd>
                        </dl>
                    </div>

                    <!-- Submission Info Card -->
                    <div class="col-md-6">
                        <h6 class="text-primary mb-3">Submission Details</h6>
                        <dl class="row mb-0 small">
                            <dt class="col-5 text-muted fw-normal">Submitted By:</dt>
                            <dd class="col-7"><?= trim($userFullName) ?: 'Unknown' ?></dd>

                            <dt class="col-5 text-muted fw-normal">User ID:</dt>
                            <dd class="col-7 text-break"><?= $userId ?></dd>

                            <dt class="col-5 text-muted fw-normal">Date:</dt>
                            <dd class="col-7"><?= $createdAt ?></dd>
                            
                            <?php if (!empty($report['ml_category'])): ?>
                                <dt class="col-5 text-muted fw-normal">ML Category:</dt>
                                <dd class="col-7"><?= htmlspecialchars($report['ml_category']) ?></dd>
                            <?php endif; ?>
                        </dl>
                    </div>
                </div>

                <hr>

                <!-- Description Section -->
                <h6 class="text-primary mb-2">Description</h6>
                <div class="alert alert-light border p-3 mb-4 small">
                    <?= nl2br($description) ?>
                </div>

                <!-- Location Map Section -->
                <h6 class="text-primary mb-2">Location: <span class="fw-normal text-secondary small"><?= $location ?></span></h6>
                <?php if ($lat && $lng): ?>
                    <div class="rounded overflow-hidden mb-4 shadow-sm" style="height: 300px; border: 1px solid #ccc;">
                        <iframe
                            width="100%"
                            height="100%"
                            style="border:0;"
                            loading="lazy"
                            allowfullscreen
                            src="https://www.google.com/maps?q=<?= urlencode($lat) ?>,<?= urlencode($lng) ?>&z=14&output=embed">
                        </iframe>
                    </div>
                <?php else: ?>
                    <div class="rounded overflow-hidden mb-4 bg-light border p-5 text-center" style="height: 300px;">
                        <i class="fas fa-map-marker-alt fa-2x text-muted mb-2"></i>
                        <p class="text-muted">No map location available.</p>
                    </div>
                <?php endif; ?>

                <!-- Images Section -->
                <h6 class="text-primary mb-3">Report Images</h6>
                <?php if (!empty($images)): ?>
                    <div class="row g-3">
                        <?php foreach ($images as $img):
                            // Prioritize 'photo' then 'image_path'
                            $imagePath = $img['photo'] ?? $img['image_path'] ?? null;
                            if (!$imagePath) {
                                continue;
                            }
                            // Construct the full path (assuming relative path is correct)
                            $fullImageUrl = '../assets/uploads/reports/' . htmlspecialchars($imagePath);
                        ?>
                            <div class="col-4 col-md-3">
                                <div class="card p-1 shadow-sm h-100">
                                    <img src="<?= $fullImageUrl ?>"
                                        class="img-fluid rounded"
                                        style="height: 100px; width: 100%; object-fit: cover; cursor: pointer;"
                                        alt="Report image"
                                        onclick="window.open(this.src, '_blank')"
                                        onerror="this.style.display='none'; this.closest('.col-4').innerHTML='<div class=&quot;text-center text-danger small p-2&quot;>Image failed to load</div>';">
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div class="text-center mb-3 p-3 border rounded bg-light">
                        <i class="far fa-image fa-2x text-muted mb-2"></i>
                        <p class="text-muted small mb-0">No images provided for this report.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<?php endforeach; ?>