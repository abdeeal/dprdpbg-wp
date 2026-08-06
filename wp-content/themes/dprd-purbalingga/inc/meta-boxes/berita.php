<?php
/**
 * Meta Box for Berita (isFeatured)
 */

if (!defined('ABSPATH')) exit;

/**
 * Ekstrak hari dan tanggal rilis secara otomatis dari isi artikel berita
 */
function dprd_extract_indonesian_date($text) {
    if (empty($text)) return '';

    $text = html_entity_decode(strip_tags($text));

    $days = "Senin|Selasa|Rabu|Kamis|Jumat|Jum'at|Sabtu|Minggu";
    $months_regex = "Januari|Februari|Maret|April|Mei|Juni|Juli|Agustus|September|Oktober|November|Desember|Jan|Feb|Mar|Apr|Mei|Jun|Jul|Ags|Sep|Okt|Nov|Des";

    $months_map = [
        1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
        5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
        9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember',
        'jan' => 'Januari', 'feb' => 'Februari', 'mar' => 'Maret', 'apr' => 'April',
        'mei' => 'Mei', 'jun' => 'Juni', 'jul' => 'Juli', 'ags' => 'Agustus',
        'sep' => 'September', 'okt' => 'Oktober', 'nov' => 'November', 'des' => 'Desember'
    ];

    $format_date = function($day_name, $d_num, $m_val, $y_num) use ($months_map) {
        $m_lower = strtolower(trim($m_val));
        if (is_numeric($m_lower)) {
            $m_int = intval($m_lower);
            $month_name = isset($months_map[$m_int]) ? $months_map[$m_int] : $m_val;
        } else {
            $month_name = isset($months_map[$m_lower]) ? $months_map[$m_lower] : ucfirst($m_lower);
        }

        $d_int = intval($d_num);
        $day_clean = trim($day_name);

        if (!empty($day_clean)) {
            return $day_clean . ', ' . $d_int . ' ' . $month_name . ' ' . $y_num;
        }
        return $d_int . ' ' . $month_name . ' ' . $y_num;
    };

    // 1. Match "Kamis (09/07/2026)", "Jumat (17/7/2026)", "Jumat, 17/7/2026", "Jumat (17 Juli 2026)", "Jumat, 17 Juli 2026"
    $pattern1 = '/\b(' . $days . ')\s*[\(\,]\s*(\d{1,2})[\/\-\.](\d{1,2}|' . $months_regex . ')[\/\-\.](\d{4})\s*[\)]?/i';

    if (preg_match($pattern1, $text, $matches)) {
        return $format_date($matches[1], $matches[2], $matches[3], $matches[4]);
    }

    // 2. Match "Jumat, 17 Juli 2026" atau "Jumat 17 Juli 2026"
    $pattern2 = '/\b(' . $days . ')\s*,?\s*(\d{1,2})\s+(' . $months_regex . ')\s+(\d{4})\b/i';
    if (preg_match($pattern2, $text, $matches)) {
        return $format_date($matches[1], $matches[2], $matches[3], $matches[4]);
    }

    // 3. Match tanggal "17/7/2026" atau "09/07/2026" tanpa nama hari -> hitung nama harinya secara otomatis
    $pattern3 = '/\b(\d{1,2})[\/\-\.](\d{1,2})[\/\-\.](\d{4})\b/';
    if (preg_match($pattern3, $text, $matches)) {
        $d = intval($matches[1]);
        $m = intval($matches[2]);
        $y = intval($matches[3]);
        if (checkdate($m, $d, $y)) {
            $ts = mktime(0, 0, 0, $m, $d, $y);
            $day_num = date('N', $ts);
            $day_names = [1 => 'Senin', 2 => 'Selasa', 3 => 'Rabu', 4 => 'Kamis', 5 => 'Jumat', 6 => 'Sabtu', 7 => 'Minggu'];
            return $format_date($day_names[$day_num], $d, $m, $y);
        }
    }

    return '';
}

/**
 * Singleton untuk instance repeater Foto Tambahan Berita
 */
function dprd_get_berita_images_repeater() {
    static $instance = null;
    if ($instance === null) {
        $instance = new DPRD_Repeater_Field(
            'dprd_berita_images_json',
            'Foto-Foto Tambahan Berita (Disisipkan di Tengah Artikel)',
            null,
            [
                'image_id'  => ['label' => 'Foto Tambahan', 'type' => 'image', 'width' => '220px'],
                'caption'   => ['label' => 'Keterangan Foto (Caption)', 'type' => 'textarea', 'width' => 'auto'],
                'paragraph' => ['label' => 'Disisipkan Setelah Paragraf Ke- (Angka)', 'type' => 'text', 'width' => '220px'],
            ]
        );
    }
    return $instance;
}

/**
 * Singleton untuk instance repeater Kutipan Tambahan Berita
 */
function dprd_get_berita_quotes_repeater() {
    static $instance = null;
    if ($instance === null) {
        $instance = new DPRD_Repeater_Field(
            'dprd_berita_quotes_json',
            'Kutipan-Kutipan Berita (Blockquote di Tengah Artikel)',
            null,
            [
                'quote_text' => ['label' => 'Isi Teks Kutipan (Blockquote)', 'type' => 'textarea', 'width' => 'auto'],
                'paragraph'  => ['label' => 'Disisipkan Setelah Paragraf Ke- (Angka)', 'type' => 'text', 'width' => '220px'],
            ]
        );
    }
    return $instance;
}

// Inisialisasi early agar asset di-enqueue
add_action('admin_init', function() {
    dprd_get_berita_images_repeater();
    dprd_get_berita_quotes_repeater();
});

add_action('add_meta_boxes', function () {
    // Meta box di sidebar untuk status featured
    add_meta_box(
        'dprd_berita_meta',
        'Pengaturan Berita',
        'dprd_render_berita_meta_box',
        'berita',
        'side',
        'default'
    );

    // Meta box di bagian utama untuk metadata tambahan
    add_meta_box(
        'dprd_berita_additional_meta',
        'Informasi Tambahan Berita',
        'dprd_render_berita_additional_meta_box',
        'berita',
        'normal',
        'default'
    );

    // Meta box untuk galeri foto tambahan di berita (repeater)
    add_meta_box(
        'dprd_berita_images_meta',
        'Foto & Caption Tambahan Berita (Disisipkan di Tengah Artikel)',
        'dprd_render_berita_images_meta_box',
        'berita',
        'normal',
        'default'
    );

    // Meta box untuk kutipan / blockquote tambahan di berita (repeater)
    add_meta_box(
        'dprd_berita_quotes_meta',
        'Kutipan / Blockquote Berita (Disisipkan di Tengah Artikel)',
        'dprd_render_berita_quotes_meta_box',
        'berita',
        'normal',
        'default'
    );
});

function dprd_render_berita_meta_box($post) {
    wp_nonce_field('dprd_save_berita_meta', 'dprd_berita_meta_nonce');
    $is_featured = get_post_meta($post->ID, 'isFeatured', true);
    ?>
    <p>
        <label>
            <input type="checkbox" name="isFeatured" value="1" <?php checked($is_featured, '1'); ?>>
            Tampilkan di Slide Utama (Featured)
        </label>
    </p>
    <?php
}

function dprd_render_berita_additional_meta_box($post) {
    wp_nonce_field('dprd_save_berita_additional_meta', 'dprd_save_berita_additional_meta_nonce');
    $day = get_post_meta($post->ID, 'day', true);
    $time = get_post_meta($post->ID, 'time', true);
    $author = get_post_meta($post->ID, 'author', true);
    if (empty($author)) {
        $author = 'Humpro DPRD Kabupaten Purbalingga';
    }
    $image_caption = get_post_meta($post->ID, 'imageCaption', true);
    $quote_text = get_post_meta($post->ID, 'dprd_quote_text', true);
    $quote_paragraph = get_post_meta($post->ID, 'dprd_quote_paragraph', true);
    
    wp_enqueue_media();
    ?>
    <table class="form-table">
        <tr>
            <th><label for="dprd_day">Hari & Tanggal Rilis</label></th>
            <td>
                <div style="display: flex; gap: 8px; align-items: center;">
                    <input type="text" name="day" id="dprd_day" value="<?php echo esc_attr($day); ?>" placeholder="Contoh: Jumat (17/7/2026)" class="regular-text">
                    <button type="button" class="button" id="dprd_detect_date_btn" title="Deteksi otomatis tanggal rilis dari isi artikel">Deteksi dari Artikel</button>
                </div>
                <p class="description">Sistem akan otomatis mendeteksi tanggal dari isi berita (contoh: <em>Jumat (17/7/2026)</em>). Kosongkan jika tidak terdeteksi atau ingin diketik manual.</p>
            </td>
        </tr>
        <tr>
            <th><label for="dprd_time">Jam / Waktu Rilis</label></th>
            <td>
                <input type="text" name="time" id="dprd_time" value="<?php echo esc_attr($time); ?>" placeholder="Contoh: 18.43 WIB" class="regular-text">
                <p class="description">Bisa dikosongkan. Isi jika ingin menentukan jam rilis sendiri.</p>
            </td>
        </tr>
        <tr>
            <th><label for="dprd_author">Nama Penulis / Sumber</label></th>
            <td>
                <div style="display: flex; gap: 8px; align-items: center;">
                    <input type="text" name="author" id="dprd_author" value="<?php echo esc_attr($author); ?>" placeholder="Contoh: Humpro DPRD Kabupaten Purbalingga" class="regular-text">
                    <button type="button" class="button" id="dprd_preset_humpro_btn" title="Klik untuk mengisi otomatis Humpro DPRD Kabupaten Purbalingga">Humpro DPRD</button>
                </div>
                <p class="description">Default: <strong>Humpro DPRD Kabupaten Purbalingga</strong>. Klik tombol <em>Humpro DPRD</em> jika ingin mengisinya secara cepat.</p>
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <p class="description" style="padding: 8px; background: #f0f7ff; border-left: 4px solid #2271b1; margin: 0;">
                    <strong>Ringkasan Berita:</strong> Diisi via kolom "Kutipan" di sidebar kanan editor.
                </p>
            </td>
        </tr>
        <tr>
            <th>
                <label for="dprd_image_caption">Keterangan Foto Utama (Caption & Sumber Foto) <span style="color: #d63638;">*</span></label>
            </th>
            <td>
                <textarea name="imageCaption" id="dprd_image_caption" rows="2" class="large-text" required placeholder="Contoh: Suasana Rapat Paripurna DPRD Purbalingga bersama Bupati (Foto: Humas DPRD)" style="overflow-y: hidden; min-height: 60px; line-height: 1.5; resize: vertical; box-sizing: border-box; width: 100%;"><?php echo esc_textarea($image_caption); ?></textarea>
                <p class="description">Teks keterangan atau sumber foto (caption) yang akan tampil tepat di bawah foto utama di halaman detail berita. <strong>Wajib diisi.</strong> Kotak akan otomatis melebar saat mengetik teks panjang.</p>
            </td>
        </tr>
    </table>
    
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        var dayInput = document.getElementById('dprd_day');
        var detectBtn = document.getElementById('dprd_detect_date_btn');

        // Fungsi pendeteksi tanggal dari isi berita
        function extractDateFromArticleText(text) {
            if (!text) return '';
            var cleanText = text.replace(/<[^>]*>/g, ' ').replace(/&nbsp;/g, ' ');
            
            var days = "Senin|Selasa|Rabu|Kamis|Jumat|Jum'at|Sabtu|Minggu";
            var monthsRegex = "Januari|Februari|Maret|April|Mei|Juni|Juli|Agustus|September|Oktober|November|Desember|Jan|Feb|Mar|Apr|Mei|Jun|Jul|Ags|Sep|Okt|Nov|Des";
            
            var monthsMap = {
                1: 'Januari', 2: 'Februari', 3: 'Maret', 4: 'April',
                5: 'Mei', 6: 'Juni', 7: 'Juli', 8: 'Agustus',
                9: 'September', 10: 'Oktober', 11: 'November', 12: 'Desember',
                'jan': 'Januari', 'feb': 'Februari', 'mar': 'Maret', 'apr': 'April',
                'mei': 'Mei', 'jun': 'Juni', 'jul': 'Juli', 'ags': 'Agustus',
                'sep': 'September', 'okt': 'Oktober', 'nov': 'November', 'des': 'Desember'
            };

            function formatDate(dayName, dNum, mVal, yNum) {
                var mLower = String(mVal).trim().toLowerCase();
                var monthName = mVal;
                if (!isNaN(mLower)) {
                    var mInt = parseInt(mLower, 10);
                    if (monthsMap[mInt]) monthName = monthsMap[mInt];
                } else if (monthsMap[mLower]) {
                    monthName = monthsMap[mLower];
                }
                var dInt = parseInt(dNum, 10);
                var dayClean = String(dayName).trim();
                if (dayClean) {
                    return dayClean + ', ' + dInt + ' ' + monthName + ' ' + yNum;
                }
                return dInt + ' ' + monthName + ' ' + yNum;
            }

            // Pattern 1: Hari (DD/MM/YYYY) atau Hari, DD/MM/YYYY
            var regex1 = new RegExp('\\b(' + days + ')\\s*[\\(\\,]\\s*(\\d{1,2})[\\/\\-\\.](\\d{1,2}|' + monthsRegex + ')[\\/\\-\\.](\\d{4})\\s*[\\)]?', 'i');
            var match1 = cleanText.match(regex1);
            if (match1) {
                return formatDate(match1[1], match1[2], match1[3], match1[4]);
            }
            
            // Pattern 2: Hari, DD Month YYYY
            var regex2 = new RegExp('\\b(' + days + ')\\s*,?\\s*(\\d{1,2})\\s+(' + monthsRegex + ')\\s+(\\d{4})\\b', 'i');
            var match2 = cleanText.match(regex2);
            if (match2) {
                return formatDate(match2[1], match2[2], match2[3], match2[4]);
            }

            return '';
        }

        function runAutoDetectDate(force) {
            if (!dayInput) return;
            if (!force && dayInput.value.trim() !== '') return;

            var content = '';
            if (typeof wp !== 'undefined' && wp.data && wp.data.select && wp.data.select('core/editor')) {
                content = wp.data.select('core/editor').getEditedPostContent();
            } else {
                var contentElem = document.getElementById('content');
                if (contentElem) content = contentElem.value;
            }

            var detected = extractDateFromArticleText(content);
            if (detected) {
                dayInput.value = detected;
            }
        }

        if (detectBtn) {
            detectBtn.addEventListener('click', function(e) {
                e.preventDefault();
                runAutoDetectDate(true);
            });
        }

        var authorInput = document.getElementById('dprd_author');
        var presetHumproBtn = document.getElementById('dprd_preset_humpro_btn');
        if (presetHumproBtn && authorInput) {
            presetHumproBtn.addEventListener('click', function(e) {
                e.preventDefault();
                authorInput.value = 'Humpro DPRD Kabupaten Purbalingga';
            });
        }

        // Auto detect saat Gutenberg selesai dimuat jika field day kosong
        if (typeof wp !== 'undefined' && wp.data && wp.data.select) {
            setTimeout(function() {
                runAutoDetectDate(false);
            }, 1500);
        }

        var captionField = document.getElementById('dprd_image_caption');
        if (!captionField) return;

        // Auto-expand tinggi box caption secara otomatis saat diketik
        function autoExpandCaption(elem) {
            if (!elem) return;
            elem.style.height = 'auto';
            elem.style.height = Math.max(60, elem.scrollHeight + 4) + 'px';
        }

        autoExpandCaption(captionField);
        captionField.addEventListener('input', function() { autoExpandCaption(this); });
        captionField.addEventListener('change', function() { autoExpandCaption(this); });

        // Validasi untuk Editor Gutenberg
        if (typeof wp !== 'undefined' && wp.data && wp.data.dispatch && wp.data.select) {
            function validateCaption() {
                var val = captionField.value.trim();
                if (val === '') {
                    wp.data.dispatch('core/editor').lockPostSaving('empty_caption_lock');
                } else {
                    wp.data.dispatch('core/editor').unlockPostSaving('empty_caption_lock');
                }
            }
            
            // Cek saat pertama kali dimuat (diberi jeda agar Gutenberg siap)
            setTimeout(validateCaption, 1000);
            
            // Cek setiap kali diketik
            captionField.addEventListener('input', validateCaption);
            captionField.addEventListener('change', validateCaption);
        }

        // Validasi untuk Classic Editor / Form standar
        var form = captionField.closest('form');
        if (form) {
            form.addEventListener('submit', function(e) {
                if (captionField.value.trim() === '') {
                    alert('Keterangan Foto Utama pada Berita harus diisi!');
                    captionField.focus();
                    e.preventDefault();
                }
            });
        }
    });
    </script>
    <?php
}

function dprd_render_berita_images_meta_box($post) {
    wp_nonce_field('dprd_save_berita_images', 'dprd_berita_images_nonce');
    $rows = get_dprd_repeater($post->ID, 'dprd_berita_images_json');

    // Fallback pre-fill jika repeater kosong tapi ada data dari field tunggal lama
    if (empty($rows)) {
        $old_img_id = get_post_meta($post->ID, 'additional_image_id', true);
        $old_caption = get_post_meta($post->ID, 'additional_image_caption', true);
        $old_para = get_post_meta($post->ID, 'additional_image_paragraph', true);
        if ($old_img_id && $old_para > 0) {
            $rows[] = [
                'image_id'  => $old_img_id,
                'caption'   => $old_caption,
                'paragraph' => $old_para
            ];
        }
    }

    echo '<p class="description" style="margin-bottom: 12px; font-size: 13px; line-height: 1.6;">' .
         'Tambahkan foto tambahan dan caption untuk disisipkan di tengah artikel.<br>' .
         'Isi kolom angka paragraf untuk menentukan posisi tampilnya foto.</p>';
    dprd_get_berita_images_repeater()->render_field_only($rows);
    ?>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        function getEditorParagraphs() {
            var text = '';
            // Cek Block Editor (Gutenberg)
            if (typeof wp !== 'undefined' && wp.data && wp.data.select && wp.data.select('core/editor')) {
                text = wp.data.select('core/editor').getEditedPostContent();
            } else if (typeof tinyMCE !== 'undefined' && tinyMCE.activeEditor && !tinyMCE.activeEditor.isHidden()) {
                text = tinyMCE.activeEditor.getContent();
            } else {
                var textarea = document.getElementById('content');
                if (textarea) text = textarea.value;
            }

            if (!text) return [];

            // Buat temp container untuk strip HTML
            var temp = document.createElement('div');
            temp.innerHTML = text;

            var pElements = temp.querySelectorAll('p');
            var paragraphs = [];

            if (pElements.length > 0) {
                pElements.forEach(function(p) {
                    var cleanText = p.textContent.trim();
                    if (cleanText.length > 0) {
                        paragraphs.push(cleanText);
                    }
                });
            } else {
                // Fallback splitting by newline if no <p> tags
                var rawLines = temp.textContent.split(/\n+/);
                rawLines.forEach(function(line) {
                    var cleanText = line.trim();
                    if (cleanText.length > 0) {
                        paragraphs.push(cleanText);
                    }
                });
            }

            return paragraphs;
        }

        function updateParagraphPreviews() {
            var paragraphs = getEditorParagraphs();

            document.querySelectorAll('.dprd-repeater input[data-key="paragraph"]').forEach(function(input) {
                var cell = input.closest('td');
                if (!cell) return;

                var hint = cell.querySelector('.dprd-paragraph-hint');
                if (!hint) {
                    hint = document.createElement('div');
                    hint.className = 'dprd-paragraph-hint';
                    hint.style.cssText = 'margin-top: 6px; font-size: 11px; line-height: 1.4; padding: 4px 8px; border-radius: 4px; transition: all 0.2s ease;';
                    cell.appendChild(hint);
                }

                var num = parseInt(input.value.trim(), 10);
                if (isNaN(num) || num <= 0) {
                    hint.style.display = 'none';
                    return;
                }

                if (num <= paragraphs.length) {
                    var pText = paragraphs[num - 1];
                    var words = pText.split(/\s+/);
                    var snippet = words.length > 8 ? '...' + words.slice(-8).join(' ') : pText;

                    hint.style.display = 'block';
                    hint.style.background = '#e7f5ea';
                    hint.style.border = '1px solid #7ad08d';
                    hint.style.color = '#135e23';
                    hint.innerHTML = '<strong>Akhir Paragraf ke-' + num + ':</strong> "' + escHtml(snippet) + '"';
                } else {
                    hint.style.display = 'block';
                    hint.style.background = '#fcf0f0';
                    hint.style.border = '1px solid #f5c6cb';
                    hint.style.color = '#a00';
                    hint.innerHTML = 'Artikel hanya memiliki <strong>' + paragraphs.length + ' paragraf</strong>.';
                }
            });
        }

        function escHtml(str) {
            return str.replace(/&/g, "&amp;").replace(/</g, "&lt;").replace(/>/g, "&gt;").replace(/"/g, "&quot;").replace(/'/g, "&#039;");
        }

        // Event listener saat diketik atau diubah
        document.addEventListener('input', function(e) {
            if (e.target && e.target.matches('input[data-key="paragraph"]')) {
                updateParagraphPreviews();
            }
        });

        // Event listener jika artikel di editor Gutenberg atau TinyMCE berubah
        if (typeof wp !== 'undefined' && wp.data && wp.data.subscribe) {
            wp.data.subscribe(function() {
                updateParagraphPreviews();
            });
        }

        setInterval(updateParagraphPreviews, 2000);
        setTimeout(updateParagraphPreviews, 1000);
    });
    </script>
    <?php
}

function dprd_render_berita_quotes_meta_box($post) {
    wp_nonce_field('dprd_save_berita_quotes', 'dprd_berita_quotes_nonce');
    $rows = get_dprd_repeater($post->ID, 'dprd_berita_quotes_json');

    // Fallback pre-fill jika repeater kosong tapi ada data kutipan tunggal lama
    if (empty($rows)) {
        $old_quote = get_post_meta($post->ID, 'dprd_quote_text', true);
        $old_para = get_post_meta($post->ID, 'dprd_quote_paragraph', true);
        if (!empty($old_quote) && $old_para > 0) {
            $rows[] = [
                'quote_text' => $old_quote,
                'paragraph'  => $old_para
            ];
        }
    }

    echo '<p class="description" style="margin-bottom: 12px; font-size: 13px; line-height: 1.6;">' .
         'Tambahkan teks kutipan (blockquote) untuk disisipkan di tengah artikel. Tambahkan tanda kutip <code>"</code> secara manual pada kalimat narasumber jika diperlukan.<br>' .
         'Isi kolom angka paragraf untuk menentukan posisi tampilnya kutipan.</p>';
    dprd_get_berita_quotes_repeater()->render_field_only($rows);
}

add_action('save_post', function ($post_id) {
    // 1. Simpan metadata Featured
    if (isset($_POST['dprd_berita_meta_nonce']) && wp_verify_nonce($_POST['dprd_berita_meta_nonce'], 'dprd_save_berita_meta')) {
        if (!defined('DOING_AUTOSAVE') || !DOING_AUTOSAVE) {
            if (current_user_can('edit_post', $post_id)) {
                $is_featured = isset($_POST['isFeatured']) ? '1' : '0';
                update_post_meta($post_id, 'isFeatured', $is_featured);
            }
        }
    }

    // 2. Simpan metadata tambahan
    if (isset($_POST['dprd_save_berita_additional_meta_nonce']) && wp_verify_nonce($_POST['dprd_save_berita_additional_meta_nonce'], 'dprd_save_berita_additional_meta')) {
        if (!defined('DOING_AUTOSAVE') || !DOING_AUTOSAVE) {
            if (current_user_can('edit_post', $post_id)) {
                if (isset($_POST['day'])) {
                    $day_val = sanitize_text_field($_POST['day']);
                    if (empty($day_val)) {
                        $post_obj = get_post($post_id);
                        if ($post_obj && !empty($post_obj->post_content)) {
                            $day_val = dprd_extract_indonesian_date($post_obj->post_content);
                        }
                    }
                    update_post_meta($post_id, 'day', $day_val);
                }
                if (isset($_POST['time'])) {
                    update_post_meta($post_id, 'time', sanitize_text_field($_POST['time']));
                }
                if (isset($_POST['author'])) {
                    $author_val = sanitize_text_field($_POST['author']);
                    if (empty($author_val)) {
                        $author_val = 'Humpro DPRD Kabupaten Purbalingga';
                    }
                    update_post_meta($post_id, 'author', $author_val);
                }
                if (isset($_POST['imageCaption'])) {
                    update_post_meta($post_id, 'imageCaption', sanitize_textarea_field($_POST['imageCaption']));
                }
            }
        }
    }

    // 3. Simpan Repeater Foto Tambahan Berita
    if (isset($_POST['dprd_berita_images_nonce']) && wp_verify_nonce($_POST['dprd_berita_images_nonce'], 'dprd_save_berita_images')) {
        if (!defined('DOING_AUTOSAVE') || !DOING_AUTOSAVE) {
            if (current_user_can('edit_post', $post_id)) {
                if (isset($_POST['dprd_berita_images_json'])) {
                    $repeater = dprd_get_berita_images_repeater();
                    $clean_json = $repeater->sanitize_from_post($_POST['dprd_berita_images_json']);
                    update_post_meta($post_id, 'dprd_berita_images_json', wp_slash($clean_json));
                }
            }
        }
    }

    // 4. Simpan Repeater Kutipan Tambahan Berita
    if (isset($_POST['dprd_berita_quotes_nonce']) && wp_verify_nonce($_POST['dprd_berita_quotes_nonce'], 'dprd_save_berita_quotes')) {
        if (!defined('DOING_AUTOSAVE') || !DOING_AUTOSAVE) {
            if (current_user_can('edit_post', $post_id)) {
                if (isset($_POST['dprd_berita_quotes_json'])) {
                    $repeater = dprd_get_berita_quotes_repeater();
                    $clean_json = $repeater->sanitize_from_post($_POST['dprd_berita_quotes_json']);
                    update_post_meta($post_id, 'dprd_berita_quotes_json', wp_slash($clean_json));
                }
            }
        }
    }
});

// Ubah placeholder "Tambahkan judul" khusus untuk CPT Berita
add_filter('enter_title_here', function ($title, $post) {
    if (is_object($post) && isset($post->post_type) && $post->post_type === 'berita') {
        return 'Tambahkan judul berita';
    }
    return $title;
}, 10, 2);
