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
	<?php $n = 0; $c = 0; $first = array_values($data)[0]; $ncourses = count($first['courses']['name'] ); ?>
		<table border="1">
			<tr>
				<th colspan="{{ (int)7 + $ncourses }}" style="text-align:center">
					Rekap nilai {{ $info['class'] }} tahun ajaran {{ $info['name'] }}
				</th>
			</tr>
			<thead>
				<tr>
					<th>No.</th>
					<th>Nama</th>
					@foreach($first['courses']['name'] as $cn)
					<th>
						{{ $cn }}
					</th>
					<?php $c++; ?>
					@endforeach
					<th>Rata-rata</th>
					<th>S</th>
					<th>I</th>
					<th>A</th>
					<th>Ranking</th>
				</tr>
			</thead>
			<tbody>
				@foreach($data as $k => $d)
				<?php 
					$total_data = count($data);
					$n++; 
					$trophy = '';
					if(intval($d['rank']) > $total_data - 3) 
					{
						
					}
					if(intval($d['rank']) <= 3) 
					{
						
					}
				?>
				<tr>
					<td>{{ $n }}</td>
					<td>
						{{ $d['student']['name'] }}
					</td>
					<?php $i = 0; ?>
					@foreach($d['courses']['grade'] as $grade)
					<td>
						<div class="grade_div" sid="{{ $k }}" cid="{{ $d['courses']['id'][$i] }}">
							@if($grade <= 6)
							<span class="red">{{ $grade }}</span>
							@else
							{{ $grade }}
							@endif
						</div>
					</td>
					<?php $i++; ?>
					@endforeach
					<td>
						{{ number_format($d['total'] / $ncourses, 2) }}
					</td>
					<td>{{ $att[$d['id']]['s'] }}</td>
					<td>{{ $att[$d['id']]['i'] }}</td>
					<td>{{ $att[$d['id']]['a'] }}</td>
					<td>{{ $d['rank'] }}</td>
				</tr>
				@endforeach
			</tbody>
		</table>
	</body>
</html>