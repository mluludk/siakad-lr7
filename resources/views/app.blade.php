<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		@yield('csrf')
		<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<meta name="csrf-token" content="{{ csrf_token() }}">
		<link rel="shortcut icon" href="{{ url('favicon32.png') }}" sizes="32x32" />
		<link rel="icon" href="{{ url('favicon192.png') }}" sizes="192x192" />
		<link rel="apple-touch-icon-precomposed" href="{{ url('favicon32.png') }}">
		<meta name="msapplication-TileImage" content="{{ url('favicon32.png') }}">
		<title>
			@if(env('APP_ENV') == 'testing') [TEST MODE] @endif
			@yield('title', config('custom.app.name') . ' - ' . config('custom.profil.tipe') . ' ' . config('custom.profil.nama')) - {{ config('custom.app.abbr') }} {{ config('custom.app.version') }} 
		</title>
		<link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
		<link rel="stylesheet" href="{{ asset('css/font-awesome.min.css') }}">
		<link rel="stylesheet" href="{{ asset('css/ionicons.min.css') }}">
		<link rel="stylesheet" href="{{ asset('css/source-sans-pro.css') }}">
		<link rel="stylesheet" href="{{ asset('css/AdminLTE.min.css') }}">
		<link rel="stylesheet" href="{{ asset('css/skin-green.min.css') }}">
		<link href="{{ asset('/css/ddmenu.css') }}" rel="stylesheet" type="text/css" />
		<style>
			@media (min-width:1200px) {
			.container {
			width: 1200px !important;
			}
			}
			
			.table-bordered {
			border: 1px solid #e8f5e8 !important;
			}
			.table-striped>tbody>tr:nth-of-type(odd) {
			background-color: #E8F5E8 !important;
			}
			
			nav#ddmenu {
			display: inline-block;
			}
			#ddmenu ul {
			width: auto;
			background: none;
			}
			#ddmenu .top-heading {
			margin: 0 10px;
			}
			#ddmenu .caret {
			left: -8px;
			}
			#ddmenu .column {
			padding:0px;
			border-right-width: 0px;
			}
			#ddmenu .dropdown {
			border: none;
			}
			#ddmenu a:hover {
			text-decoration: none;
			}
			#ddmenu .over.no-sub {
			color: #555;
			background-color: #f3f3f3;
			border-radius: 0;
			}
			.navbar-brand {
			padding: 5px 15px;
			font-size: 20px;
			line-height: 30px;
			}
			.navbar-brand img{
			height: 40px;
			vertical-align: middle;
			display: inline-block;
			}
			
			ol.tim_dosen, .lokasi, .pendamping{
			padding-left: 12px;
			}
			
			.lokasi > li, .pendamping > li{
			padding: 5px 0px;
			}
			.pendamping{
			list-style-type: lower-alpha;
			}
			
			.btn-img{
			padding: 3px 6px;
			}
			.btn-img > img{
			max-height: 26px;
			}
			
			.label-flat{
			border-radius: 0px !important;
			}
			
			#preview, .preview{
			box-shadow: 5px 5px 5px #666;
			-moz-box-shadow: 5px 5px 5px #666;
			-webkit-box-shadow: 5px 5px 5px #666;
			}
			
			.required:after{
			content: " *";
			color: red;
			}
			
			.popover{
			color: black;
			}
			
			.label{
			cursor: default;
			}
			
			.blinking{
			animation:blinkingText 0.8s infinite;
			}
			@keyframes blinkingText{
			0%{     color: #000;    }
			49%{    color: transparent; }
			50%{    color: transparent; }
			99%{    color:transparent;  }
			100%{   color: #000;    }
			}
			
			#headerPanel-body {
			background: linear-gradient(-45deg, #ee7752, #fa3954, #3f4e99, #627f91) !important;
			background-size: 400% 400% !important;
			animation: gradientBG 15s ease infinite;
			}
			@keyframes gradientBG {
			0% {
			background-position: 0% 50%;
			}
			50% {
			background-position: 100% 50%;
			}
			100% {
			background-position: 0% 50%;
			}
			}
		</style>
		@stack('styles')		
		<!--[if lt IE 9]>
			<script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
			<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
		<![endif]-->
	</head>
	<?php 
		$guest = false;
		if(Auth::guest()) $guest = true;
		$rolename = $userimage = null;
		if(!$guest)
		{
			$user = Auth::user();
			$rolename = !isset($rolename) ? md5($user -> role -> name) : $rolename; 
			$userimage = $user -> authable -> foto !== '' ? '/getimage/' . $user -> authable -> foto : '/images/logo.png';
		}
	?>
	<body class="hold-transition skin-green layout-top-nav">
		
		{!! isOnmaintenis() !!}
		
		<div class="wrapper">
		
			@if(!$guest)			
			<header class="main-header">
				<nav class="navbar navbar-static-top" role="navigation">
					<div class="container">
						
						<div class="navbar-header">
							<a href="{{ url('/') }}" class="navbar-brand"><img src="{{ url('/images/logo.png#' . config('custom.app.updated')) }}"> <b>SIAKAD</b></a>
							<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-collapse">
								<i class="fa fa-bars"></i>
							</button>
						</div>
						
						<nav id="ddmenu">
							<div class="menu-icon"></div>
							@include('menu_array')
						</nav>
						
						<div class="navbar-custom-menu">
							<ul class="nav navbar-nav">	
								@include('notifications')								
								<li class="dropdown user user-menu">
									<a href="#" class="dropdown-toggle" data-toggle="dropdown">
										<img src="{{ url($userimage) }}" class="user-image" alt="User Image">
										<span class="hidden-xs">{{ $user -> authable -> nama }}</span>
										@if($new_mail > 0)
										<span class="label label-info">{{ $new_mail }}</span>
										@endif
									</a>
									<ul class="dropdown-menu">
										<li class="user-header">
											<img src="{{ url($userimage) }}" class="img-circle" alt="User Image">
											
											<p>
												{{ $user -> authable -> nama }} - {{ $user -> role -> name }}
												@if($rolename == 'mahasiswa')
												<small>{{ $user -> authable -> prodi -> nama }}</small>
												<small>{{ $user -> authable -> NIM }}</small>
												@else
												<small>Member since {{ date('M. Y', strtotime($user -> authable -> created_at)) }}</small>
												@endif
											</p>
										</li>
										<li class="user-footer">
											<div class="pull-left">
												<a href="{{ url('/profil') }}" class="btn btn-primary btn-flat" title="Profil"><i class="fa fa-address-card"></i></a>
												<a href="{{ url('/ganti-pass') }}" class="btn btn-warning btn-flat" title="Ganti Password"><i class="fa fa-key"></i></a>
												<a href="{{ url('/mail') }}" class="btn btn-info btn-flat" title="Pesan"><i class="fa fa-envelope-o"></i> @if($new_mail > 0){{ $new_mail }}@endif</a>
											</div>
											<div class="pull-right">
												<a class="btn btn-danger btn-flat" href="{{ route('logout') }}" 
												onclick="event.preventDefault();document.getElementById('logout-form').submit();">
												<i class="fa fa-sign-out"></i> Logout</a>
												<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">@csrf</form>
											</div>
										</li>
									</ul>
								</li>
							</ul>
						</div>
					</div>
				</nav>	
				
			</header>
			@endif
			
			<div class="content-wrapper">
				@yield('header')				
				
				<section class="content">
					@if(Session::has('success'))
					<div class="callout callout-success">
						<h4>Sukses</h4>
						<p>{{ Session::get('success') }}</p>
					</div>
					@endif
					@if(Session::has('success_raw'))
					<div class="callout callout-success">
						<h4>Sukses</h4>
						<p>{!! Session::get('success_raw') !!}</p>
					</div>
					@endif
					@if(Session::has('message'))
					<div class="callout callout-info">
						<h4>Informasi</h4>
						<p>{{ Session::get('message') }}</p>
					</div>
					@endif
					@if(Session::has('message_raw'))
					<div class="callout callout-info">
						<h4>Informasi</h4>
						<p>{!! Session::get('message_raw') !!}</p>
					</div>
					@endif
					@if(Session::has('warning'))
					<div class="callout callout-warning">
						<h4>Peringatan</h4>
						<p>{{ Session::get('warning') }}</p>
					</div>
					@endif
					@if(Session::has('warning_raw'))
					<div class="callout callout-warning">
						<h4>Peringatan</h4>
						<p>{!! Session::get('warning_raw') !!}</p>
					</div>
					@endif
					@if(Session::has('danger'))
					<div class="callout callout-danger">
						<h4>Error</h4>
						<p>{{ Session::get('danger') }}</p>
					</div>
					@endif
					@if(Session::has('danger_raw'))
					<div class="callout callout-danger">
						<h4>Error</h4>
						<p>{!! Session::get('danger_raw') !!}</p>
					</div>
					@endif
					@if($errors->any())
					<div class="callout callout-danger">
						<h4>Kesalahan</h4>
						@foreach($errors->all() as $error)
						<p>{{ $error }}</p>
						@endforeach
					</div>
					@endif
					
					@yield('content')
					
				</section>
			</div>
			
			@if(!$guest)	
			<footer class="main-footer hidden-print">
				<div class="pull-right hidden-xs">
					<a data-toggle="modal" href="{{ url('/about') }}" data-target="#about">{{ config('custom.app.abbr') }} {{ config('custom.app.version') }} rev.{{ config('custom.app.version_timestamp') }}</a>
				</div>
				<strong>&copy; Copyright 2016 - {{ date('Y') }} {{ config('custom.app.title') }}</strong>
			</footer>
			<div class="control-sidebar-bg"></div>
			@endif
			
		</div>
		<script src="{{ asset('/js/jquery-2.2.3.min.js') }}"></script>
		<script src="{{ asset('/js/bootstrap.min.js') }}"></script>
		<script src="{{ asset('/js/app.min.js') }}"></script>
		<script src="{{ asset('/js/fastclick.min.js') }}"></script>
		<script src="{{ asset('/js/ddmenu.js') }}" type="text/javascript"></script>
		
		@stack('scripts')
		
		<script>
			$(document).on('click', '.has-confirmation', function(){
			var message = $(this).attr('data-message') != undefined ? $(this).attr('data-message') : 'Apakah anda yakin akan menghapus data ini?';
			if(!confirm(message)) return false;
		});
	</script>
	<div class="modal fade modal-info" id="about" tabindex="-1" role="dialog" aria-labelledby="about-title" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
			</div>
		</div>
	</div>
	
	@if(!$guest)	
	@if(Auth::user() ->isImpersonating())
<a href="{{ action('UsersController@stopImpersonate') }}" style="position: fixed !important; bottom: 10px; right: 5px;" class="btn btn-success btn-flat btn-img"  title="Kembali ke halaman Admin"><img src="{{ url('/getimage/' . \Session::get('orig_user_avatar')) }}" /> Kembali ke halaman Admin</a>
@endif
@endif

</body>
</html>																																																																																																																																																																																																																																																																																																																																																																																																																																																																		