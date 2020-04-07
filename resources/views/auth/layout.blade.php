<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="robots" content="noindex, nofollow">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link rel="shortcut icon" href="/favicon32.png" sizes="32x32" />
		<link rel="icon" href="/favicon192.png" sizes="192x192" />
		<link rel="apple-touch-icon-precomposed" href="/favicon32.png">
		<meta name="msapplication-TileImage" content="/favicon32.png">
		<meta name="description" content="Login Page" />
		<title>{{ config('custom.app.name') }} | {{ config('custom.app.title') }}</title>
		<link href="{{ asset('/css/bootstrap.min.css') }}" rel="stylesheet">
		<link href="{{ asset('/css/font-awesome.min.css') }}" rel="stylesheet">
		<link href="{{ asset('/css/bs-callout.css') }}" rel="stylesheet">
		<link rel="stylesheet" href="{{ asset('css/AdminLTE.min.css') }}">
		<link rel="stylesheet" href="{{ asset('css/source-sans-pro.css') }}">
		<style>
			html {
			height: 100%;
			box-sizing: border-box;
			}
			*,
			*:before,
			*:after {
			box-sizing: inherit;
			}
			body {
			position: relative;
			margin: 0;
			padding-bottom: 6rem;
			min-height: calc(100% - 70px);
			
			background: #50a3a2;
			background: linear-gradient(to bottom right, #50a3a2 0%, #53e3a6 100%);
			}
			.logo{
			text-align:center;
			margin-bottom: 20px;
			}
			.logo img{
			width: 128px;
			}
			.inpt{
 			position: relative;
			}
			.inpt .form-control{
			padding-left: 36px;
			}
			.form-control-feedback{
			top: 7%;
			left: 15px;
			} 
			span.fa{
			color: #999999;
			}
			#footer{
			/* 			
			position: absolute;
			right: 0;
			bottom: 0;
			left: 0; 
			*/
			margin-top: 10px;
			padding: 1rem 0;
			text-align: center;
			font-size:10px;
			color:#555;
			}
			.form{
			display: -webkit-flex;
			display: flex;
			width: 700px;
			margin: 0px auto;
			-webkit-box-shadow: 0px 0px 10px 0px rgba(50, 50, 50, 1);
			-moz-box-shadow:    0px 0px 10px 0px rgba(50, 50, 50, 1);
			box-shadow:         0px 0px 10px 0px rgba(50, 50, 50, 1);
			}
			.form-control{
			box-shadow:none;
			border:none;
			border-radius: 0px;
			border-bottom: 1px solid black;
			padding: 2px 0;
			}
			.form-control:focus{
			box-shadow:none;
			}
			
			.left{
			-webkit-flex: 2;
			flex: 2;
			background-image:url("/images/front2.jpg");
			background-color: #161316;
			color: #dedede;
			padding: 28px 10px;
			text-align: right;
			}
			.left #name{
			font-weight: bold;
			font-size: 17px;
			}
			.left #title{
			line-height: 15px;
			font-size: 19px;
			}
			.left #address{
			font-size: 15px;
			}
			.right{
			-webkit-flex: 2;
			flex: 2;
			padding: 20px 25px 40px 25px;
			}
			@media (max-width: 768px) {
			.form{
			width: 100%;
			}
			.left{
			display:none;
			}
			}
			
			.bg-bubbles {
			position: absolute;
			top: 0;
			left: 0;
			width: 100%;
			height: 100%;
			z-index: -1;
			}
			.bg-bubbles li {
			position: absolute;
			list-style: none;
			display: block;
			width: 40px;
			height: 40px;
			background-color: rgba(255, 255, 255, 0.15);
			bottom: -160px;
			-webkit-animation: square 25s infinite;
			animation: square 25s infinite;
			transition-timing-function: linear;
			border-radius: 50%;
			}
			.bg-bubbles li:nth-child(1) {
			left: 10%;
			}
			.bg-bubbles li:nth-child(2) {
			left: 20%;
			width: 80px;
			height: 80px;
			-webkit-animation-delay: 2s;
			animation-delay: 2s;
			-webkit-animation-duration: 17s;
			animation-duration: 17s;
			}
			.bg-bubbles li:nth-child(3) {
			left: 25%;
			-webkit-animation-delay: 4s;
			animation-delay: 4s;
			}
			.bg-bubbles li:nth-child(4) {
			left: 40%;
			width: 60px;
			height: 60px;
			-webkit-animation-duration: 22s;
			animation-duration: 22s;
			background-color: rgba(255, 255, 255, 0.25);
			}
			.bg-bubbles li:nth-child(5) {
			left: 70%;
			}
			.bg-bubbles li:nth-child(6) {
			left: 80%;
			width: 120px;
			height: 120px;
			-webkit-animation-delay: 3s;
			animation-delay: 3s;
			background-color: rgba(255, 255, 255, 0.2);
			}
			.bg-bubbles li:nth-child(7) {
			left: 32%;
			width: 160px;
			height: 160px;
			-webkit-animation-delay: 7s;
			animation-delay: 7s;
			}
			.bg-bubbles li:nth-child(8) {
			left: 55%;
			width: 20px;
			height: 20px;
			-webkit-animation-delay: 15s;
			animation-delay: 15s;
			-webkit-animation-duration: 40s;
			animation-duration: 40s;
			}
			.bg-bubbles li:nth-child(9) {
			left: 25%;
			width: 10px;
			height: 10px;
			-webkit-animation-delay: 2s;
			animation-delay: 2s;
			-webkit-animation-duration: 40s;
			animation-duration: 40s;
			background-color: rgba(255, 255, 255, 0.3);
			}
			.bg-bubbles li:nth-child(10) {
			left: 90%;
			width: 160px;
			height: 160px;
			-webkit-animation-delay: 11s;
			animation-delay: 11s;
			}
			
			@-webkit-keyframes square {
			0% {
			-webkit-transform: translateY(0);
			transform: translateY(0);
			}
			100% {
			-webkit-transform: translateY(-700px) rotate(600deg);
			transform: translateY(-700px) rotate(600deg);
			}
			}
			@keyframes square {
			0% {
			-webkit-transform: translateY(0);
			transform: translateY(0);
			}
			100% {
			-webkit-transform: translateY(-700px) rotate(600deg);
			transform: translateY(-700px) rotate(600deg);
			}
			} 
		</style>
	</head>
	<body>
		{!! isOnmaintenis() !!}
		<div class="container"  style="padding-top:50px;">
			<div class="row">
				<div class="center-block logo">
					<img src="{{ url('/images/logo.png') }}" />
				</div>
			</div>
			@yield('content')
			<ul class="bg-bubbles">
				<li></li>
				<li></li>
				<li></li>
				<li></li>
				<li></li>
				<li></li>
				<li></li>
				<li></li>
				<li></li>
				<li></li>
			</ul>
			<div id="footer">							
				<a data-toggle="modal" href="{{ url('/about') }}" data-target="#about">{{ config('custom.app.abbr') }} {{ config('custom.app.version') }}</a><br/>
				{{ config('custom.profil.singkatan') }}&nbsp;
				{{ config('custom.profil.nama') }}<br/>
				&copy; 2016 - {{ date('Y') }} 
			</div>
		</div>
		
		<div class="modal fade modal-warning" id="notification">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<h4 class="modal-title">Pemberitahuan</h4>
					</div>
					<div class="modal-body">
						<p>
							<strong>Pengguna SIAKAD Yth.</strong><br/>
							Proses pengembangan SIAKAD sampai saat ini masih terus berjalan. 
							Jika anda menemukan kesalahan (Error) pada SIAKAD harap segera melapor kepada Admin SIAKAD, disertai dengan deskripsi kesalahan 
							dan hal yang menyebabkan terjadinya kesalahan tersebut.<br/>
							Demikian terima kasih dan harap maklum.
							<br/>
							<br/>
							<br/>
							<em>Dev Team</em>							
						</p>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-default btn-danger" id="accept">Ya, saya mengerti</button>
					</div>
				</div>
			</div>
		</div>
		
		<div class="modal fade modal-info" id="about" tabindex="-1" role="dialog" aria-labelledby="about-title" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content"></div>
			</div>
		</div>
		<script src="{{ asset('/js/jquery-2.2.3.min.js') }}"></script>
		<script src="{{ asset('/js/bootstrap.min.js') }}"></script>
		<script src="{{ asset('/js/cookie.min.js') }}"></script>
		<script>
			if(Cookies.get('notification') != 'accepted')
			{
				$('#notification').modal('show');
			}
			
			$(document).on('click', '#accept', function(){
				Cookies.set('notification', 'accepted',  { expires: 365 });
				$('#notification').modal('hide');
			});
		</script>
	</body>
</html>
