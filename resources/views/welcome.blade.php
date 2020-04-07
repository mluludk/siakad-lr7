@extends('app')

@push('styles')
<style>
	body {
	width: 100%;
	height: 100%;
	display: table;
	font-weight: 100;
	}	
	.content {
	margin-top:50px;
	text-align: center;
	display: inline-block;
	}	
	.main_logo {
	display:block;
	margin:10px auto;
	}
</style>
@endpush
@section('content')
<div class="container">
	<div class="content">
		<h3>Madrasah Diniyah</h3>
		<h1>AN-NADWAH</h1>
		<h5>Jatikeplek - Klemunan - Wlingi</h5>
	</div>
</div>
@endsection
