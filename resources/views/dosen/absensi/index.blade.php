@extends('app')

@section('title')
Absensi Dosen
@endsection

@section('header')
<section class="content-header">
	<h1>
		Dosen
		<small>Absensi</small>
	</h1>
	<ol class="breadcrumb">
		<li><a href="/"><i class="fa fa-dashboard"></i> Home</a></li>
		<li><a href="{{ url('/dosen') }}"> Dosen</a></li>
		<li class="active">Absensi</li>
	</ol>
</section>
@endsection

@push('scripts')
<script>
	$(document).ready(function(){
		$('[data-toggle="tooltip"]').tooltip(); 
	});
	@if(!$public)
	$(document).on('click', '.status', function(){
		var me = $(this);
		var date = me.attr('date');
		date = date.split('-');
		var id = me.siblings('.nama').attr('id');
		var sta = me.children('div').html();
		if(sta == '') sta = 'H';
		window.location.href='/dosen/absensi/create/' + date[0] + '/' + date[1] + '/' + date[2] + '/' + id + '/' + sta;
	});
	@endif
</script>
@endpush

@push('styles')
<style>
	table{
	}
	thead td, tbody td:not(.nama){
	font-size: 9px;
	text-align: center !important;
	padding: 3px 0px !important;
	}
	tbody td{
	font-size: 14px !important;
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
	.status{
	cursor: pointer;
	}
	.status > div{
	
	}
</style>
@endpush

@section('content')
<div class="box box-primary">
	<div class="box-header with-border">
		<h3 class="box-title">Absensi Dosen Bulan {{ config('custom.bulan')[$month] }} {{ $year }}</h3>
		<div class="box-tools">
			<a href="{{ route('dosen.absensi.index') }}/{{ $lastmonth }}" class="btn btn-default btn-xs btn-flat" title="Absensi bulan sebelumnya"><i class="fa fa-chevron-left"></i></a>
			<a href="{{ route('dosen.absensi.index') }}/{{ $nextmonth }}" class="btn btn-default btn-xs btn-flat" title="Absensi bulan setelahnya"><i class="fa fa-chevron-right"></i></a>
			@if(!$public)<a href="{{ route('dosen.absensi.create') }}" class="btn btn-info btn-xs btn-flat" title="Input Absensi Dosen"><i class="fa fa-plus"></i></a>@endif
		</div>
	</div>
	<div class="box-body">		
		@if(count($absensi) < 1)
		<p>Belum ada data</p>
		@else
		<?php $c=1; ?>
		<table class="table table-bordered table-striped">
			<thead>
				<tr>
					<td class="normal align-left">Nama / Tanggal</td>
					@for($c = 1; $c <= $days; $c++)
					<td>{{ str_pad($c, 2, "0", STR_PAD_LEFT) }}</td>
					@endfor
					<td class="normal" width="100px;">Total jam</td>
				</tr>
			</thead>
			<tbody>
				@foreach($absensi as $d => $v)
				<tr>
					<td class="nama" id="{{ $d }}">{{ $v['nama'] }}</td>
					<?php
						$jam_total = 0;
						for($c = 1; $c <= $days; $c++)
						{
							$current = str_pad($c, 2, "0", STR_PAD_LEFT) . '-' . $month . '-' . $year;
							$jam = $status = $class = $tooltip = '';
							if(isset($v['absensi'][$current]))
							{
								foreach($v['absensi'][$current] as $jp)
								{
									$jam += intval($jp['jam']);
									$tooltip .= $jp['matkul'] . ' ' . $jp['kelas'] . ' ' . $jp['jam'] . ' jam<br/>';
									$status = $jp['status'];
									switch($status)
									{
										case 'H':
										$class= 'success';
										break;
										
										case 'S':
										$class= 'info';
										break;
										
										case 'I':
										$class= 'warning';
										break;
										
										case 'A':
										$class= 'danger';
										break;
									}
									
								}
							}
							echo '<td class="status ' . $class . '" date="'. $current .'" >';
							if($jam > 0)
							{
							echo '<div data-toggle="tooltip" data-html="true" title="'. $tooltip .'">' . $status . '</div>';
							}
							else
							{
								echo '<div>' . $status . '</div>';
							}
							echo '</td>';
							$jam_total += $jam;
						}
					?>
					<td>{{ $jam_total }}</td>
				</tr>
				@endforeach
			</tbody>
		</table>
	</div>
</div>
@endif
@endsection																	