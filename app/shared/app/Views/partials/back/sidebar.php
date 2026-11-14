<?php
/** @var string $currentPage */
// Each module can pass its own links in $adminMenu: [ 'SECTION' => [ pageKey => label ] ].
$adminMenu = $adminMenu ?? ['OVERVIEW' => ['dashboard' => 'Dashboard']];
?>
<aside class="admin-sidebar" aria-label="Back office navigation">
  <a class="admin-brand" href="<?= e(base_url('index.php?page=dashboard')) ?>">Saha<span>+</span> <small>Admin</small></a>
  <nav>
    <?php foreach ($adminMenu as $section => $links): ?>
      <p class="sidebar-label"><?= e($section) ?></p>
      <?php foreach ($links as $key => $label): ?>
        <a class="sidebar-link <?= $currentPage === $key ? 'active' : '' ?>" href="<?= e(base_url('index.php?page=' . $key)) ?>" <?= $currentPage === $key ? 'aria-current="page"' : '' ?>><?= e($label) ?></a>
      <?php endforeach; ?>
    <?php endforeach; ?>
    <p class="sidebar-label mt-4">FRONT OFFICE</p>
    <a class="sidebar-link" href="<?= e(base_url('index.php')) ?>">Open the site ↗</a>
  </nav>
</aside>
