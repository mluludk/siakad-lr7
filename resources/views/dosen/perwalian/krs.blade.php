@extends('app')

@section('title')
Validasi KRS Mahasiswa - {{ $tapel -> nama }}
@endsection


@push('scripts')
<script>
	$('.filter').change(function(){
		$('form#filter-form').submit();
	});
	
	$('#check-all').click(function(){
		$('.check-id').prop('checked', $(this).prop('checked'));
	});
</script>
@endpush

@section('header')
<section class="content-header">
	<h1>
		Mahasiswa
		<small>Validasi KRS</small>
	</h1>
	<ol class="breadcrumb">
		<li><a href="/"><i class="fa fa-dashboard"></i> Home</a></li>
		<li><a href="{{ url('/mahasiswa') }}">Mahasiswa</a></li>
		<li class="active">Validasi KRS</li>
	</ol>
</section>
@endsection

@section('content')
<?php 		
	$status = config('custom.pilihan.statusMhs');
	$n=0;
?>
<div class="box box-warning">
	<div class="box-header with-border">
		<h3 class="box-title">Filter</h3>
	</div>
	<div class="box-body">
		<form method="get" action="{{ route('krs.validasi') }}" class="form-inline" id="filter-form">
			{!! csrf_field() !!}
			
			<div class="form-group">
				<label class="sr-only" for="q">Pencarian</label>
				{!! Form::text('q',Request::get('q'), ['class' => 'form-control filter', 'placeholder' => 'Pencarian']) !!}
			</div>
			<div class="form-group">
				<label class="sr-only" for="tapel_id">Tahun Akademik</label>
				{!! Form::select('tapel_id', $tapel_sel, Request::get('tapel_id'), ['class' => 'form-control filter']) !!}
			</div>			
			<div class="form-group">
				<button class="btn btn-warning btn-flat" type="submit"><i class="fa fa-filter"></i> Filter</button>
			</div>
		</form>
	</div>
</div>

<form method="POST" action="{{ route('krs.validasi.post') }}">
	{!! csrf_field() !!}
	{!! Form::hidden('tapel_id', $tapel -> id) !!}
	<div class="box">
		<div class="box-header with-border">
			<h3 class="box-title">Validasi KRS Mahasiswa - {{ $tapel -> nama }}</h3>
			<div class="box-tools">
				<button class="btn btn-primary btn-xs btn-flat" title="Validasi KRS"><i class="fa fa-check"></i> Proses Vallidasi</button>
			</div>
		</div>
		<div class="box-body">
			<table class="table table-bordered table-striped">
				<thead>
					<tr style="background-image: -webkit-gradient(linear,0 top,0 bottom,from(#3A8341),to(#609C40)); background-image: -moz-linear-gradient(#3A8341,#054a10);color: #fff;">
						<th rowspan="2">NO</th>
						<th rowspan="2">
							<input type="checkbox" id="check-all" />
						</th>
						<th>NIM</th>				
						<th>NAMA</th>
						<th>AKT</th>
						<th>SMT</th>
						<th>NO HP</th>
						<th>PRODI</th>
						<th>PROGRAM</th>
						<th>MHS</th>
						<th>SKS</th>
						<th>DETIL KRS</th>
						<th colspan="2"> STATUS VALIDASI </th>				
					</tr>
				</thead>
				<tbody>
					@if(!$mahasiswa -> count())
					<tr>
						<td colspan="14" align="center">Data tidak ditemukan</td>
					</tr>
					@else
					@foreach($mahasiswa as $g)
					<?php 
						$n++; 			
					?>
					<tr>
						<td>
							{{ $n }}
						</td>
						<td>
							<input type="checkbox" id="check-{{ $g -> id }}" name="check[]" value="{{ $g -> id }}" class="check-id" />
						</td>
						<td>{{ $g -> NIM }}</td>
						<td>{{ $g -> nama }}</td>
						<td>{{ $g -> angkatan }}</td>
						<td>{{ $g -> semester }}</td>
						<td>{{ $g -> hp }}</td>
						<td>{{ $g -> strata }} {{ $g -> singkatan }}</td>
						<td>{{ $g -> program }}</td>
						<!--td>{{ $status[$g -> statusMhs] }}</td-->
						<td>{{ $status[$g -> status] }}</td>
						<td>{{ $g -> jml_sks }}</td>
						<td><a href="{{ route('mahasiswa.krs', [$g -> NIM, 'view', $tapel -> id]) }}" target="_blank">[ Detil KRS ]</a></td>
						<td>
							@if($g -> approved == 'y')
							<span class="label label-success"><i class="fa fa-check"></i> Sudah</span>
							@else
							<span class="label label-danger"><i class="fa fa-times"></i> Belum</span>
							@endif
						</td>
						<td>
							@if($g -> approved == 'y')
							<a href="{{ route('mahasiswa.krs', [$g -> NIM, 'review']) }}" class="btn btn-danger btn-xs btn-flat"><i class="fa fa-times"></i> Batalkan</a>
							@else
							<a href="{{ route('mahasiswa.krs', [$g -> NIM, 'approve']) }}" class="btn btn-success btn-xs btn-flat"><i class="fa fa-check"></i> Validasi</a>
							@endif
						</td>
					</tr>
					@endforeach
					
					@if($mahasiswa2 !== null)
					@foreach($mahasiswa2 as $g)
					<?php 
						$n++; 			
					?>
					<tr class="warning">
						<td>
							{{ $n }}
						</td>
						<td>
							<input type="checkbox" disabled="disabled" />
						</td>
						<td>{{ $g -> NIM }}</td>
						<td>{{ $g -> nama }}</td>
						<td>{{ $g -> prodi -> strata }} {{ $g -> prodi -> singkatan }}</td>
						<td>{{ $g -> angkatan }}</td>
						<td>{{ $g -> semesterMhs }}</td>
						<td>{{ $g -> hp }}</td>
						<td>{{ $status[$g -> statusMhs] }}</td>
						<td>0</td>
						<td colspan="3">
							<span class="label label-danger"><i class="fa fa-times"></i> Belum KRS</span>
						</td>
					</tr>
					@endforeach
					@endif
					@endif
				</tbody>
			</table>
		</div>
	</div>
</form>
@endsection																																											