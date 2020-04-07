@extends('app')

@section('title')
Absensi
@endsection

@push('styles')
<style>
	.loader{
	margin-left: 3px;
	}
	.loading{
	color: #f39c12;
	}
	.success{
	color: #5CB85C;
	}
	.failed{
	color: #D9534F;
	}
</style>
<style>
	.middle{
	text-align: center;
	vertical-align: middle !important;
	}
	.table-minimal{
	font-size: 12px;
	}
	.table-minimal th, td{
	line-height: 13px !important;
	padding: 5px !important;
	}
	/* 	.fa-check{
	color: #5cb85c;
	} */
</style>
@endpush

@section('header')
<section class="content-header">
	<h1>
		Perkuliahan
		<small>Absensi</small>
	</h1>
	<ol class="breadcrumb">
		<li><a href="/"><i class="fa fa-dashboard"></i> Home</a></li>
		<li><a href="{{ url('/kelaskuliah') }}"> Mengajar Kelas</a></li>
		<li class="active">Absensi</li>
	</ol>
</section>
@endsection

@section('content')
<div class="box box-info">
	<div class="box-header with-border">
		<h3 class="box-title">Data Mata Kuliah</h3>
		<div class="box-tools">	
			<a href='/kelaskuliah/{{ $matkul_tapel_id}}/peserta' class='btn btn-primary btn-xs btn-flat' title='Peserta'><i class='fa fa-group'></i></a>
			<a href='/kelaskuliah/{{ $matkul_tapel_id}}/jurnal' class='btn btn-warning btn-xs btn-flat' title='Jurnal'><i class='fa fa-book'></i></a>
			<a href='/matkul/tapel/{{ $matkul_tapel_id}}/nilai' class='btn btn-success btn-xs btn-flat' title='Nilai'><i class='fa fa-bar-chart'></i></a>
		</div>
	</div>
	<div class="box-body">	
		<table width="100%">
			<tr>
				<th width="20%">Matakuliah & Semester</th><th width="2%">:</th><td width="30%">{{ $mata_kuliah -> matkul }} ({{ $mata_kuliah -> kd }}) ({{ $mata_kuliah -> semester }})</td>
				<th width="20%">Dosen</th><th width="2%">:</th><td>{{ $mata_kuliah -> dosen }}</td>
			</tr>
			<tr>
				<th>Program & Kelas</th><th>:</th><td>{{ $mata_kuliah -> program }} @if(isset($mata_kuliah -> kelas)) ({{ $mata_kuliah -> semester }}{{ $mata_kuliah -> kelas }})@endif</td>
				<th>PRODI</th><th>:</th><td>{{ $mata_kuliah -> prodi }} ({{ $mata_kuliah -> singkatan }})</td>
			</tr>
			<tr>
				<th>Jadwal & Ruang</th><th>:</th><td>@if(isset($mata_kuliah -> hari)){{ config('custom.hari')[$mata_kuliah -> hari] }}, {{ $mata_kuliah -> jam_mulai }} - {{ $mata_kuliah -> jam_selesai }} ({{ $mata_kuliah -> ruang }})@else<span class="text-muted">Belum ada jadwal</span>@endif</td>
				<th>Tahun Akademik</th><th>:</th><td>{{ $mata_kuliah -> ta }}</td>
			</tr>
			<tr>
				<th>Jumlah Mahasiswa</th><th>:</th><td>{{ $anggota -> count() }}</td>
				<th></th><th></th><td></td>
			</tr>
		</table>
	</div>
</div>


@if(!$jurnals -> count())
<div class="callout callout-info">
    <h4>Informasi</h4>
	<p>Untuk mengisi absensi, diharuskan mengisi jurnal pada pertemuan yang diinginkan. Untuk mengisi jurnal perkuliahan klik <a href='/kelaskuliah/{{ $matkul_tapel_id}}/jurnal' title='Jurnal'>di sini</a>.</p>
</div>
@else
<?php $c = 1; ?>
<div class="box box-danger">
	<div class="box-header with-border">
		<h3 class="box-title">Absensi</h3>
		<div class="box-tools">	
			<a href='/kelaskuliah/{{ $matkul_tapel_id}}/absensi/cetak' class='btn btn-danger btn-xs btn-flat' title='Peserta'><i class='fa fa-print'></i> Cetak</a>
		</div>
	</div>
	<div class="box-body">
		<table class="table table-bordered table-hover table-minimal">
			<thead>
				<tr>
					<th width="10px" rowspan="3" class="middle">No</th>
					<th width="100px" rowspan="3" class="middle">NIM</th>
					<th rowspan="3" class="middle" width="200px">Nama</th>
					<th colspan="{{ $jurnals -> count() }}" class="middle">Tatap Muka</th>
				</tr>
				<tr>
					@foreach($jurnals as $jurnal)
					<td>{{ $jurnal -> pertemuan_ke }}</td>
					@endforeach
				</tr>
				<tr>
					@foreach($jurnals as $jurnal)
					<?php $date = strtotime($jurnal -> tanggal); ?>
					<td>{{ date('d', $date) }}<br/>{{ date('M', $date) }}</td>
					@endforeach
				</tr>
			</thead>
			<tbody>	
				@foreach($data as $id => $d)
				<tr>
					<td>{{ $c }}</td>
					<td>{{ $d['nim'] }}</td>
					<td>{{ $d['nama'] }}</td>
					@foreach($d['status'] as $j => $status)
					<td>
						<?php
							$status = explode(':', $status);
						?>
						{!! Form::select('status', config('custom.pilihan.absensi'), $status[0], ['class' => 'absensi', 'jid' => $j, 'mid' => $id]) !!}
					</td>
					@endforeach
				</tr>
				<?php $c++; ?>
				@endforeach
			</tbody>
		</table>	
	</div>
</div>
@endif
@endsection		

@push('scripts')
<script>
	var loading = '<i class="fa fa-spinner fa-spin loader loading"></i>';
	var success = '<i class="fa fa-check loader success"></i>';
	var failed = '<i class="fa fa-close loader failed"></i>';
	
	$(document).on('change', '.absensi', function()
	{
		var me = $(this);
		var td = me.closest('td');
		$.ajax({
			url: '{{ route("absensi.submit") }}',
			type: "post",
			dataType: 'json',
			data: {
				'mid': $(this).attr('mid'),
				'jid': $(this).attr('jid'),
				'status': $(this).val(),
				'_token': '{{ csrf_token() }}'
			},
			beforeSend: function()
			{
				td.children().remove('.loader');
				td.children('select').after(loading);
			},
			success: function(data)
			{
					td.children().remove('.loader');
				if(!data.success)
				{
					alert('Terjadi kesalahan');	
					td.children('select').after(failed);
				}
				else
				{
					td.children('select').after(success);
				}
			},
			error: function()
			{
				td.children().remove('.loader');
				td.children('select').after(failed);
			}
		});
	});
</script>
@endpush																																					