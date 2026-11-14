<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="<?= htmlspecialchars(asset('shared/assets/js/sidebar.js'), ENT_QUOTES, 'UTF-8') ?>" defer></script>
<script src="<?= htmlspecialchars(asset('shared/assets/js/ui.js'), ENT_QUOTES, 'UTF-8') ?>" defer></script>
<?php foreach (($scripts ?? []) as $script): ?>
<script src="<?= htmlspecialchars(asset($script), ENT_QUOTES, 'UTF-8') ?>" defer></script>
<?php endforeach; ?>
