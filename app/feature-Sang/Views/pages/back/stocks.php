<?php $title = 'Stocks'; ?>
<h2>Gestion des stocks</h2>
<form method="post" action="<?= url('stock/save') ?>" class="card row">
  <select name="facility_id"><?php foreach ($facilities as $f): ?><option value="<?= (int)$f['id'] ?>"><?= e($f['name']) ?></option><?php endforeach; ?></select>
  <select name="bloodType"><?php foreach (BloodStock::TYPES as $t): ?><option><?= e($t) ?></option><?php endforeach; ?></select>
  <input type="number" name="quantity" min="0" placeholder="Quantité" required><button>Ajouter / mettre à jour</button></form>
<div class="scroll"><table><tr><th>Centre</th><th>Groupe</th><th>Quantité</th><th>Urgence</th><th>Mis à jour</th><th></th></tr>
<?php foreach ($stocks as $s): $id = (int)$s['id']; ?><tr><td><?= e($s['facility']) ?></td><td><?= e($s['bloodType']) ?></td>
<td><input form="s<?= $id ?>" type="number" name="quantity" min="0" value="<?= (int)$s['quantity'] ?>"></td>
<td><select form="s<?= $id ?>" name="urgencyLevel"><option value="">auto</option><?php foreach (['normal', 'low', 'critical'] as $l): ?><option <?= $l === $s['urgencyLevel'] ? 'selected' : '' ?>><?= $l ?></option><?php endforeach; ?></select></td>
<td><?= e($s['updatedAt']) ?></td>
<td><form id="s<?= $id ?>" method="post" action="<?= url('stock/save') ?>" style="display:inline"><input type="hidden" name="facility_id" value="<?= (int)$s['facility_id'] ?>"><input type="hidden" name="bloodType" value="<?= e($s['bloodType']) ?>"><button>💾</button></form>
<form method="post" action="<?= url('stock/delete') ?>" style="display:inline"><input type="hidden" name="id" value="<?= $id ?>"><button>✕</button></form></td></tr><?php endforeach; ?></table></div>
