@extends('app')

@section('title')
Profil Prodi {{ $prodi -> nama }}
@endsection

@section('header')
<section class="content-header">
	<h1>
		Prodi {{ $prodi -> nama }}
		<small> Profil</small>
	</h1>
	<ol class="breadcrumb">
		<li><a href="/"><i class="fa fa-dashboard"></i> Home</a></li>
		<li class="active">Profil Prodi {{ $prodi -> nama }}</li>
	</ol>
</section>
@endsection

@section('content')
<div class="box box-primary">
	<div class="box-header with-border">
		<h3 class="box-title">Profil Prodi {{ $prodi -> nama }}</h3>
	</div>
	<div class="box-body">
		<table class="table table-bordered table-striped">
			<thead>
				<tr style="background-image: -webkit-gradient(linear,0 top,0 bottom,from(#3A8341),to(#609C40)); background-image: -moz-linear-gradient(#3A8341,#054a10);color: #fff;">
					<th>Kode</th>
					<th>Nama</th>
					<th>Singkatan</th>
					<th>Strata</th>
					<th>Kaprodi</th>
					<th>Wilayah</th>
					<th>No.SK</th>
					<th>Tanggal SK</th>
					<th>Peringkat</th>
					<th>Tanggal Daluarsa</th>
					<th>Status</th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td>{{ $prodi -> kode_dikti }}</td>
					<td>{{ $prodi -> nama }} ({{ $prodi -> singkatan }})</td>
					<td>{{ $prodi -> singkatan }}</td>
					<td>{{ $prodi -> strata }}</td>
					<td>{{ $prodi -> kaprodi }}</td>
					<td>{{ $prodi -> wilayah }}</td>
					<td>{{ $prodi -> no_sk }}</td>
					<td>{{ $prodi -> tgl_sk }}</td>
					<td>{{ $prodi -> peringkat }}</td>
					<td>{{ $prodi -> tgl_sk }}</td>
					<td>
						<p class="form-control-static">
							@if(strtotime($prodi -> tgl_daluarsa) < time())
							<button class="btn btn-danger btn-xs btn-flat">Kadaluarsa</button>
							@else
							<button class="btn btn-success btn-xs btn-flat">Berlaku</button>
							@endif
						</p>
					</td>
				</tr>
			</tbody>
		</table>
		<br/>
		<table class="table no-border">
			<tbody>
				<tr>
					<th width="15%">Kode</th>
					<td>{{ $prodi -> kode_dikti }}</td>
					<th width="15%">Jenjang</th>
					<td>{{ $prodi -> strata }}</td>
				</tr>
				<tr>
					<th>Nama Prodi</th>
					<td>{{ $prodi -> nama }} ({{ $prodi -> singkatan }})</td>
					<th>Wilayah</th>
					<td>{{ $prodi -> wilayah }}</td>
				</tr>
				<tr>
					<th>No. SK</th>
					<td>{{ $prodi -> no_sk }}</td>
					<th>Tanggal SK</th>
					<td>{{ $prodi -> tgl_sk }}</td>
				</tr>
				<tr>
					<th>Peringkat</th>
					<td>{{ $prodi -> peringkat }}</td>
					<th>Tanggal Daluarsa</th>
					<td>{{ $prodi -> tgl_sk }}</td>
				</tr>
				<tr>
					<th>Status Daluarsa</th>
					<td>
						@if(strtotime($prodi -> tgl_daluarsa) < time())
						<button class="btn btn-danger btn-xs btn-flat">Kadaluarsa</button>
						@else
						<button class="btn btn-success btn-xs btn-flat">Berlaku</button>
						@endif
					</td>
					<th>Kaprodi</th>
					<td>{{ $prodi -> kaprodi }}</td>
				</tr>
			</tbody>
		</table>
	</div>
</div>
@endsection	