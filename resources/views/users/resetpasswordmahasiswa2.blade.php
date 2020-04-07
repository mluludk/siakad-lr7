@extends('app')

@section('title')
Reset Password Mahasiswa
@endsection

@section('header')
<section class="content-header">
	<h1>
		Pengguna
		<small>Reset Password Mahasiswa</small>
	</h1>		
	<ol class="breadcrumb">
		<li><a href="/"><i class="fa fa-dashboard"></i> Home</a></li>
		<li><a href="{{ url('/pengguna/?filter=mahasiswa') }}"> Pengguna Mahasiswa</a></li>
		<li class="active">Reset Password Mahasiswa</li>
	</ol>
</section>
@endsection

@push('styles')
<style>
	.form-group{
	margin-bottom: 0px;
	}
	label{
	text-align: left !important;
	}
	.daftar{
	padding: 3px 0 3px 10px;
	border: 1px solid #ddd;
	border-radius: 0 0 5px 5px;
	height: 300px;
	overflow-y: scroll;
	position:relative;
	}
	.number{
	display: inline-block;
	}
	.callout a.btn{
	text-decoration: none;
	}
</style>
@endpush

@push('scripts')
<script type="text/javascript">
	var target = [];
	$(document).on('change', '.options', function(){
		if($(this).val() == 'text')
		{
			$('#textPassword').attr('disabled', false);
		}
		else
		{
			$('#textPassword').attr('disabled', true);
		}
	});
	
	$(document).on('keydown', '#query', function(e){
		if(e.which == 13)
		{
			$('#btn-search').click();
		}
	});
	
	$(document).on('click', '#btn-search', function(e){
		e.preventDefault();
		if($('#query').val() == '') return;
		$.ajax({
			url: '{{ url("/pengguna/cari") }}',
			type: "post",
			data: {
				'query' : $('#query').val(),
				'_token': '{{ csrf_token() }}'
			},
			beforeSend: function()
			{
				$('#btn-search > i').removeClass('fa-search');
				$('#btn-search > i').addClass('fa-spin');
				$('#btn-search > i').addClass('fa-spinner');
			},
			success: function(data){
				var list = '';
				for(c=0; c<data.results.length; c++ ) list += '<div class="checkbox"><label><input type="checkbox" value="'+ data.results[c]['username'] +'" ><span class="txt">'+ data.results[c]['username'] +' - '+ data.results[c]['nama'] + '</span></label></div>';
				
				$('.from').empty().append(list); 
				
				$('#btn-search > i').addClass('fa-search');
				$('#btn-search > i').removeClass('fa-spin');
				$('#btn-search > i').removeClass('fa-spinner');
			}
		}); 
	});
	
	$(document).on('click', '#btn-remove', function(){
		$('.to input:checked').parents('.checkbox').remove();
		var n = 0;
		var item = target = [];
		var list = '';
		var str = '';
		if($('.to input:checkbox').length > 0)
		{
			$('.to input:checkbox').each(function(){
				me = $(this);
				item[n] = me.siblings('.txt').text();
				n++;
			});
		}
		for(var c=0; c<item.length; c++ ) 
		{
			str = item[c].split(' - ');
			list += '<div class="checkbox"><label><input type="checkbox" name="target" value="'+ str[0] +'" ><span class="number">' + (c+1) + '</span>. <span class="txt">' + item[c] + '</span></label></div>';
			target[c] = str[0];
		}			
		
		$('#target').val(JSON.stringify(target));
		$('.to').empty().append(list); 
	});
	
	$(document).on('click', '#btn-clear', function(){
		$('.to .checkbox').remove();
	});
	
	$(document).on('change', '.check-all', function (){
		var me = $(this);
		$("." + me.val() + " :checkbox").prop('checked', me.prop("checked"));
	});
	
	$(document).on('click', '#btn-in', function(){
		
		if($('.from input:checkbox').length < 1) return;
		
		var n = m = 0;
		var item = fitem = target = [];
		var list = flist = '';
		var str = '';
		if($('.to input:checkbox').length > 0)
		{
			$('.to input:checkbox').each(function(){
				me = $(this);
				item[n] = me.siblings('.txt').text();
				n++;
			});
		}
		$('.from input:checked').each(function(){
			me = $(this);
			item[n] = me.siblings('.txt').text();
			me.closest('.checkbox').remove();
			n++;
		});
		
		for(var c=0; c<item.length; c++ ) 
		{
			str = item[c].split(' - ');
			list += '<div class="checkbox"><label><input type="checkbox" value="' + str[0] + '"><span class="number">' + (c+1) + '</span>. <span class="txt">' + item[c] + '</span></label></div>';
			target[c] = str[0];
		}			
		$('#target').val(JSON.stringify(target));
		$('.to').empty().append(list); 
	});
</script>
@endpush

@section('content')
<div class="box box-danger">
	<div class="box-header with-border">
		<h3 class="box-title">Pilih Mahasiswa</h3>
		<div class="box-tools">
			<a href="{{ url('/pengguna/resetpassword/mahasiswa') }}" class="btn btn-danger btn-xs btn-flat" title="Reset Password Semua Mahasiswa"><i class="fa fa-user"></i></a>
		</div>
	</div>
	<div class="box-body">
		<div class="row">
			<div class="col-sm-12">
				<div class="form-horizontal" role="form">
					<div class="row" style="margin-top: 3px;">
						
						<div class="col-md-5">
							<div style="margin-top: 5px;">
								<div class="input-group">
									{!! Form::text('query', null, ['id' => 'query', 'class' => 'form-control', 'placeholder' => 'NIM atau Nama Mahasiswa', 'autofocus' => 'autofocus']) !!}
									<span class="input-group-btn">
										<button class="btn btn-info btn-flat" id="btn-search"><i class="fa fa-search"></i></button>
									</span>
								</div>
								<div class="checkbox">
									<label>
										<input type="checkbox" class="check-all" value="from" >
										Pilih semua
									</label>
								</div>
							</div>
							<div class="daftar from"></div>
						</div>
						
						<div class="col-md-2" style="height: 300px; display: flex; align-items: center; justify-content: center;">
							<div style="width: 100%">
								<button class="btn btn-lg btn-warning btn-block btn-flat" id="btn-in" type="button"><i class="fa fa-angle-double-right"></i></button>
							</div>
						</div>
						
						<div class="col-md-5">
							<div style="margin-top: 5px; text-align: right;">
								<button class="btn btn-flat" id="btn-remove"><i class="fa fa-remove"></i> Hapus terpilih</button>
								<button class="btn btn-flat" id="btn-clear"><i class="fa fa-trash"></i> Bersihkan</button>
							</div>
							
							<div class="checkbox">
								<label>
									<input type="checkbox" class="check-all" value="to" >
									Pilih semua
								</label>
							</div>
							<div class="daftar to"></div>
						</div>
						
					</div>
				</div>
			</div>
		</div>
		<form method="POST" action="{{ url('/pengguna/resetpassword/mahasiswa/filter') }}">
			{!! csrf_field() !!}
			<div class="row">
				<div class="col-md-3">
					<div class="radio">
						<label>
							<input type="radio" class="options" name="options" id="optionRandomAll" value="random-all" checked>
							Ganti dengan password acak untuk semua mahasiswa
						</label>
					</div>
				</div>
				<div class="col-md-3">
					<div class="radio">
						<label>
							<input type="radio" class="options" name="options" id="optionRandom" value="random">
							Ganti dengan password acak berbeda untuk tiap mahasiswa
						</label>
					</div>
				</div>
				<div class="col-md-6">
					<div class="radio">
						<label>
							<input type="radio" class="options" name="options" id="optionText" value="text">
							Ganti password semua mahasiswa dengan <input type="text" id="textPassword" name="textPassword" class="form-control input-sm" style="width: 100px; display: inline-block;" disabled/>
						</label>
						</div>
						</div>
						</div>
						<input type="hidden" name="target" id="target" />
						<button class="btn btn-danger btn-lg btn-flat" id="btn-reset" type="submit"><i class="fa fa-refresh"></i> Reset</button>
						</form>
						</div>
						</div>
						@endsection																			