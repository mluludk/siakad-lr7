<!DOCTYPE html>
<html>
	<head>
		<title>Transkrip Nilai @if(!isset($mahasiswa)) @else {{ ' - ' . $mahasiswa -> nama . ' (' . $mahasiswa -> NIM . ')' }} @endif</title>
		<style>
			/* 1cm == 37.8px */
			body{
			padding: 20px;
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
			margin: 30px;
			text-align:center;
			}
			img{
			max-width: 100%;
			}
			table{
			font-size: 13px;
			border-collapse: collapse;	
			width: 100%;
			}
			table.profil{
			text-transform: uppercase;
			margin-bottom: 10px
			margin: 10px 0;
			}
			.tabel-nilai td, th{
			font-size: 13px;
			border: 1px solid black;
			}
			.tabel-nilai td:not(:nth-child(3)){
			text-align: center;
			}
			small{
			font-size: 10px;
			}
		</style>
	</head>
	<body>
		<img src="{{ asset('/images/header.png') }}" />
		<div class="sub-header">
			<h4><u>TRANSKRIP NILAI</u></h4>
		</div>
		<table class="profil">
			<tr>
				<td width="12%">Nama</td><td width="1%">:</td><td width="30%">{{ $mahasiswa -> nama }}</td>
			</tr>		
			<tr>
				<td>tempat / tanggal lahir</td><td>:</td><td>{{ $mahasiswa -> tmpLahir }}, {{ $mahasiswa -> tglLahir }}</td>
			</tr>	
			<tr>
				<td>npm / nirm</td><td>:</td><td>{{ $mahasiswa -> NIM }}/{{ $mahasiswa -> NIRM }}</td>
			</tr>	
			<tr>
				<td>nirl</td><td>:</td><td>{{ $mahasiswa -> NIRL }}</td>
			</tr>	
			<tr>
				<td>jurusan</td><td>:</td><td>TARBIYAH</td>
			</tr>		
			<tr>
				<td>program studi</td><td>:</td><td>{{ $mahasiswa -> prodi -> nama }}</td>
			</tr>		
			<tr>
				<td>jenjang</td><td>:</td><td>{{ $mahasiswa -> prodi -> strata }}</td>
			</tr>		
			<tr>
				<td>nomor ijazah</td><td>:</td><td>{{ $mahasiswa -> noIjazah }}</td>
			</tr>	
		</table>
		<br/>
		@if(!$data->count())
		<p class="text-muted">Belum ada nilai yang masuk</p>
		@else
		<?php 
			$c=1; 
			$total = $data -> count();
			$t1 = ceil($total / 2);
			$t2 = $total - $t1;
			
			$jsks = $sksn = $jsksn = 0;
		?>
		<table>
			<tr>
				<td width="50%" valign="top">
					<table class="tabel-nilai">
						<thead>
							<tr style="background-color:#dce3e2;">
								<th>No.</th>
								<th>Kode</th>
								<th>Mata Kuliah</th>
								<th>SKS</th>
								<th>N</th>
								<th>SKS.N</th>
							</tr>
						</thead>
						<tbody>
							@for($n = 0; $n < $t1; $n++)
							<?php
								$sksn = array_key_exists($data[$n] -> nilai, config('custom.konversi_nilai.base_4')) ? config('custom.konversi_nilai.base_4')[$data[$n] -> nilai] * $data[$n] -> sks : 0; 
							?>
							<tr>
								<td>{{ $n + 1 }}</td>
								<td>{{ $data[$n] -> kode }}</td>
								<td>{{ $data[$n] -> matkul }}</td>
								<td>{{ $data[$n] -> sks }}</td>
								<td>{{ $data[$n] -> nilai }}</td>
								<td>{{ $sksn }}</td>
							</tr>
							<?php
								$jsks += $data[$n] -> sks;
								$jsksn += $sksn;
							?>
							@endfor
							<tr>
								<td colspan="6" style="text-align: left; padding: 5px; border-bottom-color: transparent;">Judul Skripsi:</td>
							</tr>
							<tr>
								<td colspan="6" style="padding: 10px 5px 20px 5px;">{{ $mahasiswa -> skripsi -> judul ?? '' }}</td>
							</tr>
						</tbody>
					</table>
				</td>
				
				<td valign="top">
					<table class="tabel-nilai">
						<thead>
							<tr>
								<th>No.</th>
								<th>Kode</th>
								<th>Mata Kuliah</th>
								<th>SKS</th>
								<th>N</th>
								<th>SKS.N</th>
							</tr>
						</thead>
						<tbody>
							@for($n = $t1; $n < $t1 + $t2; $n++)
							<?php
								$sksn = array_key_exists($data[$n] -> nilai, config('custom.konversi_nilai.base_4')) ? config('custom.konversi_nilai.base_4')[$data[$n] -> nilai] * $data[$n] -> sks : 0; 
							?>
							<tr>
								<td>{{ $n + 1}}</td>
								<td>{{ $data[$n] -> kode }}</td>
								<td>{{ $data[$n] -> matkul }}</td>
								<td>{{ $data[$n] -> sks }}</td>
								<td>{{ $data[$n] -> nilai }}</td>
								<td>{{ $sksn }}</td>
							</tr>
							<?php
								$jsks += $data[$n] -> sks;
								$jsksn += $sksn;
							?>
							@endfor
							<tr><td colspan="2" style="text-align: left; padding: 5px; border-bottom-color: transparent; border-right-color: transparent;">Jumlah SKS :  {{ $jsks }}</td><td colspan="4" style="text-align: left; padding: 5px 0px; border-bottom-color: transparent;">Jumlah SKS.N : {{ $jsksn }}</td></tr>
							<tr><td colspan="2" style="text-align: left; padding: 5px; border-bottom-color: transparent; border-right-color: transparent;">IP :  {{ $ipk = $jsks  < 1 ? 0: round($jsksn  / $jsks , 2) }}</td><td colspan="4" style="text-align: left; padding: 5px 0px; border-bottom-color: transparent;">Yudisium : {{ predikat($skala, $ipk) }}</td></tr>
								<tr><td colspan="6" style="text-align: left; padding: 15px 5px;">Keterangan: IP = Σ SKS x N / Σ SKS</td></tr>
							</tbody>
							</table>
						</td>				
					</tr>
				</table>
				<small>* Mata Kuliah UKM</small>
				<br/>
				<br/>
				<table>
					<tr>
						<td width="30%" valign="top">
							{!! config('custom.ttd.transkrip.kiri') !!}
						</td>
						<td width="30%">
							<img style="height: 150px; margin: 0px auto; display: block;" src="@if(isset($mahasiswa->foto) and $mahasiswa->foto != ''){{ url('/getimage/' . $mahasiswa->foto) }} @else {{ asset('/images/b.png') }} @endif"></img>
						</td>
						<td width="30%" valign="top">
						{{ config('custom.profil.alamat.kabupaten') }}, {{ formatTanggal(date('Y-m-d')) }}<br/>
						{!! config('custom.ttd.transkrip.kanan') !!}
						</td>
					</tr>
				</table>
				<script>
					window.print();
				</script>
				@endif
			</body>
		</html>																																				