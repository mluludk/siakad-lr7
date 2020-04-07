@push('scripts')
<script src="{{ asset('/js/jquery.inputmask.bundle.min.js') }}"></script>
<script>
	$(function(){
		$(".curr").inputmask('999.999.999', { numericInput: true, removeMaskOnSubmit: true});
		$(".jumlah").inputmask('Rp. 999.999.999', { numericInput: true});
	});
	
	$(document).on('keyup', '.curr', function(){
		jumlah();
	});
	function jumlah()
	{
		var jml = 0;
		$(".curr").each(function(){
			var unf = Inputmask.unmask($(this).val(), { alias: "999.999.999"}); 
			if(unf != null)
			{
				unf = parseInt(unf);
				jml = jml + unf;
			}
		});
		$(".jumlah").val(jml);
	}
</script>
@endpush
{!! Form::hidden('dosen_id', $dosen -> id) !!}
<div class="form-group">
	<label for="nama" class="col-sm-2 control-label">Nama:</label>
	<div class="col-sm-9">
		<p class="form-control-static">{{ $dosen -> nama }}</p>
	</div>
</div>
<div class="form-group">
	<label for="bulan" class="col-sm-2 control-label">Bulan:</label>
	<div class="col-sm-9">
	{!! Form::select('bulan', config('custom.bulan'), intval(date('m')), ['class' => 'form-control', 'style' => 'width: 120px; display: inline-block']) !!}
	{!! Form::select('tahun', array_combine($r = range($t = intval(date('Y')) - 5, $t + 10), $r), intval(date('Y')), ['class' => 'form-control', 'style' => 'width: 100px; display: inline-block']) !!}
	</div>
</div>
@foreach($jgaji as $j)
<div class="form-group">
	<label for="gaji[{{ $j -> id }}]" class="col-sm-2 control-label">{{ $j -> nama }}:</label>
	<div class="col-sm-3">
		<div class="input-group">
			<span class="input-group-addon">Rp</span>
			{!! Form::text('gaji['.$j -> id.']', null, array('class' => 'form-control curr')) !!}
		</div>
	</div>
</div>
@endforeach
<div class="form-group">
	<label for="jumlah" class="col-sm-2 control-label">Jumlah:</label>
	<div class="col-sm-9">
		<p class="form-control-static jumlah"></p>
	</div>
</div>
{!! csrf_field() !!}
<div class="form-group">
	<div class="col-sm-offset-1 col-sm-10">
		<button class="btn btn-primary" type="submit" id="post"><i class="fa fa-floppy-o"></i> Simpan</button>
	</div>		
</div>	