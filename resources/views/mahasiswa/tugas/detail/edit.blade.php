@extends('app')

@section('title')
Edit Tugas Mahasiswa
@endsection

@section('header')
<section class="content-header">
	<h1>
		Tugas Mahasiswa
		<small>Edit Pertanyaan</small>
	</h1>
	<ol class="breadcrumb">
		<li><a href="/"><i class="fa fa-dashboard"></i> Home</a></li>
		<li><a href="{{ url('/mahasiswa/tugas') }}"> Tugas Mahasiswa</a></li>
		<li class="active">Edit Pertanyaan</li>
	</ol>
</section>
@endsection

@push('scripts')
<script src="{{ asset('/summernote/summernote.min.js') }}"></script>
<script>
	$(document).on('click', '#post', function(){
	var content = $('#summernote').summernote('code');
	$('#isi').val(content);
	$('#frm').submit();
	});
	
	$(function(){
	$('#summernote').summernote({
	minHeight: 300, 
	maxHeight: null, 
	focus: true,
	toolbar: [
	['style', ['bold', 'italic', 'underline', 'clear']],
	['font', ['strikethrough', 'superscript', 'subscript']],
	['fontsize', ['fontname', 'fontsize']],
	['color', ['color']],
	['para', ['ul', 'ol', 'paragraph']],
	['height', ['height']],
	['insert', ['link', 'picture']]
	]
	});
	});
</script>
@endpush

@push('styles')
<link href="{{ asset('/summernote/summernote.css') }}" rel="stylesheet">
<style>
	.pilihan{
	padding-left: 12px;
	}
</style>
@endpush

@section('content')
<div class="box box-info">
	<div class="box-header with-border">
		<h3 class="box-title">Deskripsi Tugas Mahasiswa</h3>
	</div>
	<div class="box-body">
		<table width="100%">
			<tr>
				<th width="15%">Tahun Akademik</th><th width="2%">:</th><td width="30%">{{ $tugas -> tapel }}</td>
				<th width="15%">Mata Kuliah</th><th width="2%">:</th><td>{{ $tugas -> matkul }} ({{ $tugas -> kode }})</td>
			</tr>
			<tr>
				<th>Prodi</th><th>:</th><td>{{ $tugas -> strata }} {{ $tugas -> prodi }}</td>
				<th>Program</th><th>:</th><td>{{ $tugas -> program }} <strong>Semester</strong> {{ $tugas -> semester }}{{ $tugas -> kelas2 }}</td>
			</tr>
			<tr>
				<th>Judul Tugas</th><th>:</th><td>{{ $tugas -> judul }}</td>
				<th>Dosen</th><th>:</th><td>{{ formatTimDosen($tugas -> perkuliahan -> tim_dosen) }}</td>
			</tr>
			<tr>
				<th valign="top">Jenis Tugas</th><th valign="top">:</th><td valign="top">
					@if($tugas -> jenis_tugas == 1) <i class="fa fa-upload"></i>
					@elseif($tugas -> jenis_tugas == 2) <i class="fa fa-file-text-o"></i>
					@elseif($tugas -> jenis_tugas == 3) <i class="fa fa-check-square"></i>
					@endif
					{{ $jenis[$tugas -> jenis_tugas] }}
				</td>
				<th valign="top">Deskripsi Tugas</th><th valign="top">:</th><td valign="top">{!! $tugas -> keterangan !!}</td>
			</tr>
			<tr>
				<th>Tanggal Tugas</th><th>:</th><td>{{ $tugas -> tanggal }}</td>
				<th>Batas Akhir Tugas</th><th>:</th><td>{{ $tugas -> batas }}</td>
			</tr>
			<tr>
				<th>Status Publikasi *</th><th>:</th><td>
					@if($tugas -> published == 'y') <span class="label label-success label-flat"><i class="fa fa-unlock"></i> Public</span>
					@else <span class="label label-danger label-flat"><i class="fa fa-lock"></i> Private</span>
					@endif
				</td>
				<th>Jenis Penilaian (bobot)</th><th>:</th>
				<td>
					@if($tugas -> jnilai == '__FINAL__') Akhir @else {{ $tugas -> jnilai }} @endif 
					({{ $tugas -> bobot }}%)
				</td>
			</tr>
		</table>
	</div>
</div>

<div class="box box-primary">
	<div class="box-header with-border">
		<h3 class="box-title">Edit Pertanyaan</h3>
	</div>
	<div class="box-body">
		
		<!-- Jenis 2 -->
		@if($tugas -> jenis_tugas == 2)
		{!! Form::model($tugas_detail, ['id' => 'frm', 'method' => 'PATCH', 'class' => 'form-horizontal', 'route' => ['mahasiswa.tugas.detail.update', $tugas -> id, $tugas_detail -> id]]) !!}
		<div class="form-group">
			{!! Form::label('pertanyaan', 'Pertanyaan:', array('class' => 'col-sm-2 control-label')) !!}
			<div class="col-sm-9">
				<div id="summernote">{!! $tugas_detail -> pertanyaan !!}</div>
				<input type="hidden" name="pertanyaan" id="isi" >
				<input type="hidden" name="pilihan" value="" >
			</div>
		</div>
		<div class="form-group">
			<div class="col-sm-offset-2 col-sm-9">
				<button class="btn btn-warning btn-flat" type="submit" id="post"><i class="fa fa-floppy-o"></i> Simpan</button>
			</div>
		</div>
		{!! Form::close() !!}
		<!-- Jenis 2 End-->
		
		<!-- Jenis 3-->		
		@elseif($tugas -> jenis_tugas == 3)
		{!! Form::model($tugas_detail, ['id' => 'frm', 'method' => 'PATCH', 'class' => 'form-horizontal', 'route' => ['mahasiswa.tugas.detail.update', $tugas -> id, $tugas_detail -> id]]) !!}
		<div class="form-group">
			{!! Form::label('pertanyaan', 'Pertanyaan:', array('class' => 'col-sm-2 control-label')) !!}
			<div class="col-sm-9">
				<div id="summernote">{!! $tugas_detail -> pertanyaan !!}</div>
				<input type="hidden" name="pertanyaan" id="isi" >
			</div>
		</div>
		<div class="form-group">
			{!! Form::label('pilihan', 'Pilihan:', array('class' => 'col-sm-2 control-label')) !!}
			<div class="col-sm-9">
				<?php
					$n = 0;
					foreach(json_decode($tugas_detail -> pilihan) as $k => $v)
					{
						if($n == 0)
						echo '<input type="text" name="pilihan['. $k .']" value="'. $v . '" class="form-control" placeholder="Jawaban benar" title="Jawaban benar" style="width: 100%; color: #1daa34;"/>';
						else
						echo '<input type="text" name="pilihan['. $k .']" value="'. $v . '" class="form-control" placeholder="Pilihan 1" title="Pilihan 1" style="width: 100%; color: #ef1821;"/>';
						$n++;						
					}
				?>
			</div>
		</div>
		<div class="form-group">
			<div class="col-sm-offset-2 col-sm-9">
				<button class="btn btn-warning btn-flat" type="submit" id="post"><i class="fa fa-floppy-o"></i> Simpan</button>
			</div>
		</div>
		{!! Form::close() !!}
		<!-- Jenis 3 End-->
		
		@endif
	</div>
</div>
@endsection																																					