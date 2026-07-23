<?php
/**
 * Modul Keamanan Tambahan & Handler Form Reservasi Kunjungan
 */

if (!defined('ABSPATH')) exit;

// 1. Matikan XML-RPC untuk mencegah brute-force dan DDoS
add_filter('xmlrpc_enabled', '__return_false');

// 2. Blokir REST API untuk pengguna yang tidak login (Mencegah User Enumeration)
add_filter('rest_authentication_errors', function( $result ) {
    if ( ! empty( $result ) ) { return $result; }
    if ( ! is_user_logged_in() && ! is_admin() ) {
        return new WP_Error( 'rest_not_logged_in', 'Akses API ditolak.', array( 'status' => 401 ) );
    }
    return $result;
});

// =============================================================================
// HANDLER FORM RESERVASI KUNJUNGAN (Fase 7)
// =============================================================================
add_action('admin_post_nopriv_submit_reservasi', 'dprd_handle_reservasi_submit');
add_action('admin_post_submit_reservasi', 'dprd_handle_reservasi_submit');

function dprd_handle_reservasi_submit() {
    // 1. Verifikasi Nonce CSRF
    if (!isset($_POST['dprd_reservasi_nonce']) || !wp_verify_nonce($_POST['dprd_reservasi_nonce'], 'dprd_submit_reservasi_action')) {
        wp_die('Akses ditolak: Validasi keamanan gagal.', 'Error', ['response' => 403]);
    }

    // 2. Ambil & Sanitasi Data Text/Textarea
    $email          = sanitize_email($_POST['res_email'] ?? '');
    $nama_instansi  = sanitize_text_field($_POST['res_nama_instansi'] ?? '');
    $alamat         = sanitize_textarea_field($_POST['res_alamat_instansi'] ?? '');
    $tanggal        = sanitize_text_field($_POST['res_tanggal'] ?? '');
    $tema           = sanitize_textarea_field($_POST['res_tema'] ?? '');
    $jabatan_pimp   = sanitize_text_field($_POST['res_jabatan_pimpinan'] ?? '');
    $nama_pimp      = sanitize_text_field($_POST['res_nama_pimpinan'] ?? '');
    $jumlah         = absint($_POST['res_jumlah_peserta'] ?? 1);
    $wa             = sanitize_text_field($_POST['res_wa'] ?? '');

    // Validasi field kosong
    if (!$email || !$nama_instansi || !$tanggal || !$tema || !$wa) {
        wp_die('Harap lengkapi semua field yang wajib diisi.', 'Error', ['response' => 400]);
    }

    // 3. Handle File Upload (HANYA PDF)
    if (empty($_FILES['res_file_surat']['name'])) {
        wp_die('Surat Permohonan (PDF) wajib diunggah.', 'Error', ['response' => 400]);
    }

    $file = $_FILES['res_file_surat'];

    // Validasi Ukuran (Maks 5MB)
    if ($file['size'] > 5 * 1024 * 1024) {
        wp_die('Ukuran file maksimal 5MB.', 'Error', ['response' => 400]);
    }

    // Validasi MIME Type secara ketat
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mime = finfo_file($finfo, $file['tmp_name']);
    finfo_close($finfo);

    if ($mime !== 'application/pdf') {
        wp_die('File harus berformat PDF.', 'Error', ['response' => 400]);
    }

    // Validasi ekstensi
    $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    if ($ext !== 'pdf') {
        wp_die('File harus memiliki ekstensi .pdf.', 'Error', ['response' => 400]);
    }

    // Gunakan fungsi bawaan WordPress untuk upload yang aman
    require_once ABSPATH . 'wp-admin/includes/file.php';
    
    // override setting untuk memaksa PDF saja
    $upload_overrides = [
        'test_form' => false,
        'mimes'     => ['pdf' => 'application/pdf']
    ];
    
    $movefile = wp_handle_upload($file, $upload_overrides);

    if ($movefile && !isset($movefile['error'])) {
        $file_url = $movefile['url'];
        
        // 4. Buat Post Baru (CPT 'reservasi' jika ada, atau masukkan sebagai draft/post)
        // Menurut PRD fase 7: simpan sebagai CPT 'reservasi'
        $post_title = 'Reservasi: ' . $nama_instansi . ' - ' . date_i18n('d M Y', strtotime($tanggal));
        
        $post_id = wp_insert_post([
            'post_title'   => $post_title,
            'post_type'    => 'reservasi', // pastikan di post-types.php sudah diregister CPT ini
            'post_status'  => 'publish',   // atau 'pending' untuk direview
            'meta_input'   => [
                'res_email'             => $email,
                'res_nama_instansi'     => $nama_instansi,
                'res_alamat_instansi'   => $alamat,
                'res_tanggal'           => $tanggal,
                'res_tema'              => $tema,
                'res_jabatan_pimpinan'  => $jabatan_pimp,
                'res_nama_pimpinan'     => $nama_pimp,
                'res_jumlah_peserta'    => $jumlah,
                'res_wa'                => $wa,
                'res_file_surat_url'    => $file_url,
            ]
        ]);

        if (is_wp_error($post_id)) {
            wp_die('Gagal menyimpan data reservasi.', 'Error', ['response' => 500]);
        }

        // 5. Redirect ke halaman sukses (atau halaman reservasi dengan status sukses)
        $redirect_url = add_query_arg('status', 'sukses', home_url('/reservasi'));
        wp_redirect($redirect_url);
        exit;
    } else {
        wp_die('Gagal mengunggah file: ' . esc_html($movefile['error']), 'Error', ['response' => 500]);
    }
}
