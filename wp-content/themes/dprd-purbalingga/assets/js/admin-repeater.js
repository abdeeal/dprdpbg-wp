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
            var cropRatio = btn.dataset.crop;
            console.log('Image selected!', { attachmentId: attachment.id, cropRatio: cropRatio, typeofCropper: typeof Cropper });
            
            if (cropRatio && typeof Cropper !== 'undefined') {
                console.log('Opening cropper modal...');
                openCropperModal(attachment, cropRatio, field, btn, sync);
            } else {
                console.log('Cropper not triggered. cropRatio:', cropRatio, 'Cropper:', typeof Cropper);
                field.querySelector('.dprd-image-id').value = attachment.id;
                field.querySelector('.dprd-image-preview').innerHTML =
                  '<img src="' + (attachment.sizes && attachment.sizes.thumbnail ? attachment.sizes.thumbnail.url : attachment.url) + '" style="max-width:60px;max-height:60px;display:block;">';
                btn.textContent = 'Ganti Gambar';
                field.querySelector('.dprd-remove-image').style.display = '';
                sync();
            }
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

  function openCropperModal(attachment, cropRatio, field, btn, syncCallback) {
      var ratioParts = cropRatio.split('/');
      var ratio = ratioParts.length === 2 ? parseInt(ratioParts[0]) / parseInt(ratioParts[1]) : NaN;

      var modal = document.createElement('div');
      modal.style.cssText = 'position:fixed; top:0; left:0; right:0; bottom:0; background:rgba(0,0,0,0.85); z-index:999999; display:flex; flex-direction:column; align-items:center; justify-content:center;';
      
      var container = document.createElement('div');
      container.style.cssText = 'width:90%; height:90%; max-width:1000px; background:#fff; padding:20px; box-sizing:border-box; border-radius:8px; display:flex; flex-direction:column;';
      
      var title = document.createElement('h2');
      title.textContent = 'Sesuaikan Crop (' + cropRatio + ')';
      title.style.cssText = 'margin-top:0;';
      
      var imgContainer = document.createElement('div');
      imgContainer.style.cssText = 'flex:1; overflow:hidden; background:#333; margin-bottom:20px; display:flex; align-items:center; justify-content:center;';
      
      var img = document.createElement('img');
      img.src = attachment.url;
      img.style.maxWidth = '100%';
      img.style.maxHeight = '100%';
      imgContainer.appendChild(img);
      
      var actions = document.createElement('div');
      actions.style.cssText = 'text-align:right; flex-shrink:0;';
      
      var cancelBtn = document.createElement('button');
      cancelBtn.type = 'button';
      cancelBtn.className = 'button';
      cancelBtn.textContent = 'Batal';
      cancelBtn.style.marginRight = '10px';
      
      var cropBtn = document.createElement('button');
      cropBtn.type = 'button';
      cropBtn.className = 'button button-primary';
      cropBtn.textContent = 'Crop & Gunakan';
      
      actions.appendChild(cancelBtn);
      actions.appendChild(cropBtn);
      
      container.appendChild(title);
      container.appendChild(imgContainer);
      container.appendChild(actions);
      modal.appendChild(container);
      document.body.appendChild(modal);

      var cropper = new Cropper(img, {
          aspectRatio: ratio || NaN,
          viewMode: 2,
      });

      cancelBtn.addEventListener('click', function() {
          cropper.destroy();
          modal.remove();
      });

      cropBtn.addEventListener('click', function() {
          cropBtn.textContent = 'Memproses...';
          cropBtn.disabled = true;
          
          cropper.getCroppedCanvas({
              maxWidth: 1920,
              maxHeight: 1080
          }).toBlob(function(blob) {
              var formData = new FormData();
              formData.append('action', 'dprd_upload_cropped_image');
              formData.append('image', blob, 'cropped-' + attachment.id + '.webp');
              formData.append('_ajax_nonce', dprd_repeater_vars.nonce);

              fetch(dprd_repeater_vars.ajax_url, {
                  method: 'POST',
                  body: formData
              })
              .then(function(res) { return res.json(); })
              .then(function(res) {
                  if (res.success) {
                      field.querySelector('.dprd-image-id').value = res.data.id;
                      field.querySelector('.dprd-image-preview').innerHTML =
                        '<img src="' + res.data.url + '" style="max-width:60px;max-height:60px;display:block;">';
                      btn.textContent = 'Ganti Gambar';
                      field.querySelector('.dprd-remove-image').style.display = '';
                      syncCallback();
                      cropper.destroy();
                      modal.remove();
                  } else {
                      alert('Gagal crop gambar: ' + (res.data || 'Error'));
                      cropBtn.textContent = 'Crop & Gunakan';
                      cropBtn.disabled = false;
                  }
              })
              .catch(function(err) {
                  alert('Terjadi kesalahan jaringan.');
                  cropBtn.textContent = 'Crop & Gunakan';
                  cropBtn.disabled = false;
              });
          }, 'image/webp', 0.75);
      });
  }

  // Points Field Event Delegation
  document.addEventListener('click', function(e) {
    if (e.target.classList.contains('dprd-add-point')) {
      var field = e.target.closest('.dprd-points-field');
      var list = field.querySelector('.dprd-points-list');
      
      var item = document.createElement('div');
      item.className = 'dprd-point-item';
      item.style.cssText = 'display:flex; align-items:center; gap:5px;';
      item.innerHTML = '<span class="dashicons dashicons-editor-justify" style="color:#888;"></span>' +
                       '<input type="text" class="widefat dprd-point-input" value="" style="flex:1;">' +
                       '<button type="button" class="button button-link dprd-remove-single-point" style="color:#a00; padding:0 5px;" title="Hapus poin ini">×</button>';
      list.appendChild(item);
      item.querySelector('input').focus();
      syncPoints(field);
    }
    
    if (e.target.classList.contains('dprd-remove-last-point')) {
      var field = e.target.closest('.dprd-points-field');
      var list = field.querySelector('.dprd-points-list');
      var items = list.querySelectorAll('.dprd-point-item');
      if (items.length > 0) {
        items[items.length - 1].remove();
        syncPoints(field);
      }
    }
    
    if (e.target.classList.contains('dprd-remove-single-point')) {
      var item = e.target.closest('.dprd-point-item');
      var field = e.target.closest('.dprd-points-field');
      item.remove();
      syncPoints(field);
    }
  });

  document.addEventListener('input', function(e) {
    if (e.target.classList.contains('dprd-point-input')) {
      var field = e.target.closest('.dprd-points-field');
      syncPoints(field);
    }
  });

  function syncPoints(field) {
    var hidden = field.querySelector('.dprd-points-hidden');
    var points = [];
    field.querySelectorAll('.dprd-point-input').forEach(function(input) {
      if (input.value.trim() !== '') {
        points.push(input.value);
      }
    });
    hidden.value = JSON.stringify(points);
    
    // Trigger input event to bubble and trigger the main repeater sync!
    var event = new Event('input', { bubbles: true });
    hidden.dispatchEvent(event);
  }
})();
