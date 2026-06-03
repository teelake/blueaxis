/**
 * Quill rich editor initializer (Quill.js — BSD-3-Clause, free)
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

  function initWrap(wrap) {
    var id = wrap.getAttribute('data-editor-id');
    var mount = document.getElementById(id + '_mount');
    var source = document.getElementById(id);
    if (!mount || !source || mount.dataset.quillReady) return;

    var quill = new Quill(mount, {
      theme: 'snow',
      modules: {
        toolbar: [
          [{ header: [2, 3, false] }],
          ['bold', 'italic', 'underline', 'strike'],
          [{ list: 'ordered' }, { list: 'bullet' }],
          ['blockquote', 'link'],
          ['clean'],
        ],
      },
    });

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
