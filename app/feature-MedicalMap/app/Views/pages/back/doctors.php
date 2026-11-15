<?php
/** @var list<Doctor> $doctors */
/** @var array<int, Facility> $facilitiesById */
?>
<section class="content-area" aria-labelledby="page-title"><div class="container-fluid">
  <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
    <div><h1 id="page-title" class="h3">Doctors</h1><p class="text-muted mb-0">Each doctor belongs to exactly one facility.</p></div>
    <span class="sp-badge sp-badge--warn">Demonstration data</span>
  </div>
  <div class="card"><div class="card-body">
    <div class="table-responsive">
      <table class="table align-middle">
        <thead><tr><th>#</th><th>Doctor</th><th>Specialty</th><th>Facility</th><th>Governorate</th><th>Email</th><th>Phone</th></tr></thead>
        <tbody>
        <?php foreach ($doctors as $doctor): $facility = $facilitiesById[$doctor->facilityId] ?? null; ?>
          <tr>
            <td><?= (int)$doctor->id ?></td>
            <td class="fw-semibold"><?= e($doctor->fullName()) ?></td>
            <td><span class="sp-pill"><?= e($doctor->specialty) ?></span></td>
            <td><?= e($facility?->name ?? '—') ?></td>
            <td><?= e($facility?->governorate ?? '—') ?></td>
            <td class="small"><?= e($doctor->email ?? '—') ?></td>
            <td class="small"><?= e($doctor->phone ?? '—') ?></td>
          </tr>
        <?php endforeach; ?>
        <?php if ($doctors === []): ?>
          <tr><td colspan="7" class="text-center text-muted py-4">No doctor registered yet.</td></tr>
        <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div></div>
</div></section>
