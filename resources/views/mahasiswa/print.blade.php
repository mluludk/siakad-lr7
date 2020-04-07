<!DOCTYPE html>
<html>
	<head>
		<title>Kartu Hasil Studi @if(!isset($mhs)) @else {{ ' - ' . $mhs['nama'] . ' (' . $mhs['NIM'] . ')' }} @endif</title>
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
		<?php
			$role_id = \Auth::user() -> role_id;
		?>
		<img src="{{ asset('/images/header.png') }}" />
		<div class="sub-header">
			<h4><u>KARTU HASIL STUDI</u></h4>
		</div>
		<table style="margin-bottom: 10px">
			<tr>
				<td width="12%">Nama</td><td width="3%">:</td><td width="30%">{{ $mhs['nama'] }}</td>
				<td width="17%">Prodi</td><td width="3%">:</td><td width="35%">{{ $mhs['prodi'] -> nama }}</td>
			</tr>			
			<tr>
				<td>NIM</td><td>:</td><td>{{ $mhs['NIM'] }}</td>
				<td>Tahun Akademik</td><td>:</td><td>{{ $nilai[array_keys($nilai)[0]][0]['ta'] }}</td>
			</tr>			
			<tr>
				<td>NIRM</td><td>:</td><td>{{ $mhs['NIRM'] }}</td>
				<td>Semester</td><td>:</td><td>{{ array_keys($nilai)[0] }}</td>
			</tr>			
		</table>
		@if(!count($nilai))
		<p class="text-muted">Data KHS belum ada</p>
		@else
		<?php
			$sks_kumulatif = 0;
			$sksn_kumulatif = 0;
		?>
		@foreach($nilai as $semester => $n)
		<div class="nilai">
			<?php 
				$c = 1; 
				$jsks = 0;
				$jsksn = 0;
				$is_locked = false;
			?>
			@if($all)
			<span class="semester">Semester {{ $semester }} ({{ $n[0]['ta'] }})</span>
			@endif
			<table border="1">
				<tr><th>No</th><th>Kode</th><th>Mata Kuliah</th><th>Nilai</th><th>SKS</th><th>sksN</th></tr>
				@foreach($n as $nn)
				<?php 
					if($locked[$n[0]['ta2']]['uts'] || $locked[$n[0]['ta2']]['uas']) $is_locked = true;
					$sksn = array_key_exists($nn['nilai'], config('custom.konversi_nilai.base_4')) ? config('custom.konversi_nilai.base_4')[$nn['nilai']] * $nn['sks'] : 0; 
				?>
				<tr>
					<td>{{ $c }}</td>
					<td>{{ $nn['kode'] }}</td>
					<td>{{ $nn['matkul'] }}</td>
					<td>@if($is_locked && $role_id > 2) X @else {{ $nn['nilai'] }}@endif</td>
					<td>{{ $nn['sks'] }}</td>
					<td>{{ $sksn }}</td>
				</tr>
				<?php 
					$c++; 
					$jsks += $nn['sks'];
					$jsksn += $sksn;
				?>
				@endforeach
				
				
				@if($c <= 8)
				@while($c <= 8) <tr><td>&nbsp;</td><td></td><td></td><td></td><td></td><td></td></tr> <?php $c++; ?>@endwhile
				@endif
				<?php
					$ip = $jsks < 1 ? 0: number_format($jsksn / $jsks, 2);
				?>
				<tr><td></td><td></td><td></td><td><strong>Jumlah</strong></td><td>{{ $jsks }}</td><td>{{ $jsksn }}</td></tr>
				<tr><td></td><td></td><td></td><td><strong>IP</strong></td><td></td><td>{{ $ip }}</td></tr>
				
			</table>
			<?php
				$sks_kumulatif += $jsks;
				$sksn_kumulatif += $jsksn;
			?>
		</div>
		@endforeach
		
		<?php
			$ipk = $sks_kumulatif  < 1 ? 0: round($sksn_kumulatif  / $sks_kumulatif , 2);
		?>
		@if($all)
		<table style="width: auto;">
			<tr><td>Kredit Kumulatif</td><td>: {{ $sks_kumulatif }}</td></tr>
			<tr><td>SksN Kumulatif</td><td>: {{ $sksn_kumulatif }}</td></tr>
			<tr><td>Indeks Prestasi Kumulatif</td><td>: {{ $ipk }}</td></tr>
			<tr><td>Predikat</td><td>: {{  predikat($ipk) }}</td></tr>
		</table>
		@endif
		<table>
			<tr><td width="70%"></td><td width="30%">
				{{ config('custom.profil.alamat.kabupaten') }}, {{ formatTanggal(date('Y-m-d')) }}
			</td></tr>
			<tr><td></td>
				<td>
					KAPRODI
					@if($mhs -> prodi -> kepala -> ttd != '')
					<img src="{{ url('/getimage/' . $mhs -> prodi -> kepala -> ttd) }}" style="display: block;max-width: 200px;"/>
					@else
					<br/>
					<br/>
					<br/>
					<br/>	
					@endif
					{{$mhs -> prodi -> kepala -> gelar_depan }} {{$mhs -> prodi -> kepala -> nama }}{{$mhs -> prodi -> kepala -> gelar_belakang }}
					
				</td>
			</tr>
		</table>
		<script>
			window.print();
		</script>
		@endif
	</body>
</html>																			