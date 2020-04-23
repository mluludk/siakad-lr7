<!doctype HTML>
<html>
	<head>
		<title>Kode Pembayaran</title>
		<style>
			body{
			padding: 5px;
			height: 21cm;
			width: 35.56cm;
			font-family: Tahoma;
			}
			table{
			width: 50%;
			border-collapse: collapse;
			}
			th{
			text-align: left;
			}
			ul{
			margin: 0px 10px;
			padding: 0px;
			}
			#qr{
			vertical-align: top;
			}
			#qr > div{
			padding: 5px;
			font-size: 11px;
			text-align: center;
			border: 1px solid black;
			border-radius: 5px;
			}
			#qr img{
			display: iniline-block;
			}
		</style>
	</head>
	<body>
		<table>
			<thead>
				<tr>
					<th colspan="3" style="font-weight:bold; font-size: 20px;">Kode Pembayaran</th>
				</tr>
			</thead>
			<tbody>
				<tr><td>NIM</td><td>:</td><td>{{ $mahasiswa -> NIM }}</td><td rowspan="8" id="qr" width="40%">
					<div>
						<img src="data:image/png;base64, {!! DNS2D::getBarcodePNG(url('/pembayaran/token?kode=' . $token), 'QRCODE', 5, 5) !!}">
						<br/>Scan QR Code ini untuk pembayaran (Adminstrator)
					</div>
				</td>
				</tr>
				<tr><td>Nama</td><td>:</td><td>{{ $mahasiswa -> nama }}</td></tr>			
				<tr><td>Tagihan</td><td>:</td><td>{{ $nama_tagihan }}</td></tr>			
				<tr><td>Nominal</td><td>:</td><th>{{ formatRupiah($sisa) }}</th></tr>			
				<tr><td>Status</td><td>:</td><td>{{ $status }}</td></tr>			
				<tr><td>Kode Bayar</td><td></td><td></td></tr>	
				<tr><td></td><td></td><td style="font-weight:bold; font-size: 33px;">{{ substr_replace($token, ' ', 3, 0) }}</td></tr>	
				<tr>
					<td colspan="3" style="font-style:italic; font-size:11px;">
						Petunjuk pembayaran:
						<ul>
							<li>Tunjukkan kode pembayaran ke bagian keuangan untuk proses pembayaran lebih lanjut. </li>
							<li>Pastikan Nominal Pembayaran sesuai dengan Nominal yang tertera diatas.</li>
							<li>
								Kode Bayar ini berlaku sampai dengan <strong>{{ formatTanggalWaktu($token_expired_at) }}</strong>. 
								Kode Bayar untuk Tagihan ini harus di-generate ulang jika sudah Kadaluarsa.
							</li>
						</ul>
					</td>
				</tr>
			</tbody>
		</table>
		<script>
			window.print();
		</script>
	</body>
</html>									