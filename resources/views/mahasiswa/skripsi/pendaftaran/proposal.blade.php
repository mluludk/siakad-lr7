<!DOCTYPE html>
<html>
	<head>
		<title>FORMULIR PENDAFTARAN UJIAN PROPOSAL SKRIPSI (Setting Print: Gunakan ukuran kertas Legal & Margin Minimum)</title>
		<style>
			/* 1cm == 37.8px */			
			body{
			padding: 20px;
			font-family: tahoma;
			line-height: 25px;
			}
			.page{
			width: 21cm;
			height: 35.56cm;
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
		<div class="page">
			<img src="{{ asset('/images/header.png') }}" />
			<div class="sub-header">
				<h4>FORMULIR PENDAFTARAN UJIAN PROPOSAL SKRIPSI </h4>
				<h4 style="line-height: 20px">
					{{ strtoupper(config('custom.profil.nama')) }} <br/>
					TAHUN AKADEMIK {{ substr($skripsi -> tapel -> nama, 0, 10) }}				
				</h4>
			</div>
			<table>
				<tr>
					<td valign="top" colspan="3">
						Yang bertanda tangan di bawah ini:	
					</td>
				</tr>
				<tr>
					<td width="31%">Nama</td><td width="10px">:</td><td>{{ $mahasiswa -> nama }}</td>
				</tr>			
				<tr>
					<td>Tempat & Tgl. Lahir</td><td>:</td><td>{{ $mahasiswa -> tmpLahir }}, {{ $mahasiswa -> tglLahir }}</td>
				</tr>	
				<tr>
					<td>NIM/NIRM</td><td>:</td><td>{{ $mahasiswa -> NIM }}</td>
				</tr>			
				<tr>
					<td>Semester</td><td>:</td><td>{{ $mahasiswa -> semesterMhs }}</td>
				</tr>			
				<tr>
					<td>Jumlah SKS*</td><td>:</td><td>{{ $total_sks }}</td>
				</tr>		
				<tr>
					<td valign="top">Alamat rumah/Telp.</td><td valign="top">:</td><td>{{ $alamat }}</td>
				</tr>			
				<tr>
					<td>Program Studi</td><td>:</td><td>{{ $mahasiswa -> prodi -> strata }} {{ $mahasiswa -> prodi -> nama }}</td>
				</tr>			
				<tr>
					<td valign="top">Judul Proposal Skripsi</td><td valign="top">:</td><td valign="top">{{ strtoupper($skripsi -> judul) }}</td>
				</tr>			
				<tr>
					<td>Tanggal Penyerahan Proposal**</td><td>:</td><td>.........................................................</td>
				</tr>		
				<tr>
					<td valign="top" colspan="3">
						Dengan ini saya mendaftarkan diri sebagai peserta <strong>Ujian  Proposal Skripsi</strong> pada semester {{ $mahasiswa -> semester }} 
						Tahun Akademik {{ substr($skripsi -> tapel -> nama, 0, 10) }} {{ config('custom.profil.nama') }} dengan melengkapi persyaratan serta berkas-berkas yang diperlukan yaitu:
						<ol>
							<li><strong>Menggandakan Proposal Skripsi 2 eksemplar</strong> (Jilid Lem Panas / <em>Perfect Binding</em> / jilid terusan) dengan cover warna hijau.</li>
							<li>Kertas <strong>"Lembar persetujuan ujian proposal skripsi"</strong> harap dijilid bersama proposal (diletakkan dibelakang cover) dan tandatangan kaprodi wajib disetempel basah</li>
							<li><strong>Maksimal 2 hari</strong> setelah pendaftaran berkas wajib dikumpulkan ke kaprodi</li>
							<li><strong>Semua berkas dimasukkan ke</strong> dalam map plastik kancing warna kuning</li>
							<li><strong>Jika Persyaratan di atas</strong> tidak terpenuihi maka pendaftaran dibatalkan</li>
						</ol>
					</td>
				</tr>			
			</table>
			<br/>
			<table>
				<tr>
					<td width="70%"></td>
					<td>
						{{ config('custom.profil.alamat.kabupaten') }}, {{ date('d-m-Y') }}
						<br/>
						Pendaftar,
						<br/><br/><br/>
						{{ $mahasiswa -> nama }}
					</td>
				</tr>			
			</table>
			<br/>
			<table style="font-size: 12px;line-height: 20px">
				<tr>
					<td>
						Keterangan: <br/>
						* Jumlah keseluruhan SKS yang sudah ditempuh dan dinyatakan lulus.<br/>
						** Diisi oleh staf akademik
					</td>
				</tr>			
			</table>
		</div>
		
		<div class="page">
			<img src="{{ asset('/images/header.png') }}" />
			<div class="sub-header">
				<h4>LEMBAR PERSETUJUAN UJIAN {{ strtoupper($jenis) }} @if($jenis == 'komprehensif') & @endif SKRIPSI</h4>
			</div>
			<table>
				<tr>
					<td width="25%">Nama</td><td width="10px">:</td><td>{{ $mahasiswa -> nama }}</td>
				</tr>			
				<tr>
					<td>NIM/NIRM</td><td>:</td><td>{{ $mahasiswa -> NIM }}</td>
				</tr>			
				<tr>
					<td>Program Studi</td><td>:</td><td>{{ $mahasiswa -> prodi -> strata }} {{ $mahasiswa -> prodi -> nama }}</td>
				</tr>			
				<tr>
					<td valign="top">Judul Proposal Skripsi</td><td valign="top">:</td><td valign="top">{{ $skripsi -> judul }}</td>
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
						@if($skripsi -> pembimbing[0] -> ttd != '')
						<img src="{{ url('/getimage/' . $skripsi -> pembimbing[0] -> ttd) }}" style="display: block;max-width: 200px;"/>
						@else
						<br/>
						<br/>
						<br/>
						<br/>	
						@endif
						{{ $skripsi -> pembimbing[0] -> gelar_depan }} {{ $skripsi -> pembimbing[0] -> nama }} {{ $skripsi -> pembimbing[0] -> gelar_belakang }}
						@endif
					</td>
				</tr>	
				<tr>
					<td colspan="3">
						</br>
						Mengetahui
						</br>
						Ketua Program Studi
						@if($mahasiswa -> prodi -> kepala -> ttd != '')
						<img src="{{ url('/getimage/' . $mahasiswa -> prodi -> kepala -> ttd) }}" style="display: block;max-width: 200px;"/>
						@else
						<br/>
						<br/>
						<br/>
						<br/>	
						@endif
						{{$mahasiswa -> prodi -> kepala -> gelar_depan }} {{$mahasiswa -> prodi -> kepala -> nama }}{{$mahasiswa -> prodi -> kepala -> gelar_belakang }}
					</td>
				</tr>			
			</table>
		</div>
		<script>
			window.print();
		</script>
	</body>
</html>																																					