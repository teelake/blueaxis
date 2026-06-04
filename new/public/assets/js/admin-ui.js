/**
 * Admin UI: repeaters and helpers
 */
document.addEventListener('alpine:init', function () {
  Alpine.data('adminRepeater', function (initialRows, emptyRow) {
    var empty = emptyRow || {};
    var rows = Array.isArray(initialRows) && initialRows.length ? initialRows : [Object.assign({}, empty)];
    return {
      rows: rows,
      add: function () {
        this.rows.push(Object.assign({}, empty));
      },
      remove: function (index) {
        if (this.rows.length <= 1) {
          this.rows[0] = Object.assign({}, empty);
          return;
        }
        this.rows.splice(index, 1);
      },
    };
  });

  Alpine.data('adminTabs', function (defaultTab) {
    return {
      tab: defaultTab || 'general',
    };
  });
});
