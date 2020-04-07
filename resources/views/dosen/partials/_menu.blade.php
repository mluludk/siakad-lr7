<?php
	$role_id = \Auth::user() -> role_id;
?>
@if($role_id == 128)
<li><a href="{{ route('user.profile') }}">Detil Dosen</a></li>
@else
<li><a href="{{ route('dosen.show', $dosen -> id) }}">Detil Dosen</a></li>
@endif
<li><a href="{{ route('dosen.aktifitasmengajar', $dosen -> id) }}" >Aktifitas Mengajar</a></li>
<li><a href="{{ route('dosen.jurnal', $dosen -> id) }}" > Jurnal Dosen</a></li>
<li><a href="{{ route('dosen.buku', $dosen -> id) }}" > Buku Dosen</a></li>
<li><a href="{{ route('dosen.penelitian', $dosen -> id) }}" > Penelitian Dosen</a></li>
<li><a href="{{ route('dosen.skripsi.mahasiswa', $dosen -> id) }}" > Mahasiswa Bimbingan</a></li>
<li><a href="{{ route('dosen.pendidikan', $dosen -> id) }}" > Riwayat Pendidikan</a></li>
<li><a href="{{ route('dosen.sertifikasi', $dosen -> id) }}" > Riwayat Sertifikasi</a></li>
<li><a href="{{ route('dosen.penugasan', $dosen -> id) }}" > Riwayat Penugasan</a></li>
<li><a href="{{ route('dosen.fungsional', $dosen -> id) }}" > Riwayat Fungsional</a></li>
<li><a href="{{ route('dosen.kepangkatan', $dosen -> id) }}" > Riwayat Kepangkatan</a></li>
