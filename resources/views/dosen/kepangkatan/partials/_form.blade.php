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
	<div class="col-sm-4">
		<p class="form-control-static">{{ $dosen -> nama }}</p>
	</div>
</div>
<div class="form-group">
	{!! Form::label('pangkat', 'Pangkat:', array('class' => 'col-sm-3 control-label')) !!}
	<div class="col-sm-3">
		{!! Form::select('pangkat', config('custom.pilihan.golongan'), null, array('class' => 'form-control')) !!}
	</div>
</div>
<div class="form-group">
	{!! Form::label('sk', 'SK Pangkat:', array('class' => 'col-sm-3 control-label')) !!}
	<div class="col-sm-4">
		{!! Form::text('sk', null, array('class' => 'form-control', 'placeholder' => 'SK Pangkat')) !!}
	</div>
</div>
<div class="form-group">
	{!! Form::label('tgl', 'Tgl SK Pangkat:', array('class' => 'col-sm-3 control-label')) !!}
	<div class="col-sm-3">
		{!! Form::text('tgl', null, array('class' => 'form-control', 'placeholder' => 'Tgl SK Pangkat', 'data-role' => "datebox", 'data-options' => '{"mode":"datebox", "useTodayButton":"true"}')) !!}
	</div>
</div>
<div class="form-group">
	{!! Form::label('tmt', 'TMT Pangkat:', array('class' => 'col-sm-3 control-label')) !!}
	<div class="col-sm-3">
		{!! Form::text('tmt', null, array('class' => 'form-control', 'placeholder' => 'TMT Pangkat', 'data-role' => "datebox", 'data-options' => '{"mode":"datebox", "useTodayButton":"true"}')) !!}
	</div>
</div>
<div class="form-group">
	{!! Form::label('masa_kerja_tahun', 'Masa Kerja Tahun:', array('class' => 'col-sm-3 control-label')) !!}
	<div class="col-sm-2">
		{!! Form::text('masa_kerja_tahun', null, array('class' => 'form-control', 'placeholder' => 'Tahun')) !!}
	</div>
</div>
<div class="form-group">
	{!! Form::label('masa_kerja_bulan', 'Masa Kerja Bulan:', array('class' => 'col-sm-3 control-label')) !!}
	<div class="col-sm-2">
		{!! Form::text('masa_kerja_bulan', null, array('class' => 'form-control', 'placeholder' => 'Bulan')) !!}
	</div>
</div>
<div class="form-group">
	<div class="col-sm-offset-3 col-sm-9">
		<button class="btn btn-primary btn-flat {{ $btn_type }}" type="submit" id="post"><i class="fa fa-floppy-o"></i> Simpan</button>
	</div>		
</div>			