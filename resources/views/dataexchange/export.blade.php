@extends('app')

@section('title')
Ekspor Data {{ $exportable[$data] }}
@endsection

@section('header')
<section class="content-header">
	<h1>
		Ekspor Data
		<small> {{ $exportable[$data] }}</small>
	</h1>		
	<ol class="breadcrumb">
		<li><a href="/"><i class="fa fa-dashboard"></i> Home</a></li>
		<li class="active">Ekspor Data {{ $exportable[$data] }}</li>
	</ol>
</section>
@endsection

@push('scripts')
<script>
	$('.filter').change(function(){
		window.location='{{ url('export/' . $data) }}?ta=' + $(this).val();
	});
</script>
@endpush

@section('content')
<!--div class="callout callout-info">
	<h4>Informasi</h4>
	<ul>
	<li>Data yang diambil adalah data Tahun Akademik yang <span class="text-success">AKTIF</span> saat ini. Untuk mengambil data Tahun Akademik yang lain, ganti status Tahun Akademik menjadi <span class="text-success">AKTIF</span></li>
	<li>Untuk memperingan beban kerja server dan mempercepat akses data, semua data telah dimasukkan kedalam <em>cache</em> dengan masa berlaku <strong>30 menit</strong>. Perubahan data yang dilakukan tidak otomatis tercatat selama <em>cache</em> masih berlaku, karena pengambilan data dari database mengikuti masa berlaku <em>cache</em></li>
	</ul>
</div-->
@if(isset($exportableWarning[$data]))
<div class="callout callout-warning">
	<h4>Peringatan</h4>
	<p>{{ $exportableWarning[$data] }}</p>
</div>
@endif
<div class="box">
	<div class="box-header with-border">
		<h3 class="box-title">{{ $exportable[$data] }}</h3>
		<div class="box-tools">
			
			@if(isset($tapel))
			<div class="form-inline">
				<div class="form-group">
					<label class="sr-only" for="ta">Tahun Akademik</label>
					{!! Form::select('ta', $tapel, Request::get('ta'), ['class' => 'form-control filter']) !!}
				</div>
			</div>
			<br/>
			@endif
			
		</div>
	</div>
	<div class="box-body">
		<?php $c = 1; ?>
		<table class="table table-bordered">
			<thead>
				<tr style="background-image: -webkit-gradient(linear,0 top,0 bottom,from(#3A8341),to(#609C40)); background-image: -moz-linear-gradient(#3A8341,#054a10);color: #fff;">
					<th width="20px">No.</th>
					<th>Format</th>
					<th></th>
				</tr>
			</thead>
			<tbody>
				@foreach(config('custom.dataFormat') as $k => $v)
				@if(in_array($k, $format))
				<tr>
					<td>{{ $c }}</td>
					<td>{{ $v }}</td>
					<td>
						@foreach($prodi as $p)
						<a class="btn btn-success btn-xs" href="{{ url('/export/'. strtolower($data) .'/'. $p -> singkatan . '/' . $k, [Request::get('ta')]) }}"><i class="fa fa-download"></i> {{ $p -> singkatan }}</a>
						@endforeach
					</td>
				</tr>
				<?php $c++; ?>
				@endif
				@endforeach
			</tbody>
		</table>
	</div>
</div>
@endsection																											