@extends('app')

@section('title')
Nilai Perkuliahan - {{ $data -> matkul }} - {{ $data -> ta }}
@endsection

@push('styles')
<style>
	.form-group{margin-bottom:0px;}
	.form-group > label{text-align:left !important}
	
	.inline{
	display: inline-block;
	width:70px;
	}
	.nilai_akhir{
	padding: 6px;
	}
	.table > thead > tr > th {
	vertical-align: middle;
	text-align: center;
	}
	th label{
	font-weight: bold !important;
	}
	.table.keterangan{
	text-align: center;
	}
	@if($data -> sync == 'y')
	.btn-calc{
	display:none;
	}
	@endif
	.box-import{
	display: none;
	}
</style>
@endpush

@push('scripts')
<script src="{{ asset('/js/clipboard.min.js') }}"></script>
<script>
 	var clipboard = new Clipboard('.cbrd');
	clipboard.on('success', function(e) {
	alert('Data telah tersimpan di clipboard !');
	});
	
	$('.btn-import').click(function(){
	$('.box-import').toggle();
	}); 
</script>
@endpush

@section('header')
<section class="content-header">
	<h1>
		Perkuliahan
		<small>Input Nilai Perkuliahan</small>
	</h1>
	<ol class="breadcrumb">
		<li><a href="{{ url('/') }}"><i class="fa fa-dashboard"></i> Home</a></li>
		<li><a href="{{ url('/matkul/tapel') }}">Kelas Kuliah</a></li>
		<li class="active">Nilai Perkuliahan</li>
	</ol>
</section>
@endsection

@section('content')
@if(isset($error))
<div class="alert alert-danger">
	<strong>Error!</strong> Semester tidak aktif
</div>
@else
<div class="box box-info">
	<div class="box-header with-border">
		<h3 class="box-title">Mata Kuliah</h3>
		<div class="box-tools">
			@if(Auth::user() -> role_id <= 2)
			<a onclick="javascript:history.back()" class="btn btn-default btn-xs btn-flat" title="Daftar"><i class="fa fa-list"></i> DAFTAR</a>
			<button class="btn btn-info btn-xs btn-flat btn-import"><i class="fa fa-cloud-download"></i> IMPOR DATA</button>
			@endif
			@if(Auth::user() -> role_id == 128)
			<a href="{{ url('/kelaskuliah/' . $matkul_tapel_id . '/peserta') }}" class='btn btn-primary btn-flat btn-xs' title='Peserta'><i class='fa fa-group'></i></a>
			<a href='/kelaskuliah/{{ $matkul_tapel_id}}/jurnal' class='btn btn-warning btn-flat btn-xs' title='Jurnal'><i class='fa fa-book'></i></a>
			<a href="/kelaskuliah/{{ $matkul_tapel_id}}/absensi" class="btn btn-danger btn-flat btn-xs" title="Absensi"><i class="fa fa-font"></i></a>
			@else
			<a href="{{ url('/matkul/tapel/' . $matkul_tapel_id . '/mahasiswa') }}" class='btn btn-primary btn-flat btn-xs' title='Peserta'><i class='fa fa-group'></i> PESERTA KULIAH</a>
			@endif
		</div>
	</div>
	<div class="box-body">
		<div class="row">
			<div class="col-xs-12">
				<table width="100%">
					<tr>
						<th width="20%">Matakuliah & Semester</th><th width="2%">:</th><td width="30%">{{ $data -> matkul }} ({{ $data -> kd }}) ({{ $data -> semester }})</td>
						<th width="20%">Dosen</th><th width="2%">:</th><td>{!! formatTimDosen($data -> tim_dosen) !!}</td>
					</tr>
					<tr>
						<th>Program & Kelas</th><th>:</th><td>{{ $data -> program }} @if(isset($data -> kelas)) ({{ $data -> kelas }})@endif</td>
						<th>PRODI</th><th>:</th><td>{{ $data -> prodi }} ({{ $data -> singkatan }})</td>
					</tr>
					<tr>
						<th>Jadwal & Ruang</th><th>:</th><td>@if(isset($data -> hari)){{ config('custom.hari')[$data -> hari] }}, {{ $data -> jam_mulai }} - {{ $data -> jam_selesai }} ({{ $data -> ruang }})@else<span class="text-muted">Belum ada jadwal</span>@endif</td>
						<th>Tahun Akademik</th><th>:</th><td>{{ $data -> ta }}</td>
					</tr>
					<tr>
						<th>Jumlah Mahasiswa</th><th>:</th><td>{{ count($peserta) }}</td>
						<th></th><th></th><td></td>
					</tr>
				</table>
			</div>
		</div>		
	</div>
</div>
<div class="box box-info box-import">
	<div class="box-header with-border">
		<h3 class="box-title">Impor Data</h3>
	</div>
	<div class="box-body">
		{!! Form::model(new Siakad\Nilai, ['class' => 'form-horizontal', 'role' => 'form', 'route' => ['matkul.tapel.nilai.import']]) !!}
		<div class="form-group">
			<label for="data" class="col-sm-1 control-label">Data:</label>
			<div class="col-sm-5">
				{!! Form::hidden('matkul_tapel_id', $matkul_tapel_id) !!}
				{!! Form::textarea('data', null, array('class' => 'form-control', 'rows' => '6', 'placeholder' => 'Nilai JSON Encoded' )) !!}
			</div>
		</div>
		<br/>
		<div class="form-group">
			<div class="col-sm-offset-1 col-sm-10">
				<button class="btn btn-flat btn-primary" type="submit"><i class="fa fa-cloud-download"></i> Import</button>
			</div>		
		</div>	
		{!! Form::close() !!}
	</div>
</div>

<div class="box box-info">
	<div class="box-header with-border">
		<h3 class="box-title">Nilai</h3>
		<div class="box-tools">
			<button class="btn btn-success btn-xs btn-flat cbrd" data-clipboard-text='{!! $edata !!}'><i class="fa fa-cloud-upload"></i> Ekspor data</button>
			<button class="btn btn-warning btn-flat btn-xs" data-toggle="modal" data-target="#keterangan"><i class="fa fa-bolt"></i> Keterangan Penilaian</button>
			<button class="btn btn-info btn-flat btn-xs" data-toggle="modal" data-target="#petunjuk"><i class="fa fa-lightbulb-o"></i> Petunjuk Pengisian Nilai</button>
			<a href="{{ route('matkul.tapel.nilai.form', $matkul_tapel_id) }}" class="btn btn-xs btn-success btn-flat" title="Cetak form Nilai" target="_blank"><i class="fa fa-print"></i> Cetak form Nilai</a>
		</div>
	</div>
	<div class="box-body">
		<?php 
			$n = 1; 
			$nilai_akhir = true;
			$nilai_final = true;
			$cb_nilai = [];
		?>
		<form method="post" action="{{ route('matkul.tapel.nilai.store') }}">
			{!! Form::hidden('mt_id', $matkul_tapel_id) !!}
			{!! Form::hidden('prodi_id', $data -> prodi_id) !!}
			{!! Form::hidden('jns', $jns) !!}
			{{ csrf_field() }}
			<table class="table table-bordered" id="nilais">
				<thead>
					<tr style="background-image: -webkit-gradient(linear,0 top,0 bottom,from(#3A8341),to(#609C40)); background-image: -moz-linear-gradient(#3A8341,#054a10);color: #fff;">
						<th width="50px">No.</th>
						<th width="100px">NIM</th>
						<th>Nama</th>
						<th>Telp</th>
						<th>Angkatan</th>
						@foreach($jenis as $k => $v)
						@if($k > 1)
						<?php
							$cb_nilai[$k] = false;
						?>
						<th>
							{{ $v[0] }}
						</th>	
						@endif
						@endforeach
						<th>Nilai Akhir</th>		
						<th>Nilai Huruf</th>					
						<th>SPP</th>					
					</tr>
				</thead>
				<tbody>
					@if(count($peserta) < 1)
					<tr>
						<td colspan="{{ count($jenis) + 6 }}" align="center">
							Belum ada mahasiswa yang terdaftar pada Mata Kuliah ini. Harap hubungi Administrator / Bagian Akademik
						</td>
					</tr>
					@else
					@foreach($peserta as $k => $mhs)
					<tr>
						<td>{{ $n }}</td>
						<td>{{ $mhs['nim'] }}</td>
						<td>{{ $mhs['nama']}}</td>
						<td>{{ $mhs['telp']}}</td>
						<td>{{ $mhs['angkatan']}}</td>
						@foreach($jenis as $j => $l)
						@if($j > 1)
						<?php
							$disabled = '';
							
							if(!$cb_nilai[$j]){ if(isset($nilai[$k][$j])){ $cb_nilai[$j] = true;}}
							
							// if($l[0] == 'UTS' and $mhs['lock']['uts'] == true) $disabled=' disabled="disabled" ';
							// if($l[0] == 'UAS' and $mhs['lock']['uas'] == true) $disabled=' disabled="disabled" ';
						?>
						<td>
							@if($data -> locked == 'y')
							{{ $nilai[$k][$j] ?? '' }}
							@else
							<input {{ $disabled }} type="number" min="0" max="100" class="form-control nilai_angka inline nl_{{ $j }}" 
							id="nl-{{ $j }}-{{ $k }}" name="nilai[{{ $k }}][{{ $j }}]" value="{{ $nilai[$k][$j] ?? '' }}"/>
							<select {{ $disabled }} class="form-control nilai_huruf inline nl_{{ $j }}">
								<option value="-">-</option>
								@foreach($skala as $h => $v)<option value="{{ $h }}" @if(isset($nilai[$k][$j]) and $h == $nilai[$k][$j . 'h']) selected="selected"@endif>{{ $h }}</option>@endforeach
							</select>
							@endif
							@if($disabled != '')
							<input type="hidden" name="nilai[{{ $k }}][{{ $j }}]" value="{{ $nilai[$k][$j] ?? '' }}"/>
							@endif
						</td>
						@endif
						@endforeach
						<?php
							if(!$nilai_akhir){ if(isset($nilai[$k][1])){ $nilai_akhir = true;}}
							if(!$nilai_final){ if(isset($nilai[$k][0])){ $nilai_final = true;}}
						?>
						<td>
							@if($data -> locked == 'y')
							{{ $nilai[$k][1] ?? '' }}
							@else
							<input {{ $disabled }} type="text" class="form-control nilai_akhir inline" name="nilai[{{ $k }}][__AKHIR__]" value="{{ $nilai[$k][1] ?? 0 }}"/>
							@endif
							
							@if($disabled != '')
							<input type="hidden" name="nilai[{{ $k }}][__AKHIR__]" value="{{ $nilai[$k][1] ?? 0 }}"/>
							@endif
						</td>
						<td>
							@if($data -> locked == 'y')
							{{ $nilai[$k][0] ?? '' }}
							@else
							<select {{ $disabled }} class="form-control nilai_final inline nl___FINAL__">
								<option value="-">-</option>
								@foreach($skala as $h => $v)<option value="{{ $h }}" @if(isset($nilai[$k][0]) and $h == $nilai[$k][0]) selected="selected"@endif>{{ $h }}</option>@endforeach
							</select>
							@endif
						</td>
						<td>
							@if($mhs['lock']['uts'] ?? $mhs['lock']['uas']) <i class="fa fa-times text-danger" data-toggle="popover" 
							data-content="Nilai <strong>belum</strong> bisa dilihat oleh Mahasiswa karena masih mempunyai tanggungan SPP Semester Aktif"></i>
							@else <i class="fa fa-check text-success" data-toggle="popover" data-content="Pembayaran SPP Semester Aktif sudah Lunas"></i>
							@endif
						</td>
					</tr>
					<?php $n++; ?>
					@endforeach
					@endif
				</tbody>
			</table>
			<br/>
			<div class="col-sm-offset-5">
				@if($data -> locked == 'n')
				<button class="btn btn-success btn-flat" type="submit"><i class="fa fa-save"></i> Simpan</button>
				<a href="{{ route('matkul.tapel.lock', $matkul_tapel_id) }}" class="btn btn-danger btn-flat"><i class="fa fa-lock"></i> Kunci</a>
				@else
				<button class="btn btn-danger btn-flat" type="button" disabled="disabled"><i class="fa fa-lock"></i> Terkunci</button>
				@endif
			</div>
		</form>
		<br/>
	</div>
</div>

@if($data -> locked == 'y')
@push('styles')
<link rel="stylesheet" href="{{ asset('/css/datepicker.min.css') }}">
<style>
	.inline{
	display: inline-block;
	width: 200px;
	}
</style>
@endpush

@push('scripts')
<script src="{{ asset('/js/datepicker.min.js') }}"></script>
<script>
	$(function(){
	$(".date").datepicker({
	format:"yyyy-mm-dd", 
	autoHide:true,
	daysMin: ['Mg', 'Sn', 'Sl', 'Rb', 'Km', 'Jm', 'Sa'],
	daysMin: ['Mg', 'Sn', 'Sl', 'Rb', 'Km', 'Jm', 'Sa'],
	monthsShort: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des']
	});
	});
</script>
@endpush
<div class="box box-warning">
	<div class="box-header with-border">
		<h3 class="box-title">Buka Penilaian</h3>
	</div>
	<div class="box-body">
		{!! Form::model($data, ['method' => 'POST', 'class' => 'form-inline', 'role' => 'form', 'route' => ['matkul.tapel.unlock', $matkul_tapel_id]]) !!}
		<div class="form-group">
			{!! Form::label('mulai', 'Waktu:', array('class' => 'col-sm-3 control-label sr-only')) !!}
			{!! Form::text('tanggal_mulai', null, array('class' => 'form-control date', 'placeholder' => 'Mulai', 'autocomplete' => "off")) !!}
			{!! Form::text('tanggal_selesai', null, array('class' => 'form-control date', 'placeholder' => 'Selesai', 'autocomplete' => "off")) !!}
		</div>
		<div class="form-group">
			<button class="btn btn-flat btn-primary" type="submit"><i class="fa fa-unlock"></i> Buka</button>
		</div>	
		{!! Form::close() !!}
	</div>
</div>
@endif

<div class="modal fade" id="keterangan" tabindex="-1" role="dialog" aria-labelledby="keterangan-title" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title" id="petunjuk-title"><strong>Keterangan Penilaian</strong></h4>
			</div>
			<div class="modal-body">
				<table class="table table-bordered keterangan">
					<thead>
						<tr>
							<th colspan="2">Angka</th>
							<th rowspan="2">Huruf</th>
							<th rowspan="2">Keterangan</th>
						</tr>
						<tr>
							<th>Interval</th>
							<th>Interval</th>
						</tr>
					</thead>
					<tbody>
						<?php
							$base = config('custom.nilai');
							$konv = config('custom.konversi_nilai');
							$c = 0;
							foreach($base as $b)
							{
								if($b != 'D') $a[$b] = '<td>' . ($konv['base_100'][$base[$c + 1]] + 1) . ' - ' . $konv['base_100'][$b] . '</td>
								<td>' . ($konv['base_4'][$base[$c + 1]] + 0.01) . ' - ' . $konv['base_4'][$b] . '</td>
								<td>' . $b . '</td>
								<td>' . $konv['base_lulus'][$b] . '</td>';
								$c++;
							}
						?>
						@foreach($a as $td)
						<tr>
							{!! $td !!}
						</tr>
						@endforeach
						<tr>
							<td>&lt; 50</td>
							<td>&lt; 1.75</td>
							<td>D</td>
							<td>Tidak Lulus</td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>
</div>

<div class="modal fade" id="petunjuk" tabindex="-1" role="dialog" aria-labelledby="petunjuk-title" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title" id="petunjuk-title"><strong>Petunjuk Pengisian Nilai</strong></h4>
			</div>
			<div class="modal-body">
				<ol style="padding-left: 18px;">
					<li>Rentang nilai yang valid adalah 1 - 100.</li>
					<li>Isikan nilai pada kolom yang tepat, kemudian tekan tombol <button class="btn btn-default btn-xs btn-flat"><i class="fa fa-rotate-90 fa-level-down"></i> Enter</button> untuk menyimpan.</li>
					<li>Untuk mengubah nilai yang telah tersimpan, klik pada nilai yang ingin diubah. Ubah nilai, kemudian tekan tombol <button class="btn btn-default btn-xs btn-flat"><i class="fa fa-rotate-90 fa-level-down"></i> Enter</button> untuk menyimpan.</li>
					<li>Untuk menghitung Hasil Akhir, klik <button class="btn btn-success btn-xs btn-flat"><i class="fa fa-save"></i> SIMPAN</button></li>
					<li>Setiap melakukan perubahan nilai, harus meng-klik <button class="btn btn-success btn-xs btn-flat"><i class="fa fa-save"></i> SIMPAN</button> untuk melihat perubahan Hasil Akhir pada mahasiswa. Hal ini ditujukan untuk memperingan beban server.</li>
				</ol>
			</div>
		</div>
	</div>
</div>
@endif
@endsection

@push('scripts')
<script>
	$(function(){
		$('[data-toggle="popover"]').popover({
			html: true,
			placement: 'auto top',
			trigger: 'hover'
		});
	});
	
	$(document).on('keyup', '.nilai_angka', function(){
	var me = $(this);
	
	me.next("select").val(toHuruf(me.val())).prop('selected', true);
	
	hitung(me);
});

$(document).on('change', '.nilai_huruf', function(){
	var me = $(this);
	var target = me.prev('.nilai_angka');
	
	e = $.Event('keydown');
	e.which = 13;
	
	target.val(toAngka(me.val())).trigger(e);
	hitung(me.prev('input'));
});

$(document).on('keyup', '.nilai_akhir', function(){
	var me = $(this);	
	var target = me.closest('tr').find('.nilai_final');
	var huruf = toHuruf(me.val());
	target.val(huruf).prop('selected', true);
});
$(document).on('change', '.nilai_final', function(){
	var me = $(this);		
	var target = me.closest('tr').find('.nilai_akhir');
	e = $.Event('keydown');
	e.which = 13;
	target.val(toAngka(me.val())).trigger(e);
});

function hitung(el)
{
	var mhs = el.attr('id').split('-')[2];
	var akhir = 0;
	akhir = @foreach($jenis as $k => $v)@if($k > 1) ($('#nl-{{ $k }}-' + mhs).val() * {{ $v[1] }} / 100) + @endif @endforeach + 0;
	el.closest('tr').find('.nilai_akhir').val(akhir);
	$('.nilai_akhir').keyup();
}

function toHuruf(angka)
{
	angka = parseInt(angka);
	if(angka <= 0) return '-';
	if(angka > 100) return '-'; @foreach($skala as $k => $v) else if(angka >= {{ $v['min_100'] }} && angka <= {{ $v['max_100'] }}) return '{{ $k }}';@endforeach		
}

function toAngka(huruf)
{
	var angka = 0;
	switch(huruf)
	{
		@foreach($skala as $k => $v)case '{{ $k }}':
		angka = {{ $v['max_100'] }};
		break;
		@endforeach
	}
	return angka;
}
</script>
@endpush																																																																																																																																																																																																																			