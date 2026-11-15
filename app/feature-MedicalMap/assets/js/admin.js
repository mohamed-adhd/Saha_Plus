/*
 * Medical Map – Back Office script.
 *
 * 1. Asks for a confirmation before a delete form is sent.
 * 2. Facility form: a small map to choose the location by clicking.
 *
 * This only helps the administrator. The real checks are done again by PHP
 * (Facility::validate) and by the database, so they cannot be bypassed from the browser.
 */
(function () {
  'use strict';

  /* 1. Confirmation before deleting: <form data-confirm="Question?"> */
  document.querySelectorAll('form[data-confirm]').forEach(function (form) {
    form.addEventListener('submit', function (event) {
      if (!window.confirm(form.dataset.confirm)) { event.preventDefault(); }
    });
  });

  /* 2. Location picker of the facility form */
  var pickerElement = document.getElementById('locationPicker');
  if (!pickerElement || typeof L === 'undefined') { return; }

  var config = JSON.parse(pickerElement.dataset.config);
  var latitudeInput = document.getElementById('latitude');
  var longitudeInput = document.getElementById('longitude');
  var governorateSelect = document.getElementById('governorate');
  var tunisia = L.latLngBounds(config.bounds);

  var map = L.map(pickerElement, { maxBounds: tunisia.pad(0.12), maxBoundsViscosity: 1, minZoom: 5 });
  map.fitBounds(tunisia);
  L.tileLayer(config.provider.url, { attribution: config.provider.attribution, maxZoom: config.provider.maxZoom }).addTo(map);
  L.rectangle(tunisia, { color: '#1f4a35', weight: 1, fill: false, dashArray: '6 4', interactive: false }).addTo(map);

  var marker = null;

  // Puts the marker on the map (only when the point is inside Tunisia).
  function placeMarker(latitude, longitude, zoomIn) {
    var point = L.latLng(latitude, longitude);
    if (!tunisia.contains(point)) { return false; }
    if (marker === null) { marker = L.marker(point).addTo(map); } else { marker.setLatLng(point); }
    if (zoomIn) { map.setView(point, Math.max(map.getZoom(), 12)); }
    return true;
  }

  // Reads the two text fields ("36,8" is accepted like "36.8").
  function readInputs() {
    var latitude = parseFloat(latitudeInput.value.replace(',', '.'));
    var longitude = parseFloat(longitudeInput.value.replace(',', '.'));
    return isNaN(latitude) || isNaN(longitude) ? null : [latitude, longitude];
  }

  // Click on the map -> fill the fields.
  map.on('click', function (event) {
    if (!placeMarker(event.latlng.lat, event.latlng.lng, false)) { return; }
    latitudeInput.value = event.latlng.lat.toFixed(6);
    longitudeInput.value = event.latlng.lng.toFixed(6);
  });

  // Typing in the fields -> move the marker.
  [latitudeInput, longitudeInput].forEach(function (input) {
    input.addEventListener('change', function () {
      var point = readInputs();
      if (point) { placeMarker(point[0], point[1], true); }
    });
  });

  // Choosing a governorate -> show that region (the marker is not moved).
  governorateSelect.addEventListener('change', function () {
    var center = config.centers[governorateSelect.value];
    if (center) { map.setView(center, 10); }
  });

  // Editing an existing facility: show its current position.
  var initial = readInputs();
  if (initial) { placeMarker(initial[0], initial[1], true); }
})();
