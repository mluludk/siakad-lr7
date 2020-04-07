@extends('app')

@section('title')
Rekap nilai kelas {{ $info['class'] }} {{ $info['name'] }}
@endsection

@push('scripts')
<script>
	/*
		$('.grade_div').on('click', function(){
		if($(this).children('.grade').length < 1)
		{
		$('.grade').remove();
		var grade = $(this).text().trim();
		$(this).html('<input type="text" class="form-control grade" value="'+grade+'"/>');
		}
		});
		$(document).on('keydown', '.grade', function(e){
		if(e.which == 13)
		{
		var sid = $(this).closest('.grade_div').attr('sid');
		var cid = $(this).closest('.grade_div').attr('cid');
		alert('cid:' + cid + ', sid: ' + sid);return;
		$.ajax({
		type: "POST",
		data: {
		_token: "{{ csrf_token() }}",
		student_id: id,
		grade: $(this).val()
		},
		url: 'http://sim.local.net/lessons/'+ cid +'/grades/create',
		dataType: 'json',
		success: function(response) {
		if(response.success) $('#'+id).html(response.grade);
		}
		});	
		}
		});
	*/
</script>
@endpush

@push('styles')
<style>
	.red{
	color:#e04343;
	}
	.grade{
	width:50px;
	}
</style>
@endpush

@section('content')
<h2 class="hidden-print">Rekap nilai {{ $info['class'] }} tahun ajaran {{ $info['name'] }}</h2>
<h4 class="visible-print" style="text-align:center;">Rekap nilai {{ $info['class'] }} tahun ajaran {{ $info['name'] }}</h4>
@if(!isset($data))
<div class="alert alert-danger">
	<strong>Error!</strong> Nilai tidak ditemukan
</div>
@else
<?php $n = 0; $c = 0; $first = array_values($data)[0]; $ncourses = count($first['courses']['name'] ); ?>
<table class="table table-bordered table-hover">
	<thead>
		<tr>
			<th>No.</th>
			<th>Nama</th>
			@foreach($first['courses']['name'] as $cn)
			<th>
				<a href="{{ route('lessons.grades', $first['courses']['id'][$c]) }}" class="hidden-print">{{ $cn }}</a>
				<span class="visible-print">{{ $cn }}</span>
			</th>
			<?php $c++; ?>
			@endforeach
			<!--th>Jumlah</th-->
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
		// dd($d);
			$total_data = count($data);
			// $class = '';
			$n++; 
			$trophy = '';
			if(intval($d['rank']) > $total_data - 3) 
			{
				// $class=' class="danger"';
				$trophy='<i class="fa fa-times hidden-print"></i>&nbsp;';
			}
			if(intval($d['rank']) <= 3) 
			{
				// $class=' class="success"';
				$trophy='<i class="fa fa-trophy hidden-print"></i>&nbsp;';
			}
		?>
		<tr>
			<td>{{ $n }}</td>
			<td>
				<a href="{{ route('students.show', $d['student']['register']) }}" class="hidden-print">{{ $d['student']['name'] }}</a>
				<span class="visible-print">{{ $d['student']['name'] }}</span>
				{!! $trophy !!}
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
			<td>@if($att != null) {{ $att[$d['id']]['s'] }}@endif</td>
			<td>@if($att != null) {{ $att[$d['id']]['i'] }}@endif</td>
			<td>@if($att != null) {{ $att[$d['id']]['a'] }}@endif</td>
			<td>{{ $d['rank'] }}</td>
		</tr>
		@endforeach
	</tbody>
</table>
<a href="{{ route('classes.recap') }}?class={{ $info['class_id'] }}&year={{ $info['id'] }}&to=excel" class="btn btn-success" title="Simpan ke Excel"><i class="fa fa-file-excel-o fa-lg"></i></a>
@endif
@endsection						