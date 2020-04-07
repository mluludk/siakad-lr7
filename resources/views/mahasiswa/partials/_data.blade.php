<div class="box box-primary">
	<div class="box-header with-border">	
		<h3 class="box-title">Data Mahasiswa</h3>
	</div>
	<div class="box-body">
		<table>
			<tr>
				<th width="14%">Nama</th><td>:&nbsp;</td><td width="50%">{{ $mahasiswa -> nama }}</td>
				<th width="20%">NIM</th><td>:&nbsp;</td><td>{{ $mahasiswa -> NIM }}</td>
			</tr>
			<tr>
				<th>PRODI</th><td>:&nbsp;</td><td>{{ $mahasiswa -> prodi -> strata }} {{ $mahasiswa -> prodi -> nama }}</td>
				<th>Program</th><td>:&nbsp;</td><td>{{ $mahasiswa -> kelas -> nama }}</td>
			</tr>
			<tr>
				<th>Semester</th><td>:&nbsp;</td><td>{{ $mahasiswa -> semesterMhs }}</td>
				<th>Dosen PA</th><td>:&nbsp;</td><td>{{ $mahasiswa -> dosenwali -> gelar_depan }} {{ $mahasiswa -> dosenwali -> nama }} {{ $mahasiswa -> dosenwali -> gelar_belakang }}</td>
			</tr>
		</table>	
	</div>
</div>