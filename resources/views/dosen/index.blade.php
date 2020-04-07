@extends('app')

@section('title')
Daftar Dosen
@endsection

@section('header')
<section class="content-header">
	<h1>
		Dosen
		<small>Daftar Dosen</small>
	</h1>		
	<ol class="breadcrumb">
		<li><a href="/"><i class="fa fa-dashboard"></i> Home</a></li>
		@if(isset($message))
		<li><a href="{{ url('/dosen') }}"> Daftar Dosen</a></li>
		<li class="active">Pencarian</li>
		@else
		<li class="active">Daftar Dosen</li>
		@endif
	</ol>
</section>
@endsection

@push('scripts')
<script>
	$('.filter').change(function(){
		$('form#filter-form').submit();
	});
</script>
@endpush

@section('content')
<div class="box box-info">
	<div class="box-header with-border">
		<h3 class="box-title">Cari data dosen</h3>
	</div>
	<div class="box-body">
		<form method="get" action="{{ url('/dosen') }}">
			{!! csrf_field() !!}
			<div class="row">
				<div class="col-xs-12">
					<div class="input-group{{ $errors -> has('q') ? ' has-error' : '' }}">
						<input type="text" class="form-control" name="q" placeholder="Pencarian ...." value="{{ Request::get('q') }}">
						<span class="input-group-btn">
							<button class="btn btn-info btn-flat" type="submit">Cari</button>
						</span>
					</div>
				</div>
			</div>
		</form>
	</div>
</div>

<div class="box box-warning">
	<div class="box-header with-border">
		<h3 class="box-title">Filter</h3>
	</div>
	<div class="box-body">
		<form method="get" action="{{ url('/dosen') }}" class="form-inline" id="filter-form">
			{!! csrf_field() !!}
			<?php
				$statusKepegawaian = ['-' => '--Semua--'] + $status_k;
				$statusDosen = ['-' => '--Semua--'] + $status_d;
			?>				
			<div class="form-group">
				<label class="sr-only" for="status">Status Kep.</label>
				{!! Form::select('status1', $statusKepegawaian, Request::get('status1'), ['class' => 'form-control filter']) !!}
			</div>
			<div class="form-group">
				<label class="sr-only" for="status">Status Dosen</label>
				{!! Form::select('status2', $statusDosen, Request::get('status2'), ['class' => 'form-control filter']) !!}
			</div>
			<!--
				<div class="form-group">
				<label class="sr-only" for="perpage">N-Data</label>
				{!! Form::select('perpage', [25 => 25, 50 => 50, 100 => 100, 200 => 200, 300 => 300], Request::get('perpage'), ['class' => 'form-control filter']) !!}
				</div>
			-->
			<div class="form-group">
				<button class="btn btn-warning btn-flat" type="submit"><i class="fa fa-filter"></i> Filter</button>
			</div>
		</form>
	</div>
</div>

<?php 
	$role_id = \Auth::user() -> role_id; 
	$n = 0;
?>
<div class="box">
	<div class="box-header with-border">
		<h3 class="box-title">Daftar Dosen</h3>
		<div class="box-tools">
			<a href="{{ route('dosen.sks') }}" class="btn btn-warning btn-xs btn-flat" title="SKS Dosen"><i class="fa fa-history"></i> SKS Dosen</a>
			<a href="{{ route('dosen.keahlian') }}" class="btn btn-success btn-xs btn-flat" title="Keahlian Dosen"><i class="fa fa-bolt"></i> Keahlian Dosen</a>
			<a href="{{ route('dosen.perwalian') }}" class="btn btn-info btn-xs btn-flat" title="Perwalian Dosen"><i class="fa fa-address-book"></i> Jumlah Perwalian Dosen</a>
			@if(!$public)<a href="{{ route('dosen.create') }}" class="btn btn-primary btn-xs btn-flat" title="Input Data"><i class="fa fa-plus"></i> Tambah Dosen Baru</a>@endif
		</div>
	</div>
	<div class="box-body">
		<p class="text-muted">{{ $message ?? '' }}</p>
		<table class="table table-bordered table-striped">
			<thead>
				<tr style="background-image: -webkit-gradient(linear,0 top,0 bottom,from(#3A8341),to(#609C40)); background-image: -moz-linear-gradient(#3A8341,#054a10);color: #fff;">
					<th width="40px">No.</th>
					<th width="50px">Kode</th>
					<th width="390px">NAMA</th>
					<th width="150px">NIDN / NUP / NIDK</th>
					<th width="100px">NIY</th>
					<th>L/P</th>
					<th>Status Kep.</th>
					<th>Status Dosen</th>
					<th width="190px"></th>
				</tr>
			</thead>
			<tbody>
			@if(!count($dosen_homebase) && !count($dosen_non_homebase) && !count($dosen_lb))
				<tr>
					<td colspan="9" align="center">Data Dosen tidak ditemukan</td>
				</tr>
				@else
				@if(count($dosen_homebase))
				@foreach($dosen_homebase as $prodi => $penugasan)
				<tr>
					<th colspan="9">{{ $prodi }} ({{ count($penugasan) }} orang)</th>
				</tr>
				@foreach($penugasan as $dosen)
				<?php $n++; ?>
				<tr>
					<td>{{ $n }}</td>
					<td>{{ $dosen -> kode }}</td>
					<td>
						@if($role_id == 4)
						{{ $dosen -> gelar_depan }} {{ trim($dosen -> nama) }}@if(isset($dosen -> gelar_belakang)), {{ $dosen -> gelar_belakang }} @endif
						@else	
						<a href="{{ route('dosen.show', $dosen->id) }}" title="Tampilkan detail data Dosen">
							{{ $dosen -> gelar_depan }} {{ trim($dosen -> nama) }}@if(isset($dosen -> gelar_belakang)), {{ $dosen -> gelar_belakang }} @endif
						</a>
						@endif
					</td>
					<td>{{ $dosen -> NIDN }}</td>
					<td>{{ $dosen -> NIY }}</td>
					<td>{{ $dosen -> jenisKelamin }}</td>
					<td>{{ $status_k[$dosen -> statusKepegawaian] }}</td>
					<td>{{ $status_d[$dosen -> statusDosen] }}</td>
					<td>
						<div class="btn-group">
							@if($role_id <= 4)
							<a href="{{ route('gaji.create', $dosen -> id) }}" class="btn btn-success btn-xs btn-flat" title="Tampilkan data pembayaran gaji Dosen"><i class="fa fa-envelope-o"></i></a>
							@endif
							<a href="{{ route('dosen.edit', $dosen->id) }}" class="btn btn-warning btn-xs btn-flat" title="Edit data dosen"><i class="fa fa-pencil-square-o"></i></a>
							@if(!$public)
							<a href="{{ route('users.impersonate', $dosen -> authInfo -> id) }}" class="btn btn-info btn-xs btn-flat" title="Login sebagai {{ $dosen -> nama }}"><i class="fa fa-sign-in"></i></a>
							<a href="{{ route('dosen.delete', $dosen->id) }}" class="btn btn-danger btn-xs btn-delete has-confirmation btn-flat" title="Hapus data dosen"><i class="fa fa-trash"></i></a>
							@endif
						</div>
					</td>
				</tr>
				@endforeach
				@endforeach				
				@endif
				
				@if(count($dosen_lb))				
				<tr>
					<th colspan="9">Dosen Luar Biasa ({{ count($dosen_lb) }} orang)</th>
				</tr>
				@foreach($dosen_lb as $dosen)
				<?php $n++; ?>
				<tr>
					<td>{{ $n }}</td>
					<td>{{ $dosen -> kode }}</td>
					<td>
						@if($role_id == 4)
						{{ $dosen -> gelar_depan }} {{ trim($dosen -> nama) }}@if(isset($dosen -> gelar_belakang)), {{ $dosen -> gelar_belakang }} @endif
						@else	
						<a href="{{ route('dosen.show', $dosen->id) }}" title="Tampilkan detail data Dosen">
							{{ $dosen -> gelar_depan }} {{ trim($dosen -> nama) }}@if(isset($dosen -> gelar_belakang)), {{ $dosen -> gelar_belakang }} @endif
						</a>
						@endif
					</td>
					<td>{{ $dosen -> NIDN }}</td>
					<td>{{ $dosen -> NIY }}</td>
					<td>{{ $dosen -> jenisKelamin }}</td>
					<td>{{ $status_k[$dosen -> statusKepegawaian] }}</td>
					<td>{{ $status_d[$dosen -> statusDosen] }}</td>
					<td>
						<div class="btn-group">
							@if($role_id <= 4)
							<a href="{{ route('gaji.create', $dosen -> id) }}" class="btn btn-success btn-xs btn-flat" title="Tampilkan data pembayaran gaji Dosen"><i class="fa fa-envelope-o"></i></a>
							@endif
							<a href="{{ route('dosen.edit', $dosen->id) }}" class="btn btn-warning btn-xs btn-flat" title="Edit data dosen"><i class="fa fa-pencil-square-o"></i></a>
							@if(!$public)
							<a href="{{ route('users.impersonate', $dosen -> authInfo -> id) }}" class="btn btn-info btn-xs btn-flat" title="Login sebagai {{ $dosen -> nama }}"><i class="fa fa-sign-in"></i></a>
							<a href="{{ route('dosen.delete', $dosen->id) }}" class="btn btn-danger btn-xs btn-delete has-confirmation btn-flat" title="Hapus data dosen"><i class="fa fa-trash"></i></a>
							@endif
						</div>
					</td>
				</tr>
				@endforeach
				@endif
				
				@if(count($dosen_non_homebase))				
				<tr>
					<th colspan="9">Non Homebase ({{ count($dosen_non_homebase) }} orang)</th>
				</tr>
				@foreach($dosen_non_homebase as $dosen)
				<?php $n++; ?>
				<tr>
					<td>{{ $n }}</td>
					<td>{{ $dosen -> kode }}</td>
					<td>
						@if($role_id == 4)
						{{ $dosen -> gelar_depan }} {{ trim($dosen -> nama) }}@if(isset($dosen -> gelar_belakang)), {{ $dosen -> gelar_belakang }} @endif
						@else	
						<a href="{{ route('dosen.show', $dosen->id) }}" title="Tampilkan detail data Dosen">
							{{ $dosen -> gelar_depan }} {{ trim($dosen -> nama) }}@if(isset($dosen -> gelar_belakang)), {{ $dosen -> gelar_belakang }} @endif
						</a>
						@endif
					</td>
					<td>{{ $dosen -> NIDN }}</td>
					<td>{{ $dosen -> NIY }}</td>
					<td>{{ $dosen -> jenisKelamin }}</td>
					<td>{{ $status_k[$dosen -> statusKepegawaian] }}</td>
					<td>{{ $status_d[$dosen -> statusDosen] }}</td>
					<td>
						<div class="btn-group">
							@if($role_id <= 4)
							<a href="{{ route('gaji.create', $dosen -> id) }}" class="btn btn-success btn-xs btn-flat" title="Tampilkan data pembayaran gaji Dosen"><i class="fa fa-envelope-o"></i></a>
							@endif
							<a href="{{ route('dosen.edit', $dosen->id) }}" class="btn btn-warning btn-xs btn-flat" title="Edit data dosen"><i class="fa fa-pencil-square-o"></i></a>
							@if(!$public)
							<a href="{{ route('users.impersonate', $dosen -> authInfo -> id) }}" class="btn btn-info btn-xs btn-flat" title="Login sebagai {{ $dosen -> nama }}"><i class="fa fa-sign-in"></i></a>
							<a href="{{ route('dosen.delete', $dosen->id) }}" class="btn btn-danger btn-xs btn-delete has-confirmation btn-flat" title="Hapus data dosen"><i class="fa fa-trash"></i></a>
							@endif
						</div>
					</td>
				</tr>
				@endforeach
				@endif
				@endif
				
			</tbody>
		</table>
	</div>
</div>
@endsection						