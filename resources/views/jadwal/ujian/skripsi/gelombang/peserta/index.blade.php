@extends('app')

@section('title')
Peserta {{ $gelombang -> ujian -> nama }} {{ $gelombang -> nama }}
@endsection

@section('header')
<section class="content-header">
	<h1>
		Peserta {{ $gelombang -> ujian -> nama }} {{ $gelombang -> nama }}
		<small>Daftar Peserta</small>
	</h1>		
	<ol class="breadcrumb">
		<li><a href="{{ url('/') }} "><i class="fa fa-dashboard"></i> Home</a></li>
		<li><a href="{{ route('jadwal.ujian.skripsi.index', $j) }}"> Jadwal Ujian {{ $j }} Skripsi</a></li>
		<li class="active">Peserta {{ $gelombang -> ujian -> nama }} {{ $gelombang -> nama }}</li>
	</ol>
</section>
@endsection

@push('styles')
<style>
	th{
	text-align: center;
	vertical-align: middle !important;
	}
	.date{
	width: 80px;
	}
	.time{
	width: 50px;
	}
</style>
@endpush

@section('content')
<div class="box box-info">
	<div class="box-header with-border">
		<h3 class="box-title">Peserta Ujian {{ $j }} Skripsi {{ $gelombang -> nama }}</h3>
		<div class="box-tools">
			<a href="{{ route('jadwal.ujian.skripsi.gelombang.peserta.print', [$j, $gelombang -> id]) }}" 
			class="btn btn-success btn-xs btn-flat" title="Cetak Data Peserta" target="_blank"><i class="fa fa-print"></i> Cetak Data Peserta</a>			
		</div>
	</div>
	<div class="box-body">
		@if(!$peserta -> count())
		<p class="text-muted">Belum ada data</p>
		@else
		<?php $c = 1; ?>
		<table class="table table-bordered table-striped">
			<thead>
				<tr style="background-image: -webkit-gradient(linear,0 top,0 bottom,from(#3A8341),to(#609C40)); background-image: -moz-linear-gradient(#3A8341,#054a10);color: #fff;">
					<th>NO</th>
					<th>RUANG</th>
					<th width="85px">TANGGAL</th>
					<th width="100px">WAKTU</th>
					<th>NAMA MAHASISWA</th>
					<th>NIM/NIRM</th>
					<th>JUDUL @if($j == 'proposal') PROPOSAL @endif SKRIPSI</th>
					<th>PENGUJI UTAMA</th>
					<th>KETUA PENGUJI</th>
					<th>SEKRETARIS</th>
					<th></th>
				</tr>
			</thead>
			<tbody>
				@foreach($peserta as $g)
				<tr>
					<td>{{ $c }}</td>
					<td>
						<?php
							$ruang_class='';
							if($g -> ruang !== null)$ruang_class=' hidden';								
						?>
						<a href="javascript:false;" class="edit-a" id="ruang-{{ $g -> jusg_id }}-{{ $g -> mahasiswa_id }}">{{ $g -> ruang ?? '' }}</a>	
						{!! Form::select('ruang', $ruang, null, ['class' => 'edit-select' . $ruang_class, 'id' => 'select-ruang-' . $g -> jusg_id .'-' . $g -> mahasiswa_id]) !!}						
					</td>
					<td>
						<?php
							$tanggal_class='';
							if($g -> tanggal !== null && $g -> tanggal !== '') $tanggal_class=' hidden';
						?>
						<a href="javascript:false;" class="edit-a-t" id="tanggal-{{ $g -> jusg_id }}-{{ $g -> mahasiswa_id }}">{{ $g -> tanggal ?? '' }}</a>	
						{!! Form::text('tanggal', null, ['class' => 'edit-text date' . $tanggal_class, 'id' => 'text-tanggal-' . $g -> jusg_id .'-' . $g -> mahasiswa_id, 'placeholder' => 'Tanggal', 'autocomplete' => 'off']) !!}
					</td>
					<td>
						<?php
							$jm_class='';
							if($g -> jam_mulai !== null && $g -> jam_mulai !== '') $jm_class=' hidden';
						?>
						<a href="javascript:false;" class="edit-a-t" id="mulai-{{ $g -> jusg_id }}-{{ $g -> mahasiswa_id }}">{{ $g -> jam_mulai ?? '' }}</a>	
						{!! Form::text('jam_mulai', null, array('class' => 'edit-text time' . $jm_class, 'id' => 'text-mulai-' . $g -> jusg_id .'-' . $g -> mahasiswa_id, 'placeholder' => 'Mulai', 'autocomplete' => 'off')) !!}
						- 
						<?php
							$js_class='';
							if($g -> jam_selesai !== null && $g -> jam_selesai !== '') $js_class=' hidden';
						?>
						<a href="javascript:false;" class="edit-a-t" id="selesai-{{ $g -> jusg_id }}-{{ $g -> mahasiswa_id }}">{{ $g -> jam_selesai ?? '' }}</a>	
						{!! Form::text('jam_selesai', null, array('class' => 'edit-text time' . $js_class, 'id' => 'text-selesai-' . $g -> jusg_id .'-' . $g -> mahasiswa_id, 'placeholder' => 'Selesai', 'autocomplete' => 'off')) !!}
						
					</td>
					<td>{{ $g -> nama }}</td>
					<td>{{ $g -> NIM }}</td>
					<td>{{ strtoupper($g -> judul) }}</td>
					<td>						
						<?php
							$p_class='';
							if($g -> p_nama !== null) $p_class=' hidden';								
						?>
						<a href="javascript:false;" class="edit-a" id="penguji-{{ $g -> jusg_id }}-{{ $g -> mahasiswa_id }}">
							{{ $g -> p_gd ?? '' }} {{ $g -> p_nama ?? '' }} {{ $g -> p_gb ?? '' }}
						</a>	
						{!! Form::select('dosen', $dosen, null, ['class' => 'edit-select' . $p_class, 'id' => 'select-penguji-' . $g -> jusg_id .'-' . $g -> mahasiswa_id]) !!}						
					</td>
					<td>{{ $g -> k_gd }} {{ $g -> k_nama }} {{ $g -> k_gb }}</td>
					<td>	
						<?php
							$s_class='';
							if($g -> s_nama !== null) $s_class=' hidden';								
						?>
						<a href="javascript:false;" class="edit-a" id="sekretaris-{{ $g -> jusg_id }}-{{ $g -> mahasiswa_id }}">
							{{ $g -> s_gd ?? '' }} {{ $g -> s_nama ?? '' }} {{ $g -> s_gb ?? '' }}
						</a>	
						{!! Form::select('dosen', $dosen, null, ['class' => 'edit-select' . $s_class, 'id' => 'select-sekretaris-' . $g -> jusg_id .'-' . $g -> mahasiswa_id]) !!}						
					</td>
					<td>						
						<a href="{{ route('jadwal.ujian.skripsi.gelombang.peserta.delete', [$j, $g -> jusg_id, $g -> mahasiswa_id]) }}" class="btn btn-danger btn-xs btn-flat has-confirmation"><i class="fa fa-times"></i> Tolak</a>						
					</td>
				</tr>
				<?php $c++; ?>
				@endforeach
			</tbody>
		</table>
		@endif
	</div>
</div>
@endsection	

@push('scripts')
<script src="{{ asset('/js/datepicker.min.js') }}"></script>
<script src="{{ asset('/js/jquery-clock-timepicker.min.js') }}"></script>
<script src="{{ asset('/js/chosen.jquery.min.js') }}"></script>
<script>
	var loader = '<i class="fa fa-spinner fa-spin loader"></i>';
	$(document).on('click', '.edit-a', function(e){
	e.preventDefault();
	var me = $(this);
	$('#select-' + me.attr('id')).removeClass('hidden');
	me.addClass('hidden');
	});
	
	$(document).on('click', '.edit-a-t', function(e){
	e.preventDefault();
	var me = $(this);
	$('#text-' + me.attr('id')).removeClass('hidden');
	me.addClass('hidden');
	});
	
	$(document).on('change', '.edit-select', function(){
	var me = $(this);
	var id = me.attr('id').split('-');
	var val = me.val();
	var txt = $("#" + me.attr('id') + " option:selected" ).text();
	
	if($('#tanggal-' + id[2] + '-' + id[3]).text() == ''
	|| $('#mulai-' + id[2] + '-' + id[3]).text() == ''
	|| $('#selesai-' + id[2] + '-' + id[3]).text() == '') {alert('Tanggal dan Waktu harus diisi terlebih dahulu. Data belum tersimpan'); return;}
	
	send(id[1], id[2], id[3], val, txt, 'select');
	});
	
	$(document).on('change', '.edit-text', function(){
	var me = $(this);
	var id = me.attr('id').split('-');
	var val = me.val();
	
	send(id[1], id[2], id[3], val, val, 'text');
	});
	
	function send(t, jid, mid, val, txt, inp)
	{
	$.ajax({
	url: '{{ url("/ujian/skripsi/peserta/edit") }}',
	type: "post",
	data: {
	'tipe': t,
	'jusg_id': jid,
	'mahasiswa_id': mid,
	'value': val,
	'_token': '{{ csrf_token() }}'
	},
	success: function(data){
	if(data.success)
	{
	$('#' + inp + '-' + t + '-' + jid + '-' + mid).addClass('hidden');
	$('#' + t + '-' + jid + '-' + mid).text(txt);
	$('#' + t + '-' + jid + '-' + mid).removeClass('hidden');
	}
	else
	{
	alert(data.message);
	}
	$('.loader').remove();
	},
	beforeSend: function()
	{
	$('#' + inp + '-' + t + '-' + jid + '-' + mid).after(loader);
	}
	}); 	
	}
	
	$(function(){
	$(".time").clockTimePicker();
	
	$(".date").datepicker({
	format:"dd-mm-yyyy", 
	autoHide:true,
	daysMin: ['Mg', 'Sn', 'Sl', 'Rb', 'Km', 'Jm', 'Sa'],
	monthsShort: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'],
});
$(".chosen-select").chosen({
	no_results_text: "Tidak ditemukan hasil pencarian untuk: "
	});
	});
</script>
@endpush

@push('styles')
<link rel="stylesheet" href="{{ asset('/css/datepicker.min.css') }}">
<link rel="stylesheet" href="{{ asset('/css/chosen.min.css') }}">
<style>
	.chosen-container{
	font-size: inherit;
	}
	.chosen-single{
	padding: 6px 10px !important;
	box-shadow: none !important;
	border-color: #d2d6de !important;
	background: white !important;
	height: 34px !important;
	border-radius: 0px !important;
	}
	.chosen-drop{
	border-color: #d2d6de !important;	
	box-shadow: none;
	}
</style>
@endpush		