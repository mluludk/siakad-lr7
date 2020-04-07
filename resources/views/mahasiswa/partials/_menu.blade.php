@if($role_id === 4)
<a href="{{ route('biaya.create', $mahasiswa -> id) }}" class="btn btn-info btn-xs btn-flat">Pembayaran</a>
@endif
@if(in_array($role_id, [1,2,8]))
<a href="{{ route('mahasiswa.cetak.kartu', [$mahasiswa -> id, 'uas']) }}" class="btn btn-success btn-xs btn-flat" title="Cetak Kartu UAS">Kartu UAS</a>
<a href="{{ route('mahasiswa.cetak.kartu', [$mahasiswa -> id, 'uts']) }}" class="btn btn-success btn-xs btn-flat" title="Cetak Kartu UTS">Kartu UTS</a>
<a href="{{ route('mahasiswa.cuti.detail', $mahasiswa -> id) }}" class="btn btn-info btn-xs btn-flat" title="Cuti">Cuti</a>
<a href="{{ route('mahasiswa.prestasi', $mahasiswa -> id) }}" class="btn btn-info btn-xs btn-flat">Prestasi</a>
<a href="{{ route('mahasiswa.krs', $mahasiswa -> NIM) }}" class="btn btn-info btn-xs btn-flat">KRS</a>
<a href="{{ route('mahasiswa.krs', $mahasiswa -> NIM) }}/histori" class="btn btn-info btn-xs btn-flat">Histori KRS</a>
<a href="{{ route('mahasiswa.khs', $mahasiswa -> NIM) }}" class="btn btn-info btn-xs btn-flat">KHS</a>
<a href="{{ route('mahasiswa.transkrip', $mahasiswa -> id) }}" class="btn btn-info btn-xs btn-flat" title="Mata Kuliah yang sudah ditempuh">Transkrip Nilai</a>
@if($mahasiswa -> skripsi_id != null && $mahasiswa -> skripsi_id > 0)
<a href="{{ route('skripsi.show', $mahasiswa -> skripsi_id) }}" class="btn btn-info btn-xs btn-flat">Skripsi</a>
@endif
@endif