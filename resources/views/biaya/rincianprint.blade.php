<!doctype HTML>
<html>
	<head>
		<title>Rincian Biaya Mahasiswa</title>
		<style>
			body{
			padding: 5px;
			height: 21cm;
			width: 35.56cm;
			font-family: Tahoma;
			}
			table{
			width: 100%;
			font-size: 10px;
			border-collapse: collapse;
			}
			table th{
			
			}
			table td{
			padding: 0px 4px;
			}
			table td, th{
			border: 1px solid black;
			}
			.al{
			text-align: left;
			}
			.ac{
			text-align: center;
			}
			.ar{
			text-align: right;
			}
		</style>
	</head>
	<body>
		<?php $c = 1; ?>
		<table>
			<thead>
				<tr>
					<th colspan="{{ (count($setup) + 2) }}" style="font-size: 14px; text-transform: uppercase; padding: 3px; background-color: #00c0ef; color: #ffffff;">Rincian Biaya Pendidikan Mahasiswa {{ $title }}</th>
				</tr>
				<tr style="background-color: #367fa9; color: #ffffff;">
					<th width="20px">No</th>
					<th>Nama</th>
					@foreach($setup as $s)
					<th>{{ $s -> nama }}</th>
					<?php 
						$s2[] = $s -> id;
					?>
					@endforeach
				</tr>
			</thead>
			<tbody>
				<tr style="background-color: #ffff00;">
					<th colspan="2" class="ac">Tanggungan</th>
					@foreach($setup as $s)
					<td class="ar">{{ formatRupiah($s -> tanggungan) }}</td>
					@endforeach
				</tr>
				@if(!count($rincian))
				<tr>
					<td colspan="{{ (count($setup) + 2) }}" class="ac">Belum ada data</td>
				</tr>
				@else
				@foreach($rincian as $k => $v)
				<?php
					$id = explode('-', $k);
				?>
				<tr>
					<td class="al">{{ $c }}</td>
					<td class="al">{{ $id[1] }}</td>
					@foreach($s2 as $s3)
					<td class="ar">@if(isset($v[$s3])){{ formatRupiah($v[$s3]) }}@endif</td>
					@endforeach
				</tr>
				<?php $c++; ?>
				@endforeach		
				@endif
			</tbody>
		</table>
		<script>
			window.print();
		</script>
	</body>
</html>									