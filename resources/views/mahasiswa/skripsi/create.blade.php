@extends('app')

@section('title')
Input Data Skripsi
@endsection

@section('header')
<section class="content-header">
	<h1>
		Skripsi
		<small>Input Data</small>
	</h1>
	<ol class="breadcrumb">
		<li><a href="/"><i class="fa fa-dashboard"></i> Home</a></li>
		<li><a href="{{ url('/skripsi') }}"> Skripsi</a></li>
		<li class="active">Input Data Skripsi</li>
	</ol>
</section>
@endsection

@section('content')
<div class="box box-primary">
	<div class="box-header with-border">
		<h3 class="box-title">Input Data Skripsi</h3>
	</div>
	<div class="box-body">
		{!! Form::model(new Siakad\Skripsi, ['class' => 'form-horizontal', 'role' => 'form', 'files' => true, 'route' => ['skripsi.store']]) !!}
		<div class="form-group">
			{!! Form::label('dosen1_id', 'Pembimbing 1:', array('class' => 'col-sm-2 control-label')) !!}
			<div class="col-sm-5">
				{!! Form::select('dosen1_id', $dosen, null, ['class' => 'form-control chosen-select']) !!}
			</div>
		</div>
		<div class="form-group">
			{!! Form::label('dosen2_id', 'Pembimbing 2:', array('class' => 'col-sm-2 control-label')) !!}
			<div class="col-sm-5">
				{!! Form::select('dosen2_id', $dosen, null, ['class' => 'form-control chosen-select']) !!}
			</div>
		</div>
		
		<div class="form-group">
			{!! Form::label('mahasiswa_id', 'Mahasiswa:', array('class' => 'col-sm-2 control-label')) !!}
			<div class="col-sm-5">
				{!! Form::select('mahasiswa_id', $mahasiswa, null, ['class' => 'form-control chosen-select']) !!}
			</div>
		</div>
		<div class="form-group has-feedback{{ $errors->has('judul') ? ' has-error' : '' }}">
			{!! Form::label('judul', 'Judul Skripsi:', array('class' => 'col-sm-2 control-label')) !!}
			<div class="col-sm-9">
				{!! Form::textarea('judul', null, array('class' => 'form-control', 'placeholder' => 'Judul Skripsi', 'rows' => '3')) !!}
			</div>
		</div>
		<div class="form-group">
			<div class="col-sm-offset-2 col-sm-9">
				<button class="btn btn-primary btn-flat btn-add" type="button"><i class="fa fa-plus"></i> Tambahkan</button>
			</div>		
		</div>
		{!! Form::close() !!}
	</div>
</div>

<div class="box box-danger">
	<div class="box-header with-border">
		<h3 class="box-title">Data Skripsi</h3>
	</div>
	<div class="box-body">
		<?php
			$c = 1;
		?>
		<table class="table table-bordered table-striped table-skripsi">
			<thead>
				<tr>
					<th width="30px">No.</th>
					<th>NIM</th>
					<th>Nama</th>
					<th>Judul Skripsi</th>
					<th></th>
				</tr>
			</thead>
			<tbody>
				@if($tmp != null)
				@foreach($tmp as $t)
				<tr>
					<td>{{ $c }}</td>
					<td>{{ $t['mahasiswa_nim'] }}</td>
					<td>{{ $t['mahasiswa_nama'] }}</td>
					<td>{{ $t['judul'] }}</td>
					<td>
						<button class="btn btn-danger btn-xs btn-flat btn-delete" id="{{ $t['mahasiswa_id'] }}"><i class="fa fa-trash"></i></button>
					</td>
				</tr>	
				<?php
					$c++;
				?>
				@endforeach
				@else
				<tr>
					<td colspan="5" align="center">Belum ada data</td>
				</tr>
				@endif
			</tbody>
		</table>
		<br/>
		<div class="pull-right">
			<a href="{{ route('skripsi.tmp.remove') }}" class="btn btn-warning btn-flat btn-cancel"><i class="fa fa-times"></i> Batal</a>
			<a href="{{ route('skripsi.store') }}" class="btn btn-success btn-flat btn-save"><i class="fa fa-save"></i> Simpan</a>
		</div>
	</div>
</div>
@endsection			

@push('scripts')
<script src="{{ asset('/js/chosen.jquery.min.js') }}"></script>
<script>
	$(function(){
		$(".chosen-select").chosen({no_results_text: "Tidak ditemukan hasil pencarian untuk: "});
	});  
	
	$(document).on('click', '.btn-delete', function(e){
		$.ajax({
			type: "get",
			url: '/skripsi/tmp/'+ $(this).attr('id') +'/delete',
			dataType: 'json',
			success: function(response) {
				var list = '';
				var c= 1;
				jQuery.each(response, function(key, val) {
					list += '<tr><td>' + c + '</td><td>' + val['mahasiswa_nim'] + '</td><td>' + val['mahasiswa_nama'] + '</td><td>' + val['judul'] + '</td><td><button class="btn btn-danger btn-xs btn-flat btn-delete" id="'+ val['mahasiswa_id'] +'"><i class="fa fa-trash"></i></button></td></tr>';
					c++;
				});
				$('.table-skripsi tbody').empty().append(list);
			},
			error: function(jqXhr, textStatus, errorThrown){
				
			}
		});	
	});
	
	$(document).on('click', '.btn-add', function(e){
		if($('select[name=dosen1_id]').val() < 1) { $('select[name=dosen1_id]').focus(); return;}
		/* if($('select[name=dosen2_id]').val() < 1) { $('select[name=dosen2_id]').focus(); return;} */
		if($('select[name=mahasiswa_id]').val() < 1){ $('select[name=mahasiswa_id]').focus(); return;}
		if($('textarea[name=judul]').val() == ''){ $('textarea[name=judul]').focus(); return; }
		
		var mhs = $('select[name=mahasiswa_id] option:selected').text().split(' - ');
		$.ajax({
			type: "POST",
			data: {
				_token: "{{ csrf_token() }}",
				dosen1_id: $('select[name=dosen1_id]').val(),
				dosen2_id: $('select[name=dosen2_id]').val(),
				mahasiswa_id: $('select[name=mahasiswa_id]').val(),
				mahasiswa_nim: mhs[1],
				mahasiswa_nama: mhs[0],
				judul: $('textarea[name=judul]').val()
			},
			url: '{{ route("skripsi.tmp.store") }}',
			dataType: 'json',
			success: function(response) {
				var list = '';
				var c= 1;
				jQuery.each(response, function(key, val) {
					list += '<tr><td>' + c + '</td><td>' + val['mahasiswa_nim'] + '</td><td>' + val['mahasiswa_nama'] + '</td><td>' + val['judul'] + '</td><td><button class="btn btn-danger btn-xs btn-flat btn-delete" id="'+ val['mahasiswa_id'] +'"><i class="fa fa-trash"></i></button></td></tr>';
					c++;
				});
				$('.table-skripsi tbody').empty().append(list);
				
				$('input[name=mahasiswa]').val('');
				$('input[name=mahasiswa_id]').val('');
				$('textarea[name=judul]').val('');
			},
			error: function(jqXhr, textStatus, errorThrown){
				
			}
		});	
	});
</script>
@endpush
@push('styles')
<link rel="stylesheet" href="{{ asset('/css/chosen.min.css') }}">
<style>
	.chosen-container{
	font-size: inherit;
	}
	.chosen-single{
	padding: 6px 10px !important;
	box-shadow: none !important;
    border-color: #d2d6de !important;
	background: white !important;
	height: 34px !important;
	border-radius: 0px !important;
	}
	.chosen-drop{
    border-color: #d2d6de !important;	
	box-shadow: none;
	}
	
	.autocomplete-suggestions { -webkit-box-sizing: border-box; -moz-box-sizing: border-box; box-sizing: border-box; border: 1px solid #999; background: #FFF; cursor: default; overflow: auto; -webkit-box-shadow: 1px 4px 3px rgba(50, 50, 50, 0.64); -moz-box-shadow: 1px 4px 3px rgba(50, 50, 50, 0.64); box-shadow: 1px 4px 3px rgba(50, 50, 50, 0.64); }
	.autocomplete-suggestion { padding: 2px 5px; white-space: nowrap; overflow: hidden; }
	.autocomplete-no-suggestion { padding: 2px 5px;}
	.autocomplete-selected { background: #F0F0F0; }
	.autocomplete-suggestions strong { font-weight: bold; color: #000; }
	.autocomplete-group { padding: 2px 5px; }
	.autocomplete-group strong { font-weight: bold; font-size: 16px; color: #000; display: block; border-bottom: 1px solid #000; }
</style>
@endpush