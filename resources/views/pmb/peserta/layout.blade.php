<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<meta name="description" content="@yield('tags', config('custom.webtags'))" />
		<link rel="icon" href="{{url('/images/logo64px.png#' . config('custom.app.updated')) }}" sizes="32x32" />
		<link rel="icon" href="{{url('/images/logo.png#' . config('custom.app.updated')) }}" sizes="192x192" />
		<link rel="apple-touch-icon-precomposed" href="{{url('/images/logo.png#' . config('custom.app.updated')) }}">
		<meta name="msapplication-TileImage" content="{{url('/images/logo.png#' . config('custom.app.updated')) }}">
		<title>@yield('title', config('custom.app.name') . ' - ' . config('custom.profil.tipe') . ' ' . config('custom.profil.nama')) - {{ config('custom.app.abbr') }} {{ config('custom.app.version') }}</title>
		<link media="all" type="text/css" rel="stylesheet" href="/css/bootstrap.min.css">	
		<link media="all" type="text/css" rel="stylesheet" href="/css/font-awesome.min.css">
		@if (App::environment('local'))
		<link rel='stylesheet' href='/css/bree-serif-all.css' type='text/css' media='all' />
		<link rel='stylesheet' href='/css/roboto-slab-latin-all.css' type='text/css' media='all' />
		@else
		<link rel='stylesheet' href='http://fonts.googleapis.com/css?family=Bree+Serif:100,100italic,300,300italic,400,400italic,700,700italic&#038;subset=latin,latin-ext' type='text/css' media='all' />
		<link rel='stylesheet' href='http://fonts.googleapis.com/css?family=Roboto+Slab:100,100italic,300,300italic,400,400italic,700,700italic&#038;subset=latin,latin-ext' type='text/css' media='all' />
		@endif
		<style>
			body{
			font-family: 'Roboto Slab', Arial, Helvetica, Verdana;
			}
			h1,h2,h3,h4,h5,h6,.h1,.h2,.h3,.h4,.h5,.h6
			{
			font-family:'Bree Serif',sans-serif
			}
			a{
			color: #333;
			}
			.navbar{
			padding:20px 0;
			margin:0;
			border-bottom: 1px solid #888;
			}
			.navbar-fixed-top{
			background-color: #fff !important;
			z-index: 9999;
			padding:7px 0 !important;
			-webkit-box-shadow: 0 0 10px 1px #777;
			-moz-box-shadow: 0 0 10px 1px #777;
			box-shadow: 0 0 10px 1px #777;
			}
			.navbar-nav > li > a{
			font-size: 13px;
			font-weight: 500;
			text-transform: uppercase;
			}
			
			.navbar-nav > li > a:hover{
			color: #333;
			background-color: transparent;
			}
			.nav-tabs>li.active>a{
			border-bottom-color: transparent !important;
			}
			.navbar-brand {
			font-family:'Bree Serif',sans-serif;
			padding: 7px 15px 7px 0;
			}
			a:hover{
			color: #069eed;
			background-color: transparent;
			text-decoration:none;
			}
			
			.hvr-underline-from-left {
			display: inline-block;
			vertical-align: middle;
			-webkit-transform: translateZ(0);
			transform: translateZ(0);
			box-shadow: 0 0 1px rgba(0, 0, 0, 0);
			-webkit-backface-visibility: hidden;
			backface-visibility: hidden;
			-moz-osx-font-smoothing: grayscale;
			position: relative;
			overflow: hidden;
			}
			.hvr-underline-from-left:before {
			content: "";
			position: absolute;
			z-index: -1;
			left: 0;
			right: 100%;
			bottom: 0;
			background: #2098d1;
			height: 4px;
			-webkit-transition-property: right;
			transition-property: right;
			-webkit-transition-duration: 0.3s;
			transition-duration: 0.3s;
			-webkit-transition-timing-function: ease-out;
			transition-timing-function: ease-out;
			}
			.hvr-underline-from-left:hover:before, .hvr-underline-from-left:focus:before, .hvr-underline-from-left:active:before {
			right: 0;
			}
			
			.hvr-underline-from-left:before {
			background: #333;
			height: 1px;
			}
			
			.article{
			margin-bottom: 80px;
			}
			
			/*
			* Callouts
			*
			* Not quite alerts, but custom and helpful notes for folks reading the docs.
			* Requires a base and modifier class.
			*/
			
			/* Common styles for all types */
			.bs-callout {
			margin: 20px 0;
			padding: 13px;
			border-left: 3px solid #eee;
			}
			.bs-callout h4 {
			font-size: 15px;
			margin-top: 0;
			margin-bottom: 5px;
			}
			.bs-callout p:last-child {
			margin-bottom: 0;
			}
			.bs-callout code {
			background-color: #fff;
			border-radius: 3px;
			}
			
			/* Variations */
			.bs-callout-danger {
			background-color: #fdf7f7;
			border-color: #d9534f;
			}
			.bs-callout-danger h4 {
			color: #d9534f;
			}
			.bs-callout-warning {
			background-color: #fcf8f2;
			border-color: #f0ad4e;
			}
			.bs-callout-warning h4 {
			color: #f0ad4e;
			}
			.bs-callout-info {
			background-color: #f4f8fa;
			border-color: #5bc0de;
			}
			.bs-callout-info h4 {
			color: #5bc0de;
			}
			.bs-callout-success {
			background-color: #dff0d8;
			border-color: #3c763d;
			}
			.bs-callout-success h4 {
			color: #3c763d;
			}
			
			@media (min-width: 1300px)
			{
			.container {
			width: 1300px !important;
			padding: 0 150px 0 150px;
			}
			}
		</style>
		
		@stack('styles')	
		<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
		<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
		<!--[if lt IE 9]>
			<script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
			<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
		<![endif]-->
	</head>
	<body>
		<nav class="navbar navbar-static-top" id="top">
			<div class="container nav-mid">
				<div class="col-sm-12">					
					<div class="navbar-header">
						<a class="navbar-brand" href="/">
							<img src="{{url('/images/logo.png#' . config('custom.app.updated')) }}" style="display:inline-block; height: 30px; vertical-align: bottom; "/>
							<span style="font-size:25px; display:inline-block;line-height:25px;">{{ config('custom.app.title') }}</span>
						</a>
						<button type="button" class="navbar-toggle collapsed btn btn-xs" data-toggle="collapse" data-target="#navbar-collapse-1">
							<i class="fa fa-navicon"></i>
						</button>
					</div>			
					<div class="collapse navbar-collapse" id="navbar-collapse-1">
						<ul class="nav navbar-nav navbar-right">
							<li><a class="hvr-underline-from-left" href="{{ url('/') }}">Home</a></li>
							<li><a class="hvr-underline-from-left" href="{{ url('/pmb/formulir') }}">Formulir PMB Online</a></li>
							<li><a href="{{ url('/pmb/print/formulir') }}" class="hvr-underline-from-left" >Cetak Formulir</a></li>
							<li><a href="{{ url('/pmb/print/kartu') }}" class="hvr-underline-from-left" >Cetak Kartu Ujian</a></li>
							<!--li><a href="{{ url('/pm') }}" class="hvr-underline-from-left" >Informasi PMB</a></li>
							<li><a class="hvr-underline-from-left" href="{{ url('/kontak') }}">Kontak</a></li-->
						</ul>
					</div>
				</div>
			</div>
		</nav>
		
		@if($errors->any())
		<div class="flash alert alert-danger" style="margin-top:5px">
			@foreach($errors->all() as $error)
			<p>{{ $error }}</p>
			@endforeach
		</div>
		@endif
		@yield('content')
		
		<footer style="margin: 30px 0; text-align:center; font-size: 11px; border-top: 1px solid #ddd; padding: 30px 0;">
			<a href="/" target="_blank">STAIMA Al-Hikam Malang &copy; 2016 - {{ date('Y') }}</a>
		</footer>
		
		<!-- Scripts -->
		<script src="/js/jquery.min.js"></script>
		<script src="/js/bootstrap.min.js"></script>
		@stack('scripts')
		
		@if (Auth::guest())
		<script>
			$(window).scroll(function() {
				var height = $(window).scrollTop();
				if(height  >= 100)
				{
					$('.navbar').removeClass('navbar-static-top').animate('slow');
					$('.navbar').addClass('navbar-fixed-top').animate('slow');
				}
				else
				{
					$('.navbar').removeClass('navbar-fixed-top').animate('slow');
					$('.navbar').addClass('navbar-static-top').animate('slow');
				}
			});
		</script>
		<script src="/js/Kalender.js"></script>
		<script>
			$(document).ready(function(){
			$('#kalender').html(printCal());
			});
		</script>
		@endif
		
	</body>
</html>																																																																																																																																																															