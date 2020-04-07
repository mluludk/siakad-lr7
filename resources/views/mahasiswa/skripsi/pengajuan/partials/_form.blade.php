<style>
	.required:after{
	content: " *";
	color: red;
	}
</style>
<div class="form-group">
	{!! Form::label('jadwal_pengajuan_skripsi_gelombang_id', 'Jadwal:', array('class' => 'col-sm-3 control-label required')) !!}
	<div class="col-sm-8">
		{!! Form::select('jadwal_pengajuan_skripsi_gelombang_id', $jadwal_buka, null, array('class' => 'form-control', 'placeholder' => 'Jadwal pengajuan', 'required' => 'required')) !!}
	</div>
</div>
<div class="form-group">
	{!! Form::label('judul', 'Judul:', array('class' => 'col-sm-3 control-label required')) !!}
	<div class="col-sm-8">
		{!! Form::text('judul', null, array('class' => 'form-control', 'placeholder' => 'Judul Skripsi', 'required' => 'required')) !!}
	</div>
</div>
<div class="form-group">
	{!! Form::label('latar_belakang', 'Latar Belakang:', array('class' => 'col-sm-3 control-label required')) !!}
	<div class="col-sm-8">
		{!! Form::textarea('latar_belakang', null, array('class' => 'form-control', 'placeholder' => 'Latar Belakang', 'rows' => '5', 'required' => 'required')) !!}
	</div>
</div>
<div class="form-group">
	{!! Form::label('rumusan_masalah[0]', 'Rumusan Masalah:', array('class' => 'col-sm-3 control-label')) !!}
	<div class="col-sm-8">
		{!! Form::text('rumusan_masalah[0]', null, array('class' => 'form-control', 'placeholder' => 'Rumusan Masalah 1')) !!}
		{!! Form::text('rumusan_masalah[1]', null, array('class' => 'form-control', 'placeholder' => 'Rumusan Masalah 2')) !!}
		{!! Form::text('rumusan_masalah[2]', null, array('class' => 'form-control', 'placeholder' => 'Rumusan Masalah 3')) !!}
		{!! Form::text('rumusan_masalah[3]', null, array('class' => 'form-control', 'placeholder' => 'Rumusan Masalah 4')) !!}
		{!! Form::text('rumusan_masalah[4]', null, array('class' => 'form-control', 'placeholder' => 'Rumusan Masalah 5')) !!}
	</div>
</div>
<div class="callout callout-warning">
	<h4>Perhatian</h4>
	<p>
		Untuk memastikan bahwa Judul Skripsi yang ajukan bisa diproses, mohon perhatikan hal-hal berikut:
		<ol class="form-control-static" style="margin: 0 0 0 13px; padding: 0;">
			<li>Sistem akan memeriksa kemiripan Judul Skripsi yang anda ajukan dengan Judul-judul Skripsi yang sudah terdaftar secara otomatis.</li>
			<li>Proses ini kadang memakan waktu yang cukup lama.</li>
			<li>Harap tunggu sampai halaman selesai dimuat.</li>
			<li>Pastikan koneksi internet anda berjalan dengan baik.</li>
		</ol>
	</p>
</div>
<div class="form-group">
	<div class="col-sm-offset-3 col-sm-9">
		<button class="btn btn-primary btn-flat {{ $btn_type }}" type="submit"><i class="fa fa-floppy-o"></i> Kirim</button>
	</div>		
</div>	