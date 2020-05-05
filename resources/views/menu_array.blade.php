<ul>
	<?php		
		$data = $user -> authable;
		$wisuda = $ujian = $skripsi = '';
		$daftar = ["PENDAFTARAN", []];
		
		if($data -> semesterMhs >= 5) $daftar[1][0][] = ["Pengajuan Judul Skripsi","/skripsi/pengajuan"];
		if($data -> semesterMhs >= 7)	
		{
			$ujian = ', ["Ujian Proposal","\/jadwal\/ujian\/proposal"], ["Ujian Skripsi","\/jadwal\/ujian\/komprehensif"]';
			$daftar[1][0][] = ["PKM","/pkm/daftar"];
			$daftar[1][0][] = ["PPL","/ppl/daftar"];
			$daftar[1][0][] = ["Daftar Ujian Proposal","/skripsi/mahasiswa/ujian/pendaftaran/proposal"];
			$daftar[1][0][] = ["Daftar Ujian Skripsi","/skripsi/mahasiswa/ujian/pendaftaran/komprehensif"];
		}
		if($data -> semesterMhs >= 8) $daftar[1][0][] = ["Wisuda","/wisuda/daftar"];			
		
		if(count($daftar[1]) > 0)
		$daftar = json_encode($daftar) . ',';
		else
		$daftar = '';
		
		$home = [['HOME', '/']];
		$menu_available = json_decode('{
		"19a5f4fd03539417489cb8606bdf4139":[["PMB","\/pmb"]],
		"43a0c7536485375c5695098e8fe62c7a":[
		["PROFIL","\/profil"],
		["KHS","\/khs"],
		["KRS ONLINE",[[["Tawaran Mata Kuliah","\/tawaran"],["Kartu Rencana Studi","\/krs"]]]],
		["PERKULIAHAN", [[
		["Jadwal Perkuliahan",
		"\/jadwalmahasiswa"], 
		["e-Tugas","\/tugas"], 
		["Kalender Akademik", "\/kalenderakademik"] '. $ujian .', 
		["Skripsi Saya","\/skripsi"]
		]]],
		["KEUANGAN",[[
		["Tagihan Pembayaran","\/tagihan\/status\/mahasiswa"],
		["Status Pembayaran","\/pembayaran\/status\/mahasiswa"],
		["Riwayat Pembayaran","\/pembayaran\/riwayat\/mahasiswa"]
		]]],
		'. $daftar .'
		
		["KARYAKU",[[
		["Prestasi Mahasiswa","\/prestasi"],
		["Buku Mahasiswa","\/mahasiswa\/'. $data -> id .'\/buku"],
		["Jurnal Mahasiswa","\/mahasiswa\/'. $data -> id .'\/jurnal"],
		["Penelitian Mahasiswa","\/mahasiswa\/'. $data -> id .'\/penelitian"],
		["Tulisan Mahasiswa","\/mahasiswa\/'. $data -> id .'\/tulisan"]
		]]],
		
		
		["CETAK",[[
		["Kartu UTS","\/mahasiswa\/'. $data -> id .'\/kartu\/uts\/cetak"],
		["Kartu UAS","\/mahasiswa\/'. $data -> id .'\/kartu\/uas\/cetak"]
		]]]
		],
		"ff83c29df29d50ab873bbb912c7fd75f":[["KUESIONER",[[["Kuesioner"],["Pertanyaan Kuesioner","\/kuesioner"],["Buat Kuesioner","\/kuesioner\/create"]],[["Penilaian"],["Persiapan Dosen","\/kuesioner\/#"],["Penilaian Dosen","\/kuesioner\/results"],["Pemahaman Visi","\/kuesioner\/#"],["Administrasi","\/kuesioner\/#"],["Dosen DPA","\/kuesioner\/#"],["Dosen DPS","\/kuesioner\/#"]]]],["PROGRAM KERJA","\/program"]],
		"0bb44ea084d166dc194a6cd0e2ba24dd":[
		["PERKULIAHAN",[[
		["Jadwal Perkuliahan","\/jadwalsemua"],
		["Aktifitas Mengajar","\/kelaskuliah"],
		["Jadwal Mengajar","\/jadwaldosen"],
		["e-Tugas","\/mahasiswa\/tugas"]
		]]],
		["DOSEN WALI / PENDAMPING",[[
		["Perwalian","\/perwalian"],
		["Validasi KRS","\/validasi\/krs"],
		["Pendamping PKM","\/pkm"],
		["Pendamping PPL","\/ppl"]
		]]],
		["BIMBINGAN SKRIPSI",[[
		["Mahasiswa Kandidat Bimbingan","\/skripsi\/bimbingan\/kandidat"],
		["Mahasiswa Bimbingan","\/skripsi\/bimbingan"],
		["Mahasiswa Selesai Bimbingan","\/skripsi\/bimbingan\/selesai"]
		]]],
		["KALENDER AKADEMIK","\/kalenderakademik"]
		],
		
		"dd62ea30c72d69edfdbc9a86de049842":[
		["MAHASISWA",[
		[
		["Daftar Mahasiswa","\/mahasiswa"],
		["Pengajuan Cuti","\/mahasiswa\/cuti"],
		["Perwalian","\/perwalian"],
		["PKM","\/pkm"],
		["PPL","\/ppl"],
		["Wisuda","\/wisuda"]
		],
		[
		["Pengajuan Judul Skripsi","\/jadwal\/pengajuan\/skripsi"],
		["Pembimbing Skripsi","\/skripsi\/pembimbing"],
		["Pembagian Dosen Pembimbing","\/skripsi\/pembagian"],
		["Skripsi","\/skripsi"],
		["Pendaftaran Ujian Proposal Skripsi","\/ujian\/skripsi\/proposal"], 
		["Pendaftaran Ujian Kompre Skripsi","\/ujian\/skripsi\/komprehensif"]
		]]],
		["VALIDASI","\/validasi"],
		["PERKULIAHAN",[
		[["Kelas Perkuliahan","\/perkuliahan"],["Lihat Jadwal Kuliah","\/jadwal"],["Buat Jadwal Kuliah","\/jadwal\/create"]], 
		[["Kalender Akademik","\/kalender"], ["Tahun Akademik","\/tapel"], ["Kurikulum","\/kurikulum"]]]],
		["PROFIL DOSEN","\/profildosen"],["PROFIL PRODI","\/prodi\/detail"],["PROGRAM KERJA","\/program"]],
		
		"761753c17d988324fc933160e867df65":[["KALENDER AKADEMIK",[[["Kalender Akademik","\/kalender"],["Tambah Kegiatan Akademik","\/kalender\/create"]]]],["PROGRAM KERJA","\/program"]],
		"2631603223377229b7f2feb252e77b66":[["MAHASISWA",[[["Daftar Mahasiswa","\/mahasiswa"],["Tambah Mahasiswa Baru","\/mahasiswa\/create"],["Mahasiswa Cuti","\/mahasiswa\/cuti"],["Prestasi","\/mahasiswa\/prestasi"],["Skripsi","\/skripsi"],["Pendaftaran"],["Wisuda","\/wisuda"],["PKM","\/pkm"],["PPL","\/ppl"],["PMB","\/pmb"]],[["Impor Data"],["Impor Data Mahasiswa","\/mahasiswa\/impor"],["Impor Data Yudisium","mahasiswa\/yudisium\/impor"],["Edit Data"],["Data Mahasiswa","\/mahasiswa\/transfer"],["Perwalian","\/mahasiswa\/adminperwalian"],["Status","\/mahasiswa\/adminstatus"]]]],["DOSEN",[[["Daftar Dosen","\/dosen"],["Absensi Dosen","\/dosen\/absensi"]],[["Riwayat Dosen"],["Riwayat Pendidikan","\/dosen\/pendidikan"],["Riwayat Sertifikasi","\/dosen\/sertifikasi"],["Riwayat Penugasan","\/dosen\/penugasan"],["Riwayat Fungsional","\/dosen\/fungsional"],["Riwayat Kepangkatan","\/dosen\/kepangkatan"]]]],["AKADEMIK",[[["Kalender Akademik","\/kalender"],["Daftar Tahun Akademik","\/tapel"],["Tambah Tahun Akademik","\/tapel\/create"],["Tambah Kegiatan Akademik","\/kalender\/create"]],[["Perkuliahan"],["Kelas Perkuliahan","\/matkul\/tapel"],["Jadwal Kuliah","\/jadwal"],["Mata Kuliah","\/matkul"],["Kurikulum","\/kurikulum"]]]],["EXPORT",[[["KOPERTAIS IV"],["Transkrip Nilai","\/export\/transkrip_merge"]]]],["MASTER",[[["Prodi"],["Daftar Prodi","\/prodi"],["Tambah Prodi","\/prodi\/create"],["Program"],["Daftar Program","\/kelas"],["Tambah Program","\/kelas\/create"],["Ruang"],["Daftar Ruang Kuliah","\/ruangan"],["Tambah Ruang Kuliah","\/ruangan\/create"]],[["FILE"],["Daftar File","\/file"],["Upload File","\/upload\/file"],["PENGUMUMAN"],["Daftar Pengumuman","\/informasi"],["Posting Pengumuman","\/informasi\/create"]]]],["PROGRAM KERJA","\/program"], ["VALIDASI","\/validasi"]],
		"a28fefd820b6fdbc383c5edddfc87475":[["DAFTAR MAHASISWA","\/mahasiswa"],["KEUANGAN",[[["Form Pembayaran","\/pembayaran\/form"],["Riwayat Pembayaran","\/biaya"],["Tagihan Pembayaran","\/tagihan"],["Jenis Pembayaran","\/jenisbiaya"],["Setup Biaya Kuliah","\/biaya\/setup"],["Privilege Pembayaran","\/tagihan\/privilege"],["Rincian Biaya Pendidikan","\/biaya\/detail"]]]],["DOSEN",[[["Daftar Dosen","\/profildosen"],["Absensi Dosen","\/dosen\/absensi"],["Tambah Absensi Dosen","\/dosen\/absensi\/create"],["Daftar Pembayaran Gaji","\/gaji"],["Jenis Gaji","\/jenisgaji"],["Tambah Jenis Gaji","\/jenisgaji\/create"]]]],["TRANSAKSI",[[["Daftar Transaksi","\/transaksi"],["Transaksi Baru","\/transaksi\/create"]]]],["KALENDER AKADEMIK","\/kalenderakademik"],["VALIDASI","\/validasi"]],
		
		"0754925017ce85c8259b4522db3a6d99":[["PRIVILEGE PEMBAYARAN","\/tagihan\/privilege\/golongan\/2"]],
		
		"56f674121d782dc0c9582ede0d31b035":[["PKM","\/pkm"]],
		
		"7b7bc2512ee1fedcd76bdc68926d4f7b":[
		["MAHASISWA",[[
		["Mahasiswa"], 
		["Daftar Mahasiswa","\/mahasiswa"],
		["Cuti Mahasiswa","\/mahasiswa\/cuti"],
		["Aktivitas Kuliah","\/mahasiswa\/akm"],
		["Validasi KRS","\/validasi\/krs"],
		["e-Tugas","\/mahasiswa\/tugas"],
		["Skripsi","\/skripsi"],
		["Prestasi","\/mahasiswa\/prestasi"],
		["Buku","\/mahasiswa\/buku"],
		["Jurnal","\/mahasiswa\/jurnal"],
		["Tulisan","\/mahasiswa\/tulisan"],
		["Penelitian","\/mahasiswa\/penelitian"]
		],
		[
		["Pendaftaran"],
		["CaMABA","\/pmb"],["PKM / PMK","\/pkm"],["PPL / PLP","\/ppl"],
		["Pengajuan Judul Skripsi","\/jadwal\/pengajuan\/skripsi"],
		["List Ajuan Judul Skripsi","\/jadwal\/pengajuan\/skripsi\/gelombang\/semua\/peserta"],
		["Ujian Proposal Skripsi","\/ujian\/skripsi\/proposal"],
		["Ujian Kompre Skripsi","\/ujian\/skripsi\/komprehensif"],
		["Wisuda","\/wisuda"]
		],
		[
		["Edit Data"],
		["Jenis Pembiayaan","\/mahasiswa\/adminpembiayaan"],
		["Data Mahasiswa","\/mahasiswa\/transfer"],
		["Perwalian","\/mahasiswa\/adminperwalian"],
		["Status","\/mahasiswa\/adminstatus"],
		["Impor Data"],
		["Impor Data Mahasiswa","\/mahasiswa\/impor"],["Impor Data Yudisium","mahasiswa\/yudisium\/impor"]		
		]
		]],
		["DOSEN",[[
		["Dosen"], ["Daftar Dosen","\/dosen"],["Tambah Dosen","\/dosen\/create"],["Absensi Dosen","\/dosen\/absensi"]],
		[["Riwayat Dosen"],["Riwayat Pendidikan","\/dosen\/pendidikan"],["Riwayat Sertifikasi","\/dosen\/sertifikasi"],["Riwayat Penugasan","\/dosen\/penugasan"],["Riwayat Fungsional","\/dosen\/fungsional"],["Riwayat Kepangkatan","\/dosen\/kepangkatan"]]]],
		["AKADEMIK",[[
		["Akademik"], ["Daftar Prodi","\/prodi"],["Daftar Program","\/kelas"],["Daftar Tahun Akademik","\/tapel"],
		["Kalender Akademik","\/kalender"],["Daftar Ruang Kuliah","\/ruangan"]
		],
		[
		["Perkuliahan"],["Kelas Perkuliahan","\/matkul\/tapel"],["Jadwal Kuliah","\/jadwal"],["Mata Kuliah","\/matkul"],["Kurikulum","\/kurikulum"],["Komponen Penilaian","\/jenisnilai"],["Skala Nilai","\/skala"]
		]]],
		["KEUANGAN",[[
		["Form"],
		["Pembayaran Mahasiswa","\/pembayaran\/form"],
		["Pembayaran Dosen","\/gajidosen"],
		["Laporan"],["Riwayat Pembayaran","\/biaya"],["Tagihan Pembayaran","\/tagihan"],["Rincian Biaya Pendidikan","\/biaya\/detail"],["Data Pemb. Gaji Dosen","\/gaji"]],
		[["Setup"],["Setup Biaya Kuliah","\/biaya\/setup"],["Privilege Pembayaran","\/tagihan\/privilege"],["Jenis Pembayaran","\/jenisbiaya"],["Setup Jenis Gaji","\/jenisgaji"]]]],
		["KUESIONER",[[
		["Kuesioner"], ["Pertanyaan Kuesioner","\/kuesioner"],["Buat Kuesioner","\/kuesioner\/create"]],
		[["Penilaian"],["Persiapan Dosen","\/kuesioner\/#"],["Penilaian Dosen","\/kuesioner\/results"],["Pemahaman Visi","\/kuesioner\/#"],["Administrasi","\/kuesioner\/#"],["Dosen DPA","\/kuesioner\/#"],["Dosen DPS","\/kuesioner\/#"]]
		]],
		
		["MASTER",[[
		["Pimpinan PT"],["Daftar Pimpinan","\/pimpinan"],["Daftar Pegawai","\/pegawai"],
		["BANK"],["Daftar Bank","\/bank"]
		],
		[
		["UPLOAD"],["Daftar File","\/file"],["Upload File","\/upload\/file"],["Daftar Pengumuman","\/informasi"],["Posting Pengumuman","\/informasi\/create"],
		["PENGATURAN"],["Pengaturan","\/config"],["Backup","\/backup"]
		]],"right-aligned"],
		
		["EXPORT",[
		[["IMPORTER"],["Kurikulum","\/export\/kurikulum"],["Mahasiswa","\/export\/dikti\/mahasiswa"],["Kelas Kuliah","\/export\/dikti\/kelaskuliah"],["KRS","\/export\/dikti\/krs"],["Dosen Ajar","\/export\/dikti\/dosen"],["Nilai Perkuliahan","\/export\/dikti\/nilai"],["AKM","\/export\/dikti\/akm"],["Prestasi","\/export\/dikti\/prestasi"],["Kelulusan","\/export\/dikti\/kelulusan"]],
		[
		["FEEDER"],
		["Mahasiswa","\/export\/feeder\/mahasiswa"],
		["History Pendidikan","\/update\/feeder\/riwayat"],
		["Kelas Kuliah","\/export\/feeder\/kelaskuliah"],
		["KRS Mahasiswa","\/export\/feeder\/krs"],
		["Nilai Perkuliahan","\/export\/feeder\/nilaiv1"],
		["AKM Mahasiswa","\/export\/feeder\/akm"],
		["Kelulusan","\/export\/feeder\/kelulusan"],
		
		["Prestasi Mahasiswa","\/export\/feeder\/prestasi"],
		["Periode perkuliahan","\/export\/feeder\/periode"],
		["Skala Nilai","\/export\/feeder\/skala"]
		],
		[
		["EMIS PTKI"],["Dosen","\/export\/emis\/dosen"],["Non Dosen","\/pegawai"],["Tugas Dosen","\/dosen\/penugasan"],["Mahasiswa Reguler","\/export\/emis\/mahasiswa"],["Lulusan","\/export\/emis\/lulusan"],["Jurnal Dosen","\/dosen\/jurnal"],["Buku Dosen","\/dosen\/buku"],["Penelitian Dosen","\/dosen\/penelitian"],
		["KOPERTAIS IV"],["Transkrip Nilai","\/export\/transkrip_merge"]		
		]
		],"right-aligned"],
		
		["PENGGUNA",[[
		["Pengguna Mahasiswa","\/pengguna\/?filter=mahasiswa"],
		["Pengguna Dosen","\/pengguna\/?filter=dosen"],
		["Pengguna Struktural","\/pengguna\/?filter=struktural"],
		["Ubah Pass. Mahasiswa Terpilih","\/pengguna\/resetpassword\/mahasiswa\/filter"],
		["Ubah Pass. Semua Mahasiswa","\/pengguna\/resetpassword\/mahasiswa"],
		["Tambah Pengguna","\/pengguna\/create"]
		]],"right-aligned"]
		]}'
		, true);
		
		/* ,["BUG",[[
			["Lapor Bug Baru","\/report\/create"],
			["Status","\/report"]
		]]] */
		/* ,["Periksa Pembaruan","\/patch\/check"] */
		
		if($rolename == 'fa03eb688ad8aa1db593d33dabd89bad' ?? $rolename == '7b7bc2512ee1fedcd76bdc68926d4f7b') 
		$menus = array_merge($home, $menu_available['7b7bc2512ee1fedcd76bdc68926d4f7b']);
		else 
		$menus = isset($menu_available[$rolename]) ? array_merge($home, $menu_available[$rolename]) : $home;
		
		foreach($menus as $k => $menu)
		{
			// if($menu[0] == 'BUG') $menu[0] = '<span style="color: red; font-weight: bold;"><i class="fa fa-bug"></i></span>';
			if(is_array($menu[1])) 
			{
				echo '<li>';
				echo '<span class="top-heading">'. $menu[0] .'</span>';
				echo '<i class="caret"></i>';
				echo '<div class="dropdown';
				echo isset($menu[2]) ? ' ' . $menu[2] : '';
				echo '">';
				echo '<div class="dd-inner">';
				foreach($menu[1] as $column)
				{
					echo '<ul class="column">';
					foreach($column as $sub)
					{
						if(isset($sub[1])) echo '<li><a href="'. url($sub[1]) .'">'. $sub[0] .'</a></li>';
						else echo '<li><h3>'. $sub[0] .'</h3></li>';
					}
					echo '</ul>';
				}
				echo '</div>';
				echo '</div>';
				echo '</li>';
			}
			else echo '<li class="no-sub"><a class="top-heading" href="' . url($menu[1]) . '">'. $menu[0] .'</a></li>';
		}
	?>
</ul>																											