@extends('app')

@section('title')
Pendaftaran PPL
@endsection

@section('header')
@if($admin)
<section class="content-header">
	<h1>
		PPL
		<small>Detail Peserta</small>
	</h1>
	<ol class="breadcrumb">
		<li><a href="/"><i class="fa fa-dashboard"></i> Home</a></li>
		<li><a href="{{ url('/ppl') }}"> Data PPL</a></li>
		<li><a href="{{ route('mahasiswa.ppl.lokasi.peserta.index', $ppl -> id) }}"> Peserta PPL</a></li>
		<li class="active">Detail Peserta</li>
	</ol>
</section>
@else
<section class="content-header">
	<h1>
		Mahasiswa
		<small>Pendaftaran PPL</small>
	</h1>
	<ol class="breadcrumb">
		<li><a href="/"><i class="fa fa-dashboard"></i> Home</a></li>
		<li class="active">Pendaftaran PPL</li>
	</ol>
</section>
@endif
@endsection


@if(!$show)

@push('styles')
<link rel="stylesheet" href="{{ asset('/css/datepicker.min.css') }}">
<link rel="stylesheet" href="{{ asset('/css/chosen.min.css') }}">
<style>
	.form-horizontal .control-label {
	text-align: left;
	}
	.form-control-static:before, .form-control:before{
	content: ":  ";
	}
	.inline{
	display:inline-block;
	width: 80%;
	}
	.content ol{
	margin: 0px;
	padding: 0px;
	display: inline-block;
	vertical-align: top;
	width: 80%;
	}
	.content ol li{
	margin-left: 15px;
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

@push('scripts')
<script src="{{ asset('/js/chosen.jquery.min.js') }}"></script>
<script>
	$(function(){
		$(".chosen-select").chosen({no_results_text: "Tidak ditemukan hasil pencarian untuk: "});
	});
</script>
<script src="{{ asset('/js/datepicker.min.js') }}"></script>
<script>
	$(function(){
		$(".date").datepicker({
			format:"dd-mm-yyyy", 
			autoHide:true,
			daysMin: ['Mg', 'Sn', 'Sl', 'Rb', 'Km', 'Jm', 'Sa'],
			monthsShort: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des']
		});
	});
</script>
<script>	
	var add1 = '<div class="addition"><div class="inline"><input class="form-control" placeholder="Kemampuan" name="kemampuan[]" type="text"></div><button class="btn btn-danger btn-xs btn-flat btn-remove" type="button"><i class="fa fa-minus"></i></button></div>';
	var add2 = '<div class="addition"><div class="inline"><input class="form-control" placeholder="Kekurangan" name="kekurangan[]" type="text"></div><button class="btn btn-danger btn-xs btn-flat btn-remove" type="button"><i class="fa fa-minus"></i></button></div>';
	$(document).on('click', '.btn-remove', function()
	{
		$(this).closest('.addition').remove();
	});
	
	$(document).on('click', '.btn-add', function()
	{
		if($(this).hasClass('kemampuan'))
		{
			$(this).closest('p').before(add1);
			$('input[type="text"][name="kemampuan[]"]:last').select();
		}
		else if($(this).hasClass('kekurangan'))
		{
			$(this).closest('p').before(add2);
			$('input[type="text"][name="kekurangan[]"]:last').select();
		}
	});
</script>
@endpush
@endif

@section('content')
<div class="box box-primary">
	<div class="box-header with-border">
		@if($admin)
		<h3 class="box-title">Detail Peserta</h3>
		@else
		<h3 class="box-title">Pendaftaran PPL</h3>
		@endif
	</div>
	<div class="box-body">
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
				{!! Form::model(new Siakad\Mahasiswa, ['class' => 'form-horizontal', 'role' => 'form', 'route' => ['mahasiswa.ppl.daftar']]) !!}
				{!! Form::hidden('foto', $data -> foto, array('id' => 'foto')) !!}
				{!! Form::hidden('matkul_id', $ppl -> matkul_id) !!}
				
				<div class="form-group">
					<label class="col-sm-2 control-label">Periode PPL :</label>
					<div class="col-sm-9">
						<p class="form-control-static">
							<strong>{{ $ppl -> tapel -> nama }} ({{ formatTanggal(date('Y-m-d', strtotime($ppl -> tanggal_mulai))) }} - {{ formatTanggal(date('Y-m-d', strtotime($ppl -> tanggal_selesai))) }})</strong>
						</p>
					</div>
				</div>
				
				<div class="form-group">
					<label class="col-sm-2 control-label">Tempat PPL :</label>
					<div class="col-sm-6">
					@if($show)
						<p class="form-control-static">{{ $lokasi[$lokasi_ppl_mhs -> ppl_lokasi_id] }}</p>
					@else
						{!! Form::select('ppl_lokasi_id', $lokasi, null, array('class' => 'form-control', 'data-placeholder' => 'Pilih Lokasi')) !!}
					@endif
					</div>
				</div>
				
				<div class="form-group">
					<label class="col-sm-2 control-label">PRODI* :</label>
					<div class="col-sm-9">
						<p class="form-control-static">{{ $data -> prodi -> strata }} {{ $data -> prodi -> nama }} ({{ $data -> kelas -> nama }})</p>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-2 control-label">NIM* :</label>
					<div class="col-sm-9">
						<p class="form-control-static">{{ $data -> NIM }}</p>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-2 control-label">NIRM* :</label>
					<div class="col-sm-9">
						<p class="form-control-static">{{ $data -> NIRM }}</p>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-2 control-label">Nama* :</label>
					<div class="col-sm-9">
						<p class="form-control-static">{{ $data -> nama }}</p>
					</div>
				</div>
				<div class="form-group">
					{!! Form::label('tmpLahir', 'TTL :', array('class' => 'col-sm-2 control-label')) !!}
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
						<span class="help-block">Sesuaikan dengan Ijazah</span>
						@endif
					</div>
				</div>
				<div class="form-group">
					{!! Form::label('statusWrgNgr', 'Status Kw :', array('class' => 'col-sm-2 control-label')) !!}
					<div class="col-sm-8">
						<?php
							if($show)
							{
								echo '<p class="form-control-static">' . config('custom.pilihan.statusWrgNgr')[$data -> statusWrgNgr] . '</p>';
							}	
							else
							{
								foreach(config('custom.pilihan.statusWrgNgr') as $k => $v) 
								{
									echo '<label class="radio-inline">';
									echo '<input type="radio" name="statusWrgNgr" ';
									if(isset($data) and $k == $data -> statusWrgNgr) echo 'checked="checked" ';
									echo 'value="'. $k .'"> '. $v .' :</label>';
								}
							}
						?>
					</div>
				</div>				
				<div class="form-group">
					{!! Form::label('wargaNegara', 'Kewarganegaraan :', array('class' => 'col-sm-2 control-label')) !!}
					<div class="col-sm-5">
						@if($show)
						<p class="form-control-static">{{ $data -> kewarganegaraan -> nama }}</p>
						@else
						{!! Form::select('wargaNegara', $negara, 'ID', array('class' => 'form-control chosen-select', 'data-placeholder' => 'Pilih Negara')) !!}
						@endif
					</div>
				</div>
				<div class="form-group">
					{!! Form::label('agama', 'Agama :', array('class' => 'col-sm-2 control-label')) !!}
					<div class="col-sm-10">
						<?php
							if($show)
							{
								echo '<p class="form-control-static">' . config('custom.pilihan.agama')[$data -> agama] . '</p>';
							}	
							else
							{
								foreach(config('custom.pilihan.agama') as $k => $v) 
								{
									echo '<label class="radio-inline">';
									echo '<input type="radio" name="agama" ';
									if(isset($data) and $k == $data -> agama) echo 'checked="checked" ';
									echo 'value="'. $k .'"> '. $v .' :</label>';
								}
							}
						?>
					</div>
				</div>
				<div class="form-group">
					{!! Form::label('statusSipil', 'Status Sipil :', array('class' => 'col-sm-2 control-label')) !!}
					<div class="col-sm-8">
						<?php
							if($show)
							{
								echo '<p class="form-control-static">' . config('custom.pilihan.statusSipil')[$data -> statusSipil] . '</p>';
							}	
							else
							{
								foreach(config('custom.pilihan.statusSipil') as $k => $v) 
								{
									echo '<label class="radio-inline">';
									echo '<input type="radio" name="statusSipil" ';
									if(isset($data) and $k == $data -> statusSipil) echo 'checked="checked" ';
									echo 'value="'. $k .'"> '. $v .' :</label>';
									}
							}
						?>
					</div>
				</div>
				
				<div class="form-group has-feedback{{ $errors->has('kelurahan') ? ' has-error' : '' }}">
					{!! Form::label('jalan', 'Alamat :', array('class' => 'col-sm-2 control-label')) !!}
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
					{!! Form::label('hp', 'No. HP :', array('class' => 'col-sm-2 control-label')) !!}
					<div class="col-sm-5">
						@if($show)
						<p class="form-control-static">{{ $data -> hp }}</p>
						@else
						{!! Form::text('hp', $data -> hp, array('class' => 'form-control', 'placeholder' => 'Nomor HP', 'required' => 'required')) !!}
						@endif
					</div>
				</div>
				<div class="form-group">
					{!! Form::label('kemampuan', 'Kemampuan Spesifik :', array('class' => 'col-sm-2 control-label', 'required' => 'required')) !!}
					<div class="col-sm-10">
						@if($show)
						<div class="form-control-static">
							<?php
								if($data -> kemampuan != '')
								{
									$k = explode('[]', $data -> kemampuan);
									echo '<ol>';
									foreach($k as $l)
									{
										if($l != '') echo '<li>' . $l . '</li>';
									}
									echo '</ol>';
								}
							?>
						</div>
						@else
						<?php
							if($data -> kemampuan != '')
							{
								$k = explode('[]', $data -> kemampuan);
								$c = 1;
								foreach($k as $l)
								{
									if($l != '') 
									{
										echo '
										<div class="inline">
										<input class="form-control" placeholder="Kemampuan" name="kemampuan[]" type="text" value="'. $l .'">
										</div>
										';
									}
								}
							}
							else
							{
								echo '
								<div class="inline">
								<input class="form-control" placeholder="Kemampuan" name="kemampuan[]" type="text">
								</div>
								';
							}
						?>
						<p>
							<button class="btn btn-primary btn-xs btn-flat btn-add kemampuan" type="button"><i class="fa fa-plus"></i></button>
						</p>
						@endif
					</div>
				</div>	
				<div class="form-group">
					{!! Form::label('kekurangan', 'Kekurangan Pribadi :', array('class' => 'col-sm-2 control-label', 'required' => 'required')) !!}
					<div class="col-sm-10">
						@if($show)
						<div class="form-control-static">
							<?php
								if($data -> kekurangan != '')
								{
									$k = explode('[]', $data -> kekurangan);
									echo '<ol>';
									foreach($k as $l)
									{
										if($l != '') echo '<li>' . $l . '</li>';
									}
									echo '</ol>';
								}
							?>
						</div>
						@else
						<?php
							if($data -> kekurangan != '')
							{
								$k = explode('[]', $data -> kekurangan);
								$c = 1;
								foreach($k as $l)
								{
									if($l != '') 
									{
										echo '
										<div class="inline">
										<input class="form-control" placeholder="kekurangan" name="kekurangan[]" type="text" value="'. $l .'">
										</div>';
									}
								}
							}
							else
							{
								echo '
								<div class="inline">
								<input class="form-control" placeholder="kekurangan" name="kekurangan[]" type="text">
								</div>';
							}
						?>
						<p>
							<button class="btn btn-primary btn-xs btn-flat btn-add kekurangan" type="button"><i class="fa fa-plus"></i></button>
						</p>
						@endif
					</div>
				</div>	
				
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
				<a href="{{ route('mahasiswa.ppl.daftar.cetak', [$lokasi_ppl_mhs -> ppl_lokasi_id, $data -> id]) }}" class="btn btn-info btn-flat" ><i class="fa fa-print"></i> Cetak</a>				
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
		<br/>
		<br/>
	</div>
</div>
@endsection																																																																																																					