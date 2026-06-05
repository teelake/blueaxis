/**
 * Loading state for submit buttons (admin + public forms).
 */
(function () {
  var SPINNER =
    '<svg class="btn-spinner" viewBox="0 0 24 24" fill="none" aria-hidden="true">' +
    '<circle class="btn-spinner__track" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="3"/>' +
    '<path class="btn-spinner__arc" d="M12 2a10 10 0 0 1 10 10" stroke="currentColor" stroke-width="3" stroke-linecap="round"/>' +
    '</svg>';

  function defaultLoadingText(btn, form) {
    if (btn.dataset.loadingText) {
      return btn.dataset.loadingText;
    }
    if (form && form.dataset.loadingText) {
      return form.dataset.loadingText;
    }
    var raw = (btn.textContent || btn.value || btn.getAttribute('aria-label') || '').trim().toLowerCase();
    if (raw.indexOf('save') !== -1) return 'Saving…';
    if (raw.indexOf('upload') !== -1) return 'Uploading…';
    if (raw.indexOf('sign in') !== -1 || raw === 'sign in') return 'Signing in…';
    if (raw.indexOf('update') !== -1) return 'Updating…';
    if (raw.indexOf('create') !== -1) return 'Creating…';
    if (raw.indexOf('submit') !== -1) return 'Submitting…';
    if (raw.indexOf('send') !== -1) return 'Sending…';
    if (raw.indexOf('search') !== -1 || raw.indexOf('filter') !== -1 || raw.indexOf('apply') !== -1) {
      return 'Searching…';
    }
    if (raw.indexOf('subscribe') !== -1) return 'Subscribing…';
    if (raw.indexOf('delete') !== -1) return 'Deleting…';
    if (raw.indexOf('approve') !== -1) return 'Approving…';
    if (raw.indexOf('publish') !== -1 || raw.indexOf('unpublish') !== -1) return 'Updating…';
    if (raw.indexOf('deactivate') !== -1 || raw.indexOf('activate') !== -1) return 'Updating…';
    return 'Please wait…';
  }

  function isLoadableButton(btn) {
    if (!btn || btn.disabled) return false;
    if (btn.tagName === 'BUTTON' && btn.type === 'button') return false;
    if (btn.tagName === 'INPUT' && btn.type !== 'submit') return false;
    return btn.type === 'submit' || (btn.tagName === 'BUTTON' && !btn.type);
  }

  function setLoading(btn, form) {
    if (btn.classList.contains('is-loading')) return;
    var text = defaultLoadingText(btn, form);
    btn.classList.add('is-loading');
    btn.setAttribute('aria-busy', 'true');
    btn.disabled = true;

    if (!btn.dataset.loadingOriginalHtml) {
      btn.dataset.loadingOriginalHtml = btn.innerHTML;
    }
    if (!btn.dataset.loadingOriginalWidth && btn.offsetWidth) {
      btn.dataset.loadingOriginalWidth = String(btn.offsetWidth);
      btn.style.minWidth = btn.dataset.loadingOriginalWidth + 'px';
    }

    if (btn.classList.contains('admin-icon-btn')) {
      btn.innerHTML = SPINNER;
      btn.setAttribute('aria-label', text);
      return;
    }

    btn.innerHTML = SPINNER + '<span class="btn-loading-label">' + text + '</span>';
  }

  /** Hidden tab/repeater fields with `required` block submit before the loading handler runs. */
  document.addEventListener(
    'click',
    function (e) {
      var btn = e.target && e.target.closest
        ? e.target.closest('button[type="submit"], input[type="submit"]')
        : null;
      if (!btn || !btn.form) return;
      btn.form.querySelectorAll('input[required], textarea[required], select[required]').forEach(function (el) {
        if (el.offsetParent === null) {
          el.removeAttribute('required');
        }
      });
    },
    true
  );

  document.addEventListener(
    'submit',
    function (e) {
      var form = e.target;
      if (!form || form.tagName !== 'FORM' || form.dataset.noLoading !== undefined) return;

      var btn = e.submitter;
      if (!isLoadableButton(btn)) {
        btn = form.querySelector(
          'button[type="submit"]:not([disabled]), input[type="submit"]:not([disabled])'
        );
      }
      if (!isLoadableButton(btn)) return;

      setLoading(btn, form);
      form.querySelectorAll('button[type="submit"], input[type="submit"]').forEach(function (b) {
        if (b !== btn) b.disabled = true;
      });
    },
    false
  );
})();
