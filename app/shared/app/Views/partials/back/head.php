<meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1"><meta name="description" content="Saha+ administration">
<title><?= e($title ?? 'Saha+ Admin') ?> | Saha+ Admin</title>
<link rel="preconnect" href="https://fonts.googleapis.com"><link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Fraunces:opsz,wght@9..144,500;9..144,600;9..144,700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
<?php foreach (['tokens.css', 'base.css', 'background.css', 'components.css', 'admin.css'] as $kitStylesheet): ?>
<link rel="stylesheet" href="<?= e(asset('shared/assets/css/' . $kitStylesheet)) ?>">
<?php endforeach; ?>
<?php foreach (($stylesheets ?? []) as $stylesheet): ?>
<link rel="stylesheet" href="<?= e(asset($stylesheet)) ?>">
<?php endforeach; ?>
