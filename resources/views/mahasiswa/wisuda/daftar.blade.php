@extends('app')

@section('title')
Pendaftaran Wisuda
@endsection

@section('header')
@if($admin)
<section class="content-header">
	<h1>
		Wisuda
		<small>Detail Peserta</small>
	</h1>
	<ol class="breadcrumb">
		<li><a href="/"><i class="fa fa-dashboard"></i> Home</a></li>
		<li><a href="{{ url('/wisuda') }}"> Jadwal Wisuda</a></li>
		<li><a href="{{ route('mahasiswa.wisuda.peserta', $wisuda -> id) }}"> Peserta Wisuda</a></li>
		<li class="active">Detail Peserta</li>
	</ol>
</section>
@else
<section class="content-header">
	<h1>
		Mahasiswa
		<small>Pendaftaran Wisuda</small>
	</h1>
	<ol class="breadcrumb">
		<li><a href="/"><i class="fa fa-dashboard"></i> Home</a></li>
		<li class="active">Pendaftaran Wisuda</li>
	</ol>
</section>
@endif
@endsection

@push('styles')
<link rel="stylesheet" href="{{ asset('/css/chosen.min.css') }}">
<style>
	.form-horizontal .control-label {
	text-align: left;
	}
	.form-control-static:before, .form-control:before {
	content: ":  ";
	}
	
	.span-block{
	display:block; width: 200px; float: left; margin-right: 2px;
	}
	
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

@if(!$show)
	
@push('scripts')
<script src="{{ asset('/js/chosen.jquery.min.js') }}"></script>
<script src="{{ asset('/js/jquery.inputmask.bundle.min.js') }}"></script>
<script src="{{ asset('/js/datepicker.min.js') }}"></script>
<script>
	$(function(){
		$(".chosen-select").chosen({no_results_text: "Tidak ditemukan hasil pencarian untuk: "});
		$(".year").inputmask("y",{"placeholder":"yyyy"});
		
		/* $(".date").inputmask("dd-mm-yyyy",{"placeholder":"dd-mm-yyyy"}); */
		$(".date").datepicker({
			format:"dd-mm-yyyy", 
			autoHide:true,
			daysMin: ['Mg', 'Sn', 'Sl', 'Rb', 'Km', 'Jm', 'Sa'],
			daysMin: ['Mg', 'Sn', 'Sl', 'Rb', 'Km', 'Jm', 'Sa'],
			monthsShort: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des']
		});
	});
</script>
@endpush

@push('styles')
<link rel="stylesheet" href="{{ asset('/css/datepicker.min.css') }}">
@endpush

@endif

@section('content')
<div class="box box-primary">
	<div class="box-header with-border">
		@if($admin)
		<h3 class="box-title">Detail Peserta</h3>
		@else
		<h3 class="box-title">Pendaftaran Wisuda</h3>
		@endif
	</div>
	<div class="box-body">
		@if($data == null)
		<div class="callout callout-danger">
			<h4>Kesalahan</h4>
			Pendaftaran wisuda sudah ditutup. Silahkan menghubungi Bagian Akademik.
		</div>
		@else
		<div class="row">
			<div class="col-sm-3">
				@if($show)
				<div class="thumbnail">
					<img src="@if(isset($data -> foto) and $data -> foto != '') /getimage/{{ $data -> foto }} @else /images/b.png @endif" alt="{{ $data -> nama }}">
				</div>
				@else
				{!! Form::open(['url' => url('/upload/image'), 'class' => 'form-inline', 'files' => true, 'autocomplete' => 'off', 'id' => 'upload']) !!}
				@include('_partials/_foto', ['default_image' => 'b.png', 'foto' => $data -> foto])
				{!! Form::close() !!}
				{!! config('custom.ketentuan.foto') !!}
				@endif
			</div>
			<div class="col-sm-9">
				{!! Form::model(new Siakad\Mahasiswa, ['class' => 'form-horizontal', 'role' => 'form', 'route' => ['mahasiswa.wisuda.daftar']]) !!}
				{!! Form::hidden('foto', $data -> foto, array('id' => 'foto')) !!}
				<div class="form-group">
					<label class="col-sm-2 control-label">Jadwal Wisuda</label>
					<div class="col-sm-9">
						@if($show)
						<p class="form-control-static"><strong>{{ $data -> wisuda -> nama }} ({{ formatTanggal(date('Y-m-d', strtotime($data -> wisuda -> tanggal))) }})</strong></p>
						@else
						{!! Form::select('wisuda_id', $wisuda, null, ['class' => 'form-control']) !!}
						@endif
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-2 control-label">Nama*</label>
					<div class="col-sm-9">
						<p class="form-control-static">{{ $data -> nama }}</p>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-2 control-label">NIM*</label>
					<div class="col-sm-9">
						<p class="form-control-static">{{ $data -> NIM }}</p>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-2 control-label">NIRM*</label>
					<div class="col-sm-9">
						<p class="form-control-static">{{ $data -> NIRM }}</p>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-2 control-label">PRODI*</label>
					<div class="col-sm-9">
						<p class="form-control-static">{{ $data -> prodi -> strata }} {{ $data -> prodi -> nama }}</p>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-2 control-label">Nama Ayah</label>
					<div class="col-sm-9">
						@if($show)
						<p class="form-control-static">{{ $data -> namaAyah }}</p>
						@else
						{!! Form::text('namaAyah', $data -> namaAyah, array('class' => 'form-control', 'placeholder' => 'Nama Ayah Kandung', 'required' => 'required')) !!}
						@endif
					</div>
				</div>
				<div class="form-group">
					{!! Form::label('tmpLahir', 'TTL', array('class' => 'col-sm-2 control-label')) !!}
					<div class="col-sm-10">
						@if($show)
						<p class="form-control-static">{{ $data -> tmpLahir }}, {{ $data -> tglLahir }}</p>
						@else
						<div style="display:inline-block;">
							{!! Form::text('tmpLahir', $data -> tmpLahir, array('class' => 'form-control', 'placeholder' => 'Tempat Lahir', 'required' => 'required')) !!}
						</div>
						<div style="display:inline-block;">
							{!! Form::text('tglLahir', $data -> tglLahir, array('class' => 'form-control date', 'placeholder' => 'Tanggal Lahir', 'required' => 'required')) !!}
						</div>
						@endif
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-2 control-label">Tinggi Badan</label>
					<div class="col-sm-9">
						@if($show)
						<p class="form-control-static">{{ $data -> tinggi_badan }}</p>
						@else
						<div class="input-group">
							{!! Form::text('tinggi_badan', $data -> tinggi_badan, array('class' => 'form-control', 'placeholder' => 'Tinggi Badan', 'required' => 'required')) !!}
							<span class="input-group-addon">cm</span>
						</div>
						@endif
					</div>
				</div>
				<div class="form-group">
					{!! Form::label('hp', 'No. HP', array('class' => 'col-sm-2 control-label')) !!}
					<div class="col-sm-5">
						@if($show)
						<p class="form-control-static">{{ $data -> hp }}</p>
						@else
						{!! Form::text('hp', $data -> hp, array('class' => 'form-control', 'placeholder' => 'Nomor HP', 'required' => 'required')) !!}
						@endif
					</div>
				</div>
				
				<div class="form-group has-feedback{{ $errors->has('kelurahan') ? ' has-error' : '' }}">
					{!! Form::label('jalan', 'Alamat:', array('class' => 'col-sm-2 control-label')) !!}
					<div class="col-sm-10">
						@if($show)
						<p class="form-control-static">{{ $alamat }}</p>
						@else
						<div style="display:inline-block;">
							{!! Form::text('jalan', $data -> jalan, array('class' => 'form-control', 'placeholder' => 'Jalan', 'style' => 'width: 150px')) !!}
						</div>
						<div style="display:inline-block;">
							{!! Form::text('rt', $data -> rt, array('class' => 'form-control', 'placeholder' => 'RT', 'style' => 'width: 80px')) !!}
						</div>
						<div style="display:inline-block;">
							{!! Form::text('rw', $data -> rw, array('class' => 'form-control', 'placeholder' => 'RW', 'style' => 'width: 80px')) !!}
						</div>
						<div style="display:inline-block;">
							{!! Form::text('dusun', $data -> dusun, array('class' => 'form-control', 'placeholder' => 'Dusun / Lingkungan', 'style' => 'width: 150px')) !!}
						</div>
						<div style="display:inline-block;">
							{!! Form::text('kelurahan', $data -> kelurahan, array('class' => 'form-control', 'placeholder' => 'Desa / Kelurahan', 'style' => 'width: 150px', 'required' => 'required')) !!}
						</div>
						<div style="display:inline-block;">
							{!! Form::select('id_wil', $wilayah, $data -> id_wil, array('class' => 'form-control chosen-select', 'data-placeholder' => 'Kecamatan')) !!}
						</div>
						<div style="display:inline-block;">
							{!! Form::text('kodePos', $data -> kodePos, array('class' => 'form-control', 'placeholder' => 'Kode Pos', 'style' => 'width: 150px')) !!}
						</div>
						@endif
					</div>
				</div>
				
				<div class="form-group">
					{!! Form::label('judul_skripsi', 'Judul Skripsi', array('class' => 'col-sm-2 control-label', 'required' => 'required')) !!}
					<div class="col-sm-10">
						@if($show)
						<p class="form-control-static">{{ $data -> judul_skripsi }}</p>
						{!! Form::hidden('judul_skripsi', $data -> judul_skripsi) !!}
						@else
						{!! Form::textarea('judul_skripsi', $data -> judul_skripsi , array('class' => 'form-control', 'rows' => '5', 'placeholder' => 'Judul Skripsi')) !!}
						@endif
					</div>
				</div>	
				<div class="form-group">
					{!! Form::label('dosen1_id', 'Pembimbing 1:', array('class' => 'col-sm-2 control-label')) !!}
					<div class="col-sm-5">
						@if($show)
						<p class="form-control-static">{{ $data -> dosen1_id ?? '-' }}</p>
						@else
						{!! Form::select('dosen1_id', $dosen, $data -> dosen1_id, ['class' => 'form-control chosen-select']) !!}
						@endif
					</div>
				</div>
				<div class="form-group">
					{!! Form::label('dosen2_id', 'Pembimbing 2:', array('class' => 'col-sm-2 control-label')) !!}
					<div class="col-sm-5">
						@if($show)
						<p class="form-control-static">{{ $data -> dosen2_id ?? '-' }}</p>
						@else
						{!! Form::select('dosen2_id', $dosen, $data -> dosen2_id, ['class' => 'form-control chosen-select']) !!}
						@endif
					</div>
				</div>
				{!! Form::hidden('statusMhs', '4') !!}
				
				@if(!$admin)
				@if($show)
				<p class="help-block">Jika terdapat kesalahan data, silahkan menghubungi Bagian Akademik.</p>
				@else		
				<div class="form-group">
					<div class="col-sm-offset-2 col-sm-10">
						<p class="help-block">
							*: Jika terdapat kesalahan data, silahkan menghubungi Bagian Akademik. Pastikan seluruh data sudah diisi dengan benar, data yang sudha masuk tidak dapat diubah lagi.<br/>
						</p>
					</div>		
				</div>
				@endif
				@endif
				
				@if($show)
				@if($admin)
				<a href="{{ route('mahasiswa.wisuda.peserta.cetak', [$data -> wisuda -> id, $data -> id]) }}" class="btn btn-info btn-flat" ><i class="fa fa-print"></i> Cetak</a>
				@else	
				<a href="{{ route('mahasiswa.wisuda.peserta.cetak2') }}" class="btn btn-info btn-flat" ><i class="fa fa-print"></i> Cetak</a>
				@endif
				@else		
				<div class="form-group">
					<div class="col-sm-offset-2 col-sm-10">
						<button class="btn btn-primary btn-flat" type="submit"><i class="fa fa-floppy-o"></i> Simpan</button>
					</div>		
				</div>
				@endif
				{!! Form::close() !!}
			</div>
		</div>
		@endif
		<br/>
		<br/>
	</div>
</div>
@endsection																																																												