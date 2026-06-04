/**
 * Drag-and-drop image upload zones (admin).
 */
(function () {
  function initZone(wrap) {
    if (wrap.dataset.uploadReady) return;
    var input = wrap.querySelector('[data-upload-input]');
    var hidden = wrap.querySelector('[data-upload-path]');
    var preview = wrap.querySelector('[data-upload-preview]');
    var placeholder = wrap.querySelector('[data-upload-placeholder]');
    var url = wrap.getAttribute('data-upload-url');
    var csrf = wrap.getAttribute('data-csrf');
    if (!input || !hidden || !url || !csrf) return;

    function setPreview(path, fullUrl) {
      hidden.value = path || '';
      if (path && preview) {
        preview.src = fullUrl || path;
        preview.classList.remove('hidden');
        if (placeholder) placeholder.classList.add('hidden');
      } else {
        if (preview) {
          preview.removeAttribute('src');
          preview.classList.add('hidden');
        }
        if (placeholder) placeholder.classList.remove('hidden');
      }
    }

    var initial = wrap.getAttribute('data-initial-url');
    var initialPath = wrap.getAttribute('data-initial-path');
    if (initialPath) setPreview(initialPath, initial);

    function uploadFile(file) {
      if (!file || !file.type.match(/^image\//)) {
        alert('Please choose an image file (JPG, PNG, or WebP).');
        return;
      }
      wrap.classList.add('is-uploading');
      var fd = new FormData();
      fd.append('file', file);
      fd.append('_csrf', csrf);
      fd.append('_response', 'json');
      fetch(url, { method: 'POST', body: fd, headers: { Accept: 'application/json' }, credentials: 'same-origin' })
        .then(function (r) { return r.json(); })
        .then(function (data) {
          wrap.classList.remove('is-uploading');
          if (data.error) {
            alert(data.error);
            return;
          }
          setPreview(data.path, data.url);
        })
        .catch(function () {
          wrap.classList.remove('is-uploading');
          alert('Upload failed. Try again.');
        });
    }

    input.addEventListener('change', function () {
      if (input.files && input.files[0]) uploadFile(input.files[0]);
    });

    ['dragenter', 'dragover'].forEach(function (ev) {
      wrap.addEventListener(ev, function (e) {
        e.preventDefault();
        e.stopPropagation();
        wrap.classList.add('is-dragover');
      });
    });
    ['dragleave', 'drop'].forEach(function (ev) {
      wrap.addEventListener(ev, function (e) {
        e.preventDefault();
        e.stopPropagation();
        wrap.classList.remove('is-dragover');
        if (ev === 'drop' && e.dataTransfer.files && e.dataTransfer.files[0]) {
          uploadFile(e.dataTransfer.files[0]);
        }
      });
    });

    var clearBtn = wrap.querySelector('[data-upload-clear]');
    if (clearBtn) {
      clearBtn.addEventListener('click', function () {
        setPreview('', '');
        input.value = '';
      });
    }

    wrap.dataset.uploadReady = '1';
  }

  function boot() {
    document.querySelectorAll('[data-image-upload]').forEach(initZone);
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', boot);
  } else {
    boot();
  }
})();
