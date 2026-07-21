<?php
/**
 * DPRD Purbalingga Theme - Default Data Importer
 * Mengimpor halaman statis dan data metadatanya secara otomatis (sekali jalan).
 *
 * @package DPRD_Purbalingga
 */

if (!defined('ABSPATH')) exit;

add_action('init', function() {
    // Jalankan sekali saja agar tidak menimpa pengeditan manual berikutnya
    if (get_option('dprd_default_data_imported')) {
        return;
    }

    // Helper untuk setup halaman dan post meta-nya
    function dprd_import_setup_page($title, $slug, $template, $meta) {
        $pages = get_posts([
            'post_type'   => 'page',
            'name'        => $slug,
            'post_status' => 'any',
            'numberposts' => 1
        ]);

        if (empty($pages)) {
            $post_id = wp_insert_post([
                'post_title'  => $title,
                'post_name'   => $slug,
                'post_status' => 'publish',
                'post_type'   => 'page',
            ]);
        } else {
            $post_id = $pages[0]->ID;
        }

        if ($post_id && !is_wp_error($post_id)) {
            update_post_meta($post_id, '_wp_page_template', $template);
            foreach ($meta as $key => $val) {
                update_post_meta($post_id, $key, $val);
            }
        }
    }

    // 1. Pimpinan DPRD
    dprd_import_setup_page(
        'Pimpinan DPRD',
        'pimpinan-dprd',
        'page-pimpinan-dprd.php',
        [
            'dprd_pimpinan_dasar_penetapan' => 'Berdasarkan Undang-Undang Republik Indonesia, Pimpinan DPRD terdiri dari satu orang Ketua dan tiga orang Wakil Ketua untuk DPRD Kabupaten dengan jumlah anggota 45-50 orang. Komposisi pimpinan didasarkan pada urutan perolehan kursi terbanyak partai politik di tingkat Kabupaten Purbalingga hasil Pemilihan Umum Legislatif 2024.',
            'dprd_pimpinan_note' => 'Penetapan ini diatur dalam Keputusan Gubernur Jawa Tengah dan Peraturan Tata Tertib DPRD Kabupaten Purbalingga untuk memastikan penyelenggaraan fungsi legislasi, anggaran, dan pengawasan berjalan secara kolektif kolegial.',
            'dprd_pimpinan_tugas_json' => wp_json_encode([
                [
                    'kategori' => 'Kepemimpinan & Koordinasi',
                    'icon'     => 'gavel',
                    'poin'     => [
                        'Memimpin sidang DPRD dan menyimpulkan hasil sidang untuk diambil keputusan.',
                        'Menyusun rencana kerja pimpinan dan mengadakan pembagian kerja antara ketua dan wakil ketua.',
                        'Melakukan koordinasi dalam upaya menyinergikan pelaksanaan agenda dan materi kegiatan dari alat kelengkapan DPRD.'
                    ]
                ],
                [
                    'kategori' => 'Perwakilan & Komunikasi',
                    'icon'     => 'users',
                    'poin'     => [
                        'Menjadi juru bicara DPRD.',
                        'Mewakili DPRD dalam berhubungan dengan lembaga/instansi lainnya.'
                    ]
                ]
            ])
        ]
    );

    // 2. Badan Musyawarah
    dprd_import_setup_page(
        'Badan Musyawarah',
        'badan-musyawarah',
        'page-badan-musyawarah.php',
        [
            'dprd_badan_dasar_pembentukan' => 'Badan Musyawarah merupakan alat kelengkapan DPRD yang bersifat tetap, dibentuk oleh DPRD pada awal masa jabatan keanggotaan. Badan Musyawarah terdiri atas unsur-unsur fraksi berdasarkan perimbangan jumlah anggota, dengan jumlah anggota paling banyak setengah dari total anggota DPRD.',
            'dprd_badan_tugas_json' => wp_json_encode([
                [
                    'kategori' => 'Perencanaan Agenda & Jadwal',
                    'icon'     => 'calendar',
                    'poin'     => [
                        'Menetapkan agenda DPRD untuk 1 tahun sidang, 1 masa persidangan, atau sebagian masa sidang, termasuk perkiraan waktu penyelesaian masalah dan jangka waktu penyelesaian rancangan peraturan daerah — dengan tidak mengurangi kewenangan rapat paripurna untuk mengubahnya.',
                        'Menetapkan jadwal acara rapat DPRD.'
                    ]
                ],
                [
                    'kategori' => 'Koordinasi & Pertimbangan',
                    'icon'     => 'users',
                    'poin'     => [
                        'Memberikan pendapat kepada pimpinan dalam menentukan garis kebijakan yang menyangkut pelaksanaan tugas dan wewenang DPRD.',
                        'Meminta dan/atau memberikan kesempatan kepada alat kelengkapan DPRD lain untuk memberikan keterangan/penjelasan mengenai pelaksanaan tugas masing-masing.',
                        'Memberikan saran/pendapat untuk memperlancar kegiatan.'
                    ]
                ],
                [
                    'kategori' => 'Rekomendasi & Tugas Lain',
                    'icon'     => 'file-text',
                    'poin'     => [
                        'Merekomendasikan pembentukan panitia khusus.',
                        'Melaksanakan tugas lain yang diserahkan oleh rapat paripurna kepada Badan Musyawarah.'
                    ]
                ]
            ])
        ]
    );

    // 3. Badan Anggaran
    dprd_import_setup_page(
        'Badan Anggaran',
        'badan-anggaran',
        'page-badan-anggaran.php',
        [
            'dprd_badan_dasar_pembentukan' => 'Badan Anggaran merupakan alat kelengkapan DPRD yang bersifat tetap, dibentuk oleh DPRD pada awal masa jabatan keanggotaan.',
            'dprd_badan_tugas_json' => wp_json_encode([
                [
                    'kategori' => 'Perencanaan & Pembahasan KUA-PPAS',
                    'icon'     => 'calendar',
                    'poin'     => [
                        'Memberikan saran dan pendapat berupa pokok-pokok pikiran DPRD kepada Bupati dalam mempersiapkan RAPBD, paling lambat 5 (lima) bulan sebelum APBD ditetapkan.',
                        'Melakukan konsultasi (dapat diwakili anggota) kepada komisi terkait untuk memperoleh masukan dalam pembahasan rancangan kebijakan umum APBD serta prioritas dan plafon anggaran sementara.',
                        'Melakukan pembahasan bersama tim anggaran pemerintah daerah (TAPD) terhadap rancangan kebijakan umum APBD serta rancangan prioritas dan plafon anggaran sementara yang disampaikan Bupati.'
                    ]
                ],
                [
                    'kategori' => 'Penyusunan & Evaluasi APBD',
                    'icon'     => 'file-text',
                    'poin'     => [
                        'Memberikan saran dan pendapat kepada Bupati dalam mempersiapkan raperda perubahan APBD dan raperda pertanggungjawaban pelaksanaan APBD.',
                        'Melakukan penyelarasan hasil pembahasan komisi-komisi dalam pembahasan RAPBD dan perubahan APBD, disesuaikan dengan kemampuan keuangan daerah.',
                        'Melakukan penyempurnaan raperda APBD dan raperda pertanggungjawaban pelaksanaan APBD berdasarkan hasil evaluasi Gubernur bersama TAPD.'
                    ]
                ],
                [
                    'kategori' => 'Anggaran Internal DPRD',
                    'icon'     => 'wallet',
                    'poin'     => [
                        'Memberikan saran kepada pimpinan dalam penyusunan anggaran belanja DPRD.'
                    ]
                ]
            ])
        ]
    );

    // 4. Bapemperda
    dprd_import_setup_page(
        'Badan Pembentukan Peraturan Daerah',
        'badan-pembentukan-peraturan-daerah',
        'page-bapemperda.php',
        [
            'dprd_badan_dasar_pembentukan' => 'Badan Pembentukan Peraturan Daerah (Bapemperda) merupakan alat kelengkapan DPRD yang bersifat tetap, dibentuk dalam rapat paripurna.',
            'dprd_badan_tugas_json' => wp_json_encode([
                [
                    'kategori' => 'Perencanaan Program Legislasi',
                    'icon'     => 'calendar',
                    'poin'     => [
                        'Menyusun rancangan program legislasi daerah yang memuat daftar urutan dan prioritas raperda beserta alasannya untuk setiap tahun anggaran di lingkungan DPRD.',
                        'Mengkoordinasikan penyusunan program legislasi daerah antara DPRD dan pemerintah daerah yang telah ditetapkan.'
                    ]
                ],
                [
                    'kategori' => 'Harmonisasi & Evaluasi Raperda',
                    'icon'     => 'scale',
                    'poin'     => [
                        'Melakukan pengharmonisasian, pembulatan, dan pemantapan konsepsi raperda yang diajukan anggota, komisi, dan/atau gabungan komisi, di luar prioritas raperda tahun berjalan atau di luar raperda yang terdaftar dalam program legislasi daerah.',
                        'Memberikan pertimbangan terhadap raperda yang diajukan anggota, komisi, dan/atau gabungan komisi, di luar prioritas raperda tahun berjalan atau di luar raperda yang terdaftar dalam program legislasi daerah.',
                        'Mengikuti perkembangan dan melakukan evaluasi terhadap pembahasan materi muatan raperda melalui koordinasi dengan komisi and/atau panitia khusus.'
                    ]
                ],
                [
                    'kategori' => 'Pelaporan & Rekomendasi',
                    'icon'     => 'file-text',
                    'poin'     => [
                        'Memberikan masukan kepada pimpinan DPRD atas raperda yang ditugaskan oleh Badan Musyawarah.',
                        'Membuat laporan kinerja pada masa akhir keanggotaan DPRD — baik yang sudah maupun belum terselesaikan — sebagai bahan bagi komisi pada masa keanggotaan berikutnya.'
                    ]
                ]
            ])
        ]
    );

    // 5. Badan Kehormatan
    dprd_import_setup_page(
        'Badan Kehormatan',
        'badan-kehormatan',
        'page-badan-kehormatan.php',
        [
            'dprd_bk_dasar_pembentukan' => 'Badan Kehormatan dibentuk oleh DPRD dan merupakan alat kelengkapan DPRD yang bersifat tetap.',
            'dprd_bk_jumlah_anggota' => '5 Orang',
            'dprd_bk_jumlah_anggota_desc' => "JUMLAH ANGGOTA\nDipilih dari dan oleh anggota DPRD",
            'dprd_bk_masa_tugas' => '2,5 Tahun',
            'dprd_bk_masa_tugas_desc' => 'MASA TUGAS MAKSIMAL',
            'dprd_bk_sanksi_json' => wp_json_encode([
                [
                    'sanksi'     => 'Teguran lisan',
                    'keterangan' => ''
                ],
                [
                    'sanksi'     => 'Teguran tertulis',
                    'keterangan' => ''
                ],
                [
                    'sanksi'     => 'Pemberhentian sebagai pimpinan alat kelengkapan DPRD',
                    'keterangan' => ''
                ],
                [
                    'sanksi'     => 'Pemberhentian sebagai anggota DPRD',
                    'keterangan' => 'Sesuai ketentuan peraturan perundang-undangan'
                ]
            ])
        ]
    );

    // Set flag sukses import
    update_option('dprd_default_data_imported', true);

    // --- IMPORT ALAT KELENGKAPAN (KOMISI & FRAKSI) ---
    if (!get_option('dprd_default_ak_data_imported')) {
        // Helper untuk membuat/mendapatkan term jenis
        function dprd_import_setup_term($name, $slug) {
            $term = get_term_by('slug', $slug, 'jenis');
            if (!$term) {
                $inserted = wp_insert_term($name, 'jenis', ['slug' => $slug]);
                if (!is_wp_error($inserted)) {
                    return $inserted['term_id'];
                }
            } else {
                return $term->term_id;
            }
            return 0;
        }

        // Helper untuk membuat/mendapatkan post alat kelengkapan
        function dprd_import_setup_ak($title, $slug, $term_id, $meta = []) {
            $posts = get_posts([
                'post_type'   => 'alat-kelengkapan',
                'name'        => $slug,
                'post_status' => 'any',
                'numberposts' => 1
            ]);

            if (empty($posts)) {
                $post_id = wp_insert_post([
                    'post_title'  => $title,
                    'post_name'   => $slug,
                    'post_status' => 'publish',
                    'post_type'   => 'alat-kelengkapan',
                ]);
            } else {
                $post_id = $posts[0]->ID;
            }

            if ($post_id && !is_wp_error($post_id)) {
                if ($term_id) {
                    wp_set_post_terms($post_id, [$term_id], 'jenis');
                }
                foreach ($meta as $key => $val) {
                    update_post_meta($post_id, $key, $val);
                }
            }
        }

        $term_komisi_id = dprd_import_setup_term('Komisi', 'komisi');
        $term_fraksi_id = dprd_import_setup_term('Fraksi', 'fraksi');

        // Import Komisi I - IV
        dprd_import_setup_ak('Komisi I', 'komisi-1', $term_komisi_id, [
            'dprd_komisi_mitra_kerja_json' => wp_json_encode([
                ['mitra' => 'Inspektorat Kabupaten'],
                ['mitra' => 'Badan Kepegawaian, Pendidikan dan Pelatihan Daerah'],
                ['mitra' => 'Dinas Pemberdayaan Masyarakat dan Desa'],
                ['mitra' => 'Dinas Kependudukan dan Catatan Sipil'],
                ['mitra' => 'Dinas Kearsipan dan Perpustakaan'],
                ['mitra' => 'Dinas Penanaman Modal dan Pelayanan Terpadu Satu Pintu'],
                ['mitra' => 'Satuan Polisi Pamong Praja'],
                ['mitra' => 'Kantor Kesatuan Bangsa dan Politik'],
                ['mitra' => 'Bagian Hukum'],
                ['mitra' => 'Bagian Pemerintahan'],
                ['mitra' => 'Bagian Umum'],
                ['mitra' => 'Bagian Organisasi dan Tata Laksana'],
                ['mitra' => 'Bagian Humas dan Protokol'],
                ['mitra' => 'Kecamatan'],
                ['mitra' => 'Kelurahan']
            ])
        ]);

        dprd_import_setup_ak('Komisi II', 'komisi-2', $term_komisi_id, [
            'dprd_komisi_mitra_kerja_json' => wp_json_encode([
                ['mitra' => 'Sekretariat DPRD'],
                ['mitra' => 'Badan Keuangan Daerah'],
                ['mitra' => 'Dinas Ketahanan Pangan dan Perikanan'],
                ['mitra' => 'Dinas Pertanian'],
                ['mitra' => 'Dinas Perindustrian dan Perdagangan'],
                ['mitra' => 'Dinas Koperasi, Usaha Kecil dan Menengah'],
                ['mitra' => 'Bagian Perekonomian'],
                ['mitra' => 'PDAM'],
                ['mitra' => 'Perumda BPR Artha Perwira'],
                ['mitra' => 'Perumda Puspahastama'],
                ['mitra' => 'Perumda BPR BKK Purbalingga'],
                ['mitra' => 'Perumda BPR BKK Kejobong dan Rembang'],
                ['mitra' => 'Perumda Owabong'],
                ['mitra' => 'Bank Syariah Buana Mitra Perwira']
            ])
        ]);

        dprd_import_setup_ak('Komisi III', 'komisi-3', $term_komisi_id, [
            'dprd_komisi_mitra_kerja_json' => wp_json_encode([
                ['mitra' => 'Dinas Pendidikan dan Kebudayaan'],
                ['mitra' => 'Dinas Pemuda, Olahraga dan Pariwisata'],
                ['mitra' => 'Dinas Kesehatan'],
                ['mitra' => 'Dinas Tenaga Kerja'],
                ['mitra' => 'Dinas Sosial, Pengendalian Penduduk, Keluarga Berencana, Pemberdayaan Perempuan, dan Perlindungan Anak'],
                ['mitra' => 'BPBD'],
                ['mitra' => 'Bagian Kesra'],
                ['mitra' => 'RSUD Goeteng Taroenadibrata'],
                ['mitra' => 'RSUD Panti Nugroho']
            ])
        ]);

        dprd_import_setup_ak('Komisi IV', 'komisi-4', $term_komisi_id, [
            'dprd_komisi_mitra_kerja_json' => wp_json_encode([
                ['mitra' => 'Badan Perencanaan Pembangunan, Penelitian, dan Pengembangan Daerah'],
                ['mitra' => 'Dinas Pekerjaan Umum dan Penataan Ruang'],
                ['mitra' => 'Dinas Perumahan dan Permukiman'],
                ['mitra' => 'Dinas Lingkungan Hidup'],
                ['mitra' => 'Dinas Perhubungan'],
                ['mitra' => 'Dinas Komunikasi dan Informatika'],
                ['mitra' => 'Bagian Administrasi Pembangunan'],
                ['mitra' => 'Bagian Layanan Pengadaan']
            ])
        ]);

        // Import Fraksi-Fraksi
        dprd_import_setup_ak('Fraksi PDI Perjuangan', 'fraksi-pdi-perjuangan', $term_fraksi_id);
        dprd_import_setup_ak('Fraksi Partai Golkar', 'fraksi-partai-golkar', $term_fraksi_id);
        dprd_import_setup_ak('Fraksi Partai Gerindra', 'fraksi-partai-gerindra', $term_fraksi_id);
        dprd_import_setup_ak('Fraksi PKB', 'fraksi-pkb', $term_fraksi_id);
        dprd_import_setup_ak('Fraksi PKS', 'fraksi-pks', $term_fraksi_id);
        dprd_import_setup_ak('Fraksi PAN', 'fraksi-pan', $term_fraksi_id);

        update_option('dprd_default_ak_data_imported', true);
    }
});
