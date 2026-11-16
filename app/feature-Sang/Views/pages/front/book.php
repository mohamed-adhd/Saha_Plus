<?php $title = 'Rendez-vous'; ?>
<div class="grid">
<form id="elig" class="card"><h2>Vérifier mon éligibilité</h2>
  <label>Âge <input type="number" name="age" min="1" required></label>
  <label>Poids (kg) <input type="number" name="weight" min="1" required></label>
  <label>Dernier don (optionnel) <input type="date" name="last"></label>
  <label><input type="checkbox" name="healthy"> Je suis en bonne santé aujourd’hui</label>
  <button>Vérifier</button><div id="eligRes"></div></form>

<form method="post" action="<?= url('book') ?>" class="card"><h2>Réserver un don</h2>
  <label><input type="checkbox" id="anon" name="anonymous" value="1"> Rester anonyme</label>
  <label>Nom complet <input name="name" id="name" required></label>
  <label>Coordonnées (téléphone ou e-mail) <input name="contact" id="contact" required></label>
  <label>Centre <select name="facility_id"><?php foreach ($facilities as $f): ?><option value="<?= (int)$f['id'] ?>" <?= $sel == $f['id'] ? 'selected' : '' ?>><?= e($f['name']) ?> – <?= e($f['city']) ?></option><?php endforeach; ?></select></label>
  <label>Groupe sanguin <select name="bloodType"><?php foreach (BloodStock::TYPES as $t): ?><option><?= e($t) ?></option><?php endforeach; ?></select></label>
  <label>Date <input type="date" name="date" min="<?= date('Y-m-d') ?>" required></label>
  <button>Réserver</button></form>
</div>
<h2>Mes rendez-vous</h2>
<div class="scroll"><table><tr><th>Date</th><th>Centre</th><th>Groupe</th><th>Nom</th><th>Statut</th><th></th></tr>
<?php foreach ($mine as $a): ?><tr><td><?= e($a['date']) ?></td><td><?= e($a['facility']) ?></td><td><?= e($a['bloodType']) ?></td>
<td><?= $a['is_anonymous'] ? 'Anonyme' : e($a['name']) ?></td><td><?= e($a['status']) ?></td>
<td><?php if (in_array($a['status'], ['pending', 'confirmed'])): ?><form method="post" action="<?= url('cancel') ?>"><input type="hidden" name="id" value="<?= (int)$a['id'] ?>"><button>Annuler</button></form><?php endif; ?></td></tr>
<?php endforeach; if (!$mine) echo '<tr><td colspan="6">Aucun rendez-vous.</td></tr>'; ?></table></div>
