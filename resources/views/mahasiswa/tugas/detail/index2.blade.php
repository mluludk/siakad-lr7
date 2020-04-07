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
		<li><a href="{{ url('/tugas') }}"> Tugas Mahasiswa</a></li>
		<li class="active">Deskripsi</li>
	</ol>
</section>
@endsection

@section('content')
<style>
	.pilihan{
	padding-left: 12px;
	}
</style>
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
				<th>Status Tugas</th><th>:</th>
				<td>
					@if($tugas -> status == 1)
					<span class="label label-info label-flat">Dikirim</span>
					@elseif($tugas -> status == 2)
					<span class="label label-warning label-flat">Diperiksa</span>
					@elseif($tugas -> status == 3)
					<span class="label label-danger label-flat">Perbaikan</span>
					@elseif($tugas -> status == 4)
					<span class="label label-success label-flat">Selesai</span>
					@else
					<span class="label label-default label-flat">Belum</span>
					@endif
				</td>
				<th>Jenis Penilaian</th><th>:</th>
				<td>
					@if($tugas -> jnilai == '__FINAL__') Akhir @else {{ $tugas -> jnilai }} @endif 
				</td>
			</tr>
		</table>
	</div>
</div>
<div class="box box-primary">
	<div class="box-header with-border">
		@if($tugas -> jenis_tugas == 1)
		<h3 class="box-title">Download file-file Tugas berikut ini, kemudian upload kembali setelah dikerjakan !</h3>
		@elseif($tugas -> jenis_tugas == 2)
		<h3 class="box-title">Jawab pertanyaan-pertanyaan dibawah ini dengan benar !</h3>
		@elseif($tugas -> jenis_tugas == 3)
		<h3 class="box-title">Pilihlah Jawaban yang paling tepat !</h3>
		@endif
	</div>
	<div class="box-body">
		
		<?php $c=1; ?>
		
		<!-- Jenis 1 -->
		@if($tugas -> jenis_tugas == 1)
		{!! Form::model(new Siakad\Tugas, ['class' => 'form-inline', 'files' => true, 'route' => ['mahasiswa.tugas.detail2.store', $tugas -> id]]) !!}
		<table class="table table-bordered">
			<thead>
				<tr style="background-image: -webkit-gradient(linear,0 top,0 bottom,from(#3A8341),to(#609C40)); background-image: -moz-linear-gradient(#3A8341,#054a10);color: #fff;">
					<th width="30px">No.</th>
					<th>Tugas</th>
					<th>Upload</th>
				</tr>
			</thead>
			<tbody>
				@if($detail -> count())
				@foreach($detail as $d)
				<tr>
					<td>{{ $c }}</td>
					<td>
						<a href="{{ route('mahasiswa.tugas.detail.get', [$tugas -> id, $d -> id]) }}" class="btn btn-info btn-xs btn-flat">
							<i class="fa fa-download"></i> {{ substr($d -> pertanyaan, 11, strlen($d -> pertanyaan)) }}
						</a>
					</td>
					<td>
						@if($tugas -> status == 0 ?? $tugas -> status == 3 ?? $edit)
						<input type="file" name="tugas_detail[{{ $d -> id}}]" class="form-control"/>
						@else
						<a href="{{ route('mahasiswa.tugas.detail2.get', [$tugas -> id, $user -> authable_id, $d -> id]) }}" class="btn btn-danger btn-xs btn-flat">
							<i class="fa fa-download"></i> {{ substr($d ->  jawaban, 11, strlen($d ->  jawaban)) }}
						</a>
						@endif
					</td>
				</tr>
				<?php $c++; ?>
				@endforeach
				@endif
				
				@if($tugas -> status == 0 ?? $tugas -> status == 3 ?? $edit)
				<tr>
					<td colspan="5">
						<strong>Perhatian:</strong>
						<ol style="padding-left: 18px;">
							<li>Pastikan File yang anda Upload adalah file Microsoft Word (.doc / .docx).</li>
							<li>Pastikan semua nomor telah diisi file untuk upload sebelum klik tombol <strong>Simpan</strong></li>
						</ol>
					</td>
				</tr>
				<tr>
					<td align="center" colspan="3"><button class="btn btn-primary btn-flat" type="submit"><i class="fa fa-floppy-o"></i> Simpan</button></td>
				</tr>
				@endif
				
			</tbody>
		</table>
		{!! Form::hidden('jenis_tugas', $tugas -> jenis_tugas) !!}
		{!! Form::close() !!}
		<!-- Jenis 1 End-->
		
		<!-- Jenis 2 -->
		@elseif($tugas -> jenis_tugas == 2)
		{!! Form::model(new Siakad\Tugas, ['id' => 'frm', 'class' => 'form-inline', 'route' => ['mahasiswa.tugas.detail2.store', $tugas -> id]]) !!}
		@if($detail -> count())
		<table class="table table-bordered">
			@foreach($detail as $d)
			<tr>
				<th rowspan="2" valign="top" width="30px">{{ $c }}. </th>
				<td>{!! $d -> pertanyaan !!}</td>
			</tr>
			<tr>
				<td>
					@if($tugas -> status == 0 ?? $tugas -> status == 3 ?? $edit)
					<div id="sn-{{ $d -> id}}" class="summernote">{!! $d ->  jawaban ?? '' !!}</div>
					<input type="hidden" name="tugas_detail[{{ $d -> id}}]" id="isi-{{ $d -> id}}" >
					@else
					{!! $d ->  jawaban !!}
					@endif
				</td>
			</tr>
			<?php $c++; ?>
			@endforeach
			@endif
			@if($tugas -> status == 0 ?? $tugas -> status == 3 ?? $edit)
			<tr>
				<td colspan="5">
					Periksa jawaban anda dengan seksama sebelum meng-klik tombol 
					<strong> Simpan</strong>. 
				</td>
			</tr>
			<tr>
				<td align="center" colspan="5"><button class="btn btn-primary btn-flat" type="button" id="post"><i class="fa fa-floppy-o"></i> Simpan</button></td>
			</tr>
			@endif
		</table>
		{!! Form::hidden('jenis_tugas', $tugas -> jenis_tugas) !!}
		{!! Form::close() !!}
		<!-- Jenis 2 End-->
		
		<!-- Jenis 3-->		
		@elseif($tugas -> jenis_tugas == 3)
		{!! Form::model(new Siakad\Tugas, ['route' => ['mahasiswa.tugas.detail2.store', $tugas -> id]]) !!}
		@if($detail -> count())
		<table class="table table-bordered">
			@foreach($detail as $d)
			<tr>
				<th rowspan="2" valign="top" width="30px">{{ $c }}. </th>
				<td colspan="4">{!! $d -> pertanyaan !!}</td>
			</tr>
			<tr>
				<?php
					$pilihan = json_decode($d -> pilihan, true);
					if($tugas -> status == 0 ?? $tugas -> status == 3) shuffle_assoc($pilihan);
				?>
				@foreach($pilihan as $k => $v)
				<td width="25%">
					<label class="radio-inline">
						<input type="radio" name="tugas_detail[{{ $d -> id}}]" @if(isset($d ->  jawaban) && !$edit) disabled="disabled" @if($k == $d ->  jawaban) checked="checked" @endif @endif  value="{{ $k }}"> {{ $v }}
					</label>
				</td>
				@endforeach
			</tr>
			<?php $c++; ?>
			@endforeach
			
			@if($tugas -> status == 0 ?? $tugas -> status == 3 ?? $edit)
			<tr>
				<td colspan="5">
					Periksa jawaban anda dengan seksama sebelum meng-klik tombol 
					<strong> Simpan</strong>. 
				</td>
			</tr>
			<tr>
				<td align="center" colspan="5"><button class="btn btn-primary btn-flat" type="submit"><i class="fa fa-floppy-o"></i> Simpan</button></td>
			</tr>
			@endif
			
		</table>
		@endif
		{!! Form::hidden('jenis_tugas', $tugas -> jenis_tugas) !!}
		{!! Form::close() !!}
		<!-- Jenis 3 End-->
		
		@endif
		
		@if(!$edit && $tugas -> status != 4)
		<div class="center-block" style="text-align: center;">
			<a href="{{ route('mahasiswa.tugas.detail2.edit', $tugas -> id) }}" class="btn btn-warning btn-flat btn-lg"><i class="fa fa-edit"></i> Edit Jawaban*</a>
		</div>
		<br/>
		<span class="help-block">*: Jawaban bisa diperbarui selama Dosen belum memberi Penilaian.</span>
		@endif
	</div>
</div>
@endsection	

@push('scripts')
<script src="{{ asset('/summernote/summernote.min.js') }}"></script>
<script>
	$(document).on('click', '#post', function(){
		var passed = true;
		$('div.summernote').each(function(){
			if ($(this).summernote('isEmpty')) {
				alert('Semua jawaban harus diisi.'); 
				passed = false;
				return false;
			}
			var ids = $(this).attr('id').split('-');	
			var content = $(this).summernote('code');
			$('#isi-' + ids[1]).val(content);
		});
		if(passed) $('#frm').submit();
	});
	
	$(function(){
		$('.summernote').summernote({
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
@endpush