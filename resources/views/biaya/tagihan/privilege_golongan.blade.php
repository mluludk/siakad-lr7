@extends('app')

@section('title')
Privilege Tagihan
@endsection

@section('header')
<section class="content-header">
	<h1>
		Keuangan
		<small>Privilege Tagihan</small>
	</h1>
	<ol class="breadcrumb">
		<li><a href="/"><i class="fa fa-dashboard"></i> Home</a></li>
		<li class="active">Privilege Tagihan</li>
	</ol>
</section>
@endsection

@section('content')
<div class="box box-info">
	<div class="box-header with-border">
		<h3 class="box-title">Cari Mahasiswa</h3>
	</div>
	<div class="box-body">
		<form method="get" action="{{ url('/tagihan/privilege/golongan/' . $golongan_id) }}">
			{!! csrf_field() !!}
			<div class="row">
				<div class="col-xs-12">
					<div class="input-group{{ $errors -> has('q') ? ' has-error' : '' }}">
						<input type="text" class="form-control" name="q" placeholder="NIM / Nama" value="{{ Request::get('q') }}">
						<span class="input-group-btn">
							<button class="btn btn-info btn-flat" type="submit">Cari</button>
						</span>
					</div>
				</div>
			</div>
		</form>
	</div>
</div>


<div class="box box-success">
	<div class="box-header with-border">
		<h3 class="box-title">Privilege Tagihan</h3>
	</div>
	<div class="box-body">	
		<?php $c = 1; ?>
		<table class="table table-striped table-bordered">
			<thead>
				<tr style="background-image: -webkit-gradient(linear,0 top,0 bottom,from(#3A8341),to(#609C40)); background-image: -moz-linear-gradient(#3A8341,#054a10);color: #fff;">
					<th rowspan="2">No.</th>
					<th rowspan="2">NIM</th>
					<th rowspan="2">Nama</th>
					<th rowspan="2">Nominal Tagihan BAMP</th>
					<th>Privilege</th>
					<th rowspan="2"></th>
				</tr>
				<tr>
					<th>WISUDA</th>
				</tr>
			</thead>
			<tbody>
				@if(count($tgolongan))
				@foreach($tgolongan as $b)
				<?php 
					$c++; 
					if($b['privilege_wis'] == 'y')
					{
						$value = 'n';
						$btn_type = 'btn-success';
					}
					else
					{
						$value = 'y';
						$btn_type = 'btn-warning';						
					}
				?>
				<tr>
					<td>{{ $c }}</td>
					<td>{{ $b['nim'] }}</td>
					<td>{{ $b['nama'] }}</td>
					<td>Rp {{ number_format($b['tanggungan'], 0, ',', '.') }}</td>
					<td align="center">
						@if($b['privilege_wis'] == 'y') 
							<i class="fa fa-check-square fa-lg text-success"></i> 
						@else 
							<i class="fa fa-square-o fa-lg text-danger"></i> 
						@endif
					</td>
					<td>
						<a href="{{ route('tagihan.update.golongan', [$golongan_id, $b['mahasiswa_id'], 'privilege_wis', $value]) }}" class="btn {{ $btn_type }} btn-xs btn-flat">
							<i class="fa fa-pencil-square-o"></i> Edit</a>
					</td>
				</tr>
				@endforeach
				@else
				<tr>
					<td colspan="11" align="center">Belum ada data</td>
				</tr>
				@endif
			</tbody>
		</table>
	</div>
	</div>
	@endsection																																																																	