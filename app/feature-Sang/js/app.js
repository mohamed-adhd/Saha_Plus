const BT = ['O-','O+','A-','A+','B-','B+','AB-','AB+'];
// donneur -> receveurs compatibles
const COMP = {
  'O-':BT, 'O+':['O+','A+','B+','AB+'],
  'A-':['A-','A+','AB-','AB+'], 'A+':['A+','AB+'],
  'B-':['B-','B+','AB-','AB+'], 'B+':['B+','AB+'],
  'AB-':['AB-','AB+'], 'AB+':['AB+']
};
const $ = s => document.querySelector(s);
const esc = s => String(s).replace(/[&<>"']/g, c => ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[c]));
const load = (k, d) => { try { return JSON.parse(localStorage.getItem(k)) || d; } catch { return d; } };
const save = (k, v) => localStorage.setItem(k, JSON.stringify(v));
const level = q => q < 5 ? 'critical' : q < 15 ? 'low' : 'normal';

// Coordonnées approximatives et accessibilité de démonstration : à vérifier / corriger
const facilities = [
  {id:1, name:'Centre National de Transfusion Sanguine', city:'Tunis', lat:36.8065, lng:10.1475, accessible:true},
  {id:2, name:'Hôpital Charles Nicolle', city:'Tunis', lat:36.8019, lng:10.1658, accessible:true},
  {id:3, name:'Hôpital La Rabta', city:'Tunis', lat:36.8062, lng:10.1569, accessible:false},
  {id:4, name:'Hôpital Habib Thameur', city:'Tunis', lat:36.7937, lng:10.1723, accessible:true},
  {id:5, name:'Hôpital Abderrahmen Mami', city:'Ariana', lat:36.8610, lng:10.1960, accessible:true},
  {id:6, name:'Hôpital Mongi Slim', city:'La Marsa', lat:36.8741, lng:10.3220, accessible:false}
];
const qty0 = [5,40,8,35,3,20,2,10];
let stocks = load('stocks', BT.map((b,i) => ({
  id:i+1, bloodType:b, quantity:qty0[i], urgencyLevel:level(qty0[i]),
  facility_id:(i%6)+1, updatedAt:new Date().toISOString()
})));
let appts = load('appts', []);
let uid = load('uid', null);
if (!uid) { uid = 'U' + Date.now().toString(36); save('uid', uid); }

const fac = id => (facilities.find(f => f.id == id) || {}).name || '?';
const opts = a => a.map(x => `<option value="${x.id ?? x}">${esc(x.name ?? x)}</option>`).join('');
const fmt = d => new Date(d).toLocaleDateString('fr-FR');

function persist() { save('stocks', stocks); save('appts', appts); render(); drawMap(); }

function render() {
  // Visiteur : matrice
  $('#matrix').innerHTML = '<tr><th>Donneur ↓ / Receveur →</th>' + BT.map(b => `<th>${b}</th>`).join('') + '</tr>' +
    BT.map(d => `<tr><th>${d}</th>${BT.map(r => `<td>${COMP[d].includes(r) ? '✅' : '—'}</td>`).join('')}</tr>`).join('');
  // Visiteur : besoins urgents
  const order = {critical:0, low:1};
  const urg = stocks.filter(s => s.urgencyLevel !== 'normal').sort((a,b) => order[a.urgencyLevel] - order[b.urgencyLevel]);
  $('#urgent').innerHTML = urg.length ? urg.map(s =>
    `<div class="urg ${s.urgencyLevel}"><b>${s.bloodType}</b><span>${esc(fac(s.facility_id))}</span>
     <span>Stock : ${s.quantity}</span><span class="tag ${s.urgencyLevel}">${s.urgencyLevel}</span></div>`).join('')
    : '<p>Aucun besoin urgent actuellement.</p>';

  // Patient : mes rendez-vous
  const mine = appts.filter(a => a.user_id === uid);
  $('#mine').innerHTML = '<tr><th>Date</th><th>Centre</th><th>Groupe</th><th>Nom</th><th>Statut</th><th></th></tr>' +
    (mine.map(a => `<tr><td>${fmt(a.date)}</td><td>${esc(fac(a.facility_id))}</td><td>${a.bloodType}</td>
      <td>${a.anonymous ? 'Anonyme' : esc(a.name)}</td><td>${a.status}</td>
      <td>${['pending','confirmed'].includes(a.status) ? `<button onclick="cancelAppt(${a.id})">Annuler</button>` : ''}</td></tr>`).join('')
    || '<tr><td colspan="6">Aucun rendez-vous.</td></tr>');

  // Admin : stocks
  $('#stocks').innerHTML = '<tr><th>Centre</th><th>Groupe</th><th>Quantité</th><th>Urgence</th><th>Mis à jour</th><th></th></tr>' +
    stocks.map(s => `<tr><td>${esc(fac(s.facility_id))}</td><td>${s.bloodType}</td>
      <td><input type="number" min="0" value="${s.quantity}" onchange="upStock(${s.id},'quantity',this.value)"></td>
      <td><select onchange="upStock(${s.id},'urgencyLevel',this.value)">
        ${['normal','low','critical'].map(l => `<option ${l===s.urgencyLevel?'selected':''}>${l}</option>`).join('')}</select></td>
      <td>${fmt(s.updatedAt)}</td><td><button onclick="delStock(${s.id})">✕</button></td></tr>`).join('');

  // Admin : rendez-vous
  $('#appts').innerHTML = '<tr><th>Date</th><th>Donneur</th><th>Coordonnées</th><th>Centre</th><th>Groupe</th><th>Statut</th></tr>' +
    (appts.map(a => `<tr><td>${fmt(a.date)}</td><td>${a.anonymous ? 'Anonyme' : esc(a.name)}</td>
      <td>${a.anonymous ? '—' : esc(a.contact)}</td><td>${esc(fac(a.facility_id))}</td><td>${a.bloodType}</td>
      <td><select onchange="upAppt(${a.id},this.value)">
        ${['pending','confirmed','done','cancelled'].map(s => `<option ${s===a.status?'selected':''}>${s}</option>`).join('')}</select></td></tr>`).join('')
    || '<tr><td colspan="6">Aucun rendez-vous.</td></tr>');
}

// ---- Actions ----
function cancelAppt(id) { appts.find(a => a.id === id).status = 'cancelled'; persist(); }
function upAppt(id, v) { appts.find(a => a.id === id).status = v; persist(); }
function upStock(id, k, v) {
  const s = stocks.find(x => x.id === id);
  if (k === 'quantity') { s.quantity = Math.max(0, +v); s.urgencyLevel = level(s.quantity); } else s[k] = v;
  s.updatedAt = new Date().toISOString(); persist();
}
function delStock(id) { stocks = stocks.filter(s => s.id !== id); persist(); }

$('#stockForm').onsubmit = e => {
  e.preventDefault();
  const f = +$('#sFac').value, b = $('#sType').value, q = Math.max(0, +$('#sQty').value);
  const s = stocks.find(x => x.facility_id === f && x.bloodType === b);
  if (s) { s.quantity = q; s.urgencyLevel = level(q); s.updatedAt = new Date().toISOString(); }
  else stocks.push({id:Date.now(), bloodType:b, quantity:q, urgencyLevel:level(q), facility_id:f, updatedAt:new Date().toISOString()});
  e.target.reset(); persist();
};

$('#anon').onchange = e => ['#name','#contact'].forEach(s => {
  $(s).disabled = e.target.checked; $(s).required = !e.target.checked; if (e.target.checked) $(s).value = '';
});

$('#book').onsubmit = e => {
  e.preventDefault();
  const anon = $('#anon').checked;
  appts.push({
    id:Date.now(), user_id:uid, anonymous:anon,
    name: anon ? 'Anonyme' : $('#name').value.trim(),
    contact: anon ? '' : $('#contact').value.trim(),
    facility_id:+$('#facility').value, bloodType:$('#bloodType').value,
    date:$('#date').value, status:'pending'
  });
  e.target.reset(); $('#anon').onchange({target:$('#anon')}); persist();
  alert('Rendez-vous enregistré (statut : pending).');
};

$('#elig').onsubmit = e => {
  e.preventDefault();
  const f = e.target, age = +f.age.value, w = +f.weight.value, r = [];
  if (age < 18 || age > 65) r.push('Âge requis : 18 à 65 ans.');
  if (w < 50) r.push('Poids minimum : 50 kg.');
  if (f.last.value && (Date.now() - new Date(f.last.value)) / 864e5 < 56) r.push('Dernier don trop récent (minimum 8 semaines).');
  if (!f.healthy.checked) r.push('Vous devez être en bonne santé aujourd’hui.');
  const box = $('#eligRes');
  box.className = r.length ? 'bad' : 'ok';
  box.innerHTML = r.length ? '❌ Non éligible :<br>' + r.map(esc).join('<br>') : '✅ Vous êtes éligible, vous pouvez réserver un rendez-vous.';
};

document.querySelectorAll('#tabs button').forEach(b => b.onclick = () => {
  document.querySelectorAll('#tabs button').forEach(x => x.classList.toggle('on', x === b));
  ['visitor','patient','admin'].forEach(v => $('#' + v).hidden = v !== b.dataset.v);
  if (b.dataset.v === 'visitor') setTimeout(() => { map.invalidateSize(); drawMap(); }, 50);
});

// ---- Carte (Leaflet + OpenStreetMap) ----
let map, markers = [], me = null;
const needsOf = f => stocks.filter(s => s.facility_id == f.id && s.urgencyLevel !== 'normal');
const colorOf = f => { const n = needsOf(f); return n.some(s => s.urgencyLevel === 'critical') ? '#c9475a' : n.length ? '#e8a05a' : '#5fa97f'; };
const pin = c => L.divIcon({className:'', html:`<span class="pin" style="background:${c}"></span>`, iconSize:[22,22], iconAnchor:[11,22], popupAnchor:[0,-22]});
const route = f => `https://www.openstreetmap.org/directions?to=${f.lat}%2C${f.lng}`;
const km = (a, b, c, d) => { const r = x => x * Math.PI / 180, h = Math.sin(r(c - a) / 2) ** 2 + Math.cos(r(a)) * Math.cos(r(c)) * Math.sin(r(d - b) / 2) ** 2; return 12742 * Math.asin(Math.sqrt(h)); };

function drawMap() {
  if (!map) return;
  markers.forEach(m => m.remove()); markers = [];
  const only = $('#onlyAcc').checked;
  facilities.filter(f => !only || f.accessible).forEach(f => {
    const needs = needsOf(f).map(s => `<span class="tag ${s.urgencyLevel}">${s.bloodType}</span>`).join(' ') || 'Aucun besoin urgent';
    markers.push(L.marker([f.lat, f.lng], {icon: pin(colorOf(f))}).addTo(map).bindPopup(
      `<b>${esc(f.name)}</b><br>${esc(f.city)}<br>${f.accessible ? '♿ Accès PMR' : 'Accès PMR non confirmé'}<br>Besoins : ${needs}<br>
       <a href="#" onclick="goBook(${f.id});return false">Prendre rendez-vous</a> · <a target="_blank" rel="noopener" href="${route(f)}">Itinéraire</a>`));
  });
  if (markers.length) map.fitBounds(L.featureGroup(markers).getBounds().pad(0.2));
}
function goBook(id) {
  document.querySelector('#tabs button[data-v="patient"]').click();
  $('#facility').value = id; $('#book').scrollIntoView({behavior:'smooth'});
}
function initMap() {
  map = L.map('map').setView([36.82, 10.17], 11);
  L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {maxZoom:19, attribution:'© OpenStreetMap'}).addTo(map);
  $('#onlyAcc').onchange = drawMap;
  $('#near').onclick = () => {
    if (!navigator.geolocation) return alert('Géolocalisation non disponible.');
    navigator.geolocation.getCurrentPosition(p => {
      const {latitude: la, longitude: lo} = p.coords, only = $('#onlyAcc').checked;
      if (me) me.remove();
      me = L.circleMarker([la, lo], {radius:9, color:'#1f3a2e', fillColor:'#8fcba6', fillOpacity:1}).addTo(map).bindPopup('Vous êtes ici');
      const top = facilities.filter(f => !only || f.accessible).map(f => ({...f, d: km(la, lo, f.lat, f.lng)})).sort((a, b) => a.d - b.d).slice(0, 3);
      $('#nearest').innerHTML = top.map(f => `<div class="near-card"><span><b>${esc(f.name)}</b> – ${f.d.toFixed(1)} km ${f.accessible ? '♿' : ''}</span>
        <a class="lnk" href="${route(f)}" target="_blank" rel="noopener">Itinéraire</a></div>`).join('');
      if (top.length) map.fitBounds(L.latLngBounds([[la, lo], ...top.map(f => [f.lat, f.lng])]).pad(0.2));
    }, () => alert('Position refusée ou indisponible.'));
  };
  drawMap();
}

// ---- Init ----
$('#facility').innerHTML = opts(facilities); $('#sFac').innerHTML = opts(facilities);
$('#bloodType').innerHTML = opts(BT); $('#sType').innerHTML = opts(BT);
$('#date').min = new Date().toISOString().slice(0,10);
render();
initMap();
