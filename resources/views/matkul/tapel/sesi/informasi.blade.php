<div class="f-col-1">
	<h4><i class="fa fa-info"></i> Informasi Kelas Kuliah</h4>
	<div class="f-box">
		<div class="f-box-body" style="display: flex; flex-direction: column; justify-content: center; align-items: center;">				
			<div class="kelas-logo">
				{{ $kelas -> kurikulum -> matkul -> singkatan }}
			</div>
			<h4>{{ $kelas -> kurikulum -> matkul -> nama }}</h4>
			<p>KELAS: {{ $kelas -> kurikulum -> semester }} {{ $kelas -> kelas2 }}</p>
			<div class="kelas-detail">
				<div class="text-muted">Dosen pengajar</div>
				@foreach($kelas -> tim_dosen as $d)
				<div class="kelas-detail-ket">
					<i class="fa fa-graduation-cap"></i> {{ $d -> gelar_depan }} {{ $d -> nama }} {{ $d -> gelar_belakang }}
				</div>
				@endforeach
			</div>
			<div class="kelas-detail">
				<div class="text-muted">Periode Akademik</div>
				<div class="kelas-detail-ket">
					{{ $kelas -> tapel -> nama }}
				</div>
			</div>
			<div class="kelas-detail">
				<div class="text-muted">Nama Kelas</div>
				<div class="kelas-detail-ket">
					{{ $kelas -> kurikulum -> semester }} {{ $kelas -> kelas2 }}
				</div>
			</div>
		</div>
	</div>
</div>