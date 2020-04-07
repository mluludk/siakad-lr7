@extends('auth/layout')

@section('content')
<div class="center-block" style="width: 500px">
	<form class="form-horizontal" role="form" method="POST" action="{{ url('/password/reset') }}" style="padding: 20px; background-color: #fff; position: relative; z-index: 2;">
		@if(isset($message))
		<div class="callout callout-info">
			<h4>Informasi</h4>
			<p>{{ $message }}</p>
		</div>
		@endif
		@if(Session::has('message'))
		<div class="callout callout-info">
			<h4>Informasi</h4>
			<p>{{ Session::get('message') }}</p>
		</div>
		@endif
		@if(Session::has('error'))
		<div class="callout callout-danger">
			<h4>Kesalahan</h4>
			<p>{{ Session::get('error') }}</p>
		</div>
		@endif
		{{ csrf_field() }}
		{!! Form::hidden('username', $user -> username) !!}
		{!! Form::hidden('reset_token', $user -> reset_token) !!}
		<div class="form-group">
			<label class="col-md-4 control-label">Username</label>
			<div class="col-md-8">
				<p class="form-control-static">{{ $user->username }}</p>
			</div>
		</div>
		<div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
			<label class="col-md-4 control-label">Password Baru</label>
			<div class="col-md-8">
				<input type="password" class="form-control" name="password" required="required" @if($locked) disabled @endif >
				@if ($errors->has('password'))
				<span class="help-block">
					{{ $errors->first('password') }}
				</span>
				@endif
			</div>
		</div>
		
		<div class="form-group{{ $errors->has('password_confirmation') ? ' has-error' : '' }}">
			<label class="col-md-4 control-label">Ulangi Password Baru</label>
			<div class="col-md-8">
				<input type="password" class="form-control" name="password_confirmation"  required="required" @if($locked) disabled @endif >
				@if ($errors->has('password_confirmation'))
				<span class="help-block">
					{{ $errors->first('password_confirmation') }}
				</span>
				@endif
			</div>
		</div>
		<button type="submit" class="btn btn-primary btn-flat btn-block" @if($locked) disabled @endif >
			<i class="fa fa-refresh"></i> Proses
		</button>
	</form>	
</div>
@endsection