@extends('pmb/peserta/layout')

@section('title')
Pendaftaran Mahasiswa Baru Online - {{ config('local.profil.singkatan') }}
@endsection

@push('styles')
<style>
	.content{
	padding: 30px;
	text-align: center;
	}
	.content h3, p{
	text-align: center;
	}
	address{
	text-align: center;
	}
	a.lnk{
	color: #FF6600;
	}
	a.lnk:hover{
	text-decoration:underline;
	}
</style>
@endpush
<?php
	$config = config('custom');
?>
@section('content')
<div class="container content">
	<div class="row">
		<div class="col-sm-12">
			<h3>
				@if($error == 'not_found')
				Maaf, Halaman tidak ditemukan.
				@elseif($error == 'data_not_found')
				Maaf, Kode tidak ditemukan. Pastikan anda telah memasukkan kode dengan benar.
				@else
				Maaf, Pendaftaran Mahasiswa Baru tidak bisa dilakukan pada saat ini.
				@endif
			</h3>
			@if(isset($message))
			<p>
				{!! $message !!}
			</p>
			
			@if($error == 'ip')
			<p>
				<a class="lnk" href="?key={{ csrf_token() }}">Klik disini</a> untuk melakukan pendaftaran baru.
			</p>
			@endif
			
			@endif
			<p>
				Untuk Informasi lebih lanjut, hubungi Sekretariat {{ $config['profil']['nama'] }} di:
			</p>
			<address class="center-block">
				{{ $config['profil']['alamat']['jalan'] }} {{ $config['profil']['alamat']['kabupaten'] }}</br>
				<strong>Telepon:</strong><br/>{{ $config['profil']['telepon'] }}</br>
				<strong>Email:</strong><br/>{!! HTML::mailto($config['profil']['email']) !!}</br>
				<strong>Website:</strong><br/>{!! link_to($config['profil']['website'], $config['profil']['website']) !!}</br>
				<strong>Fabecook:</strong><br/>{!! link_to($config['profil']['facebook'], $config['profil']['facebook'], ['title' => 'Facebook ' . $config['profil']['singkatan']]) !!}</br>
				<strong>Twtiter:</strong><br/>{!! link_to($config['profil']['twitter'], $config['profil']['twitter'], ['title' => 'Twitter ' . $config['profil']['singkatan']]) !!}</br>
			</address>
			</div>
			</div>
			</div>
			@endsection																			