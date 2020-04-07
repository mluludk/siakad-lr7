<div class="form-group">
	<label class="col-md-3 control-label">Password{{ $asterix ?? '' }}</label>
	<div class="col-md-4">
		<input type="password" class="form-control" name="password" {!! $required ?? '' !!}>
	</div>
</div>

<div class="form-group">
	<label class="col-md-3 control-label">Ulangi Password{{ $asterix ?? '' }}</label>
	<div class="col-md-4">
		<input type="password" class="form-control" name="password_confirmation" {!! $required ?? '' !!}>
	</div>
</div>

<div class="form-group">
	<label class="col-md-3 control-label">Nama</label>
	<div class="col-md-4">
		<input type="text" class="form-control" name="nama" value="{{ $user -> authable -> nama ?? old('nama') }}" required="required">
	</div>
</div>

<div class="form-group">
	<label class="col-md-3 control-label">Telp/HP</label>
	<div class="col-md-4">
		<input type="text" class="form-control" name="telp" value="{{ $user -> authable -> telp ?? old('telp') }}">
	</div>
</div>

<div class="form-group">
	<label class="col-md-3 control-label">E-Mail</label>
	<div class="col-md-4">
		<input type="email" class="form-control" name="email" value="{{ $user -> authable -> email ?? old('email') }}">
	</div>
</div>
@if(isset($roles))
<div class="form-group">
	<label class="col-md-3 control-label">Bidang Tugas</label>
	<div class="col-md-4">
		{!! Form::select('role_id', $roles, null, ['class' => 'form-control']) !!}
	</div>
</div>
@endif
<div class="form-group">
	<label class="col-md-3 control-label"></label>
	<div class="col-md-9">
		<span class="help-block">{!! $help ?? '' !!}</span>
		<button type="submit" class="btn btn-primary btn-flat {{ $btn_type }}" ><i class="fa fa-floppy-o"></i> Simpan</button>
	</div>
</div>