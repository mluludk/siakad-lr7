@extends('app')

@section('title')
Nilai Peserta PKM {{ $pkm -> tapel -> nama }}
@endsection

@section('header')
<section class="content-header">
	<h1>
		PKM {{ $pkm -> tapel -> nama }}
		<small>Nilai Peserta</small>
	</h1>
	<ol class="breadcrumb">
		<li><a href="{{ url('/') }}"><i class="fa fa-dashboard"></i> Home</a></li>
		<li><a href="{{ url('/pkm') }}"> Data PKM</a></li>
		<li><a href="{{ url('/pkm/' . $pkm -> id . '/peserta') }}"> Peserta PKM {{ $pkm -> tapel -> nama }}</a></li>
		<li class="active">Nilai Peserta</li>
	</ol>
</section>
@endsection

@section('content')
<div class="box box-info">
	<div class="box-body">
		<div class="row">
			<div class="col-xs-12">
				<table width="100%">
					<tbody>
						<tr>
							<th width="20%">TAHUN AKADEMIK</th><td>: {{ $pkm -> tapel -> nama }}</td>
							<th width="20%">PRODI</th><td>: {{ $lokasi_matkul -> prodi -> strata }} {{ $lokasi_matkul -> prodi -> nama }}</td>
						</tr>
						<tr>
							<th>LOKASI</th><td>: {{ $lokasi_matkul -> lokasi -> nama }}</td>
							<th>DOSEN PENDAMPING</th><td>: {!! formatPendamping($lokasi_matkul -> pkm_lokasi_id, $pendamping) !!}</td>
						</tr>
						<tr>
							<th valign="top">MATA KULIAH</th><td valign="top">: {{ $lokasi_matkul -> mk -> nama }} ({{ $lokasi_matkul -> mk -> kode }})</td>
							<th></th><td></td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>		
	</div>
</div>
<div class="box">
	<div class="box-header with-border">
		<h3 class="box-title">Nilai Peserta</h3>
	</div>
	<div class="box-body">
		<table class="table table-bordered">
			<thead>
				<tr style="background-image: -webkit-gradient(linear,0 top,0 bottom,from(#3A8341),to(#609C40)); background-image: -moz-linear-gradient(#3A8341,#054a10);color: #fff;">
					<th width="30px">NO</th>
					<th>NIM</th>
					<th>NAMA</th>
					<th>NILAI</th>
				</tr>
			</thead>
			<tbody>
				@if(count($peserta) < 1)
				<tr>
					<td colspan="8" align="center">Belum ada data</td>
				</tr>
				@else
				<?php $c=1; ?>
				@foreach($peserta as $g)
				<tr>
					<td>{{ $c }}</td>
					<td>{{ $g -> NIM }}</td>
					<td>{{ $g -> mahasiswa }}</td>
					<td>
						<select name="nilai" class='nilai' 
						data-mahasiswa-id="{{ $g -> mahasiswa_id }}" 
						data-mahasiswa-nim="{{ $g -> NIM }}" 
						>
							<?php
								foreach($skala as $k => $v)
								{
									$key = explode('|', $k);
									echo '<option value="' . $k . '"';
									if($key[0] == $g -> nilai) echo ' selected="selected" ';
									echo '>' . $v .'</option>';
								}
							?>
						</select>
						<input type="text" class="nilai_angka" value="{{ $g -> nilai_angka ?? 0 }}"/>
					</td>
				</tr>
				@endforeach
				@endif
			</tbody>
		</table>
	</div>
</div>
@endsection

@push('scripts')
<style>
	ol{
	display: inline-block;
	}
	.nilai{
	padding: 2px 0;
	}
	.nilai_angka{
	width: 30px;
	}
</style>
<link rel="stylesheet" href="{{ asset('css/toastr.min.css') }}">
@endpush

@push('scripts')
	<script type="text/javascript" src="{{ asset('/js/toastr.min.js') }}"></script>
	<script>
		$(document).on('keyup', '.nilai_angka', function(e){
			var me = $(this);
			
			if(e.which == 13) submitNilai(me.prev('.nilai'));
			
			me.prev("select").val(toHuruf(me.val())).prop('selected', true);
		});
		
		$(document).on('change', '.nilai', function(){
			var me = $(this);
			var target = me.next('.nilai_angka');
			
			e = $.Event('keydown');
			e.which = 13;
			
			target.val(toAngka(me.val())).trigger(e);
			
			submitNilai(me);
		});
		
		function toHuruf(angka)
		{
			angka = parseInt(angka);
			if(angka > 100) return '-'; @foreach($skala_o as $k => $v) else if(angka >= {{ $v['min_100'] }} && angka <= {{ $v['max_100'] }}) return '{{ $k }}';@endforeach
		}
		function toAngka(huruf)
		{
			var angka = 0;
			switch(huruf)
			{
				@foreach($skala_o as $k => $v)case '{{ $k }}':
				angka = {{ $v['max_100'] }};
				break;
				@endforeach
			}
			return angka;
		}
		
		function submitNilai(me){
			
			$.ajax({
				url: '{{ url("/pkm/". $pkm -> id ."/lokasi/". $lokasi_matkul -> pkm_lokasi_id ."/nilai/". $lokasi_matkul -> matkul_id) }}',
				type: "post",
				data: {
					'nilai' : me.val() + '|' + me.next('.nilai_angka').val(),
					'mahasiswa_id' : me.attr('data-mahasiswa-id'),
					'mahasiswa_nim' : me.attr('data-mahasiswa-nim'),
					'_token': '{{ csrf_token() }}'
				},
				beforeSend: function()
				{
					me.addClass('hidden');
					me.siblings('.nilai_result').remove();
					me.after('<i class="fa fa-spinner fa-spin text-success" id="nilai_loader"></i>');
				},
				success: function(data){
					me.removeClass('hidden');
					$('#nilai_loader').remove();
					
					if(data.success == 0)
					{
						toastr.error(data.message)
					}
					else if(data.success == 1)
					{
						toastr.success(data.message);
					}
				}
			});
		}
	</script>
@endpush					