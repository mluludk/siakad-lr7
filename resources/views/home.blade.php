<?php 
	$auth = Auth::user();
	$rolename = strtolower($auth -> role -> name); 
	$userimage = $auth -> authable -> foto !== '' ? url('/getimage/' . $auth -> authable -> foto) : url('/images/logo.png');
	$config = config('custom');
?>
@extends('app')

@section('title')
Selamat Datang di {{ $config['app']['abbr'] }}
@endsection

@section('header')
&nbsp;
@endsection

@push('styles')
<style>
	.col-sm-3{
	margin-bottom: 20px;
	}
	.menus{
	margin-top: 20px;
	}
	.menus a{
	color:#333;
	}
	.menus a:hover{
	color:#46a520;
	text-decoration:none;
	}	
	.menus a.sign-out:hover{
	color: #d22c26;
	}	
	.media{
	padding: 5px;
	}
	.media:hover{
	background-color: #f8f8f8;
	}
	
	.thumbnail{
	max-width: 150px;
	}
	
	.nav-tabs{
	margin-left: 5px;
	}
	.nav-info li.active a{
	background-color: #00c0ef !important;
	color: #fff !important;
	}
	
	.nav-success li.active a{
	background-color: #00a65a !important;
	color: #fff !important;
	}
	
	.info-box-number {
    font-size: 23px;
	}
</style>
<link rel="stylesheet" href="{{ url('css/morris.css') }}">
@endpush

@section('content')

@if(in_array($rolename, ['root', 'administrator', 'akademik', 'prodi']))
<div class="box">
	<div class="box-body">
		<div class="row">
			<div class="col-md-6">
				<div class="page-header">
					<h3><i class="fa fa-graduation-cap"></i> Identitas Perguruan Tinggi</h3>
				</div>
				<h4>{{ $config['profil']['nama'] }} <small>/ {{ $config['profil']['kode'] }}</small></h4>
				<table width="100%">
					<tr>
						<td width="50%"><strong>Alamat:</strong> {{ $config['profil']['alamat']['jalan'] }}</td>
						<td><strong>Kodepos:</strong> {{ $config['profil']['alamat']['kodepos'] }}</td>
					</tr>
					<tr>
						<td><strong>Telp:</strong> {{ $config['profil']['telepon'] }}</td>
						<td><strong>Fax:</strong> {{ $config['profil']['fax'] }}</td>
					</tr>
					<tr>
						<td><strong>Email:</strong> {{ explode(',', $config['profil']['email'])[0] }}</td>
						<td><strong>Website:</strong> {{ $config['profil']['website'] }}</td>
					</tr>
					<tr>
						<td colspan="2"><strong>Login Terakhir:</strong> {{ formatTanggalWaktu($auth -> last_login) }}</td>
					</tr>
				</table>
			</div>
			<div class="col-md-6">
				<div class="row">
					<div class="col-md-6">
						<a class="btn btn-block btn-lg btn-social bg-purple btn-flat">
							<i class="fa fa-users"></i> Mahasiswa Aktif <span class="pull-right">{{ $counter['mahasiswa_aktif'][0] }} / {{ $counter['mahasiswa_aktif'][1] }}</span> 
						</a>
						<a class="btn btn-block btn-lg btn-social btn-linkedin btn-flat">
							<i class="fa fa-user-plus"></i> Mahasiswa Baru Putra<span class="pull-right">{{ $counter['mahasiswa_baru_pa'][0] }} / {{ $counter['mahasiswa_baru_pa'][1] }}</span>
						</a>
						<a class="btn btn-block btn-lg btn-social bg-olive btn-flat">
							<i class="fa fa-graduation-cap"></i> Mahasiswa Lulus<span class="pull-right">{{ $counter['mahasiswa_lulus'][0] }} / {{ $counter['mahasiswa_lulus'][1] }}</span>
						</a>
					</div>
					<div class="col-md-6">
						<a class="btn btn-block btn-lg btn-social btn-github btn-flat">
							<i class="fa fa-users"></i> Mahasiswa Non-Aktif <span class="pull-right">{{ $counter['mahasiswa_non_aktif'][0] }} / {{ $counter['mahasiswa_non_aktif'][1] }}</span>
						</a>
						<a class="btn btn-block btn-lg btn-social btn-foursquare btn-flat">
							<i class="fa fa-user-plus"></i> Mahasiswa Baru Putri<span class="pull-right">{{ $counter['mahasiswa_baru_pi'][0] }} / {{ $counter['mahasiswa_baru_pi'][1] }}</span>
						</a>
						<a class="btn btn-block btn-lg btn-social bg-orange btn-flat">
							<i class="fa fa-graduation-cap"></i> Mahasiswa Belum Lulus<span class="pull-right">{{ $counter['mahasiswa_blm_lulus'][0] }} / {{ $counter['mahasiswa_blm_lulus'][0] }}</span>
						</a>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="row">
	<div class="col-sm-12">
		<ul class="nav nav-tabs nav-info">
			<li class="active"><a href="#jk_mhs" data-toggle="tab">Jenis Kelamin</a></li>
			<li><a href="#asal_mhs" data-toggle="tab">Asal</a></li>
			<li><a href="#tab_pk_ortu_mhs" data-toggle="tab">Pek. Ortu</a></li>
			<li><a href="#tab_mhs" data-toggle="tab">Mahasiswa</a></li>
			@if(in_array($rolename, ['root', 'administrator']))
			<li><a href="#tab_ultah_mhs" data-toggle="tab">Ultah</a></li>
			@endif
			<li><a href="#tab_status_mhs" data-toggle="tab">AKM</a></li>
			<li><a href="#tab_prodi_mhs" data-toggle="tab">Prodi</a></li>
			
			<li><a href="#tab_status_dosen" data-toggle="tab">Status Dosen</a></li>
			<li><a href="#tab_png_dosen" data-toggle="tab">Penugasan Dosen</a></li>
			<li><a href="#tab_jk_dosen" data-toggle="tab">Jenis Kelamin Dosen</a></li>
		</ul>
		<div class="tab-content">
			<div class="tab-pane fade in active" id="jk_mhs">
				<div class="box box-info">
					<div class="box-header with-border">
						<h3 class="box-title"><i class="fa fa-bar-chart"></i> Jumlah Mahasiswa Sesuai Jenis Kelamin Per-angkatan</h3>
					</div>
					<div class="box-body chart-responsive" style="height: 340px">
						<canvas id="chart_jk_angk" />
					</div>
				</div>
			</div>
			
			<div class="tab-pane fade" id="tab_mhs">
				<div class="box box-info">
					<div class="box-header with-border">
						<h3 class="box-title"><i class="fa fa-line-chart"></i> Jumlah Mahasiswa Baru & Lulusan</h3>
					</div>
					<div class="box-body chart-responsive">
						<div class="chart" id="chart1" style="height: 340px;"></div>
					</div>
				</div>
			</div>
			
			<div class="tab-pane fade" id="asal_mhs">
				<div class="box box-info">
					<div class="box-header with-border">
						<h3 class="box-title"><i class="fa fa-bar-chart-o"></i> Daerah Asal Mahasiswa</h3>
					</div>
					<div class="box-body chart-responsive" style="height: 340px">
						<canvas id="chart3"/>
					</div>
				</div>
			</div>
			
			<div class="tab-pane fade" id="tab_status_mhs">
				<div class="box box-info">
					<div class="box-header with-border">
						<h3 class="box-title"><i class="fa fa-pie-chart"></i> Status Mahasiswa</h3>
					</div>
					<div class="box-body">
						<div class="chart" id="chart2" style="height: 340px;"></div>
					</div>
				</div>
			</div>
			
			<div class="tab-pane fade" id="tab_prodi_mhs">
				<div class="box box-info">
					<div class="box-header with-border">
						<h3 class="box-title"><i class="fa fa-pie-chart"></i> Mahasiswa Per-Prodi</h3>
					</div>
					<div class="box-body">
						<div class="chart" id="prodi_mhs" style="height: 340px;"></div>
					</div>
				</div>
			</div>
			
			@if(in_array($rolename, ['root', 'administrator']))
			
			@if(count($birthday))
			@push('styles')
			<style>
				.thumbnail{
				max-width: 85px;
				float: left;
				margin: 0px 3px;
				}
				.popover{
				font-size: 12px;
				}
			</style>
			@endpush
			@push('scripts')
			<script>
				$(function () {
					$('[data-toggle="popover"]').popover({
						html: true,
						placement: 'auto top',
						trigger: 'hover'
					})
				})
			</script>
			@endpush
			@endif
			
			<div class="tab-pane fade" id="tab_ultah_mhs">
				<div class="box box-success">
					<div class="box-header with-border">
						<h3 class="box-title"><i class="fa fa-gift"></i> Ulang Tahun hari ini </h3>
					</div>
					<div class="box-body" style="height: 340px">
						@if(count($birthday))
						@foreach($birthday as $b)
						<a href="{{ url($b['typ'] . '/' . $b['id']) }}" target="_blank" class="thumbnail" data-toggle="popover" data-content="<strong>{{ $b['nam'] }}</strong><br/>{{ $b['age'] }} tahun<br/>{{ $b['wtm'] }}">
							<img src="@if($b['pct'] != ''){{ url('/getimage/' . $b['pct']) }} @else {{ url('/images/b.png') }} @endif" alt="{{ $b['nam'] }}">
						</a>
						@endforeach
						@else
						Tidak ada data
						@endif
					</div>
				</div>
			</div>
			@endif
			
			<div class="tab-pane fade" id="tab_pk_ortu_mhs">
				<div class="box box-info">
					<div class="box-header with-border">
						<h3 class="box-title"><i class="fa fa-pie-chart"></i> Pekerjaan Orang Tua Mahasiswa</h3>
					</div>
					<div class="box-body chart-responsive" style="height: 340px">
						<canvas id="chart4"/>
					</div>
				</div>
			</div>
			
			<div class="tab-pane fade" id="tab_status_dosen">
				<div class="box box-success">
					<div class="box-header with-border">
						<h3 class="box-title"><i class="fa fa-pie-chart"></i> Status Dosen</h3>
					</div>
					<div class="box-body chart-responsive" style="height: 340px">
						<canvas id="st_dosen"/>
					</div>
				</div>
			</div>
			
			<div class="tab-pane fade" id="tab_png_dosen">
				<div class="box box-success">
					<div class="box-header with-border">
						<h3 class="box-title"><i class="fa fa-pie-chart"></i> Penugasan Dosen</h3>
					</div>
					<div class="box-body chart-responsive" style="height: 340px">
						<canvas id="png_dosen" />
					</div>
				</div>
			</div>
			
			<div class="tab-pane fade" id="tab_jk_dosen">
				<div class="box box-success">
					<div class="box-header with-border">
						<h3 class="box-title"><i class="fa fa-pie-chart"></i> Jumlah Dosen Sesuai Jenis Kelamin </h3>
					</div>
					<div class="box-body chart-responsive" style="height: 340px">
						<canvas id="jk_dosen" />
					</div>
				</div>
			</div>
			
		</div>
	</div>
</div>

@else

<div class="row">
	<div class="col-md-9">
		<div class="box box-primary">
			<div class="box-header with-border">
				<h1 class="box-title" style="font-size: 30px;">Selamat Datang</h1>
			</div>
			<div class="box-body" style="font-size: 17px;">
				Selamat Datang di Sistem Administrasi Akademik (SIAKAD) {{ config('custom.profil.nama') }}. SIAKAD adalah sistem yang memungkinkan para civitas akademika {{ config('custom.profil.nama') }} menerima
				dan mengirim informasi dengan cepat melalui internet. Sistem ini diharapkan dapat  memberi kemudahan setiap civitas akademika untuk melakukan aktivitas-aktivitas akademik dan proses belajar mengajar.
				Selamat menggunakan fasilitas ini.
			</div>
		</div>
	</div>
	
	<div class="col-md-3">	
		<div class="box box-primary">
			<div class="box-body box-profile">
				<img class="profile-user-img img-responsive img-circle" src="{{ url($userimage) }}" alt="User profile picture" style="width: 100px; height: 100px">
				
				<h3 class="profile-username text-center">{{ Auth::user() -> authable -> nama }}</h3>
				
				<p class="text-muted text-center">{{ Auth::user() -> role -> name }}</p>
				
				<a href="{{ url('/profil') }}" class="btn btn-primary btn-block"><b>Profil</b></a>
			</div>
		</div>
	</div>
</div>
@endif

@if(Auth::user() -> role_id == 4)
<div class="row">
	<div class="col-md-3 col-sm-6 col-xs-12">
		<div class="info-box">
			<span class="info-box-icon bg-red"><i class="fa fa-tag"></i></span>
			<div class="info-box-content">
				<span class="info-box-text">Tagihan Belum Lunas</span>
				<span class="info-box-number">{{ formatRupiah($keuangan['total_tagihan'] -> t_jumlah - $keuangan['total_tagihan'] -> t_bayar) }}</span>
			</div>
		</div>
	</div>
	
	<div class="col-md-3 col-sm-6 col-xs-12">
		
		<div class="info-box">
			<span class="info-box-icon bg-green"><i class="fa fa-check-circle-o"></i></span>
			<div class="info-box-content">
				<span class="info-box-text">Tagihan Lunas</span>
				<span class="info-box-number">{{ formatRupiah($keuangan['total_tagihan'] -> t_bayar) }}</span>
			</div>
		</div>
		
	</div>
	
	<div class="clearfix visible-sm-block"></div>
	
	<div class="col-md-3 col-sm-6 col-xs-12">
		<div class="info-box">
			<span class="info-box-icon bg-blue"><i class="fa fa-envelope-o"></i></span>
			
			<div class="info-box-content">
				<span class="info-box-text">Tagihan Semester Aktif</span>
				<span class="info-box-number">{{ formatRupiah($keuangan['total_tagihan_semester'] -> s_jumlah - $keuangan['total_tagihan_semester'] -> s_bayar) }}</span>
			</div>
		</div>
	</div>
	
	<div class="col-md-3 col-sm-6 col-xs-12">
		<div class="info-box">
			<span class="info-box-icon bg-purple"><i class="fa fa-money"></i></span>
			
			<div class="info-box-content">
				<span class="info-box-text">Pembayaran {{ config('custom.bulan')[date('m')] }} {{ date('Y') }}</span>
				<span class="info-box-number">{{ formatRupiah($keuangan['total_pembayaran_bulan'] -> b_jumlah) }}</span>
			</div>
		</div>
	</div>
</div>
@else

<!-- Mahasiswa -->
@if(Auth::user() -> role_id == 512)
@push('scripts')
<script src="{{ asset('/js/jquery.knob.min.js') }}"></script>
<script>
	$(".knob").knob();
</script>
@endpush

@push('styles')
<style>
	.ctr{
	font-weight: bold;
	text-align: center;
	}
</style>
@endpush

<div class="box box-info">
	<div class="box-body" style="background: #f5f5f5 !important;">
		<div class="row">
			<div class="col-sm-7">
				<h4>JADWAL KULIAH TERDEKAT</h4>
				@foreach($jadwal_terdekat as $jt)
				<div class="box box-solid">
					<div class="box-body">
						<h4><strong>{{ $jt -> matkul }}</strong> ({{ $jt -> kd }}) <small class="pull-right">{{ $hari[$jt -> hari] }} {{ $jt -> jam_mulai }} - {{ $jt -> jam_selesai }}</small></h4>
						<p>
							<?php 
								foreach($jt -> matkul_tapel -> tim_dosen as $td) $dosen[] = $td -> gelar_depan . ' ' . $td -> nama . ' ' . $td -> gelar_belakang;
								echo implode(', ', $dosen) . ' | ';
								echo $jt -> sks . ' SKS | ';
								echo ' Ruang ' . $jt -> ruang;
							?>
						</p>
					</div>
				</div>
				@endforeach
			</div>
			<div class="col-sm-5">
				<div class="row">
					<div class="col-sm-4 ctr">
						<h5>Semester</h5>
						<input type="text" class="knob" value="{{ $auth -> authable -> semesterMhs ?? 1 }}" data-max="14" data-angleOffset="180" data-thickness=".2" data-width="130" data-height="130" data-fgColor="#DB5157" data-readonly="true">
					</div>
					<div class="col-sm-4 ctr">
						<h5>SKS</h5>
						<input type="text" class="knob" value="{{ $akm -> total_sks ?? 0 }}" data-max="{{ $kurikulum -> sks_total ?? 0 }}" data-thickness=".2" data-angleOffset="180" data-width="130" data-height="130" data-fgColor="#35a964" data-readonly="true">						
					</div>
					<div class="col-sm-4 ctr">
						<h5>IPK</h5>
						<input type="text" class="knob" value="{{ $akm -> ipk ?? 0 }}" data-max="4" data-thickness=".2" data-angleOffset="180" data-width="130" data-height="130" data-fgColor="#479cce" data-readonly="true">						
					</div>
				</div>
				<div class="row">
					<div class="col-sm-12">
						<h4>TAGIHAN ANDA SEMESTER INI</h4>
						<div class="box box-solid">
							<div class="box-body">
								@foreach($tagihan_semester as $ts)
								{{ $ts -> nama }}
								<small class="pull-right">
									@if($ts -> bayar == 0)
									<label class="label label-danger">BELUM DIBAYAR</label>
									@elseif($ts -> jumlah - $ts -> bayar > 0)
									<label class="label label-warning">{{ formatRupiah($ts -> jumlah - $ts -> bayar) }}</label>
									@else
									<label class="label label-success">LUNAS</label>
									@endif
								</small>
								<h4><strong>{{ formatRupiah($ts -> jumlah) }}</strong></h4>
								<hr/>
								@endforeach
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
@endif

<div class="box box-success collapsed-box">
	<div class="box-header with-border">
		<h1 class="box-title"><i class="fa fa-bullhorn"></i> Pengumuman</h1>
		<div class="box-tools pull-right">
			<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i></button>
			<button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
		</div>
	</div>
	<div class="box-body">
		@foreach($informasi as $i)
		<div class="media">
			<small class="pull-right">
				{{ formatTanggalWaktu($i -> updated_at) }}
			</small>
			<div class="media-body">
				<h4 class="media-heading">{{ $i -> judul }} </h4>
				{{ str_limit(strip_tags($i -> isi), 100, '') }} <a href="{{ url('/info/' . $i -> id) }}"> selengkapnya ...</a> 
			</div>
		</div>
		@endforeach
	</div>
</div>
@endif

@if(Auth::user() -> role_id > 2)
<?php $c = 1; ?>
<div class="box box-danger collapsed-box">
	<div class="box-header with-border">
		<h1 class="box-title"><i class="fa fa-download"></i> Download</h1>
		<div class="box-tools pull-right">
			<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i></button>
			<button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
		</div>
	</div>
	<div class="box-body">
		<table class="table table-bordered">
			<tr>
				<th>No.</th>
				<th>Tanggal</th>
				<th>Nama</th>
				<th>File</th>
			</tr>
			@foreach($files as $file)
			<tr>
				<td>{{ $c }}</td>
				<td>{{ formatTanggal(substr($file -> created_at, 0, 10)) }}</td>
				<td>{{ $file -> nama }} <span style="font-size: 10px;">{{ $file -> ukuran }}</span></td>
				<td>
					<a href="{{ url('/getfile/' . $file -> namafile) }}" class="btn btn-info btn-xs btn-flat"><i class="fa fa-download"></i> Download</a>
				</td>
			</tr>
			<?php $c++; ?>
			@endforeach
		</table>
	</div>
</div>
@endif

@if(Auth::user() -> role_id == 1) 
<div class="box box-warning">
	<div class="box-header with-border">
		<h3 class="box-title"><i class="fa fa-server"></i> Server information</h3>
		<div class="box-tools pull-right">
			<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
			</button>
		</div>
	</div>
	<div class="box-body" style="display: block;">
		<?php
			$info = \Cache::get('configs');
		?>
		Web Server: {{ $info['server']['apache'] }}<br/>
		PHP: {{ $info['server']['php'] }}<br/>
		Database:  {{ $info['server']['mysql'] }}<br/>
	</div>
</div>

@endif
@endsection

@push('scripts')
<script src="{{ asset('/js/raphael.min.js') }}"></script>
<script src="{{ asset('/js/morris.min.js') }}"></script>
<script>
	$(function () {
		"use strict";
		
		// LINE CHART
		var chart_mhs = new Morris.Line({
			element: 'chart1',
			resize: true,
			data: [
			@foreach($per_angkatan as $k => $v)
			{y: '{{ $k }}', masuk: {{ $v }}, lulus: {{ $lulusan[$k] }}},
			@endforeach
			],
			xkey: 'y',
			ykeys: ['masuk', 'lulus'],
			labels: ['Mahasiswa', 'Lulusan'],
			lineColors: ['#3c8dbc', '#f35800'],
			hideHover: 'auto'
		});
		
		//DONUT CHART
		<?php $s = config('custom.pilihan.statusMhs'); ?>
		var chart_status_mhs = new Morris.Donut({
			element: 'chart2',
			resize: true,
			colors: ["#00a65a", "#3c8dbc", "#f35800", "#00c0ef",  "#f39c12", "#dd4b59", "#000000", "#ffcb00", "#9933cc", "#777777"],
			data: [
			@foreach($status as $k => $v)
			{label: '{{ $s[$k] }}', value: {{ $v }}, id: {{ $k }}},
			@endforeach
			],
			hideHover: 'auto'
			}).on('click', function(i, x){
			window.location.href = '{{ url('/mahasiswa/filter') }}' +  '?_token=' + '{{ csrf_token() }}' + '&status=' + x.id;
		});
		
		// mahasiswa per prodi
		var chart_prodi_mhs = new Morris.Donut({
			element: 'prodi_mhs',
			resize: true,
			colors: ["#00a65a", "#3c8dbc", "#f35800", "#00c0ef",  "#f39c12", "#dd4b59", "#000000", "#ffcb00", "#9933cc", "#777777"],
			data: [
			@if(count($mhs_prodi) > 0)
			@foreach($mhs_prodi as $k => $v)
			{label: '{{ $k }}', value: {{ $v }}},
			@endforeach
			@endif
			],
			hideHover: 'auto'
		});
		
		//FIX -- http://stackoverflow.com/a/38313151/6934844
		$('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
			var href = $(this).attr('href');
			switch(href){
				
				case '#tab_mhs':
				chart_mhs.redraw();
				break;
				
				case '#tab_status_mhs':
				chart_status_mhs.redraw();
				break;
				
				case '#tab_prodi_mhs':
				chart_prodi_mhs.redraw();
				break;
				
			}
			$('svg').css({ width: '100%' });
		});
	});
</script>
<script src="{{ asset('/js/ChartJS.bundle.min.js') }}"></script>
<script src="{{ asset('/js/chartjs-plugin-datalabels.js') }}"></script>
<script>	
	$(function () {
		var randomColorFactor = function() {
			return Math.round(Math.random() * 255);
		};
		var randomColor = function() {
			return 'rgb(' + randomColorFactor() + ',' + randomColorFactor() + ',' + randomColorFactor() + ')';
		};
		
		//http://stackoverflow.com/a/38797604
		var pieOptions = {
			events: false,
			maintainAspectRatio: false,
			responsive: true,
			animation: {
				duration: 500,
				easing: "easeOutQuart",
				onComplete: function () {
					var ctx = this.chart.ctx;
					ctx.font = Chart.helpers.fontString(17, 'normal', Chart.defaults.global.defaultFontFamily);
					ctx.textAlign = 'center';
					ctx.textBaseline = 'bottom';
					
					this.data.datasets.forEach(function (dataset) {
						
						for (var i = 0; i < dataset.data.length; i++) {
							var model = dataset._meta[Object.keys(dataset._meta)[0]].data[i]._model,
							total = dataset._meta[Object.keys(dataset._meta)[0]].total,
							mid_radius = model.innerRadius + (model.outerRadius - model.innerRadius)/2,
							start_angle = model.startAngle,
							end_angle = model.endAngle,
							mid_angle = start_angle + (end_angle - start_angle)/2;
							
							var x = mid_radius * Math.cos(mid_angle);
							var y = mid_radius * Math.sin(mid_angle);
							
							ctx.fillStyle = '#fff';
							if (i == 3){
								ctx.fillStyle = '#444';
							}
							
							var val = dataset.data[i];
							var percent = String(Math.round(val/total*100)) + "%";
							
							if(val != 0) {
								ctx.fillText(dataset.data[i], model.x + x, model.y + y);
								ctx.fillText(percent, model.x + x, model.y + y + 15);
							}
						}
					});               
				}
			}
		};
		
		/* Kecamatan Start*/
		var configBar = {
			type: 'bar',
			data: {
				datasets: [{
					label: "Jumlah",
					data: [
					@foreach($kec as $p => $j)
					{{ $j }},
					@endforeach
					],
					backgroundColor: [
					@foreach($kec as $p => $j)
					randomColor(),
					@endforeach
					]
				}],
				<?php //$c=0; ?>
				labels: [
				@foreach($kec as $p => $j)
				<?php //$c++; ?>
				"{{ $p }}",
				@endforeach
				]
			},
			options: {
				maintainAspectRatio: false,
				responsive: true,
				scales: {
					xAxes: [{
						ticks: {
							autoSkip: false
						}
					}]
				},
				legend: {
					display: false
				}
			}
		};
		var ctxBar = document.getElementById("chart3").getContext("2d");
		window.myBar = new Chart(ctxBar, configBar);
		/* Kecamatan End */
		
		/* pk_ortu */
		var config = {
			type: 'pie',
			data: {
				datasets: [{
					data: [
					@foreach($pk_ortu as $k => $v)
					{{ $v }},
					@endforeach
					],
					backgroundColor: [
					"#57C5E9",
					"#46BFBD",
					"#FDB45C",
					"#949FB1",
					"#4D5360",
					"#A1E24C",
					"#50FFDF",
					"#F7464A",
					"#EB00DB",
					"#00992E",
					"#A114FF",
					"#FF4405",
					"#001361",
					"#33FF0F",
					],
				}],
				labels: [
				@foreach(config('custom.pilihan.pekerjaanOrtu') as $pk)
				"{{ $pk }}",
				@endforeach
				]
			},
			options: {
				maintainAspectRatio: false,
				responsive: true
			}
		};		
		var ctx = document.getElementById("chart4").getContext("2d");
		window.myPie = new Chart(ctx, config);
		/* pk_ortu */
		
		
		/* jk_angk */
		var jk_angk_data = {
			labels: [
			@if(count($jk_angkatan) > 0)
			@foreach($jk_angkatan as $k => $v)
			"{{ $k }}",
			@endforeach
			@endif
			],
			datasets: [{
				label: 'Laki-laki',
				backgroundColor: "rgba(0,192,239,0.5)",
				yAxisID: "y-axis-1",
				data: [
				@if(count($jk_angkatan) > 0)
				@foreach($jk_angkatan as $k => $v)
				{{ $v['L'] }},
				@endforeach
				@endif
				],
				datalabels: {
					rotation: 270,
					align: 'start',
					anchor: 'end'
				}
				}, {
				label: 'Perempuan',
				backgroundColor: "rgba(235,0,219,0.5)",
				yAxisID: "y-axis-2",
				data: [
				@if(count($jk_angkatan) > 0)
				@foreach($jk_angkatan as $k => $v)
				{{ $v['P'] }},
				@endforeach
				@endif
				],
				datalabels: {
					rotation: 270,
					align: 'start',
					anchor: 'end'
				}
			}]			
		};
		
		var xxx = {
			type: 'bar',
			data: jk_angk_data,
			options: {
				maintainAspectRatio: false,
				responsive: true, 
				plugins: {
					datalabels: {
						color: 'white',
						display: function(context) {
							return context.dataset.data[context.dataIndex] > 15;
						},
						font: {
							size: 12
						},
						formatter: Math.round
					}
				},
				hoverMode: 'index',
				hoverAnimationDuration: 400,
				stacked: false,
				scales: {
					yAxes: [{
						type: "linear",
						display: true,
						position: "left",
						id: "y-axis-1",
						}, {
						type: "linear",
						display: true,
						position: "right",
						id: "y-axis-2",
						gridLines: {
							drawOnChartArea: false
						}
					}],
				}
			}
		};
		var ctx_chart_jk_angk = document.getElementById("chart_jk_angk").getContext("2d");
		window.myBar = new Chart(ctx_chart_jk_angk, xxx);
		/* jk_angk */
		
		/* jk_dosen */
		var jk_dosen_config = {
			type: 'pie',
			data: {
				datasets: [{
					data: [
					@if(count($jk_dosen) > 0)
					@foreach($jk_dosen as $k => $v)
					{{ $v }},
					@endforeach
					@endif
					],
					backgroundColor: [
					"rgba(0,192,239,0.5)",
					"rgba(235,0,219,0.5)"
					],
				}],
				labels: ['Laki-laki', 'Perempuan']
			},
			options: pieOptions
		};		
		var jk_dosen_ctx = document.getElementById("jk_dosen").getContext("2d");
		window.myPie = new Chart(jk_dosen_ctx, jk_dosen_config);
		/* jk_dosen */
		
		/* st_dosen */
		var st_dosen_config = {
			type: 'pie',
			data: {
				datasets: [{
					data: [
					@if(count($st_dosen) > 0)
					@foreach($st_dosen as $v)
					{{ $v }},
					@endforeach
					@endif
					],
					backgroundColor: [
					"rgba(0,153,46,1)",
					"rgba(161,226,76,1)",
					"rgba(148,159,177,1)"
					], 
				}],
				labels: ['Dosen Tetap', 'Dosen Tetap Ber-NIDN', 'Dosen Tidak Tetap']
			},
			options: pieOptions
		};		
		var st_dosen_ctx = document.getElementById("st_dosen").getContext("2d");
		window.myPie = new Chart(st_dosen_ctx, st_dosen_config);
		/* st_dosen */
		
		/* png_dosen */
		var png_dosen_config = {
			type: 'pie',
			data: {
				datasets: [{
					data: [
					@if(count($png_dosen) > 0)
					@foreach($png_dosen as $k => $v)
					{{ $v }},
					@endforeach
					@endif
					],
					backgroundColor: ["#00a65a", "#3c8dbc", "#f35800", "#00c0ef",  "#f39c12", "#dd4b59", "#000000", "#ffcb00", "#9933cc", "#777777"], 
				}],
				labels: [
				@if(count($png_dosen) > 0)
				@foreach($png_dosen as $k => $v)
				'{{ $k }}',
				@endforeach
				@endif
				]
			},
			options: pieOptions
		};		
		var png_dosen_ctx = document.getElementById("png_dosen").getContext("2d");
		window.myPie = new Chart(png_dosen_ctx, png_dosen_config);
		/* st_dosen */
	});
</script>
@endpush
