<!DOCTYPE html><html lang="fr"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title><?= e($title ?? "Don de sang") ?></title>
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"><script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<link rel="stylesheet" href="<?= shared("css/theme.css") ?>"><link rel="stylesheet" href="<?= asset("css/app.css") ?>"></head><body>
<header><b>🛠️ Administration</b><nav><a href="<?= url("admin") ?>">Stocks</a><a href="<?= url("admin/appointments") ?>">Rendez-vous</a><a href="<?= url("home") ?>">Site</a><a href="<?= url("logout") ?>">Déconnexion</a></nav></header>
<main><?php if (!empty($_SESSION["flash"])): [$m,$t]=$_SESSION["flash"]; unset($_SESSION["flash"]); ?><div class="flash <?= e($t) ?>"><?= e($m) ?></div><?php endif; ?>
<?= $content ?></main><script src="<?= asset("js/app.js") ?>"></script></body></html>
