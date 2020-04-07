<!doctype HTML>
<html>
	<head>
		<title>Kurikulum</title>
		<style>
			table{
			border-collapse:collapse;
			}
			td{
			border: 1px solid black;
			}
		</style>
	</head>
	<body>
		<table>
			<thead>
				<tr style="background-image: -webkit-gradient(linear,0 top,0 bottom,from(#3A8341),to(#609C40)); background-image: -moz-linear-gradient(#3A8341,#054a10);color: #fff;">
					<td>Kode MK</td>
					<td>Nama MK</td>
					<td>Jenjang Pendidikan</td>
					<td>Jenis MK</td>
					<td>sks_tm</td>
					<td>sks_prak</td>
					<td>sks_prak_lap</td>
					<td>sks_sim</td>
					<td>a_sap</td>
					<td>a_silabus</td>
					<td>a_bahan_ajar</td>
					<td>acara_praka_diktat</td>
					<td>Semester</td>
					<td>Kode Prodi</td>
				</tr>
			</thead>
			<tbody>
				@foreach($rdata as $m)
				<tr>
					<td>{{ $m -> kode }}</td>
					<td>{{ $m -> nama }}</td>
					<td>30</td>
					<td>{{ $m -> kelompok }}</td>
					<td>{{ $m -> sks_tm }}</td>
					<td>{{ $m -> sks_prak }}</td>
					<td>{{ $m -> sks_prak_lap }}</td>
					<td>{{ $m -> sks_sim }}</td>
					<td>@if($m -> sap == 'y') 1 @else 0 @endif</td>
					<td>@if($m -> silabus == 'y') 1 @else 0 @endif</td>
					<td>@if($m -> bahan_ajar == 'y') 1 @else 0 @endif</td>
					<td>@if($m -> praktek == 'y') 1 @else 0 @endif</td>
					<td>{{ $m ->  semester}}</td>
					<td>{{ $m -> kode_dikti }}</td>
				</tr>
				@endforeach
			</tbody>
		</table>
	</body>
</html>											