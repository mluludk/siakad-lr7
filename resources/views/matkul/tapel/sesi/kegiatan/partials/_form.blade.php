<div class="form-group">
	{!! Form::label('', 'Jenis:', array('class' => 'col-md-2 control-label')) !!}
	<div class="col-md-10">
		<p class="form-control-static">{{ $jenis }}</p>
	</div>
</div>
<div class="form-group">
	{!! Form::label('topik', 'Topik:', array('class' => 'col-md-2 control-label')) !!}
	<div class="col-md-9">
		{!! Form::text('topik', null, array('class' => 'form-control', 'placeholder' => 'Topik')) !!}
	</div>
</div>
<div class="form-group">
	{!! Form::label('dibagikan', 'Bagikan ' . $jenis . ':', array('class' => 'col-md-2 control-label')) !!}
	<div class="col-md-9">
		<label class="radio-inline">
			{!! Form::radio('dibagikan', 'y', null) !!}
			Sekarang
		</label>
		<label class="radio-inline">
			{!! Form::radio('dibagikan', 'n', null) !!}
			Nanti
		</label>
		<label class="radio-inline">
			{!! Form::radio('dibagikan', 'j', null) !!}
			Sesuai Jadwal
		</label>
	</div>
</div>

@if($jenis_id == 1 || $jenis_id == 3)
<div class="form-group">
	{!! Form::label('gambar', 'Gambar:', array('class' => 'col-md-2 control-label')) !!}
	<div class="col-md-9">
		<div class="gambar-preview col-md-12" style="margin: 0 -20px">
			@if(isset($media['gambar']))
			@foreach($media['gambar'] as $g)
			<div class="col-sm-2">
				<div class="thumbnail">
					<img src="{{ url('/getfile/' . $g['fullpath']) }}" alt="{{ $g['filename'] }}" />			
					<div class="caption">
						<button type="button" id="btn-gambar-{{$g['id'] }}" class="btn btn-danger btn-xs btn-flat btn-del-gambar">
							<i class="fa fa-trash"></i> Hapus
						</button>
						<input type="hidden" name="isi[gambar][]" id="input-gambar-{{$g['id'] }}" value="{{$g['id'] }}" />
					</div>
				</div>
			</div>
			@endforeach
			@endif
		</div>
		<button class="btn btn-success btn-flat modal-link btn-xs btn-gambar" type="button" data-toggle="modal" href="#myModal" data-href="{{ route('file.manager', 'gambar') }}">
			<i class="fa fa-image"></i> Tambahkan Gambar
		</button>
	</div>
</div>
<div class="form-group">
	{!! Form::label('video', 'Video:', array('class' => 'col-md-2 control-label')) !!}
	<div class="col-md-9">
		<div class="video-preview col-md-12" style="margin: 0 -20px">
			@if(isset($media['video']))
			@foreach($media['video'] as $g)			
			<div class="col-sm-6">
				<div class="thumbnail">
					<video controls style="display: block; margin: 0px auto;">
						<source src="{{ url('/getfile/' . $g['fullpath']) }}" type="{{ $g['mime'] }}">
						Your browser does not support the video tag.
					</video>
					<div class="caption">
						<button type="button" id="btn-video-{{$g['id'] }}" class="btn btn-danger btn-xs btn-flat btn-del-video"><i class="fa fa-trash"></i> Hapus</button>
						<input type="hidden"  name="isi[video][]" id="input-video-{{$g['id'] }}" value="{{$g['id'] }}" />
					</div>
				</div>
			</div>
			@endforeach
			@endif
		</div>
		<button class="btn btn-success btn-flat modal-link btn-xs btn-video @if(isset($media['video'])) hidden @endif" type="button" data-toggle="modal" href="#myModal" data-href="{{ route('file.manager', 'video') }}">
			<i class="fa fa-video-camera"></i> Tambahkan Video
		</button>
	</div>
</div>
<div class="form-group">
	{!! Form::label('dokumen', 'Dokumen:', array('class' => 'col-md-2 control-label')) !!}
	<div class="col-md-9">
		<table class="dokumen-preview" style="width: 100%;">
			<tbody>
				@if(isset($media['dokumen']))
				@foreach($media['dokumen'] as $g)
				<?php
					$file = explode('/', $g['fullpath']);
					$name = end($file);
					$ext = explode('.', $name)[1];
				?>				
				<tr id="tr-dokumen-'+ id +'" width="80%">
					<td>
						<a href="{{ url('/getfile/' . $g['fullpath']) }}" class="btn btn-default btn-xs btn-flat"><i class="fa {{ $icons[$ext] }}"></i> {{ $g['filename'] }}</a>
					</td>
					<td>
						&nbsp;
						<button type="button" id="btn-dokumen-{{ $g['id'] }}" class="btn btn-danger btn-xs btn-flat btn-del-dokumen">
							<i class="fa fa-trash"></i> Hapus
						</button>
						<input type="hidden" name="isi[dokumen][]" id="input-dokumen-{{ $g['id'] }}" value="{{ $g['id'] }}" />
					</td>
				</tr>
				@endforeach
				@endif
			</tbody>
		</table>
		<button class="btn btn-success btn-flat modal-link btn-xs btn-dokumen" type="button" data-toggle="modal" href="#myModal" data-href="{{ route('file.manager', 'dokumen') }}">
			<i class="fa fa-file-word-o"></i> Tambahkan Dokumen
		</button>
	</div>
</div>
@endif

<div class="form-group">
	<label for="summernote" class="col-md-2 control-label">Catatan:</label>
	<div class="col-md-5">
		<div id="summernote">{!! $kegiatan -> catatan ?? '' !!}</div>
	</div>
</div>
<input type="hidden" name="catatan" id="isi" >

<div class="form-group">
	<div class="col-md-offset-2 col-md-6">
		<button class="btn btn-primary btn-flat {{ $btn_type }}" type="button" id="post"><i class="fa fa-floppy-o"></i> Simpan</button>
	</div>		
</div>		

<div class="modal-container"></div>

@push('scripts')
<script src="{{ asset('/js/datepicker.min.js') }}"></script>
<script src="{{ asset('/summernote/summernote.min.js') }}"></script>
<script>
	$(document).on('click', '.btn-del-gambar', function(){
		if(confirm('Apakah anda yakin akan menghapus Gambar ini?'))
		{
			$(this).closest('.col-sm-2').remove();			
		}
	});
	$(document).on('click', '.btn-del-video', function(){
		if(confirm('Apakah anda yakin akan menghapus Video ini?'))
		{
			$(this).closest('.col-sm-6').remove();			
			$('.btn-video').removeClass('hidden');			
		}
	});
	$(document).on('click', '.btn-del-dokumen', function(){
		if(confirm('Apakah anda yakin akan menghapus Dokumen ini?'))
		{
			$(this).closest('tr').remove();			
		}
	});
	$(document).on('click', '#post', function(){
		var content = $('#summernote').summernote('code');
		$('#isi').val(content);
		$('#post-form').submit();
	});
	$(function(){
		
		$('.modal-link').click(function(e) {
			var url = $(this).attr('data-href');
			$('.modal-container').load(url, function(result){
				$('#myModal').modal({show:true});
			});
		});
		
		$(document).on('hidden.bs.modal', '#myModal',function (e) {
			$('#myModal').remove();
		});
		
		$('#summernote').summernote({
			minHeight: 300, 
			maxHeight: null, 
			focus: true,
		toolbar: [
		['style', ['bold', 'italic', 'underline', 'clear']],
		['font', ['strikethrough', 'superscript', 'subscript']],
		['fontsize', ['fontname', 'fontsize']],
		['color', ['color']],
		['para', ['ul', 'ol', 'paragraph']],
		['height', ['height']],
		['insert', ['link']]
		]
		});
		
		$(".date").datepicker({
		format:"dd-mm-yyyy", 
		autoHide:true,
		daysMin: ['Mg', 'Sn', 'Sl', 'Rb', 'Km', 'Jm', 'Sa'],
		daysMin: ['Mg', 'Sn', 'Sl', 'Rb', 'Km', 'Jm', 'Sa'],
		monthsShort: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'],
		});
		});
		</script>
		@endpush
		
		@push('styles')
		<link rel="stylesheet" href="{{ asset('/css/datepicker.min.css') }}">
		<link href="{{ asset('/summernote/summernote.css') }}" rel="stylesheet">
		@endpush																								