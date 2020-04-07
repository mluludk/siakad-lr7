<!doctype HTML>
<html>
	<head>
		<title>Kwitansi Pembayaran # {{ $kwitansi -> id }}</title>
		<style>
			body{
			padding: 10px;
			width: 21cm;
			height: 35.56cm;
			font-family: Tahoma;
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
			margin: 30px;
			text-align:center;
			}
			img{
			max-width: 100%;
			}
			.table{
			width: 100%;
			border-collapse: collapse;
			}
			.table th{
			padding: 7px;
			}
			.table td{
			padding: 8px 4px;
			}
			
		</style>
	</head>
	<body>
		<img src="{{ asset('/images/header.png') }}" />
		<h3 style="text-align: center; text-transform: uppercse">Kwitansi Pembayaran</h3>
		<p style="text-align: right; font-size: 14px; color: #888;">Tanggal: {{ date('d/m/Y', strtotime($kwitansi -> created_at)) }}</p>
		<table class="table">
			<tr>
				<td>
					Terima dari<br/>
					Nama: <strong>{{ $kwitansi -> nama }}</strong><br/>
					NIM: {{ $kwitansi -> NIM }}
				</td>
				<td>
					<!--
						Penerima<br/>
						Bagian Keuangan<br/>&nbsp;
					-->
				</td>
				<td>
					No. Kwitansi<br/>
					<strong># {{ $kwitansi -> p_id }}</strong><br/>&nbsp;
				</td>
			</tr>
		</table>
		<br/>
		<br/>
		<table class="table">
			<tr>
				<td><strong><em>SEJUMLAH</em></strong></td>
				<td align="right"><strong><em>{{ formatRupiah($kwitansi -> jumlah) }}</em></strong></td>
			</tr>
			<tr style="background-color: #F3F4F5; border-color: #dddddd; border-width: 1px 0 1px 0; border-style:solid;">
				<td><em>Terbilang</em></td>
				<td align="right"><em><small>{{ terbilang($kwitansi -> jumlah) }} rupiah</small></em></td>
			</tr>
			<tr>
				<td></td>
				<td align="right"><strong>Untuk pembayaran {{ $kwitansi -> jenis }} {{ $kwitansi -> tapel }}</strong></td>
			</tr>
		</table>
		<br/>
		<table class="table">
			<tr width="30%">
				<td align="center">
					<br/>
					Yang membayar,<br/><br/><br/><br/>
					<strong>{{ $kwitansi -> nama }}</strong>
				</td>
				<td width="30%"></td>
				<td align="center" width="30%">
					{{ config('custom.profil.alamat.kabupaten') }}, {{ formatTanggal(date('Y-m-d'), strtotime($kwitansi -> created_at)) }}<br/>
					{!! config('custom.ttd.biaya.kwitansi.kanan') !!}
				</td>
			</tr>
		</table>
		<hr/>
		@if(count($credential))
		Username: <strong>{{ $credential['username'] }}</strong><br/>
		Password: <strong>{{ $credential['password'] }}</strong>
		@endif
	<script>
		window.print();
	</script>
	</body>
</html>																