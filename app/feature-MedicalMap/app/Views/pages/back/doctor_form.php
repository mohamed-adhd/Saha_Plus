<?php
/** @var int $id                         0 = new doctor */
/** @var array<string, mixed> $values */
/** @var array<string, string> $errors */
/** @var list<string> $specialties */
/** @var list<Facility> $facilities */
/** @var string $csrf */

$value = fn (string $field): string => e((string)($values[$field] ?? ''));
$invalid = fn (string $field): string => isset($errors[$field]) ? ' is-invalid' : '';
$feedback = fn (string $field): string => isset($errors[$field]) ? '<div class="invalid-feedback d-block">' . e($errors[$field]) . '</div>' : '';
?>
<section class="content-area" aria-labelledby="page-title"><div class="container-fluid">
  <div class="mb-4">
    <a class="small" href="<?= e(base_url('index.php?page=admin-doctors')) ?>">← Back to doctors</a>
    <h1 id="page-title" class="h3 mt-2"><?= $id === 0 ? 'Add doctor' : 'Edit doctor' ?></h1>
    <p class="text-muted mb-0">A doctor must be attached to one existing facility.</p>
  </div>

  <?php if ($errors !== []): ?>
    <div class="sp-alert sp-alert--danger mb-4" role="alert"><span aria-hidden="true">●</span><div>The doctor was not saved. Please correct the fields marked below.</div></div>
  <?php endif; ?>

  <form class="card medmap-form-narrow" method="post" action="<?= e(base_url('index.php?page=admin-doctor-save')) ?>" novalidate>
    <div class="card-body">
      <input type="hidden" name="csrf" value="<?= e($csrf) ?>">
      <input type="hidden" name="id" value="<?= (int)$id ?>">

      <div class="row g-3 mb-3">
        <div class="col-sm-6">
          <label class="form-label" for="firstName">First name</label>
          <input class="form-control<?= $invalid('firstName') ?>" id="firstName" name="firstName" maxlength="60" required value="<?= $value('firstName') ?>">
          <?= $feedback('firstName') ?>
        </div>
        <div class="col-sm-6">
          <label class="form-label" for="lastName">Last name</label>
          <input class="form-control<?= $invalid('lastName') ?>" id="lastName" name="lastName" maxlength="60" required value="<?= $value('lastName') ?>">
          <?= $feedback('lastName') ?>
        </div>
      </div>
      <div class="mb-3">
        <label class="form-label" for="specialty">Specialty</label>
        <select class="form-select<?= $invalid('specialty') ?>" id="specialty" name="specialty" required>
          <option value="">Choose…</option>
          <?php foreach ($specialties as $name): ?>
            <option value="<?= e($name) ?>" <?= ($values['specialty'] ?? '') === $name ? 'selected' : '' ?>><?= e($name) ?></option>
          <?php endforeach; ?>
        </select>
        <?= $feedback('specialty') ?>
      </div>
      <div class="mb-3">
        <label class="form-label" for="facilityId">Facility</label>
        <select class="form-select<?= $invalid('facilityId') ?>" id="facilityId" name="facilityId" required>
          <option value="">Choose…</option>
          <?php foreach ($facilities as $facility): ?>
            <option value="<?= (int)$facility->id ?>" <?= (int)($values['facilityId'] ?? 0) === $facility->id ? 'selected' : '' ?>>
              <?= e($facility->name) ?> (<?= e($facility->governorate) ?>)
            </option>
          <?php endforeach; ?>
        </select>
        <?= $feedback('facilityId') ?>
      </div>
      <div class="row g-3">
        <div class="col-sm-6">
          <label class="form-label" for="email">E-mail <span class="text-muted">(optional)</span></label>
          <input class="form-control<?= $invalid('email') ?>" id="email" name="email" type="email" maxlength="120" value="<?= $value('email') ?>">
          <?= $feedback('email') ?>
        </div>
        <div class="col-sm-6">
          <label class="form-label" for="phone">Phone <span class="text-muted">(optional)</span></label>
          <input class="form-control<?= $invalid('phone') ?>" id="phone" name="phone" maxlength="20" placeholder="+216 71 234 567" value="<?= $value('phone') ?>">
          <?= $feedback('phone') ?>
        </div>
      </div>
    </div>
    <div class="card-footer d-flex justify-content-end gap-2 bg-transparent">
      <a class="btn btn-outline-secondary" href="<?= e(base_url('index.php?page=admin-doctors')) ?>">Cancel</a>
      <button class="btn btn-primary" type="submit"><?= $id === 0 ? 'Add doctor' : 'Save changes' ?></button>
    </div>
  </form>
</div></section>
