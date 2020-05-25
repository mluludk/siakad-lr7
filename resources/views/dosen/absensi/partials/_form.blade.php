@push('styles')
<link href="{{ asset('css/datepicker.min.css') }}" rel="stylesheet">
@endsection

@push('scripts')
<script src="{{ asset('/js/datepicker.min.js') }}"></script>
<script>
	$('#filter').on('change', function(){
		var tgl = $('#tanggal').val().split('-');
		window.location.href="/dosen/absensi/create/"+ tgl[0] +"/"+ tgl[1] +"/"+ tgl[2] +"/"+ $(this).val() + "/H/";
	});
</script>

<script>
	$(".date").datepicker({
			format:"dd-mm-yyyy", 
			autoHide:true,
			daysMin: ['Mg', 'Sn', 'Sl', 'Rb', 'Km', 'Jm', 'Sa'],
			daysMin: ['Mg', 'Sn', 'Sl', 'Rb', 'Km', 'Jm', 'Sa'],
			monthsShort: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des']
		});
	});
</script>
@endpush

<div class="form-group">
	{!! Form::label('tanggal', 'Tanggal:', array('class' => 'col-sm-2 control-label')) !!}
	<div class="col-sm-3">
		{!! Form::text('tanggal', $date, array('class' => 'form-control date', 'id' => 'tanggal', 'placeholder' => 'Tanggal', 'autocomplete' => 'off')) !!}
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