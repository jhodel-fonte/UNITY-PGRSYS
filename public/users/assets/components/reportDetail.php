<?php

$imageDir = __DIR__ .'../../../assets/uploads/reports';

if (!isset($reports) || !is_array($reports)) {
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
        $createdAt = date('Y-m-d H:i:s', strtotime($createdAt));
    }
    $createdAt = htmlspecialchars($createdAt ?? 'N/A');
    $lat = $report['latitude'] ?? null;
    $lng = $report['longitude'] ?? null;
    $images = $report['images'] ?? [];
?>

<div class="modal fade" id="reportModal<?= $reportId ?>" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content bg-dark text-light">
            <div class="modal-header border-secondary">
                <h5 class="modal-title text-neon">Report Details</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <p><strong>Report ID:</strong> <?= $reportId ?></p>
                        <p><strong>Title:</strong> <?= $title ?></p>
                        <p><strong>Category:</strong> <?= $category ?></p>
                        <p><strong>Status:</strong>
                            <span class="badge bg-<?= match($status){
                                'Approved'=>'success',
                                'Pending'=>'warning',
                                'Ongoing'=>'info',
                                'Resolved'=>'primary',
                                default=>'secondary'
                            } ?>"><?= $status ?></span>
                        </p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>Submitted By:</strong> <?= trim($userFullName) ?: 'Unknown' ?></p>
                        <p><strong>User ID:</strong> <?= $userId ?></p>
                        <p><strong>Date Submitted:</strong> <?= $createdAt ?></p>
                        <?php if (!empty($report['ml_category'])): ?>
                            <p><strong>ML Category:</strong> <?= htmlspecialchars($report['ml_category']) ?></p>
                        <?php endif; ?>
                    </div>
                </div>
                <hr class="border-secondary">
                <p><strong>Description:</strong></p>
                <p class="mb-3"><?= $description ?></p>

                <p><strong>Location:</strong> <?= $location ?></p>

                <?php if ($lat && $lng): ?>
                    <div class="rounded overflow-hidden mb-3" style="height: 300px;">
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
                    <div class="rounded overflow-hidden mb-3" style="height: 300px;">
                        <p class="text-center text-muted p-3">No map location available.</p>
                    </div>
                <?php endif; ?>

                <?php if (!empty($images)): ?>
                    <p><strong>Report Images:</strong></p>
                    <div class="row mb-3">
                        <?php foreach ($images as $img):
                            $imagePath = $img['photo'] ?? $img['image_path'] ?? null;
                            if (!$imagePath) {
                                continue;
                            }
                        ?>
                            <div class="col-md-4 mb-3">
                                <div class="text-center">
                                    <img src="../assets/uploads/reports/<?= htmlspecialchars($imagePath) ?>"
                                         class="img-fluid rounded shadow"
                                         style="max-height: 200px; width: 100%; object-fit: cover; cursor: pointer;"
                                         alt="Report image"
                                         onclick="window.open(this.src, '_blank')"
                                         onerror="this.style.display='none';">
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div class="text-center mb-3">
                        <p class="text-muted">No images provided</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<?php endforeach; ?>