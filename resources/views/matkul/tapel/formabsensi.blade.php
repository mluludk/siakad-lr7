<!doctype HTML>
<html>
	<head>
		<title>Form Absensi</title>
		<style>
			body{
			padding: 10px;
			width: 21cm;
			height: 35.56cm;
			font-family: Tahoma;
			}
			table{
			font-size: 13px;
			width: 100%;
			margin-top: 20px;
			}
			table.absensi{
			font-size: 10px;
			border: 1px solid black;
			border-collapse: collapse;
			}
			table.absensi th, table.absensi td{
			border: 1px solid black;
			}
			table.absensi thead td{
			text-align: center;
			padding: 3px 2px !important;
			}
			table.absensi tbody td{
			padding: 7px 3px;
			}
			.normal{
			font-size: 14px;
			padding: 3px 8px !important;
			}
			.align-right{
			text-align: right !important;
			}
			.align-left{
			text-align: left !important;
			}
			.align-center{
			text-align: center !important;
			}
			h2, h3{
			text-align: center; font-size: 15px;margin: 0px;
			}			
			h2{
			text-transform: uppercase; 
			}
			.top{
			height: 50px;
			}
			.tot{
			width: 20px;
			}
			ol{
			margin-top: 5px;
			padding-left: 15px;
			}
		</style>
	</head>
	<body>
		<h2>Presensi Mahasiswa</h2>
		<h2>{{ config('custom.profil.abbr') }} {{ config('custom.profil.name') }}</h2>
		<h3>Tahun Akademik {{ $data -> ta }}</h3>
		<table>
			<tr>
				<td width="100px">Mata Kuliah</td>
				<td width="10px">:</td>
				<td>{{ $data -> matkul }} ({{ $data -> kode }})</td>
			</tr>
			<tr>
				<td>Semester</td>
				<td>:</td>
				<td>{{ arabicToRoman($data -> semester) }}</td>
			</tr>
			<tr>
				<td>PRODI</td>
				<td>:</td>
				<td>{{ $data -> prodi }} ({{ $data -> program }})</td>
			</tr>
			<tr>
				<td>Pengampu</td>
				<td>:</td>
				<td>{{ $data -> gelar_depan }} {{ $data -> nama }} {{ $data -> gelar_belakang }}</td>
			</tr>
		</table>
		<?php $n=1; $cols=16;?>
		<table class="absensi">
			<thead>
				<tr>
					<th class="normal align-left" width="20px" rowspan="2">No.</th>
					<th class="normal align-center" rowspan="2" width="80px" >NIM</th>
					<th class="normal align-center" rowspan="2" width="130px" >Nama</th>
					<th colspan="{{ $cols }}">Tanggal Pertemuan</th>
					<!--th colspan="3">Jumlah</th-->
				</tr>
				<tr>
					@for($c = 1; $c <= $cols; $c++)
					<th class="top">&nbsp;&nbsp;&nbsp;</th>
					@endfor
					<!--th class="tot">S</th>
						<th class="tot">I</th>
					<th class="tot">A</th-->
				</tr>
			</thead>
			<tbody>
				@foreach($anggota as $mhs)
				<tr>
					<td>{{ $n }}</td>
					<td>{{ $mhs -> NIM }}</td>
					<td>{{ $mhs -> nama }}</td>
					@for($c = 1; $c <= $cols; $c++)
					<td></td>
					@endfor
					<!--td></td>
						<td></td>
					<td></td-->
				</tr>
				<?php $n++; ?>
				@endforeach
			</tbody>
		</table>		
		<table>
			<tr>
				<td width="70%" valign="top">
					<strong>Catatan:</strong>
					<ol>
						<li>Dilarang menambahkan nama Mahasiswa secara manual di absensi.</li>
						<li>Mahasiswa yang belum her-registrasi dan melakukan KRS tidak akan tercantum di absen.</li>
						<li>Bagi mahasiswa yang namanya belum tercantum di absensi segera lapor KAPRODI.</li>
					</ol>
				</td>
				<td>
					{{ config('custom.profil.alamat.kabupaten') }}, .......................... <br/>
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