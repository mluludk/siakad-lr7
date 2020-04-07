@extends('app')

@section('title')
Edit Pengaturan
@endsection

@section('header')
<section class="content-header">
	<h1>
		Pengaturan
		<small>Ubah Data</small>
	</h1>
	<ol class="breadcrumb">
		<li><a href="/"><i class="fa fa-dashboard"></i> Home</a></li>
		<li class="active">Ubah Data</li>
	</ol>
</section>
@endsection

@push('styles')
<style>
	.upload {
	width: 0.1px;
	height: 0.1px;
	opacity: 0;
	overflow: hidden;
	position: absolute;
	z-index: 122;
	}
	.upload + label {
    display: inline-block;
	cursor: pointer;
	}
	.upload:focus + label {
	outline: 1px dotted #000;
	outline: -webkit-focus-ring-color auto 5px;
	}
	.upload + label * {
	pointer-events: none;
	}
	
	.inline{
	display: inline-block;
	margin-bottom: 3px;
	}
	
	.tab-pane{
	margin: 20px 0;
	}
	
	.checkbox-inline+.checkbox-inline {
	margin-top: 0;
	margin-left: 0;
	margin-right: 10px;
	}
	.checkbox-inline:not(first-child){
	margin-right: 10px;
	}
</style>
<link rel="stylesheet" href="{{ asset('/css/datepicker.min.css') }}">
@endpush

@push('scripts')
<!--
<script src="{{ asset('js/jquery-ui.min.js') }}"></script>
-->
<script src="{{ asset('/js/datepicker.min.js') }}"></script>
<script>
	var inputs = document.querySelectorAll( '.upload' );
	Array.prototype.forEach.call( inputs, function( input )
	{
		var label	 = input.nextElementSibling,
		labelVal = label.innerHTML;
		
		input.addEventListener( 'change', function( e )
		{
			var fileName = '';
			if( this.files && this.files.length > 1 )
			fileName = ( this.getAttribute( 'data-multiple-caption' ) || '' ).replace( '{count}', this.files.length );
			else
			fileName = e.target.value.split( '\\' ).pop();
			
			if( fileName )
			label.querySelector( 'span' ).innerHTML = fileName;
			else
			label.innerHTML = labelVal;
		});
	});
	
	$(function(){
		$(".date").datepicker({
			format:"dd-mm-yyyy", 
			autoHide:true,
			daysMin: ['Mg', 'Sn', 'Sl', 'Rb', 'Km', 'Jm', 'Sa'],
			monthsShort: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des']
			});
	});
</script>
@endpush

@section('content')
<div class="box box-warning">
	<div class="box-header with-border">
		<h3 class="box-title">Ubah Pengaturan</h3>
	</div>
	<div class="box-body">
		<form method="post" action="{{ route('config.update') }}" enctype="multipart/form-data">
			<input type="hidden" name="_method" value="PATCH" />
			<input type="hidden" name="_token" value="{{ csrf_token() }}" />
			<ul class="nav nav-tabs">
				<li class="active"><a href="#profil" data-toggle="tab">Profil</a></li>
				<li><a href="#informasi" data-toggle="tab">Informasi</a></li>
				<li><a href="#akta" data-toggle="tab">Akta Pendirian</a></li>
				<li><a href="#mahasiswa" data-toggle="tab">Mahasiswa</a></li>
				<li><a href="#kuesioner" data-toggle="tab">Kuesioner</a></li>
				<li><a href="#keuangan" data-toggle="tab">Keuangan</a></li>
				<li><a href="#kartu" data-toggle="tab">Kartu</a></li>
				<li><a href="#ttd" data-toggle="tab">TTD</a></li>
				<li><a href="#login" data-toggle="tab">Login</a></li>
				<li><a href="#app" data-toggle="tab">Aplikasi</a></li>
				<li><a href="#api" data-toggle="tab">FEEDER</a></li>
				<li><a href="#maintenis" data-toggle="tab">Maintenis</a></li>
			</ul>
			
			<div class="tab-content">
				
				<div class="tab-pane fade form-horizontal" id="api">	
					<div class="form-group">
						{!! Form::label('', 'PDDIKTI', array('class' => 'col-sm-2 control-label')) !!}
						<div class="col-sm-2"></div>
					</div>
					<div class="form-group">
						{!! Form::label('feeder.host', 'URL:', array('class' => 'col-sm-2 control-label')) !!}
						<div class="col-sm-9">
							<div style="display: inline-block; width: 200px;">
								<input type="text" class="form-control" name="feeder.host" placeholder="URL" value="{{ $configs['feeder']['host'] ?? '' }}">
							</div>
							<div style="display: inline-block; width: 200px;">
								<input type="text" class="form-control" name="feeder.port" placeholder="Port" value="{{ $configs['feeder']['port'] ?? '' }}">
							</div>
						</div>
					</div>	
					<div class="form-group">
						{!! Form::label('feeder.username', 'Username:', array('class' => 'col-sm-2 control-label')) !!}
						<div class="col-sm-2">
							<input type="text" class="form-control" name="feeder.username" placeholder="Username" value="{{ $configs['feeder']['username'] ?? '' }}">
						</div>
					</div>	
					<div class="form-group">
						{!! Form::label('feeder.password', 'Password:', array('class' => 'col-sm-2 control-label')) !!}
						<div class="col-sm-2">
							<input type="text" class="form-control" name="feeder.password" placeholder="Password" value="{{ $configs['feeder']['password'] ?? '' }}">
						</div>
					</div>	
				</div>
				
				<div class="tab-pane fade form-horizontal" id="ttd">					
					<div class="form-group">
						{!! Form::label('ttd.transkrip.kiri', 'TTD Transkrip Nilai Kiri*:', array('class' => 'col-sm-2 control-label')) !!}
						<div class="col-sm-8">
							<textarea class="form-control" name="ttd.transkrip.kiri" placeholder="" rows="7">{{ $configs['ttd']['transkrip']['kiri'] ?? '' }}</textarea>
						</div>
					</div>	
					<div class="form-group">
						{!! Form::label('ttd.transkrip.kanan', 'TTD Transkrip Nilai Kanan*:', array('class' => 'col-sm-2 control-label')) !!}
						<div class="col-sm-8">
							<textarea class="form-control" name="ttd.transkrip.kanan" placeholder="" rows="7">{{ $configs['ttd']['transkrip']['kanan'] ?? '' }}</textarea>
						</div>
					</div>
					<div class="form-group">
						{!! Form::label('ttd.khs.semua.kanan', 'TTD KHS Semua Semester Kanan*:', array('class' => 'col-sm-2 control-label')) !!}
						<div class="col-sm-8">
							<textarea class="form-control" name="ttd.khs.semua.kanan" placeholder="" rows="7">{{ $configs['ttd']['khs']['semua']['kanan'] ?? '' }}</textarea>
						</div>
					</div>
					<div class="form-group">
						{!! Form::label('ttd.biaya.kwitansi.kanan', 'TTD Kwitansi Biaya Kanan*:', array('class' => 'col-sm-2 control-label')) !!}
						<div class="col-sm-8">
							<textarea class="form-control" name="ttd.biaya.kwitansi.kanan" placeholder="" rows="7">{{ $configs['ttd']['biaya']['kwitansi']['kanan'] ?? '' }}</textarea>
						</div>
					</div>
					<div class="form-group">
						{!! Form::label('ttd.biaya.status.kiri', 'TTD Status Biaya Kiri*:', array('class' => 'col-sm-2 control-label')) !!}
						<div class="col-sm-8">
							<textarea class="form-control" name="ttd.biaya.status.kiri" placeholder="" rows="7">{{ $configs['ttd']['biaya']['status']['kiri'] ?? '' }}</textarea>
						</div>
					</div>
					<div class="col-sm-offset-2">
						<span class="help-block">* Tag HTML yang diperbolehkan: &lt;br/&gt;, &lt;b&gt;, &lt;i&gt;, &lt;u&gt;, &lt;strong&gt; , &lt;center&gt; , &lt;ol&gt; , &lt;ul&gt; , &lt;li&gt; dan &lt;em&gt;</span>	
					</div>
				</div>
				
				<div class="tab-pane fade form-horizontal" id="keuangan">
					<div class="form-group">
						{!! Form::label('jenisPembayaran', 'Jenis Pembayaran:', array('class' => 'col-sm-2 control-label')) !!}
						<div class="col-sm-9">
							<?php
								$jenis = implode(';', $configs['pilihan']['jenisPembayaran']);
							?>
							<textarea class="form-control" name="jenisPembayaran" placeholder="..." cols="50" rows="5">{{ $jenis ?? '' }}</textarea>
							<span class="help-block">Pisahkan data dengan tanda titik-koma (;). Untuk menjaga konsistensi data, tambahkan data baru di <strong>BELAKANG</strong> data yang sudah ada.</span>
						</div>
					</div>
				</div>
				
				<div class="tab-pane fade form-horizontal" id="login">
					<div class="form-group">
						{!! Form::label('info.login', 'Informasi:', array('class' => 'col-sm-2 control-label')) !!}
						<div class="col-sm-9">
							<textarea class="form-control" name="info.login" placeholder="..." cols="50" rows="5">{{ $configs['info']['login'] ?? '' }}</textarea>
						</div>
					</div>
					
					<div class="form-group">
						{!! Form::label('user.reset-password', 'Reset Password:', array('class' => 'col-sm-2 control-label')) !!}
						<div class="col-sm-9">
							<?php
								foreach(['Tidak Aktif', 'Aktif'] as $k => $v) 
								{
									echo '<label class="radio-inline">';
									echo '<input type="radio" name="user.reset-password" ';
									if(isset($configs['user']['reset-password']) and $k == $configs['user']['reset-password']) echo 'checked="checked" ';
									echo 'value="'. $k .'"> '. $v .'</label>';
								}
							?>
						</div>
					</div>
				</div>
				
				<div class="tab-pane fade in active form-horizontal" id="profil">
					<div class="form-group">
						{!! Form::label('profil.kode', 'Kode PT:', array('class' => 'col-sm-2 control-label')) !!}
						<div class="col-sm-2">
							<input type="text" class="form-control" name="profil.kode" placeholder="Kode Perguruan Tinggi" value="{{ $configs['profil']['kode'] ?? '' }}">
						</div>
					</div>
					<div class="form-group">
						{!! Form::label('profil.nama', 'Nama PT:', array('class' => 'col-sm-2 control-label')) !!}
						<div class="col-sm-8">
							<input type="text" class="form-control" name="profil.nama" placeholder="Nama Perguruan Tinggi" value="{{ $configs['profil']['nama'] ?? '' }}">
						</div>
					</div>
					<div class="form-group">
						{!! Form::label('profil.singkatan', 'Singkatan:', array('class' => 'col-sm-2 control-label')) !!}
						<div class="col-sm-2">
							<input type="text" class="form-control" name="profil.singkatan" placeholder="Singkatan" value="{{ $configs['profil']['singkatan'] ?? '' }}">
						</div>
					</div>
					<div class="form-group">
						{!! Form::label('profil.rektor', 'Rektor:', array('class' => 'col-sm-2 control-label')) !!}
						<div class="col-sm-4">
							<input type="text" class="form-control" name="profil.rektor" placeholder="Nama Rektor" value="{{ $configs['profil']['rektor'] ?? '' }}">
						</div>
					</div>
					<div class="form-group">
						{!! Form::label('profil.alamat.jalan', 'Alamat:', array('class' => 'col-sm-2 control-label')) !!}
						<div class="col-sm-10">
							<div class="inline">
								<input type="text" class="form-control" name="profil.alamat.jalan" placeholder="Jalan" value="{{ $configs['profil']['alamat']['jalan'] ?? '' }}" style='width: 150px'>
							</div>
							<div class="inline">
								<input type="text" class="form-control" name="profil.alamat.rt" placeholder="RT" value="{{ $configs['profil']['alamat']['rt'] ?? '' }}" style='width: 80px'>
							</div>
							<div class="inline">
								<input type="text" class="form-control" name="profil.alamat.rw" placeholder="RW" value="{{ $configs['profil']['alamat']['rw'] ?? '' }}" style='width: 80px'>
							</div>
							<div class="inline">
								<input type="text" class="form-control" name="profil.alamat.dusun" placeholder="Dusun / Lingkungan" value="{{ $configs['profil']['alamat']['dusun'] ?? '' }}" style='width: 150px'>
							</div>
							<div class="inline">
								<input type="text" class="form-control" name="profil.alamat.kelurahan" placeholder="Desa / Kelurahan" value="{{ $configs['profil']['alamat']['kelurahan'] ?? '' }}" style='width: 150px'>
							</div>
							<div class="inline">
								<input type="text" class="form-control" name="profil.alamat.kodepos" placeholder="Kode Pos" value="{{ $configs['profil']['alamat']['kodepos'] ?? '' }}" style='width: 150px'>
							</div>
							<div class="inline">
								<input type="text" class="form-control" name="profil.alamat.kecamatan" placeholder="Kecamatan" value="{{ $configs['profil']['alamat']['kecamatan'] ?? '' }}" style='width: 150px'>
							</div>
							<div class="inline">
								<input type="text" class="form-control" name="profil.alamat.kabupaten" placeholder="Kota / Kabupaten" value="{{ $configs['profil']['alamat']['kabupaten'] ?? '' }}" style='width: 150px'>
							</div>
							<div class="inline">
								<input type="text" class="form-control" name="profil.alamat.propinsi" placeholder="Propinsi" value="{{ $configs['profil']['alamat']['propinsi'] ?? '' }}" style='width: 163px'>
							</div>
							<div class="inline">
								<input type="text" class="form-control" name="profil.alamat.lintang" placeholder="Koordinat Lintang" value="{{ $configs['profil']['alamat']['lintang'] ?? '' }}" style='width: 163px'>
							</div>
							<div class="inline">
								<input type="text" class="form-control" name="profil.alamat.bujur" placeholder="Koordinat Bujur" value="{{ $configs['profil']['alamat']['bujur'] ?? '' }}" style='width: 163px'>
							</div>
						</div>
					</div>
					<div class="form-group">
						{!! Form::label('profil.telepon', 'Telepon:', array('class' => 'col-sm-2 control-label')) !!}
						<div class="col-sm-4">
							<input type="text" class="form-control" name="profil.telepon" placeholder="Telepon" value="{{ $configs['profil']['telepon'] ?? '' }}">
						</div>
					</div>
					<div class="form-group">
						{!! Form::label('profil.fax', 'Fax:', array('class' => 'col-sm-2 control-label')) !!}
						<div class="col-sm-4">
							<input type="text" class="form-control" name="profil.fax" placeholder="Faximile" value="{{ $configs['profil']['fax'] ?? '' }}">
						</div>
					</div>
					<div class="form-group">
						{!! Form::label('profil.email', 'Email:', array('class' => 'col-sm-2 control-label')) !!}
						<div class="col-sm-6">
							<input type="text" class="form-control" name="profil.email" placeholder="Email" value="{{ $configs['profil']['email'] ?? '' }}">
						</div>
					</div>
					<div class="form-group">
						{!! Form::label('profil.website', 'Website:', array('class' => 'col-sm-2 control-label')) !!}
						<div class="col-sm-3">
							<input type="text" class="form-control" name="profil.website" placeholder="Website" value="{{ $configs['profil']['website'] ?? '' }}">
						</div>
					</div>
					<div class="form-group">
						{!! Form::label('profil.facebook', 'Fabecook:', array('class' => 'col-sm-2 control-label')) !!}
						<div class="col-sm-3">
							<input type="text" class="form-control" name="profil.facebook" placeholder="Facebook" value="{{ $configs['profil']['facebook'] ?? '' }}">
						</div>
					</div>
					<div class="form-group">
						{!! Form::label('profil.twitter', 'Twtiter:', array('class' => 'col-sm-2 control-label')) !!}
						<div class="col-sm-3">
							<input type="text" class="form-control" name="profil.twitter" placeholder="Twitter" value="{{ $configs['profil']['twitter'] ?? '' }}">
						</div>
					</div>
				</div>
				<div class="tab-pane fade form-horizontal" id="informasi">
					<div class="form-group">
						{!! Form::label('profil.informasi.bank', 'Bank:', array('class' => 'col-sm-2 control-label')) !!}
						<div class="col-sm-4">
							<input type="text" class="form-control" name="profil.informasi.bank" placeholder="Bank" value="{{ $configs['profil']['informasi']['bank'] ?? '' }}">
						</div>
					</div>					
					<div class="form-group">
						{!! Form::label('profil.informasi.unit', 'Unit Cabang:', array('class' => 'col-sm-2 control-label')) !!}
						<div class="col-sm-4">
							<input type="text" class="form-control" name="profil.informasi.unit" placeholder="Unit Cabang" value="{{ $configs['profil']['informasi']['unit'] ?? '' }}">
						</div>
					</div>		
					<div class="form-group">
						{!! Form::label('profil.informasi.no-rekening', 'No. Rekening:', array('class' => 'col-sm-2 control-label')) !!}
						<div class="col-sm-4">
							<input type="text" class="form-control" name="profil.informasi.no-rekening" placeholder="Nomor Rekening" value="{{ $configs['profil']['informasi']['no-rekening'] ?? '' }}">
						</div>
					</div>		
					<div class="form-group">
						{!! Form::label('profil.informasi.mbs', 'MBS:', array('class' => 'col-sm-2 control-label')) !!}
						<div class="col-sm-4">
							<input type="text" class="form-control" name="profil.informasi.mbs" placeholder="MBS" value="{{ $configs['profil']['informasi']['mbs'] ?? '' }}">
						</div>
					</div>		
					<div class="form-group">
						{!! Form::label('profil.informasi.luas-tanah-milik', 'Luas tanah milik:', array('class' => 'col-sm-2 control-label')) !!}
						<div class="col-sm-4">
							<input type="text" class="form-control" name="profil.informasi.luas-tanah-milik" placeholder="Luas Tanah Milik" value="{{ $configs['profil']['informasi']['luas-tanah-milik'] ?? '' }}">
						</div>
					</div>		
					<div class="form-group">
						{!! Form::label('profil.informasi.luas-tanah-bukan-milik', 'Luas tanah bukan milik:', array('class' => 'col-sm-2 control-label')) !!}
						<div class="col-sm-4">
							<input type="text" class="form-control" name="profil.informasi.luas-tanah-bukan-milik" placeholder="Luas Tanah Bukan Milik" value="{{ $configs['profil']['informasi']['luas-tanah-bukan-milik'] ?? '' }}">
						</div>
					</div>					
				</div>		
				<div class="tab-pane fade form-horizontal" id="akta">
					<div class="form-group">
						{!! Form::label('profil.akta-pendirian.sk-pend', 'No. SK Pendirian:', array('class' => 'col-sm-2 control-label')) !!}
						<div class="col-sm-4">
							<input type="text" class="form-control" name="profil.akta-pendirian.sk-pend" placeholder="SK Pendirian" value="{{ $configs['profil']['akta-pendirian']['sk-pend'] ?? '' }}">
						</div>
					</div>	
					<div class="form-group">
						{!! Form::label('profil.akta-pendirian.tgl-sk-pend', 'Tanggal SK Pendirian:', array('class' => 'col-sm-2 control-label')) !!}
						<div class="col-sm-2">
							<input type="text" class="form-control date" name="profil.akta-pendirian.tgl-sk-pend" placeholder="Tanggal SK Pendirian" value="{{ $configs['profil']['akta-pendirian']['tgl-sk-pend'] ?? '' }}">
						</div>
					</div>	
					<div class="form-group">
						{!! Form::label('profil.akta-pendirian.status-kep', 'Status Kepemilikan:', array('class' => 'col-sm-2 control-label')) !!}
						<div class="col-sm-4">
							<input type="text" class="form-control" name="profil.akta-pendirian.status-kep" placeholder="Status Kepemilikan" value="{{ $configs['profil']['akta-pendirian']['status-kep'] ?? '' }}">
						</div>
					</div>	
					<div class="form-group">
						{!! Form::label('profil.akta-pendirian.status-pt', 'Status Perguruan Tinggi:', array('class' => 'col-sm-2 control-label')) !!}
						<div class="col-sm-4">
							<input type="text" class="form-control" name="profil.akta-pendirian.status-pt" placeholder="Status Perguruan Tinggi" value="{{ $configs['profil']['akta-pendirian']['status-pt'] ?? '' }}">
						</div>
					</div>	
					<div class="form-group">
						{!! Form::label('profil.akta-pendirian.sk-ijin-ops', 'SK Ijin Operasional:', array('class' => 'col-sm-2 control-label')) !!}
						<div class="col-sm-4">
							<input type="text" class="form-control" name="profil.akta-pendirian.sk-ijin-ops" placeholder="SK Ijin Operasional" value="{{ $configs['profil']['akta-pendirian']['sk-ijin-ops'] ?? '' }}">
						</div>
					</div>	
					<div class="form-group">
						{!! Form::label('profil.akta-pendirian.tgl-sk-ijin-ops', 'Tanggal SK  Ijin Operasional:', array('class' => 'col-sm-2 control-label')) !!}
						<div class="col-sm-2">
							<input type="text" class="form-control date" name="profil.akta-pendirian.tgl-sk-ijin-ops" placeholder="Tanggal SK Ijin Operasional" value="{{ $configs['profil']['akta-pendirian']['tgl-sk-ijin-ops'] ?? '' }}">
						</div>
					</div>						
				</div>
				
				<div class="tab-pane fade form-horizontal" id="app">
					<div class="form-group">
						{!! Form::label('app.title', 'Judul Aplikasi:', array('class' => 'col-sm-2 control-label')) !!}
						<div class="col-sm-8">
							<input type="text" class="form-control" name="app.title" placeholder="" value="{{ $configs['app']['title'] ?? '' }}">
						</div>
					</div>		
					<div class="form-group">
						{!! Form::label('favicon_image', 'Favicon:', array('class' => 'col-sm-2 control-label')) !!}
						<div class="col-sm-3">
							<div class="file-upload">
								<img src="{{ url('favicon192.png') }}" style="width: 64px;">
								<input id="favicon" class="upload" data-multiple-caption="Terdapat {count} file terpilih" name="favicon" type="file">
								<label for="favicon" class="btn btn-danger btn-flat upload-label"><i class="fa fa-search" id="upload-icon"></i> <span id="filename">Pilih file gambar...</span></label>
							</div>
						</div>
					</div>
					<div class="form-group">
						{!! Form::label('logo_image', 'Logo:', array('class' => 'col-sm-2 control-label')) !!}
						<div class="col-sm-3">
							<div class="file-upload">
								<img src="{{ url('/images/logo.png') }}" style="width: 128px;">
								<input id="logo" class="upload" data-multiple-caption="Terdapat {count} file terpilih" name="logo" type="file">
								<label for="logo" class="btn btn-danger btn-flat upload-label"><i class="fa fa-search" id="upload-icon"></i> <span id="filename">Pilih file gambar...</span></label>
							</div>
						</div>
					</div>
					<div class="form-group">
						{!! Form::label('header_image', 'Kop:', array('class' => 'col-sm-2 control-label')) !!}
						<div class="col-sm-10">
							<div class="file-upload">
								<img src="{{ url('/images/header.png') }}" style="width: 100%;">
								<input id="header" class="upload" data-multiple-caption="Terdapat {count} file terpilih" name="header" type="file">
								<label for="header" class="btn btn-danger btn-flat upload-label"><i class="fa fa-search" id="upload-icon"></i> <span id="filename">Pilih file gambar...</span></label>
							</div>
						</div>
					</div>
				</div>
				
				<div class="tab-pane fade form-horizontal" id="kartu">
					<div class="form-group">
						{!! Form::label('kartu.ktm.header.1', 'Header KTM Depan:', array('class' => 'col-sm-2 control-label')) !!}
						<div class="col-sm-3">
							<input type="text" class="form-control" name="kartu.ktm.header.1" placeholder="Header 1" value="{{ $configs['kartu']['ktm']['header'][1] ?? '' }}"/>
						</div>
						<div class="col-sm-3">
							<input type="text" class="form-control" name="kartu.ktm.header.2" placeholder="Header 2" value="{{ $configs['kartu']['ktm']['header'][2] ?? '' }}"/>
						</div>
						<div class="col-sm-4">
							<input type="text" class="form-control" name="kartu.ktm.header.3" placeholder="Header 3" value="{{ $configs['kartu']['ktm']['header'][3] ?? '' }}"/>
						</div>
					</div>
					<div class="form-group">
						{!! Form::label('kartu.ktm.header.4', 'Header KTM Belakang:', array('class' => 'col-sm-2 control-label')) !!}
						<div class="col-sm-10">
							<div class="row">
								<div class="col-sm-5">
									<input type="text" class="form-control" name="kartu.ktm.header.4" placeholder="Header 1" value="{{ $configs['kartu']['ktm']['header'][4] ?? '' }}"/>
								</div>
								<div class="col-sm-5">
									<input type="text" class="form-control" name="kartu.ktm.header.5" placeholder="Header 2" value="{{ $configs['kartu']['ktm']['header'][5] ?? '' }}"/>
								</div>
								<div class="col-sm-6">
									<input type="text" class="form-control" name="kartu.ktm.header.6" placeholder="Header 3" value="{{ $configs['kartu']['ktm']['header'][6] ?? '' }}"/>
								</div>
								<div class="col-sm-6">
									<input type="text" class="form-control" name="kartu.ktm.header.7" placeholder="Header 4" value="{{ $configs['kartu']['ktm']['header'][7] ?? '' }}"/>
								</div>
							</div>
						</div>
					</div>
					<div class="form-group">
						{!! Form::label('kartu.ktm.ketentuan', 'Ketentuan:', array('class' => 'col-sm-2 control-label')) !!}
						<div class="col-sm-10">
							<textarea class="form-control" name="kartu.ktm.ketentuan" placeholder="Ketentuan" rows="5">{{ $configs['kartu']['ktm']['ketentuan'] ?? '' }}</textarea>
						</div>
					</div>
					<div class="form-group">
						{!! Form::label('ktm_f', 'Background KTM:', array('class' => 'col-sm-2 control-label')) !!}
						<div class="col-sm-5">
							<div class="file-upload">
								<img src="{{ url('/images/ktm_f.png') }}" style="width: 100%;">
								<input id="ktm_f" class="upload" data-multiple-caption="Terdapat {count} file terpilih" name="ktm_f" type="file">
								<label for="ktm_f" class="btn btn-info btn-flat upload-label"><i class="fa fa-search" id="upload-icon"></i> <span id="filename">KTM Depan</span></label>
							</div>
						</div>
						<div class="col-sm-5">
							<div class="file-upload">
								<img src="{{ url('/images/ktm_b.png') }}" style="width: 100%;">
								<input id="ktm_b" class="upload" data-multiple-caption="Terdapat {count} file terpilih" name="ktm_b" type="file">
								<label for="ktm_b" class="btn btn-success btn-flat upload-label"><i class="fa fa-search" id="upload-icon"></i> <span id="filename">KTM Belakang</span></label>
							</div>
						</div>
					</div>
					<div class="form-group">
						{!! Form::label('ku_f', 'Background Kartu Ujian:', array('class' => 'col-sm-2 control-label')) !!}
						<div class="col-sm-5">
							<div class="file-upload">
								<img src="{{ url('/images/ku_f.png') }}" style="width: 100%;">
								<input id="ku_f" class="upload" data-multiple-caption="Terdapat {count} file terpilih" name="ku_f" type="file">
								<label for="ku_f" class="btn btn-info btn-flat upload-label"><i class="fa fa-search" id="upload-icon"></i> <span id="filename">Kartu Ujian</span></label>
							</div>
						</div>
					</div>				
				</div>
				
				<div class="tab-pane fade form-horizontal" id="kuesioner">
					<div class="form-group">
						{!! Form::label('kuesioner.tgl-mulai', 'Tanggal Mulai:', array('class' => 'col-sm-2 control-label')) !!}
						<div class="col-sm-3">
							<input type="text" class="form-control date" name="kuesioner.tgl-mulai" placeholder="Tanggal Mulai Kuesioner" value="{{ $configs['kuesioner']['tgl-mulai'] ?? '' }}">
						</div>
					</div>
					<div class="form-group">
						{!! Form::label('kuesioner.tgl-selesai', 'Tanggal Selesai:', array('class' => 'col-sm-2 control-label')) !!}
						<div class="col-sm-3">
							<input type="text" class="form-control date" name="kuesioner.tgl-selesai" placeholder="Tanggal Selesai Kuesioner" value="{{ $configs['kuesioner']['tgl-selesai'] ?? '' }}">
						</div>
					</div>
				</div>
				
				<div class="tab-pane fade form-horizontal" id="mahasiswa">					
					<div class="form-group">
						{!! Form::label('ketentuan.foto', 'Ketentuan Foto*:', array('class' => 'col-sm-2 control-label')) !!}
						<div class="col-sm-8">
							<textarea class="form-control" name="ketentuan.foto" placeholder="" rows="7">{{ $configs['ketentuan']['foto'] ?? '' }}</textarea>
						</div>
					</div>	
					<div class="form-group">
						{!! Form::label('mahasiswa.ubah-foto', 'Ubah Foto:', array('class' => 'col-sm-2 control-label')) !!}
						<div class="col-sm-9">
							<?php
								foreach(['Tidak Aktif', 'Aktif'] as $k => $v) 
								{
									echo '<label class="radio-inline">';
									echo '<input type="radio" name="mahasiswa.ubah-foto" ';
									if(isset($configs['mahasiswa']['ubah-foto']) and $k == $configs['mahasiswa']['ubah-foto']) echo 'checked="checked" ';
									echo 'value="'. $k .'"> '. $v .'</label>';
								}
							?>
						</div>
					</div>
				</div>
				
				<div class="tab-pane fade form-horizontal" id="maintenis">
					<div class="callout callout-info">
						<h4>Informasi</h4>
						<p>Status aplikasi:</p>
						<ul>
							<li>Up: Aplikasi dapat diakses oleh siapapun.</li>
							<li>Limited: Aplikasi hanya dapat diakses oleh Admin dan pengguna yang mempunyai akses yang terpilih.</li>
							<li>Down: Aplikasi hanya dapat diakses oleh Admin.</li>
						</ul>
					</div>
					<div class="form-group">
						{!! Form::label('status', 'Status Aplikasi:', array('class' => 'col-sm-2 control-label')) !!}
						<div class="col-sm-9">
							<?php
								foreach(['Up', 'Limited', 'Down'] as $k => $v) 
								{
									echo '<label class="radio-inline">';
									echo '<input type="radio" name="status" ';
									if(isset($status) and $k == $status) echo 'checked="checked" ';
									echo 'value="'. $k .'"> '. $v .'</label>';
								}
							?>
						</div>
					</div>
					<div class="form-group">
						{!! Form::label('roles', 'Akses:', array('class' => 'col-sm-2 control-label')) !!}
						<div class="col-sm-9">
							<?php
								foreach($roles as $k => $v) 
								{
									echo '<label class="checkbox-inline">';
									echo '<input type="checkbox" name="roles[]" ';
									if(in_array($k, $checked_roles)) echo 'checked="checked" ';
									echo 'value="'. $k .'"> '. $v .'</label>';
								}
							?>
						</div>
					</div>
					<div class="form-group">
						{!! Form::label('message', 'Pesan:', array('class' => 'col-sm-2 control-label')) !!}
						<div class="col-sm-8">
							<textarea class="form-control" name="message" placeholder="..." cols="50" rows="5">{{ $message ?? '' }}</textarea>
						</div>
					</div>					
				</div>
			</div>
			<hr/>
			<button class="btn btn-warning btn-flat btn-lg" type="submit" id="submit-button"><i class="fa fa-floppy-o"></i> Simpan</button>
		</form>
	</div>
@endsection																																																																																																										