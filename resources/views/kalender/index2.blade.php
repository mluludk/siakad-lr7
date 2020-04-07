@extends('app')

@section('title')
Kalender Akademik
@endsection

@section('header')
<section class="content-header">
	<h1>
		Akademik
		<small>Kalender Akademik</small>
	</h1>
	<ol class="breadcrumb">
		<li><a href="/"><i class="fa fa-dashboard"></i> Home</a></li>
		<li class="active">Kalender Akademik</li>
	</ol>
</section>
@endsection

@push('styles')
<style>
	table.kalender td, th{
	text-align: center;
	}
	.legend-color{
	display: inline-block; 
	height: 13px;
	width: 30px;
	}
	.kd{
	cursor: default;
	font-size: 9px;
	vertical-align: middle !important;
	}
</style>
@endpush

@if(!$public)
@push('scripts')
<script>
	$(document).on('click', '.btn-save', function(){
		var tid = $(this).attr('id').split('-');
		$.ajax({
			type: "POST",
			url: "{{ url('kalender') }}/" + tid[1],
			data: {
				fg: $('#fg-' + tid[1]).val(),
				bg: $('#bg-' + tid[1]).val(),
				_token: '{{ csrf_token() }}',
				_method: 'PATCH'
			},
			success: function(response){
				alert('Success!');
				$('#sv-' + tid[1]).attr('disabled', true);
			}
		});
	});
	
	$(document).on('change', '.col-input', function(){
		var me = $(this)
		var tid = me.attr('id').split('-');
		if(tid[0] == 'fg') $('.cal-' + tid[1]).css('color', me.val());
		else $('.cal-' + tid[1]).css('background-color', me.val());
		
		$('#sv-' + tid[1]).attr('disabled', false);
	});
	
	$(document).on('change', '.tahun', function(){
		document.location.href = '{{ url('/kalender2') }}' + '?tahun=' + $(this).val();
	});
</script>
@endpush
@endif

@section('content')
<div class="box">
	<div class="box-header with-border">
		<h3 class="box-title pull-left">Kalender Akademik</h3>
		@if(!$public)
		@if(isset($tahun))
		{!! Form::select('tahun', $tahun, $aktif, ['class' => 'form-control pull-left tahun', 'style' => 'display: inline-block !important; width: auto; margin-left: 5px; padding: 1px 5px !important ; height: 24px;']) !!}
		@endif
		<div class="box-tools">
			<a href="{{ route('kalender.index') }}?tahun={{ $aktif }}" class="btn btn-info btn-xs btn-flat" title="Kalender Akademik Tabel"><i class="fa fa-list"></i> Kalender Akademik Tabel</a>
			<a href="{{ route('kalender.create') }}?tahun={{ $aktif }}" class="btn btn-primary btn-xs btn-flat" title="Tambah Kegiatan"><i class="fa fa-plus"></i> Tambah Kegiatan Akademik</a>
		</div>
		@else
		<div class="box-tools">
			<a href="{{ route('kalender.public') }}?tahun={{ $aktif }}" class="btn btn-info btn-xs btn-flat" title="Kalender Akademik Tabel"><i class="fa fa-list"></i> Kalender Akademik Tabel</a>
		</div>
		@endif
	</div>
	<div class="box-body">		
		@if(count($agenda) < 1)
		Belum ada data kegiatan
		@else
		<?php $c = 1; ?>
		<table class="table table-bordered kalender">
			<tr>
				<th>Bulan</th>
				@foreach(range(1, 31) as $tgl)
				<td>{{ $tgl }}</td>
				@endforeach
			</tr>
			@foreach($bulan_hari as $b)
			<?php $a = explode('|', $b);?>
			<tr>
				<td>{{ str_replace('20', '\'', $a[0]) }}</td>
				@for($d = 1; $d <= $a[1]; $d++)
				<?php $cur = $a[2] . '-' . str_pad($d, 2, '0', STR_PAD_LEFT); ?>
				@if(isset($agenda[$cur]))	
				<td class="kd cal-{{ $agenda[$cur]['id'] }}" style="background-color: {{ $agenda[$cur]['bg'] }}; color: {{ $agenda[$cur]['fg'] }}" title="{{ $agenda[$cur]['title'] }}">{{ $agenda[$cur]['kode'] }}</td>
				@else
				<td></td>
				@endif
				@endfor
			</tr>
			@endforeach
		</table>
		@endif
		@if(count($legends) > 1)
		<br/>
		<h4><strong>Keterangan</strong></h4>
		@if($public)
		@foreach($legends as $legend)
		<div><span class="legend-color" style="background-color: {{ $legend['bg'] }};"></span> : {{ $legend['label'] }}</div>
		@endforeach
		@else
		<?php $c = 1; ?>
		<table class="table table-bordered" style="width: 50%">
			<tr style="background-image: -webkit-gradient(linear,0 top,0 bottom,from(#3A8341),to(#609C40)); background-image: -moz-linear-gradient(#3A8341,#054a10);color: #fff;">
				<th>No.</th>
				<th>Kegiatan</th>
				<th>Warna</th>
				<th>Warna Belakang</th>
				<th></th>
			</tr>
			@foreach($legends as $legend)
			<tr>
				<td>{{ $c }}</td>
				<td>{{ $legend['label'] }} @if($legend['kode'] != '')({{ $legend['kode'] }})@endif</td>
				<td><input type="color" id="fg-{{ $legend['id'] }}" class="col-input" value="{{ $legend['fg'] }}"/></td>
				<td><input type="color" id="bg-{{ $legend['id'] }}" class="col-input" value="{{ $legend['bg'] }}"/></td>
				<td><button class="btn btn-primary btn-save btn-xs btn-flat" disabled id="sv-{{ $legend['id'] }}"><i class="fa fa-save"></i> Simpan</button></td>
			</tr>
			<?php $c++; ?>
			@endforeach
		</table>
		@endif
		@endif
	</div>
</div>
@endsection																						