<!-- ======== All JavaScript files linkup ======== -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="<?= htmlspecialchars(asset('ui-kit/assets/js/sidebar.js'), ENT_QUOTES, 'UTF-8') ?>" defer></script>
<script src="<?= htmlspecialchars(asset('ui-kit/assets/js/ui.js'), ENT_QUOTES, 'UTF-8') ?>" defer></script>
<script src="<?= htmlspecialchars(asset('app/Views/assets/js/shell.js'), ENT_QUOTES, 'UTF-8') ?>" defer></script>
<script src="<?= htmlspecialchars(asset('app/Views/assets/js/assistant.js'), ENT_QUOTES, 'UTF-8') ?>" defer></script>

<?php if (!empty($extraScripts)): ?>
<?= $extraScripts ?>
<?php endif; ?>
</body>
</html>
