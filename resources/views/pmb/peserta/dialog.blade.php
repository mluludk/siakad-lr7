@extends('pmb/peserta/layout')

@section('title')
Cetak {{ $types[$type] }}
@endsection

@push('scripts')
<script>
	$(document).on('click', '.btn-print', function(){
		window.location.href="{{ url('/') }}" + "/pmb/print/{{ $type }}/"+ $('input[name=kode]').val();
	});
</script>
@endpush

@push('styles')
<style>
	.content{
	padding: 30px;
	text-align: center;
	}
	p{
	font-size: 15px;
	}
	.kode{
	margin: 20px auto;
	font-size: 40px;
	color: #29b8cc;
	border: 5px solid #29b8cc;
	border-radius: 5px;
	width: 300px;
	display: block;
	text-align: center;
	padding: 10px;
	font-weight: bold;
	font-family: tahoma;
	}
</style>
@endpush

@section('content')
<div class="container content">
	<div class="row">
		<div class="col-sm-12">
			<h3>
				Cetak {{ $types[$type] }}
			</h3>
			<p>
				Masukkan Kode Pendaftaran Anda
			</p>
			<input type="text" class="kode" name="kode" autofocus />
			@if($type == 'kartu')
			<button class="btn btn-warning btn-lg btn-print"><i class="fa fa-print"></i> Cetak</button>
			@else
			<button class="btn btn-success btn-lg btn-print"><i class="fa fa-print"></i> Cetak</button>
			@endif
		</div>
	</div>
</div>
@endsection															