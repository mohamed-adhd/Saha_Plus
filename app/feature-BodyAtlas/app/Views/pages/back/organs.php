<?php
/** @var list<Organ> $organs */
$systems = ['vital' => 'Vital organs', 'muscular' => 'Muscular & Skeletal', 'nervous' => 'Mental & Nervous'];
?>
<section class="content-area" aria-labelledby="page-title"><div class="container-fluid">
  <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
    <div><h1 id="page-title" class="h3">Organs</h1><p class="text-muted mb-0">Organs displayed on the interactive Body Atlas.</p></div>
    <button class="btn btn-primary" type="button" data-add="organModal">Add organ</button>
  </div>
  <div class="card"><div class="card-body">
    <div class="d-flex flex-wrap gap-2 mb-3">
      <input class="form-control admin-search" type="search" placeholder="Search an organ…" aria-label="Search organs" data-search="organTable">
    </div>
    <div class="table-responsive">
      <table class="table align-middle" id="organTable" data-crud="organModal" data-label="organ">
        <thead><tr><th>#</th><th>Name</th><th>System</th><th>Specialty</th><th>Function</th><th class="text-end">Actions</th></tr></thead>
        <tbody>
        <?php foreach ($organs as $organ): ?>
          <tr data-row="<?= e(json_encode(['id' => $organ->id, 'name' => $organ->name, 'system' => $organ->system, 'specialty' => $organ->specialty, 'description' => $organ->description, 'function' => $organ->function])) ?>">
            <td data-col="id"><?= (int)$organ->id ?></td>
            <td data-col="name" class="fw-semibold"><?= e($organ->name) ?></td>
            <td data-col="system"><?= e($systems[$organ->system]) ?></td>
            <td data-col="specialty"><?= e($organ->specialty) ?></td>
            <td data-col="function" class="text-muted small"><?= e($organ->function) ?></td>
            <td class="text-end text-nowrap">
              <a class="btn btn-sm btn-outline-secondary" href="<?= e(base_url('index.php?page=organ&slug=' . $organ->slug)) ?>" target="_blank" rel="noopener">View</a>
              <button class="btn btn-sm btn-outline-primary" type="button" data-edit>Edit</button>
              <button class="btn btn-sm btn-outline-danger" type="button" data-delete>Delete</button>
            </td>
          </tr>
        <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div></div>
</div></section>

<div class="modal fade content-area" id="organModal" tabindex="-1" aria-labelledby="organModalTitle" aria-hidden="true"><div class="modal-dialog modal-lg">
  <form class="modal-content needs-validation" novalidate>
    <div class="modal-header"><h2 class="modal-title fs-5" id="organModalTitle" data-title-add="Add organ" data-title-edit="Edit organ">Add organ</h2><button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button></div>
    <div class="modal-body">
      <input type="hidden" name="id">
      <div class="row g-3">
        <div class="col-md-6"><label class="form-label" for="organName">Name</label><input class="form-control" id="organName" name="name" required minlength="2" maxlength="60"><div class="invalid-feedback">Enter a name (2–60 characters).</div></div>
        <div class="col-md-6"><label class="form-label" for="organSystem">System</label><select class="form-select" id="organSystem" name="system" required><?php foreach ($systems as $value => $label): ?><option value="<?= e($value) ?>"><?= e($label) ?></option><?php endforeach; ?></select></div>
        <div class="col-md-6"><label class="form-label" for="organSpecialty">Medical specialty</label><input class="form-control" id="organSpecialty" name="specialty" required placeholder="e.g. Cardiology"><div class="invalid-feedback">Enter the matching specialty.</div></div>
        <div class="col-12"><label class="form-label" for="organDescription">Description</label><textarea class="form-control" id="organDescription" name="description" rows="2" required></textarea><div class="invalid-feedback">Enter a description.</div></div>
        <div class="col-12"><label class="form-label" for="organFunction">Function</label><textarea class="form-control" id="organFunction" name="function" rows="2" required></textarea><div class="invalid-feedback">Explain what the organ does.</div></div>
      </div>
    </div>
    <div class="modal-footer"><button class="btn btn-secondary" type="button" data-bs-dismiss="modal">Cancel</button><button class="btn btn-primary" type="submit">Save</button></div>
  </form>
</div></div>
