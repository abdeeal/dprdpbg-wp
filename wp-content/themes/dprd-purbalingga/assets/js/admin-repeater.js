/**
 * DPRD Admin Repeater — vanilla JS, tanpa dependency (pengganti ACF Repeater UI)
 * Menangani: tambah/hapus baris, nested children (sub-menu), field gambar via
 * WP Media Uploader, dan sync semua state ke 1 hidden input sebagai JSON
 * sebelum form disubmit.
 */
(function () {
  'use strict';

  document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.dprd-repeater').forEach(initRepeater);
  });

  function initRepeater(wrapper) {
    var isNestable = wrapper.dataset.nestable === '1';
    var tbody = wrapper.querySelector(':scope > table > tbody.dprd-repeater-rows');
    var hiddenInput = wrapper.querySelector(':scope > input.dprd-repeater-data');
    var addBtn = wrapper.querySelector(':scope > p > .dprd-add-row');
    var rowTemplate = wrapper.querySelector(':scope > template.dprd-row-template');

    function collectRow(rowEl) {
      var data = {};
      rowEl.querySelectorAll(':scope > td > input[data-key], :scope > td > textarea[data-key], :scope > td > .dprd-image-field > input[data-key]').forEach(function (input) {
        data[input.dataset.key] = input.value;
      });
      return data;
    }

    function collectAll() {
      var rows = [];
      tbody.querySelectorAll(':scope > tr.dprd-repeater-row').forEach(function (rowEl) {
        var rowData = collectRow(rowEl);

        if (isNestable) {
          var childrenRow = rowEl.nextElementSibling;
          if (childrenRow && childrenRow.classList.contains('dprd-repeater-children-row')) {
            var children = [];
            childrenRow.querySelectorAll('.dprd-repeater-child-row').forEach(function (childEl) {
              children.push(collectRow(childEl));
            });
            rowData.children = children;
          }
        }

        rows.push(rowData);
      });
      return rows;
    }

    function sync() {
      hiddenInput.value = JSON.stringify(collectAll());
    }

    function attachImageHandlers(scopeEl) {
      scopeEl.querySelectorAll('.dprd-select-image').forEach(function (btn) {
        if (btn.dataset.bound) return;
        btn.dataset.bound = '1';
        btn.addEventListener('click', function () {
          var field = btn.closest('.dprd-image-field');
          var frame = wp.media({
            title: 'Pilih Gambar',
            multiple: false,
            library: { type: 'image' },
          });
          frame.on('select', function () {
            var attachment = frame.state().get('selection').first().toJSON();
            field.querySelector('.dprd-image-id').value = attachment.id;
            field.querySelector('.dprd-image-preview').innerHTML =
              '<img src="' + (attachment.sizes && attachment.sizes.thumbnail ? attachment.sizes.thumbnail.url : attachment.url) + '" style="max-width:60px;max-height:60px;display:block;">';
            btn.textContent = 'Ganti Gambar';
            field.querySelector('.dprd-remove-image').style.display = '';
            sync();
          });
          frame.open();
        });
      });

      scopeEl.querySelectorAll('.dprd-remove-image').forEach(function (btn) {
        if (btn.dataset.bound) return;
        btn.dataset.bound = '1';
        btn.addEventListener('click', function () {
          var field = btn.closest('.dprd-image-field');
          field.querySelector('.dprd-image-id').value = '';
          field.querySelector('.dprd-image-preview').innerHTML = '';
          field.querySelector('.dprd-select-image').textContent = 'Pilih Gambar';
          btn.style.display = 'none';
          sync();
        });
      });
    }

    function attachRowHandlers(rowEl) {
      var removeBtn = rowEl.querySelector(':scope > td.dprd-row-actions > .dprd-remove-row');
      if (removeBtn) {
        removeBtn.addEventListener('click', function () {
          if (isNestable) {
            var childrenRow = rowEl.nextElementSibling;
            if (childrenRow && childrenRow.classList.contains('dprd-repeater-children-row')) {
              childrenRow.remove();
            }
          }
          rowEl.remove();
          sync();
        });
      }

      // input change listeners
      rowEl.querySelectorAll('input[data-key], textarea[data-key]').forEach(function (input) {
        input.addEventListener('input', sync);
      });

      attachImageHandlers(rowEl);

      if (isNestable) {
        var addChildBtn = rowEl.querySelector(':scope > td.dprd-row-actions > .dprd-add-child');
        if (addChildBtn) {
          addChildBtn.addEventListener('click', function () {
            var childrenRow = rowEl.nextElementSibling;
            var childTemplate = wrapper.querySelector(':scope > template.dprd-row-template').content
              ? null
              : null;
            var childRowTemplate = childrenRow.parentElement
              ? childrenRow.querySelector(':scope > td > template.dprd-child-row-template')
              : null;

            if (!childrenRow || !childrenRow.classList.contains('dprd-repeater-children-row')) return;

            var tpl = childrenRow.querySelector('template.dprd-child-row-template');
            var newChildRow = tpl.content.firstElementChild.cloneNode(true);
            var childrenTbody = childrenRow.querySelector('.dprd-repeater-children');
            childrenTbody.appendChild(newChildRow);
            attachChildRowHandlers(newChildRow);
            sync();
          });
        }
      }
    }

    function attachChildRowHandlers(childRowEl) {
      var removeBtn = childRowEl.querySelector('.dprd-remove-child');
      if (removeBtn) {
        removeBtn.addEventListener('click', function () {
          childRowEl.remove();
          sync();
        });
      }
      childRowEl.querySelectorAll('input[data-key], textarea[data-key]').forEach(function (input) {
        input.addEventListener('input', sync);
      });
      attachImageHandlers(childRowEl);
    }

    // Bind existing rows on load
    tbody.querySelectorAll(':scope > tr.dprd-repeater-row').forEach(attachRowHandlers);
    tbody.querySelectorAll('.dprd-repeater-child-row').forEach(attachChildRowHandlers);

    if (addBtn && rowTemplate) {
      addBtn.addEventListener('click', function () {
        // Clone the WHOLE template fragment so the row <tr> and (if nestable)
        // its paired children <tr> are cloned together and stay in sync.
        var fragment = rowTemplate.content.cloneNode(true);
        var newRow = fragment.querySelector('tr.dprd-repeater-row');
        var newChildrenRow = fragment.querySelector('tr.dprd-repeater-children-row');

        tbody.appendChild(newRow);
        if (newChildrenRow) {
          tbody.appendChild(newChildrenRow);
        }

        attachRowHandlers(newRow);
        sync();
      });
    }

    // Final safety sync before the containing form submits
    var form = wrapper.closest('form');
    if (form) {
      form.addEventListener('submit', sync);
    }

    // Initial sync so hidden input matches rendered state exactly
    sync();
  }
})();
