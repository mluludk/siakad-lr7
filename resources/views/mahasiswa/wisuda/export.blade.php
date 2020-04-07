<?php
	$c = 1;
?>
<table>
	<thead>
		<tr>
			<th>No</th>
			<th></th>
			<th>NIM</th>
			<th>Nama</th>
			<th>Alamat</th>
			<th>No. Hp</th>
			<th>Tinggi Badan (cm)</th>
			<th>PRODI</th>
			<th>Program</th>
			<th>Judul Skripsi</th>
		</tr>
	</thead>
	<tbody>
		@if(count($rdata) < 1) 
		<tr>
			<td colspan="10">Data tidak ditemukan</td>
		</tr>
		@else
		@foreach($rdata as $d)
		<?php 
			$ffile = $d -> foto != '' ? str_replace('\\', '/', storage_path()) . '/app/upload/images/' . $d -> foto : ''; 
			
			$alamat = '';
			if($d['jalan'] != '') $alamat .= 'Jl. ' . $d['jalan'] . ' ';
			if($d['dusun'] != '') $alamat .= $d['dusun'] . ' ';
			if($d['rt'] != '') $alamat .= 'RT ' . $d['rt'] . ' ';
			if($d['rw'] != '') $alamat .= 'RW ' . $d['rw'] . ' ';
			if($d['kelurahan'] != '') $alamat .= $d['kelurahan'] . ' ';
			if($d['id_wil'] != '') 
			{
				$wilayah2 = \Siakad\Wilayah::dataKecamatan($d['id_wil']) -> first();
				if($wilayah2)
				$alamat .= trim($wilayah2 -> kec) . ' ' . trim($wilayah2 -> kab) . ' ' . trim($wilayah2 -> prov) . ' ';
			}
			if($d['kodePos'] != '') $alamat .= $d['kodePos'];
		?>
		<tr>
			<td>{{ $c }}</td>
			<td>
				@if($d -> foto != '' && file_exists($ffile))
				<img src="{{ $ffile }}"/>
				@endif
			</td>
			<td>{{ $d -> NIM }}</td>
			<td>{{ $d -> nama }}</td>
			<td>{{ $alamat }}</td>
			<td>
				@if(isset($d -> telp)){{ $d -> telp }}
				@else
				{{ $d -> hp }}
				@endif
			</td>
			<td>{{ $d -> nama }}</td>
			<td>{{ $d -> tinggi_badan }}</td>
			<td>{{ $d -> prodi -> strata }} {{ $d -> prodi -> nama }}</td>
			<td>{{ $d -> kelas -> nama }}</td>
			<td>{{ $d -> skripsi -> judul ?? '' }}</td>
		</tr>
		<?php
			$c++;
		?>
		@endforeach
		@endif
	</tbody>
</table>																																																																						