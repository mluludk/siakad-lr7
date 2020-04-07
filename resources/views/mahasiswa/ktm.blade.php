<!DOCTYPE html>
<html>
	<head>
		<title>Kartu Tanda Mahasiswa - {{ $data -> nama }} - {{ $data -> NIM }}</title>
		<style>
			body{
			margin: 0px;
			padding: 0px;
			font-family: Calibri;
			}
			
			.content#f{			
			background-image: url({{ asset('/images/ktm_f.png') }});
			}
			.content#b{			
			background-image: url({{ asset('/images/ktm_b.png') }});
			}
			
			.content{
			margin-right: 2px;
			display: block;
			float: left;
			
			background-repeat:no-repeat;
			background-position:center;
			// background-attachment: fixed;
			-webkit-background-size: cover;
			-moz-background-size: cover;
			-o-background-size: cover;
			background-size: cover;
			
			border: 1px solid black;
			width: 9cm;
			height: 5.7cm;
			z-index: 0;
			}
			
			hr{
			margin: 1px;
			}
			h1, h2, h3, h4, h5, h6{
			margin: 0px;
			}
			#f .header{
			text-align: center;
			font-family: "Times New Roman";
			color: #fff;
			}
			
			#b .header{
			text-align: right !important;		
			font-family: Calibri, verdana;	
			}
			#b .header h5{
			font-size: 18px;
			color: #31991d;
			line-height: 16px;
			}
			#b .header h6{
			font-size: 11px;
			line-height: 10.5px;
			font-weight: normal;
			}
			
			img{
			display: block;
			}
			.logo{
			float: left;
			height: 50px;
			}
			small{
			font-size: 11px;
			}
			.ketentuan ol, ul{
			margin: 0 0 0 25px;
			padding: 0px;
			}
			.ketentuan{
			color: #262d2a;
			font-weight: bold;
			font-size: 13px;
			}
		</style>
	</head>
	<body>
		<?php
			$conf = config('custom');
		?>
		<div class="content" id="f">
			<table style="font-size: 18px;">
				<tr>
					<td><img class="logo"src="{{ asset('/images/logo64px.png') }}" /></td>
					<td>
						<div class="header">
							<h4>{{ $conf['kartu']['ktm']['header'][1] }}</h4>
							<h6>{{ $conf['kartu']['ktm']['header'][2] }}</h6>
							<h6>{{ $conf['kartu']['ktm']['header'][3] }}</h6>
						</div>
					</td>
				</tr>
			</table>
			<hr/>
			<table width="100%">
				<tr>
					<td style="width: 65%; padding-left: 10px;" valign="top">
						<div style="width: 100%; height: 22px; font-size: 20px; font-weight: bold; overflow: hidden;">{{ $data -> nama }}</div>
						<div style="font-size: 13px">
							{{ $data -> tmpLahir }}, {{ $data -> tglLahir }}
						</div>
						<div style="font-size: 14px; font-weight: bold">
							{{ $data -> prodi -> nama }}
						</div>
						<div style="width: 100%; height: 20px;  overflow: hidden; font-size: 10px; line-height: 10px;">
							{{ $data -> jalan }} {{ $data -> dusun }} @if($data -> rt) RT {{ $data -> rt }}@endif @if($data -> rw) RW {{ $data -> rw }}@endif {{ $data -> kelurahan }} 
						</div>
						<div style="height: 40px; width: 162px; padding: 4px; background: #fff; text-align: center; font-size: 10px;">
							<img src="data:image/png;base64,{{ DNS1D::getBarcodePNG($data -> NIM, 'C128', 1,33) }} " alt="{{ $data -> NIM }}" height="30px" />
							{{ $data -> NIM }}
						</div>
					</td>
					<td valign="top" style="padding-top: 3px;" align="center">
						<img src="@if(isset($data->foto) and $data->foto != '')/getimage/{{ $data->foto }} @else/images/b.png @endif" height="95px"></img>
						<div style="font-size: 9px; line-height: 10px; margin-top: 5px;">
							Malang, {{ formatTanggal(date('Y-m-d')) }}
							<br/>
							Ketua
							<br/>
							<br/>
							<strong>{{ $conf['profil']['rektor'] }}</strong>
						</div>
					</td>
				</tr>
			</table>
			<br/>
		</div>
		
		<div class="content" id="b">
			<table width="100%">
				<tr>
					<td>
						<div class="header">
							<h5>{{ $conf['kartu']['ktm']['header'][4] }}</h5>
							<h5>{{ $conf['kartu']['ktm']['header'][5] }}</h5>
							<h6>{{ $conf['kartu']['ktm']['header'][6] }}</h6>
							<h6>{{ $conf['kartu']['ktm']['header'][7] }}</h6>
						</div>
					</td>
				</tr>
			</table>
			<hr/>
			<table width="100%">
				<tr>
					<td class="ketentuan" valign="top">
						{!! $conf['kartu']['ktm']['ketentuan'] !!}
					</td>
				</tr>
			</table>
			<br/>
		</div>
		<script>
			// window.print();
		</script>
	</body>
</html>																								