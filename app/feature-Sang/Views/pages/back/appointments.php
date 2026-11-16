<?php $title = 'Rendez-vous'; ?>
<h2>Gestion des rendez-vous</h2>
<div class="scroll"><table><tr><th>Date</th><th>Donneur</th><th>Coordonnées</th><th>Centre</th><th>Groupe</th><th>Statut</th></tr>
<?php foreach ($appts as $a): $id = (int)$a['id']; ?><tr><td><?= e($a['date']) ?></td><td><?= $a['is_anonymous'] ? 'Anonyme' : e($a['name']) ?></td>
<td><?= $a['is_anonymous'] ? '—' : e($a['contact']) ?></td><td><?= e($a['facility']) ?></td><td><?= e($a['bloodType']) ?></td>
<td><form method="post" action="<?= url('admin/appointments/status') ?>"><input type="hidden" name="id" value="<?= $id ?>">
<select name="status" onchange="this.form.submit()"><?php foreach (DonationAppointment::STATUS as $s): ?><option <?= $s === $a['status'] ? 'selected' : '' ?>><?= $s ?></option><?php endforeach; ?></select></form></td></tr>
<?php endforeach; if (!$appts) echo '<tr><td colspan="6">Aucun rendez-vous.</td></tr>'; ?></table></div>
