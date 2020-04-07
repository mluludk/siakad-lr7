
@push('scripts')
<script src="{{ asset('/js/datepicker.min.js') }}"></script>
<script src="{{ asset('/js/jquery.inputmask.bundle.min.js') }}"></script>
<script>
	$(function(){
		$("#dob").datepicker({
			format:"yyyy-mm-dd", 
			autoHide:true,
			daysMin: ['Mg', 'Sn', 'Sl', 'Rb', 'Km', 'Jm', 'Sa'],
			daysMin: ['Mg', 'Sn', 'Sl', 'Rb', 'Km', 'Jm', 'Sa'],
			monthsShort: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'],
			endDate: "{{ date('Y-m-d', strtotime('-15 year', time())) }}"
		});
		
		$(".year").inputmask("y",{"placeholder":"yyyy"});
	});
	$('.pekerjaan input[type=radio]').on('change', function(){
		
		if($(this).val() == 'p-lain') 
		{
			$(this).next('input[type=text]').attr('disabled', false);
			$(this).next('input[type=text]').focus();
			$(this).next('input[type=text]').select();
		}
		else
		{
			$(this).closest('.pekerjaan').find('input[type=text]').attr('disabled', true);
		}
	});
</script>
@endpush

@push('styles')
<link rel="stylesheet" href="{{ asset('/css/datepicker.min.css') }}">
<style>
	.form-horizontal .control-label {
	text-align: left;
	}
	
	.radio-inline+.radio-inline, .checkbox-inline+.checkbox-inline {
	margin-top: 0;
	margin-left: 0;
	margin-right: 10px;
	}
	.radio-inline:not(first-child){
	margin-right: 10px;
	}
	input[type=text]{
	box-shadow:none;
	border:none;
	border-radius: 0px;
	border-bottom: 1px solid black;
	padding: 2px 0;
	}
	input[type=text]:focus{
	box-shadow:none;
	}
	.sub-header{
	font-weight: bold;
	margin: 9px 0;
	text-transform: uppercase;
    padding: 3px 5px;
    background-color: #333;
    color: #fff;
	}
	.sub-header-2{
	margin-left: 15px !important;
	text-transform: uppercase;
	}
	.form-group{
	margin-left: 0px !important;
	}
	.form-group-2{
	margin-left: 15px !important;
	}
	.form-group label{
	font-weight: normal;
	}
</style>
@endpush
<div class="sub-header">
	A. Identitas Mahasiswa
</div>
<div class="form-group">
	{!! Form::label('nama', '1. Nama sesuai Ijazah:', array('class' => 'col-sm-3 control-label')) !!}
	<div class="col-sm-8">
		{!! Form::text('nama', null, array('class' => 'form-control', 'placeholder' => 'Nama lengkap', 'required' => 'required')) !!}
	</div>
</div>
<div class="form-group">
	{!! Form::label('jenisKelamin', '2. Jenis Kelamin:', array('class' => 'col-sm-3 control-label')) !!}
	<div class="col-sm-8">
		<?php
			foreach(config('custom.pilihan.jenisKelamin') as $k => $v) 
			{
				echo '<label class="radio-inline">';
				echo '<input type="radio" name="jenisKelamin" ';
				if(isset($mahasiswa) and $k == $mahasiswa -> jenisKelamin) echo 'checked="checked" ';
				echo 'value="'. $k .'"> '. $v .'</label>';
			}
		?>
	</div>
</div>
<div class="form-group">
	{!! Form::label('tmpLahir', '3. TTL:', array('class' => 'col-sm-3 control-label')) !!}
	<div class="col-sm-9">
		<div style="display:inline-block;">
			{!! Form::text('tmpLahir', null, array('class' => 'form-control', 'placeholder' => 'Tempat Lahir', 'required' => 'required')) !!}
		</div>
		<div style="display:inline-block;">
			{!! Form::text('tglLahir', null, array('class' => 'form-control', 'id' => 'dob', 'placeholder' => 'Tanggal Lahir', 'required' => 'required', 'autocomplete' => 'off')) !!}
		</div>
	</div>
</div>
<div class="form-group">
	{!! Form::label('agama', '4. Agama:', array('class' => 'col-sm-3 control-label')) !!}
	<div class="col-sm-9">
		<?php
			foreach(config('custom.pilihan.agama') as $k => $v) 
			{
				echo '<label class="radio-inline">';
				echo '<input type="radio" name="agama" ';
				if(isset($mahasiswa) and $k == $mahasiswa -> agama) echo 'checked="checked" ';
				echo 'value="'. $k .'"> '. $v .'</label>';
			}
		?>
	</div>
</div>
<div class="form-group">
	{!! Form::label('alamatMhs', '5. Alamat sesuai KTP:', array('class' => 'col-sm-3 control-label')) !!}
	<div class="col-sm-9">
		{!! Form::text('alamatMhs', null, array('class' => 'form-control', 'placeholder' => 'Alamat', 'required' => 'required')) !!} 
		{!! Form::text('rtrwMhs', null, array('class' => 'form-control', 'placeholder' => 'RT/RW', 'style' => 'margin-top: 5px;display: inline-block; width: 200px;')) !!} 
		{!! Form::text('kodePosMhs', null, array('class' => 'form-control', 'placeholder' => 'Kode Pos', 'style' => 'margin-top: 5px;display: inline-block; width: 200px;')) !!} 
	</div>
</div>
<div class="form-group">
	{!! Form::label('telpMhs', '6. Telp/HP:', array('class' => 'col-sm-3 control-label')) !!}
	<div class="col-sm-6">
		{!! Form::text('telpMhs', null, array('class' => 'form-control', 'placeholder' => 'Telp/HP', 'required' => 'required')) !!} 
	</div>
</div>
<div class="form-group">
	{!! Form::label('noKtp', '7. Nomor KTP:', array('class' => 'col-sm-3 control-label')) !!}
	<div class="col-sm-6">
		{!! Form::text('noKtp', null, array('class' => 'form-control', 'placeholder' => 'Nomor KTP', 'required' => 'required')) !!} 
	</div>
</div>
<div class="form-group">
	{!! Form::label('statusSipil', '8. Status Sipil:', array('class' => 'col-sm-3 control-label')) !!}
	<div class="col-sm-8">
		<?php
			foreach(config('custom.pilihan.statusSipil') as $k => $v) 
			{
				echo '<label class="radio-inline">';
				echo '<input type="radio" name="statusSipil" ';				
				if(isset($mahasiswa) and $k == $mahasiswa -> statusSipil) echo 'checked="checked" ';
				echo 'value="'. $k .'"> '. $v .'</label>';
			}
		?>
	</div>
</div>
<div class="form-group">
	{!! Form::label('jurusan', '9. Jurusan:', array('class' => 'col-sm-3 control-label')) !!}
	<div class="col-sm-8">
		<?php
			foreach($prodi as $k => $v) 
			{
				echo '<label class="radio-inline">';
				echo '<input type="radio" name="jurusan" ';
				if(isset($mahasiswa) and $k == $mahasiswa -> jurusan) echo 'checked="checked" ';
				echo 'value="'. $k .'"> '. $v .'</label>';
			}
		?>
	</div>
</div>
<div class="form-group">
	{!! Form::label('tujuan', '10. Program:', array('class' => 'col-sm-3 control-label')) !!}
	<div class="col-sm-8">
		<?php
			foreach($tujuan as $k => $v) 
			{
				echo '<label class="radio-inline">';
				echo '<input type="radio" name="tujuan" ';
				if(isset($mahasiswa) and $k == $mahasiswa -> tujuan) echo 'checked="checked" ';
				echo 'value="'. $k .'"> '. $v .'</label>';
			}
		?>
	</div>
</div>
<div class="form-group">
	{!! Form::label('jalur', '11. Jalur:', array('class' => 'col-sm-3 control-label')) !!}
	<div class="col-sm-8">
		<?php
			foreach($jalur as $k => $v) 
			{
				echo '<label class="radio-inline">';
				echo '<input type="radio" name="jalur" ';
				if(isset($mahasiswa) and $k == $mahasiswa -> jalur) echo 'checked="checked" ';
				echo 'value="'. $k .'"> '. $v .'</label>';
			}
		?>
	</div>
</div>
<div class="form-group">
	{!! Form::label('kelas', '12. Pilihan Kelas:', array('class' => 'col-sm-3 control-label')) !!}
	<div class="col-sm-8">
		<?php
			foreach($kelas as $k => $v) 
			{
				echo '<label class="radio-inline">';
				echo '<input type="radio" name="kelas" ';
				if(isset($mahasiswa) and $k == $mahasiswa -> kelas) echo 'checked="checked" ';
				echo 'value="'. $k .'"> '. $v .'</label>';
			}
		?>
	</div>
</div>
<div class="sub-header">
	B. Asal Sekolah/Pesantren
</div>
<div class="form-group">
	{!! Form::label('namaSekolahAsal', '1. Nama Sekolah:', array('class' => 'col-sm-3 control-label')) !!}
	<div class="col-sm-8">
		{!! Form::text('namaSekolahAsal', null, array('class' => 'form-control', 'placeholder' => 'Nama Sekolah', 'required' => 'required')) !!}
	</div>
</div>
<div class="form-group">
	{!! Form::label('sekolahAsal', '2. Pendidikan Terakhir:', array('class' => 'col-sm-3 control-label')) !!}
	<div class="col-sm-8">
		<?php
			foreach(config('custom.pilihan.sekolahAsal') as $k => $v) 
			{
				echo '<label class="radio-inline">';
				echo '<input type="radio" name="sekolahAsal" ';
				if(isset($mahasiswa) and $k == $mahasiswa -> sekolahAsal) echo 'checked="checked" ';
				echo 'value="'. $k .'"> '. $v .'</label>';
			}
		?>
	</div>
</div>
<div class="form-group">
	{!! Form::label('thLulus', '3. Tahun Kelulusan:', array('class' => 'col-sm-3 control-label')) !!}
	<div class="col-sm-9">
		<div style="display:inline-block;">
			{!! Form::text('thLulus', null, array('class' => 'form-control year', 'placeholder' => 'Tahun', 'style' => 'width: 80px')) !!}
		</div>
		<div style="display:inline-block;">
			{!! Form::text('ijazah', null, array('class' => 'form-control', 'placeholder' => 'Nomor Ijazah', 'style' => 'width: 180px')) !!}
		</div>
		<div style="display:inline-block;">
			{!! Form::text('jurusanSekolahAsal', null, array('class' => 'form-control', 'placeholder' => 'Jurusan', 'style' => 'width: 250px')) !!}
		</div>
	</div>
</div>
<div class="form-group">
	{!! Form::label('alamatSekolahAsal', '4. Alamat:', array('class' => 'col-sm-3 control-label')) !!}
	<div class="col-sm-9">
		{!! Form::text('alamatSekolahAsal', null, array('class' => 'form-control', 'placeholder' => 'Alamat')) !!} 
		{!! Form::text('rtrwSekolahAsal', null, array('class' => 'form-control', 'placeholder' => 'RT/RW', 'style' => 'margin-top: 5px;display: inline-block; width: 200px;')) !!} 
		{!! Form::text('kodePosSekolahAsal', null, array('class' => 'form-control', 'placeholder' => 'Kode Pos', 'style' => 'margin-top: 5px;display: inline-block; width: 200px;')) !!} 
	</div>
</div>
<div class="sub-header">
	C. Identitas Orang Tua
</div>
<!--
<div class="sub-header sub-header-2">
-->
<div class="form-group">
	1. Identitas Ayah
</div>
<div class="form-group form-group-2">
	{!! Form::label('namaAyah', 'a. Nama:', array('class' => 'col-sm-3 control-label')) !!}
	<div class="col-sm-8">
		{!! Form::text('namaAyah', null, array('class' => 'form-control', 'placeholder' => 'Nama Ayah', 'required' => 'required')) !!}
	</div>
</div>
<div class="form-group form-group-2">
	{!! Form::label('alamatAyah', 'b. Alamat:', array('class' => 'col-sm-3 control-label')) !!}
	<div class="col-sm-9">
		{!! Form::text('alamatAyah', null, array('class' => 'form-control', 'placeholder' => 'Alamat')) !!} 
		{!! Form::text('rtrwAyah', null, array('class' => 'form-control', 'placeholder' => 'RT/RW', 'style' => 'margin-top: 5px;display: inline-block; width: 200px;')) !!} 
		{!! Form::text('kodePosAyah', null, array('class' => 'form-control', 'placeholder' => 'Kode Pos', 'style' => 'margin-top: 5px;display: inline-block; width: 200px;')) !!} 
	</div>
</div>
<div class="form-group form-group-2">
	{!! Form::label('telpAyah', 'c. Telp/HP:', array('class' => 'col-sm-3 control-label')) !!}
	<div class="col-sm-6">
		{!! Form::text('telpAyah', null, array('class' => 'form-control', 'placeholder' => 'Telp/HP')) !!} 
	</div>
</div>
<div class="form-group form-group-2">
	{!! Form::label('pekerjaanAyah', 'd. Pekerjaan:', array('class' => 'col-sm-3 control-label')) !!}
	<div class="col-sm-8 pekerjaan">
		<?php
			foreach(config('custom.pilihan.pekerjaanOrtu') as $v) 
			{
				echo '<label class="radio-inline">';
				echo '<input type="radio" name="pekerjaanAyah" ';
				if(isset($mahasiswa) and $v == $mahasiswa -> pekerjaanAyah) echo 'checked="checked" ';
				echo 'value="'. $v .'"> '. $v .'</label>';
			}
			if(isset($mahasiswa) and !in_array($mahasiswa -> pekerjaanAyah, config('custom.pilihan.pekerjaanOrtu')))
			{
				echo '<label class="radio-inline"><input type="radio" name="pekerjaanAyah" value="p-lain" class="more" style="margin-top: 7px;" checked><input type="text" name="p-lain-ayah" class="form-control custom" style="height: auto;" value="'. $mahasiswa -> pekerjaanAyah .'" placeholder="Lainnya"> </label>';
			}
			else
			{
				echo '<label class="radio-inline"><input type="radio" name="pekerjaanAyah" value="p-lain" class="more" style="margin-top: 7px;" ><input disabled type="text" name="p-lain-ayah" class="form-control custom" style="height: auto;" value="" placeholder="Lainnya"> </label>';
			}
		?>
	</div>
</div>
<div class="form-group form-group-2">
	{!! Form::label('pendidikanAyah', 'e. Pendidikan:', array('class' => 'col-sm-3 control-label')) !!}
	<div class="col-sm-8">
		<?php
			foreach(config('custom.pilihan.pendidikanOrtu') as $k => $v) 
			{
				echo '<label class="radio-inline">';
				echo '<input type="radio" name="pendidikanAyah" ';
				if(isset($mahasiswa) and $k == $mahasiswa -> pendidikanAyah) echo 'checked="checked" ';
				echo 'value="'. $k .'"> '. $v .'</label>';
			}
		?>
	</div>
</div>
<div class="form-group form-group-2">
	{!! Form::label('penghasilanAyah', 'f. Penghasilan:', array('class' => 'col-sm-3 control-label')) !!}
	<div class="col-sm-8">
		<?php
			foreach(config('custom.pilihan.penghasilanOrtu') as $k => $v) 
			{
				echo '<label class="radio-inline">';
				echo '<input type="radio" name="penghasilanAyah" ';
				if(isset($mahasiswa) and $k == $mahasiswa -> penghasilanAyah) echo 'checked="checked" ';
				echo 'value="'. $k .'"> '. $v .'</label>';
			}
		?>
	</div>
</div>
<!--
<div class="sub-header sub-header-2">
-->
<div class="form-group">
	2. Identitas Ibu
</div>
<div class="form-group form-group-2">
	{!! Form::label('namaIbu', 'a. Nama:', array('class' => 'col-sm-3 control-label')) !!}
	<div class="col-sm-8">
		{!! Form::text('namaIbu', null, array('class' => 'form-control', 'placeholder' => 'Nama Ibu', 'required' => 'required')) !!}
	</div>
</div>
<div class="form-group form-group-2">
	{!! Form::label('alamatIbu', 'b. Alamat:', array('class' => 'col-sm-3 control-label')) !!}
	<div class="col-sm-9">
		{!! Form::text('alamatIbu', null, array('class' => 'form-control', 'placeholder' => 'Alamat')) !!} 
		{!! Form::text('rtrwIbu', null, array('class' => 'form-control', 'placeholder' => 'RT/RW', 'style' => 'margin-top: 5px;display: inline-block; width: 200px;')) !!} 
		{!! Form::text('kodePosIbu', null, array('class' => 'form-control', 'placeholder' => 'Kode Pos', 'style' => 'margin-top: 5px;display: inline-block; width: 200px;')) !!} 
	</div>
</div>
<div class="form-group form-group-2">
	{!! Form::label('telpIbu', 'c. Telp/HP:', array('class' => 'col-sm-3 control-label')) !!}
	<div class="col-sm-6">
		{!! Form::text('telpIbu', null, array('class' => 'form-control', 'placeholder' => 'Telp/HP')) !!} 
	</div>
</div>
<div class="form-group form-group-2">
	{!! Form::label('pekerjaanIbu', 'd. Pekerjaan:', array('class' => 'col-sm-3 control-label')) !!}
	<div class="col-sm-8 pekerjaan">
		<?php
			foreach(config('custom.pilihan.pekerjaanOrtu') as $v) 
			{
				echo '<label class="radio-inline">';
				echo '<input type="radio" name="pekerjaanIbu" ';
				if(isset($mahasiswa) and $v == $mahasiswa -> pekerjaanIbu) echo 'checked="checked" ';
				echo 'value="'. $v .'"> '. $v .'</label>';
			}
			if(isset($mahasiswa) and !in_array($mahasiswa -> pekerjaanIbu, config('custom.pilihan.pekerjaanOrtu')))
			{
				echo '<label class="radio-inline"><input type="radio" name="pekerjaanIbu" value="p-lain" class="more" style="margin-top: 7px;" checked><input type="text" name="p-lain-ibu" class="form-control custom" style="height: auto;" value="'. $mahasiswa -> pekerjaanIbu .'" placeholder="Lainnya"> </label>';
			}
			else
			{
				echo '<label class="radio-inline"><input type="radio" name="pekerjaanIbu" value="p-lain" class="more" style="margin-top: 7px;" ><input disabled type="text" name="p-lain-ibu" class="form-control custom" style="height: auto;" value="" placeholder="Lainnya"> </label>';
			}
		?>
		
	</div>
</div>
<div class="form-group form-group-2">
	{!! Form::label('pendidikanIbu', 'e. Pendidikan:', array('class' => 'col-sm-3 control-label')) !!}
	<div class="col-sm-8">
		<?php
			foreach(config('custom.pilihan.pendidikanOrtu') as $k => $v) 
			{
				echo '<label class="radio-inline">';
				echo '<input type="radio" name="pendidikanIbu" ';
				if(isset($mahasiswa) and $k == $mahasiswa -> pendidikanIbu) echo 'checked="checked" ';
				echo 'value="'. $k .'"> '. $v .'</label>';
			}
		?>
	</div>
</div>
<div class="form-group form-group-2">
	{!! Form::label('penghasilanIbu', 'f. Penghasilan:', array('class' => 'col-sm-3 control-label')) !!}
	<div class="col-sm-8">
		<?php
			foreach(config('custom.pilihan.penghasilanOrtu') as $k => $v) 
			{
				echo '<label class="radio-inline">';
				echo '<input type="radio" name="penghasilanIbu" ';
				if(isset($mahasiswa) and $k == $mahasiswa -> penghasilanIbu) echo 'checked="checked" ';
				echo 'value="'. $k .'"> '. $v .'</label>';
			}
		?>
	</div>
</div>

<div class="sub-header">
	D. Upload* (Foto dan Bukti Pembayarn Max 2MB)
</div>
<div class="form-group">
	{!! Form::label('foto', '1. Foto:', array('class' => 'col-sm-3 control-label')) !!}
	<div class="col-sm-8">
		@if(isset($mahasiswa -> foto) and $mahasiswa -> foto != '') 
		{!! Form::hidden('foto_stored', $mahasiswa -> foto) !!}
		<img width="250px" src="{{ url('/getimage/' . $mahasiswa -> foto) }}" />
		@endif
		<input type="file" name="foto" class="form-control" />
	</div>
</div>
<div class="form-group">
	{!! Form::label('slip', '2. Slip Pembayaran:', array('class' => 'col-sm-3 control-label')) !!}
	<div class="col-sm-8">
		@if(isset($mahasiswa -> slip) and $mahasiswa -> slip != '') 
		{!! Form::hidden('slip_stored', $mahasiswa -> slip) !!}
		<img width="400px" src="{{ url('/getimage/' . $mahasiswa -> slip) }}" />
		@endif
		<input type="file" name="slip" class="form-control" />
	</div>
</div>
<div class="form-group">
	<div class="col-sm-offset-2 col-sm-10">
		<span class="help-block">Pastikan seluruh data sudah diisi dengan benar sebelum meng-klik tombol <strong>Simpan</strong></span>
		<button class="btn {{ $btn_type ?? 'btn-primary' }} btn-lg btn-flat" type="submit"><i class="fa fa-floppy-o"></i>  {{ $submit_text ?? 'Simpan' }}</button>
	</div>		
</div>					