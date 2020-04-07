@push('styles')
<style>
	#preview{
	width: 166px;
	height: 220px;
	padding: 5px;
	margin: 15px auto;
	border: 1px solid #999;
	position: relative;
	overflow: hidden;
	}
	
	#preview img {
	max-width: 100%;
	max-height: 100%;
	width: auto;
	height: auto;
	position: absolute;
	left: 50%;
	top: 50%;
	-webkit-transform: translate(-50%, -50%);
	-moz-transform: translate(-50%, -50%);
	-ms-transform: translate(-50%, -50%);
	transform: translate(-50%, -50%);
	}
	.status{
	width: 100%;
	text-align: center;
	margin-bottom: 10px;
	}
	.sidebar-menu-small h5{
	text-align: center;
	background-color: #023355;
	color: white;
	padding: 5px;
	}
	.sidebar-menu-small ul{
    list-style: none;
	}
	.sidebar-menu-small {
    list-style: none;
    margin: 0;
    padding: 0
	}
	.sidebar-menu-small > li {
    position: relative;
    margin: 0;
    padding: 0
	}
	.sidebar-menu-small > li > a {
    padding: 5px 2px 5px 12px;
    display: block
	}
	.sidebar-menu-small > li > a > .fa{
    width: 20px
	}
	
	.sidebar-menu-small > li > a {
	border-left: 3px solid transparent;
	color: #120101;
	border-bottom: 1px solid #bbb;
	}
	.sidebar-menu-small > li:hover > a,
	.sidebar-menu-small > li.active > a {
	color: #3c8dbc;
	background: #f5f9fc;
	border-left-color: #3c8dbc
	}
	
	.treeview ul{
	margin: 0;
	padding: 0;
	}
	.treeview > ul > li{
	padding-left: 10px;
	}
	.treeview-menu>li>a {
    padding: 5px 5px 5px 15px;
    display: block;
    font-size: 14px;
	}
	.sidebar-menu-small .treeview-menu>li>a:hover {
	color: #fff;
	background-color: #023355;
	}
</style>
@endpush

<li><a href="{{ url('mahasiswa', $mahasiswa -> id) }}">Detil Mahasiswa</a></li>
<li><a href="{{ route('mahasiswa.aktivitas', $mahasiswa -> id) }}" >Aktivitas Perkuliahan</a></li>
<li><a href="{{ route('mahasiswa.kemajuan', $mahasiswa -> id) }}" >Tanggungan SKS</a></li>

<li class="treeview">
	<a href="#" >KRS Mahasiswa<span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span></a>
	<ul class="treeview-menu hidden">
		<li><a href="{{ route('mahasiswa.krs', $mahasiswa -> NIM) }}" title="KRS Mahasiswa">KRS Mahasiswa</a></li>
		<li><a href="{{ route('mahasiswa.krs', $mahasiswa -> NIM) }}/histori" title="History Nilai">History KRS</a></li>
	</ul>
</li>

<li><a href="{{ route('mahasiswa.khs', $mahasiswa -> NIM) }}" title="RHS Mahasiswa">History Nilai</a></li>

<li class="treeview">
	<a href="#" >Cetak Kartu<span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span></a>
	<ul class="treeview-menu hidden">
		<li><a href="{{ route('mahasiswa.cetak.kartu', [$mahasiswa -> id, 'uts']) }}" title="Cetak Kartu UTS">Kartu UTS</a></li>
		<li><a href="{{ route('mahasiswa.cetak.kartu', [$mahasiswa -> id, 'uas']) }}" title="Cetak Kartu UAS">Kartu UAS</a></li>
		<li><a href="{{ route('mahasiswa.cetak.kartu', [$mahasiswa -> id, 'ktm']) }}" >Kartu KTM</a></li>
	</ul>
</li>

<li class="treeview">
	<a href="#" >Informasi Nilai<span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span></a>
	<ul class="treeview-menu hidden">
		<li><a href="{{ route('mahasiswa.khs', $mahasiswa -> NIM) }}" title="Kartu Hasil Studi">Kartu Hasil Studi</a></li>
		<li><a href="{{ route('mahasiswa.transkrip', $mahasiswa -> id) }}" title="Mata Kuliah yang sudah ditempuh">Traskip Nilai</a></li>
	</ul>
</li>

<li class="treeview">
	<a href="#" >Karya Mahasiswa<span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span></a>
	<ul class="treeview-menu hidden">
		<li><a href="{{ route('mahasiswa.prestasi', $mahasiswa -> id) }}" >Prestasi</a></li>
		<li><a href="{{ route('mahasiswa.buku.daftar', $mahasiswa -> id) }}" >Buku</a></li>
		<li><a href="{{ route('mahasiswa.jurnal.daftar', $mahasiswa -> id) }}" >Jurnal</a></li>
		<li><a href="{{ route('mahasiswa.tulisan.daftar', $mahasiswa -> id) }}" >Tulisan</a></li>
		<li><a href="{{ route('mahasiswa.penelitian.daftar', $mahasiswa -> id) }}" >Penelitian</a></li>
	</ul>
</li>

<li class="treeview">
	<a href="#" >Informasi Keuangan<span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span></a>
	<ul class="treeview-menu hidden">
		<li><a href="{{ route('mahasiswa.tagihan', $mahasiswa -> id) }}" title="Status Pembayaran" >Status Pembayaran</a></li>
		<li><a href="{{ route('mahasiswa.pembayaran', $mahasiswa -> id) }}" title="Riwayat Pembayaran">Riwayat Pembayaran</a></li>
	</ul>
</li>

<li class="treeview">
	<a href="#" >Informasi Pendaftaran<span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span></a>
<ul class="treeview-menu hidden">
<li><a href="{{ route('mahasiswa.ppl.status', $mahasiswa -> id) }}" title="Program Pengalaman Lapangan" >PPL</a></li>
<li><a href="{{ route('mahasiswa.pkm.status', $mahasiswa -> id) }}" title="Program Kreativitas Mahasiswa ">PKM</a></li>
<li><a href="{{ route('mahasiswa.wisuda.status', $mahasiswa -> id) }}" title="Wisuda">WISUDA</a></li>
</ul>
</li>

@if($mahasiswa -> skripsi_id != null && $mahasiswa -> skripsi_id > 0)
<li><a href="{{ route('skripsi.show', $mahasiswa -> skripsi_id) }}" title="Skripsi Saya">Skripsi Saya</a></li>
@endif	


<li class="treeview">
<a href="#" >Cuti Mahasiswa<span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span></a>
<ul class="treeview-menu hidden">
<li><a href="{{ route('mahasiswa.cuti.create', $mahasiswa -> id) }}" >Pengajuan Cuti</a></li>
<li><a href="{{ route('mahasiswa.cuti.detail', $mahasiswa -> id) }}" title="Cuti">Riwayat Cuti</a></li>
</ul>
</li>

@push('scripts')
<script>
$(document).on('click', '.treeview > a', function(){
var el = $(this).next('ul');
if(el.hasClass('hidden')) el.removeClass('hidden'); else el.addClass('hidden');
return false;
});
</script>
@endpush