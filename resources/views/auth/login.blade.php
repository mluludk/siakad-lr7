<?php
	$config = config('custom');
?>
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
		<title>{{ $config['app']['name'] }} | {{ $config['app']['title'] }}</title>
		<link href="{{ asset('/css/bootstrap.min.css') }}" rel="stylesheet">
		<link href="{{ asset('/css/font-awesome.min.css') }}" rel="stylesheet">
		<link href="{{ asset('/css/bs-callout.css') }}" rel="stylesheet">
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
			
			background: #F6F2D5;
			// background: linear-gradient(to bottom right, #50a3a2 0%, #53e3a6 100%);
			
			background: url('{{ url("/images/staima1.jpg") }}') no-repeat center center fixed;
			-webkit-background-size: cover;
			-moz-background-size: cover;
			-o-background-size: cover;
			background-size: cover;	
			
			}
			
			.wrapper{
			margin: 0px auto;
			}
			.tbl-form{
			font-size: 12px;
			background-color: #fff;
			-webkit-box-shadow: 0px 0px 10px 0px rgba(50, 50, 50, 1);
			-moz-box-shadow:    0px 0px 10px 0px rgba(50, 50, 50, 1);
			box-shadow:         0px 0px 10px 0px rgba(50, 50, 50, 1);
			
			}
			.judul{
			border-bottom: 3px solid #006066;
			padding: 10px 0; 
			}
			ol.info{
			padding: 0 13px;
			margin: 0px;
			}
			.footer{
			margin-top:18px; font-size: 75%; color:#fff;
			text-shadow: 1px 1px 1px #000
			}
		</style>
	</head>
	<body>
		{!! isOnmaintenis() !!}
		<div class="wrapper" style="width:70%; margin-top: 90px;">
			<table align="center" border="0" class="tbl-form">
				<tbody>
					<tr style="cursor: auto;">
						<td width="908" class="judul">
							<table width="95%" height="80%" border="0" align="center" style="box-shadow:none;background:none">
								<tbody>
									<tr>
										<td width="75px">
											<img style="width: 56px;" src="{{ url('/images/logo.png') }}" />
										</td>
										<td>
											<div style="font-size: 19px;">SISTEM INFORMASI AKADEMIK (SIAKAD)</div>
											<div style="font-size: 15px;">{{ $config['profil']['nama'] }}</div>
										</td>
									</tr>
								</tbody>
							</table>
						</td>
					</tr>					
					<tr style="cursor: auto; font-size: 5px;">
						<td>&nbsp;</td>
					</tr>	
					@if(Session::has('message'))
					<tr style="cursor: auto;">
						<td style="margin-top: 10px;color:white;text-align:center;background-color:lightblue">
							<p style="margin: 2px;">{{ Session::get('message') }}</p>
						</td>
					</tr>
					@endif
					@if($errors->any())
					<tr style="cursor: auto;">
						<td style="margin-top: 10px;color:white;text-align:center;background-color:rgba(156, 15, 15, 0.8)">
							@foreach($errors -> all() as $error)
							<p style="margin: 2px;">{{ $error }}</p>
							@endforeach
						</td>
					</tr>
					@endif
					<tr align="center" style="cursor: auto;">
						<td>
							<table width="95%" height="80%" border="0" align="center" style="box-shadow:none;background:none">
								<tbody>
									<tr style="cursor: auto; font-size: 5px;"><td colspan="2"><br></td></tr>
									<tr style="cursor: auto;">
										<td width="482" valign="top" style="border-right:1px dotted rgba(0,0,0,.26);">
											<div style="margin: 0 20px">
												{!! $config['info']['login'] !!}
											</div>
										</td>
										<td width="359" style="vertical-align:middle; padding: 15px;">
											<div align="center">
												
												<form class="form-horizontal" role="form" method="POST" action="{{ url('/login') }}">
													<input type="hidden" name="_token" value="{{ csrf_token() }}">
													
													<div class="form-group">
														<label class="col-xs-12 sr-only">Username</label>
														<div class="col-xs-12 inpt">
															<input type="text" class="form-control" name="username" value="{{ old('username') }}" autofocus="autofocus" required="required" placeholder="NIM / Username">
															<span class="fa fa-user fa-2x form-control-feedback"></span>
														</div>
													</div>
													
													<div class="form-group">
														<label class="col-xs-12 sr-only">Password</label>
														<div class="col-xs-12 inpt">
															<input type="password" class="form-control" name="password" required="required"  placeholder="Kata kunci">
															<span class="fa fa-lock fa-2x form-control-feedback"></span>
														</div>
													</div>
													<div class="form-group">
														<div class="col-xs-12">
															<div class="checkbox" align="left">
																<label>
																	<input type="checkbox" name="remember"> Remember Me
																</label>
															</div>
														</div>
													</div>
													<div class="form-group">
														<div class="col-md-12">
															<button type="submit" class="btn btn-primary btn-block"><i class="fa fa-sign-in"></i> Masuk</button>
															@if(config('custom.user.reset-password') == 0) 
															<a class="btn btn-info btn-block" disabled="disabled"><i class="fa fa-srefresh"></i> Lupa Password?</a>
															@else
															<a class="btn btn-info btn-block" href="{{ url('/password/username') }}"><i class="fa fa-key"></i> Lupa Password?</a>
															@endif
														</div>
													</div>
												</form>
												
											</div>
										</td>
									</tr>
								</tbody>
							</table>
						</td>
					</tr>
					<tr style="cursor: auto;"><td colspan="2"><br></td></tr>
				</tbody>
			</table>
		</div>
		
		<div align="center">
			<p class="footer">
				Pusat Teknologi Informasi dan Pangkalan Data
				<br>	Pusat bantuan : {{ $config['profil']['email'] }}
			</p>			
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