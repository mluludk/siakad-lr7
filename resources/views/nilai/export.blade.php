<!DOCTYPE html>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<style>
			th{
			font-size:16;
			}
			td{
			font-size:12;
			}
			table, th, td{
			border: 1px solid #000;
			}
			.red{
			background-color:#e6b8b7;
			}
			.red2{
			background-color:#da9694;
			}
			.red3{
			background-color:#963634;
			}
		</style>
	</head>
	<body>
		@if(count($nilai) < 1)
		<div class="callout callout-danger">
			<h4>Kesalahan</h4>
			Belum ada mahasiswa yang terdaftar pada Mata Kuliah ini. Harap hubungi Administrator / Bagian Akademik
		</div>
		@else
		<?php $n = 1; ?>
		<table>
			<thead>
				<tr>
					<th>Matakuliah & Semester</th>
					<td>{{ $data -> matkul }} ({{ $data -> kd }}) ({{ $data -> semester }})</td>
					<th>Dosen</th>
					<td>{{ $data -> dosen }}</td>
				</tr>
				<tr>
					<th>Program & Kelas</th><td>{{ $data -> program }} @if(isset($data -> kelas)) ({{ $data -> kelas }})@endif</td>
					<th>PRODI</th><td>{{ $data -> prodi }} ({{ $data -> singkatan }})</td>
				</tr>
				<tr>
					<th>Jadwal & Ruang</th><td>@if(isset($data -> hari)){{ config('custom.hari')[$data -> hari] }}, {{ $data -> jam_mulai }} - {{ $data -> jam_selesai }} ({{ $data -> ruang }})@else<span class="text-muted">Belum ada jadwal</span>@endif</td>
					<th>Tahun Akademik</th><td>{{ $data -> ta }}</td>
				</tr>
				<tr>
					<th>Jumlah Mahasiswa</th>
					<td>{{ count($nilai) }}</td>
					<th></th><td></td>
				</tr>
				<tr>
					<th></th>
					<td></td>
					<th></th>
					<td></td>
				</tr>
				<tr>
					<th>No.</th>
					<th>NIM</th>
					<th>Nama</th>
					<th>Nilai Akhir</th>
				</tr>
			</thead>
			<tbody>
				@foreach($nilai as $nl)
				<tr>
					<td>{{ $n }}</td>
					<td>{{ $nl -> NIM }}</td>
					<td>{{ $nl -> nama }}</td>
					<td>{{ $nl -> nilai }}</td>
				</tr>
				<?php $n++; ?>
				@endforeach
			</tbody>
		</table>
		@endif
		<script>
			// window.print();
		</script>
	</body>
</html>