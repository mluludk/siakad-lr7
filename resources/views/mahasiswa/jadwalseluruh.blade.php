
<h2>Jadwal Perkuliahan Seluruh Prodi</h2>

<?php $c = 1; ?>
<table class="table table-bordered table-hover">
	<thead>
		<tr style="background-image: -webkit-gradient(linear,0 top,0 bottom,from(#3A8341),to(#609C40)); background-image: -moz-linear-gradient(#3A8341,#054a10);color: #fff;">
			<th>No.</th>
			<th>Prodi</th>
			<th>Mata Kuliah</th>
			<th>Dosen</th>
			<th>Semester</th>
			<th>Kelas</th>
			<th>Jadwal</th>
			<th>Ruang</th>
		</tr>
	</thead>
	<tbody>
		@if(!$data -> count())
		<td colspan="8" align="center">Belum ada data</td>
		@else
		@foreach($data as $mk)
		<tr>
			<td>{{ $c }}</td>
			<td>{{ $mk -> prodi }}</td>
			<td>{{ $mk -> matkul }} ({{ $mk -> kd }})</td>
			<td>{{ $mk -> dosen }}</td>
			<td>{{ $mk -> semester }}</td>
			<td>{{ $mk -> kelas }}</td>
			<td>{{ config('custom.hari')[$mk -> hari] }}, {{ $mk -> jam_mulai }} - {{ $mk -> jam_selesai }}</td>
			<td>{{ $mk -> ruang }}</td>
		</tr>
		<?php $c++; ?>
		@endforeach
		@endif
	</tbody>
</table>																		