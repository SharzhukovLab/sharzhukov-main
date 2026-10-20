(() => {
  const roots = document.querySelectorAll('[data-reactions-root]');
  if (!roots.length) return;

  const csrf = document.querySelector('meta[name="csrf-token"]')?.content || '';

  const paint = (root, counts, userReactions) => {
    Object.keys(counts || {}).forEach((k) => {
      const btn = root.querySelector(`[data-reaction="${k}"]`);
      if (!btn) return;
      const cnt = counts[k] || 0;
      btn.querySelector('[data-count-for]').textContent = cnt;
      btn.dataset.count = String(cnt);
      const active = !!userReactions?.[k];
      btn.setAttribute('aria-pressed', active ? 'true' : 'false');
    });
  };

  const load = async (root) => {
    try {
      const res = await fetch(root.dataset.apiIndex, {
        headers: { 'Accept': 'application/json' },
        credentials: 'same-origin',
      });
      if (!res.ok) return;
      const data = await res.json();
      paint(root, data.counts, data.user_reactions);
    } catch (err) {
      console.error('[reactions] load', err);
    }
  };

  const toggle = async (root, type) => {
    const res = await fetch(root.dataset.apiToggle, {
      method: 'POST',
      headers: {
        'X-CSRF-TOKEN': csrf,
        'Accept': 'application/json',
        'Content-Type': 'application/json',
        'X-Requested-With': 'XMLHttpRequest',
      },
      credentials: 'same-origin',
      body: JSON.stringify({
        subject_type: root.dataset.subjectType,
        subject_id: Number(root.dataset.subjectId),
        type,
      }),
    });
    if (!res.ok) {
      const err = await res.json().catch(() => ({}));
      throw new Error(err.message || ('HTTP ' + res.status));
    }
    return res.json();
  };

  roots.forEach((root) => {
    load(root);

    root.addEventListener('click', async (e) => {
      const btn = e.target.closest('[data-reaction]');
      if (!btn) return;
      e.preventDefault();

      // не залогинен → редирект на login
      if (root.dataset.authed !== '1') {
        window.location.href = root.dataset.loginUrl;
        return;
      }

      if (btn.disabled) return;
      btn.disabled = true;
      try {
        const data = await toggle(root, btn.dataset.reaction);
        paint(root, data.counts, data.user_reactions);
        btn.classList.remove('is-pulse');
        void btn.offsetWidth; // reflow
        btn.classList.add('is-pulse');
      } catch (err) {
        console.error('[reactions] toggle', err);
      } finally {
        btn.disabled = false;
      }
    });
  });
})();
