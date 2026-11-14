<?php
/** @var list<Module> $modules */
$ready = count(array_filter($modules, fn (Module $m) => $m->status === 'ready'));
$entities = array_sum(array_map(fn (Module $m) => count($m->entities), $modules));
?>
<section class="content-area" aria-labelledby="page-title"><div class="container-fluid">
  <div class="mb-4"><h1 id="page-title" class="h3">Dashboard</h1><p class="text-muted mb-0">Overview of the Saha+ modules.</p></div>
  <div class="row g-3 mb-4">
    <div class="col-sm-4"><div class="card"><div class="card-body"><p class="text-muted small mb-1">Modules</p><p class="h3 mb-0"><?= count($modules) ?></p></div></div></div>
    <div class="col-sm-4"><div class="card"><div class="card-body"><p class="text-muted small mb-1">Prototypes ready</p><p class="h3 mb-0"><?= $ready ?></p></div></div></div>
    <div class="col-sm-4"><div class="card"><div class="card-body"><p class="text-muted small mb-1">Entities</p><p class="h3 mb-0"><?= $entities ?></p></div></div></div>
  </div>
  <div class="card"><div class="card-body"><div class="table-responsive">
    <table class="table align-middle">
      <thead><tr><th>Module</th><th>Owner</th><th>Entities</th><th>Folder</th><th>Status</th><th class="text-end">Back Office</th></tr></thead>
      <tbody>
      <?php foreach ($modules as $module): ?>
        <tr>
          <td class="fw-semibold"><?= e($module->name) ?></td>
          <td><?= e($module->owner) ?></td>
          <td><?= e(implode(', ', $module->entities)) ?></td>
          <td><code>app/<?= e($module->folder) ?></code></td>
          <td><span class="status-badge status-badge--<?= e($module->status) ?>"><?= e($module->statusLabel()) ?></span></td>
          <td class="text-end">
            <?php if ($module->isAvailable()): ?><a class="btn btn-sm btn-outline-primary" href="<?= e($module->url()) ?>">Open</a><?php else: ?><span class="text-muted small">—</span><?php endif; ?>
          </td>
        </tr>
      <?php endforeach; ?>
      </tbody>
    </table>
  </div></div></div>
</div></section>
