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
// HANDLER FORM RESERVASI KUNJUNGAN (Sudah Dikelola Lengkap oleh inc/backend-reservasi.php)
// =============================================================================

