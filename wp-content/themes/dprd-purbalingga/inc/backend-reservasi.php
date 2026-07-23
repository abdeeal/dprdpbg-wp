<?php
/**
 * Custom Meta Box & Handler Backend untuk Reservasi Kunjungan
 *
 * @package DPRD_Purbalingga
 */

if (!defined('ABSPATH')) exit;

// 1. Registrasi Meta Box Reservasi di Admin WordPress
add_action('add_meta_boxes', function () {
    add_meta_box(
        'dprd_reservasi_detail',
        'Detail Permohonan Reservasi Kunjungan',
        'dprd_render_reservasi_meta_box',
        'reservasi',
        'normal',
        'high'
    );
});

function dprd_render_reservasi_meta_box($post) {
    $email            = get_post_meta($post->ID, 'res_email', true);
    $nama_instansi    = get_post_meta($post->ID, 'res_nama_instansi', true);
    $alamat_instansi  = get_post_meta($post->ID, 'res_alamat_instansi', true);
    $tanggal          = get_post_meta($post->ID, 'res_tanggal', true);
    $tema             = get_post_meta($post->ID, 'res_tema', true);
    $jabatan_pimpinan = get_post_meta($post->ID, 'res_jabatan_pimpinan', true);
    $nama_pimpinan    = get_post_meta($post->ID, 'res_nama_pimpinan', true);
    $jumlah_peserta   = get_post_meta($post->ID, 'res_jumlah_peserta', true);
    $wa               = get_post_meta($post->ID, 'res_wa', true);
    $file_url         = get_post_meta($post->ID, 'res_file_url', true);
    $status           = get_post_meta($post->ID, 'res_status', true) ?: 'Pending';
    ?>
    <table class="form-table">
        <tr>
            <th><label>Status Permohonan</label></th>
            <td>
                <select name="res_status" class="regular-text">
                    <option value="Pending" <?php selected($status, 'Pending'); ?>>⏳ Menunggu Persetujuan (Pending)</option>
                    <option value="Disetujui" <?php selected($status, 'Disetujui'); ?>>✅ Disetujui (Approved)</option>
                    <option value="Ditolak" <?php selected($status, 'Ditolak'); ?>>❌ Ditolak (Rejected)</option>
                </select>
            </td>
        </tr>
        <tr>
            <th><label>Nama Instansi</label></th>
            <td><input type="text" readonly value="<?php echo esc_attr($nama_instansi); ?>" class="large-text"></td>
        </tr>
        <tr>
            <th><label>Email Instansi / Narahubung</label></th>
            <td><input type="email" readonly value="<?php echo esc_attr($email); ?>" class="regular-text"></td>
        </tr>
        <tr>
            <th><label>Alamat Instansi</label></th>
            <td><textarea readonly rows="3" class="large-text"><?php echo esc_textarea($alamat_instansi); ?></textarea></td>
        </tr>
        <tr>
            <th><label>Rencana Tanggal Kunjungan</label></th>
            <td><input type="text" readonly value="<?php echo esc_attr($tanggal); ?>" class="regular-text"></td>
        </tr>
        <tr>
            <th><label>Tema / Materi Kunjungan</label></th>
            <td><textarea readonly rows="3" class="large-text"><?php echo esc_textarea($tema); ?></textarea></td>
        </tr>
        <tr>
            <th><label>Pimpinan Rombongan</label></th>
            <td>
                <strong><?php echo esc_html($nama_pimpinan); ?></strong> (<?php echo esc_html($jabatan_pimpinan); ?>)
            </td>
        </tr>
        <tr>
            <th><label>Jumlah Peserta</label></th>
            <td><input type="text" readonly value="<?php echo esc_attr($jumlah_peserta); ?> Orang" class="small-text"></td>
        </tr>
        <tr>
            <th><label>Narahubung (WhatsApp)</label></th>
            <td>
                <a href="https://wa.me/<?php echo preg_replace('/[^0-9]/', '', $wa); ?>" target="_blank" class="button button-secondary">
                    💬 Chat WA: <?php echo esc_html($wa); ?>
                </a>
            </td>
        </tr>
        <tr>
            <th><label>Surat Permohonan (PDF)</label></th>
            <td>
                <?php if ($file_url) : ?>
                    <a href="<?php echo esc_url($file_url); ?>" target="_blank" class="button button-primary">
                        📄 Lihat / Download Berkas Surat PDF
                    </a>
                <?php else : ?>
                    <em>Tidak ada lampiran berkas</em>
                <?php endif; ?>
            </td>
        </tr>
    </table>
    <?php
}

// Simpan perubahan Status oleh Admin
add_action('save_post', function($post_id) {
    if (isset($_POST['res_status'])) {
        update_post_meta($post_id, 'res_status', sanitize_text_field($_POST['res_status']));
    }
});


// 2. Handler Form Submission via AJAX / Action Post
add_action('wp_ajax_dprd_submit_reservasi', 'dprd_handle_reservasi_submit');
add_action('wp_ajax_nopriv_dprd_submit_reservasi', 'dprd_handle_reservasi_submit');

function dprd_handle_reservasi_submit() {
    if (!check_ajax_referer('dprd_reservasi_nonce', 'dprd_reservasi_security', false) && !check_ajax_referer('dprd_reservasi_nonce', 'security', false)) {
        wp_send_json_error(['message' => 'Sesi keamanan telah berakhir. Silakan refresh halaman dan coba lagi.'], 403);
    }

    $email            = sanitize_email($_POST['res_email'] ?? '');
    $nama_instansi    = sanitize_text_field($_POST['res_nama_instansi'] ?? '');
    $alamat_instansi  = sanitize_textarea_field($_POST['res_alamat_instansi'] ?? '');
    $tanggal          = sanitize_text_field($_POST['res_tanggal'] ?? '');
    $tema             = sanitize_textarea_field($_POST['res_tema'] ?? '');
    $jabatan_pimpinan = sanitize_text_field($_POST['res_jabatan_pimpinan'] ?? '');
    $nama_pimpinan    = sanitize_text_field($_POST['res_nama_pimpinan'] ?? '');
    $jumlah_peserta   = intval($_POST['res_jumlah_peserta'] ?? 0);
    $raw_wa           = sanitize_text_field($_POST['res_wa'] ?? '');

    // 1. Validasi Kolom Wajib
    if (empty($email) || empty($nama_instansi) || empty($alamat_instansi) || empty($tanggal) || empty($tema) || empty($jabatan_pimpinan) || empty($nama_pimpinan) || empty($raw_wa)) {
        wp_send_json_error(['message' => 'Mohon lengkapi seluruh kolom formulir bertanda bintang (*).']);
    }

    // 2. Validasi Email (Must be valid email format with @)
    if (!is_email($email) || strpos($email, '@') === false) {
        wp_send_json_error(['message' => 'Format alamat email tidak valid (contoh: nama@instansi.go.id).']);
    }

    // 3. Validasi & Format Nomor WhatsApp (+62)
    $digits_wa = preg_replace('/[^0-9]/', '', $raw_wa);
    if (empty($digits_wa)) {
        wp_send_json_error(['message' => 'Nomor WhatsApp tidak boleh kosong dan harus berupa angka.']);
    }

    if (strpos($digits_wa, '62') === 0) {
        $body_wa = substr($digits_wa, 2);
    } elseif (strpos($digits_wa, '0') === 0) {
        $body_wa = substr($digits_wa, 1);
    } else {
        $body_wa = $digits_wa;
    }

    $len_body = strlen($body_wa);
    if ($len_body < 8 || $len_body > 13) {
        wp_send_json_error(['message' => 'Nomor WhatsApp tidak valid. Masukkan 9 - 13 digit angka (contoh: 81234567890).']);
    }

    $formatted_wa = '+62' . $body_wa;

    // 4. Validasi Tanggal (Tidak boleh masa lalu & Hari Kerja)
    $timestamp_tanggal = strtotime($tanggal);
    if (!$timestamp_tanggal) {
        wp_send_json_error(['message' => 'Format rencana tanggal kunjungan tidak valid.']);
    }

    $today_timestamp = strtotime(date('Y-m-d'));
    if ($timestamp_tanggal < $today_timestamp) {
        wp_send_json_error(['message' => 'Rencana tanggal kunjungan tidak boleh di masa lalu.']);
    }

    $day_of_week = date('N', $timestamp_tanggal); // 1 (Senin) - 7 (Minggu)
    if ($day_of_week >= 6) {
        wp_send_json_error(['message' => 'Kunjungan kerja hanya dilayani pada hari kerja (Senin - Jumat).']);
    }

    // 5. Validasi Jumlah Peserta
    if ($jumlah_peserta < 1) {
        wp_send_json_error(['message' => 'Jumlah peserta rombongan minimal 1 orang.']);
    }

    // 6. Validasi & Upload Berkas PDF Surat Permohonan
    if (empty($_FILES['res_file_surat']['name']) || $_FILES['res_file_surat']['error'] !== UPLOAD_ERR_OK) {
        wp_send_json_error(['message' => 'Surat Permohonan wajib diunggah dalam format PDF.']);
    }

    $file_info = $_FILES['res_file_surat'];
    $ext = strtolower(pathinfo($file_info['name'], PATHINFO_EXTENSION));
    if ($ext !== 'pdf') {
        wp_send_json_error(['message' => 'Berkas surat permohonan harus berformat PDF (.pdf).']);
    }

    if ($file_info['size'] > 5 * 1024 * 1024) {
        wp_send_json_error(['message' => 'Ukuran berkas PDF surat permohonan maksimal 5MB.']);
    }

    require_once(ABSPATH . 'wp-admin/includes/file.php');
    require_once(ABSPATH . 'wp-admin/includes/image.php');
    require_once(ABSPATH . 'wp-admin/includes/media.php');

    $uploaded = wp_handle_upload($_FILES['res_file_surat'], ['test_form' => false]);
    if (!isset($uploaded['url'])) {
        wp_send_json_error(['message' => 'Gagal mengunggah surat permohonan. ' . ($uploaded['error'] ?? '')]);
    }
    $file_url = $uploaded['url'];

    // 7. Simpan ke Database WordPress (CPT reservasi)
    $post_title = $nama_instansi . ' - ' . date('d M Y', $timestamp_tanggal);
    $post_id = wp_insert_post([
        'post_title'   => $post_title,
        'post_status'  => 'publish',
        'post_type'    => 'reservasi',
        'post_content' => $tema,
    ]);

    if (!$post_id || is_wp_error($post_id)) {
        wp_send_json_error(['message' => 'Gagal menyimpan reservasi ke database. Silakan coba lagi.']);
    }

    // Simpan Post Meta
    update_post_meta($post_id, 'res_email', $email);
    update_post_meta($post_id, 'res_nama_instansi', $nama_instansi);
    update_post_meta($post_id, 'res_alamat_instansi', $alamat_instansi);
    update_post_meta($post_id, 'res_tanggal', $tanggal);
    update_post_meta($post_id, 'res_tema', $tema);
    update_post_meta($post_id, 'res_jabatan_pimpinan', $jabatan_pimpinan);
    update_post_meta($post_id, 'res_nama_pimpinan', $nama_pimpinan);
    update_post_meta($post_id, 'res_jumlah_peserta', $jumlah_peserta);
    update_post_meta($post_id, 'res_wa', $formatted_wa);
    update_post_meta($post_id, 'res_file_url', $file_url);
    update_post_meta($post_id, 'res_status', 'Pending');

    // 8. Kirim Real-time ke Google Sheets Webhook
    $webhook_url = get_option('dprd_google_sheets_webhook_url', 'https://script.google.com/macros/s/AKfycbxxF2-PFfYxfm6FDB5yOSHMYuNp9DSxNsTF5tcr-680wZmkLAUyLxaWjrKbp_SyO2-Z/exec');
    if (!empty($webhook_url)) {
        $sheet_data = [
            'timestamp'        => date('Y-m-d H:i:s'),
            'nama_instansi'    => $nama_instansi,
            'email'            => $email,
            'alamat_instansi'  => $alamat_instansi,
            'tanggal_kunjungan'=> $tanggal,
            'tema'             => $tema,
            'nama_pimpinan'    => $nama_pimpinan,
            'jabatan_pimpinan' => $jabatan_pimpinan,
            'jumlah_peserta'   => $jumlah_peserta,
            'wa'               => $formatted_wa,
            'file_url'         => $file_url,
            'status'           => 'Pending'
        ];

        wp_remote_post($webhook_url, [
            'method'      => 'POST',
            'timeout'     => 10,
            'redirection' => 5,
            'httpversion' => '1.1',
            'blocking'    => false,
            'headers'     => ['Content-Type' => 'application/json; charset=utf-8'],
            'body'        => wp_json_encode($sheet_data),
        ]);
    }

    wp_send_json_success(['message' => 'Permohonan reservasi kunjungan Anda berhasil dikirim dan tersimpan!']);
}

// Option Page Input Webhook URL di Admin Settings
add_action('admin_init', function() {
    register_setting('general', 'dprd_google_sheets_webhook_url');
    add_settings_field(
        'dprd_google_sheets_webhook_url',
        'Google Sheets Webhook URL (Reservasi)',
        function() {
            $value = get_option('dprd_google_sheets_webhook_url', '');
            echo '<input type="url" name="dprd_google_sheets_webhook_url" value="' . esc_url($value) . '" class="large-text" placeholder="https://script.google.com/macros/s/AKfycbx.../exec">';
            echo '<p class="description">Masukkan Webhook URL dari Google Apps Script untuk sinkronisasi otomatis ke Google Sheets.</p>';
        },
        'general'
    );
});
