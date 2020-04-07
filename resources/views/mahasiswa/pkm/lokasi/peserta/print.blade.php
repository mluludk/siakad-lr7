<!DOCTYPE html>
<html>
	<head>
		<title>Daftar Peserta PKM {{ $pkm -> strata }} {{ $pkm -> prodi }} {{ $pkm -> tapel }}</title>
		<style>
			body{
			margin: 10px;
			font-family: "Times New Roman";
			}
			table{
			border-collapse: collapse;
			}
			th, td{
			border: 1px solid black;
			}
		</style>
	</head>
	<body>
		<h3 class="box-title">Daftar Peserta PKM {{ $pkm -> tapel -> nama }}</h3>
		<?php $c=1; ?>
		<table class="table table-bordered">
			<thead>
				<tr>
					<th width="30px">NO</th>
					<th>LOKASI</th>
					<th>DOSEN PENDAMPING</th>
					<th>PRODI</th>
					<th>MATA KULIAH</th>
					<th>NIM</th>
					<th>NAMA</th>
					<th colspan="2">NILAI</th>
				</tr>
			</thead>
			<tbody>
				@if(count($peserta) < 1)
				<tr>
					<td colspan="7" align="center">Belum ada data</td>
				</tr>
				@else
				<?php
					$l1 = false;
				?>
				@foreach($peserta as $lokasi)
				<?php
					if(!isset($rs1))
					{
						$rs1=0;
						foreach($lokasi as $matkul) 
						{
							$rs1 += count($matkul);
						}
					}
					$span1 = $rs1 > 1 ? ' rowspan="' . $rs1 . '"' : '';
				?>
				@foreach($lokasi as $matkul)
				<?php
					$l2 = false;
					$rs2 = count($matkul);
					$span2 = $rs2 > 1 ? ' rowspan="' . $rs2 . '"' : '';
				?>
				@foreach($matkul as $g)
				@if(in_array($user -> role_id, [1,2,8,257]) || ($user -> role_id == 128 && isPendamping($g -> pkm_lokasi_id, $user -> authable_id, $pendamping)))
				<tr>
					<td>{{ $c }}</td>
					@if(!$l1)
					<td {!! $span1 !!}>{{ $g -> lokasi }}</td>
					<td {!! $span1 !!}>{!! formatPendamping($g -> pkm_lokasi_id, $pendamping) !!}</td>
					@endif
					@if(!$l2)
					<td {!! $span2 !!}>
						{{ $g -> strata }} {{ $g -> singkatan }}
					</td>
					<td {!! $span2 !!}>
						{{ $g -> nama_matkul }} ({{ $g -> kode_matkul }})
					</td>
					@endif
					<td>{{ $g -> NIM }}</td>
					<td>{{ $g -> mahasiswa }}</td>
					<td>
						{{ $g -> nilai ?? '' }}
					</td>
					<td>
						{{ $g -> nilai_angka ?? '0' }}
					</td>
				</tr>
				@endif
				<?php
					$c++;
					if(!$l2) $l2 = true;
					if(!$l1) $l1 = true;
				?>
				@endforeach
				@endforeach
				@endforeach
				@endif
			</tbody>
			</table>
			<script>
				window.print();
			</script>
		</body>
	</html>				