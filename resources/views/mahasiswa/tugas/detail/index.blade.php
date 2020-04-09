@extends('app')

@section('title')
Deskripsi Tugas Mahasiswa
@endsection

@section('header')
<section class="content-header">
	<h1>
		Tugas Mahasiswa
		<small>Deskripsi</small>
	</h1>
	<ol class="breadcrumb">
		<li><a href="/"><i class="fa fa-dashboard"></i> Home</a></li>
		<li><a href="{{ url('/mahasiswa/tugas') }}"> Tugas Mahasiswa</a></li>
		<li class="active">Deskripsi</li>
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
				<!-- <th valign="top">Deskripsi Tugas</th><th valign="top">:</th><td valign="top">{!! $tugas -> keterangan !!}</td> -->
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
		<h3>DESKRIPSI TUGAS MAHASISWA</h3>
			<ol style="padding-left: 18px;">
			<td valign="top">{!! $tugas -> keterangan !!}</td>
	</div>
</div>
<div class="box box-primary">
	<div class="box-header with-border">
		<h3 class="box-title">Detail Tugas Mahasiswa</h3>
	</div>
	<div class="box-body">
		
		<?php $c=1; ?>
		
		<!-- Jenis 1 -->
		@if($tugas -> jenis_tugas == 1)
		{!! Form::model(new Siakad\Tugas, ['class' => 'form-inline', 'files' => true, 'route' => ['mahasiswa.tugas.detail.store', $tugas -> id]]) !!}
		<table class="table table-bordered">
			<thead>
				<tr style="background-image: -webkit-gradient(linear,0 top,0 bottom,from(#3A8341),to(#609C40)); background-image: -moz-linear-gradient(#3A8341,#054a10);color: #fff;">
					<th width="30px">No.</th>
					<th>File</th>
					<th></th>
				</tr>
			</thead>
			<tbody>
				@if($detail -> count())
				@foreach($detail as $d)
				<?php
					if(!$dijawab) if(isset($d -> jawaban[0]) && $d -> jawaban[0] -> jawaban != null) $dijawab = true;	
				?>
				<tr>
					<td>{{ $c }}</td>
					<td>
						<a href="{{ route('mahasiswa.tugas.detail.get', [$tugas -> id, $d -> id]) }}">
						{{ substr($d -> pertanyaan, 11, strlen($d -> pertanyaan)) }}</a>
					</td>
					
					<td>
						@if($tugas -> published == 'n')
						<a href="{{ route('mahasiswa.tugas.detail.delete', [$tugas -> id, $d -> id]) }}" class="btn btn-danger btn-flat btn-xs has-confirmation"><i class="fa fa-trash"></i> Hapus</a>
						@endif
					</td>
				</tr>
				<?php $c++; ?>
				@endforeach
				@endif
				@if($tugas -> published == 'n')
				<tr>
					<td>{{ $c }}</td>
					<td>
						<input type="file" name="pertanyaan" class="form-control"/>
					</td>
					<td>
						<button class="btn btn-primary btn-flat" type="submit"><i class="fa fa-floppy-o"></i> Simpan</button>
					</td>
				</tr>
				@endif
			</tbody>
		</table>
		{!! Form::hidden('jenis_tugas', $tugas -> jenis_tugas) !!}
		{!! Form::close() !!}
		<!-- Jenis 1 End-->
		
		<!-- Jenis 2 -->
		@elseif($tugas -> jenis_tugas == 2)
		{!! Form::model(new Siakad\Tugas, ['id' => 'frm', 'class' => 'form-inline', 'route' => ['mahasiswa.tugas.detail.store', $tugas -> id]]) !!}
		<table class="table table-bordered">
			<thead>
				<tr style="background-image: -webkit-gradient(linear,0 top,0 bottom,from(#3A8341),to(#609C40)); background-image: -moz-linear-gradient(#3A8341,#054a10);color: #fff;">
					<th width="30px">No.</th>
					<th>Pertanyaan</th>
					<th></th>
				</tr>
			</thead>
			<tbody>
				@if($detail -> count())
				@foreach($detail as $d)
				<?php
					if(!$dijawab) if(isset($d -> jawaban[0]) && $d -> jawaban[0] -> jawaban != null) $dijawab = true;	
				?>
				<tr>
					<td>{{ $c }}</td>
					<td>{!! $d -> pertanyaan !!}</td>
					<td>
						@if($tugas -> published == 'n')
						<a href="{{ route('mahasiswa.tugas.detail.edit', [$tugas -> id, $d -> id]) }}" class="btn btn-warning btn-flat btn-xs"><i class="fa fa-edit"></i> Edit</a>
						<a href="{{ route('mahasiswa.tugas.detail.delete', [$tugas -> id, $d -> id]) }}" class="btn btn-danger btn-flat btn-xs has-confirmation"><i class="fa fa-trash"></i> Hapus</a>
						@endif
					</td>
				</tr>
				<?php $c++; ?>
				@endforeach
				@endif
				@if($tugas -> published == 'n')
				<tr>
					<td>{{ $c }}</td>
					<td>
						<div id="summernote"></div>
						<input type="hidden" name="pertanyaan" id="isi" >
					</td>
					<td>
						<button class="btn btn-primary btn-flat" type="submit" id="post"><i class="fa fa-floppy-o"></i> Simpan</button>
					</td>
				</tr>
				@endif
			</tbody>
		</table>
		{!! Form::hidden('jenis_tugas', $tugas -> jenis_tugas) !!}
		{!! Form::close() !!}
		<!-- Jenis 2 End-->
		
		<!-- Jenis 3-->		
		@elseif($tugas -> jenis_tugas == 3)
		{!! Form::model(new Siakad\Tugas, ['id' => 'frm', 'class' => 'form-inline', 'route' => ['mahasiswa.tugas.detail.store', $tugas -> id]]) !!}
		<table class="table table-bordered">
			<thead>
				<tr>
					<th width="30px">No.</th>
					<th width="60%">Pertanyaan</th>
					<th>Pilihan Jawaban **</th>
					<th></th>
				</tr>
			</thead>
			<tbody>
				@if($detail -> count())
				@foreach($detail as $d)				
				<?php
					if(!$dijawab) if(isset($d -> jawaban[0]) && $d -> jawaban[0] -> jawaban != null) $dijawab = true;	
				?>				
				<tr>
					<td>{{ $c }}</td>
					<td>{!! $d -> pertanyaan !!}</td>
					<td>
						<ol type="A" class="pilihan">
							@foreach(json_decode($d -> pilihan) as $k => $v)
							<li @if($k == $d -> kunci) class="text-success" @else class="text-danger" @endif >
								{{ $v }}
							</li>
							@endforeach
						</ol>
					</td>
					<td>
						@if($tugas -> published == 'n')
						<a href="{{ route('mahasiswa.tugas.detail.edit', [$tugas -> id, $d -> id]) }}" class="btn btn-warning btn-flat btn-xs"><i class="fa fa-edit"></i> Edit</a>
						<a href="{{ route('mahasiswa.tugas.detail.delete', [$tugas -> id, $d -> id]) }}" class="btn btn-danger btn-flat btn-xs has-confirmation"><i class="fa fa-trash"></i> Hapus</a>
						@endif
					</td>
				</tr>
				<?php $c++; ?>
				@endforeach
				@endif
				@if($tugas -> published == 'n')
				<tr>
					<td>{{ $c }}</td>
					<td>
					<div id="summernote"></div>
					<input type="hidden" name="pertanyaan" id="isi" >
					</td>
					<td>
					<input type="text" name="pilihan[]" class="form-control" placeholder="Jawaban benar" title="Jawaban benar" style="width: 100%; color: #1daa34;"/>
					<input type="text" name="pilihan[]" class="form-control" placeholder="Pilihan 1" title="Pilihan 1" style="width: 100%; color: #ef1821;"/>
					<input type="text" name="pilihan[]" class="form-control" placeholder="Pilihan 2" title="Pilihan 2" style="width: 100%; color: #ef1821;"/>
					<input type="text" name="pilihan[]" class="form-control" placeholder="Pilihan 3" title="Pilihan 3" style="width: 100%; color: #ef1821;"/>
					</td>
					<td>
					<button class="btn btn-primary btn-flat" type="submit" id="post"><i class="fa fa-floppy-o"></i> Simpan</button>
					</td>
					</tr>
					@endif
					</tbody>
					</table>
					{!! Form::hidden('jenis_tugas', $tugas -> jenis_tugas) !!}
					{!! Form::close() !!}
					<!-- Jenis 3 End-->
					
					@endif
					
					@if($tugas -> published == 'n')
					<div class="center-block" style="text-align: center;">
					<a href="{{ route('mahasiswa.tugas.publish', $tugas -> id) }}" class="btn btn-success btn-flat btn-lg"><i class="fa fa-send"></i> Publikasikan</a>
					</div>
					<br/>
					<span class="help-block">* Tugas yang <span class="label label-danger label-flat">Belum</span>
					dipublikasikan tidak muncul di Login Mahasiswa. 
					Tugas yang <span class="label label-success label-flat">Sudah</span> 
					dipublikasikan akan muncul di Login Mahasiswa & <strong>tidak</strong> bisa lagi di-edit.</span>
					<span class="help-block">** Pilihan Jawaban pada Jenis Tugas Pilihan Ganda akan diacak oleh sistem di Login Mahasiswa </span>
					@else
					@if(!$dijawab)
					<div class="center-block" style="text-align: center;">
					<a href="{{ route('mahasiswa.tugas.publish', [$tugas -> id, 'n']) }}" class="btn btn-danger btn-flat btn-lg"><i class="fa fa-undo"></i> Batalkan Publikasi *</a>
					</div>
					<br/>
					<span class="help-block">* Pembatalan Publikasi Tugas <strong>HANYA</strong> bisa dilakukan selama belum ada 
					Mahasiswa yang mengerjakan Tugas ini.</span>
					@endif
					
					@endif
					</div>
					</div>
					@endsection																																										