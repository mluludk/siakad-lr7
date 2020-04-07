@extends('pmb/peserta/layout')

@section('title')
Pendaftaran Santri Baru {{ $data -> nama }} Online - {{ config('local.profil.singkatan') }}
@endsection

@push('styles')
<style>
	.kode{
	margin: 20px auto;
	font-size: 40px;
	color: #29b8cc;
	border: 5px solid #29b8cc;
	border-radius: 5px;
	width: 300px;
	text-align: center;
	padding: 10px;
	font-weight: bold;
	font-family: tahoma;
	}	
	.content{
	padding: 30px;
	text-align: center;
	}
	p{
	font-size: 15px;
	}
</style>
@endpush

@section('content')
<div class="container content">
	<div class="row">
		<div class="col-sm-12">
			<h3>
				Proses pendaftaran berhasil
			</h3>
			<p>
				Kode pendaftaran anda adalah
			</p>
			<div class="kode">
				{{ $data -> kode }}
			</div>
			<p>
				Simpan kode tersebut karena akan dibutuhkan sewaktu-waktu 
			</p>
			<a class="btn btn-success btn-lg btn-flat" href="{{ route('pmb.peserta.print', ['formulir', $data -> kode]) }}"><i class=" fa fa-print"></i> Cetak Formulir</a>
			<a class="btn btn-info btn-lg btn-flat" href="{{ route('pmb.peserta.print', ['kartu', $data -> kode]) }}"><i class=" fa fa-print"></i> Cetak Kartu Ujian</a>
		</div>
	</div>
</div>
@endsection															