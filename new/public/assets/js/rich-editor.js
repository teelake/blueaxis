/**
 * Quill rich editor (image upload to media library)
 */
(function () {
  var loaded = false;
  var queue = [];

  function loadQuill(cb) {
    if (typeof Quill !== 'undefined') {
      cb();
      return;
    }
    if (!loaded) {
      loaded = true;
      var link = document.createElement('link');
      link.rel = 'stylesheet';
      link.href = 'https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.snow.css';
      document.head.appendChild(link);
      var script = document.createElement('script');
      script.src = 'https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.js';
      script.onload = function () {
        queue.forEach(function (fn) {
          fn();
        });
        queue = [];
      };
      document.head.appendChild(script);
    }
    queue.push(cb);
  }

  function uploadImage(file, uploadUrl, csrf) {
    var fd = new FormData();
    fd.append('file', file);
    fd.append('_csrf', csrf);
    fd.append('_response', 'json');
    return fetch(uploadUrl, {
      method: 'POST',
      body: fd,
      headers: { Accept: 'application/json' },
      credentials: 'same-origin',
    }).then(function (r) {
      return r.json();
    });
  }

  function imageHandler(quill, uploadUrl, csrf) {
    var input = document.createElement('input');
    input.type = 'file';
    input.accept = 'image/*';
    input.onchange = function () {
      var file = input.files && input.files[0];
      if (!file) return;
      uploadImage(file, uploadUrl, csrf)
        .then(function (data) {
          if (data.error) {
            alert(data.error);
            return;
          }
          var url = data.url || '';
          var range = quill.getSelection(true);
          quill.insertEmbed(range.index, 'image', url, 'user');
          quill.setSelection(range.index + 1);
        })
        .catch(function () {
          alert('Image upload failed.');
        });
    };
    input.click();
  }

  function initWrap(wrap) {
    var id = wrap.getAttribute('data-editor-id');
    var mount = document.getElementById(id + '_mount');
    var source = document.getElementById(id);
    if (!mount || !source || mount.dataset.quillReady) return;

    var uploadUrl = wrap.getAttribute('data-upload-url') || '';
    var csrf = wrap.getAttribute('data-csrf') || '';
    var toolbar = [
      [{ header: [2, 3, false] }],
      ['bold', 'italic', 'underline', 'strike'],
      [{ list: 'ordered' }, { list: 'bullet' }],
      ['blockquote', 'link'],
      ['clean'],
    ];
    if (uploadUrl && csrf) {
      toolbar.splice(5, 0, ['image']);
    }

    var quill = new Quill(mount, {
      theme: 'snow',
      modules: {
        toolbar: toolbar,
      },
    });

    if (uploadUrl && csrf) {
      quill.getModule('toolbar').addHandler('image', function () {
        imageHandler(quill, uploadUrl, csrf);
      });
    }

    if (source.value.trim()) {
      quill.root.innerHTML = source.value;
    }

    mount.dataset.quillReady = '1';
    var form = wrap.closest('form');
    if (form) {
      form.addEventListener('submit', function () {
        source.value = quill.root.innerHTML;
      });
    }
  }

  function boot() {
    document.querySelectorAll('.rich-editor-wrap').forEach(function (wrap) {
      loadQuill(function () {
        initWrap(wrap);
      });
    });
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', boot);
  } else {
    boot();
  }
})();
