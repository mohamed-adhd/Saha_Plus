/*
 * Medical Map – Front Office script.
 *
 * 1. Creates the Leaflet map, locked on Tunisia.
 * 2. Reads the filters of the form and asks PHP for the matching facilities
 *    (index.php?page=map-data ...), which answers in JSON.
 * 3. Draws one marker per facility, the result list and the details panel.
 *
 * The filtering itself is done by PHP + MySQL (Facility::search), not here.
 */
(function () {
  'use strict';

  var mapElement = document.getElementById('medmap');
  if (!mapElement || typeof L === 'undefined') { return; }

  var config = JSON.parse(mapElement.dataset.config);
  var form = document.getElementById('medmapFilters');
  var countElement = document.getElementById('medmapCount');
  var listElement = document.getElementById('medmapList');
  var resultsPanel = document.getElementById('medmapResults');
  var detailsPanel = document.getElementById('medmapDetails');
  var detailsBody = document.getElementById('medmapDetailsBody');
  var citySelect = document.getElementById('filterCity');
  var governorateSelect = document.getElementById('filterGovernorate');

  var TYPE_LETTERS = { hospital: 'H', clinic: 'C', donation_center: 'D' };

  /* ------------------------------------------------------------------
   * 1. The map, locked on Tunisia
   * ------------------------------------------------------------------ */
  var tunisia = L.latLngBounds(config.bounds);
  var map = L.map(mapElement, {
    maxBounds: tunisia.pad(0.12),     // the visitor cannot drag the map away from Tunisia
    maxBoundsViscosity: 1,
    minZoom: 6,
    zoomSnap: 0.25
  });
  map.fitBounds(tunisia);
  map.setMinZoom(map.getZoom());      // cannot zoom out further than "all of Tunisia"

  // Background layers come from config/map.php, so the provider can be changed without touching this file.
  var layers = {};
  Object.keys(config.providers).forEach(function (key) {
    var provider = config.providers[key];
    layers[provider.label] = L.tileLayer(provider.url, { attribution: provider.attribution, maxZoom: provider.maxZoom });
  });
  var defaultProvider = config.providers[config.defaultLayer] || config.providers.streets;
  var activeLayer = layers[defaultProvider.label].addTo(map);
  if (Object.keys(layers).length > 1) { L.control.layers(layers, null, { position: 'topright' }).addTo(map); }

  // If the default background cannot be loaded, fall back to the street map once.
  var streets = config.providers.streets ? layers[config.providers.streets.label] : null;
  activeLayer.once('tileerror', function () {
    if (streets && activeLayer !== streets && map.hasLayer(activeLayer)) {
      map.removeLayer(activeLayer);
      streets.addTo(map);
    }
  });

  // Darken everything outside the Tunisia rectangle (a big polygon with a hole).
  L.polygon([
    [[-85, -180], [-85, 180], [85, 180], [85, -180]],
    [tunisia.getSouthWest(), tunisia.getNorthWest(), tunisia.getNorthEast(), tunisia.getSouthEast()]
  ], { stroke: false, fillColor: '#0e1a14', fillOpacity: 0.72, interactive: false }).addTo(map);

  var markerLayer = L.layerGroup().addTo(map);
  var markersById = {};
  var facilitiesById = {};

  /* ------------------------------------------------------------------
   * Small helpers
   * ------------------------------------------------------------------ */
  // Creates an element with text. textContent is used everywhere, so data can never inject HTML.
  function element(tag, className, text) {
    var node = document.createElement(tag);
    if (className) { node.className = className; }
    if (text !== undefined && text !== null) { node.textContent = text; }
    return node;
  }

  function markerIcon(type, selected) {
    return L.divIcon({
      className: '',
      html: '<div class="medmap-pin medmap-pin--' + type + (selected ? ' is-selected' : '') + '"><span>' + (TYPE_LETTERS[type] || '?') + '</span></div>',
      iconSize: [30, 30],
      iconAnchor: [15, 30],
      popupAnchor: [0, -30]
    });
  }

  /* ------------------------------------------------------------------
   * 2. Ask PHP for the facilities matching the filters
   * ------------------------------------------------------------------ */
  var requestNumber = 0;

  function loadFacilities() {
    var query = new URLSearchParams(new FormData(form)).toString();
    var thisRequest = ++requestNumber;
    countElement.textContent = 'Searching…';

    fetch(config.dataUrl + '&' + query, { headers: { Accept: 'application/json' } })
      .then(function (response) {
        if (!response.ok) { throw new Error('HTTP ' + response.status); }
        return response.json();
      })
      .then(function (data) {
        if (thisRequest !== requestNumber) { return; }   // a newer search has started: ignore this answer
        showFacilities(data.facilities);
      })
      .catch(function () {
        if (thisRequest !== requestNumber) { return; }
        countElement.textContent = 'The facilities could not be loaded. Check that the database is running.';
      });
  }

  /* ------------------------------------------------------------------
   * 3. Draw markers, result list and details
   * ------------------------------------------------------------------ */
  function showFacilities(facilities) {
    markerLayer.clearLayers();
    markersById = {};
    facilitiesById = {};
    listElement.replaceChildren();
    showResultsPanel();

    var specialty = form.elements.specialty.value.trim();

    if (facilities.length === 0) {
      countElement.textContent = 'No matching facility in Tunisia.';
      var empty = element('li', 'sp-empty');
      empty.append(
        element('h3', 'h6', 'No matching facility in Tunisia'),
        element('p', 'mb-0', specialty ? 'No registered doctor matches "' + specialty + '" with these filters.' : 'Try removing a filter.')
      );
      listElement.append(empty);
      map.fitBounds(tunisia);
      return;
    }

    countElement.textContent = facilities.length + (facilities.length === 1 ? ' facility' : ' facilities') + ' in Tunisia'
      + (specialty ? ' with a doctor matching "' + specialty + '"' : '');

    var points = [];
    facilities.forEach(function (facility) {
      facilitiesById[facility.id] = facility;
      points.push([facility.lat, facility.lng]);

      // Marker + small popup
      var marker = L.marker([facility.lat, facility.lng], { icon: markerIcon(facility.type, false), title: facility.name });
      var popup = element('div', 'medmap-popup');
      popup.append(element('strong', '', facility.name), element('small', '', facility.typeLabel + ' · ' + facility.city));
      marker.bindPopup(popup);
      marker.on('click', function () { selectFacility(facility.id, false); });
      marker.addTo(markerLayer);
      markersById[facility.id] = marker;

      // Line in the result list
      var matching = facility.doctors.filter(function (doctor) { return doctor.match; }).length;
      var button = element('button', 'medmap-item');
      button.type = 'button';
      var name = element('span', 'medmap-item__name');
      name.append(element('span', 'medmap-dot medmap-dot--' + facility.type), document.createTextNode(facility.name));
      name.firstChild.style.background = 'var(--type-' + facility.type + ')';
      var meta = facility.typeLabel + ' · ' + facility.city + ', ' + facility.governorate;
      if (specialty) { meta += ' · ' + matching + ' matching doctor' + (matching === 1 ? '' : 's'); }
      button.append(name, element('span', 'medmap-item__meta', meta));
      button.addEventListener('click', function () { selectFacility(facility.id, true); });
      var item = element('li');
      item.append(button);
      listElement.append(item);
    });

    map.fitBounds(L.latLngBounds(points).pad(0.25), { maxZoom: 12 });
  }

  var selectedId = null;

  function selectFacility(id, moveMap) {
    var facility = facilitiesById[id];
    if (!facility) { return; }

    // Highlight the selected marker
    if (selectedId !== null && markersById[selectedId]) {
      markersById[selectedId].setIcon(markerIcon(facilitiesById[selectedId].type, false));
    }
    selectedId = id;
    markersById[id].setIcon(markerIcon(facility.type, true));
    if (moveMap) {
      map.flyTo([facility.lat, facility.lng], Math.max(map.getZoom(), 12), { duration: 0.6 });
      markersById[id].openPopup();
    }

    // Details panel
    var box = element('div', 'medmap-details');
    box.append(element('h2', '', facility.name));

    var tags = element('div', 'medmap-tags');
    tags.append(element('span', 'sp-pill', facility.typeLabel));
    tags.append(element('span', facility.emergency ? 'sp-badge sp-badge--danger' : 'sp-badge sp-badge--neutral',
      facility.emergency ? 'Emergency service' : 'No emergency service'));
    if (facility.isDemo) { tags.append(element('span', 'sp-badge sp-badge--warn', 'Demo data')); }
    box.append(tags);

    var facts = element('dl');
    [['Address', facility.address], ['City', facility.city], ['Governorate', facility.governorate],
     ['Phone', facility.phone || 'Not available']].forEach(function (row) {
      facts.append(element('dt', '', row[0]), element('dd', '', row[1]));
    });
    box.append(facts);

    box.append(element('h3', '', 'Doctors (' + facility.doctors.length + ')'));
    if (facility.doctors.length === 0) {
      box.append(element('p', 'text-muted mb-0', 'No doctor is registered in this facility.'));
    }
    // Doctors matching the searched specialty first
    facility.doctors.slice().sort(function (a, b) { return (b.match ? 1 : 0) - (a.match ? 1 : 0); }).forEach(function (doctor) {
      var card = element('div', 'medmap-doctor' + (doctor.match ? ' is-match' : ''));
      card.append(element('strong', '', doctor.name), element('small', '', doctor.specialty));
      var contact = [doctor.phone, doctor.email].filter(Boolean).join(' · ');
      card.append(element('small', '', contact || 'No contact information'));
      box.append(card);
    });

    detailsBody.replaceChildren(box);
    resultsPanel.hidden = true;
    detailsPanel.hidden = false;
  }

  function showResultsPanel() {
    if (selectedId !== null && markersById[selectedId]) {
      markersById[selectedId].setIcon(markerIcon(facilitiesById[selectedId].type, false));
    }
    selectedId = null;
    detailsPanel.hidden = true;
    resultsPanel.hidden = false;
  }

  /* ------------------------------------------------------------------
   * Events of the filter form
   * ------------------------------------------------------------------ */
  // The city list depends on the chosen governorate.
  function fillCities() {
    var cities = config.cities[governorateSelect.value] || [];
    citySelect.replaceChildren(new Option('All cities', ''));
    cities.forEach(function (city) { citySelect.add(new Option(city, city)); });
    citySelect.disabled = cities.length === 0;
  }

  var typingTimer = null;
  form.addEventListener('input', function (event) {
    if (event.target === governorateSelect) { fillCities(); }
    // Wait until the visitor stops typing before searching.
    clearTimeout(typingTimer);
    typingTimer = setTimeout(loadFacilities, event.target.name === 'specialty' ? 300 : 0);
  });
  form.addEventListener('submit', function (event) { event.preventDefault(); loadFacilities(); });
  form.addEventListener('reset', function () { setTimeout(function () { fillCities(); loadFacilities(); }, 0); });
  document.getElementById('medmapBack').addEventListener('click', showResultsPanel);

  loadFacilities();
})();
