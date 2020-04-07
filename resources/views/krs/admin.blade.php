@extends('app')

@section('title')
Administrasi Kartu Rencana Studi
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
		<small>Kartu Rencana Studi</small>
	</h1>
	<ol class="breadcrumb">
		<li><a href="{{ url('/') }}"><i class="fa fa-dashboard"></i> Home</a></li>
		<li><a href="{{ url('/mahasiswa') }}"> Mahasiswa</a></li>
		<li><a href="{{ route('mahasiswa.show', $mhs -> id) }}"> {{ ucwords(strtolower($mhs -> nama)) }}</a></li>
		<li class="active">Kartu Rencana Studi</li>
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
				
				@if(\Auth::user() -> role_id != 512)
				<ul class="sidebar-menu-small">
					<li><h5>AKSI CEPAT</h5></li>
					@include('mahasiswa.partials._menu2', ['role_id' => \Auth::user() -> role_id, 'mahasiswa' => $mhs])
				</ul>
				@endif
				
			</div>
		</div>
	</div>
	
	<div class="col-sm-9">
		<div class="box box-info">
			<div class="box-body">
				<table width="100%">
					<tr>
						<th width="14%">Nama</th><td>:&nbsp;</td><td>{{ $mhs -> nama }}</td>
						<th width="29%">NIM</th><td>:&nbsp;</td><td>{{ $mhs -> NIM }}</td>
					</tr>
					<tr>
						<th>PRODI</th><td>:&nbsp;</td><td>{{ $mhs -> prodi -> nama }}</td>
						<th>Program</th><td>:&nbsp;</td><td>{{ $mhs -> kelas -> nama }}</td>
					</tr>
					<tr>
						<th>Semester</th><td>:&nbsp;</td><td>{{ hitungSemester($mhs -> tapelMasuk, $tapel -> nama2) }}</td>
						<th>Tahun Akademik</th><td>:&nbsp;</td><td>{{ $tapel -> nama }}</td>
					</tr>
					<tr>
						<th>Dosen PA</th><td>:&nbsp;</td><td>{{ $mhs -> dosenwali -> nama ?? '-'}}</td>
					</tr>
					<tr>
						<th>Status KRS</th><td>:&nbsp;</td>
						<td>
							@if($status -> approved == 'y')
							<span class="label label-success"><i class="fa fa-check"></i> Sudah Validasi</span>
							@else
							<span class="label label-danger"><i class="fa fa-times"></i> Belum Validasi</span>
							@endif
						</td>
						<th>
							Batas akhir pengisian KRS
						</th>
						<td>
							:&nbsp;
						</td>
						<td>
							{{ formatTanggal($tapel -> selesaiKrs) }}
						</td>
					</tr>
				</table>
			</div>
		</div>
		<div class="row">
			<div class="col-sm-9">
				<div class="box">
					<div class="box-header with-border">
						<h3 class="box-title">Kartu Rencana Studi</h3>
					</div>
					<div class="box-body">
						@if(!count($krs))
						<p class="text-muted">
							@if(
							strtotime(date('Y-m-d H:i:s')) 
							>= strtotime($tapel -> selesaiKrs . ' 23:59:59') &&
							$status -> approved != 'y'
							)
							<a href="{{ url('/tawaran/' . $mhs -> id) }}" class="btn btn-warning btn-flat"><i class="fa fa-exclamation-triangle"></i> KRS Susulan</a>
							@else
							Belum ada data 
							@endif
						</p>
						@else
						<?php $c=1; $role_id = Auth::user() -> role_id;?>
						<table class="table table-bordered table-striped">
							<thead>
								<tr style="background-image: -webkit-gradient(linear,0 top,0 bottom,from(#3A8341),to(#609C40)); background-image: -moz-linear-gradient(#3A8341,#054a10);color: #fff;">
									<th width="20px">No</th>
									<th>Kode</th>
									<th>Mata Kuliah</th>
									<th>Program</th>
									<th>Kelas</th>
									<th>Dosen</th>
									<th>SKS</th>
									<th>Waktu</th>
									<th>Ruang</th>
									@if($role_id <=2)
									<th></th>
									@endif
								</tr>
							</thead>
							<tbody>
								@foreach($krs as $g)
								<tr>
									<td>{{ $c }}</td>
									<td>{{ $g -> kode }}</td>
									<td>{{ $g -> nama_matkul }}</td>
									<td>{{ $g -> program }}</td>
									<td>{{ $mhs -> semesterMhs }} {{ $g -> kelas2 }}</td>
									<td>{{ $g -> dosen }}</td>
									<td>{{ $g -> sks }}</td>
									<td>
										@if(isset(config('custom.hari')[$g -> hari])) {{ config('custom.hari')[$g -> hari] }}
										@else
										-
										@endif
										@if($g -> jam_mulai != '')
										,
										@endif
										{{ $g -> jam_mulai ?? '' }}
										@if($g -> jam_mulai != '')
										- 
										@endif
										{{ $g -> jam_selesai ?? '' }}
									</td>
									<td>{{ $g -> ruangan }}</td>
									@if($role_id <=2)
									<?php $matkul_tapel_id = isset($g -> matkul_tapel_id) ? $g -> matkul_tapel_id : 0; ?>
									<th>
										@if($status -> approved == 'y')
										<button class="btn btn-danger btn-flat btn-xs" disabled="disabled" ><i class="fa fa-trash"></i></button>
										@else
										<a href="{{ route('krs.detail.delete', [$g -> krs_id, $matkul_tapel_id]) }}" class="btn btn-danger btn-flat btn-xs"><i class="fa fa-trash"></i></a>
										@endif
									</th>
									@endif
								</tr>
							</tr>
							<?php $c++; ?>
							@endforeach
						</tbody>
					</table>
					
					<br/>
					
					@if(\Auth::user() -> role_id < 10)
					<a href="{{ url('/tawaran/' . $mhs -> id) }}" class="btn btn-primary btn-flat" title="Input Data"><i class="fa fa-plus"></i> Tambah</a>
					@if($status -> approved == 'n')
					<a href="{{ route('mahasiswa.krs', [$mhs -> NIM, 'approve']) }}" class="btn btn-success btn-flat"><i class="fa fa-check"></i> Validasi KRS</a>
					@else
					<a href="{{ route('mahasiswa.krs', [$mhs -> NIM, 'review']) }}" class="btn btn-danger btn-flat"><i class="fa fa-times"></i> Batalkan Validasi KRS</a>
					@endif
					@endif
					
					<a href="{{ route('mahasiswa.krs', [$mhs -> NIM, 'print']) }}" class="btn btn-warning btn-flat" target="_blank"><i class="fa fa-print"></i> Cetak</a>
					@endif
				</div>
				</div>
			</div>
			<div class="col-sm-3">
				<div class="row">
					<div class="col-sm-12 col-xs-4">
						<div class="box box-danger">
							<div class="box-header with-border">
								<h3 class="box-title">SKS Mahasiswa</h3>
								
								<div class="box-tools pull-right">
									<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
									</button>
									<button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
								</div>
							</div>
							<div class="box-body">
								<div id="sks-chart" style="height: 250px;"></div>
							</div>
							<!-- /.box-body -->
						</div>
					</div>
					<div class="col-sm-12 col-xs-4">
						<div class="box box-warning">
							<div class="box-header with-border">
								<h3 class="box-title">IP Mahasiswa</h3>						
								<div class="box-tools pull-right">
									<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
									</button>
									<button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
								</div>
							</div>
							<div class="box-body">
								<div id="ip-chart" style="height: 250px;"></div>
							</div>
							<!-- /.box-body -->
						</div>
					</div>
					<div class="col-sm-12 col-xs-4">
						<div class="box box-success">
							<div class="box-header with-border">
								<h3 class="box-title">Perbandingan Nilai</h3>
								
								<div class="box-tools pull-right">
									<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
									</button>
									<button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
								</div>
							</div>
							<div class="box-body">
								<div id="nilai-donut" style="height: 250px;"></div>
							</div>
							<!-- /.box-body -->
						</div>
					</div>
				</div>
			</div>
		</div>
		@endsection			
		
		@push('scripts')
		<script src="{{ url('/js/jquery.flot.min.js') }}"></script>
		<script src="{{ url('/js/jquery.flot.resize.min.js') }}"></script>
		<script src="{{ url('/js/jquery.flot.pie.min.js') }}"></script>
		<script>
			$(function () {
			var sks = {
				data: [
				@if(count($sksm) > 0)
				@foreach($sksm as $sks) [{{ $sks -> semester }}, {{ $sks -> sksn }}], @endforeach
				@endif
				],
				color: "#dd4b39"
			};
			var ip = {
				data: [
				@if(count($ipm) > 0)
				@foreach($ipm as $semester => $ip) [{{ $semester }}, {{ $ip }}], @endforeach
				@endif
				],
				color: "#f39c12"
			};
			
			var nilai = [
			@if(count($nilaim) > 0)
			@foreach($nilaim as $nilai)
			{ label: "{{ $nilai -> nilai }}",  data: [{{ $nilai -> jumlah }}]},
			@endforeach
			@endif
			];
			
			var options = {
			grid: {
			hoverable: true,
			borderColor: "#f3f3f3",
			borderWidth: 1,
			tickColor: "#f3f3f3"
			},
			series: {
			shadowSize: 0,
			lines: {
			show: true
			},
			points: {
			show: true
			}
			},
			lines: {
			fill: false,
			color: ["#3c8dbc"]
			},
			yaxis: {
			show: true,
			tickDecimals: 0
			},
			xaxis: {
			show: true,
			tickDecimals: 0
			},
			legend: {
			show: false
			}
			};
			
			$.plot("#sks-chart", [sks], options);
			$.plot("#ip-chart", [ip], options);
			
			$.plot('#nilai-donut', nilai, {
			series: {
			pie: {
			show: true
			}
			},
			legend: {
			show: false
			}
			});
			
			$('<div class="tooltip-inner" id="sks-chart-tooltip"></div>').css({
			position: "absolute",
			display: "none",
			opacity: 0.8
			}).appendTo("body");
			
			$("#sks-chart").bind("plothover", function (event, pos, item) {			
			if (item) {
			var x = item.datapoint[0], y = item.datapoint[1];
			
			$("#sks-chart-tooltip").html(y + " SKS pada semester " + x)
			.css({top: item.pageY + 5, left: item.pageX + 5})
			.fadeIn(200);
			} else {
			$("#sks-chart-tooltip").hide();
			}			
			});
			
			$('<div class="tooltip-inner" id="ip-chart-tooltip"></div>').css({
			position: "absolute",
			display: "none",
			opacity: 0.8
			}).appendTo("body");
			$("#ip-chart").bind("plothover", function (event, pos, item) {			
			if (item) {
			var x = item.datapoint[0], y = item.datapoint[1];
			
			$("#ip-chart-tooltip").html("IPK pada semester " + x + " adalah " + y)
			.css({top: item.pageY + 5, left: item.pageX + 5})
			.fadeIn(200);
			} else {
			$("#ip-chart-tooltip").hide();
			}			
			});
			
			});	
			
			function labelFormatter(label, series) {
			return "<div style='font-size:8pt; text-align:center; padding:2px; color:white;'>" + label + "<br/>" + Math.round(series.percent) + "%</div>";
			}
			
		</script>
	@endpush																																																																																																												