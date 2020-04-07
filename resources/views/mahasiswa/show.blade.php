@extends('app')

@section('title')
Data Mahasiswa - {{ $mahasiswa -> nama ?? ''}}
@endsection

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
</style>
@endpush

@section('header')
<section class="content-header">
	<h1>
		Mahasiswa
		<small>Detail</small>
	</h1>
	<ol class="breadcrumb">
		<li><a href="/"><i class="fa fa-dashboard"></i> Home</a></li>
		<li><a href="{{ url('/mahasiswa') }}"> Mahasiswa</a></li>
		<li class="active">{{ ucwords(strtolower($mahasiswa -> nama)) }}</li>
	</ol>
</section>
@endsection

@section('content')
<?php
	$config = config('custom.pilihan');
?>
<div class="row">
	<div class="col-sm-3">
		<div class="box box-primary">
			<div class="box-header with-border">
				<h3 class="box-title">Nama : {{ $mahasiswa -> NIM }} / {{ ucwords(strtolower($mahasiswa -> nama)) }}</h3>
			</div>
			<div class="box-body" style="padding-left: 0px;">
				<div id="preview">
					<img src="@if(isset($mahasiswa->foto) and $mahasiswa->foto != '')/getimage/{{ $mahasiswa->foto }} @else/images/b.png @endif"></img>
				</div>
				<div class="status">
					@if($mahasiswa -> statusMhs == 1)
					<span class="label label-success">{{ $config['statusMhs'][$mahasiswa -> statusMhs] }}</span>
					@else
					<span class="label label-default">{{ $config['statusMhs'][$mahasiswa -> statusMhs] }}</span>
					@endif
				</div>
				
				<ul class="sidebar-menu-small">
					<li><h5>AKSI CEPAT</h5></li>
					@include('mahasiswa.partials._menu2', ['role_id' => \Auth::user() -> role_id, 'mahasiswa' => $mahasiswa])
					<!--li><a href="{{ route('mahasiswa.edit', $mahasiswa -> id) }}" class="btn btn-warning btn-xs btn-flat" title="Edit data dosen"><i class="fa fa-pencil-square-o"></i> Edit Data</a>-->
				</ul>
			</div>
		</div>
	</div>
	
	<div class="col-sm-9">
		<div class="box box-primary">
			<div class="box-header with-border">	
				<div class="box-tool pull-right">
					<a onclick="javascript:history.back()" class="btn btn-default btn-xs btn-flat" title="Daftar"><i class="fa fa-list"></i> DAFTAR</a>
					<a href="{{ route('mahasiswa.edit', $mahasiswa -> id) }}" class="btn btn-warning btn-xs btn-flat" title="Edit data Mahasiswa"><i class="fa fa-pencil-square-o"></i> EDIT DATA</a>	
				</div>
			</div>
			<div class="box-body">
				<div class="row">
					<div class="col-xs-12">
						<table width="100%">
							<tbody>
								<tr><th width="20%">NAMA</th><td width="30%">: {{ $mahasiswa->nama }}</td><th width="20%">NAMA IBU</th><td>: {{ $mahasiswa->namaIbu }}</td></tr>
								<tr><th>TEMPAT LAHIR</th><td>: {{ $mahasiswa->tmpLahir }}</td><th>TANGGAL LAHIR</th><td>: {{ $mahasiswa->tglLahir }}</td></tr>
								<tr><th>JENIS KELAMIN</th><td>: {{ $config['jenisKelamin'][$mahasiswa -> jenisKelamin] }}</td><th>AGAMA</th><td>: {{ $config['agama'][$mahasiswa->agama] }}</td></tr>
								<tr><th>STATUS AKTIF</th><td>: {{ $config['statusMhs'][$mahasiswa -> statusMhs] }}</td><th>NIM</th><td>: {{ $mahasiswa->NIM }}</td></tr>
							</tbody>
						</table>
					</div>
				</div>		
			</div>
		</div>
		
		<div class="box box-info">			
			<ul class="nav nav-tabs">
				<li class="active"><a href="#alamat" data-toggle="tab">ALAMAT</a></li>
				<li><a href="#ortu" data-toggle="tab">ORANG TUA</a></li>
				<li><a href="#wali" data-toggle="tab">WALI</a></li>
				<li><a href="#akademik" data-toggle="tab">AKADEMIK</a></li>
			</ul>
			<div class="tab-content">
				<div class="tab-pane active" id="alamat">
					<table class="table table-bordered table-striped">
						<tbody>
							<tr>
								<th width="18%">Nama</th>
								<td colspan="5">{{ $mahasiswa->nama }}</td>
							</tr>
							<tr>
								<th>NIK</th>
								<td colspan="5">{{ $mahasiswa->NIK }}</td>
							</tr>
							<tr>
								<th>TTL</th>
								<td colspan="5">@if($mahasiswa->tmpLahir != ''){{ $mahasiswa->tmpLahir }}, @endif {{ $mahasiswa->tglLahir }}</td>
							</tr>
							<tr>
								<th>NISN</th>
								<td colspan="5">{{ $mahasiswa->NISN ?? "-"}}</td>
							</tr>
							<tr><th>NPWP</th><td colspan="5">{{ $mahasiswa->NPWP }}</td></tr>
							<tr><th>Kewarganegaraan</th><td colspan="5">{{ $config['statusWrgNgr'][$mahasiswa -> statusWrgNgr] }} {{ $mahasiswa -> kewarganegaraan -> nama }}</td></tr>
							<tr><th>Jalan</th><td colspan="5">Jl. {{ $mahasiswa -> jalan }}</td></tr>
							<tr><th>Dusun</th><td width="20%">{{ $mahasiswa -> dusun }}</td><th width="10%">RT</th><td>{{ $mahasiswa -> rt }}</td><th width="10%">RW</th><td>{{ $mahasiswa -> rw }}</td></tr>
							<tr><th>Kelurahan</th><td>{{ $mahasiswa -> kelurahan }}</td><th>Kode Pos</th><td colspan="3">{{ $mahasiswa -> kodePos }}</td></tr>
							<tr><th>Kecamatan</th><td colspan="5">{{ $kec }}</td></tr>
							<tr><th>Jenis Tinggal</th><td colspan="5">{{ $config['mukim'][$mahasiswa -> mukim] }}</td></tr>
							<tr><th>Jenis Pembiayaan</th><td colspan="5">@if(isset($config['jenisPembayaran'][$mahasiswa -> jenisPembayaran])){{ $config['jenisPembayaran'][$mahasiswa -> jenisPembayaran] }}@endif</td></tr>
							<tr><th>Alat Transportasi</th><td colspan="5">{{ $config['transportasi'][$mahasiswa -> transportasi] }}</td></tr>
							<tr><th>Telepon</th><td>{{ $mahasiswa->telp }}</td><th>HP</th><td colspan="3">{{ $mahasiswa -> hp }}</td></tr>
							<tr><th>Email</th><td colspan="5">{{ $mahasiswa->email }}</td></tr>
							<tr>
								<th>Penerima KPS?</th>
								<td>
									@if($mahasiswa -> kps == 'Y')
									Ya
									@else
									Tidak
									@endif
								</td>
							<th>No. KPS</th><td>{{ $mahasiswa -> noKps }}</td></tr>
						</tbody>
					</table>
				</div>
				<div class="tab-pane" id="ortu">
					<table class="table table-bordered table-striped">
						<tbody>
							<tr><th width="18%">AYAH</th><td> {{ $mahasiswa -> namaAyah }}</td><th>IBU</th><td>{{ $mahasiswa->namaIbu }}</td></tr>
							<tr><th>NIK</th><td>{{ $mahasiswa->NIKAyah }}</td><th>NIK</th><td>{{ $mahasiswa->NIKIbu }}</td></tr>
							<tr><th>Tanggal Lahir</th><td>{{ $mahasiswa->tglLahirAyah }}</td><th>Tanggal Lahir</th><td>{{ $mahasiswa->tglLahirIbu }}</td></tr>
							<tr>
								<th>Pendidikan</th><td>{{ $config['pendidikanOrtu'][$mahasiswa -> pendidikanAyah] ?? '-' }}</td>
								<th>Pendidikan</th><td>{{ $config['pendidikanOrtu'][$mahasiswa -> pendidikanIbu] ?? '-' }}</td>									
							</tr>
							<tr>
								<th>Pekerjaan</th><td>{{ $config['pekerjaanOrtu'][$mahasiswa -> pekerjaanAyah] ?? '-' }}</td>
								<th>Pekerjaan</th><td>{{ $config['pekerjaanOrtu'][$mahasiswa -> pekerjaanIbu] ?? '-' }}</td>									
							</tr>
							<tr>
								<th>Penghasilan</th><td>{{ $config['penghasilanOrtu'][$mahasiswa -> penghasilanAyah] ?? '-' }}</td>
								<th>Penghasilan</th><td>{{ $config['penghasilanOrtu'][$mahasiswa -> penghasilanIbu] ?? '-'  }}</td>									
							</tr>
						</tbody>
					</table>							
				</div>
				<div class="tab-pane" id="wali">
					<table class="table table-bordered table-striped">
						<tbody>
							<tr>
								<th width="15%">NIK</th><td colspan="5">{{ $mahasiswa->NIKWali ?? "-"}}</td>
							</tr>
							<tr>
								<th>Nama</th><td colspan="5">{{ $mahasiswa->namaWali ?? "-"}}</td>
							</tr>
							<tr>
								<th>Tanggal Lahir</th><td colspan="5">{{ $mahasiswa->tglLahirWali ?? "-"}}</td>
							</tr>
							<tr>
								<th>Pendidikan</th><td colspan="5">{{ $config['pendidikanOrtu'][$mahasiswa -> pendidikanWali] ?? '-' }}</td>	
							</tr>
							<tr>
								<th>Pekerjaan</th><td colspan="5">{{ $config['pekerjaanOrtu'][$mahasiswa -> pekerjaanWali] ?? '-' }}</td>								
							</tr>
							<tr>
								<th>Penghasilan</th><td colspan="5">{{ $config['penghasilanOrtu'][$mahasiswa -> penghasilanWali] ?? '-' }}</td>
							</tr>
						</tbody>
					</table>
				</div>
				<div class="tab-pane" id="akademik">
					<table class="table table-bordered table-striped">
						<tbody>
							<tr>
								<th width="17%">NIM</th><td width="25%">{{ $mahasiswa->NIM }}</td>
								<th width="12%">NIRM</th><td colspan="5">{{ $mahasiswa->NIRM }}</td>
							</tr>
							<tr>
								<th>NIRL 1</th><td>{{ $mahasiswa->NIRL1 }}</td>
								<th>NIRL 2</th><td colspan="5">{{ $mahasiswa->NIRL2 }}</td>
							</tr>
							<tr>
								<th>PRODI</th><td colspan="5">{{ $mahasiswa -> prodi -> strata }} {{ $mahasiswa -> prodi -> nama }}</td>
							</tr>
							<tr>
								<th>Program Kelas</th>
								<td>
									{{ $mahasiswa -> kelas -> nama }}
								</td>
								<th>Jenis Pembiayaan</th>
								<td colspan="3">
									<span class="label label-warning">
										@if(isset($config['jenisPembayaran'][$mahasiswa -> jenisPembayaran])){{ $config['jenisPembayaran'][$mahasiswa -> jenisPembayaran] }}@endif
									</span>
								</td>
							</tr>
							<tr>
								<th>Semester</th>
								<td>{{ $mahasiswa->semesterMhs }}</td>
								<th>Status Keaktifan</th>
								<td colspan="3">
									@if($mahasiswa -> statusMhs == 1)
									<span class="label label-success">{{ $config['statusMhs'][$mahasiswa -> statusMhs] ?? '-'  }}</span>
									@else
									<span class="label label-default">{{ $config['statusMhs'][$mahasiswa -> statusMhs] ?? '-'  }}</span>
									@endif
								</td>
							</tr>
							<tr>
								<th>Dosen Wali</th><td colspan="5">
									{{ $mahasiswa -> dosenwali -> gelar_depan  ?? '-' }} 
									{{ $mahasiswa -> dosenwali -> nama  ?? '-' }} 
									{{ $mahasiswa -> dosenwali -> gelar_belakang  ?? '-' }}
								</td>
							</tr>
							<tr>
								<th>Tahun Masuk</th><td>{{ $mahasiswa -> angkatan ?? '' }} </td> 
								<th>Mulai Masuk</th><td>{{ $mahasiswa -> tglMasuk ?? '' }} </td> 
								<th width="12%">Tahun Akademik</th><td> {{ $tapel -> nama ?? '' }}</td>
							</tr>
							<tr>
								<th>Jenis Daftar</th><td>{{ $config['jenisPendaftaran'][$mahasiswa -> jenisPendaftaran] }}</td>
								<th>Jalur Masuk</th><td colspan="5">{{ $config['jalurMasuk'][$mahasiswa -> jalurMasuk] }}</td>
							</tr>
							<tr>
								<th>Pendidikan Sebelumnya</th>
								<td>
									@if(intval($mahasiswa -> thSMTA)){{ $mahasiswa -> thSMTA }}@endif
									@if($mahasiswa -> jurSMTA != ''), Jurusan {{ $mahasiswa -> jurSMTA }}@endif
								</td>
								<th>NISN</th>
								<td colspan="5">{{ $mahasiswa -> NISN ?? "-"}}</td>
							</tr>
							<tr>
								<th>IPK</th>
								<td>{{ getIPK($mahasiswa -> akm) }}</td>
								<th>SKS LULUS</th>
								<td colspan="3">{{ $kurikulum -> sks_total ?? "-" }} SKS</td>
							</tr>
							<tr>
								<th>LAMA STUDI</th><td>{{ $mahasiswa -> semesterMhs }} Semester</td> 
								<th>CUTI</th><td>{{ $mahasiswa -> cuti -> count() }} Semester</td> 
								<th>SISA STUDI</th><td>{{ 14 - $mahasiswa -> semesterMhs }} Semester</td>
							</tr>
							<tr>
								<th>Judul Skripsi</th>
							<td colspan="5">{{ $mahasiswa -> skripsi -> judul ?? '-' }}</td></tr>
							<tr>
								<th>SK Yudisium</th>
								<td>@if(isset($mahasiswa -> wisuda))
									@if($mahasiswa -> wisuda -> SKYudisium != '')
									{{ $mahasiswa -> wisuda -> SKYudisium }}, <strong>Tanggal:</strong> {{ $mahasiswa -> wisuda -> tglSKYudisium }}
									@else
									- 
									@endif
								@endif</td><th width="10%">No. Ijasah</th><td width="20%">@if($mahasiswa -> noIjazah != ''){{ $mahasiswa -> noIjazah}}, <strong>Tanggal:</strong> {{ $mahasiswa -> tglIjazah  ?? '-'}}@else - @endif</td><th width="10%">Tgl. Keluar</th><td>{{ $mahasiswa -> tglKeluar  ?? '-' }}</td></tr>
						</tbody>
					</table>						
				</div>
			</div>			
		</div>				
	</div>	
</div>			
@endsection	
