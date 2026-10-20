(() => {
  const roots = document.querySelectorAll('[data-comments-root]');
  if (!roots.length) return;

  const csrf = document.querySelector('meta[name="csrf-token"]')?.content || '';

  const fmtTime = (iso, human) => human || (iso ? new Date(iso).toLocaleString() : '');

  const escapeHtml = (s) => String(s ?? '')
    .replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;')
    .replace(/"/g, '&quot;').replace(/'/g, '&#39;');

  const renderComment = (c, isReply = false) => {
    const pending  = c.status === 'pending';
    const deleted  = !!c.is_deleted;
    const badges   = [];
    if (pending && !deleted) badges.push('<span class="lab-comment__badge lab-comment__badge--pending">PENDING</span>');
    if (deleted) badges.push('<span class="lab-comment__badge lab-comment__badge--pending">DELETED</span>');
    if (c.user?.is_trusted && !deleted) badges.push('<span class="lab-comment__badge">TRUSTED</span>');

    const replies = Array.isArray(c.replies) && c.replies.length
      ? `<div class="lab-comment__replies">${c.replies.map(r => renderComment(r, true)).join('')}</div>`
      : '';

    const avatar = c.user?.avatar || '/favicon.svg';
    const authorUrl = `https://sharzhukov.com/u/${c.user?.username ?? ''}`;
    const canReply = !isReply && !deleted && c.status === 'approved';

    const bodyHtml = deleted
      ? '<span class="lab-comment__deleted">[deleted]</span>'
      : escapeHtml(c.body);

    return `
<article class="lab-comment ${pending && !deleted ? 'lab-comment--pending' : ''} ${deleted ? 'lab-comment--deleted' : ''} ${isReply ? 'lab-comment--reply' : ''}" id="comment-${c.id}" data-comment-id="${c.id}">
  <header class="lab-comment__head">
    <img class="lab-comment__avatar" src="${escapeHtml(avatar)}" alt="">
    <div class="lab-comment__meta">
      <a class="lab-comment__author" href="${authorUrl}" target="_blank" rel="noopener">${escapeHtml(c.user?.name || c.user?.username || 'Anonymous')}</a>
      <span class="lab-comment__time">${escapeHtml(fmtTime(c.created_at, c.created_human))}</span>
    </div>
    ${badges.join('')}
  </header>
  <div class="lab-comment__body">${bodyHtml}</div>
  <footer class="lab-comment__foot">
    ${canReply ? `<button type="button" class="lab-comment__action" data-reply-toggle="${c.id}">REPLY →</button>` : ''}
    ${c.can_delete ? `<button type="button" class="lab-comment__action lab-comment__action--danger" data-delete="${c.id}">DELETE</button>` : ''}
  </footer>
  <div class="lab-reply-form-slot" data-reply-slot="${c.id}"></div>
  ${replies}
</article>`;
  };

  const replyFormTpl = (parentId) => `
<form class="lab-reply-form" data-reply-form data-parent-id="${parentId}">
  <textarea name="body" maxlength="5000" placeholder="Your reply…" required></textarea>
  <div class="lab-reply-form__foot">
    <button type="submit" class="lab-btn lab-btn--primary" style="min-height:34px;padding:0 14px;font-size:11px;">REPLY →</button>
    <button type="button" class="lab-comment__action" data-reply-cancel>Cancel</button>
  </div>
</form>`;

  const setCount = (root, n) => {
    const el = root.querySelector('[data-comments-count]');
    if (el && typeof n === 'number') el.textContent = n;
  };

  const setHint = (root, msg, state) => {
    const el = root.querySelector('[data-comment-hint]');
    if (!el) return;
    el.textContent = msg || '';
    if (state) el.dataset.state = state; else delete el.dataset.state;
  };

  const load = async (root) => {
    const list = root.querySelector('[data-comments-list]');
    try {
      const res = await fetch(root.dataset.apiIndex, { headers: { 'Accept': 'application/json' }, credentials: 'same-origin' });
      const data = await res.json();
      setCount(root, data.count ?? 0);
      if (!data.comments || !data.comments.length) {
        list.innerHTML = '<div class="lab-comments__empty">No comments yet. Be the first.</div>';
        return;
      }
      list.innerHTML = data.comments.map(c => renderComment(c)).join('');
    } catch (err) {
      console.error('[comments] load', err);
      list.innerHTML = '<div class="lab-comments__empty">Failed to load comments.</div>';
    }
  };

  const post = async (root, url, payload, method = 'POST') => {
    const res = await fetch(url, {
      method,
      headers: {
        'X-CSRF-TOKEN': csrf,
        'Accept': 'application/json',
        'Content-Type': 'application/json',
        'X-Requested-With': 'XMLHttpRequest',
      },
      credentials: 'same-origin',
      body: method === 'DELETE' ? null : JSON.stringify(payload),
    });
    let data = {};
    try { data = await res.json(); } catch (_) {}
    if (!res.ok) {
      const msg = data.message || data.error || ('HTTP ' + res.status);
      throw new Error(msg);
    }
    return data;
  };

  roots.forEach((root) => {
    load(root);

    // Submit main form
    const form = root.querySelector('[data-comment-form]');
    if (form) {
      form.addEventListener('submit', async (e) => {
        e.preventDefault();
        const ta = form.querySelector('textarea');
        const body = ta.value.trim();
        if (body.length < 2) { setHint(root, 'Too short', 'error'); return; }

        setHint(root, 'Sending…');
        try {
          const data = await post(root, root.dataset.apiStore, {
            subject_type: root.dataset.subjectType,
            subject_id: Number(root.dataset.subjectId),
            body,
          });
          setHint(root, data.message || 'OK', 'ok');
          ta.value = '';
          load(root);
        } catch (err) {
          setHint(root, err.message, 'error');
        }
      });
    }

    // Delegated: reply / delete / cancel
    root.addEventListener('click', async (e) => {
      const replyBtn = e.target.closest('[data-reply-toggle]');
      if (replyBtn) {
        const id = replyBtn.dataset.replyToggle;
        const slot = root.querySelector(`[data-reply-slot="${id}"]`);
        if (!slot) return;
        slot.innerHTML = slot.innerHTML.trim() ? '' : replyFormTpl(id);
        return;
      }

      const cancel = e.target.closest('[data-reply-cancel]');
      if (cancel) {
        const form = cancel.closest('[data-reply-form]');
        const slot = form?.parentElement;
        if (slot) slot.innerHTML = '';
        return;
      }

      const del = e.target.closest('[data-delete]');
      if (del) {
        if (!confirm('Delete this comment?')) return;
        try {
          await post(root, `/api/comments/${del.dataset.delete}`, null, 'DELETE');
          load(root);
        } catch (err) {
          alert(err.message);
        }
        return;
      }
    });

    // Delegated: reply submit
    root.addEventListener('submit', async (e) => {
      const form = e.target.closest('[data-reply-form]');
      if (!form) return;
      e.preventDefault();
      const parentId = Number(form.dataset.parentId);
      const body = form.querySelector('textarea').value.trim();
      if (body.length < 2) return;
      try {
        await post(root, root.dataset.apiStore, {
          subject_type: root.dataset.subjectType,
          subject_id: Number(root.dataset.subjectId),
          body,
          parent_id: parentId,
        });
        load(root);
      } catch (err) {
        alert(err.message);
      }
    });
  });
})();
