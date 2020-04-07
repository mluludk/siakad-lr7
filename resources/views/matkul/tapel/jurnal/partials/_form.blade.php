@push('scripts')
<script src="{{ asset('/js/jquery.inputmask.bundle.min.js') }}"></script>
<script>
	$(function(){
		$(".date").inputmask("dd-mm-yyyy",{"placeholder":"dd-mm-yyyy"});
		$(".time").inputmask("hh:mm",{"placeholder":"hh:mm"});
	});
</script>
@endpush

<div class="form-group">
	{!! Form::label('pertemuan_ke', 'Pertemuan Ke:', array('class' => 'col-sm-4 control-label')) !!}
	<div class="col-sm-2">
		{!! Form::text('pertemuan_ke', null, array('class' => 'form-control')) !!}
	</div>
</div>
<div class="form-group">
	{!! Form::label('tanggal', 'Tanggal:', array('class' => 'col-sm-4 control-label')) !!}
	<div class="col-sm-3">
		{!! Form::text('tanggal', null, array('class' => 'form-control date')) !!}
	</div>
</div>
<div class="form-group">
	{!! Form::label('jam_mulai', 'Jam Mulai:', array('class' => 'col-sm-4 control-label')) !!}
	<div class="col-sm-2">
		{!! Form::text('jam_mulai', null, array('class' => 'form-control time')) !!}
	</div>
</div>
<div class="form-group">
	{!! Form::label('jam_selesai', 'Jam Selesai:', array('class' => 'col-sm-4 control-label')) !!}
	<div class="col-sm-2">
		{!! Form::text('jam_selesai', null, array('class' => 'form-control time')) !!}
	</div>
</div>
<div class="form-group">
	{!! Form::label('ruang_id', 'Ruang:', array('class' => 'col-sm-4 control-label')) !!}
	<div class="col-sm-4">
		{!! Form::select('ruang_id', $ruang, null, array('class' => 'form-control')) !!}
	</div>
</div>
<div class="form-group">
	{!! Form::label('jenis_pertemuan', 'Jenis Pertemuan:', array('class' => 'col-sm-4 control-label')) !!}
	<div class="col-sm-4">
		{!! Form::select('jenis_pertemuan', config('custom.pilihan.jenisPertemuan'), null, array('class' => 'form-control')) !!}
	</div>
</div>
<div class="form-group">
	{!! Form::label('rencana_materi', 'Judul Materi:', array('class' => 'col-sm-4 control-label')) !!}
	<div class="col-sm-8">
		{!! Form::textarea('rencana_materi', null, ['class' => 'form-control', 'rows' => '5']) !!}
	</div>
</div>
<div class="form-group">
	{!! Form::label('materi_kegiatan', 'Materi/Kegiatan:', array('class' => 'col-sm-4 control-label')) !!}
	<div class="col-sm-8">
		{!! Form::textarea('materi_kegiatan', null, ['class' => 'form-control', 'rows' => '5']) !!}
	</div>
</div>
<div class="form-group">
	{!! Form::label('catatan_dosen', 'Catatan Dosen:', array('class' => 'col-sm-4 control-label')) !!}
	<div class="col-sm-8">
		{!! Form::textarea('catatan_dosen', null, ['class' => 'form-control', 'rows' => '5']) !!}
	</div>
</div>
<div class="form-group">
	{!! Form::label('alasan_ganti', 'Alasan Ganti:', array('class' => 'col-sm-4 control-label')) !!}
	<div class="col-sm-6">
		{!! Form::text('alasan_ganti', null, array('class' => 'form-control')) !!}
	</div>
</div>
<div class="form-group">
	{!! Form::label('kesesuaian_sap', 'Kesesuaian SAP (rencana dan materi):', array('class' => 'col-sm-4 control-label')) !!}
	<div class="col-sm-8">
		<?php
			foreach(['n' => 'Tidak', 'y' => 'Sesuai'] as $k => $v) 
			{
				echo '<label class="radio-inline">';
				echo '<input type="radio" name="kesesuaian_sap" ';
				if(isset($jurnal) and $k == $jurnal -> kesesuaian_sap) echo 'checked="checked" ';
				echo 'value="'. $k .'"> '. $v .'</label>';
			}
		?>
	</div>
</div>
<div class="form-group">
	{!! Form::label('file', 'File Materi:', array('class' => 'col-sm-4 control-label')) !!}
	<div class="col-sm-4">
		{!! Form::file('file', array('class' => '')) !!}
		<p class="form-control-static">@if(isset($jurnal -> file))<a href="{{ url('/download/' . $jurnal -> file . '/' . csrf_token()) }}" class="btn btn-primary btn-xs btn-flat"><i class="fa fa-download"></i> Download</a>@endif</p>
	</div>
</div>
<div class="form-group">
	{!! Form::label('status', 'Status:', array('class' => 'col-sm-4 control-label')) !!}
	<div class="col-sm-4">
		{!! Form::select('status', config('custom.pilihan.statusJurnal'), null, array('class' => 'form-control')) !!}
	</div>
</div>
<div class="form-group">
	<div class="col-sm-offset-4 col-sm-10">
		<button class="btn btn-primary" type="submit"><i class="fa fa-floppy-o"></i> Simpan</button>
	</div>		
</div>					