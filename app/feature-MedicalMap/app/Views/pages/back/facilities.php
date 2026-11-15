<?php
/** @var list<Facility> $facilities */
/** @var array<int, int> $doctorCounts */
/** @var string $search */
/** @var string $type */
/** @var string $csrf */
?>
<section class="content-area" aria-labelledby="page-title"><div class="container-fluid">
  <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
    <div><h1 id="page-title" class="h3">Facilities</h1><p class="text-muted mb-0">Hospitals, clinics and donation centers located in Tunisia.</p></div>
    <a class="btn btn-primary" href="<?= e(base_url('index.php?page=admin-facility-form')) ?>">Add facility</a>
  </div>
  <div class="card"><div class="card-body">
    <!-- Search + filter: a normal GET form, the filtering is done by PHP -->
    <form class="d-flex flex-wrap gap-2 mb-3" method="get" action="<?= e(base_url('index.php')) ?>">
      <input type="hidden" name="page" value="admin-facilities">
      <input class="form-control admin-search" type="search" name="q" value="<?= e($search) ?>" placeholder="Name, city or governorate…" aria-label="Search facilities">
      <select class="form-select admin-filter" name="type" aria-label="Filter by type">
        <option value="">All types</option>
        <?php foreach (Facility::TYPES as $value => $label): ?>
          <option value="<?= e($value) ?>" <?= $type === $value ? 'selected' : '' ?>><?= e($label) ?></option>
        <?php endforeach; ?>
      </select>
      <button class="btn btn-outline-primary" type="submit">Filter</button>
      <?php if ($search !== '' || $type !== ''): ?>
        <a class="btn btn-outline-secondary" href="<?= e(base_url('index.php?page=admin-facilities')) ?>">Reset</a>
      <?php endif; ?>
    </form>
    <p class="text-muted small mb-2"><?= count($facilities) ?> facilit<?= count($facilities) === 1 ? 'y' : 'ies' ?></p>
    <div class="table-responsive">
      <table class="table align-middle">
        <thead><tr><th>#</th><th>Name</th><th>Type</th><th>City</th><th>Governorate</th><th>Coordinates</th><th>Emergency</th><th>Doctors</th><th class="text-end">Actions</th></tr></thead>
        <tbody>
        <?php foreach ($facilities as $facility): ?>
          <tr>
            <td><?= (int)$facility->id ?></td>
            <td class="fw-semibold"><?= e($facility->name) ?>
              <?php if ($facility->isDemo): ?><span class="sp-badge sp-badge--warn ms-1">Demo</span><?php endif; ?>
              <div class="text-muted small fw-normal"><?= e($facility->address) ?><?= $facility->phone !== null ? ' · ' . e($facility->phone) : '' ?></div></td>
            <td><span class="sp-pill"><?= e($facility->typeLabel()) ?></span></td>
            <td><?= e($facility->city) ?></td>
            <td><?= e($facility->governorate) ?></td>
            <td class="small text-nowrap"><?= e(number_format($facility->latitude, 4)) ?>, <?= e(number_format($facility->longitude, 4)) ?></td>
            <td><?= $facility->emergencyStatus ? '<span class="sp-badge sp-badge--danger">24/7</span>' : '<span class="text-muted">—</span>' ?></td>
            <td><?= (int)($doctorCounts[$facility->id] ?? 0) ?></td>
            <td class="text-end text-nowrap">
              <a class="btn btn-sm btn-outline-primary" href="<?= e(base_url('index.php?page=admin-facility-form&id=' . $facility->id)) ?>">Edit</a>
              <form class="d-inline" method="post" action="<?= e(base_url('index.php?page=admin-facility-delete')) ?>"
                    data-confirm="Delete the facility &quot;<?= e($facility->name) ?>&quot;?">
                <input type="hidden" name="csrf" value="<?= e($csrf) ?>">
                <input type="hidden" name="id" value="<?= (int)$facility->id ?>">
                <button class="btn btn-sm btn-outline-danger" type="submit">Delete</button>
              </form>
            </td>
          </tr>
        <?php endforeach; ?>
        <?php if ($facilities === []): ?>
          <tr><td colspan="9" class="text-center text-muted py-4">No facility found in Tunisia for this search.</td></tr>
        <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div></div>
</div></section>
