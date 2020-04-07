@extends('app')

@section('title')
Laravel Log
@endsection

@section('header')
<section class="content-header">
	<h1>
		Laravel Log
		<small>Reader</small>
	</h1>		
	<ol class="breadcrumb">
		<li><a href="/"><i class="fa fa-dashboard"></i> Home</a></li>
		<li class="active">Laravel Log Reader</li>
	</ol>
</section>
@endsection

@section('content')
<div class="box">
	<div class="box-header with-border">
		<h3 class="box-title">Laravel Log Reader</h3>
	</div>
	<div class="box-body">
		@if(!count($date))
		<p class="text-muted">Belum ada data</p>
		@else
		<?php $c=1; ?>
		<table class="table table-bordered">
			<thead>
				<tr style="background-image: -webkit-gradient(linear,0 top,0 bottom,from(#3A8341),to(#609C40)); background-image: -moz-linear-gradient(#3A8341,#054a10);color: #fff;">
					<th width="238px">ID</th>
					<th>Message</th>
					<th width="70px">Level</th>
					<th width="70px">Time</th>
				</tr>
			</thead>
			<tbody>
				@foreach($date as $d => $file)
				<tr class="tr-{{ $d }}">
					<th colspan="4"><a id="{{ $d }}" class="load-log" href="#"><i class="fa fa-plus-circle"></i></a> Date {{ $d }}</th>
				</tr>
				@endforeach
			</tbody>
		</table>
		@endif
	</div>
</div>

<div class="loader hidden">
	<img src="{{ asset('images/loading.gif') }}"/>
</div>
@endsection		

@push('styles')
<style>
	ol.traces{
	padding-left: 12px;
	}
	.loader{
	width: 100%;
	height: 100%;
	position: absolute;
	z-index: 9999;
	top: 0px;
	left: 0px;
	}
	.loader img{
	position: absolute;
	top: 30%;
	right: 50%;
	}
</style>
@endpush

@push('scripts')
<script>
	function loading(state)
	{
		if(state) $('.loader').removeClass('hidden');	
		else  $('.loader').addClass('hidden');	
	}
	function morphIcon(me, plus)
	{
		if(plus)
		me.children('i').removeClass('fa-minus-circle').addClass('fa-plus-circle');
		else
		me.children('i').removeClass('fa-plus-circle').addClass('fa-minus-circle');
	}
	
	$(document).on('click', '.loaded', function(){
		var me = $(this);
		if(me.children('i').hasClass('fa-plus-circle'))
		{
			$('.tr-detail-' + me.attr('id')).removeClass('hidden');
			morphIcon(me, false);
		}
		else
		{
			$('.tr-detail-' + me.attr('id')).addClass('hidden');
			morphIcon(me, true);
		}
	});
	
	$(document).on('click', '.load-detail-log', function(e){
		e.preventDefault();
		var me = $(this);
		var id = me.attr('id').split('.');		
		
		$.ajax({
			url: "{{ url('/logreader') }}/" + id[0] + '/' + id[1],
			data: {
				'_token': '{{ csrf_token() }}'
			},
			type: "get",
			beforeSend: function()
			{
				loading(true);
			},
			success: function(data){
				if(data)
				{
					var tr = '';
					var traces = '<ol class="traces">';
					for(i in data.stack_traces) 
					{
						traces += '<li>Caught at: ' + data.stack_traces[i].caught_at + '<br/>Caught in: ' + data.stack_traces[i].in + ' (line ' + data.stack_traces[i].line + ')</li>';
					}
					traces += '</ol>';
					
					tr += '<tr class="tr-detail-'+ id[0] +'"><td colspan="4"><table width="100%">';
					tr += '<tr><th width="200px">Exception</th><td>:</td><td>' + data.context.exception + '</td></tr>';
					tr += '<tr><th>Caught in</th><td>:</td><td>' + data.context.in + ' (line ' + data.context.line + ')</td></tr>';
					tr += '<tr><th valign="top">Stack trace</th><td valign="top">:</td><td>' + traces + '</td></tr>';
					tr += '</table></td></tr>';
					me.closest('tr').after(tr);
				}
				loading(false);
				me.addClass('detail-loaded').removeClass('load-detail-log');
			}
		});  
	});
	$(document).on('click', '.load-log', function(e){
		e.preventDefault();
		var me = $(this);
		var date = me.attr('id');		
		
		$.ajax({
			url: "{{ url('/logreader') }}/" + date,
			data: {
				'_token': '{{ csrf_token() }}'
			},
			type: "get",
			beforeSend: function()
			{
				me.addClass('hidden');
				loading(true);
			},
			success: function(data){
				if(data)
				{
					var tr = '';
					var level = '';
					for(id in data )
					{
						if(data[id].level == 'error')
						level = '<span class="text-danger"><i class="fa fa-exclamation-triangle"></i> '+ data[id].level +'</span>';
						else if(data[id].level == 'warning')
						level = '<span class="text-warning"><i class="fa fa-info-circle"></i> '+ data[id].level +'</span>';
						else
						level = '<span class="text-info"><i class="fa fa-info-circle"></i> '+ data[id].level +'</span>';
						
						tr += '<tr class="tr-detail-'+ date +'">';
						tr += '<td><a id="'  + date + '.' + data[id].id + '" class="load-detail-log" href="#">' + data[id].id + '</a></td>';
						tr += '<td>' + data[id].context.message + '</td>';
						tr += '<td>' + level + '</td>';
						tr += '<td>' + data[id].date.date.substr(11, 8) + '</td>';
						tr += '</tr>';
					}
					me.closest('tr').after(tr);
				}
				loading(false);
				me.addClass('loaded').removeClass('hidden load-log');
				morphIcon(me, false);
			}
		});  
	});
</script>
@endpush						