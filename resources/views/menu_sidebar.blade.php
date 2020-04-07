<ul class="sidebar-menu">
	<li class="header">MENU NAVIGASI</li>			
	<li><a href="{{ url('/') }}"><i class="fa fa-home"style="font-size:19px;color:coral"></i> <span>BERANDA</span></a></li>
	
	@if($rolename == 'pmb')
	<li><a href="{{ url('/pmb') }}"><i class=" fa fa-drivers-license-o"></i> <span>PMB</span></a></li>
	@elseif($rolename == 'keuangan / administrasi')
	<!-- Keuangan / Administrasi -->
	<li><a href="{{ url('/mahasiswa') }}"><i class="ion ion-ios-people"style="font-size:19px;color:yellow"></i> <span>Daftar Mahasiswa</span></a></li>
	<li class="treeview">
		<a href="#" ><i class="fa fa-money"style="font-size:19px;color:purple"></i> <span> KEUANGAN</span><span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i> </span></a>
		<ul class="treeview-menu">
			<li><a href="{{ url('/biaya') }}"> Riwayat Pembayaran</a></li>
			<li><a href="{{ url('/biaya/form') }}"> Form Pembayaran</a></li>
			<li><a href="{{ url('/jenisbiaya') }}">Jenis Pembayaran</a></li>
			<li><a href="{{ url('/biaya/setup') }}"> Setup Biaya Kuliah</a></li>
			<li><a href="{{ url('/biaya/detail') }}"> Rincian Biaya Pendidikan</a></li>
		</ul>
	</li>
	
	<li class="treeview">
		<a href="#" ><i class="fa fa-street-view"style="font-size:19px;color:orange"></i> <span> DOSEN </span><span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span></a>
		<ul class="treeview-menu">
			<li><a href="{{ url('/profildosen') }}"> Daftar Dosen</a></li>
			
			<li><a href="{{ url('/dosen/absensi') }}"> Absensi Dosen</a></li>
			<li><a href="{{ url('/dosen/absensi/create') }}"> Tambah Absensi Dosen</a></li>
			
			<li><a href="{{ url('/gaji') }}"> Daftar Pembayaran Gaji</a></li>
			
			<li><a href="{{ url('/jenisgaji') }}">Jenis Gaji</a></li>
			<li><a href="{{ url('/jenisgaji/create') }}">Tambah Jenis Gaji</a></li>
		</ul>
	</li>
	
	<li class="treeview">
		<a href="#" ><i class="fa fa-exchange"style="font-size:19px;color:red"></i> <span> TRANSAKSI</span><span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i> </span></a>
		<ul class="treeview-menu">
			<li><a href="{{ url('/transaksi') }}">Daftar Transaksi</a></li>
			<li><a href="{{ url('/transaksi/create') }}"> Transaksi Baru</a></li>
		</ul>
	</li>
	<!--li><a href="{{ url('/neraca') }}"><i class="fa fa-balance-scale"></i> <span> Neraca</a></li-->
	<li><a href="{{ url('/kalenderakademik') }}"><i class="fa fa-calendar"style="font-size:19px;color:brown"></i> <span> KALENDER AKADEMIK</a></li>
		
		@elseif($rolename == 'mahasiswa')
		<!-- Mahasiswa -->
		<li><a href="{{ url('/profil') }}"><i class="fa fa-user-circle"style="font-size:19px;color:red"></i> <span> Profil</span></a></li>
		<li><a href="{{ url('/khs') }}"><i class="fa fa-list-alt"style="font-size:19px;color:yellow"></i> <span> Kartu Hasil Studi</span></a></li>
		<li class="treeview">
			<a href="{{ url('/krs') }}"><i class="fa fa-leanpub"style="font-size:19px;color:green"></i> <span> Kartu Rencana Studi</span><span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span></a>
			<ul class="treeview-menu">
				<li><a href="{{ url('/tawaran') }}"><span> Tawaran Mata Kuliah</span></a></li>
				<li><a href="{{ url('/krs') }}"><span> Kartu Rencana Studi</span></a></li>
			</ul>
		</li>
		<li><a href="{{ url('/jadwalmahasiswa') }}"><i class="fa fa-credit-card-alt"style="font-size:16px;color:orange"></i> <span> Jadwal Perkuliahan</span></a></li>
		<li><a href="{{ url('/kalenderakademik') }}"><i class="fa fa-calendar"style="font-size:19px;color:purple"></i> <span> Kalender Akademik</span></a></li>
		<li class="treeview">
			<a href=""><i class="fa fa-money"style="font-size:19px;color:brown"></i> <span> Keuangan</span><span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span></a>
			<ul class="treeview-menu">
				<li><a href="{{ url('/biaya/tanggungan') }}"><span> Status Pembayaran</span></a></li>
				<li><a href="{{ url('/biaya/riwayat') }}"><span> Riwayat Pembayaran</span></a></li>
			</ul>
		</li>
		<?php $data = $user -> authable; ?>
		@if($data -> semesterMhs >= 7)
		<li class="treeview">
			<a href=""><i class="fa fa-product-hunt"style="font-size:19px;color:aqua"></i> <span> Pendaftaran</span><span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span></a>
			<ul class="treeview-menu">
				<li><a href="{{ url('/pkm/daftar') }}"><span> PKM</span></a></li>
				<!--li><a href="{{ url('/pkm/info') }}"><span> Informasi PKM</span></a></li-->
				<li><a href="{{ url('/ppl/daftar') }}"><span> PPL</span></a></li>
				<!--li><a href="{{ url('/ppl/info') }}"><span> Informasi PPL</span></a></li-->
				@if($data -> semesterMhs >= 8)
				<li><a href="{{ url('/wisuda/daftar') }}"><span> Wisuda</span></a></li>
				<!--li><a href="{{ url('/wisuda/info') }}"><span> Informasi Wisuda</span></a></li-->
				@endif
			</ul>
		</li>
		@endif
		<li><a href="{{ url('/prestasi') }}"><i class="fa fa-trophy"style="font-size:19px;color:aqua"></i> <span> Prestasi</span></a></li>
		<li class="treeview">
			<a href=""><i class="fa fa-print"style="font-size:19px;color:lightblue"></i> <span> Cetak Kartu Ujian</span><span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span></a>
			<ul class="treeview-menu">
				<li><a href="{{ route('mahasiswa.cetak.kartu', [$data -> id, 'uts']) }}"><span> Kartu UTS</span></a></li>
				<li><a href="{{ route('mahasiswa.cetak.kartu', [$data -> id, 'uas']) }}"><span> Kartu UAS</span></a></li>
			</ul>
		</li>
		
		@elseif($rolename == 'akademik')
		<!-- Akademik -->
		<li class="treeview">
			<a href="#" ><i class="ion ion-ios-people"style="font-size:19px;color:yellow"></i> <span> MAHASISWA </span><span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i> </span></a>
			<ul class="treeview-menu">
				<li><a href="{{ url('/mahasiswa') }}"><span>Daftar Mahasiswa</span></a></li>
				<li><a href="{{ url('/mahasiswa/create') }}"><span>Tambah Mahasiswa Baru</span></a></li>
				
				<li class="treeview">
					<a href="#" ><span>Pendaftaran</span><span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span></a>
					<ul class="treeview-menu">
						<li><a href="{{ url('/wisuda') }}"><span>Wisuda</span></a></li>
						<li><a href="{{ url('/pkm') }}"><span>PKM</span></a></li>
						<li><a href="{{ url('/ppl') }}"><span>PPL</span></a></li>
						<li><a href="{{ url('/pmb') }}"><span>PMB</span></a></li>
					</ul>
				</li>
				
				<li><a href="{{ url('/cuti') }}"><span>Cuti Mahasiswa</span></a></li>
				
				<li class="treeview">
					<a href="#" ><span>Impor Data</span><span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span></a>
					<ul class="treeview-menu">
						<li><a href="{{ url('/mahasiswa/impor') }}"><span>Impor Data Mahasiswa</span></a></li>
						<li><a href="{{ url('mahasiswa/yudisium/impor') }}"><span>Impor Data Yudisium</span></a></li>
					</ul>
				</li>
				
				<li class="treeview">
					<a href="#" ><span>Edit Data</span><span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span></a>
					<ul class="treeview-menu">
						<!--
							<li><a href="{{ url('/mahasiswa/adminpembiayaan') }}"><span> Jenis Pembiayaan</span></a></li>
						-->
						<li><a href="{{ url('/mahasiswa/transfer') }}"><span> Data Mahasiswa</span></a></li>
						<li><a href="{{ url('/mahasiswa/adminperwalian') }}"><span> Perwalian</span></a></li>
						<li><a href="{{ url('/mahasiswa/adminstatus') }}"><span> Status</span></a></li>
					</ul>
				</li>
				<li><a href="{{ url('/skripsi') }}"><span>Skripsi</span></a></li>
				<li><a href="{{ url('/mahasiswa/prestasi') }}"><span>Prestasi</span></a></li>
			</ul>
		</li>
		<li class="treeview">
			<a href="#" ><i class="fa fa-street-view"style="font-size:19px;color:orange"></i> <span> DOSEN </span><span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span></a>
			<ul class="treeview-menu">
				<li><a href="{{ url('/dosen') }}"> Daftar Dosen</a></li>
				<li><a href="{{ url('/dosen/absensi') }}"> Absensi Dosen</a></li>
				<li><a href="{{ url('/dosen/pendidikan') }}"> Riwayat Pendidikan</a></li>
				<li><a href="{{ url('/dosen/sertifikasi') }}"> Riwayat Sertifikasi</a></li>
				<li><a href="{{ url('/dosen/penugasan') }}"> Riwayat Penugasan</a></li>
				<li><a href="{{ url('/dosen/fungsional') }}"> Riwayat Fungsional</a></li>
				<li><a href="{{ url('/dosen/kepangkatan') }}"> Riwayat Kepangkatan</a></li>
			</ul>
		</li>
		<li class="treeview">
			<a href="#" ><i class="fa fa-envelope"style="font-size:19px;color:amber"></i> <span> PERKULIAHAN</span><span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i> </span></a>
			<ul class="treeview-menu">
				
				<li><a href="{{ url('/matkul/tapel') }}"> Kelas Perkuliahan</a></li>
				<li><a href="{{ url('/jadwal') }}"> Jadwal Kuliah</a></li>
				<li><a href="{{ url('/matkul') }}"> Mata Kuliah</a></li>				
				<li><a href="{{ url('/kurikulum') }}"> Kurikulum</a></li>
			</ul>
		</li>
		<li class="treeview">
			<a href="#" ><i class="fa fa-calendar"style="font-size:19px;color:brown"></i> <span> AKADEMIK</span><span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i> </span></a>
			<ul class="treeview-menu">
				<li><a href="{{ url('/kalender') }}"><span> Kalender Akademik</span></a></li>
				<li><a href="{{ url('/tapel') }}"><span> Daftar Tahun Akademik</span></a></li>
				<li><a href="{{ url('/tapel/create') }}"><span> Tambah Tahun Akademik</span></a></li>
				<li><a href="{{ url('/kalender/create') }}"><span> Tambah Kegiatan Akademik</span></a></li>
			</ul>
		</li>
		<li class="treeview">
			<a href="#" ><i class="fa fa-exchange"style="font-size:19px;color:red"></i> <span> EXPORT DATA  </span><span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i> </span></a>
			<ul class="treeview-menu">
				<li class="treeview">
					<a href="#" ><i class="#"></i> <span>KOPERTAIS IV</span><span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i> </span></a>
					<ul class="treeview-menu">
						<li><a href="{{ url('export/transkrip_merge') }}"><span>Transkrip Merge</span></a></li>
					</ul>
				</li>
			</ul>
		</li>
		<li class="treeview">
			<a href="#" ><i class="fa fa-bullhorn"style="font-size:19px;color:sepia"></i> <span> PENGUMUMAN</span><span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i> </span></a>
			<ul class="treeview-menu">
				<li><a href="{{ url('/informasi') }}">Daftar Pengumuman</a></li>
				<li><a href="{{ url('/informasi/create') }}">Posting Pengumuman</a></li>
			</ul>
		</li>
		<li class="treeview">
			<a href="#" ><i class="fa fa-upload"style="font-size:19px;color:aqua"></i> <span> FILE UPLOAD</span><span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i> </span></a>
			<ul class="treeview-menu">
				<li><a href="{{ url('/file') }}"> Daftar File</a></li>
				<li><a href="{{ url('/upload/file') }}"> Upload File</a></li>
			</ul>
		</li>
		<li class="treeview">
			<a href="#" ><i class="fa fa-hdd-o"style="font-size:19px;color:teal"></i> <span> MASTER DATA</span><span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i> </span></a>
			<ul class="treeview-menu">					
				<li><a href="{{ url('/prodi') }}"> Daftar Prodi</a></li>
				<li><a href="{{ url('/prodi/create') }}"> Tambah Prodi</a></li>
				
				<li><a href="{{ url('/kelas') }}"> Daftar Program</a></li>
				<li><a href="{{ url('/kelas/create') }}"> Tambah Program</a></li>
				
				<li><a href="{{ url('/ruangan') }}"> Daftar Ruang Kuliah</a></li>
				<li><a href="{{ url('/ruangan/create') }}"> Tambah Ruang Kuliah</a></li>
			</ul>
		</li>
		<li><a href="{{ url('/program') }}"><i class="fa fa-exchange"style="font-size:19px;color:red"></i> <span> PROGRAM KERJA</a></li>
			
			@elseif($rolename == 'p2m (pusat penjaminan mutu)')
			<li class="treeview">
				<a href="#" ><i class="fa fa-question-circle"></i> <span> Kuesioner</span><span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i> </span></a>
				<ul class="treeview-menu">
					<li><a href="{{ url('/kuesioner') }}"> Daftar Pertanyaan Kuesioner</a></li>
					<li><a href="{{ url('/kuesioner/create') }}"> Tambah Pertanyaan</a></li>
					<li><a href="{{ url('/kuesioner/results') }}"> Hasil Kuesioner</a></li>
					
					<li class="treeview">
						<a href="#" ><span>Penilaan</span><span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span></a>
						<ul class="treeview-menu">
							<li><a href="{{ url('/kuesioner/#') }}"><span> Persiapan Dosen</span></a></li>
							<li><a href="{{ url('/kuesioner/results') }}"><span> Penilaian Dosen</span></a></li>
							<li><a href="{{ url('/kuesioner/#') }}"><span> Pemahaman Visi</span></a></li>
							<li><a href="{{ url('/kuesioner/#') }}"><span> Administrasi</span></a></li>
							<li><a href="{{ url('/kuesioner/#') }}"><span> Dosen DPA</span></a></li>
							<li><a href="{{ url('/kuesioner/#') }}"><span> Dosen DPS</span></a></li>
						</ul>	
					</li>
					
					<li><a href="{{ url('/program') }}"><i class="fa fa-map-signs"></i> <span> Program Kerja</span></a></li>
					
					@elseif($rolename == 'kemahasiswaan')
					<!-- Kemahasiswaan -->
					<li class="treeview">
						<a href="#" ><i class="fa fa-calendar"></i> <span> Kalender Akademik</span><span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i> </span></a>
						<ul class="treeview-menu">
							<li><a href="{{ url('/kalender') }}"> Kalender Akademik</a></li>
							<li><a href="{{ url('/kalender/create') }}"> Tambah Kegiatan Akademik</a></li>
						</ul>
					</li>
					<li><a href="{{ url('/program') }}"><i class="fa fa-map-signs"></i> <span> Program Kerja</span></a></li>
					
					@elseif($rolename == 'prodi')
					<!-- PRODI -->
					<li><a href="{{ url('/mahasiswa') }}"><i class="ion ion-ios-people"style="font-size:19px;color:yellow"></i> <span>Daftar Mahasiswa</span></a></li>
					<li class="treeview">
						<a href="#" ><i class="fa fa-map-o"></i> <span> Jadwal Kuliah</span><span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i> </span></a>
						<ul class="treeview-menu">
							<li><a href="{{ url('/jadwal') }}"> Lihat Jadwal Kuliah</a></li>
							<li><a href="{{ url('/jadwal/create') }}"> Buat Jadwal Kuliah</a></li>
						</ul>
					</li>
					<li><a href="{{ url('/profildosen') }}"><i class="fa fa-briefcase"></i> <span> Profil Dosen</span></a></li>
					<li><a href="{{ url('/program') }}"><i class="fa fa-map-signs"></i> <span> Program Kerja</span></a></li>
					
					@elseif($rolename == 'ketua' )
					<!-- Ketua -->
					<li><a href="{{ url('/mahasiswa') }}"><i class="ion ion-ios-people"></i> <span> Daftar Mahasiswa</span></a></li>
					<li><a href="{{ url('/dosen') }}"><i class="fa fa-briefcase"></i> <span> Daftar Dosen</span></a></li>
					<li class="treeview">
						<a href="#" ><i class="fa fa-bullhorn"></i> <span> Pengumuman</span><span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i> </span></a>
						<ul class="treeview-menu">
							<li><a href="{{ url('/informasi') }}">Daftar Pengumuman</a></li>
							<li><a href="{{ url('/informasi/create') }}">Posting Pengumuman</a></li>
						</ul>
					</li>
					<li><a href="{{ url('/program/lihat') }}"><i class="fa fa-map-signs"></i> <span> Program Kerja</span></a></li>
					
					@elseif($rolename == 'dosen')
					<!-- Dosen -->
					<li class="treeview">
						<a href="#" ><i class="fa fa-envelope"style="font-size:19px;color:amber"></i> <span> PERKULIAHAN</span><span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i> </span></a>
						<ul class="treeview-menu">
							<li><a href="{{ url('/kelaskuliah') }}"> Aktifitas Mengajar</a></li>
							<li><a href="{{ url('/jadwaldosen') }}"> Jadwal Mengajar</a></li>
						</ul>
					</li>
					<li><a href="{{ url('/perwalian') }}"><i class="ion ion-ios-people"style="font-size:19px;color:yellow"></i> <span> DOSEN WALI</span></a></li>
					<li><a href="{{ url('/kalenderakademik') }}"><i class="fa fa-calendar"style="font-size:19px;color:brown"></i> <span> KALENDER AKADEMIK</span></a></li>
					<!--
						<li><a href="{{ url('/gaji') }}"><i class="fa fa-credit-card"style="font-size:19px;color:aqua"></i> <span> GAJI</span></a></li>
					-->
					<!--li class="treeview">
						<a href="#" ><i class="fa fa-odnoklassniki"></i> <span> Profil</span><span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i> </span></a>
						<ul class="treeview-menu">
						<li><a href="{{ url('/profil') }}"> Tampilkan Profil</a></li>
						<li><a href="{{ url('/profil/edit') }}"> Edit Profil</a></li>
						</ul>
					</li-->
					
					@elseif($rolename == 'root' ?? $rolename == 'administrator')
					<li class="treeview">
						<a href="#" ><i class="ion ion-ios-people"style="font-size:19px;color:yellow"></i> <span> MAHASISWA </span><span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i> </span></a>
						<ul class="treeview-menu">
							<li><a href="{{ url('/mahasiswa') }}"><span>Daftar Mahasiswa</span></a></li>
							<li><a href="{{ url('/mahasiswa/create') }}"><span>Tambah Mahasiswa Baru</span></a></li>
							
							<li class="treeview">
								<a href="#" ><span>Pendaftaran</span><span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span></a>
								<ul class="treeview-menu">
									<li><a href="{{ url('/wisuda') }}"><span>Wisuda</span></a></li>
									<li><a href="{{ url('/pkm') }}"><span>PKM</span></a></li>
									<li><a href="{{ url('/ppl') }}"><span>PPL</span></a></li>
									<li><a href="{{ url('/pmb') }}"><span>PMB</span></a></li>
								</ul>
							</li>
							<li><a href="{{ url('/mahasiswa/cuti') }}"><span>Mahasiswa Cuti </span></a></li>
							
							<li class="treeview">
								<a href="#" ><span>Impor Data</span><span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span></a>
								<ul class="treeview-menu">
									<li><a href="{{ url('/mahasiswa/impor') }}"><span>Impor Data Mahasiswa</span></a></li>
									<li><a href="{{ url('mahasiswa/yudisium/impor') }}"><span>Impor Data Yudisium</span></a></li>
								</ul>
							</li>
							
							<li class="treeview">
								<a href="#" ><span>Edit Data</span><span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span></a>
								<ul class="treeview-menu">
									<li><a href="{{ url('/mahasiswa/adminpembiayaan') }}"><span> Jenis Pembiayaan</span></a></li>
									<li><a href="{{ url('/mahasiswa/transfer') }}"><span> Data Mahasiswa</span></a></li>
									<li><a href="{{ url('/mahasiswa/adminperwalian') }}"><span> Perwalian</span></a></li>
									<li><a href="{{ url('/mahasiswa/adminstatus') }}"><span> Status</span></a></li>
								</ul>
							</li>
							<li><a href="{{ url('/mahasiswa/prestasi') }}"><span>Prestasi</span></a></li>
							<li><a href="{{ url('/skripsi') }}"><span>Skripsi</span></a></li>
							
						</ul>
					</li>
					<li class="treeview">
						<a href="#" ><i class="fa fa-street-view"style="font-size:19px;color:orange"></i> <span> DOSEN </span><span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span></a>
						<ul class="treeview-menu">
							<li><a href="{{ url('/dosen') }}"> Daftar Dosen</a></li>
							<li><a href="{{ url('/dosen/create') }}"> Tambah Dosen</a></li>
							<li><a href="{{ url('/dosen/absensi') }}"> Absensi Dosen</a></li>
							
							<li class="treeview">
								<a href="#" ><span>Riwayat Dosen</span><span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span></a>
								<ul class="treeview-menu">
									<li><a href="{{ url('/dosen/pendidikan') }}"><span> Riwayat Pendidikan<span></a></li>
										<li><a href="{{ url('/dosen/sertifikasi') }}"><span> Riwayat Sertifikasi<span></a></li>
											<li><a href="{{ url('/dosen/penugasan') }}"><span> Riwayat Penugasan<span></a></li>
												<li><a href="{{ url('/dosen/fungsional') }}"><span> Riwayat Fungsional<span></a></li>
													<li><a href="{{ url('/dosen/kepangkatan') }}"><span> Riwayat Kepangkatan<span></a></li>
													</ul>
													</li>	
												</ul>
												</li>
												<li class="treeview">
													<a href="#" ><i class="fa fa-envelope"style="font-size:19px;color:amber"></i> <span> PERKULIAHAN</span><span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i> </span></a>
													<ul class="treeview-menu">
														
														<li><a href="{{ url('/matkul/tapel') }}"> Kelas Perkuliahan</a></li>
														<li><a href="{{ url('/jadwal') }}"> Jadwal Kuliah</a></li>
														<li><a href="{{ url('/matkul') }}"> Mata Kuliah</a></li>
														
														<li><a href="{{ url('/kurikulum') }}"> Kurikulum</a></li>
														<!--li><a href="{{ url('/skala') }}"><span> Skala Nilai</span></a></li-->
													</ul>
												</li>
												<li class="treeview">
													<a href="#" ><i class="fa fa-calendar"style="font-size:19px;color:brown"></i> <span> AKADEMIK</span><span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i> </span></a>
													<ul class="treeview-menu">
														<li><a href="{{ url('/kalender') }}"><span> Kalender Akademik</span></a></li>
														<li><a href="{{ url('/tapel') }}"><span> Daftar Tahun Akademik</span></a></li>
														<li><a href="{{ url('/tapel/create') }}"><span> Tambah Tahun Akademik</span></a></li>
														<li><a href="{{ url('/kalender/create') }}"><span> Tambah Kegiatan Akademik</span></a></li>
													</ul>
												</li>
												
												<li class="treeview">
													<a href="#" ><i class="fa fa-money"style="font-size:19px;color:purple"></i> <span> KEUANGAN</span><span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i> </span></a>
													<ul class="treeview-menu">
														<li><a href="{{ url('/biaya') }}"> Riwayat Pembayaran</a></li>
														<li><a href="{{ url('/biaya/detail') }}"> Rincian Biaya Pendidikan</a></li>
														<li><a href="{{ url('/gajidosen') }}"> Pembayaran Gaji Dosen</a></li>
														<li><a href="{{ url('/gaji') }}"> Data Pemb. Gaji Dosen</a></li>
														<li><a href="{{ url('/jenisgaji/create') }}">Tambah Jenis Gaji</a></li>
														<li><a href="{{ url('/biaya/form') }}"> Form Pembayaran</a></li>
														<li><a href="{{ url('/jenisbiaya') }}">Jenis Pembayaran</a></li>
														<li><a href="{{ url('/biaya/setup') }}"> Setup Biaya Kuliah</a></li>
														<li><a href="{{ url('/jenisgaji') }}">Jenis Gaji</a></li>
													</ul>
												</li>
												
												<li class="treeview">
													<a href="#" ><i class="fa fa-question-circle"style="font-size:19px;color:tan"></i> <span>KUESIONER</span><span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i> </span></a>
													<ul class="treeview-menu">
														<li><a href="{{ url('/kuesioner') }}"> Pertanyaan Kuesioner</a></li>
														<li><a href="{{ url('/kuesioner/create') }}"> Buat Kuesioner</a></li>
														<li><a href="{{ url('/kuesioner/results') }}"> Hasil Kuesioner</a></li>
														<!--
															<li class="treeview">
															<a href="#" ><span>Penilaan</span><span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span></a>
															<ul class="treeview-menu">
															<li><a href="{{ url('/kuesioner/#') }}"><span> Persiapan Dosen</span></a></li>
															<li><a href="{{ url('/kuesioner/results') }}"><span> Penilaian Dosen</span></a></li>
															<li><a href="{{ url('/kuesioner/#') }}"><span> Pemahaman Visi</span></a></li>
															<li><a href="{{ url('/kuesioner/#') }}"><span> Administrasi</span></a></li>
															<li><a href="{{ url('/kuesioner/#') }}"><span> Dosen DPA</span></a></li>
															<li><a href="{{ url('/kuesioner/#') }}"><span> Dosen DPS</span></a></li>
															
															</ul>
															</li>
														-->
													</ul>
												</li>
												<li class="treeview">
													<a href="#" ><i class="fa fa-upload"style="font-size:19px;color:aqua"></i> <span> FILE UPLOAD</span><span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i> </span></a>
													<ul class="treeview-menu">
														<li><a href="{{ url('/file') }}"> Daftar File</a></li>
														<li><a href="{{ url('/upload/file') }}"> Upload File</a></li>
													</ul>
												</li>
												<li class="treeview">
													<a href="#" ><i class="fa fa-bullhorn"style="font-size:19px;color:sepia"></i> <span> PENGUMUMAN</span><span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i> </span></a>
													<ul class="treeview-menu">
														<li><a href="{{ url('/informasi') }}">Daftar Pengumuman</a></li>
														<li><a href="{{ url('/informasi/create') }}">Posting Pengumuman</a></li>
													</ul>
												</li>
												<li class="treeview">
													<a href="#" ><i class="fa fa-hdd-o"style="font-size:19px;color:teal"></i> <span> MASTER DATA</span><span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i> </span></a>
													<ul class="treeview-menu">					
														<li><a href="{{ url('/prodi') }}"> Daftar Prodi</a></li>
														<li><a href="{{ url('/prodi/create') }}"> Tambah Prodi</a></li>
														
														<li><a href="{{ url('/kelas') }}"> Daftar Program</a></li>
														<li><a href="{{ url('/kelas/create') }}"> Tambah Program</a></li>
														
														<li><a href="{{ url('/ruangan') }}"> Daftar Ruang Kuliah</a></li>
														<li><a href="{{ url('/ruangan/create') }}"> Tambah Ruang Kuliah</a></li>
													</ul>
												</li>
												
												<li class="treeview">
													<a href="#" ><i class="fa fa-exchange"style="font-size:19px;color:red"></i> <span> EXPORT DATA  </span><span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i> </span></a>
													<ul class="treeview-menu">
														<li class="treeview">
															<a href="#" ><i class="#"></i><span>PDDIKTI</span><span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i> </span></a>
															<ul class="treeview-menu">
																<li><a href="{{ url('/export/kurikulum') }}"><span>Kurikulum</span></a></li>
																<li><a href="{{ url('/export/dikti/mahasiswa') }}"><span>Mahasiswa</span></a></li>
																<li class="treeview">
																	<a href="#" ><span>Kelas dan KRS</span><span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span></a>
																	<ul class="treeview-menu">
																		<li><a href="{{ url('/export/dikti/kelaskuliah') }}"><span>Kelas Kuliah</span></a></li>
																		<li><a href="{{ url('/export/dikti/krs') }}"><span>KRS</span></a></li>
																		<li><a href="{{ url('/export/dikti/dosen') }}"><span>Dosen Ajar</span></a></li>
																	</ul>
																</li>
																<li><a href="{{ url('/export/dikti/nilai') }}"><span>Nilai Perkuliahan</span></a></li>
																<li><a href="{{ url('/export/dikti/akm') }}"><span>AKM</span></a></li>
																<li><a href="{{ url('/export/dikti/prestasi') }}"><span>Prestasi</span></a></li>
																<li><a href="{{ url('/export/dikti/kelulusan') }}"><span>Kelulusan</span></a></li>
															</ul>
														</li>
														<li class="treeview">
															<a href="#" ><i class="#"></i><span>EMIS PTKI</span><span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i> </span></a>
															<ul class="treeview-menu">
																<li class="treeview">
																	<a href="#" ><span>Dosen</span><span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span></a>
																	<ul class="treeview-menu">
																		<li><a href="{{ url('/export/emis/dosen') }}"><span>Dosen</span></a></li>
																		<li><a href="{{ url('/pegawai') }}"><span>Non Dosen</span></a></li>
																		<li><a href="{{ url('/dosen/penugasan') }}"><span>Tugas Dosen</span></a></li>
																	</ul>
																</li>
																<li class="treeview">
																	<a href="#" ><span>Mahasiswa</span><span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span></a>
																	<ul class="treeview-menu">
																		<li><a href="{{ url('/export/emis/mahasiswa') }}"><span>Mahasiswa Reguler</span></a></li>
																		<li><a href="{{ url('/export/emis/lulusan') }}"><span>Lulusan</span></a></li>
																	</ul>
																</li>
																<li class="treeview">
																	<a href="#" ><span>Penelitian Dosen</span><span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span></a>
																	<ul class="treeview-menu">
																		<li><a href="{{ url('/dosen/jurnal') }}"> Jurnal</a></li>
																		<li><a href="{{ url('/dosen/buku') }}"><span>Buku</span></a></li>
																		<li><a href="{{ url('/dosen/penelitian') }}"><span>Penelitian</span></a></li>
																	</ul>
																</li>
																<li><a href="{{ url('#') }}"><span>Lembaga</span></a></li>
															</ul>
														</li>
														<li class="treeview">
															<a href="#" ><i class="#"></i> <span>KOPERTAIS IV</span><span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i> </span></a>
															<ul class="treeview-menu">
																<li><a href="{{ url('export/transkrip_merge') }}"><span>Transkrip Merge</span></a></li>
															</ul>
														</li>
													</ul>
												</li>
												<li class="treeview">
													<a href="#" ><i class="fa fa-user"style="font-size:19px;color:green"></i> <span>DATA PENGGUNA</span><span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i> </span></a>
													<ul class="treeview-menu">
														<li><a href="{{ url('/pengguna/resetpassword/mahasiswa/filter') }}"> Ubah Pass. Mahasiswa Terpilih</a></li>
														<li><a href="{{ url('/pengguna/resetpassword/mahasiswa') }}"> Ubah Pass. Semua Mahasiswa</a></li>
														<li><a href="{{ url('/pengguna/?filter=mahasiswa') }}"> Pengguna Mahasiswa</a></li>
														<li><a href="{{ url('/pengguna/?filter=struktural') }}"> Pengguna Struktural</a></li>
														<li><a href="{{ url('/pengguna/create') }}"> Tambah Pengguna</a></li>
														<li><a href="{{ url('/pengguna/?filter=dosen') }}"> Pengguna Dosen</a></li>
														
														<!--li><a href="{{ url('/pengguna/resetpassword/dosen') }}"> Reset Password Dosen</a></li-->
														
													</ul>
												</li>
												<li><a href="{{ url('/config') }}" ><i class="fa fa-wrench"style="font-size:19px;color:pink"></i> <span>PENGATURAN</span></a></li>
												@endif	
												<!--li class="treeview">
													<a href="{{ url('/mail') }}">
													<i class="fa fa-envelope"></i> <span>Pesan</span>
													<span class="pull-right-container">
													<i class="fa fa-angle-left pull-right"></i>
													</span>
													</a>
													<ul class="treeview-menu menu-open" style="display: block;">
													<li class="">
													<a href="{{ url('/mail') }}">Kotak Masuk
													<span class="pull-right-container">
													<span class="label label-danger pull-right">1</span>
													</span>
													</a>
													</li>
													<li><a href="{{ url('/mail/compose') }}">Buat Pesan</a></li>
													</ul>
												</li-->
												@if($rolename == 'root')
												<li><a href="{{ url('/backup') }}" ><i class="fa fa-eye"></i> <span>Backup</span></a></li>
												@endif	
												<li><a data-toggle="modal" href="{{ url('/about') }}" data-target="#about"><i class="fa fa-eye"></i> <span> ABOUT</span></a></li>
											</ul>																																																																																																																																																																																																																																																				
																						