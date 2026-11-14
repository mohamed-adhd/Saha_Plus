<?php
/** @var list<Symptom> $symptoms */
/** @var list<Organ> $organs */
$organNames = [];
foreach ($organs as $organ) { $organNames[$organ->id] = $organ->name; }
$severities = ['low' => 'Low', 'medium' => 'Medium', 'high' => 'High'];
?>
<section class="content-area" aria-labelledby="page-title"><div class="container-fluid">
  <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
    <div><h1 id="page-title" class="h3">Symptoms</h1><p class="text-muted mb-0">Symptom tags shown in each organ's symptom explorer.</p></div>
    <button class="btn btn-primary" type="button" data-add="symptomModal">Add symptom</button>
  </div>
  <div class="card"><div class="card-body">
    <div class="d-flex flex-wrap gap-2 mb-3">
      <input class="form-control admin-search" type="search" placeholder="Search a symptom…" aria-label="Search symptoms" data-search="symptomTable">
      <select class="form-select admin-filter" aria-label="Filter by organ" data-filter="symptomTable" data-filter-col="organId">
        <option value="">All organs</option>
        <?php foreach ($organNames as $id => $name): ?><option value="<?= (int)$id ?>"><?= e($name) ?></option><?php endforeach; ?>
      </select>
    </div>
    <div class="table-responsive">
      <table class="table align-middle" id="symptomTable" data-crud="symptomModal" data-label="symptom">
        <thead><tr><th>#</th><th>Symptom</th><th>Organ</th><th>Severity</th><th>Description</th><th class="text-end">Actions</th></tr></thead>
        <tbody>
        <?php foreach ($symptoms as $symptom): ?>
          <tr data-row="<?= e(json_encode(['id' => $symptom->id, 'name' => $symptom->name, 'organId' => (string)$symptom->organId, 'severity' => $symptom->severity, 'description' => $symptom->description])) ?>">
            <td data-col="id"><?= (int)$symptom->id ?></td>
            <td data-col="name" class="fw-semibold"><?= e($symptom->name) ?></td>
            <td data-col="organId"><?= e($organNames[$symptom->organId] ?? '—') ?></td>
            <td data-col="severity" data-badge><span class="severity-badge severity-badge--<?= e($symptom->severity) ?>"><?= e($severities[$symptom->severity]) ?></span></td>
            <td data-col="description" class="text-muted small"><?= e($symptom->description) ?></td>
            <td class="text-end text-nowrap">
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

<div class="modal fade content-area" id="symptomModal" tabindex="-1" aria-labelledby="symptomModalTitle" aria-hidden="true"><div class="modal-dialog">
  <form class="modal-content needs-validation" novalidate>
    <div class="modal-header"><h2 class="modal-title fs-5" id="symptomModalTitle" data-title-add="Add symptom" data-title-edit="Edit symptom">Add symptom</h2><button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button></div>
    <div class="modal-body">
      <input type="hidden" name="id">
      <div class="mb-3"><label class="form-label" for="symptomName">Name</label><input class="form-control" id="symptomName" name="name" required minlength="2" maxlength="60"><div class="invalid-feedback">Enter a name (2–60 characters).</div></div>
      <div class="mb-3"><label class="form-label" for="symptomOrgan">Organ</label><select class="form-select" id="symptomOrgan" name="organId" required><?php foreach ($organNames as $id => $name): ?><option value="<?= (int)$id ?>"><?= e($name) ?></option><?php endforeach; ?></select></div>
      <div class="mb-3"><label class="form-label" for="symptomSeverity">Severity</label><select class="form-select" id="symptomSeverity" name="severity" required><?php foreach ($severities as $value => $label): ?><option value="<?= e($value) ?>"><?= e($label) ?></option><?php endforeach; ?></select></div>
      <div><label class="form-label" for="symptomDescription">Description</label><textarea class="form-control" id="symptomDescription" name="description" rows="3" required></textarea><div class="invalid-feedback">Enter a short description.</div></div>
    </div>
    <div class="modal-footer"><button class="btn btn-secondary" type="button" data-bs-dismiss="modal">Cancel</button><button class="btn btn-primary" type="submit">Save</button></div>
  </form>
</div></div>
