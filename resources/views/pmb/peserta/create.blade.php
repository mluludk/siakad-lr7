@extends('pmb/peserta/layout')

@section('title')
Formulir Pendaftaran Mahasiswa Baru Online Tahun {{ $pmb -> nama }}
@endsection

@push('styles')
<style>
	.panel-body a{
	color: #337ab7;
	}
	.panel-body a:hover{
	text-decoration: underline;
	}
	img{
	max-width: 100%;
	}
	
	label.btn{
	border-radius: 0px 4px 0px 0px !important;
	}
</style>
@endpush

@section('content')
<div class="container" style="margin-bottom: 30px;">
	<div class="row">
		<div class="col-xs-12">
			<div class="page-header">
				<h2>Formulir Pendaftaran Mahasiswa Baru Online Tahun {{ $pmb -> nama }}</h2>
			</div>
			{!! Form::model(new Siakad\PmbPeserta, ['class' => 'form-horizontal', 'files' => true, 'role' => 'form', 'route' => ['pmb.peserta.store']]) !!}
			@include('pmb/peserta/_form', ['submit_text' => 'Simpan'])
			@if(null !== Request::get('key'))
			{!! Form::hidden('key', Request::get('key')) !!}
			@endif
			{!! Form::hidden('pmb_id', $pmb -> id) !!}
			{!! Form::close() !!}
		</div>
	</div>
</div>
@endsection	