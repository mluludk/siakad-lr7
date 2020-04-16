<div id="myModal" class="modal fade">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title" id="file-title"><strong>File Manager {{ ucfirst($type) }}</strong></h4>
			</div>
			
			<div class="modal-body">
				<ul class="nav nav-tabs" role="tablist">
					<li role="presentation" class="active"><a href="#file" aria-controls="file" role="tab" data-toggle="tab"><i class="fa fa-hdd-o"></i> File Saya</a></li>
					<li role="presentation"><a href="#upload" aria-controls="upload" role="tab" data-toggle="tab"><i class="fa fa-upload"></i> Upload</a></li>
					<!--
						<li role="presentation"><a href="#link" aria-controls="link" role="tab" data-toggle="tab"><i class="fa fa-link"></i> Link</a></li>
					-->
				</ul>
				<div class="tab-content">
					
					<div role="tabpanel" class="tab-pane active" id="file" style="min-height: 75px; max-height: 350px;overflow-y: scroll;overflow-x: hidden;">
						@if($file -> count())
						<table id="file-table" style="width:100%" class="compact">
							<thead>
								<tr>
									<td></td>
									<td></td>
									<td></td>
									<td></td>
									<td></td>
								</tr>
							</thead>
							<tbody>
								@foreach($file as $f)
								<?php
									$file = explode('/', $f -> namafile);
									$name = end($file);
									$ext = explode('.', $name)[1];
								?>
								<tr>
									<td valign="middle">
										<input type="checkbox" value="{{ $f -> id }}" class="cb-file" />
									</td>
									<td>
										@if($type == 'gambar')
										<img width="100px" src="{{ url('/getfile/' . $f -> namafile) }}"></img>
										@else
										@if(!isset($icons[$ext]))
										<i class="fa fa-file-o fa-3x" data-href="{{ url('/getfile/' . $f -> namafile) }}" data-mime="{{ $f -> mime }}"></i>
										@else
										<i class="fa {{ $icons[$ext] }} fa-3x" data-href="{{ url('/getfile/' . $f -> namafile) }}" data-label="{{ $f -> nama }}" data-mime="{{ $f -> mime }}"></i>
										@endif
										@endif
									</td>
									<td>
										<span class="name">{{ $name }}</span><br/>
										<span class="text-muted">{{ $f -> ukuran }}</span>
									</td>
									<td>
										<time class="timeago" datetime="{{ $f -> created_at ?? '-' }}"></time>
									</td>
									<td>
										<button class="btn btn-danger btn-xs btn-flat btn-del-file-{{ $rand }}" id="file-{{ $f -> id }}" type="button"><i class="fa fa-times"></i></button>
									</td>
								</tr>
								@endforeach
							</tbody>
						</table>
						@else
						<div>Belum ada file</div>
						@endif					
					</div>
					
					<div role="tabpanel" class="tab-pane" id="upload" style="min-height: 75px;">
						<div class="col-sm-12">
							{!! Form::open(['url' => url('/fm/upload'), 'class' => 'form-inline', 'files' => true, 'autocomplete' => 'off', 'id' => 'upload-' . $rand ]) !!}								
							<div class="input-group" style="width:80% !important;">
								<input type="text" class="form-control" readonly=""/>
								<label class="input-group-btn" style="width:20px !important;">
									<span class="btn btn-default btn-flat">
										Pilih File <input type="file" style="display: none;" name="file"/>
									</span>
								</label>
							</div>
							&nbsp;&nbsp;&nbsp;
							<button class="btn btn-primary btn-flat" id="btn-upload-{{ $rand }}" type="button">Unggah</button>
							{!! Form::hidden('kategori', $type) !!}
							{!! Form::close() !!}
							<span class="result"></span>
						</div>
					</div>
					
					<div role="tabpanel" class="tab-pane" id="link" style="min-height: 75px;">
						
					</div>
				</div>
			</div>
			
			<div class="modal-footer">
				<button class="btn btn-info btn-flat" id="btn-pilih-{{ $type }}-{{ $rand }}" type="button">Pilih</button>
			</div>
			
		</div>
	</div>
	
	<link rel="stylesheet" href="{{ asset('css/blue.css') }}">
	<link rel="stylesheet" href="{{ asset('css/datatables.min.css') }}">
	<link rel="stylesheet" href="{{ asset('/css/toastr.min.css') }}">
	<style>
		.modal-body{
		padding: 5px;
		}
		.tab-content{
		padding: 10px 5px;
		}
		.nav-tabs>li.active>a, 
		.nav-tabs>li.active>a:focus, 
		.nav-tabs>li.active>a:hover {	
		background-color: transparent;
		border-color: transparent;
		border-bottom: 3px solid red;
		}
		.nav>li>a {
		padding: 5px 5px;
		}
	</style>
	
	<script src="{{ asset('/js/jquery.form.min.js') }}"></script>
	<script src="{{ asset('js/datatables.min.js') }}"></script>
	<script src="{{ asset('js/icheck.min.js') }}"></script>
	<script src="{{ asset('/js/jquery.timeago.js') }}" type="text/javascript"></script>
	<script src="{{ asset('/js/toastr.min.js') }}"></script>
	<script>
		$(document).on('click', '.btn-del-file-{{ $rand }}', function(){	
		var me = $(this);
		var id = me.attr('id').split('-')[1];
		if(confirm('Apakah Anda yakin akan menghapus File ini?'))
		{
		$.ajax({
		url: "{{ url('file/delete') }}/" + id, 
		success: function(result){
		if(!result.success)
		{
		toastr.error(result.message, 'Error');
		}
		else
		{
		me.closest('tr').remove();	
		toastr.success(result.message, 'Sukses');
		}
		}
		});
		}
		});
		$(document).on('click', '#btn-upload-{{ $rand }}', function(){		
		if($('input[name=file]').val() == '') 
		{
		toastr.info('Pilih File terlebih dahulu', 'Informasi'); return false;
		}
		else{
		$('form#upload-{{ $rand }}').submit();
		}
		});
		
		$(document).on('click', '#btn-pilih-{{ $type }}-{{ $rand }}', function(){
		var cb = $('.cb-file:checked');
		if(cb.length < 1) {toastr.info('Pilih File terlebih dahulu', 'Informasi'); return false;}
		
		if('{{ $type }}' == 'gambar')
		{
		cb.each(function(i){
		var img = $(this).closest('td').next('td').html();
		addImage($(this).val(), img);
		});
		}
		else if('{{ $type }}' == 'video')
		{
		cb.each(function(i){
		var icon = $(this).closest('td').next('td').children('i');
		addVideo($(this).val(), icon.attr('data-href'), icon.attr('data-mime'));
		});
		
		$('.btn-video').addClass('hidden');	
		}
		else if('{{ $type }}' == 'dokumen')
		{
		cb.each(function(i){
		var icon = $(this).closest('td').next('td').children('i');
		addLink($(this).val(), icon.attr('class').split(' ')[1], icon.attr('data-href'), icon.attr('data-label'));
		});
		}
		
		$('#myModal').modal('hide');
		});
		
		$('form#upload-{{ $rand }}').ajaxForm({
		beforeSend: function() {
		
		},
		success: function(data) {
		if(!data.success)
		{
		$('.result').html('<span class="text-danger">Upload File gagal: '+ data.error +'</span>');
	}
	else
	{
		var row = '<tr>' +
		'<td valign="middle">' +
		'<input type="checkbox" value="'+ data.id +'"  class="cb-file"/>' +
		'</td><td>' +
		
		@if($type == 'gambar')
		'<img width="100px" src="{{ url('/getfile') }}/' + data.filename +'"></img>' +
		@else
		'<i class="fa '+ getIcon(data.filename.split('.')[1]) +' fa-3x" data-href="{{ url('/getfile') }}/' + data.filename +'" data-mime="'+ data.mime +'" data-label="'+ data.name +'"></i>' +
		@endif
		
		'</td><td>' +
		'<span class="name">'+ data.name +'</span><br/>' +
		'<span class="text-muted">'+ data.filesize +'</span>' +
		'</td><td>' +
		'Baru saja' +
		'</td><td>'+
		'<button class="btn btn-danger btn-xs btn-flat btn-del-file-{{ $rand }}" id="file-'+ data.id +'" type="button"><i class="fa fa-times"></i></button>' +
		'</td></tr>';
		
		$('#file-table tbody').prepend(row);
		$('.result').html('<span class="text-success">File berhasil di-upload</span>');
		
		$('ul.nav-tabs li').removeClass('active');
		$('ul.nav-tabs li:first').addClass('active');					
		$('div.tab-content > div').removeClass('active');
		$('div.tab-content > div:first').addClass('active');
		
		$('#upload-{{ $rand }} input[type=text]').val('');
		
		// $('input[type="checkbox"]').iCheck({
			// checkboxClass: 'icheckbox_flat-blue'
		// });
	}
},
complete: function(xhr) {
},
error: function(XMLHttpRequest, textStatus, errorThrown){
	console.log('Terjadi kesalahan: ' + errorThrown);
}
});  

function getIcon(ext)
{
	switch(ext){
		@foreach($icons as $e => $i) case '{{ $e }}': return '{{ $i }}'; break; @endforeach default: return '<i class="fa fa-file-o fa-3x"></i>';
	}
}
function addImage(id, img)
{
	//return if gambar already appended
	if($('input-gambar-' + id).length) return;
	
	var img_block = '<div class="col-sm-2"><div class="thumbnail">' + img + 
	'<div class="caption">'+
	'<button type="button" id="btn-gambar-' + id + '" class="btn btn-danger btn-xs btn-flat btn-del-gambar"><i class="fa fa-trash"></i> Hapus</button>';
	$('.gambar-preview').append(img_block + '<input type="hidden" name="isi[gambar][]" id="input-gambar-'+ id +'" value="'+ id +'" /></div></div></div>');
}
function addVideo(id, url, mime)
{
	if($('input-video-' + id).length) return;
	
	var vid_block = '<div class="col-sm-6"><div class="thumbnail"><video width="320" height="240" controls>' +  
	'<source src="'+ url +'" type="'+ mime +'">'+
	'Your browser does not support the video tag.</video>' +
	'<div class="caption">'+
	'<button type="button" id="btn-video-' + id + '" class="btn btn-danger btn-xs btn-flat btn-del-video"><i class="fa fa-trash"></i> Hapus</button>';
	$('.video-preview').append(vid_block + '<input type="hidden"  name="isi[video][]" id="input-video-'+ id +'" value="'+ id +'" /></div></div></div>');
}
function addLink(id, fa, url, label)
{
	if($('input-dokumen-' + id).length) return;
	var dok_block = '<tr id="tr-dokumen-'+ id +'" width="80%"><td>'+
	'<a href="'+ url +'" class="btn btn-default btn-xs btn-flat"><i class="fa '+ fa +'"></i> ' + label + '</a></td><td>'+
	'&nbsp;<button type="button" id="btn-dokumen-' + id + '" class="btn btn-danger btn-xs btn-flat btn-del-dokumen"><i class="fa fa-trash"></i> Hapus</button>';
	$('.dokumen-preview > tbody').append(dok_block + '<input type="hidden" name="isi[dokumen][]" id="input-dokumen-'+ id +'" value="'+ id +'" /></td></tr>');
}
</script>
<script>
	toastr.options = {
		"newestOnTop": true,
		"progressBar": true,
		"positionClass": "toast-top-center",
	}
	jQuery.timeago.settings.strings = {
		prefixAgo: null,
		prefixFromNow: null,
		suffixAgo: "yang lalu",
		suffixFromNow: "dari sekarang",
		seconds: "kurang dari semenit",
		minute: "sekitar satu menit",
		minutes: "%d menit",
		hour: "sekitar sejam",
		hours: "sekitar %d jam",
		day: "sehari",
		days: "%d hari",
		month: "sekitar sebulan",
		months: "%d bulan",
		year: "sekitar setahun",
		years: "%d tahun"
		};
		
		$(function () {
		$('#file-table').DataTable({
		dom: 'ft',
		drawCallback: function( settings ) {
		$("#file-table thead").remove(); 
		}
		});
		// $('input[type="checkbox"]').iCheck({
		// checkboxClass: 'icheckbox_flat-blue'
		// });	
		$("time.timeago").timeago();
		
		$(document).on('change', ':file', function() {
		var input = $(this),
		numFiles = input.get(0).files ? input.get(0).files.length : 1,
		label = input.val().replace(/\\/g, '/').replace(/.*\//, '');
		input.trigger('fileselect', [numFiles, label]);
		});
		
		$(document).ready( function() {
		$(':file').on('fileselect', function(event, numFiles, label) {
		
		var input = $(this).parents('.input-group').find(':text'),
		log = numFiles > 1 ? numFiles + ' files selected' : label;
		
		if( input.length ) {
		input.val(log);
		} else {
		if( log ) alert(log);
		}
		
		});
		});
		});
		</script>	
		</div>																												