@extends('app')

@section('title')
Lembaga Penjaminan Mutu {{ config('custom.profil.abbr') }} {{ config('custom.profil.name') }}
@endsection

@push('styles')
<style>
	th{
	padding: 0px !important;
	text-align: center;
	vertical-align: middle !important;
	}
	
	.dm th{
	text-align: left;
	}
	.dm th, td{
	vertical-align: top !important;
	}
</style>
@endpush

@section('header')
<section class="content-header">
	<h1>
		Lembaga Penjaminan Mutu
		<small>Kuesioner</small>
	</h1>
	<ol class="breadcrumb">
		<li><a href="/"><i class="fa fa-dashboard"></i> Home</a></li>
		<li class="active">Kuesioner</li>
	</ol>
</section>
@endsection

@section('content')
<div class="callout callout-danger">
	<h4>Perhatian! </h4>
	<p>Anda diharuskan mengisi kuisioner yang telah disediakan. Anda tidak dapat masuk ke halaman SIAKAD sebelum mengisi semua kuisioner.</p>
</div>
<div class="box box-info">
	<div class="box-header with-border">
		<h3 class="box-title">Kuesioner</h3>
	</div>
	<div class="box-body">
		<div class="row">
			<div class="col-xs-3">
				<strong>PETUNJUK PENGISIAN KUESIONER</strong>
				<ol style="margin-left: 15px; padding: 0;">
					<li>
						Pilihlah dosen dalam daftar penilaian dosen dengan meng-klik tombol 
						<button class="btn btn-warning btn-flat btn-xs" disabled><i class="fa fa-times"></i> Belum</button>
						di kolom penilaian, jika tombol 
						<button class="btn btn-warning btn-flat btn-xs" disabled><i class="fa fa-times"></i> Belum</button>
						berubah menjadi 
						<button class="btn btn-success btn-flat btn-xs" disabled><i class="fa fa-check"></i> Sudah</button> 
						berarti sudah mengisi kuisioner untuk dosen tersebut dan tidak bisa mengisi kembali.
					</li>
					<li>
						Isilah skor masing-masing item dengan meng-klik di baris skor sesuai deskripsi nilai skor.
					</li>
				</ol>
			</div>
			<div class="col-xs-9">
				<strong>PENILAIAN DOSEN</strong>
				<div class="row">
					<div class="col-xs-12">
						<?php $c=1; ?>
						<table class="table table-bordered">
							<tr style="background-image: -webkit-gradient(linear,0 top,0 bottom,from(#3A8341),to(#609C40)); background-image: -moz-linear-gradient(#3A8341,#054a10);color: #fff;">
								<th>No.</th>
								<th>Nama Dosen</th>
								<th>Mata Kuliah</th>
								<th>Tahun Akademik</th>
								<th>Penilaian</th>
							</tr>
							@foreach($dosens as $d)
							<tr>
								<td>{{ $c }}</td>
								<td>{{ $d -> gelar_depan }} {{ $d -> dosen }} {{ $d -> gelar_belakang }}</td>
								<td>{{ $d -> matakuliah }}</td>
								<td>{{ $d -> tapel }}</td>
								<td>
									@if(intval($d -> skor) < 1)
									<a href="/penilaian/{{ $d -> iddosen }}/{{ $d -> idmt }}#dosen" class="btn btn-warning btn-xs btn-flat"><i class="fa fa-times"></i> Belum</a>
									@else
									<button class="btn btn-success btn-flat btn-xs"><i class="fa fa-check"></i> Sudah</button>
									@endif
								</td>
							</tr>
							<?php $c++; ?>
							@endforeach
						</table>
					</div>
				</div>
				@if(isset($dosen_matkul))
				<hr/>
				<div class="row" id="dosen">
					<div class="col-xs-12">
						<div class="box box-danger">
							<div class="box-header with-border">
							<h3 class="box-title">Data Dosen & Mata Kuliah</h3>
							</div>
							<div class="box-body">	
							<table width="100%" class="dm">
							<tbody>
							<tr>
							<th width="20%">Nama Dosen</th><th width="2%">:</th><td width="30%">{{ $dosen_matkul -> gelar_depan }} {{ $dosen_matkul -> dosen }} {{ $dosen_matkul -> gelar_belakang }}</td>
							<th width="20%">Matakuliah</th><th width="2%">:</th><td>{{ $dosen_matkul -> matkul }}</td>
							</tr>
							<tr>
							<th>NIDN</th><th>:</th><td>{{ $dosen_matkul -> NIDN }}</td>
							<th>Kode</th><th>:</th><td>{{ $dosen_matkul -> kd }}</td>
							</tr>
							<tr>
							<th>Alamat</th><th>:</th><td>{{ $dosen_matkul -> alamat }}</td>
							<th>Tahun Akademik</th><th>:</th><td>{{ $dosen_matkul -> ta }}</td>
							</tr>
							<tr>
							<th>Pendidikan</th><th>:</th>
							<td>
							@if(isset($dosen_matkul -> jurS3) and $dosen_matkul -> jurS3 != '')
							S3 {{ $dosen_matkul -> jurS3 }} @if($dosen_matkul -> fakS3 != '') Fakultas {{ $dosen_matkul -> fakS3 }}@endif {{ $dosen_matkul -> univS3 }}
							@elseif(isset($dosen_matkul -> jurS2) and $dosen_matkul -> jurS2 != '')
							S2 {{ $dosen_matkul -> jurS2 }} @if($dosen_matkul -> fakS2 != '') Fakultas {{ $dosen_matkul -> fakS2 }}@endif {{ $dosen_matkul -> univS2 }}
							@elseif(isset($dosen_matkul -> jurS1) and $dosen_matkul -> jurS1 != '')
							S1 {{ $dosen_matkul -> jurS1 }} @if($dosen_matkul -> fakS1 != '') Fakultas {{ $dosen_matkul -> fakS1 }}@endif {{ $dosen_matkul -> univS1 }}
							@endif
							</td>
							<th></th><th></th><td></td>
							</tr>
							</tbody>
							</table>
							</div>
							</div>
							</div>
							</div>
							<div class="row" style="border: 1px dotted black; margin: 0px; padding: 10px 5px;">
							@foreach(config('custom.kuesioner.skor') as $skor => $keterangan)
							<div class="col-xs-6">
							{{ $skor }} = {{ $keterangan }}
							</div>
							@endforeach
							</div>
							<br/>
							<p class="text-danger">Isilah dengan jujur dan objektif. Kuisioner di jamin kerahasiaannya oleh LPM dan tidak mempengaruhi nilai akademik.</p>
							<?php $c = 1; ?>
							@if (App::environment('local'))
							<button id="r" class="btn btn-danger btn-flat"><i class="fa fa-rebel"></i> Random</button>
							@endif
							<form method="post" action="{{ route('penilaian.update', $mtid) }}" id="penilaian">
							{{ csrf_field() }}
							<input type="hidden" name="_method" value="patch" />
							<table class=" table table-bordered table-hover">
							<tr>
							<th rowspan="2">No</th>
							<th rowspan="2">Aspek yang dinilai</th>
							<th colspan="5">Skor</th>
							</tr>
							<tr>
							<th>1</th>
							<th>2</th>
							<th>3</th>
							<th>4</th>
							<th>5</th>
							</tr>
							@foreach($poin as $kompetensi => $pertanyaan)
							<tr><td colspan="7"><strong>{{ config('custom.kuesioner.kompetensi')[$kompetensi] }}</strong></td></tr>
							@foreach($pertanyaan as $p)
							<tr>
							<td>{{ $c }}</td>
							<td>{{ $p -> pertanyaan }}</td>
							<td><input type="radio" name="p-{{ $p -> id }}" value="1"/></td>
							<td><input type="radio" name="p-{{ $p -> id }}" value="2"/></td>
							<td><input type="radio" name="p-{{ $p -> id }}" value="3"/></td>
							<td><input type="radio" name="p-{{ $p -> id }}" value="4"/></td>
							<td><input type="radio" name="p-{{ $p -> id }}" value="5"/></td>
							</tr>
							<?php $c++; ?>
							@endforeach
							@endforeach
							</table>
							<p class="text-danger">Periksalah kembali pilihan Anda. Jawaban tidak bisa di edit kembali.</p>
							<button type="submit" class="btn btn-primary btn-flat"><i class="fa fa-floppy-o"></i> Simpan</button>
							</form>
							@endif
							</div>
							</div>
							</div>
							</div>
							@endsection		
							
							@push('scripts')
							<script>
							$('form#penilaian').on('submit', function(e){
							var empty = false;
							$('#penilaian tr').removeClass('danger');
							$('#penilaian input[type="radio"]').each(function(){
							if ($("input[name='"+ $(this).attr('name') +"']:checked").val() == '' || $("input[name='"+ $(this).attr('name') +"']:checked").val() == undefined) {
							$(this).closest('tr').addClass('danger');
							empty = true;
							}
							});
							
							if(empty) 
							{
							alert('Semua pertanyaan harus dijawab!. Silahkan teliti kembali');		
							e.preventDefault();	
							}
							});
							@if (App::environment('local'))
							$('#r').click(function(){
							$('#penilaian input[type="radio"]').each(function(){
							var vl = Math.floor(Math.random() * 5) + 1;
							$('input[name='+ $(this).attr('name') +'][value=' + vl + ']').prop('checked',true);
							});
							$('form#penilaian').submit();
							});
							@endif
							</script>
							@endsection																																