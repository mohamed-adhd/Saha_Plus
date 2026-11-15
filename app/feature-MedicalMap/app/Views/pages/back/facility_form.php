<?php
/** @var int $id                         0 = new facility */
/** @var array<string, mixed> $values    values to show in the fields */
/** @var array<string, string> $errors   error message by field */
/** @var array<string, string> $types */
/** @var list<string> $governorates */
/** @var array $pickerConfig             settings of the location map (bounds, tiles, governorate centres) */
/** @var string $csrf */

// Small helpers to keep the HTML below readable.
$value = fn (string $field): string => e((string)($values[$field] ?? ''));
$invalid = fn (string $field): string => isset($errors[$field]) ? ' is-invalid' : '';
$feedback = fn (string $field): string => isset($errors[$field]) ? '<div class="invalid-feedback d-block">' . e($errors[$field]) . '</div>' : '';
?>
<section class="content-area" aria-labelledby="page-title"><div class="container-fluid">
  <div class="mb-4">
    <a class="small" href="<?= e(base_url('index.php?page=admin-facilities')) ?>">← Back to facilities</a>
    <h1 id="page-title" class="h3 mt-2"><?= $id === 0 ? 'Add facility' : 'Edit facility' ?></h1>
    <p class="text-muted mb-0">Only locations inside Tunisia are accepted.</p>
  </div>

  <?php if ($errors !== []): ?>
    <div class="sp-alert sp-alert--danger mb-4" role="alert"><span aria-hidden="true">●</span><div>The facility was not saved. Please correct the fields marked below.</div></div>
  <?php endif; ?>

  <form class="card" method="post" action="<?= e(base_url('index.php?page=admin-facility-save')) ?>" novalidate>
    <div class="card-body">
      <input type="hidden" name="csrf" value="<?= e($csrf) ?>">
      <input type="hidden" name="id" value="<?= (int)$id ?>">

      <div class="row g-4">
        <div class="col-lg-6">
          <div class="mb-3">
            <label class="form-label" for="name">Name</label>
            <input class="form-control<?= $invalid('name') ?>" id="name" name="name" maxlength="120" required value="<?= $value('name') ?>">
            <?= $feedback('name') ?>
          </div>
          <div class="mb-3">
            <label class="form-label" for="type">Type</label>
            <select class="form-select<?= $invalid('type') ?>" id="type" name="type" required>
              <option value="">Choose…</option>
              <?php foreach ($types as $typeValue => $label): ?>
                <option value="<?= e($typeValue) ?>" <?= ($values['type'] ?? '') === $typeValue ? 'selected' : '' ?>><?= e($label) ?></option>
              <?php endforeach; ?>
            </select>
            <?= $feedback('type') ?>
          </div>
          <div class="mb-3">
            <label class="form-label" for="address">Address</label>
            <input class="form-control<?= $invalid('address') ?>" id="address" name="address" maxlength="200" required value="<?= $value('address') ?>">
            <?= $feedback('address') ?>
          </div>
          <div class="row g-3 mb-3">
            <div class="col-sm-6">
              <label class="form-label" for="governorate">Governorate</label>
              <select class="form-select<?= $invalid('governorate') ?>" id="governorate" name="governorate" required>
                <option value="">Choose…</option>
                <?php foreach ($governorates as $name): ?>
                  <option value="<?= e($name) ?>" <?= ($values['governorate'] ?? '') === $name ? 'selected' : '' ?>><?= e($name) ?></option>
                <?php endforeach; ?>
              </select>
              <?= $feedback('governorate') ?>
            </div>
            <div class="col-sm-6">
              <label class="form-label" for="city">City</label>
              <input class="form-control<?= $invalid('city') ?>" id="city" name="city" maxlength="80" required value="<?= $value('city') ?>">
              <?= $feedback('city') ?>
            </div>
          </div>
          <div class="mb-3">
            <label class="form-label" for="phone">Phone <span class="text-muted">(optional)</span></label>
            <input class="form-control<?= $invalid('phone') ?>" id="phone" name="phone" maxlength="20" placeholder="+216 71 234 567" value="<?= $value('phone') ?>">
            <?= $feedback('phone') ?>
          </div>
          <div class="form-check mb-2">
            <input class="form-check-input" type="checkbox" id="emergencyStatus" name="emergencyStatus" value="1" <?= !empty($values['emergencyStatus']) ? 'checked' : '' ?>>
            <label class="form-check-label" for="emergencyStatus">Has an emergency service</label>
          </div>
          <div class="form-check">
            <input class="form-check-input" type="checkbox" id="isDemo" name="isDemo" value="1" <?= !empty($values['isDemo']) ? 'checked' : '' ?>>
            <label class="form-check-label" for="isDemo">Demonstration data (not a verified real facility)</label>
          </div>
        </div>

        <div class="col-lg-6">
          <span class="form-label d-block">Location</span>
          <p class="text-muted small mb-2">Click on the map to place the facility, or type the coordinates.</p>
          <div id="locationPicker" class="medmap-picker<?= isset($errors['location']) ? ' is-invalid' : '' ?>"
               data-config="<?= e(json_encode($pickerConfig, JSON_UNESCAPED_UNICODE)) ?>"></div>
          <div class="row g-3 mt-1">
            <div class="col-6">
              <label class="form-label" for="latitude">Latitude</label>
              <input class="form-control<?= $invalid('location') ?>" id="latitude" name="latitude" inputmode="decimal" required value="<?= $value('latitude') ?>">
            </div>
            <div class="col-6">
              <label class="form-label" for="longitude">Longitude</label>
              <input class="form-control<?= $invalid('location') ?>" id="longitude" name="longitude" inputmode="decimal" required value="<?= $value('longitude') ?>">
            </div>
          </div>
          <?= $feedback('location') ?>
        </div>
      </div>
    </div>
    <div class="card-footer d-flex justify-content-end gap-2 bg-transparent">
      <a class="btn btn-outline-secondary" href="<?= e(base_url('index.php?page=admin-facilities')) ?>">Cancel</a>
      <button class="btn btn-primary" type="submit"><?= $id === 0 ? 'Add facility' : 'Save changes' ?></button>
    </div>
  </form>
</div></section>
