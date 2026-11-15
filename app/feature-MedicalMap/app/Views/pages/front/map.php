<?php
/** @var list<string> $specialties */
/** @var array<string, string> $types               type value => label */
/** @var array<string, array{0: float, 1: float}> $governorates   name => [lat, lng] */
/** @var array<string, list<string>> $citiesByGovernorate */
/** @var array $mapConfig                            content of config/map.php */
/** @var string $dataUrl                             URL of the JSON endpoint */

// Everything assets/js/map.js needs, passed once through a data attribute.
$jsConfig = [
    'dataUrl' => $dataUrl,
    'bounds' => $mapConfig['bounds'],
    'defaultLayer' => $mapConfig['default'],
    'providers' => $mapConfig['providers'],
    'governorates' => $governorates,
    'cities' => $citiesByGovernorate,
];
?>
<div class="container-fluid px-3 px-lg-5 py-4 py-lg-5 medmap">
  <header class="sp-page-header" data-sp-reveal>
    <div class="sp-page-header__copy">
      <p class="medmap-eyebrow">Tunisia · 24 governorates</p>
      <h1>Find care near you</h1>
      <p>Hospitals, clinics, donation centers and their doctors, across Tunisia.</p>
    </div>
  </header>

  <div class="sp-alert sp-alert--warn mb-4" role="note">
    <span aria-hidden="true">●</span>
    <div><strong>Demonstration data.</strong> The facilities and doctors shown here are fictional samples, not real establishments or practitioners.</div>
  </div>

  <!-- ===== Filters ===== -->
  <form class="sp-card medmap-filters mb-4" id="medmapFilters" autocomplete="off">
    <div class="sp-card__body">
      <div class="row g-3 align-items-end">
        <div class="col-md-6 col-xl-3 sp-field">
          <label for="filterSpecialty">Doctor specialty</label>
          <input class="sp-input" id="filterSpecialty" name="specialty" list="specialtyList" maxlength="60" placeholder="e.g. Dermatology">
          <datalist id="specialtyList">
            <?php foreach ($specialties as $specialty): ?><option value="<?= e($specialty) ?>"></option><?php endforeach; ?>
          </datalist>
        </div>
        <div class="col-md-6 col-xl-3 sp-field">
          <label for="filterGovernorate">Governorate</label>
          <select class="sp-select" id="filterGovernorate" name="governorate">
            <option value="">All 24 governorates</option>
            <?php foreach ($governorates as $name => $center): ?><option value="<?= e($name) ?>"><?= e($name) ?></option><?php endforeach; ?>
          </select>
        </div>
        <div class="col-md-6 col-xl-2 sp-field">
          <label for="filterCity">City</label>
          <select class="sp-select" id="filterCity" name="city" disabled>
            <option value="">All cities</option>
          </select>
        </div>
        <fieldset class="col-md-6 col-xl-4 medmap-types">
          <legend>Facility type</legend>
          <?php foreach ($types as $value => $label): ?>
            <label class="medmap-type medmap-type--<?= e($value) ?>">
              <input type="checkbox" name="types[]" value="<?= e($value) ?>">
              <span class="medmap-dot" aria-hidden="true"></span><?= e($label) ?>
            </label>
          <?php endforeach; ?>
        </fieldset>
      </div>
      <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mt-3">
        <p class="medmap-count mb-0" id="medmapCount" role="status" aria-live="polite">Loading facilities…</p>
        <button class="sp-button sp-button--outline" type="reset">Reset filters</button>
      </div>
    </div>
  </form>

  <!-- ===== Map + side panel ===== -->
  <div class="row g-4">
    <div class="col-lg-8">
      <div class="sp-card medmap-map-card">
        <div id="medmap" class="medmap-map" role="application" aria-label="Map of healthcare facilities in Tunisia"
             data-config="<?= e(json_encode($jsConfig, JSON_UNESCAPED_UNICODE)) ?>"></div>
      </div>
    </div>
    <div class="col-lg-4">
      <!-- Result list (default view of the panel) -->
      <section class="sp-card medmap-panel" id="medmapResults" aria-labelledby="medmapResultsTitle">
        <header class="sp-card__header" id="medmapResultsTitle">Results</header>
        <div class="sp-card__body medmap-panel__body"><ul class="medmap-list" id="medmapList"></ul></div>
      </section>
      <!-- Facility details (shown when a marker or a result is selected) -->
      <section class="sp-card medmap-panel" id="medmapDetails" aria-live="polite" hidden>
        <header class="sp-card__header d-flex justify-content-between align-items-center gap-2">
          <span>Facility details</span>
          <button class="sp-button sp-button--soft medmap-back" type="button" id="medmapBack">← Results</button>
        </header>
        <div class="sp-card__body medmap-panel__body" id="medmapDetailsBody"></div>
      </section>
    </div>
  </div>
</div>
