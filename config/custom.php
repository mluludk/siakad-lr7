<?php
	$config =null;
	@include storage_path() . '/app/config.php';
	if($config != null)
	{
		return $config;
	}
	else
	return array (
	'app' => 
	array (
    'title' => 'Nama Perguruan Tinggi',
    'name' => 'Sistem Informasi Akademik',
    'abbr' => 'SIAKAD',
	'version' => '2.5 Alpha'
	),
	'route_group_middleware' => [
	1 => ['auth'], 
	2 => ['auth', 'maintenis'], 
	3 => ['auth', 'roles', 'maintenis'],
	4 => ['auth', 'roles', 'profil', 'kuesioner', 'maintenis'],
	],
	'tahun_mulai_pendataan' => '2015',
	'profil' => 
	array (
    'jenis-pt' => 'Jenis Perguruan Tinggi',
    'singk-jenis-pt' => 'SINGK',
    'kode' => 'K9009',
    'nama' => 'Nama Perguruan Tinggi',
    'singkatan' => 'Singk. PT',
    'email' => 'email@website.domain',
    'website' => 'http://website.domain',
    'telepon' => '0000-000000',
    'fax' => '0000-000000',
    'alamat' => 
    array (
	'jalan' => '',
	'rt' => '',
	'rw' => '',
	'dusun' => '',
	'kelurahan' => '',
	'kodepos' => '',
	'kecamatan' => '',
	'kabupaten' => '',
	'propinsi' => '',
	'lintang' => '000',
	'bujur' => '000',
    ),
    'informasi' => 
    array (
	'bank' => '',
	'unit' => '',
	'no-rekening' => '',
	'mbs' => '',
	'luas-tanah-milik' => '000',
	'luas-tanah-bukan-milik' => '000',
    ),
    'akta-pendirian' => 
    array (
	'sk-pend' => '',
	'tgl-sk-pend' => '',
	'status-kep' => '',
	'status-pt' => '',
	'sk-ijin-ops' => '',
	'tgl-sk-ijin-ops' => '',
    ),
	),
	'hari' => 
	array (
    1 => 'Senin',
    2 => 'Selasa',
    3 => 'Rabu',
    4 => 'Kamis',
    5 => ' Jumat',
    6 => 'Sabtu',
    7 => 'Minggu',
	),
	'bulan' => 
	array (
    '01' => 'Januari',
    '02' => 'Februari',
    '03' => 'Maret',
    '04' => 'April',
    '05' => 'Mei',
    '06' => 'Juni',
    '07' => 'Juli',
    '08' => 'Agustus',
    '09' => 'September',
    10 => 'Oktober',
    11 => 'November',
    12 => 'Desember',
	),
	'dataFormat' => 
	array (
    'xlsx' => 'Microsoft Excel',
    'pdf' => 'PDF',
    'html' => 'Hyper Text Markup Language (HTML)',
    'csv' => 'Comma Separated Values (CSV)',
    'sql' => 'MySQL',
	),
	'pilihan' => 
	array (
    'tipe' => 
    array (
	0 => 'Pedoman Penulisan Skripsi',
	1 => 'Proposal Skripsi',
	2 => 'Makalah',
	3 => 'RPP',
	4 => 'Silabus',
	5 => 'Jurnal',
	6 => 'Kalender Akademik',
	99 => 'Lain',
    ),
    'absensi' => 
    array (
	'-' => '-',
	'H' => 'Hadir',
	'S' => 'Sakit',
	'I' => 'Izin',
	'A' => 'Alpa',
	),
	'kelompokMatkul' => 
	array (
	'A' => 'MPK-Pengembangan Kepribadian',
	'B' => 'MKK-Keilmuan dan Ketrampilan',
	'C' => 'MKB-Keahlian Berkarya',
	'D' => 'MPB-Perilaku Berkarya',
	'E' => 'MBB-Berkehidupan Bermasyarakat',
	'F' => 'MKU/MKDU',
	'G' => 'MKDK',
	'H' => 'MKK',
	),
	'jenisMatkul' => 
	array (
	0 => 'Wajib',
	1 => 'Pilihan',
	2 => 'Wajib Peminatan',
	3 => 'Pilihan Peminatan',
	4 => 'Tugas akhir/Skripsi/Tesis/Disertasi',
	),
	'jenisPertemuan' => 
	array (
	1 => 'Kuliah',
	2 => 'Quiz',
	3 => 'UAS',
	4 => 'UTS',
	5 => 'Praktikum',
	),
	'statusJurnal' => 
	array (
	1 => 'Terjadwal',
	2 => 'Ganti',
	3 => 'Selesai',
	4 => 'Batal',
	),
	'jenisKegiatan' => 
	array (
	1 => 'Akademik',
	2 => 'General Stadium',
	3 => 'Workshop',
	4 => 'Seminar',
	5 => 'Pelatihan',
	6 => 'Lain',
	),
	'jenisKelamin' => 
	array (
	'L' => 'LAKI-LAKI',
	'P' => 'PEREMPUAN',
	),
	'statusWrgNgr' => 
	array (
	0 => 'WNI',
	1 => 'WNI KETURUNAN',
	2 => 'WNA',
	),
	'mukim' => 
	array (
	1 => 'Bersama Orang Tua',
	2 => 'Wali',
	3 => 'Kost',
	4 => 'Asrama',
	5 => 'Panti Asuhan',
	99 => 'Lainnya',
	),
	'jenisPembayaran' => 
	array (
	1 => 'Mukim',
	2 => 'Non-mukim',
	3 => 'Subsidi',
	4 => 'Beasiswa',
	),
	'transportasi' => 
	array (
	1 => 'Jalan kaki',
	2 => 'Kendaraan pribadi',
	3 => 'Angkutan umum/bus/pete-pete',
	4 => 'Mobil/bus antar jemput',
	5 => 'Kereta api',
	6 => 'Ojek',
	7 => 'Andong/bendi/sado/dokar/delman/becak',
	8 => 'Perahu penyeberangan/rakit/getek',
	11 => 'Kuda',
	12 => 'Sepeda',
	13 => 'Sepeda motor',
	14 => 'Mobil pribadi',
	99 => 'Lainnya',
	),
	'agama' => 
	array (
	1 => 'ISLAM',
	2 => 'PROTESTAN',
	3 => 'KATOLIK',
	4 => 'HINDU',
	5 => 'BUDHA',
	6 => 'KONGHUCHU',
	99 => 'LAINNYA',
	),
	'statusSipil' => 
	array (
	0 => 'BELUM MENIKAH',
	1 => 'MENIKAH',
	2 => 'JANDA',
	3 => 'DUDA',
	),
	'sekolahAsal' => [1 => 'MA', 'SMA', 'SMK', 'Paket C', 'Pondok Pesantren'],
	'pendidikanOrtu' => 
	array (
	1 => 'Tidak Sekolah',
	2 => 'Paud',
	3 => 'TK / Sederajat',
	4 => 'Putus SD',
	5 => 'SD / Sederajat',
	6 => 'SMP / Sederajat',
	7 => 'SMA / Sederajat',
	8 => 'Paket A',
	9 => 'Paket B',
	10 => 'Paket C',
	20 => 'D1',
	21 => 'D2',
	22 => 'D3',
	23 => 'D4',
	25 => 'Profesi',
	30 => 'S1',
	32 => 'Sp-1',
	35 => 'S2',
	37 => 'Sp-2',
	40 => 'S3',
	90 => 'Non formal',
	91 => 'Informal',
	99 => 'Lainnya',
	),
	'pekerjaanOrtu' => 
	array (
	1 => 'Tidak bekerja',
	2 => 'Nelayan',
	3 => 'Petani',
	4 => 'Peternak',
	5 => 'PNS/TNI/Polri',
	6 => 'Karyawan Swasta',
	7 => 'Pedagang Kecil',
	8 => 'Pedagang Besar',
	9 => 'Wiraswasta',
	10 => 'Wirausaha',
	11 => 'Buruh',
	12 => 'Pensiunan',
	98 => 'Sudah Meninggal',
	99 => 'Lainnya',
	),
	'penghasilanOrtu' => 
	array (
	1 => '< Rp. 500.000',
	2 => '500.000 - 1.000.000',
	3 => '1.000.000 - 2 .000.000',
	4 => '2.000.0000 - 5.000.000',
	5 => '5.000.000 - 20.000.000',
	6 => '> 20 jt',
	),
	'jalurMasuk' => 
	array (
	1 => 'SBMPTN',
	2 => 'SNMPTN',
	3 => 'PMDK Penelusuran minat dan kemampuan (akademik)',
	4 => 'Penelusuran minat dan kemampuan (prestasi)',
	5 => 'Seleksi Mandiri PTN',
	6 => 'Seleksi Mandiri PTS',
	7 => 'Ujian Masuk Bersama PTN (UMB-PT)',
	8 => 'Ujian Masuk Bersama PTS (UMB-PTS)',
	9 => 'Program Internasional',
	11 => 'Program Kerjasama Perusahaan/Institusi/Pemerintah',
	),
	'jenisPendaftaran' => 
	array (
	1 => 'Peserta didik baru',
	2 => 'Pindahan',
	3 => 'Naik kelas',
	4 => 'Akselerasi',
	5 => 'Mengulang',
	6 => 'Lanjutan semester',
	8 => 'Pindahan Alih Bentuk',
	11 => 'Alih Jenjang',
	12 => 'Lintas Jalur',
	),
	'tSbgMhs' => 
	array (
	0 => 'BIASA',
	1 => 'BEASISWA',
	2 => 'TUGAS BELAJAR',
	3 => 'IKATAN DINAS',
	4 => 'LAIN-LAIN',
	),
	'statusMhs' => 
	array (
	1 => 'AKTIF',
	2 => 'MUTASI',
	3 => 'DIKELUARKAN',
	4 => 'LULUS',
	5 => 'MENGUNDURKAN DIRI',
	6 => 'PUTUS KULIAH',
	7 => 'WAFAT',
	8 => 'HILANG',
	9 => 'NON-AKTIF',
	10 => 'LAINNYA',
	),
	'statusMhs2' => 
	array (
	1 => 'A',
	6 => 'D',
	3 => 'K',
	4 => 'L',
	9 => 'C',
	),
	'pendidikanDosen' => 
	array (
	0 => 'DIPLOMA',
	1 => 'S1',
	2 => 'S2',
	3 => 'S3',
	),
	'statusDosen' => 
	array (
	1 => 'DOSEN TETAP',
	2 => 'DOSEN TIDAK TETAP',
	),
	),
	'nilai' => 
	array (
	0 => 'A+',
	1 => 'A',
	2 => 'A-',
	3 => 'B+',
	4 => 'B',
	5 => 'B-',
	6 => 'C+',
	7 => 'C',
	8 => 'C-',
	9 => 'D',
	),
	'konversi_nilai' => 
	array (
	'base_100' => 
	array (
	'A+' => 100,
	'A' => 90,
	'A-' => 85,
	'B+' => 80,
	'B' => 75,
	'B-' => 70,
	'C+' => 65,
	'C' => 60,
	'C-' => 55,
	'D' => 50,
	),
	'base_4' => 
	array (
	'A+' => 4,
	'A' => 3.75,
	'A-' => 3.5,
	'B+' => 3.25,
	'B' => 3,
	'B-' => 2.75,
	'C+' => 2.5,
	'C' => 2.25,
	'C-' => 2,
	'D' => 1.75,
	),
	'base_lulus' => 
	array (
	'A+' => 'Lulus',
	'A' => 'Lulus',
	'A-' => 'Lulus',
	'B+' => 'Lulus',
	'B' => 'Lulus',
	'B-' => 'Lulus',
	'C+' => 'Lulus',
	'C' => 'Lulus',
	'C-' => 'Tidak Lulus',
	'D' => 'Tidak Lulus',
	),
	),
	'filetypes' => 
	array (
	'application/msword' => 'doc',
	'application/vnd.openxmlformats-officedocument.wordprocessingml.document' => 'docx',
	'application/pdf' => 'pdf',
	),
	'kuesioner' => 
	array (
	'kompetensi' => 
	array (
	1 => 'Kompetensi Kepribadian',
	2 => 'Kompetensi Pedagogik',
	3 => 'Kompetensi Profesional',
	4 => 'Kompetensi Sosial',
	),
	'skor' => 
	array (
	'sangat tidak baik/sangat rendah/tidak pernah',
	'tidak baik/rendah/jarang',
	'biasa/cukup/kadang-kadang',
	'baik/tinggi/sering',
	'sangat baik/sangat tingi/selalu',
	),
	),
);