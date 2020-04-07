<html>
	<head>
		<title>Penelitian Dosen</title>
	</head>
	<body>
		@if(count($rdata) < 1) 
		Data tidak ditemukan
		@else
		<table>
			<thead>
				<tr>
					<tr>
						<td>Tahun</td>
						<td>Judul Penelitian</td>
						<td>Jenis Penelitian</td>
						<td>Nama Peneliti/Ketua Kelompok</td>
						<td>Mandiri</td>
						<td>Lembaga</td>
						<td>Hibah Nasional</td>
						<td>Hibah Internasional</td>
					</tr>
				</thead>
				<tbody>
					@foreach($rdata as $d)
					<tr>
						<td>{{ $d -> tahun }}</td>
						<td>{{ str_replace("'", '`', $d -> judul) }}</td>
						<td>{{ $d -> jenis }}</td>
						<td>
							@if($d -> ketua_penelitian == ''){{ str_replace("'", '`', $d -> dosen) }}@else{{ str_replace("'", '`', $d -> ketua_penelitian) }}@endif
						</td>
						<td>
							@if(intval($d -> dana_pribadi)){{ intval($d -> dana_pribadi) }}@endif
						</td>
						<td>
							@if(intval($d -> dana_lembaga)){{ intval($d -> dana_lembaga) }}@endif
						</td>
						<td>
							@if(intval($d -> dana_hibah_nasional)){{ intval($d -> dana_hibah_nasional) }}@endif
						</td>
						<td>
							@if(intval($d -> dana_hibah_internasional)){{ intval($d -> dana_hibah_internasional) }}@endif
						</td>
					</tr>
					@endforeach
				</tbody>
			</table>
			@endif
		</body>
	</html>													