<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		@yield('csrf')
		<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link rel="icon" href="{{url('/images/logo64px.png#' . config('custom.app.updated')) }}" sizes="32x32" />
		<link rel="icon" href="{{url('/images/logo.png#' . config('custom.app.updated')) }}" sizes="192x192" />
		<link rel="apple-touch-icon-precomposed" href="{{url('/images/logo.png#' . config('custom.app.updated')) }}">
		<meta name="msapplication-TileImage" content="{{url('/images/logo.png#' . config('custom.app.updated')) }}">
		<title>@yield('title', config('custom.app.name') . ' - ' . config('custom.profil.tipe') . ' ' . config('custom.profil.nama')) - {{ config('custom.app.abbr') }} {{ config('custom.app.version') }}</title>
		<link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
		<link rel="stylesheet" href="{{ asset('css/font-awesome.min.css') }}">
		<link rel="stylesheet" href="{{ asset('css/source-sans-pro.css') }}">
		<link rel="stylesheet" href="{{ asset('css/AdminLTE.min.css') }}">
		<link rel="stylesheet" href="{{ asset('css/skin-red.min.css') }}">
		<style>
			.table-bordered {
			border: 1px solid #e8f5e8 !important;
			}
			.table-striped>tbody>tr:nth-of-type(odd) {
			background-color: #E8F5E8 !important;
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
			$rolename = !isset($rolename) ? strtolower($user -> role -> name) : $rolename; 
			$userimage = $user -> authable -> foto !== '' ? '/getimage/' . $user -> authable -> foto : '/images/logo.png';
		}
	?>
	<body class="hold-transition skin-red sidebar-mini">
		
		{!! isOnmaintenis() !!}
		
		<div class="wrapper">
			
			@if(!$guest)	
			<header class="main-header">
				<a href="{{ url('/') }}" class="logo">
					<span class="logo-mini"><b>SIAK</b></span>
					<span class="logo-lg"><b>{{ config('custom.app.abbr') }}</b> {{ config('custom.profil.singkatan') }}</span>
				</a>
				<nav class="navbar navbar-static-top" role="navigation">
					<a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
						<span class="sr-only">Toggle navigation</span>
					</a>
					<div class="navbar-custom-menu">
						<ul class="nav navbar-nav">	
							<!--
								<li class="dropdown messages-menu">
								<a href="#" class="dropdown-toggle" data-toggle="dropdown">
								<i class="fa fa-envelope-o"></i>
								<span class="label label-danger">1</span>
								</a>
								<ul class="dropdown-menu">
								<li class="header">Anda mempunyai 1 pesan</li>
								<li>
								<ul class="menu">
								<li>
								<a href="#">
								<div class="pull-left">
								<img src="{{ url('/images/logo.png') }}" class="img-circle" alt="User Image">
								</div>
								<h4>
								Admin
								<small><i class="fa fa-clock-o"></i> 5 mnt</small>
								</h4>
								<p>Selamat Datang di SIAKAD ... </p>
								</a>
								</li>
								</ul>
								</li>
								<li class="footer"><a href="{{ url('/mail') }}">Lihat semua pesan</a></li>
								</ul>
								</li>
							-->
							@include('notifications')
							
							<li class="dropdown user user-menu">
								<a href="#" class="dropdown-toggle" data-toggle="dropdown">
									<img src="{{ url($userimage) }}" class="user-image" alt="User Image">
									<span class="hidden-xs">{{ $user -> authable -> nama }}</span>
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
										</div>
										<div class="pull-right">
											<a href="{{ url('/auth/logout') }}" class="btn btn-danger btn-flat"><i class="fa fa-sign-out"></i> Keluar</a>
										</div>
									</li>
								</ul>
							</li>
							<li>
								<a href="#" data-toggle="control-sidebar"><i class="fa fa-gears"></i></a>
							</li>
						</ul>
					</div>
				</nav>				
			</header>
			<aside class="main-sidebar">
				<section class="sidebar">
					<div class="user-panel">
						<div class="pull-left image">
							<img src="{{ url('/images/logo.png#' . config('custom.app.updated')) }}">
						</div>
						<div class="pull-left info">
							<p>{{ config('custom.app.abbr') }}</p>
							{{ config('custom.app.title') }}
						</div>
					</div>
					@include('menu')
				</section>
			</aside>
			@endif
			<div class="content-wrapper">
				@yield('header' , '
				<section class="content-header">
					<h1>
						Beranda
						<small>Halaman Utama</small>
					</h1>
					<ol class="breadcrumb">
						<li><a href="#"><i class="fa fa-dashboard"></i> Beranda</a></li>
					</ol>
				</section>
				')				
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
			<footer class="main-footer">
				<div class="pull-right hidden-xs">
					<a data-toggle="modal" href="{{ url('/about') }}" data-target="#about">{{ config('custom.app.abbr') }} {{ config('custom.app.version') }}</a>
				</div>
				<strong>Copyright &copy; 2016 - 2017.</strong> All rights reserved.
			</footer>
			<div class="control-sidebar-bg"></div>
			@endif
			
		</div>
		<script src="{{ asset('/js/jquery-2.2.3.min.js') }}"></script>
		<script src="{{ asset('/js/bootstrap.min.js') }}"></script>
		<script src="{{ asset('/js/app.min.js') }}"></script>
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
	</body>
</html>																																																																																																																																																																																																																																																																																																																																																						