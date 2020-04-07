@extends('app')

@section('title')
Aktivitas Kuliah Mahasiswa {{ $ta -> nama }}
@endsection

@push('scripts')
<script>
	$('.filter').change(function(){
		$('form#filter-form').submit();
	});
	
	$('.st_selection').change(function(){
		var me = $(this);
		var btn = $('button#btn-save');
		btn.prop('disabled', false);
		$('input[name="status['+ me.attr("id") +']"').remove();
		btn.before('<input type="hidden" name="status['+ me.attr("id") +']" value="'+ me.val() +'"/>');
	});
	
</script>
@endpush

@push('styles')
<style>
	.akt th, .ctr{
	text-align: center !important;
	vertical-align: middle !important;
	}
	.akt .rgt{
	text-align: right !important;
	}
	.dgr > td{
	background-color: #fdcdfc;
	}
</style>
@endpush

@section('header')
<section class="content-header">
	<h1>
		Perkuliahan
		<small>Aktivitas Kuliah Mahasiswa {{ $ta -> nama }}</small>
	</h1>		
	<ol class="breadcrumb">
		<li><a href="{{ url('/') }}"><i class="fa fa-dashboard"></i> Home</a></li>
		<li><a href="{{ url('/mahasiswa') }}"> Mahasiswa</a></li>
		<li class="active">Aktivitas Kuliah Mahasiswa {{ $ta -> nama }}</li>
	</ol>
</section>
@endsection

@section('content')
<div class="box box-warning">
	<div class="box-header with-border">
		<h3 class="box-title">Filter</h3>
	</div>
	<div class="box-body">
		<form method="get" action="{{ url('/mahasiswa/akm') }}" class="form-inline" id="filter-form">
			{!! csrf_field() !!}
			<div class="form-group">
				<label class="sr-only" for="ta">Tahun Akademik</label>
				{!! Form::select('ta', $ta_list, $cur_ta, ['class' => 'form-control filter']) !!}
			</div>
			<div class="form-group">
				<label class="sr-only" for="prodi">Program Studi</label>
				{!! Form::select('prodi', $pr_list, $cur_pr, ['class' => 'form-control filter']) !!}
			</div>
			<div class="form-group">
				<button class="btn btn-warning btn-flat" type="submit"><i class="fa fa-filter"></i> Filter</button>
			</div>
		</form>
	</div>
</div>

<div class="box box-primary">
	<div class="box-header with-border">
		<h3 class="box-title">Aktivitas Kuliah Mahasiswa {{ $ta -> nama }}</h3>
		<div class="box-tool pull-right">
			<form method="post" id="frm-status">
				{!! csrf_field() !!}
				<input type="hidden" name="tapel_id" value="{{ $cur_ta }}"/>
				<button class="btn btn-warning btn-flat" id="btn-save" title="Simpan" disabled="disabled"><i class="fa fa-save"></i> SIMPAN</button>
			</form>
		</div>
	</div>
	<div class="box-body">
		@if(count($akm) < 1)
		<p>Belum ada data</p>
		@else
		<?php 
			$status = config('custom.pilihan.statusMhs');
			$status_count = [];
			$cls1 = 'class="active"';
			$cls2 = 'active';
		?>
		<ul class="nav nav-tabs">
			@foreach($akm as $k => $v)
			<li {!! $cls1 !!}><a href="#akt{{ $k }}" data-toggle="tab">Angkatan {{ $k }}</a></li>
			<?php 
				if($cls1 = 'class="active"') $cls1 = '';
			?>
			@endforeach
		</ul>
		
		<div class="tab-content">
			@foreach($akm as $k => $v)
			<div class="tab-pane {{ $cls2 }}" id="akt{{ $k }}">
				<?php 
					$c = 0;
					foreach($status as $i => $j) $status_count[$i] = 0;
				?>
				<table class="table table-bordered table-striped akt">
					<thead>
						<tr style="background-image: -webkit-gradient(linear,3 top,0 bottom,from(#3A8341),to(#609C40)); background-image: -moz-linear-gradient(#3A8341,#054a10);color: #fff;">
							<th rowspan="2" width="20px">No.</th>
							<th rowspan="2">NIM</th>
							<th rowspan="2">Nama</th>
							<th rowspan="2">Status</th>
							<th rowspan="2">IPS</th>
							<th rowspan="2">IPK</th>
							<th colspan="2">Jumlah SKS</th>
						</tr>
						<tr>
							<th>Semester</th>
							<th>Total</th>
						</tr>
					</thead>
					<tbody>
						@if(count($v) < 1)
						<tr>
							<td colspan="7" align="center">Belum ada data</td>
						</tr>
						@else
						@foreach($v as $a)
						<?php
							$c++; 
							$class = '';
							if($a  -> status != 1 && $a -> status != 9) $class = ' class="dgr"';
						?>
						<tr {!! $class !!}>
							<td>{{ $c }}</td>
							<td>
								{{ $a -> NIM }}
								<a href="{{ route('mahasiswa.akm.recount', [$a -> id, $cur_ta]) }}" class="btn btn-xs btn-flat btn-success" title="Hitung Ulang IPS, IPK dan SKS"><i class="fa fa-refresh"></i></a>
							</td>
							<td>
								{{ $a -> nama }}
							</td>
							@if($a  -> status != 1 && $a -> status != 9)
							<td colspan="5" class="ctr">
								Mahasiswa ini sudah <strong>{{ $status[$a -> status] }}</strong>
							</td>
							@else
							<td class="ctr">
								{!! Form::select('status', $status, $a -> status, ['class' => 'st_selection', 'id' => $a -> id]) !!}
							</td>
							<td class="rgt">{{ $a -> ips }}</td>
							<td class="rgt">{{ $a -> ipk }}</td>
							<td class="rgt">{{ $a -> skss }}</td>
							<td class="rgt">{{ $a -> skst }}</td>
							@endif
						</tr>
						<?php
							$status_count[$a -> status] ++;
						?>
						@endforeach
						@endif
					</tbody>
				</table>
				<hr>
				<table class="table table-bordered" style="width: 400px">
					<thead>
						<tr>
							<th>Status</th>
							<th>Jumlah</th>
						</tr>
					</thead>
					<tbody>
						@foreach($status_count as $o => $p)
						<tr><th>{{ $status[$o] }}</th><td>{{ $p }}</td></tr>
						@endforeach
					</tbody>
				</table>
			</div>
			<?php 
				if($cls2 = 'active') $cls2 = '';
			?>
			@endforeach
			</div>
		@endif
		</div>
	</div>
	@endsection										