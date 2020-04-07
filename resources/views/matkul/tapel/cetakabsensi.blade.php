<!doctype HTML>
<html>
	<head>
		<title>Presensi Mahasiswa</title>
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
			.nama{
			padding: 3px 8px !important;
			width: 300px;
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
			.text-danger{
			color: #DD4B39;
			}
			.text-success{
			color: #00A65A;
			}
			.text-info{
			color: #00C0EF;
			}
			.text-warning{
			color: #F39C12;
			}
			.x{
			text-align: center;
			}
		</style>
	</head>
	<body>
		<h2>Presensi Mahasiswa</h2>
		<h2>{{ config('custom.profil.abbr') }} {{ config('custom.profil.name') }}</h2>
		<h3>Tahun Akademik {{ $mata_kuliah -> ta }}</h3>
		<table>
			<tr>
				<td width="100px">Mata Kuliah</td>
				<td width="10px">:</td>
				<td>{{ $mata_kuliah -> matkul }} ({{ $mata_kuliah -> kode }})</td>
			</tr>
			<tr>
				<td>Semester</td>
				<td>:</td>
				<td>{{ arabicToRoman($mata_kuliah -> semester) }}</td>
			</tr>
			<tr>
				<td>PRODI</td>
				<td>:</td>
				<td>{{ $mata_kuliah -> prodi }} ({{ $mata_kuliah -> program }})</td>
			</tr>
			<tr>
				<td>Dosen Pengampu</td>
				<td>:</td>
				<td>{{ $mata_kuliah -> dosen }}</td>
			</tr>
		</table>
		<?php $c=1; $cols=30;?>
		<table class="absensi">
			<thead>
				<tr>
					<th class="normal align-left" width="20px" rowspan="3">No.</th>
					<th class="normal align-center" rowspan="3">NIM</th>
					<th class="normal align-center" rowspan="3">Nama</th>
					<th colspan="{{ $cols }}" class="middle">Tatap Muka</th>
					<th colspan="3">Jumlah</th>
				</tr>
				<tr>
					@for($j = 0; $j < $cols; $j++)
					<th>@if(isset($jurnals[$j])){{ $jurnals[$j] -> pertemuan_ke }}@endif</th>
					@endfor
					<th class="tot" rowspan="2">S</th>
					<th class="tot" rowspan="2">I</th>
					<th class="tot" rowspan="2">A</th>
				</tr>
				<tr>
					<?php 
						for($j = 0; $j < $cols; $j++)
						{
							if(isset($jurnals[$j]))
							{
								$date = strtotime($jurnals[$j] -> tanggal);
								echo '<td>' . date('d', $date) . '<br/>' . date('m', $date) . '</td>';
							}
							else
							{
								echo '<th>&nbsp;&nbsp;</th>';
							}
						}					
					?>
				</tr>
			</thead>
			<tbody>
				@foreach($data as $id => $d)
				<tr>
					<td>{{ $c }}</td>
					<td>{{ $d['nim'] }}</td>
					<td>{{ $d['nama'] }}</td>
					@for($j = 0; $j < $cols; $j++)
					@if(isset($jurnals[$j]))
					<td class="x">
						<?php
							$status = explode(':', $d['status'][$jurnals[$j] -> id]);
							switch($status[0])
							{
								case '-':
								echo $status[0];
								break;
								
								case 'H':
								echo '&#10003;';
								break;
								
								case 'I':
								echo '<span class="text-warning">I</span>';
								break;
								
								case 'S':
								echo '<span class="text-info">S</span>';
								break;
								
								case 'A':
								echo '<span class="text-danger">A</span>';
								break;		
							}
						?>
					</td>
					@else
					<td></td>
					@endif
					@endfor
					<td></td>
					<td></td>
					<td></td>
				</tr>
				<?php $c++; ?>
				@endforeach
				
			</tbody>
		</table>		
		<table>
			<tr>
				<td width="80%"></td>
				<td>
					{{ config('custom.profil.alamat.kabupaten') }}, .......................... <br/>
					Ketua PRODI {{ $mata_kuliah -> singkatan }}<br/>
					@if($mata_kuliah -> kaprodi -> ttd != '')
					<img src="{{ url('/getimage/' . $mata_kuliah -> kaprodi -> ttd) }}" style="display: block;max-width: 200px;"/>
					@else
					<br/>
					<br/>
					<br/>
					<br/>	
					@endif
					<strong>{{ $mata_kuliah -> kaprodi -> gelar_depan }} {{ $mata_kuliah -> kaprodi -> nama }} {{ $mata_kuliah -> kaprodi -> gelar_belakang }}</strong>
				</td>
			</tr>
		</table>	
		<script>
			window.print();
		</script>
	</body>
</html>														