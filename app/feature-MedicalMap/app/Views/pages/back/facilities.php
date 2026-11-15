<?php
/** @var list<Facility> $facilities */
/** @var array<int, int> $doctorCounts */
?>
<section class="content-area" aria-labelledby="page-title"><div class="container-fluid">
  <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
    <div><h1 id="page-title" class="h3">Facilities</h1><p class="text-muted mb-0">Hospitals, clinics and donation centers located in Tunisia.</p></div>
    <span class="sp-badge sp-badge--warn">Demonstration data</span>
  </div>
  <div class="card"><div class="card-body">
    <div class="table-responsive">
      <table class="table align-middle">
        <thead><tr><th>#</th><th>Name</th><th>Type</th><th>City</th><th>Governorate</th><th>Coordinates</th><th>Emergency</th><th>Doctors</th></tr></thead>
        <tbody>
        <?php foreach ($facilities as $facility): ?>
          <tr>
            <td><?= (int)$facility->id ?></td>
            <td class="fw-semibold"><?= e($facility->name) ?><div class="text-muted small fw-normal"><?= e($facility->address) ?></div></td>
            <td><span class="sp-pill"><?= e($facility->typeLabel()) ?></span></td>
            <td><?= e($facility->city) ?></td>
            <td><?= e($facility->governorate) ?></td>
            <td class="small text-nowrap"><?= e(number_format($facility->latitude, 4)) ?>, <?= e(number_format($facility->longitude, 4)) ?></td>
            <td><?= $facility->emergencyStatus ? '<span class="sp-badge sp-badge--danger">24/7</span>' : '<span class="text-muted">—</span>' ?></td>
            <td><?= (int)($doctorCounts[$facility->id] ?? 0) ?></td>
          </tr>
        <?php endforeach; ?>
        <?php if ($facilities === []): ?>
          <tr><td colspan="8" class="text-center text-muted py-4">No facility registered yet.</td></tr>
        <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div></div>
</div></section>
