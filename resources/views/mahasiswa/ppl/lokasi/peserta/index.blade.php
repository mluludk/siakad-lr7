@extends('app')

@section('title')
Peserta PPL {{ $ppl -> strata }} {{ $ppl -> prodi }} {{ $ppl -> tapel }}
@endsection

@section('header')
<section class="content-header">
	<h1>
		PPL {{ $ppl -> strata }} {{ $ppl -> prodi }} {{ $ppl -> tapel }}
		<small>Daftar Peserta</small>
	</h1>
	<ol class="breadcrumb">
		<li><a href="{{ url('/') }}"><i class="fa fa-dashboard"></i> Home</a></li>
		<li><a href="{{ url('/ppl') }}"> Data PPL</a></li>
		<li class="active">Peserta PPL {{ $ppl -> strata }} {{ $ppl -> prodi }} {{ $ppl -> tapel }} </li>
	</ol>
</section>
@endsection

@section('content')
<div class="box">
	<div class="box-header with-border">
		<h3 class="box-title">Daftar Peserta PPL {{ $ppl -> strata }} {{ $ppl -> prodi }} {{ $ppl -> tapel }} </h3>
		<div class="box-tools">
			<a href="{{ route('mahasiswa.ppl.lokasi.peserta.index', [$ppl -> id, 'print']) }}" class="btn btn-success btn-xs btn-flat" title="Cetak Data PPL"><i class="fa fa-print"></i> Cetak Data Peserta</a>
		</div>
	</div>
	<div class="box-body">
		<?php $c=1; ?>
		<table class="table table-bordered">
			<thead>
				<tr style="background-image: -webkit-gradient(linear,0 top,0 bottom,from(#3A8341),to(#609C40)); background-image: -moz-linear-gradient(#3A8341,#054a10);color: #fff;">
					<th width="30px">NO</th>
					<th>NIM</th>
					<th>NAMA</th>
					<th>PRODI</th>
					<th>PROGRAM</th>
					<th>LOKASI</th>
					<th>DOSEN PENDAMPING</th>
					<th>NILAI</th>
					<th></th>
				</tr>
			</thead>
			<tbody>
				@if(count($peserta) < 1)
				<tr>
					<td colspan="8" align="center">Belum ada data</td>
				</tr>
				@else
				@foreach($peserta as $lokasi)
				<?php
					$span = '';
					$l = false;
					$rs = count($lokasi);
					if($rs > 1) $span = ' rowspan="' . $rs . '"';
				?>
				@foreach($lokasi as $g)
				@if($user -> role_id < 128 || ($user -> role_id == 128 && isPendamping($g -> ppl_lokasi_id, $user -> authable_id, $pendamping)))
					<tr>
						<td>{{ $c }}</td>
						<td>{{ $g -> NIM }}</td>
						<td>{{ $g -> mahasiswa }}</td>
						<td>{{ $g -> strata }} {{ $g -> singkatan }}</td>
						<td>{{ $g -> program }}</td>
						@if(!$l)
						<td {!! $span !!}>{{ $g -> lokasi }}</td>
						<td {!! $span !!}>{!! formatPendamping($g -> ppl_lokasi_id, $pendamping) !!}</td>
						@endif
						<td>
							@if($user -> role_id <=2 || ($user -> role_id == 128 && isPendamping($g -> ppl_lokasi_id, $user -> authable_id, $pendamping)))
								<select name="nilai" class='nilai' data-angkatan-id="{{ $g -> angkatan }}" data-mahasiswa-id="{{ $g -> mahasiswa_id }}" data-mahasiswa-nim="{{ $g -> NIM }}" data-lokasi-id="{{ $g -> ppl_lokasi_id }}">
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
							@else
							{{ $g -> nilai }}
							@endif
						</td>
						<td>
						<a href="{{ route('mahasiswa.ppl.lokasi.peserta.delete', [$ppl -> id, $g -> ppl_lokasi_id, $g -> mahasiswa_id]) }}"
						class="btn btn-danger btn-xs btn-flat has-confirmation" title="Hapus Peserta PPL"><i class="fa fa-trash"></i></a>
						</td>
					</tr>
					@endif
					<?php
						$c++;
						if(!$l) $l = true;
					?>
					@endforeach
					@endforeach
					@endif
				</tbody>
			</table>
		</div>
		</div>
		@endsection

		@push('styles')
		<link rel="stylesheet" href="{{ asset('css/toastr.min.css') }}">
		<style type="text/css">
		.nilai_angka{
width: 30px;
		}
		</style>
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
		url: '{{ url("/ppl/". $ppl -> id ."/peserta") }}',
		type: "post",
		data: {
		'nilai' : me.val() + '|' + me.next('.nilai_angka').val(),
		'lokasi_id' : me.attr('data-lokasi-id'),
		'matkul_id' : {{ $ppl -> matkul_id }},
		'prodi_id': {{ $ppl -> prodi_id }},
		'angkatan' : me.attr('data-angkatan-id'),
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