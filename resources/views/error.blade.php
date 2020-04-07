@extends('app')

@section('header')
Kesalahan
@endsection

@section('content')
<div class="error-page">
	<h2 class="headline text-yellow"> Kesalahan</h2>
	
	<div class="error-content">
		<h3><i class="fa fa-warning text-yellow"></i> Maaf, telah terjadi kesalahan.</h3>
		
		<p>
			Harap hubungi Administrator.
			<a href="/">Kembali ke halaman utama</a>.
		</p>
		
		<form class="search-form">
			<div class="input-group">
				<input type="text" name="search" class="form-control" placeholder="Search">
				
				<div class="input-group-btn">
					<button type="submit" name="submit" class="btn btn-warning btn-flat"><i class="fa fa-search"></i>
					</button>
				</div>
			</div>
		</form>
	</div>
</div>
@endsection
