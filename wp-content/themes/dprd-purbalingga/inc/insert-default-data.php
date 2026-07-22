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
    if (get_option('dprd_default_pages_cleanup_done_v2')) {
        return;
    }

    // Bersihkan halaman statis lama yang bentrok dengan CPT Alat Kelengkapan
    $old_pages = [
        'pimpinan-dprd',
        'badan-musyawarah',
        'badan-anggaran',
        'bapemperda',
        'badan-kehormatan',
        'badan-pembentukan-peraturan-daerah'
    ];
    foreach ($old_pages as $oslug) {
        $p = get_page_by_path($oslug);
        if ($p) {
            wp_delete_post($p->ID, true);
        }
    }

    update_option('dprd_default_pages_cleanup_done_v2', true);
});

// --- IMPORT ALAT KELENGKAPAN GROUP (KOMISI, FRAKSI, DAN BADAN) ---
add_action('init', function() {
    if (!get_option('dprd_default_ak_group_imported_v9')) {
        // Helper untuk membuat/mendapatkan term jenis
        if (!function_exists('dprd_import_setup_term')) {
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
        }

        // Helper untuk membuat/mendapatkan post anggota
        if (!function_exists('dprd_import_setup_anggota')) {
            function dprd_import_setup_anggota($name, $content) {
                $posts = get_posts([
                    'post_type'   => 'anggota',
                    'title'       => $name,
                    'post_status' => 'any',
                    'numberposts' => 1
                ]);

                if (empty($posts)) {
                    $post_id = wp_insert_post([
                        'post_title'   => $name,
                        'post_content' => $content,
                        'post_status'  => 'publish',
                        'post_type'    => 'anggota',
                    ]);
                } else {
                    $post_id = $posts[0]->ID;
                    wp_update_post([
                        'ID'           => $post_id,
                        'post_content' => $content
                    ]);
                }
                return $post_id;
            }
        }

        // Helper untuk membuat/mendapatkan post alat kelengkapan
        if (!function_exists('dprd_import_setup_ak')) {
            function dprd_import_setup_ak($title, $slug, $term_id, $meta = [], $date = '') {
                $posts = get_posts([
                    'post_type'   => 'alat-kelengkapan',
                    'name'        => $slug,
                    'post_status' => 'any',
                    'numberposts' => 1
                ]);

                $post_arr = [
                    'post_title'  => $title,
                    'post_name'   => $slug,
                    'post_status' => 'publish',
                    'post_type'   => 'alat-kelengkapan',
                ];
                if ($date) {
                    $post_arr['post_date'] = $date;
                    $post_arr['post_date_gmt'] = get_gmt_from_date($date);
                }

                if (empty($posts)) {
                    $post_id = wp_insert_post($post_arr);
                } else {
                    $post_id = $posts[0]->ID;
                    if ($date) {
                        $post_arr['ID'] = $post_id;
                        wp_update_post($post_arr);
                    }
                }

                if ($post_id && !is_wp_error($post_id)) {
                    if ($term_id) {
                        wp_set_post_terms($post_id, [$term_id], 'jenis');
                    }
                    foreach ($meta as $key => $val) {
                        update_post_meta($post_id, $key, $val);
                    }
                }
                return $post_id;
            }
        }

        // 1. Setup Anggota DPRD
        $id_bambang = dprd_import_setup_anggota(
            'H.R Bambang Irawan, S.H., S.Sos., M.M.',
            'Setelah mendapatkan kepercayaan untuk memimpin DPRD Kabupaten Purbalingga, H.R. Bambang Irawan, S.H., S.Sos., M.M. mengemban amanah sebagai Ketua DPRD dengan komitmen memperjuangkan aspirasi masyarakat, memperkuat fungsi legislasi, penganggaran, dan pengawasan, serta mendorong terwujudnya pemerintahan daerah yang transparan, akuntabel, dan berpihak pada kepentingan rakyat demi meningkatkan kesejahteraan masyarakat Purbalingga.'
        );
        $id_aris = dprd_import_setup_anggota(
            'Aris Widiarso, S.H.',
            'Sebagai Wakil Ketua DPRD Kabupaten Purbalingga, Aris Widiarso, S.H. berkomitmen mendukung terciptanya pemerintahan daerah yang efektif, transparan, dan responsif melalui penguatan fungsi legislasi, penganggaran, serta pengawasan. Dengan mengedepankan sinergi bersama seluruh pemangku kepentingan, ia terus memperjuangkan aspirasi masyarakat demi mendorong pembangunan daerah yang berkelanjutan dan meningkatkan kesejahteraan warga Purbalingga.'
        );
        $id_aman = dprd_import_setup_anggota(
            'H. Aman Waliyudin, S.E., M.S.I.',
            'Sebagai Wakil Ketua DPRD Kabupaten Purbalingga, H. Aman Waliyudin, S.E., M.S.I. berkomitmen mengawal pelaksanaan tugas dan fungsi DPRD melalui kerja sama yang harmonis, penguatan pengawasan terhadap jalannya pemerintahan daerah, serta penyusunan kebijakan yang berpihak pada kepentingan masyarakat. Dengan mengedepankan integritas dan semangat pelayanan, ia terus mendorong pembangunan yang inklusif, berkelanjutan, dan berorientasi pada peningkatan kesejahteraan masyarakat Purbalingga.'
        );
        $id_tenny = dprd_import_setup_anggota(
            'HJ. Tenny Juliawaty, S.E., M.Si.',
            'Sebagai Wakil Ketua DPRD Kabupaten Purbalingga, Hj. Tenny Juliawaty, S.E., M.Si. berkomitmen memperkuat peran DPRD dalam menyerap dan memperjuangkan aspirasi masyarakat melalui pelaksanaan fungsi legislasi, penganggaran, dan pengawasan yang efektif. Dengan mengedepankan kolaborasi, profesionalisme, dan kepedulian terhadap kepentingan publik, ia terus mendorong terwujudnya pembangunan daerah yang berkelanjutan, inklusif, dan berorientasi pada peningkatan kesejahteraan masyarakat Purbalingga.'
        );

        $term_komisi_id = dprd_import_setup_term('Komisi', 'komisi');
        $term_fraksi_id = dprd_import_setup_term('Fraksi', 'fraksi');
        $term_badan_id  = dprd_import_setup_term('Badan', 'badan');

        // 2. Setup Komisi Group JSON
        $komisi_json = wp_json_encode([
            'tipe' => 'grup',
            'nama' => 'Komisi',
            'children' => [
                [
                    'tipe' => 'badan',
                    'nama' => 'Komisi I',
                    'mitra_kerja' => [
                        'Inspektorat Kabupaten',
                        'Badan Kepegawaian, Pendidikan dan Pelatihan Daerah',
                        'Dinas Pemberdayaan Masyarakat dan Desa',
                        'Dinas Kependudukan dan Catatan Sipil',
                        'Dinas Kearsipan dan Perpustakaan',
                        'Dinas Penanaman Modal dan Pelayanan Terpadu Satu Pintu',
                        'Satuan Polisi Pamong Praja',
                        'Kantor Kesatuan Bangsa dan Politik',
                        'Bagian Hukum',
                        'Bagian Pemerintahan',
                        'Bagian Umum',
                        'Bagian Organisasi dan Tata Laksana',
                        'Bagian Humas dan Protokol',
                        'Kecamatan',
                        'Kelurahan'
                    ],
                    'hierarki' => [
                        [
                            'members' => [
                                ['jabatan' => 'Ketua Komisi', 'anggota_id' => $id_bambang]
                            ]
                        ],
                        [
                            'members' => [
                                ['jabatan' => 'Wakil Ketua Komisi', 'anggota_id' => $id_aris],
                                ['jabatan' => 'Sekretaris Komisi', 'anggota_id' => $id_aman]
                            ]
                        ],
                        [
                            'members' => [
                                ['jabatan' => 'Anggota', 'anggota_id' => $id_tenny]
                            ]
                        ]
                    ]
                ],
                [
                    'tipe' => 'badan',
                    'nama' => 'Komisi II',
                    'mitra_kerja' => [
                        'Sekretariat DPRD',
                        'Badan Keuangan Daerah',
                        'Dinas Ketahanan Pangan dan Perikanan',
                        'Dinas Pertanian',
                        'Dinas Perindustrian dan Perdagangan',
                        'Dinas Koperasi, Usaha Kecil dan Menengah',
                        'Bagian Perekonomian',
                        'PDAM',
                        'Perumda BPR Artha Perwira',
                        'Perumda Puspahastama',
                        'Perumda BPR BKK Purbalingga',
                        'Perumda BPR BKK Kejobong dan Rembang',
                        'Perumda Owabong',
                        'Bank Syariah Buana Mitra Perwira'
                    ],
                    'hierarki' => [
                        [
                            'members' => [
                                ['jabatan' => 'Ketua Komisi', 'anggota_id' => $id_bambang]
                            ]
                        ],
                        [
                            'members' => [
                                ['jabatan' => 'Wakil Ketua Komisi', 'anggota_id' => $id_aris],
                                ['jabatan' => 'Sekretaris Komisi', 'anggota_id' => $id_aman]
                            ]
                        ],
                        [
                            'members' => [
                                ['jabatan' => 'Anggota', 'anggota_id' => $id_tenny]
                            ]
                        ]
                    ]
                ],
                [
                    'tipe' => 'badan',
                    'nama' => 'Komisi III',
                    'mitra_kerja' => [
                        'Dinas Pendidikan dan Kebudayaan',
                        'Dinas Pemuda, Olahraga dan Pariwisata',
                        'Dinas Kesehatan',
                        'Dinas Tenaga Kerja',
                        'Dinas Sosial, Pengendalian Penduduk, Keluarga Berencana, Pemberdayaan Perempuan, dan Perlindungan Anak',
                        'BPBD',
                        'Bagian Kesra',
                        'RSUD Goeteng Taroenadibrata',
                        'RSUD Panti Nugroho'
                    ],
                    'hierarki' => [
                        [
                            'members' => [
                                ['jabatan' => 'Ketua Komisi', 'anggota_id' => $id_bambang]
                            ]
                        ],
                        [
                            'members' => [
                                ['jabatan' => 'Wakil Ketua Komisi', 'anggota_id' => $id_aris],
                                ['jabatan' => 'Sekretaris Komisi', 'anggota_id' => $id_aman]
                            ]
                        ],
                        [
                            'members' => [
                                ['jabatan' => 'Anggota', 'anggota_id' => $id_tenny]
                            ]
                        ]
                    ]
                ],
                [
                    'tipe' => 'badan',
                    'nama' => 'Komisi IV',
                    'mitra_kerja' => [
                        'Badan Perencanaan Pembangunan, Penelitian, dan Pengembangan Daerah',
                        'Dinas Pekerjaan Umum dan Penataan Ruang',
                        'Dinas Perumahan dan Permukiman',
                        'Dinas Lingkungan Hidup',
                        'Dinas Perhubungan',
                        'Dinas Komunikasi dan Informatika',
                        'Bagian Administrasi Pembangunan',
                        'Bagian Layanan Pengadaan'
                    ],
                    'hierarki' => [
                        [
                            'members' => [
                                ['jabatan' => 'Ketua Komisi', 'anggota_id' => $id_bambang]
                            ]
                        ],
                        [
                            'members' => [
                                ['jabatan' => 'Wakil Ketua Komisi', 'anggota_id' => $id_aris],
                                ['jabatan' => 'Sekretaris Komisi', 'anggota_id' => $id_aman]
                            ]
                        ],
                        [
                            'members' => [
                                ['jabatan' => 'Anggota', 'anggota_id' => $id_tenny]
                            ]
                        ]
                    ]
                ]
            ]
        ]);

        // 3. Setup Fraksi Group JSON
        $fraksi_json = wp_json_encode([
            'tipe' => 'grup',
            'nama' => 'Fraksi',
            'children' => [
                [
                    'tipe' => 'badan',
                    'nama' => 'Fraksi PDI Perjuangan',
                    'hierarki' => [
                        [
                            'members' => [
                                ['jabatan' => 'Ketua Fraksi', 'anggota_id' => $id_bambang]
                            ]
                        ],
                        [
                            'members' => [
                                ['jabatan' => 'Sekretaris Fraksi', 'anggota_id' => $id_aris],
                                ['jabatan' => 'Bendahara Fraksi', 'anggota_id' => $id_aman]
                            ]
                        ],
                        [
                            'members' => [
                                ['jabatan' => 'Anggota', 'anggota_id' => $id_tenny]
                            ]
                        ]
                    ]
                ],
                [
                    'tipe' => 'badan',
                    'nama' => 'Fraksi Partai Golkar',
                    'hierarki' => [
                        [
                            'members' => [
                                ['jabatan' => 'Ketua Fraksi', 'anggota_id' => $id_bambang]
                            ]
                        ],
                        [
                            'members' => [
                                ['jabatan' => 'Sekretaris Fraksi', 'anggota_id' => $id_aris]
                            ]
                        ]
                    ]
                ],
                [
                    'tipe' => 'badan',
                    'nama' => 'Fraksi Partai Gerindra',
                    'hierarki' => [
                        [
                            'members' => [
                                ['jabatan' => 'Ketua Fraksi', 'anggota_id' => $id_bambang]
                            ]
                        ],
                        [
                            'members' => [
                                ['jabatan' => 'Sekretaris Fraksi', 'anggota_id' => $id_aris]
                            ]
                        ]
                    ]
                ],
                [
                    'tipe' => 'badan',
                    'nama' => 'Fraksi PKB',
                    'hierarki' => [
                        [
                            'members' => [
                                ['jabatan' => 'Ketua Fraksi', 'anggota_id' => $id_bambang]
                            ]
                        ],
                        [
                            'members' => [
                                ['jabatan' => 'Sekretaris Fraksi', 'anggota_id' => $id_aris]
                            ]
                        ]
                    ]
                ],
                [
                    'tipe' => 'badan',
                    'nama' => 'Fraksi PKS',
                    'hierarki' => [
                        [
                            'members' => [
                                ['jabatan' => 'Ketua Fraksi', 'anggota_id' => $id_bambang]
                            ]
                        ],
                        [
                            'members' => [
                                ['jabatan' => 'Sekretaris Fraksi', 'anggota_id' => $id_aris]
                            ]
                        ]
                    ]
                ],
                [
                    'tipe' => 'badan',
                    'nama' => 'Fraksi PAN',
                    'hierarki' => [
                        [
                            'members' => [
                                ['jabatan' => 'Ketua Fraksi', 'anggota_id' => $id_bambang]
                            ]
                        ],
                        [
                            'members' => [
                                ['jabatan' => 'Sekretaris Fraksi', 'anggota_id' => $id_aris]
                            ]
                        ]
                    ]
                ]
            ]
        ]);

        // 1. Pimpinan DPRD
        dprd_import_setup_ak('Pimpinan DPRD', 'pimpinan-dprd', 0, [
            'dprd_pimpinan_dasar_penetapan' => 'Berdasarkan Undang-Undang Republik Indonesia, Pimpinan DPRD terdiri dari satu orang Ketua dan tiga orang Wakil Ketua untuk DPRD Kabupaten dengan jumlah anggota 45-50 orang. Komposisi pimpinan didasarkan pada urutan perolehan kursi terbanyak partai politik di tingkat Kabupaten Purbalingga hasil Pemilihan Umum Legislatif 2024.',
            'dprd_pimpinan_note' => 'Penetapan ini diatur dalam Keputusan Gubernur Jawa Tengah dan Peraturan Tata Tertib DPRD Kabupaten Purbalingga untuk memastikan penyelenggaraan fungsi legislasi, anggaran, dan pengawasan berjalan secara kolektif kolegial.',
            'dprd_pimpinan_tugas_json' => wp_json_encode([
                [
                    'kategori' => 'Kepemimpinan & Koordinasi',
                    'icon'     => 'gavel',
                    'poin'     => [
                        'Memimpin sidang DPRD and menyimpulkan hasil sidang untuk diambil keputusan.',
                        'Menyusun rencana kerja pimpinan dan mengadakan pembagian kerja antara ketua dan wakil ketua.',
                        'Melakukan koordinasi dalam upaya menyinergikan pelaksanaan agenda dan materi kegiatan dari alat kelengkapan DPRD.'
                    ]
                ],
                [
                    'kategori' => 'Perwakilan & Komunikasi',
                    'icon'     => 'users',
                    'poin'     => [
                        'Menjadi juru bicara DPRD.',
                        'Mewakili DPRD dalam berhubungan dengan lembaga/instansi lainnya.',
                        'Mengadakan konsultasi dengan bupati dan pimpinan lembaga/instansi lainnya sesuai dengan keputusan DPRD.',
                        'Mewakili DPRD di pengadilan.'
                    ]
                ],
                [
                    'kategori' => 'Administrasi & Akuntabilitas',
                    'icon'     => 'file-text',
                    'poin'     => [
                        'Melaksanakan dan memasyarakatkan keputusan DPRD.',
                        'Melaksanakan keputusan DPRD berkenaan dengan penetapan sanksi atau rehabilitasi anggota sesuai dengan ketentuan peraturan.',
                        'Menyusun rencana anggaran DPRD bersama sekretariat DPRD yang pengesahannya dilakukan dalam rapat paripurna.',
                        'Menyampaikan laporan kinerja pimpinan dalam rapat paripurna yang khusus diadakan untuk itu.'
                    ]
                ]
            ])
        ], '2026-07-21 12:00:06');

        // 2. Badan Musyawarah
        $bamus_json = wp_json_encode([
            'tipe' => 'badan',
            'nama' => 'Badan Musyawarah',
            'hierarki' => [
                [
                    'members' => [
                        ['jabatan' => 'Ketua', 'anggota_id' => $id_bambang]
                    ]
                ],
                [
                    'members' => [
                        ['jabatan' => 'Wakil Ketua', 'anggota_id' => $id_aris],
                        ['jabatan' => 'Wakil Ketua', 'anggota_id' => $id_aman],
                        ['jabatan' => 'Wakil Ketua', 'anggota_id' => $id_tenny]
                    ]
                ],
                [
                    'members' => [
                        ['jabatan' => 'Sekretaris (Bukan Anggota)', 'anggota_id' => $id_bambang]
                    ]
                ],
                [
                    'members' => [
                        ['jabatan' => 'Anggota', 'anggota_id' => $id_bambang],
                        ['jabatan' => 'Anggota', 'anggota_id' => $id_aris],
                        ['jabatan' => 'Anggota', 'anggota_id' => $id_aman],
                        ['jabatan' => 'Anggota', 'anggota_id' => $id_tenny]
                    ]
                ]
            ]
        ]);
        dprd_import_setup_ak('Badan Musyawarah', 'badan-musyawarah', $term_badan_id, [
            'dprd_ak_struktur_json' => $bamus_json,
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
        ], '2026-07-21 12:00:05');

        // 3. Badan Anggaran
        $banggar_json = wp_json_encode([
            'tipe' => 'badan',
            'nama' => 'Badan Anggaran',
            'hierarki' => [
                [
                    'members' => [
                        ['jabatan' => 'Ketua Merangkap Anggota', 'anggota_id' => $id_bambang]
                    ]
                ],
                [
                    'members' => [
                        ['jabatan' => 'Wakil Ketua Merangkap Anggota', 'anggota_id' => $id_aris],
                        ['jabatan' => 'Wakil Ketua Merangkap Anggota', 'anggota_id' => $id_aman],
                        ['jabatan' => 'Wakil Ketua Merangkap Anggota', 'anggota_id' => $id_tenny]
                    ]
                ],
                [
                    'members' => [
                        ['jabatan' => 'Sekretaris Bukan Anggota', 'anggota_id' => $id_bambang]
                    ]
                ],
                [
                    'members' => [
                        ['jabatan' => 'Anggota', 'anggota_id' => $id_bambang],
                        ['jabatan' => 'Anggota', 'anggota_id' => $id_aris],
                        ['jabatan' => 'Anggota', 'anggota_id' => $id_aman],
                        ['jabatan' => 'Anggota', 'anggota_id' => $id_tenny]
                    ]
                ]
            ]
        ]);
        dprd_import_setup_ak('Badan Anggaran', 'badan-anggaran', $term_badan_id, [
            'dprd_ak_struktur_json' => $banggar_json,
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
        ], '2026-07-21 12:00:04');

        // 4. Badan Pembentukan Peraturan Daerah
        $bapemperda_json = wp_json_encode([
            'tipe' => 'badan',
            'nama' => 'Badan Pembentukan Peraturan Daerah',
            'hierarki' => [
                [
                    'members' => [
                        ['jabatan' => 'Ketua', 'anggota_id' => $id_bambang]
                    ]
                ],
                [
                    'members' => [
                        ['jabatan' => 'Wakil Ketua', 'anggota_id' => $id_aris]
                    ]
                ],
                [
                    'members' => [
                        ['jabatan' => 'Anggota', 'anggota_id' => $id_aman],
                        ['jabatan' => 'Anggota', 'anggota_id' => $id_tenny]
                    ]
                ]
            ]
        ]);
        dprd_import_setup_ak('Badan Pembentukan Peraturan Daerah', 'badan-pembentukan-peraturan-daerah', $term_badan_id, [
            'dprd_ak_struktur_json' => $bapemperda_json,
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
                        'Mengikuti perkembangan dan melakukan evaluasi terhadap pembahasan materi muatan raperda melalui koordinasi dengan komisi dan/atau panitia khusus.'
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
        ], '2026-07-21 12:00:03');

        // 5. Badan Kehormatan
        $bk_json = wp_json_encode([
            'tipe' => 'badan',
            'nama' => 'Badan Kehormatan',
            'hierarki' => [
                [
                    'members' => [
                        ['jabatan' => 'Ketua', 'anggota_id' => $id_bambang]
                    ]
                ],
                [
                    'members' => [
                        ['jabatan' => 'Wakil Ketua', 'anggota_id' => $id_aris]
                    ]
                ],
                [
                    'members' => [
                        ['jabatan' => 'Anggota', 'anggota_id' => $id_aman],
                        ['jabatan' => 'Anggota', 'anggota_id' => $id_tenny],
                        ['jabatan' => 'Anggota', 'anggota_id' => $id_bambang]
                    ]
                ]
            ]
        ]);
        dprd_import_setup_ak('Badan Kehormatan', 'badan-kehormatan', $term_badan_id, [
            'dprd_ak_struktur_json' => $bk_json,
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
        ], '2026-07-21 12:00:02');

        // 6. Komisi
        dprd_import_setup_ak('Komisi', 'komisi', $term_komisi_id, [
            'dprd_ak_struktur_json' => $komisi_json
        ], '2026-07-21 12:00:01');

        // 7. Fraksi
        dprd_import_setup_ak('Fraksi', 'fraksi', $term_fraksi_id, [
            'dprd_ak_struktur_json' => $fraksi_json
        ], '2026-07-21 12:00:00');

        // Hapus post-post pecahan yang terbuat di import versi sebelumnya (jika ada)
        $old_slugs = [
            'komisi-1', 'komisi-2', 'komisi-3', 'komisi-4',
            'fraksi-pdi-perjuangan', 'fraksi-partai-golkar', 'fraksi-partai-gerindra',
            'fraksi-pkb', 'fraksi-pks', 'fraksi-pan', 'bapemperda'
        ];
        foreach ($old_slugs as $oslug) {
            $oposts = get_posts([
                'post_type' => 'alat-kelengkapan',
                'name'      => $oslug,
                'post_status' => 'any',
                'numberposts' => -1
            ]);
            foreach ($oposts as $op) {
                wp_delete_post($op->ID, true);
            }
        }

        update_option('dprd_default_ak_group_imported_v9', true);
    }
});

// --- IMPORT DEFAULT POSTS PPID ---
add_action('init', function() {
    if (!get_option('dprd_default_ppid_imported_v1')) {
        $ppid_list = [
            [
                'slug'        => 'sk-ppid',
                'title'       => 'SK PPID',
                'description' => 'SK PPID DPRD Kabupaten Purbalingga',
                'documents'   => [
                    ['title' => 'SK 170 Perubahan Fraksi', 'url' => '#'],
                    ['title' => 'SK NO 170-04 TH 2022 PEMBENTUKAN KOMISI', 'url' => '#'],
                    ['title' => 'SK NO 170-03 TH 2022 PEMBENTUKAN BADAN MUSYAWARAH', 'url' => '#'],
                    ['title' => 'SK NO 170-06 TH 2022 PEMBENTUKAN BADAN ANGGARAN', 'url' => '#'],
                    ['title' => 'SK NO 170-07 TH 2022 PEMBENTUKAN BADAN KEHORMATAN', 'url' => '#']
                ],
                'date'        => '2026-07-21 10:00:00'
            ],
            [
                'slug'        => 'informasi-publik',
                'title'       => 'Informasi Publik',
                'description' => 'Rencana Kerja Periode 2024-2029',
                'documents'   => [
                    ['title' => 'Dokumen Informasi Publik 2024', 'url' => '#']
                ],
                'date'        => '2026-07-21 10:00:01'
            ],
            [
                'slug'        => 'permohonan-informasi',
                'title'       => 'Permohonan Informasi',
                'description' => 'Rencana Kerja Periode 2024-2029',
                'documents'   => [
                    ['title' => 'Formulir Permohonan Informasi', 'url' => '#']
                ],
                'date'        => '2026-07-21 10:00:02'
            ],
            [
                'slug'        => 'informasi-serta-merta',
                'title'       => 'Informasi Serta Merta',
                'description' => 'Rencana Kerja Periode 2024-2029',
                'documents'   => [
                    ['title' => 'Dokumen Informasi Serta Merta', 'url' => '#']
                ],
                'date'        => '2026-07-21 10:00:03'
            ],
            [
                'slug'        => 'informasi-setiap-saat',
                'title'       => 'Informasi Setiap Saat',
                'description' => 'Rencana Kerja Periode 2024-2029',
                'documents'   => [
                    ['title' => 'Dokumen Informasi Setiap Saat', 'url' => '#']
                ],
                'date'        => '2026-07-21 10:00:04'
            ],
            [
                'slug'        => 'informasi-berkala',
                'title'       => 'Informasi Berkala',
                'description' => 'Rencana Kerja Periode 2024-2029',
                'documents'   => [
                    ['title' => 'Dokumen Informasi Berkala', 'url' => '#']
                ],
                'date'        => '2026-07-21 10:00:05'
            ],
        ];

        foreach ($ppid_list as $item) {
            $posts = get_posts([
                'post_type'   => 'ppid',
                'name'        => $item['slug'],
                'post_status' => 'any',
                'numberposts' => 1
            ]);

            $post_data = [
                'post_title'  => $item['title'],
                'post_name'   => $item['slug'],
                'post_status' => 'publish',
                'post_type'   => 'ppid',
                'post_date'   => $item['date'],
                'post_date_gmt' => get_gmt_from_date($item['date']),
            ];

            if (empty($posts)) {
                $post_id = wp_insert_post($post_data);
            } else {
                $post_id = $posts[0]->ID;
                $post_data['ID'] = $post_id;
                wp_update_post($post_data);
            }

            if ($post_id && !is_wp_error($post_id)) {
                update_post_meta($post_id, 'description', $item['description']);
                update_post_meta($post_id, 'documents_json', wp_json_encode($item['documents']));
            }
        }

        update_option('dprd_default_ppid_imported_v1', true);
    }
});

