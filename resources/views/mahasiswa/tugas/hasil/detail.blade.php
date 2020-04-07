@extends('app')

@section('title')
Jawaban Tugas Mahasiswa
@endsection

@section('header')
<section class="content-header">
	<h1>
		Tugas Mahasiswa
		<small>Jawaban</small>
	</h1>
	<ol class="breadcrumb">
		<li><a href="/"><i class="fa fa-dashboard"></i> Home</a></li>
		<li><a href="{{ url('/mahasiswa/tugas') }}"> Tugas Mahasiswa</a></li>
		<li><a href="{{ url('/mahasiswa/tugas/' . $tugas -> id . '/hasil') }}"> Pengumpulan Hasil</a></li>
		<li class="active">Jawaban</li>
	</ol>
</section>
@endsection

@section('content')
<div class="box box-info">
	<div class="box-header with-border">
		<h3 class="box-title">Data Mahasiswa</h3>
	</div>
	<div class="box-body">
		
		<table width="100%">
			<tr>
				<th width="15%">Nama</th><th width="2%">:</th><td width="30%">{{ $mahasiswa -> nama }}</td>
				<th width="15%">NIM</th><th width="2%">:</th><td>{{ $mahasiswa -> NIM }}</td>
			</tr>
			<tr>
				<th>Prodi</th><th>:</th><td>{{ $mahasiswa -> prodi -> strata }} {{ $mahasiswa -> prodi -> singkatan }}</td>
				<th>Program</th><th>:</th><td>{{ $mahasiswa -> kelas -> nama }}</td>
			</tr>
			<tr>
				<th>Semester</th><th>:</th><td>{{ $mahasiswa -> semesterMhs }}</td>
				<th></th><th></th><td></td>
			</tr>
		</table>
		
	</div>
</div>
<div class="box box-primary">
	<div class="box-header with-border">
		<h3 class="box-title">Jawaban Mahasiswa</h3>
	</div>
	<div class="box-body">
		
		<?php $c=1; ?>
		
		<!-- Jenis 1 -->
		@if($tugas -> jenis_tugas == 1)
		<table class="table table-bordered">
			<thead>
				<tr style="background-image: -webkit-gradient(linear,0 top,0 bottom,from(#3A8341),to(#609C40)); background-image: -moz-linear-gradient(#3A8341,#054a10);color: #fff;">
					<th width="30px">No.</th>
					<th>Tugas</th>
					<th>Upload Mahasiswa</th>
				</tr>
			</thead>
			<tbody>
				@if($hasil_detail -> count())
				@foreach($hasil_detail as $d)
				<tr>
					<td>{{ $c }}</td>
					<td>
						<a href="{{ route('mahasiswa.tugas.detail.get', [$tugas -> id, $d -> id]) }}" class="btn btn-info btn-xs btn-flat">
							<i class="fa fa-download"></i> {{ substr($d -> pertanyaan, 11, strlen($d -> pertanyaan)) }}
						</a>
					</td>
					<td>
						<a href="{{ route('mahasiswa.tugas.detail2.get', [$tugas -> id, $mahasiswa -> id, $d -> id]) }}" class="btn btn-danger btn-xs btn-flat">
							<i class="fa fa-download"></i> {{ substr($d ->  jawaban, 11, strlen($d ->  jawaban)) }}
						</a>
					</td>
				</tr>
				<?php $c++; ?>
				@endforeach
				@endif				
			</tbody>
		</table>
		<!-- Jenis 1 End-->
		
		<!-- Jenis 2 -->
		@elseif($tugas -> jenis_tugas == 2)
		@if($hasil_detail -> count())
		<table class="table table-bordered">
			@foreach($hasil_detail as $d)
			<tr>
				<th rowspan="2" valign="top" width="30px">{{ $c }}. </th>
				<td colspan="4">{!! $d -> pertanyaan !!}</td>
			</tr>
			<tr>
				<td>
					{!! $d ->  jawaban !!}
				</td>
			</tr>
			<?php $c++; ?>
			@endforeach
			@endif
		</table>
		<!-- Jenis 2 End-->
		
		<!-- Jenis 3-->		
		@elseif($tugas -> jenis_tugas == 3)
		@if($hasil_detail -> count())
		<table class="table table-bordered">
			@foreach($hasil_detail as $d)
			<tr>
				<th rowspan="2" valign="top" width="30px"@if($d -> jawaban != $d -> kunci) class="danger" @endif>{{ $c }}. </th>
				<td colspan="4">{!! $d -> pertanyaan !!}</td>
			</tr>
			<tr>
				<?php
					$pilihan = json_decode($d -> pilihan, true);
				?>
				@foreach($pilihan as $k => $v)
				
				<td width="25%"@if($k == $d ->  jawaban) class="info" @endif>
					{{ $v }}
				</td>
				@endforeach
			</tr>
			<?php $c++; ?>
			@endforeach			
		</table>
		@endif
		<!-- Jenis 3 End-->
		
		@endif
		
	</div>
</div>

<div class="box box-danger">
	<div class="box-header with-border">
		<h3 class="box-title">Penilaian</h3>
	</div>
	<div class="box-body">
		{!! Form::model(new Siakad\MahasiswaTugas, ['class' => 'form-horizontal', 'role' => 'form', 'route' => ['mahasiswa.tugas.hasil.nilai', $tugas -> id, $mahasiswa -> id]]) !!}		
		<div class="form-group">
			{!! Form::label('nilai', 'Nilai:', array('class' => 'col-sm-1 control-label')) !!}
			<div class="col-xs-9">
				<input type="number" min="0" max="100" class="form-control nilai_angka inline" name="nilai" value="{{ $mahasiswa_tugas -> angka }}"/>
				<select class="form-control nilai_huruf inline">
					<option value="-">-</option>
					@foreach($skala as $h => $v)<option value="{{ $h }}" @if($mahasiswa_tugas -> nilai == $h) selected="selected" @endif>{{ $h }}</option>@endforeach
				</select>
				<button class="btn btn-primary btn-flat inline" style="vertical-align: top;"><i class="fa fa-check"></i> OK</button>
			</div>
		</div>
		{!! Form::hidden('matkul_tapel_id', $tugas -> matkul_tapel_id) !!}
		{!! Form::hidden('jenis_nilai_id', $tugas -> jenis_nilai_id) !!}
		{!! Form::hidden('prodi_id', $perkuliahan -> prodi_id) !!}
		{!! Form::close() !!}
	</div>
</div>
@endsection	

@push('scripts')
<script>
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
	function toHuruf(angka)
	{
		angka = parseInt(angka);
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

@push('styles')
<style>
	.pilihan{
	padding-left: 12px;
	}
	.inline{
	display:inline-block;
	width: auto;
	}
</style>
@endpush