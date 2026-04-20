// Admin JS - Cosmic Aria

// Toggle active state
document.querySelectorAll('.toggle-active').forEach(toggle => {
  toggle.addEventListener('change', async (e) => {
    const id = e.target.dataset.id;
    const model = e.target.dataset.model;
    const field = e.target.dataset.field;
    const value = e.target.checked;

    try {
      const res = await fetch(`/admin/api/toggle`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '' },
        body: JSON.stringify({ id, model, field, value })
      });
      if (!res.ok) throw new Error('Toggle failed');
    } catch (err) {
      e.target.checked = !value; // revert
      console.error(err);
    }
  });
});

// Delete confirmation
document.querySelectorAll('.btn-delete').forEach(btn => {
  btn.addEventListener('click', (e) => {
    if (!confirm('Supprimer cet élément ? Cette action est irréversible.')) {
      e.preventDefault();
    }
  });
});

// Modal open/close
document.querySelectorAll('[data-modal]').forEach(trigger => {
  trigger.addEventListener('click', () => {
    const modalId = trigger.dataset.modal;
    const modal = document.getElementById(modalId);
    if (modal) modal.classList.add('open');
  });
});

document.querySelectorAll('.modal-backdrop').forEach(backdrop => {
  backdrop.addEventListener('click', (e) => {
    if (e.target === backdrop) backdrop.classList.remove('open');
  });
});

document.querySelectorAll('.modal-close').forEach(btn => {
  btn.addEventListener('click', () => {
    btn.closest('.modal-backdrop').classList.remove('open');
  });
});

// Sortable lists
document.querySelectorAll('.sortable-list').forEach(list => {
  if (typeof Sortable === 'undefined') return;
  new Sortable(list, {
    handle: '.sort-handle',
    animation: 150,
    onEnd: async (evt) => {
      const ids = [...list.querySelectorAll('[data-id]')].map(el => el.dataset.id);
      try {
        await fetch(`/admin/api/reorder`, {
          method: 'POST',
          headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '' },
          body: JSON.stringify({ model: list.dataset.model, ids })
        });
      } catch (err) { console.error('Reorder failed', err); }
    }
  });
});

// Search filter
document.querySelectorAll('[data-search]').forEach(input => {
  input.addEventListener('input', (e) => {
    const query = e.target.value.toLowerCase();
    const target = document.querySelector(e.target.dataset.search);
    if (!target) return;
    target.querySelectorAll('[data-searchable]').forEach(row => {
      const text = row.textContent.toLowerCase();
      row.style.display = text.includes(query) ? '' : 'none';
    });
  });
});
