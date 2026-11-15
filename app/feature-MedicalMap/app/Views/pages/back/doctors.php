<?php
/** @var list<Doctor> $doctors */
/** @var array<int, Facility> $facilitiesById */
/** @var list<string> $specialties */
/** @var string $search */
/** @var string $specialty */
/** @var string $csrf */
?>
<section class="content-area" aria-labelledby="page-title"><div class="container-fluid">
  <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
    <div><h1 id="page-title" class="h3">Doctors</h1><p class="text-muted mb-0">Each doctor belongs to exactly one facility.</p></div>
    <a class="btn btn-primary" href="<?= e(base_url('index.php?page=admin-doctor-form')) ?>">Add doctor</a>
  </div>
  <div class="card"><div class="card-body">
    <form class="d-flex flex-wrap gap-2 mb-3" method="get" action="<?= e(base_url('index.php')) ?>">
      <input type="hidden" name="page" value="admin-doctors">
      <input class="form-control admin-search" type="search" name="q" value="<?= e($search) ?>" placeholder="Name or e-mail…" aria-label="Search doctors">
      <select class="form-select admin-filter" name="specialty" aria-label="Filter by specialty">
        <option value="">All specialties</option>
        <?php foreach ($specialties as $name): ?>
          <option value="<?= e($name) ?>" <?= $specialty === $name ? 'selected' : '' ?>><?= e($name) ?></option>
        <?php endforeach; ?>
      </select>
      <button class="btn btn-outline-primary" type="submit">Filter</button>
      <?php if ($search !== '' || $specialty !== ''): ?>
        <a class="btn btn-outline-secondary" href="<?= e(base_url('index.php?page=admin-doctors')) ?>">Reset</a>
      <?php endif; ?>
    </form>
    <p class="text-muted small mb-2"><?= count($doctors) ?> doctor<?= count($doctors) === 1 ? '' : 's' ?></p>
    <div class="table-responsive">
      <table class="table align-middle">
        <thead><tr><th>#</th><th>Doctor</th><th>Specialty</th><th>Facility</th><th>Governorate</th><th>Contact</th><th class="text-end">Actions</th></tr></thead>
        <tbody>
        <?php foreach ($doctors as $doctor): $facility = $facilitiesById[$doctor->facilityId] ?? null; ?>
          <tr>
            <td><?= (int)$doctor->id ?></td>
            <td class="fw-semibold"><?= e($doctor->fullName()) ?></td>
            <td><span class="sp-pill"><?= e($doctor->specialty) ?></span></td>
            <td><?= e($facility?->name ?? '—') ?></td>
            <td><?= e($facility?->governorate ?? '—') ?></td>
            <td class="small"><?= e($doctor->email ?? '—') ?><?= $doctor->phone !== null ? '<br>' . e($doctor->phone) : '' ?></td>
            <td class="text-end text-nowrap">
              <a class="btn btn-sm btn-outline-primary" href="<?= e(base_url('index.php?page=admin-doctor-form&id=' . $doctor->id)) ?>">Edit</a>
              <form class="d-inline" method="post" action="<?= e(base_url('index.php?page=admin-doctor-delete')) ?>"
                    data-confirm="Delete <?= e($doctor->fullName()) ?>?">
                <input type="hidden" name="csrf" value="<?= e($csrf) ?>">
                <input type="hidden" name="id" value="<?= (int)$doctor->id ?>">
                <button class="btn btn-sm btn-outline-danger" type="submit">Delete</button>
              </form>
            </td>
          </tr>
        <?php endforeach; ?>
        <?php if ($doctors === []): ?>
          <tr><td colspan="7" class="text-center text-muted py-4">No doctor found for this search.</td></tr>
        <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div></div>
</div></section>
