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
	{!! Form::label('jabatan', 'Jabatan:', array('class' => 'col-sm-3 control-label')) !!}
	<div class="col-sm-3">
		{!! Form::select('jabatan', config('custom.pilihan.jabatan_fungsional'), null, array('class' => 'form-control')) !!}
	</div>
</div>
<div class="form-group">
	{!! Form::label('sk', 'SK Jabatan:', array('class' => 'col-sm-3 control-label')) !!}
	<div class="col-sm-4">
		{!! Form::text('sk', null, array('class' => 'form-control', 'placeholder' => 'SK Jabatan')) !!}
	</div>
</div>
<div class="form-group">
	{!! Form::label('tmt', 'TMT Jabatan:', array('class' => 'col-sm-3 control-label')) !!}
	<div class="col-sm-3">
		{!! Form::text('tmt', null, array('class' => 'form-control', 'placeholder' => 'TMT Jabatan', 'data-role' => "datebox", 'data-options' => '{"mode":"datebox", "useTodayButton":"true"}')) !!}
	</div>
</div>
<div class="form-group">
	<div class="col-sm-offset-3 col-sm-9">
		<button class="btn btn-primary btn-flat {{ $btn_type }}" type="submit" id="post"><i class="fa fa-floppy-o"></i> Simpan</button>
	</div>		
</div>			