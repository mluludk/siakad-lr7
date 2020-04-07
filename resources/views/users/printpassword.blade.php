<!DOCTYPE html>
<html>
	<head>
		<title>Cetak Username dan Password</title>
		<style>
			/* 1cm == 37.8px */
			body{
			padding: 10px;
			width: 21cm;
			height: 35.56cm;
			font-family: tahoma;
			}
			hr{
			margin: 1px;
			}
			h1, h2, h3, h4, h5{
			margin: 0px;
			}
			header{
			text-align: center;
			}
			header div{
			font-size: 12px;
			}
			.sub-header{
			margin: 10px;
			text-align:center;
			}
			img{
			max-width: 100%;
			}
			table{
			font-size: 12px;
			width: 100%;
			border-collapse: collapse;
			}
			td{
			padding: 3px 5px;
			}
			.up{
			padding: 5px;
			border: 1px solid black;
			width: 29.5%;
			margin-right: 1px;
			display: inline-block;
			}
		</style>
	</head>
	<body>
		<div class="sub-header">
			<h4>Cetak Username dan Password</h4>
		</div>
		@foreach($users as $user)
		<div class="up">
			<table>
				<tr>
					<td valign="top">Nama</td><td valign="top">:</td><td>{{ cutStr($user -> authable -> nama, 18) }}</td>
				</tr>
				<tr>
					<td>NIM</td><td>:</td><td>{{ $user -> authable -> NIM }}</td>
				</tr>
				<tr>
					<td>Username</td><td>:</td><td>{{ $user -> username }}</td>
				</tr>
				<tr>
					<td>Password</td><td>:</td><td><strong>{{ $tmp[$user -> username] }}</strong></td>
				</tr>
			</table>
		</div>
		@endforeach			
		<script>
			// window.print();
		</script>
		</body>
		</html>																							