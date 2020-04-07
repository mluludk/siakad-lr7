<!doctype HTML>
<html>
	<head>
		<title>Form Nilai</title>
		<style>
			body{
			padding: 10px;
			width: 21cm;
			height: 35.56cm;
			font-family: tahoma;
			}
			h3{
			text-align: center;
			}
			table{
			font-size: 12px;
			width: 100%;
			}
			th{
			text-align: left;
			font-size: 13px;
			}
			table#nilai{
			border-collapse: collapse;
			}
			table#nilai th{
			text-align: center;
			}
			table#nilai td, table#nilai th{
			padding: 5px 3px;
			border: 1px solid black;
			}
		</style>
	</head>
	<body>
		<h3>Form Nilai</h3>
		<table width="100%">
			<tr>
				<th width="20%">Matakuliah & Semester</th><th width="2%">:</th><td width="30%">{{ $data -> matkul }} ({{ $data -> kd }}) ({{ $data -> semester }})</td>
				<th width="20%">Dosen</th><th width="2%">:</th><td>{{ $data -> gelar_depan }} {{ $data -> nama }} {{ $data -> gelar_belakang }}</td>
			</tr>
			<tr>
				<th>Program & Kelas</th><th>:</th><td>{{ $data -> program }} @if(isset($data -> kelas)) ({{ $data -> kelas }})@endif</td>
				<th>Prodi</th><th>:</th><td>{{ $data -> prodi }} ({{ $data -> singkatan }})</td>
			</tr>
			<tr>
				<th>Jadwal & Ruang</th><th>:</th><td>@if(isset($data -> hari)){{ config('custom.hari')[$data -> hari] }}, {{ $data -> jam_mulai }} - {{ $data -> jam_selesai }} ({{ $data -> ruang }})@else<span class="text-muted">Belum ada jadwal</span>@endif</td>
				<th>Tahun Akademik</th><th>:</th><td>{{ $data -> ta }}</td>
			</tr>
			<tr>
				<th>Jumlah Mahasiswa</th><th>:</th><td>{{ count($jenis_nilai) }}</td>
				<th></th><th></th><td></td>
			</tr>
		</table>
		<?php $n = 1; ?>
		<table id="nilai">
			<thead>
				<tr style="background-image: -webkit-gradient(linear,0 top,0 bottom,from(#3A8341),to(#609C40)); background-image: -moz-linear-gradient(#3A8341,#054a10);color: #fff;">
					<th width="20px" rowspan="2">No.</th>
					<th rowspan="2">NIM</th>
					<th rowspan="2">Nama</th>
					<?php
						$sid = current(array_keys($jenis_nilai));
						$gtype = current(array_keys($jenis_nilai[$sid]));
					?>
					<th colspan="{{ count($komponen)}}">Komponen Penilaian</th>
					<th rowspan="2">Keterangan</th>					
				</tr>
				<tr>
					@foreach($komponen as $k => $v)
					<th>{{ $v }}</th>
					@endforeach				
				</tr>
			</thead>
			<tbody>
				@foreach($jenis_nilai as $mahasiswa)
				<?php
				if(!isset($mahasiswa[$gtype])) continue;
				?>
				<tr>
					<td>{{ $n }}</td>
					<td>{{ $mahasiswa[$gtype]['nim'] }}</td>
					<td>{{ $mahasiswa[$gtype]['nama']}}</td>
					@foreach($komponen as $k => $v)
					<td align="center">@if(isset($mahasiswa[$k])) @if($mahasiswa[$k]['nilai'] > 0) {{ $mahasiswa[$k]['huruf'] }} @endif @endif</td>
					@endforeach			
					<td></td>
				</tr>
				<?php $n++; ?>
				@endforeach
			</tbody>
		</table>	
		<br/>
		<table>
			<tr>
				<td width="70%">
					<br/>
					Dosen Pengampu<br/>
					@if($data -> ttd != '')
					<img src="{{ url('/getimage/' . $data -> ttd) }}" style="display: block;max-width: 200px;"/>
					@else
					<br/>
					<br/>
					<br/>
					<br/>	
					@endif
					{{ $data -> gelar_depan }} {{ $data -> nama }} {{ $data -> gelar_belakang }}
				</td>
				<td>
					Malang, {{ formatTanggal(date('Y-m-d')) }}<br/>
					Ketua PRODI {{ $data -> singkatan }}<br/>
					@if($data -> k_ttd != '')
					<img src="{{ url('/getimage/' . $data -> k_ttd) }}" style="display: block;max-width: 200px;"/>
					@else
					<br/>
					<br/>
					<br/>
					<br/>	
					@endif
					<strong>{{ $data -> k_gelar_depan }} {{ $data -> k_nama }} {{ $data -> k_gelar_belakang }}</strong>
				</td>
			</tr>
		</table>	
		<script>
			window.print();
		</script>
	</body>
</html>													