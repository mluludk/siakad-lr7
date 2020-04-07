@extends('app')

@section('title')
Daftar Pembayaran Gaji
@endsection

@section('header')
<section class="content-header">
	<h1>
		Gaji
		<small>Daftar Pembayaran Gaji</small>
	</h1>
	<ol class="breadcrumb">
		<li><a href="/"><i class="fa fa-dashboard"></i> Home</a></li>
		<li class="active">Daftar Pembayaran Gaji</li>
	</ol>
</section>
@endsection

<?php 
	$c = 1; 
	$role_id = \Auth::user() -> role_id;
?>
@section('content')
<div class="box">
	<div class="box-header with-border">
		<h3 class="box-title">Daftar Pembayaran Gaji</h3>
		<div class="box-tools">
			@if($role_id == 4)<a href="/profildosen" class="btn btn-info btn-xs" title="Data Dosen"><i class="fa fa-briefcase"></i></a>@endif
		</div>
	</div>
	<div class="box-body">
		@if(count($dgaji) < 1)
		<p class="text-muted">Belum ada data</p>
		@else
		<table class="table table-bordered">
			<tr style="background-image: -webkit-gradient(linear,0 top,0 bottom,from(#3A8341),to(#609C40)); background-image: -moz-linear-gradient(#3A8341,#054a10);color: #fff;">
				<th width="20px">No.</th>
				<th>Nama Dosen</th>
				<th>Bulan</th>
				@foreach($jgaji as $col)
				<th>{{ $col }}</th>
				@endforeach
				<th>Jumlah</th>
				<th>Diterima</th>
				@if($role_id == 4)
				<th></th>
				@endif
			</tr>	
			@foreach($dgaji as $d => $v)
			<?php $j = 0; ?>
			<tr>
				<td>{{ $c }}</td>
				<td>{{ $v['dosen'] }}</td>	
				<td>{{ date('M Y', strtotime($v['bulan'])) }}</td>	
				@foreach($v['gaji'] as $g)
				<td>Rp {{ number_format($g, 0, ',', '.') }}</td>
				<?php $j = $j + $g; ?>
				@endforeach
				<td>Rp {{ number_format($j, 0, ',', '.') }}</td>
				<td>@if($v['diterima'] == '')<a href="/gaji/{{ $v['id'] }}/{{ $v['bulan'] }}/confirm" class="btn btn-xs btn-info"><i class="fa fa-check"></i> Konfirmasi</a>@else<span class="label label-success">Sudah</span>@endif</td>
				@if($role_id == 4)
				<td><a href="/gaji/{{ $v['id'] }}/{{ $v['bulan'] }}/delete" class="btn btn-xs btn-danger"><i class="fa fa-trash"></i></a></td>
				@endif
				<?php $c ++; ?>
				@endforeach
			</table>
			{!! $gaji -> render() !!}
			@endif
		</div>
	</div>
@endsection