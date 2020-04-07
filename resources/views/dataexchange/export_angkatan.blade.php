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
	$('.btn-export').click(function(){
		var format = $(this).attr('format');
		window.location='{{ url('export/') }}/' + $(this).attr('data') + '/' + $('#prodi-' + format).val() + '/' + format + '/' + $('#angkatan-' + format).val();
	});
</script>
@endpush

@section('content')
@if(isset($exportableWarning[$data]))
<div class="callout callout-warning">
	<h4>Peringatan</h4>
	<p>{{ $exportableWarning[$data] }}</p>
</div>
@endif
<div class="box">
	<div class="box-header with-border">
		<h3 class="box-title">{{ $exportable[$data] }}</h3>
	</div>
	<div class="box-body">
		<?php $c = 1; ?>
		<table class="table table-bordered">
			<thead>
				<tr style="background-image: -webkit-gradient(linear,0 top,0 bottom,from(#3A8341),to(#609C40)); background-image: -moz-linear-gradient(#3A8341,#054a10);color: #fff;">
					<th width="20px">No.</th>
					<th>Format</th>
					<th>PRODI</th>
					<th>Angkatan</th>
					<th></th>
				</tr>
			</thead>
			<tbody>
				@foreach(config('custom.dataFormat') as $k => $v)
				@if(in_array($k, $format))
				<tr>
					<td>{{ $c }}</td>
					<td>{{ $v }}</td>
					<td>{!! Form::select('prodi', $prodi, null, ['id' => 'prodi-' . $k]) !!}</td>
					<td>{!! Form::select('angkatan', $angkatan, null, ['id' => 'angkatan-' . $k]) !!}</td>
					<td>
						<button class="btn btn-success btn-xs btn-flat btn-export" data="{{ $data }}" format="{{ $k }}" title="Ekspor"><i class="fa fa-file-excel-o"></i> Excel</button>
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