<?php
$adminLinks = [
    'admin-organs' => 'Organs',
    'admin-symptoms' => 'Symptoms',
    'admin-users' => 'Users',
];
?>
<aside class="admin-sidebar" aria-label="Back office navigation">
  <a class="admin-brand" href="<?= e(base_url('index.php?page=admin-organs')) ?>">Saha<span>+</span> <small>Admin</small></a>
  <nav>
    <p class="sidebar-label">BODY ATLAS</p>
    <?php foreach (['admin-organs', 'admin-symptoms'] as $key): ?>
      <a class="sidebar-link <?= $currentPage === $key ? 'active' : '' ?>" href="<?= e(base_url('index.php?page=' . $key)) ?>" <?= $currentPage === $key ? 'aria-current="page"' : '' ?>><?= e($adminLinks[$key]) ?></a>
    <?php endforeach; ?>
    <p class="sidebar-label mt-4">ACCOUNTS</p>
    <a class="sidebar-link <?= $currentPage === 'admin-users' ? 'active' : '' ?>" href="<?= e(base_url('index.php?page=admin-users')) ?>" <?= $currentPage === 'admin-users' ? 'aria-current="page"' : '' ?>>Users</a>
    <p class="sidebar-label mt-4">FRONT OFFICE</p>
    <a class="sidebar-link" href="<?= e(base_url('index.php?page=body-atlas')) ?>">Body Atlas ↗</a>
  </nav>
</aside>
