@extends('auth/layout')

@section('content')
<div class="center-block" style="width: 500px">
	<div class="box box-info">
		<div class="box-header">
			<h3 class="box-title">Ganti Password</h3>
		</div>
		<div class="box-body">
			<form class="form-horizontal" role="form" method="POST" action="{{ url('/password/username') }}" style="padding: 20px; background-color: #fff; position: relative; z-index: 2;">
				@if(Session::has('message'))
				<div class="callout callout-success">
					<h4>Sukses</h4>
					<p>{{ Session::get('message') }}</p>
				</div>
				@else
				<!--
					<div class="callout callout-info">
					Dengan mengirimkan permintaan penggantian Password, anda akan menerima email yang
					berisi link / tautan untuk mengubah Password anda. Ikuti Langkah-langkah yang disebutkan dalam email tersebut. Pastikan anda sudah mengisi <strong>
					Email </strong> di profil anda.
					</div>
				-->
				@endif
				
				{{ csrf_field() }}				
				<div class="form-group{{ $errors->has('username') ? ' has-error' : '' }}">
					<label for="username" class="col-md-2 control-label">Username</label>
					<div class="col-md-10">
						<input id="username" type="text" class="form-control" name="username" value="{{ $username ?? old('username') }}" required autofocus placeholder="Masukkan NIM / Username" @if($locked) disabled @endif >
						@if ($errors->has('username'))
						<span class="help-block">
							{{ $errors->first('username') }}
						</span>
						@endif
					</div>
				</div>
				<button type="submit" class="btn btn-primary btn-flat btn-block" @if($locked) disabled @endif >
					<i class="fa fa-send"></i> Kirim
				</button>
			</form>
			<h3>CARA MELAKUKAN RESET PASSWORD</h3>
			<ol style="padding-left: 18px;">
				<li>Pastikan 
					<span class="label bg-blue"><i class="fa fa-check"></i>STATUS MAHASISWA</span> 
					<span class="label bg-red">AKTIF</span> 
				</li>
				<li>Pastikan 
					<span class="label bg-red">EMAIL</span> sudah di input di profil mahasiswa
				</li>
				<li>Dengan 
					<span class="label bg-red">mengirimkan permintaan penggantian Password,</span> anda akan menerima email yang berisi link / tautan untuk mengubah Password anda. 
				</li>
				<li>Ikuti Langkah-langkah yang disebutkan dalam email tersebut.</li>
			</ol>
		</div>
	</div>
</div>
@endsection	