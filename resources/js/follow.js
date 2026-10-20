document.addEventListener('click', async (e) => {
  const btn = e.target.closest('.lab-follow-btn[data-follow-url]');
  if (!btn || btn.disabled) return;
  e.preventDefault();

  const following = btn.dataset.following === '1';
  const url    = following ? btn.dataset.unfollowUrl : btn.dataset.followUrl;
  const method = following ? 'DELETE' : 'POST';

  btn.disabled = true;
  try {
    const res = await fetch(url, {
      method,
      headers: {
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content ?? '',
        'Accept': 'application/json',
        'X-Requested-With': 'XMLHttpRequest',
      },
      credentials: 'same-origin',
    });
    if (!res.ok) throw new Error('HTTP ' + res.status);

    const data = await res.json();
    const nowFollowing = !!data.following;
    btn.dataset.following = nowFollowing ? '1' : '0';
    btn.setAttribute('aria-pressed', nowFollowing ? 'true' : 'false');
    btn.querySelector('.lab-follow-btn__label').textContent = nowFollowing ? 'FOLLOWING' : 'FOLLOW';

    const counter = document.querySelector('[data-followers-count]');
    if (counter && typeof data.followers_count === 'number') {
      counter.textContent = data.followers_count;
    }
  } catch (err) {
    console.error('[follow]', err);
  } finally {
    btn.disabled = false;
  }
});
