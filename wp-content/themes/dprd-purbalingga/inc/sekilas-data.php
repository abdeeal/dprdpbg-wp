<?php
/**
 * Data static sekilas tentang Purbalingga
 * Dijadikan fallback apabila data dinamis di database kosong.
 */
if (!defined('ABSPATH')) exit;

function dprd_get_sekilas_data_static() {
    return [
        'letakGeografis' => [
            'deskripsi' => "Kabupaten Purbalingga terletak pada letak astronomis dan letak geografis wilayah di daerah pegunungan beriklim tropis. Secara astronomis Purbalingga berada di antara 109° 11' - 109° 35' Bujur Timur dan 7° 10' - 7° 29' Lintang Selatan.",
            'batasWilayah' => [
                'utara' => "Kab. Pemalang",
                'timur' => "Kab. Banjarnegara",
                'selatan' => "Kab. Banjarnegara",
                'barat' => "Kab. Banyumas"
            ],
            'jarakKotaBesar' => [
                ['kota' => "Purwokerto", 'jarak' => "20"],
                ['kota' => "Semarang", 'jarak' => "191"],
                ['kota' => "Cilacap", 'jarak' => "60"],
                ['kota' => "Banjarnegara", 'jarak' => "45"],
                ['kota' => "Wonosobo", 'jarak' => "75"]
            ]
        ],
        'luasWilayah' => [
            'luasTotal' => "80.576,00",
            'persentaseJateng' => "2,39",
            'luasPerKecamatan' => [
                ['kecamatan' => "Kemangkon", 'luas' => "4.846,00"],
                ['kecamatan' => "Bukateja", 'luas' => "4.496,00"],
                ['kecamatan' => "Kejobong", 'luas' => "4.014,00"],
                ['kecamatan' => "Pengadegan", 'luas' => "4.125,00"],
                ['kecamatan' => "Kaligondang", 'luas' => "5.144,00"],
                ['kecamatan' => "Purbalingga", 'luas' => "1.566,00"],
                ['kecamatan' => "Kalimanah", 'luas' => "2.324,00"],
                ['kecamatan' => "Padamara", 'luas' => "1.790,00"],
                ['kecamatan' => "Kutasari", 'luas' => "3.781,00"],
                ['kecamatan' => "Bojongsari", 'luas' => "4.499,00"],
                ['kecamatan' => "Mrebet", 'luas' => "5.143,00"],
                ['kecamatan' => "Bobotsari", 'luas' => "3.554,00"],
                ['kecamatan' => "Karanganyar", 'luas' => "3.521,00"],
                ['kecamatan' => "Karangmoncol", 'luas' => "7.198,00"],
                ['kecamatan' => "Rembang", 'luas' => "9.879,00"],
                ['kecamatan' => "Karangjambu", 'luas' => "4.895,00"],
                ['kecamatan' => "Karangreja", 'luas' => "6.201,00"],
                ['kecamatan' => "Kertanegara", 'luas' => "3.601,00"]
            ]
        ],
        'topografiTanah' => [
            'wilayahUtara' => "Merupakan daerah dataran tinggi yang berbukit-bukit dengan kelerengan lebih dari 40 persen",
            'wilayahSelatan' => "Merupakan daerah yang relatif rendah dengan nilai faktor kemiringan berada antara 0 persen sampai dengan 25 persen",
            'distribusiKetinggian' => [
                ['ketinggian' => "15 - 25", 'persentase' => "0,56%"],
                ['ketinggian' => "25 - 100", 'persentase' => "27,02%"],
                ['ketinggian' => "100 - 500", 'persentase' => "44,13%"],
                ['ketinggian' => "500 - 1.000", 'persentase' => "23,05%"],
                ['ketinggian' => "> 1.000", 'persentase' => "5,24%"]
            ],
            'persebaranJenisTanah' => [
                ['jenis' => "Latosol coklat dan Regosol", 'persentase' => "19,22%"],
                ['jenis' => "Aluvial coklat tua", 'persentase' => "17,79%"],
                ['jenis' => "Latosol coklat induk vulkanik", 'persentase' => "10,92%"],
                ['jenis' => "Latosol merah kuning", 'persentase' => "5,78%"],
                ['jenis' => "Latosol coklat tua", 'persentase' => "8,02%"],
                ['jenis' => "Andosol coklat", 'persentase' => "7,28%"],
                ['jenis' => "Litosol", 'persentase' => "0,74%"],
                ['jenis' => "Padmolik merah kuning", 'persentase' => "12,92%"],
                ['jenis' => "Grumusol kelabu", 'persentase' => "7,33%"]
            ]
        ],
        'hidrologi' => [
            'sungaiMelewati' => [
                "Sungai Pekacangan",
                "Sungai Klawing",
                "Sungai Serayu"
            ],
            'sungaiMengalir' => [
                "Sungai Onggawa",
                "Sungai Gemuruh",
                "Sungai Kajar",
                "Sungai Lembereng"
            ]
        ],
        'pemerintahan' => [
            'jumlahKecamatan' => "18",
            'jumlahDesa' => "239",
            'jumlahRT' => "5.092"
        ],
        'kepegawaian' => [
            'totalAsn' => "7.639",
            'lakiLaki' => "3.615",
            'perempuan' => "4.024",
            'distribusiGolongan' => [
                ['golongan' => "Golongan IV (Pembina)", 'jumlah' => "2.257"],
                ['golongan' => "Golongan III (Penata)", 'jumlah' => "3.832"],
                ['golongan' => "Golongan II (Pengatur)", 'jumlah' => "1.058"],
                ['golongan' => "Golongan I (Juru)", 'jumlah' => "114"]
            ]
        ],
        'kependudukan' => [
            'totalPenduduk' => "925.193",
            'kepadatan' => "1.190",
            'lajuPertumbuhan' => "1,05",
            'rasioJenisKelamin' => "97,63",
            'jumlahRumahTangga' => "231.648"
        ],
        'sosialFasilitas' => [
            'pendidikan' => [
                ['jenjang' => "TK / RA / BA", 'jumlahSekolah' => "507", 'totalGuru' => "898", 'totalSiswa' => "17.134"],
                ['jenjang' => "SD / MI", 'jumlahSekolah' => "648", 'totalGuru' => "5.901", 'totalSiswa' => "99.961"],
                ['jenjang' => "SMP / MTs", 'jumlahSekolah' => "116", 'totalGuru' => "1.044", 'totalSiswa' => "44.087"],
                ['jenjang' => "SMA / SMK / MA", 'jumlahSekolah' => "60", 'totalGuru' => "563", 'totalSiswa' => "18.248"]
            ],
            'kesehatan' => [
                'fasilitas' => [
                    ['label' => "RUMAH SAKIT UMUM", 'nilai' => "4"],
                    ['label' => "RUMAH SAKIT BERSALIN", 'nilai' => "2"],
                    ['label' => "PUSKESMAS", 'nilai' => "22"],
                    ['label' => "PUSKESMAS PEMBANTU", 'nilai' => "48"],
                    ['label' => "APOTEK", 'nilai' => "72"]
                ],
                'tenagaMedis' => [
                    ['tenaga' => "Dokter", 'jumlah' => "319"],
                    ['tenaga' => "Bidan", 'jumlah' => "562"],
                    ['tenaga' => "Paramedis Lainnya", 'jumlah' => "1.355"]
                ]
            ]
        ]
    ];
}
