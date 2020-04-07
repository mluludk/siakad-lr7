<!doctype HTML>
<html>
	<head>
		<title>Rincian Biaya Mahasiswa</title>
		<script>
			table{
				border-collapse: collapse;
			}
			th, td{
				border: 1px solid black;
			}
		</script>
	</head>
	<body>
		<?php $c = 1; ?>
		<table>
			<tr>
				<th colspan="{{ (count($rdata['setup']) + 2) }}" style="background-color: #00c0ef; color: #ffffff; text-align: center;">Rincian Biaya Pendidikan Mahasiswa {{ $rdata['title'] }}</th>
			</tr>
			<tr style="background-image: -webkit-gradient(linear,0 top,0 bottom,from(#3A8341),to(#609C40)); background-image: -moz-linear-gradient(#3A8341,#054a10);color: #fff;">
				<th>No</th>
				<th style="text-align: center;">Nama</th>
				@foreach($rdata['setup'] as $s)
				<th style="text-align: center;">{{ $s -> nama }}</th>
					<?php 
						$s2[] = $s -> id;
					?>
				@endforeach
			</tr>
			<tr style="background-color: #ffff00;">
				<th colspan="2" style="text-align: center;">Tanggungan</th>
				@foreach($rdata['setup'] as $s)
				<td>{{ $s -> tanggungan }}</td>
				@endforeach
			</tr>
			@if(!count($rdata['rincian']))
				<tr>
					<td colspan="{{ (count($rdata['setup']) + 2) }}" class="ac">Belum ada data</td>
				</tr>
				@else
			@foreach($rdata['rincian'] as $k => $v)
			<?php
				$id = explode('-', $k);
			?>
			<tr>
				<td>{{ $c }}</td>
				<td>{{ $id[1] }}</td>
					@foreach($s2 as $s3)
				<td>@if(isset($v[$s3])){{ $v[$s3] }}@endif</td>
			@endforeach
			</tr>
			<?php $c++; ?>
			@endforeach		
				@endif	
		</table>
	</body>
</html>									