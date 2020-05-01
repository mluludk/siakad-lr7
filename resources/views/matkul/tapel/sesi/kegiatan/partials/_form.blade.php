
<div class="form-group">
	{!! Form::label('topik', 'Topik:') !!}
	{!! Form::text('topik', null, array('class' => 'form-control', 'placeholder' => 'Topik')) !!}
</div>
<div class="form-group">
	{!! Form::label('dibagikan', 'Bagikan ' . $jenis . ':') !!}
	<div class="col-md-12" style="padding-left:0px; margin-bottom: 15px;">
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
	{!! Form::label('gambar', 'Gambar:') !!}
	<div class="gambar-preview" style="width: 100%;">
		@if(isset($media['gambar']))
		@foreach($media['gambar'] as $g)	
		<div class="thumbnail">
			<img src="{{ url('/getfile/' . $g['fullpath']) }}" alt="{{ $g['filename'] }}" style="max-width: 500px;"/>			
			<div class="caption">
				<button type="button" id="btn-gambar-{{$g['id'] }}" class="btn btn-danger btn-xs btn-flat btn-del-gambar">
					<i class="fa fa-trash"></i> Hapus
				</button>
				<input type="hidden" name="isi[gambar][]" id="input-gambar-{{$g['id'] }}" value="{{$g['id'] }}" />
			</div>
		</div>
		@endforeach
		@endif
	</div>
	<div class="clearfix"></div>
	<button class="btn btn-success btn-flat modal-link btn-xs btn-gambar" type="button" data-toggle="modal" href="#myModal" data-href="{{ route('file.manager', 'gambar') }}">
		<i class="fa fa-image"></i> Tambahkan Gambar
	</button>
</div>
<div class="form-group">
	{!! Form::label('video', 'Video:') !!}
	<div class="video-preview">
		@if(isset($media['video']))
		@foreach($media['video'] as $g)	
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
		@endforeach
		@endif
	</div>
	<button class="btn btn-success btn-flat modal-link btn-xs btn-video @if(isset($media['video'])) hidden @endif" type="button" data-toggle="modal" href="#myModal" data-href="{{ route('file.manager', 'video') }}">
		<i class="fa fa-video-camera"></i> Tambahkan Video
	</button>
</div>
<div class="form-group">
	{!! Form::label('dokumen', 'Dokumen:') !!}
	<table class="dokumen-preview" style="width: auto;">
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

@elseif($jenis_id == 2 || $jenis_id == 3)
<div class="form-group">
	{!! Form::label('batas1', 'Tanggal & Waktu Selesai:') !!}
	<?php
		$batas = [null, null];
		if(isset($kegiatan) and $kegiatan -> batas_waktu != '')
		{
			$batas = explode(' ', substr($kegiatan -> batas_waktu, 0, -3));
		}
	?>
	<div class="col-md-12" style="padding-left:0px; margin-bottom: 15px;">
		{!! Form::text('batas1', $batas[0], ['class' => 'form-control date2', 'placeholder' => 'Tanggal', 'style' => 'display: inline-block; width: 200px;']) !!}
		{!! Form::text('batas2', $batas[1], ['class' => 'form-control time', 'placeholder' => 'Waktu', 'style' => 'display: inline-block; width: 200px;']) !!}
	</div>
</div>
@elseif($jenis_id == 2)
<div class="form-group">
	{!! Form::label('laporan', 'Tampilkan laporan kepada peserta setelah quiz selesai?') !!}
	<div class="col-md-12" style="padding-left:0px; margin-bottom: 15px;">
		<label class="radio-inline">
			{!! Form::radio('laporan', 'y', null) !!}
		Tampilkan
		</label>
		<label class="radio-inline">
		{!! Form::radio('laporan', 'n', null) !!}
		Tidak
		</label>
	</div>
</div>
<br/>
@endif

@if($jenis_id <= 3)
<div class="modal-container"></div>
@push('scripts')
<script>
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
	});
</script>
@endpush
@endif

@if($jenis_id == 1 || $jenis_id == 3)
@push('scripts')
<script>
	$(document).on('click', '.btn-del-gambar', function(){
		if(confirm('Apakah anda yakin akan menghapus Gambar ini?'))
		{
			$(this).closest('.thumbnail').remove();			
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
</script>
@endpush
@endif

@push('scripts')
<script src="{{ asset('/js/datepicker.min.js') }}"></script>
<script src="{{ asset('/js/jquery-clock-timepicker.min.js') }}"></script>
<script src="{{ asset('/summernote/summernote.min.js') }}"></script>
<script>	
	$(document).on('click', '#post', function(){
	var content = $('#summernote').summernote('code');
	$('#isi').val(content);
	$('#post-form').submit();
});
$(function(){
	
	$('#summernote').summernote({
		minHeight: 100, 
		maxHeight: null, 
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
	
	$(".time").clockTimePicker();
	$(".date").datepicker({
		format:"dd-mm-yyyy", 
		autoHide:true,
		daysMin: ['Mg', 'Sn', 'Sl', 'Rb', 'Km', 'Jm', 'Sa'],
		daysMin: ['Mg', 'Sn', 'Sl', 'Rb', 'Km', 'Jm', 'Sa'],
		monthsShort: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'],
	});
	$(".date2").datepicker({
		format:"yyyy-mm-dd", 
		startDate: "{{ date('Y-m-d') }}",
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