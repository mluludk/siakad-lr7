@extends('app')

@section('title')
Kartu Hasil Studi @if(!isset($mhs)) @else {{ ' - ' . $mhs['nama'] . ' (' . $mhs['NIM'] . ')' }} @endif
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
	.sidebar-menu-small h5{
	text-align: center;
	background-color: #023355;
	color: white;
	padding: 5px;
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
</style>
@endpush

@section('header')
<?php
	$role_id = \Auth::user() -> role_id;
?>
<section class="content-header">
	<h1>
		Mahasiswa
		<small>Kartu Hasil Studi</small>
	</h1>
	<ol class="breadcrumb">
		<li><a href="/"><i class="fa fa-dashboard"></i> Home</a></li>
		@if($role_id != 512)
		<li><a href="{{ url('/mahasiswa') }}"> Mahasiswa</a></li>
		<li><a href="{{ route('mahasiswa.show', $mhs['id']) }}"> {{ ucwords(strtolower($mhs['nama'])) }}</a></li>
		@endif
		@if(!$all)
		<li><a href="{{ route('mahasiswa.khs', $mhs['NIM']) }}" > Kartu Hasil Studi</a></li>
		<li class="active">{{ $mhs['ta'] }}</li>
		@else
		<li class="active">Kartu Hasil Studi</li>
		@endif
	</ol>
</section>
@endsection

@section('content')
<div class="row">
	<div class="col-sm-3">
		<div class="box box-primary">
			<div class="box-header with-border">
				<h3 class="box-title">Nama : {{ $mhs -> NIM }} / {{ ucwords(strtolower($mhs -> nama)) }}</h3>
			</div>
			<div class="box-body" style="padding-left: 0px;">
				<div id="preview">
					<img src="@if(isset($mhs->foto) and $mhs->foto != '')/getimage/{{ $mhs->foto }} @else/images/b.png @endif"></img>
				</div>
				<div class="status">
					@if($mhs -> statusMhs == 1)
					<span class="label label-success">{{ config('custom.pilihan.statusMhs')[$mhs -> statusMhs] }}</span>
					@else
					<span class="label label-default">{{ config('custom.pilihan.statusMhs')[$mhs -> statusMhs] }}</span>
					@endif
				</div>
				
				@if($role_id != 512)
				<ul class="sidebar-menu-small">
					<li><h5>AKSI CEPAT</h5></li>
					@include('mahasiswa.partials._menu2', ['role_id' => $role_id, 'mahasiswa' => $mhs])
				</ul>
				@endif
				
			</div>
		</div>
	</div>
	<div class="col-sm-9">
		@include('mahasiswa.partials._data', ['mahasiswa' => $mhs])
		<div class="box">
			<div class="box-header with-border">
				<h3 class="box-title"><span class="label label-success">KHS per-semester</span> untuk Mahasiswa, <span class="label label-warning">KHS semua semester</span> untuk dikumpulkan ke KAPRODI | AKADEMIK</h3>
				<div class="box-tools">
					@if(strtolower(Auth::user() -> role -> name) == 'mahasiswa')
					<a href="{{ route('printmykhs') }}" class="btn btn-warning btn-xs btn-flat" title="Cetak semua"><i class="fa fa-print"></i> Cetak semua</a>
					@else
					<a href="{{ route('mahasiswa.khs.cetak', [$mhs['NIM']]) }}" class="btn btn-warning btn-xs btn-flat" title="Cetak semua"><i class="fa fa-print"></i> Cetak semua</a>
					@endif
				</div>
			</div>
			<div class="box-body">
				@if(!count($nilai))
				<p class="text-muted">Data KHS belum ada</p>
				@else
				<?php
					$sks_kumulatif = 0;
					$sksn_kumulatif = 0;
					$t_count=0;
				?>
				<table class="khs">
					<tr>
						@foreach($nilai as $semester => $n)
						<td width="50%" valign="top">
							<?php 
								$c = 1; 
								$jsks = 0;
								$jsksn = 0;
								$disc = 0;
								$is_locked = false;
							?>
							@if($all)
							<span class="semester">Semester {{ $semester }} ({{ $n[0]['ta'] }})
								@if($role_id == 512)
								<a href="{{ route('printmykhs', $n[0]['taid']) }}" class="btn btn-success btn-xs" target="_blank"><i class="fa fa-print"></i> Cetak</a> 	
								@else
								<a href="{{ route('mahasiswa.khs.cetak', [$mhs['NIM'], $n[0]['taid']]) }}" class="btn btn-success btn-xs" target="_blank"><i class="fa fa-print"></i> Cetak</a> 			
								@endif
							</span>
							@endif
							<table class="table table-bordered"@if($t_count % 2 == 0) style="width: 98%;" @endif>
								<tr style="background-color:#dce3e2;">
									<th>No</th>
									<th>Kode</th>
									<th>Mata Kuliah</th>
									<th>Nilai</th>
									<th>SKS</th>
									<th>sksN</th>
								</tr>
								
								<?php 
									if($locked[$n[0]['ta2']]['uts'] || $locked[$n[0]['ta2']]['uas']) $is_locked = true;
								?>
								
								@foreach($n as $nn)
								<?php 
									$sksn = array_key_exists($nn['nilai'], $skala) ? $skala[$nn['nilai']]['angka'] * $nn['sks'] : 0; 
									if(!array_key_exists($nn['nilai'], $skala)) $disc += $nn['sks'];
								?>
								<tr><td>{{ $c }}</td><td>{{ $nn['kode'] }}</td>
									<td>
										{{ $nn['matkul'] }}
										@if($role_id <= 2) 
										<a href="{{ route('mahasiswa.kelas.delete', [$nn['mahasiswa_id'], $nn['matkul_tapel_id']]) }}" title="Hapus kelas">&times;</a>
										@endif
									</td>
									<td>
										@if($is_locked && $role_id > 2)
										<i class="fa fa-times text-danger" data-toggle="popover" 
										data-content="Nilai <strong>belum</strong> bisa ditampilkan karena Anda masih mempunyai tanggungan SPP Semester Aktif"></i>
										@else
										{{ $nn['nilai'] }}
										@endif
									</td>
								<td>{{ $nn['sks'] }}</td><td>{{ $sksn }}</td></tr>
								<?php 
									$c++; 
									$jsks += $nn['sks'];
									$jsksn += $sksn;
								?>
								@endforeach
								
								<!-- if(!$is_locked) -->
									@if($all)
										@if($c <= 9)
											@while($c <= 9) <tr><td>&nbsp;</td><td></td><td></td><td></td><td></td><td></td></tr> <?php $c++; ?>@endwhile
										@endif
									@endif
								
								<?php
									$ip = $jsks < 1 ? 0: number_format($jsksn / $jsks, 2);
								?>
								<tr><td></td><td></td><td></td><td><strong>Jumlah</strong></td><td>{{ $jsks }}</td><td>{{ $jsksn }}</td></tr>
								<tr><td></td><td></td><td></td><td><strong>IPS</strong></td><td></td><td>{{ $ip }}</td></tr>
								<!-- endif	-->			
							</table>
							<?php
								$sks_kumulatif += $jsks;
								$sksn_kumulatif += $jsksn;
								$t_count++;
							?>
						</td>
						@if($t_count % 2 == 0)</tr><tr>@endif
						@endforeach
					</tr>
				</table>
				<?php
					$div = ($sks_kumulatif - $disc) < 1 ? $sks_kumulatif : ($sks_kumulatif - $disc);
					$ipk = $sks_kumulatif  < 1 ? 0: round($sksn_kumulatif  / $div , 2);
					
				?>
				<div class="col-sm-12">
					<table class="table table-bordered" style="width: auto;">
						<tr><td>Kredit Kumulatif</td><td>: {{ $sks_kumulatif }}</td></tr>
						<tr><td>SksN Kumulatif</td><td>: {{ $sksn_kumulatif }}</td></tr>
						<tr><td>Indeks Prestasi Kumulatif</td><td>: {{ $ipk }}</td></tr>
						<tr><td>Predikat</td><td>: {{  predikat($skala, $ipk) }}</td></tr>
					</table>
				</div>
				@endif
			</div>
		</div>
	</div>
</div>
@endsection		

@push('scripts')
<script>
	$(function(){
		$('[data-toggle="popover"]').popover({
			html: true,
			placement: 'auto top',
			trigger: 'hover'
		});
	});
</script>
@endpush