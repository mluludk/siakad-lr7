<?php
	$active = null;
	$id = $kelas -> id;
	$sesi_side = $kelas -> sesi;
?>
@extends('matkul.tapel.sesi.layout')

@section('tengah')
<div class="f-col-2">
	<div class="f-box-row">
		<div class="f-box-side" style="flex-grow:3;">
			<h4><i class="fa fa-list"></i> Sesi ke {{ $sesi -> sesi_ke }}</h4>
		</div>
	</div>
	
	<div class="f-box">
		<div class="f-box-body">
			
			<div class="f-box-side pull-left">
				<a href="{{ route('matkul.tapel.sesi.kegiatan.index', [$kelas -> id, $sesi -> id])}}" class="btn btn-default btn-flat btn-xs"><i class="fa fa-arrow-left"></i> Kembali</a>
			</div>
			
			<div class="f-box-side pull-right">
				<a href="{{ route('matkul.tapel.sesi.kegiatan.edit', [$kelas -> id, $sesi -> id, $kegiatan -> id])}}" class="btn btn-warning btn-flat btn-xs"><i class="fa fa-edit"></i> Ubah</a>
				<a href="{{ route('matkul.tapel.sesi.kegiatan.delete', [$kelas -> id, $sesi -> id, $kegiatan -> id])}}" class="btn btn-danger btn-flat btn-xs has-confirmation"><i class="fa fa-trash"></i> Hapus</a>
			</div>
			<div class="clearfix"></div>
			<h4>Topik</h4>
			<p>{{ $kegiatan -> topik }}</p>
			<h4>Bagikan Materi</h4>
			<p>
				@if($kegiatan -> dibagikan == 'y')
				<span class="label label-info label-flat"><i class="fa fa-check"></i> Dibagikan</span>
				@elseif($kegiatan -> dibagikan == 'j')
				<span class="label label-default label-flat"><i class="fa fa-clock-o"></i> Sesuai jadwal</span>
				@else
				<span class="label label-warning label-flat"><i class="fa fa-exclamation-triangle"></i> Belum dibagikan</span>
				@endif
			</p>
			
			<h4>Gambar</h4>
			@if(isset($media['gambar']))
			@foreach($media['gambar'] as $g)
			<div class="thumbnail">
				<img src="{{ url('/getfile/' . $g['fullpath']) }}" alt="{{ $g['filename'] }}" />
			</div>
			@endforeach
			@else
			<p class="text-muted">Tidak ada gambar</p>
			@endif
			
			<h4>Video</h4>
			@if(isset($media['video']))
			@foreach($media['video'] as $g)
			<div class="thumbnail">
				<video controls style="display: block; margin: 0px auto;">
					<source src="{{ url('/getfile/' . $g['fullpath']) }}" type="{{ $g['mime'] }}">
					Your browser does not support the video tag.
				</video>
			</div>
			@endforeach
			@else
			<p class="text-muted">Tidak ada video</p>
			@endif
			
			<h4>Dokumen</h4>
			@if(isset($media['dokumen']))
			@foreach($media['dokumen'] as $g)
			<?php
				$file = explode('/', $g['fullpath']);
				$name = end($file);
				$ext = explode('.', $name)[1];
			?>
			<p>
				<a href="{{ url('/getfile/' . $g['fullpath']) }}" class="btn btn-default btn-flat">@if(!isset($icons[$ext]))<i class="fa fa-file-o" ></i> @else <i class="fa {{ $icons[$ext] }}"></i>@endif  {{ $g['filename'] }}</a>
			</p>
			@endforeach
			@else
			<p class="text-muted">Tidak ada dokumen</p>
			@endif
		</div>
	</div>
	
	<div class="f-box" style="border-left: 4px solid #ffdd57;">
		<div class="f-box-body">
			<h4><i class="fa fa-sticky-note-o"></i> Catatan</h4>
			<p>{!! $kegiatan -> catatan !!}</p>
		</div>
	</div>
	
	<div class="f-box">
		<div class="f-box-body">
			<h4><i class="fa fa-comments"></i> Komentar</h4>			
			<div class="box-body chat" id="chat-box" style="overflow-y: auto; width: auto; max-height: 250px;">
				@if($kegiatan -> komentar -> count())
				@foreach($kegiatan -> komentar as $m)
				
				<?php
					$userimage = $m -> author -> authable -> foto !== '' ? '/getimage/' . $m -> author -> authable -> foto : '/images/logo.png';
				?>
				<div class="item">
					<img src="{{ url($userimage) }}" alt="{{ $m -> author -> authable -> nama }}" class="online">					
					<p class="message">
						<a href="#" class="name">
							<time class="text-muted pull-right timeago" datetime="{{ $m -> waktu }}"></time>
							{{ $m -> author -> authable -> nama }}
						</a>
						{{ $m -> komentar }}
					</p>
				</div>
				@endforeach
				@endif
			</div>
			
			<form action="{{ route('komentar.post', ['Kegiatan', $kegiatan -> id]) }}" method="post" id="frm-komentar">
				{!! csrf_field() !!}
				<div class="input-group">
					<input type="text" name="komentar" placeholder="Tambahkan Komentar" class="form-control">
					<span class="input-group-btn">
						<button type="button" class="btn btn-default btn-flat" id="btn-komentar"><i class="fa fa-send"></i></button>
					</span>
				</div>
			</form>
			
		</div>
	</div>
	
</div>

@endsection

@push('scripts')
<script src="{{ url('/js/jquery.form.min.js') }}"></script>
<script>
	$(document).on('click', '#btn-komentar', function(){		
	$('form#frm-komentar').submit();
});

$('form#frm-komentar').ajaxForm({
	beforeSend: function() {
		
	},
	success: function(data) {
		if(!data.success)
		{
			alert('Terjadi kesalahan: ' + data.error);
		}
		else
		{
			var item = '<div class="item">'+
			'<img src="'+ data.image +'" alt="'+ data.user +'" class="'+ data.status +'">'+			
			'<p class="message">'+
			'<a href="#" class="name">'+
			'<time class="text-muted pull-right timeago" datetime="'+ data.waktu +'">Baru saja</time>'+
			data.user +
			'</a>'+
			data.komentar +
			'</p>'+
			'</div>';
			$('.chat').append(item);
		}
	},
	complete: function(xhr) {
		$('input[name=komentar]').val('');
	},
	error: function(XMLHttpRequest, textStatus, errorThrown){
		alert('Terjadi kesalahan: ' + errorThrown);
	}
});  
</script>
<script src="{{ asset('/js/jquery.timeago.js') }}" type="text/javascript"></script>
<script>
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
		$("time.timeago").timeago();
	});
</script>
@endpush
