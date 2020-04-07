@extends('app')

@section('title')
Dosen Pembimbing Skripsi
@endsection

@section('header')
<section class="content-header">
	<h1>
		Skripsi
		<small>Dosen Pembimbing Skripsi</small>
	</h1>
	<ol class="breadcrumb">
		<li><a href="/"><i class="fa fa-dashboard"></i> Home</a></li>
		<li><a href="{{ url('/skripsi') }}"> Skripsi</a></li>
		<li class="active">Dosen Pembimbing Skripsi</li>
	</ol>
</section>
@endsection

@push('scripts')
<script src="{{ asset('/js/chosen.jquery.min.js') }}"></script>
<script src="{{ asset('/js/list.min.js') }}"></script>
<script>
	var options = {
		valueNames: [ 'nim', 'nama' ]
	};
	
	var mahasiswaList = new List('mahasiswa-list', options);
</script>
<script>
	$(function(){
		$(".chosen-select").chosen({no_results_text: "Tidak ditemukan hasil pencarian untuk: "});
	});  
</script>
<script type="text/javascript">
	var loader = '<i class="fa fa-spinner fa-spin"></i>';
	
	$(document).on('click', '.btn-in', function(){
		
		if($('.from input:checkbox').length < 1) return;
		
		var n = 0;
		var id = {};
		var name = {};
		var clone = '';
		$('.from input:checked').each(function(){
			me = $(this);
			id[n] = me.val();
			n++;
		});
		$('input:checked').prop('checked', false);
		if(n > 0)
		{
			$.ajax({
				url: '{{ url("/skripsi/pembimbing") }}',
				type: "post",
				data: {
					'id' : id,
					'dosen': $('select[name=\'dosen\'].select-to').val(),
					'angkatan': $('select[name=\'angkatan\'].select-to').val(),
					'_token': '{{ csrf_token() }}'
				},
				beforeSend: function()
				{
					$('.btn-in').html(loader);
				},
				success: function(data){
					$('.from').empty();
					if(data.from.length > 0)
					{
						var list = '';
						for(c=0; c<data.from.length; c++ ) list += '<div class="checkbox"><label><input type="checkbox" value="'+ data.from[c]['id'] +'" ><span class="number">' + (c + 1) + '</span>. '+ data.from[c]['NIM'] +' - '+ data.from[c]['nama'] +'</label></div>';
						$('.from').append(list);
					}
					$('.to').empty();
					if(data.to.length > 0)
					{
						var list = '';
						for(c=0; c<data.to.length; c++ ) list += '<div class="checkbox"><label><input type="checkbox" value="'+ data.to[c]['id'] +'" ><span class="number">' + (c + 1) + '</span>. '+ data.to[c]['NIM'] +' - '+ data.to[c]['nama'] +'</label></div>';
						$('.to').append(list);
					}
					$('.btn-in').html('>>');
				}
			});  
		}
	});
	
	$(document).on('change', '.check-all', function (){
		var me = $(this);
		$("." + me.val() + " :checkbox").prop('checked', me.prop("checked"));
	});
	
	$(document).on('click', '.btn-filter-dosen', function (){
		var me = $(this);
		var icon = me.html();
		var target = me.attr('id').split('-')[1].trim();
		$.ajax({
			url: '{{ url("/skripsi/pembimbing/anggota") }}',
			type: "post",
			data: {
				'dosen' : me.siblings('select[name=\'dosen\']' ).val(),
				'angkatan' : me.siblings('select[name=\'angkatan\']' ).val(),
				'_token': '{{ csrf_token() }}'
			},
			beforeSend: function()
			{
				me.html(loader);
			},
			success: function(data){
				$('.from').empty();
				if(data.from.length > 0)
				{
					var list = '';
					for(c=0; c<data.from.length; c++ ) list += '<div class="checkbox"><label><input type="checkbox" value="'+ data.from[c]['id'] +'" ><span class="number">' + (c + 1) + '</span>. '+ data.from[c]['NIM'] +' - '+ data.from[c]['nama'] +'</label></div>';
					$('.from').append(list);
				}
				$('.to').empty();
				if(data.to.length > 0)
				{
					var list = '';
					for(c=0; c<data.to.length; c++ ) list += '<div class="checkbox"><label><input type="checkbox" value="'+ data.to[c]['id'] +'" ><span class="number">' + (c + 1) + '</span>. '+ data.to[c]['NIM'] +' - '+ data.to[c]['nama'] +'</label></div>';
					$('.to').append(list);
				}
				me.html(icon);
			}
		});  
	});
</script>
@endpush

@push('styles')
<style>
	.form-group{
	margin-bottom: 0px;
	}
	label{
	text-align: left !important;
	}
	.daftar{
	margin-top: 10px;
	padding: 3px 0 3px 10px;
	border: 1px solid #ddd;
	border-radius: 5px;
	height: 300px;
	overflow-y: scroll;
	position:relative;
	}
	.loader{
	position: absolute;
	left: 50%;
	top: 30%;
	}
	select.form-control{
	display: inline-block;
	width: auto;
	}
	.btn-filter, .btn-filter-dosen{
	margin-top: -3px;
	}
</style>
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
</style>
@endpush

@section('content')
<div class="box box-danger">
	<div class="box-body">
		<div class="row">
			<div class="col-sm-12">
				<div class="form-horizontal" role="form">
					<div class="row" style="margin-top: 3px;">
						<div id="mahasiswa-list" class="col-md-5">
							<div style="margin-top: 5px;">
								{!! Form::text('cari', null, ['class' => 'search select-from form-control', 'placeholder' => 'Cari NIM / Nama']) !!}
								<div class="checkbox">
									<label>
										<input type="checkbox" class="check-all" value="from" >
										Pilih semua
									</label>
								</div>
							</div>
							<div class="list daftar from">
								<?php $c = 1; ?>
								@foreach($mahasiswa as $mhs)
								<div class="checkbox">
									<label>
										<input type="checkbox" value="{{ $mhs -> id }}" >
										<span class="number">{{ $c }}</span>. <span class="nim">{{ $mhs -> NIM }}</span> - <span class="nama">{{ $mhs->nama }}</span>
										@if(in_array($mhs -> id, $terdaftar))
										<i class="fa fa-check text-success"></i>
										@endif
									</label>
								</div>
								<?php $c++; ?>
								@endforeach
							</div>
						</div>
						<div class="col-md-2" style="height: 400px; display: flex; align-items: center; justify-content: center;">
							<div style="width: 100%">
								<button class="btn btn-lg btn-primary btn-in btn-block btn-flat" type="button">>></button>
							</div>
						</div>
						<div class="col-md-5">
							<div style="margin-top: 5px;">
								{!! Form::select('dosen', $dosen, null, ['class' => 'select-to form-control chosen-select']) !!}
								{!! Form::select('angkatan', $angkatan, null, ['class' => 'select-to form-control chosen-select']) !!}
								<button class="btn btn-success btn-filter-dosen btn-flat" id="btn-to"><i class="fa fa-filter"></i></button>
								<div class="checkbox">
									<label>
										<input type="checkbox" class="check-all" value="to" >
									Pilih semua
									</label>
								</div>
							</div>
							<div class="daftar to">
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection																																																														