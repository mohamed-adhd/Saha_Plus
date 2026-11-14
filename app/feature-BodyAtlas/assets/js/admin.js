/*
 * Body Atlas – Back Office prototype CRUD.
 * Add / edit / delete rows directly in the page (no database yet).
 * Each table: <table data-crud="modalId">, rows: <tr data-row='{json}'>, cells: <td data-col="field">.
 */
(() => {
  'use strict';

  const toast = (message) => {
    const el = document.createElement('div');
    el.className = 'admin-toast';
    el.setAttribute('role', 'status');
    el.textContent = message;
    document.body.append(el);
    setTimeout(() => el.remove(), 2600);
  };

  document.querySelectorAll('table[data-crud]').forEach((table) => {
    const modalEl = document.getElementById(table.dataset.crud);
    const modal = bootstrap.Modal.getOrCreateInstance(modalEl);
    const form = modalEl.querySelector('form');
    const titleEl = modalEl.querySelector('.modal-title');
    const label = table.dataset.label || 'item';
    const tbody = table.tBodies[0];
    let editingRow = null;

    const rowData = (tr) => JSON.parse(tr.dataset.row);

    // Text to show in a cell: the option label for <select>, the value otherwise.
    const displayValue = (field, value) => {
      const input = form.elements[field];
      if (input && input.tagName === 'SELECT') {
        const option = [...input.options].find((o) => o.value === String(value));
        return option ? option.textContent : value;
      }
      return value === '' ? '—' : value;
    };

    const fillCells = (tr, data) => {
      tr.dataset.row = JSON.stringify(data);
      tr.querySelectorAll('td[data-col]').forEach((td) => {
        const field = td.dataset.col;
        const text = displayValue(field, data[field] ?? '');
        if (td.hasAttribute('data-badge')) {
          const badge = td.querySelector('span') || document.createElement('span');
          badge.className = badge.className.replace(/--\S+/, `--${data[field]}`);
          badge.textContent = text;
          td.replaceChildren(badge);
        } else {
          td.textContent = text;
        }
      });
    };

    const openForm = (tr) => {
      editingRow = tr;
      form.reset();
      form.classList.remove('was-validated');
      titleEl.textContent = tr ? titleEl.dataset.titleEdit : titleEl.dataset.titleAdd;
      if (tr) {
        const data = rowData(tr);
        Object.entries(data).forEach(([key, value]) => { if (form.elements[key]) form.elements[key].value = value ?? ''; });
      }
      modal.show();
    };

    document.querySelector(`[data-add="${table.dataset.crud}"]`)?.addEventListener('click', () => openForm(null));

    tbody.addEventListener('click', (event) => {
      const tr = event.target.closest('tr');
      if (event.target.closest('[data-edit]')) openForm(tr);
      if (event.target.closest('[data-delete]')) {
        const data = rowData(tr);
        const name = data.name || `${data.firstName ?? ''} ${data.lastName ?? ''}`.trim();
        if (confirm(`Delete the ${label} "${name}"?`)) {
          tr.remove();
          toast(`The ${label} was deleted.`);
        }
      }
    });

    form.addEventListener('submit', (event) => {
      event.preventDefault();
      if (!form.checkValidity()) {
        form.classList.add('was-validated');
        return;
      }
      const data = Object.fromEntries(new FormData(form).entries());
      Object.keys(data).forEach((k) => { if (typeof data[k] === 'string') data[k] = data[k].trim(); });

      if (editingRow) {
        data.id = Number(data.id);
        fillCells(editingRow, { ...rowData(editingRow), ...data });
        toast(`The ${label} was updated.`);
      } else {
        const ids = [...tbody.rows].map((r) => Number(rowData(r).id) || 0);
        data.id = Math.max(0, ...ids) + 1;
        if ('createdAt' in data) data.createdAt = new Date().toISOString().slice(0, 10);
        // Clone the structure of an existing row so the new row has the same cells.
        const template = tbody.rows[0];
        const tr = template.cloneNode(true);
        tr.querySelectorAll('a[target="_blank"]').forEach((a) => a.remove());
        tbody.prepend(tr);
        fillCells(tr, data);
        toast(`The ${label} was added.`);
      }
      modal.hide();
    });
  });

  /* ---------- Search + filter ---------- */
  const applyFilters = (tableId) => {
    const table = document.getElementById(tableId);
    const search = document.querySelector(`[data-search="${tableId}"]`);
    const filter = document.querySelector(`[data-filter="${tableId}"]`);
    const term = (search?.value || '').toLowerCase();
    [...table.tBodies[0].rows].forEach((tr) => {
      const data = JSON.parse(tr.dataset.row);
      const matchText = tr.textContent.toLowerCase().includes(term);
      const matchFilter = !filter || filter.value === '' || String(data[filter.dataset.filterCol]) === filter.value;
      tr.hidden = !(matchText && matchFilter);
    });
  };
  document.querySelectorAll('[data-search]').forEach((el) => el.addEventListener('input', () => applyFilters(el.dataset.search)));
  document.querySelectorAll('[data-filter]').forEach((el) => el.addEventListener('change', () => applyFilters(el.dataset.filter)));
})();
