<?php $title = 'Carte des centres'; ?>
<section class="hero"><h1>Chaque don compte 🩸</h1>
<p>Trouvez un centre de don accessible près de vous, consultez les besoins urgents et réservez en quelques clics.</p>
<a class="btn" href="<?= url('book') ?>">Prendre rendez-vous</a></section>

<h2>Carte des centres de don</h2>
<div class="bar">
  <label><input type="checkbox" id="onlyAcc"> ♿ Accès PMR uniquement</label>
  <button id="near" type="button">📍 Le plus proche de moi</button>
  <span class="leg"><i style="background:#5fa97f"></i>Stock normal <i style="background:#e8a05a"></i>Besoin <i style="background:#c9475a"></i>Critique</span>
</div>
<div id="map"></div>
<div id="nearest"></div>

<h2>Besoins urgents</h2>
<?php foreach ($urgent as $s): ?>
  <div class="urg <?= e($s['urgencyLevel']) ?>"><b><?= e($s['bloodType']) ?></b><span><?= e($s['facility']) ?></span>
  <span>Stock : <?= (int)$s['quantity'] ?></span><span class="tag <?= e($s['urgencyLevel']) ?>"><?= e($s['urgencyLevel']) ?></span></div>
<?php endforeach; if (!$urgent) echo '<p>Aucun besoin urgent actuellement.</p>'; ?>

<h2>Matrice de compatibilité</h2>
<div class="scroll"><table><tr><th>Donneur ↓ / Receveur →</th><?php foreach ($comp as $d => $_): ?><th><?= e($d) ?></th><?php endforeach; ?></tr>
<?php foreach ($comp as $d => $rec): ?><tr><th><?= e($d) ?></th><?php foreach (array_keys($comp) as $r): ?><td><?= in_array($r, $rec) ? '✅' : '—' ?></td><?php endforeach; ?></tr><?php endforeach; ?></table></div>

<script>window.FACILITIES = <?= json_encode($facilities, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) ?>;</script>
<script src="<?= asset('js/map.js') ?>"></script>
