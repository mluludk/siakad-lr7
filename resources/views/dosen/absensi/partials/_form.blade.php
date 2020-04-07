@push('styles')
<link href="{{ asset('css/jtsage-datebox.min.css') }}" rel="stylesheet">
@endsection

@push('scripts')
<script src="{{ asset('js/jquery-ui.min.js') }}"></script>
<script src="{{ asset('js/jtsage-datebox.min.js') }}"></script>
<script src="{{ asset('js/jtsage-datebox.i18n.id.utf8.min.js') }}"></script>
<script src="{{ asset('js/jquery.mousewheel.min.js') }}"></script>
<script>
	$('#filter').on('change', function(){
		var tgl = $('#tanggal').val().split('-');
		window.location.href="/dosen/absensi/create/"+ tgl[0] +"/"+ tgl[1] +"/"+ tgl[2] +"/"+ $(this).val() + "/H/";
	});
</script>
@endpush

<div class="form-group">
	{!! Form::label('tanggal', 'Tanggal:', array('class' => 'col-sm-2 control-label')) !!}
	<div class="col-sm-3">
		{!! Form::text('tanggal', $date, array('class' => 'form-control', 'id' => 'tanggal', 'placeholder' => 'Tanggal', 'data-role' => "datebox", 'data-options' => '{"mode":"flipbox"}' )) !!}
	</div>
</div>
<div class="form-group">
	{!! Form::label('dosen_id', 'Dosen:', array('class' => 'col-sm-2 control-label')) !!}
	<div class="col-sm-5">
		{!! Form::select('dosen_id', $dosen, $id, ['class' => 'form-control', 'id' => 'filter']) !!}
	</div>
</div>
<div class="form-group">
	{!! Form::label('matkul_tapel_id', 'Matakuliah:', array('class' => 'col-sm-2 control-label')) !!}
	<div class="col-sm-6">
		{!! Form::select('matkul_tapel_id', $matkul, $mtid, ['class' => 'form-control', 'id' => 'matkul']) !!}
	</div>
</div>
<div class="form-group">
	{!! Form::label('jam', 'Jumlah jam:', array('class' => 'col-sm-2 control-label')) !!}
	<div class="col-sm-2">
		{!! Form::select('jam', array_combine($r = range(0, 15), $r), $jam, array('class' => 'form-control', 'id' => 'jam')) !!}
	</div>
</div>
<div class="form-group">
{!! Form::label('status', 'Status:', array('class' => 'col-sm-2 control-label')) !!}
<div class="col-sm-2">
{!! Form::select('status', config('custom.pilihan.absensi'), $sta, ['class' => 'form-control']) !!}
</div>
</div>
<div class="form-group">
<div class="col-sm-offset-2 col-sm-9">
<button class="btn btn-primary btn-flat" type="submit" id="post"><i class="fa fa-floppy-o"></i> Simpan</button>
</div>		
</div>	