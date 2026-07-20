<?php
/**
 * DPRD_Repeater_Field
 * Reusable Repeater Meta Box (pengganti ACF Repeater — 100% native & gratis)
 *
 * Data disimpan sebagai JSON di 1 meta key (post meta) ATAU 1 wp_option
 * (untuk options page), decode via get_dprd_repeater() / dprd_get_option_repeater().
 *
 * Mendukung:
 * - Field text biasa
 * - Field textarea
 * - Field image (pakai WP Media Uploader, simpan attachment ID)
 * - Field url
 * - Nested children (1 level) — dipakai untuk navigasi menu bercabang
 *
 * Bisa dipakai dengan 2 mode:
 * 1. Meta box di post type:  new DPRD_Repeater_Field([...], 'meta_box', $post_type)
 * 2. Field mandiri di options page: render_field_only() dipanggil manual dari
 *    dprd_render_site_settings_page(), lalu save_from_options() dipanggil saat submit.
 *
 * @package DPRD_Purbalingga
 */

if (!defined('ABSPATH')) {
    exit; // no direct access
}

class DPRD_Repeater_Field {

    private $id;
    private $title;
    private $post_type;
    private $sub_fields;   // ['key' => ['label' => 'Nama', 'type' => 'text|textarea|image|url']]
    private $nestable;     // bool — apakah tiap baris punya children (nested, 1 level)
    private $context;      // meta box context: 'normal' | 'side'
    private static $printed_assets = false;

    /**
     * @param string $id         Meta key / option name
     * @param string $title      Judul meta box
     * @param string|null $post_type  Post type target. Isi null kalau field ini
     *                                dipakai manual di options page (bukan lewat add_meta_box).
     * @param array  $sub_fields Kolom-kolom repeater, format:
     *                           ['title' => ['label' => 'Judul', 'type' => 'text']]
     *                           Boleh singkat: ['title' => 'Judul'] (default type = text)
     * @param bool   $nestable   True kalau tiap baris boleh punya children (nested 1 level)
     */
    public function __construct($id, $title, $post_type, $sub_fields, $nestable = false) {
        $this->id         = $id;
        $this->title      = $title;
        $this->post_type  = $post_type;
        $this->sub_fields = $this->normalize_sub_fields($sub_fields);
        $this->nestable   = $nestable;
        $this->context    = 'normal';

        if ($this->post_type) {
            add_action('add_meta_boxes', [$this, 'register']);
            add_action('save_post', [$this, 'save']);
        }

        add_action('admin_enqueue_scripts', [$this, 'enqueue_assets']);
    }

    private function normalize_sub_fields($sub_fields) {
        $normalized = [];
        foreach ($sub_fields as $key => $def) {
            if (is_string($def)) {
                $normalized[$key] = ['label' => $def, 'type' => 'text'];
            } else {
                $normalized[$key] = wp_parse_args($def, ['label' => $key, 'type' => 'text']);
            }
        }
        return $normalized;
    }

    public function enqueue_assets($hook) {
        // Load di semua halaman admin post edit + halaman options kita.
        $is_relevant = in_array($hook, ['post.php', 'post-new.php'], true)
            || (isset($_GET['page']) && strpos($_GET['page'], 'dprd-') === 0);

        if (!$is_relevant) return;

        wp_enqueue_media(); // WP media uploader untuk field image

        wp_enqueue_style(
            'dprd-admin-repeater',
            get_template_directory_uri() . '/assets/css/admin-repeater.css',
            [],
            '1.0.0'
        );

        wp_enqueue_script(
            'dprd-admin-repeater',
            get_template_directory_uri() . '/assets/js/admin-repeater.js',
            [],
            '1.0.0',
            true
        );
    }

    public function register() {
        add_meta_box($this->id, $this->title, [$this, 'render_meta_box'], $this->post_type, $this->context, 'default');
    }

    public function render_meta_box($post) {
        wp_nonce_field('dprd_repeater_' . $this->id, $this->id . '_nonce');
        $raw  = get_post_meta($post->ID, $this->id, true);
        $rows = $raw ? json_decode($raw, true) : [];
        $this->render_field_only($rows);
    }

    /**
     * Render markup repeater saja, tanpa nonce/meta box wrapper.
     * Dipakai baik dari meta box (post) maupun manual di options page.
     */
    public function render_field_only($rows) {
        if (!is_array($rows)) $rows = [];
        ?>
        <div class="dprd-repeater" data-field-id="<?php echo esc_attr($this->id); ?>" data-nestable="<?php echo $this->nestable ? '1' : '0'; ?>">
            <table class="widefat striped dprd-repeater-table">
                <thead>
                    <tr>
                        <?php if ($this->nestable) : ?><th style="width:24px;"></th><?php endif; ?>
                        <?php foreach ($this->sub_fields as $def) : ?>
                            <th><?php echo esc_html($def['label']); ?></th>
                        <?php endforeach; ?>
                        <th style="width:1%;"></th>
                    </tr>
                </thead>
                <tbody class="dprd-repeater-rows">
                    <?php foreach ($rows as $row) : ?>
                        <?php $this->render_row($row); ?>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <p>
                <button type="button" class="button dprd-add-row">+ Tambah Baris</button>
            </p>
            <template class="dprd-row-template">
                <?php $this->render_row([], true); ?>
            </template>
            <input type="hidden" class="dprd-repeater-data" name="<?php echo esc_attr($this->id); ?>" value="<?php echo esc_attr(wp_json_encode($rows)); ?>">
        </div>
        <?php
    }

    private function render_row($row, $is_template = false) {
        $row_class = 'dprd-repeater-row' . ($this->nestable ? ' dprd-repeater-row--parent' : '');
        echo '<tr class="' . esc_attr($row_class) . '">';

        if ($this->nestable) {
            echo '<td><span class="dashicons dashicons-menu dprd-drag-handle" title="Geser"></span></td>';
        }

        foreach ($this->sub_fields as $key => $def) {
            $value = $row[$key] ?? '';
            echo '<td>' . $this->render_field_input($key, $def, $value) . '</td>';
        }

        echo '<td class="dprd-row-actions">';
        if ($this->nestable) {
            echo '<button type="button" class="button button-small dprd-add-child" title="Tambah sub-menu">+ Sub</button> ';
        }
        echo '<button type="button" class="button button-small dprd-remove-row">Hapus</button>';
        echo '</td>';
        echo '</tr>';

        if ($this->nestable) {
            $children = $row['children'] ?? [];
            echo '<tr class="dprd-repeater-children-row"><td></td><td colspan="' . (count($this->sub_fields) + 1) . '">';
            echo '<table class="widefat dprd-repeater-children-table"><tbody class="dprd-repeater-children">';
            foreach ($children as $child) {
                $this->render_child_row($child);
            }
            echo '</tbody></table>';
            echo '<template class="dprd-child-row-template">';
            $this->render_child_row([]);
            echo '</template>';
            echo '</td></tr>';
        }
    }

    private function render_child_row($child) {
        echo '<tr class="dprd-repeater-child-row">';
        foreach ($this->sub_fields as $key => $def) {
            $value = $child[$key] ?? '';
            echo '<td>' . $this->render_field_input($key, $def, $value) . '</td>';
        }
        echo '<td><button type="button" class="button button-small dprd-remove-child">Hapus</button></td>';
        echo '</tr>';
    }

    private function render_field_input($key, $def, $value) {
        $type = $def['type'];

        switch ($type) {
            case 'textarea':
                return sprintf(
                    '<textarea class="widefat" rows="2" data-key="%s">%s</textarea>',
                    esc_attr($key),
                    esc_textarea($value)
                );

            case 'image':
                $image_url = $value ? wp_get_attachment_image_url((int) $value, 'thumbnail') : '';
                return sprintf(
                    '<div class="dprd-image-field">
                        <input type="hidden" class="dprd-image-id" data-key="%s" value="%s">
                        <div class="dprd-image-preview">%s</div>
                        <button type="button" class="button button-small dprd-select-image">%s</button>
                        <button type="button" class="button-link dprd-remove-image" style="%s">Hapus gambar</button>
                    </div>',
                    esc_attr($key),
                    esc_attr($value),
                    $image_url ? '<img src="' . esc_url($image_url) . '" style="max-width:60px;max-height:60px;display:block;">' : '',
                    $value ? 'Ganti Gambar' : 'Pilih Gambar',
                    $value ? '' : 'display:none;'
                );

            case 'url':
                return sprintf(
                    '<input type="url" class="widefat" data-key="%s" value="%s" placeholder="https://">',
                    esc_attr($key),
                    esc_attr($value)
                );

            case 'text':
            default:
                return sprintf(
                    '<input type="text" class="widefat" data-key="%s" value="%s">',
                    esc_attr($key),
                    esc_attr($value)
                );
        }
    }

    /**
     * Save handler untuk mode meta box (post).
     */
    public function save($post_id) {
        if (!isset($_POST[$this->id . '_nonce']) || !wp_verify_nonce($_POST[$this->id . '_nonce'], 'dprd_repeater_' . $this->id)) {
            return;
        }
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
        if (!current_user_can('edit_post', $post_id)) return;
        if (!isset($_POST[$this->id])) return;

        $sanitized = $this->sanitize_from_post($_POST[$this->id]);
        update_post_meta($post_id, $this->id, wp_json_encode($sanitized));
    }

    /**
     * Save handler untuk mode options page — dipanggil manual dari
     * handler form options page, BUKAN lewat hook save_post.
     *
     * @param string $raw_json JSON string dari $_POST[$this->id]
     * @return string JSON string yang sudah tersanitasi, siap update_option()
     */
    public function sanitize_from_post($raw_json) {
        $json    = wp_unslash($raw_json);
        $decoded = json_decode($json, true);

        if (!is_array($decoded)) return wp_json_encode([]);

        $clean_rows = [];
        foreach ($decoded as $row) {
            $clean_row = [];
            foreach (array_keys($this->sub_fields) as $key) {
                $type = $this->sub_fields[$key]['type'];
                $val  = $row[$key] ?? '';

                if ($type === 'image') {
                    $clean_row[$key] = absint($val);
                } elseif ($type === 'url') {
                    $clean_row[$key] = esc_url_raw($val);
                } elseif ($type === 'textarea') {
                    $clean_row[$key] = sanitize_textarea_field($val);
                } else {
                    $clean_row[$key] = sanitize_text_field($val);
                }
            }

            if ($this->nestable && isset($row['children']) && is_array($row['children'])) {
                $clean_children = [];
                foreach ($row['children'] as $child) {
                    $clean_child = [];
                    foreach (array_keys($this->sub_fields) as $key) {
                        $type = $this->sub_fields[$key]['type'];
                        $val  = $child[$key] ?? '';
                        if ($type === 'image') {
                            $clean_child[$key] = absint($val);
                        } elseif ($type === 'url') {
                            $clean_child[$key] = esc_url_raw($val);
                        } elseif ($type === 'textarea') {
                            $clean_child[$key] = sanitize_textarea_field($val);
                        } else {
                            $clean_child[$key] = sanitize_text_field($val);
                        }
                    }
                    $clean_children[] = $clean_child;
                }
                $clean_row['children'] = $clean_children;
            }

            $clean_rows[] = $clean_row;
        }

        return wp_json_encode($clean_rows);
    }
}

/**
 * Helper ambil data repeater dari post meta — mirip have_rows()/get_field() ACF.
 *
 * @param int    $post_id
 * @param string $field_id
 * @return array
 */
function get_dprd_repeater($post_id, $field_id) {
    $raw = get_post_meta($post_id, $field_id, true);
    $decoded = $raw ? json_decode($raw, true) : [];
    return is_array($decoded) ? $decoded : [];
}

/**
 * Helper ambil data repeater dari wp_option (dipakai di options page) —
 * mirip get_field() ACF untuk options page.
 *
 * @param string $option_name
 * @return array
 */
function dprd_get_option_repeater($option_name) {
    $raw = get_option($option_name, '[]');
    $decoded = json_decode($raw, true);
    return is_array($decoded) ? $decoded : [];
}
