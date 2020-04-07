<!DOCTYPE html>
<html>
	<head>
		<title>LEMBAR PERSETUJUAN UJIAN {{ strtoupper($jenis) }} SKRIPSI</title>
		<style>
			/* 1cm == 37.8px */
			body{
			padding: 20px;
			width: 21cm;
			height: 35.56cm;
			font-family: tahoma;
			line-height: 30px;
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
			table{
			width: 100%;
			border-collapse: collapse;
			margin: 10px 0;
			}
			td{
			padding: 3px 5px;
			}
		</style>
	</head>
	<body>
		<img src="{{ asset('/images/header.png') }}" />
		<div class="sub-header">
			<h4>LEMBAR PERSETUJUAN UJIAN {{ strtoupper($jenis) }} @if($jenis == 'komprehensif') & @endif SKRIPSI</h4>
		</div>
		<table>
			<tr>
				<td width="20%">Nama</td><td width="10px">:</td><td>{{ $mahasiswa -> nama }}</td>
			</tr>			
			<tr>
				<td>NIM/NIRM</td><td>:</td><td>{{ $mahasiswa -> NIM }}</td>
			</tr>			
			<tr>
				<td>Program Studi</td><td>:</td><td>{{ $mahasiswa -> prodi -> strata }} {{ $mahasiswa -> prodi -> nama }}</td>
			</tr>			
			<tr>
				<td valign="top">Judul Skripsi</td><td valign="top">:</td><td valign="top">{{ $skripsi -> judul }}</td>
			</tr>		
			<tr>
				<td valign="top" colspan="3">
					Setelah diperiksa dan dilakukan perbaikan seperlunya, @if($jenis == 'proposal') Proposal @endif Skripsi dengan judul sebagaimana 
					di atas disetujui untuk diajukan ke Sidang Ujian @if($jenis == 'proposal') Proposal @endif Skripsi.
				</td>
			</tr>		
			<tr>
				<td colspan="3">
					<br/>
					<br/>
					{{ config('custom.profil.alamat.kabupaten') }}, @if($jenis == 'proposal'){{ $skripsi -> tgl_validasi_proposal }}@else{{ $skripsi -> tgl_validasi_kompre }}@endif
				</td>
			</tr>	
			<tr>
				<td colspan="3">
					@if($skripsi -> pembimbing -> count() < 1)
					Pembimbing
					<br/>
					<br/>
					<br/>
					<br/>	
					..................................................
					@else
					Pembimbing
					<br/>
					<br/>
					<br/>
					<br/>	
					{{ $skripsi -> pembimbing[0] -> gelar_depan }} {{ $skripsi -> pembimbing[0] -> nama }} {{ $skripsi -> pembimbing[0] -> gelar_belakang }}
					@endif
				</td>
			</tr>	
			<tr>
				<td colspan="3">
					</br>
					Mengetahui
					</br>
					Ketua Progam Studi
					<br/>
					<br/>
					<br/>
					<br/>	
					{{ $mahasiswa -> prodi -> kaprodi }}
				</td>
			</tr>			
		</table>
		
		<script>
			window.print();
		</script>
	</body>
</html>																			