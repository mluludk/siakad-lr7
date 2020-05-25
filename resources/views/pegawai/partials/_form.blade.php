@push('styles')
<link href="{{ asset('css/datepicker.min.css') }}" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('/css/chosen.min.css') }}">
<style>
	.radio-inline+.radio-inline, .checkbox-inline+.checkbox-inline {
	margin-top: 0;
	margin-left: 0;
	margin-right: 10px;
	}
	.radio-inline:not(first-child){
	margin-right: 10px;
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
<script src="{{ asset('/js/datepicker.min.js') }}"></script>
<script>
	$(".date").datepicker({
	format:"dd-mm-yyyy", 
	autoHide:true,
	daysMin: ['Mg', 'Sn', 'Sl', 'Rb', 'Km', 'Jm', 'Sa'],
	daysMin: ['Mg', 'Sn', 'Sl', 'Rb', 'Km', 'Jm', 'Sa'],
	monthsShort: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des']
});
</script>
<script src="{{ asset('/js/chosen.jquery.min.js') }}"></script>
<script>
	$(function(){
		$(".chosen-select").chosen({no_results_text: "Tidak ditemukan hasil pencarian untuk: "});
	});
</script>
@endpush

<div class="form-group">
	{!! Form::label('gelar_depan', 'Nama Lengkap:', array('class' => 'col-sm-3 control-label')) !!}
	<div class="col-sm-9">
		<div style="display:inline-block;">
			{!! Form::text('gelar_depan', null, array('class' => 'form-control', 'placeholder' => 'Gelar Depan')) !!}
		</div>
		<div style="display:inline-block;">
			{!! Form::text('nama', null, array('class' => 'form-control', 'placeholder' => 'Nama lengkap tanpa Gelar', 'required' => 'required')) !!}
		</div>
		<div style="display:inline-block;">
			{!! Form::text('gelar_belakang', null, array('class' => 'form-control', 'placeholder' => 'Gelar Belakang')) !!}
		</div>
	</div>
</div>
<div class="form-group">
	{!! Form::label('pns', 'PNS:', array('class' => 'col-sm-3 control-label')) !!}
	<div class="col-sm-9">
		<?php
			foreach([1 => 'PNS', 2 => 'Non PNS'] as $k => $v) 
			{
				echo '<label class="radio-inline">';
				echo '<input type="radio" name="pns" ';
				if(isset($pegawai) and $k == $pegawai -> pns) echo 'checked="checked" ';
				echo 'value="'. $k .'"> '. $v .'</label>';
			}
		?>
	</div>
</div>
<div class="form-group">
	{!! Form::label('nip', 'NIP:', array('class' => 'col-sm-3 control-label')) !!}
	<div class="col-sm-4 col-xs-9">
		{!! Form::text('nip', null, array('class' => 'form-control', 'placeholder' => 'Nomor Induk Pegawai')) !!}
	</div>
</div>
<div class="form-group">
	{!! Form::label('golongan', 'Golongan:', array('class' => 'col-sm-3 control-label')) !!}
	<div class="col-sm-2">
		{!! Form::select('golongan', config('custom.pilihan.golongan'), null, array('class' => 'form-control')) !!}
	</div>
</div>
<div class="form-group">
	{!! Form::label('niy', 'NIY:', array('class' => 'col-sm-3 control-label')) !!}
	<div class="col-sm-3 col-xs-6">
		{!! Form::text('niy', null, array('class' => 'form-control', 'placeholder' => 'Nomor Induk Yayasan')) !!}
	</div>
</div>
<div class="form-group">
	{!! Form::label('no_sk_awal', 'SK Awal:', array('class' => 'col-sm-3 control-label')) !!}
	<div class="col-sm-9">
		<div style="display:block; float: left; margin-right: 2px;">
			{!! Form::text('no_sk_awal', null, array('class' => 'form-control', 'placeholder' => 'SK Awal')) !!}
		</div>
		<div style="display:block; float: left; margin-right: 2px; width: 200px;">
			{!! Form::text('tmt_sk_awal', null, array('class' => 'form-control date', 'placeholder' => 'TMT SK Awal ', 'autocomplete' => 'off')) !!}
		</div>
	</div>
</div>

<div class="form-group">
	{!! Form::label('no_sk_terbaru', 'SK Terbaru:', array('class' => 'col-sm-3 control-label')) !!}
	<div class="col-sm-6">
		<div style="display:block; float: left; margin-right: 2px;">
			{!! Form::text('no_sk_terbaru', null, array('class' => 'form-control', 'placeholder' => 'SK Terbaru')) !!}
		</div>
		<div style="display:block; float: left; margin-right: 2px; width: 200px;">
			{!! Form::text('tmt_sk_terbaru', null, array('class' => 'form-control date', 'placeholder' => 'TMT SK Terbaru', 'autocomplete' => 'off')) !!}
		</div>
	</div>
</div>

<div class="form-group">
	{!! Form::label('instansi', 'Instansi yang mengangkat:', array('class' => 'col-sm-3 control-label')) !!}
	<div class="col-sm-3">
		{!! Form::select('instansi', config('custom.pilihan.emis.instansi'), null, array('class' => 'form-control')) !!}
	</div>
</div>

<div class="form-group">
	{!! Form::label('status_tugas', 'Status Tugas:', array('class' => 'col-sm-3 control-label')) !!}
	<div class="col-sm-9">
		<?php
			foreach(config('custom.pilihan.emis.status_tugas') as $k => $v) 
			{
				echo '<label class="radio-inline">';
				echo '<input type="radio" name="status_tugas" ';
				if(isset($pegawai) and $k == $pegawai -> status_tugas) echo 'checked="checked" ';
				echo 'value="'. $k .'"> '. $v .'</label>';
			}
		?>
	</div>
</div>	
<div class="form-group">
	{!! Form::label('status_keaktifan', 'Status Keaktifan:', array('class' => 'col-sm-3 control-label')) !!}
	<div class="col-sm-9">
		<?php
			foreach(config('custom.pilihan.emis.status_keaktifan') as $k => $v) 
			{
				echo '<label class="radio-inline">';
				echo '<input type="radio" name="status_keaktifan" ';
				if(isset($pegawai) and $k == $pegawai -> status_keaktifan) echo 'checked="checked" ';
				echo 'value="'. $k .'"> '. $v .'</label>';
			}
		?>
	</div>
</div>	
<div class="form-group">
	{!! Form::label('unit_tugas', 'Unit Tempat Tugas:', array('class' => 'col-sm-3 control-label')) !!}
	<div class="col-sm-9">
		<?php
			foreach(config('custom.pilihan.emis.unit_tugas') as $k => $v) 
			{
				echo '<label class="radio-inline">';
				echo '<input type="radio" name="unit_tugas" ';
				if(isset($pegawai) and $k == $pegawai -> unit_tugas) echo 'checked="checked" ';
				echo 'value="'. $k .'"> '. $v .'</label>';
			}
		?>
	</div>
</div>	
<div class="form-group">
	{!! Form::label('nama_unit_tugas', 'Nama Unit:', array('class' => 'col-sm-3 control-label')) !!}
	<div class="col-sm-4">
		{!! Form::text('nama_unit_tugas', null, array('class' => 'form-control', 'placeholder' => 'Nama Unit Tempat Tugas')) !!}
	</div>
</div>

<div class="form-group">
	{!! Form::label('tugas_pokok', 'Tugas Pokok:', array('class' => 'col-sm-3 control-label')) !!}
	<div class="col-sm-3">
		{!! Form::select('tugas_pokok', config('custom.pilihan.emis.tugas_pokok'), null, array('class' => 'form-control')) !!}
	</div>
</div>
<div class="form-group">
	{!! Form::label('tugas_tambahan', 'Tugas Tambahan:', array('class' => 'col-sm-3 control-label')) !!}
	<div class="col-sm-6">
		{!! Form::select('tugas_tambahan', config('custom.pilihan.emis.tugas_tambahan'), null, array('class' => 'form-control')) !!}
	</div>
</div>
<div class="form-group">
	{!! Form::label('pendidikan_terakhir', 'Pendidikan Terakhir:', array('class' => 'col-sm-3 control-label')) !!}
	<div class="col-sm-9">
		<?php
			foreach(config('custom.pilihan.pendidikanDosen') as $k => $v) 
			{
				echo '<label class="radio-inline">';
				echo '<input type="radio" name="pendidikan_terakhir" ';
				if(isset($pegawai) and $k == $pegawai -> pendidikan_terakhir) echo 'checked="checked" ';
				echo 'value="'. $k .'"> '. $v .'</label>';
			}
		?>
	</div>
</div>
<div class="form-group">
	{!! Form::label('program_studi', 'Program Studi:', array('class' => 'col-sm-3 control-label')) !!}
	<div class="col-sm-6">
		{!! Form::text('program_studi', null, array('class' => 'form-control', 'placeholder' => 'Nama Program Studi')) !!}
	</div>
</div>
<div class="form-group">
	{!! Form::label('tgl_ijasah', 'Tgl Ijasah:', array('class' => 'col-sm-3 control-label')) !!}
	<div class="col-sm-3">
		{!! Form::text('tgl_ijasah', null, array('class' => 'form-control date', 'placeholder' => 'Tanggal Ijasah', 'autocomplete' => 'off')) !!}
	</div>
</div>
<hr/>
<div class="form-group">
	{!! Form::label('nik', 'No. KTP:', array('class' => 'col-sm-3 control-label')) !!}
	<div class="col-sm-6">
		{!! Form::text('nik', null, array('class' => 'form-control', 'placeholder' => 'Nomor Induk Kependudukan (NIK) / No. KTP')) !!}
	</div>
</div>
<div class="form-group">
	{!! Form::label('jenis_kelamin', 'Jenis Kelamin:', array('class' => 'col-sm-3 control-label')) !!}
	<div class="col-sm-8">
		<?php
			foreach(config('custom.pilihan.jenisKelamin') as $k => $v) 
			{
				echo '<label class="radio-inline">';
				echo '<input type="radio" name="jenis_kelamin" ';
				if(isset($pegawai) and $k == $pegawai -> jenis_kelamin) echo 'checked="checked" ';
				echo 'value="'. $k .'"> '. $v .'</label>';
			}
		?>
	</div>
</div>
<div class="form-group">
	{!! Form::label('tmp_lahir', 'TTL:', array('class' => 'col-sm-3 control-label')) !!}
	<div class="col-sm-9">
		<div style="display:block; float: left; margin-right: 2px;">
			{!! Form::text('tmp_lahir', null, array('class' => 'form-control', 'placeholder' => 'Tempat Lahir')) !!}
		</div>
		<div style="display:block; float: left; width: 200px;">
			{!! Form::text('tgl_lahir', null, array('class' => 'form-control date', 'placeholder' => 'Tanggal Lahir', 'autocomplete' => 'off')) !!}
		</div>
	</div>
</div>
<div class="form-group">
	{!! Form::label('nama_ibu', 'Nama Ibu Kandung:', array('class' => 'col-sm-3 control-label')) !!}
	<div class="col-sm-4">
		{!! Form::text('nama_ibu', null, array('class' => 'form-control', 'placeholder' => 'Nama Ibu Kandung')) !!}
	</div>
</div>
<div class="form-group">
	{!! Form::label('alamat', 'Alamat:', array('class' => 'col-sm-3 control-label')) !!}
	<div class="col-sm-6">
		{!! Form::textarea('alamat', null, array('class' => 'form-control', 'rows' => '5', 'placeholder' => 'Alamat Lengkap')) !!}
	</div>
</div>
<div class="form-group">
	{!! Form::label('kab', 'Kabupaten:', array('class' => 'col-sm-3 control-label')) !!}
	<div class="col-sm-9">
		<div style="display:block; float: left; margin-right: 2px;">
			{!! Form::text('kab', null, array('class' => 'form-control', 'placeholder' => 'Kabupaten')) !!}
		</div>
		<div style="display:block; float: left; margin-right: 2px;">
			{!! Form::select('provinsi', config('custom.pilihan.emis.provinsi'), null, array('class' => 'form-control chosen-select')) !!}
		</div>
	</div>
</div>
<div class="form-group">
	{!! Form::label('telp', 'Telepon/HP:', array('class' => 'col-sm-3 control-label')) !!}
	<div class="col-sm-4">
		{!! Form::text('telp', null, array('class' => 'form-control', 'placeholder' => 'Nomor Telepon/HP')) !!}
	</div>
</div>
<div class="form-group">
	{!! Form::label('email', 'Email:', array('class' => 'col-sm-3 control-label')) !!}
	<div class="col-sm-4">
		{!! Form::email('email', null, array('class' => 'form-control', 'placeholder' => 'Email')) !!}
	</div>
</div>	
