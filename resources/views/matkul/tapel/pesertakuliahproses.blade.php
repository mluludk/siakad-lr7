@extends('app')

@section('title')
Peserta Kuliah
@endsection

@section('header')
<section class="content-header">
	<h1>
		Perkuliahan
		<small>Peserta Kuliah</small>
	</h1>
	<ol class="breadcrumb">
		<li><a href="/"><i class="fa fa-dashboard"></i> Home</a></li>
		<li><a href="{{ url('/matkul/tapel') }}"> Kelas Kuliah</a></li>
		<li class="active">Peserta Kuliah</li>
	</ol>
</section>
@endsection

@push('scripts')
<script type="text/javascript">
	var loader = '<i class="fa fa-hourglass-o fa-spin fa-2x loader"></i>';
	function move(srcList, dstList, url)
	{
		if($('.' + srcList + ' input:checkbox').length<1) return;
		var n = 0;
		var id = {};
		var name = {};
		var clone = '';
		$('.' + srcList + " input:checked").each(function(){
			me = $(this);
			id[n] = me.val();
			n++;
		});
		$('input:checked').prop('checked', false);
		if(n > 0)
		{
			$.ajax({
				url: url,
				type: "post",
				data: {
					'id' : id,
					'_token': '{{ csrf_token() }}'
				},
				beforeSend: function()
				{
					$('.' + srcList).prepend(loader);
					$('.' + dstList).prepend(loader);
				},
				success: function(data){
					$('.loader').remove();
					if(data.trim() != 'success')
					{
						alert('Terjadi kesalahan');	
					}
					else
					{
						window.location.reload();
					}
				}
			});  
		}
	}
	
	$(document).on('click', '.btn-in', function(){
		move('mahasiswa', 'anggota', "{{ route('matkul.tapel.addmhsin', $matkul_tapel_id) }}")
	});
	
	$(document).on('click', '.btn-out', function(){
		move('anggota', 'mahasiswa', "{{ route('matkul.tapel.addmhsout', $matkul_tapel_id) }}")
	});
	
	$(document).on('change', '.check-all', function (){
		var me = $(this);
		$("." + me.val() + " :checkbox").prop('checked', me.prop("checked"));
	});
</script>
@endpush

@push('styles')
<style>
	.form-group{
	margin-bottom: 0px;
	}
	label{
	text-align: left !important;
	}
	.daftar{
	/* margin-top: 10px;
	padding: 3px 0 3px 10px;
	border: 1px solid #ddd;
	border-radius: 5px; */
	height: 300px;
	overflow-y: scroll;
	position:relative;
	}
	.loader{
	/* display:none; */
	position: absolute;
	left: 50%;
	top: 30%;
	}
	.angkatan{
	display: inline-block;
	width: auto;
	}
</style>
@endpush

@section('content')
<div class="box box-info">
	<div class="box-header with-border">
		<h3 class="box-title">Data Mata Kuliah</h3>
		<div class="box-tools">
			<a href="{{ url('/matkul/tapel/'. $matkul_tapel_id .'/nilai') }}" class="btn btn-xs btn-flat btn-success" title="Nilai"><i class="fa fa-bar-chart"></i> Nilai Perkuliahan</a>
		</div>
	</div>
	<div class="box-body">	
		<table width="100%">
			<tr>
				<th valign="top" width="20%">Matakuliah & Semester</th><th valign="top" width="2%">:</th><td valign="top" width="30%">{{ $data -> matkul }} ({{ $data -> kd }}) ({{ $data -> semester }})</td>
				<th valign="top" width="20%">Dosen</th><th valign="top" width="2%">:</th>
				<td>
					{!! formatTimDosen($data -> tim_dosen) !!}
				</td>
			</tr>
			<tr>
				<th>Program & Kelas</th><th>:</th><td>{{ $data -> program }} @if(isset($data -> kelas)) ({{ $data -> semester }}{{ $data -> kelas }})@endif</td>
				<th>PRODI</th><th>:</th><td>{{ $data -> prodi }} ({{ $data -> singkatan }})</td>
			</tr>
			<tr>
				<th>Jadwal & Ruang</th><th>:</th><td>@if(isset($data -> hari)){{ config('custom.hari')[$data -> hari] }}, {{ $data -> jam_mulai }} - {{ $data -> jam_selesai }} ({{ $data -> ruang }})@else<span class="text-muted">Belum ada jadwal</span>@endif</td>
				<th>Tahun Akademik</th><th>:</th><td>{{ $data -> ta }}</td>
			</tr>
			<tr>
				<th>Jumlah Mahasiswa</th><th>:</th><td>{{ $anggota -> count() }}</td>
				<th></th><th></th><td></td>
			</tr>
		</table>
	</div>
</div>

<div class="row">
<div class="col-sm-12">
<div class="form-horizontal" role="form">
<div class="row" style="margin-top: 3px;">
<div class="col-xs-5">
<div class="panel panel-warning">
<div class="panel-heading" style="color: #fff; background-color: #f0ad4e;">
<h3 class="panel-title">Mahasiswa yang bisa mengambil matakuliah*</h3>
</div>
<div class="panel-body">
<div class="checkbox-inline">
<label>
<input type="checkbox" class="check-all" value="mahasiswa" >
Pilih semua
</label>
</div>
<div class="daftar mahasiswa">
<?php $c = 1; ?>
@foreach($mahasiswa as $mhs)
<div class="checkbox">
<label>
<input type="checkbox" value="{{ $mhs->id }}" >
<span class="number">{{ $c }}</span>. {{ $mhs->NIM }} - {{ $mhs->nama }}
</label>
</div>
<?php $c++; ?>
@endforeach
</div>
</div>
</div>
<span class="help-block">*: Hanya mahasiswa yang mempunyai Prodi, Semester dan Kelas yang sesuai dengan Matakuliah yang ditampilkan. <br/><a class="btn btn-success btn-xs" href="/mahasiswa/transfer">Update data mahasiswa</a></span>
</div>
<div class="col-xs-2" style="height: 300px; display: flex; align-items: center; justify-content: center;">
<div style="width: 100%">
<button class="btn btn-lg btn-primary btn-in btn-block" type="button">>></button>
<button class="btn btn-lg btn-warning btn-out btn-block" type="button"><<</button>
</div>
</div>
<div class="col-xs-5">
<div class="panel panel-primary">
<div class="panel-heading">
<h3 class="panel-title">Peserta kuliah</h3>
</div>
<div class="panel-body">
<div class="checkbox-inline">
<label>
<input type="checkbox" class="check-all" value="anggota" >
Pilih semua
</label>
</div>
<div class="daftar anggota">
<?php $c = 1; ?>
@foreach($anggota as $ang)
<div class="checkbox">
<label>
<input type="checkbox" value="{{ $ang -> mhs_id }}" >
<span class="number">{{ $c }}</span>. {{ $ang->NIM }} - {{ $ang->nama }}
</label>
</div>
<?php $c++; ?>
@endforeach
</div>
</div>
</div>
</div>
</div>
</div>
</div>
</div>
</div>
</div>
@endsection																																																																																				