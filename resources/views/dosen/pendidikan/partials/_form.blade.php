@push('scripts')
<script src="{{ asset('js/jquery-ui.min.js') }}"></script>
<script src="{{ asset('js/jtsage-datebox.min.js') }}"></script>
<script src="{{ asset('js/jtsage-datebox.i18n.id.utf8.min.js') }}"></script>
<script src="{{ asset('js/jquery.mousewheel.min.js') }}"></script>
@endpush
@push('styles')
<link href="{{ asset('css/jtsage-datebox.min.css') }}" rel="stylesheet">
@endsection

<div class="form-group">
	{!! Form::label('', 'Nama:', array('class' => 'col-sm-3 control-label')) !!}
	<div class="col-sm-3">
		<p class="form-control-static">{{ $dosen -> nama }}</p>
	</div>
</div>
<div class="form-group">
	{!! Form::label('bidangStudi', 'Bidang Studi:', array('class' => 'col-sm-3 control-label')) !!}
	<div class="col-sm-5">
		{!! Form::text('bidangStudi', null, array('class' => 'form-control', 'placeholder' => 'Bidang Studi')) !!}
	</div>
</div>
<div class="form-group">
	{!! Form::label('jenjang', 'Jenjang:', array('class' => 'col-sm-3 control-label')) !!}
	<div class="col-sm-2">
		{!! Form::select('jenjang', config('custom.pilihan.pendidikanDosen'), null, array('class' => 'form-control')) !!}
	</div>
</div>
<div class="form-group">
	{!! Form::label('gelar', 'Gelar:', array('class' => 'col-sm-3 control-label')) !!}
	<div class="col-sm-2">
		{!! Form::text('gelar', null, array('class' => 'form-control', 'placeholder' => 'Gelar')) !!}
	</div>
</div>
<div class="form-group">
	{!! Form::label('perguruanTinggi', 'Perguruan Tinggi:', array('class' => 'col-sm-3 control-label')) !!}
	<div class="col-sm-6">
		{!! Form::text('perguruanTinggi', null, array('class' => 'form-control', 'placeholder' => 'Nama Perguruan Tinggi')) !!}
	</div>
</div>
<div class="form-group">
	{!! Form::label('fakultas', 'Fakultas:', array('class' => 'col-sm-3 control-label')) !!}
	<div class="col-sm-5">
		{!! Form::text('fakultas', null, array('class' => 'form-control', 'placeholder' => 'Nama Fakultas')) !!}
	</div>
</div>
<div class="form-group">
	{!! Form::label('tahunLulus', 'Tahun Lulus:', array('class' => 'col-sm-3 control-label')) !!}
	<div class="col-sm-2">
		<input class="form-control" placeholder="Tahun Lulus" name="tahunLulus" type="number" value="{{ $pendidikan -> tahunLulus ?? ''}}" id="tahunLulus" min="1900" max="3000">
	</div>
</div>
<div class="form-group">
	{!! Form::label('nomor_ijasah', 'Nomor Ijasah:', array('class' => 'col-sm-3 control-label')) !!}
	<div class="col-sm-4">
		{!! Form::text('nomor_ijasah', null, array('class' => 'form-control', 'placeholder' => 'Nomor Ijasah')) !!}
	</div>
</div>
<div class="form-group">
	{!! Form::label('tgl_ijasah', 'Tanggal Ijasah:', array('class' => 'col-sm-3 control-label')) !!}
	<div class="col-sm-3">
		{!! Form::text('tgl_ijasah', null, array('class' => 'form-control', 'placeholder' => 'Tanggal Ijasah', 'data-role' => "datebox", 'data-options' => '{"mode":"datebox", "useTodayButton":"true"}')) !!}
	</div>
</div>
<div class="form-group">
	{!! Form::label('sks', 'SKS:', array('class' => 'col-sm-3 control-label')) !!}
	<div class="col-sm-3">
		{!! Form::text('sks', null, array('class' => 'form-control', 'placeholder' => 'Jumlah SKS Lulus')) !!}
	</div>
</div>
<div class="form-group">
	{!! Form::label('ipk', 'IPK:', array('class' => 'col-sm-3 control-label')) !!}
	<div class="col-sm-2">
		{!! Form::text('ipk', null, array('class' => 'form-control', 'placeholder' => 'IPK')) !!}
	</div>
</div>
<div class="form-group">
	<div class="col-sm-offset-3 col-sm-9">
		<button class="btn btn-primary btn-flat {{ $btn_type }}" type="submit" id="post"><i class="fa fa-floppy-o"></i> Simpan</button>
	</div>		
</div>	