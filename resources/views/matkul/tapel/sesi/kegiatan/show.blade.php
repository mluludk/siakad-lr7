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
			<h4>Bagikan {{ $jenis[$kegiatan -> jenis] }}</h4>
			<p>
				@if($kegiatan -> dibagikan == 'y')
				<span class="label label-info label-flat"><i class="fa fa-check"></i> Dibagikan</span>
				@elseif($kegiatan -> dibagikan == 'j')
				<span class="label label-default label-flat"><i class="fa fa-clock-o"></i> Sesuai jadwal</span>
				@else
				<span class="label label-warning label-flat"><i class="fa fa-exclamation-triangle"></i> Belum dibagikan</span>
				@endif
			</p>
			
			@if($kegiatan -> jenis == 1 or $kegiatan -> jenis == 3)
			
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
			
			@endif
			
			@if($kegiatan -> jenis == 2 or $kegiatan -> jenis == 3)
			<h4>Tanggal & Waktu Selesai</h4>
			<p>
				@if($kegiatan -> batas_waktu != '')
				{{ formatTanggalWaktu($kegiatan -> batas_waktu) }}
				<span class="pull-right" id="cd"></span>
				@else
				-
				@endif
			</p>	
			@endif
			
			@if($kegiatan -> jenis == 2)
			<h4>Tampilkan laporan kepada peserta setelah quiz selesai?</h4>
			<p>
				@if($kegiatan -> laporan == 'y')
				<span class="label label-info label-flat"><i class="fa fa-check"></i> Ditampilkan</span>
				@else
				<span class="label label-default label-flat"><i class="fa fa-exclamation-triangle"></i> Tidak Ditampilkan</span>
				@endif
			</p>			
			@endif
			
		</div>
	</div>
	
	@if($kegiatan -> jenis == 2)
	<div class="f-box">
		<div class="f-box-body">
			<h4>Pertanyaan Quiz</h4>
			@if(isset($kegiatan -> isi))
			<table width="100%" id="tbl-soal">
				<tbody>
					<?php $c = 1; ?>
					@foreach($kegiatan -> isi as $isi)
					<tr class="tr-soal">
						<td width="30px">{{ $c }}.</td>
						<td>{!! $isi['soal'] !!}</td>
						<td width="30px" class="text-info">{{ $isi['bobot'] }}</td>
					</tr>
					<?php $c++; ?>
					@endforeach
				</tbody>
			</table>
			@endif
		</div>
	</div>
	
	<div class="f-box">
		<div class="f-box-body">
			<h4><i class="fa fa-area-chart"></i> Laporan Quiz</h4>
			<p></p>
		</div>
	</div>	
	@endif
	
	@if($kegiatan -> jenis == 3)
	<div class="f-box">
		<div class="f-box-body">
			<h4>Tugas</h4>
			@if(isset($kegiatan -> isi['tugas']))
			<table width="100%" id="tbl-soal">
				<tbody>
					<?php $c = 1; ?>
					@foreach($kegiatan -> isi['tugas'] as $isi)
					<tr class="tr-soal">
						<td width="30px">{{ $c }}.</td>
						<td width="30px">
							@switch($isi['jenis'])
							@case(0)
							<i class="fa fa-align-left" title="Isian" data-toggle="tooltip" data-placement="top" ></i>
							@break
							
							@case(1)
							<i class="fa fa-check-square-o" data-toggle="tooltip" data-placement="top" title="Pilihan Ganda"></i>
							@break
							
							@case(2)
							<i class="fa fa-upload" data-toggle="tooltip" data-placement="top" title="Upload File"></i>
							@break
							
							@endswitch
						</td>
						<td>{!! $isi['soal'] !!}</td>
					</tr>
					
					@if($isi['jenis'] == 1)
					<tr>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>
							<ol type="A" style="padding-left:15px">
								@foreach($isi['pilihan'] as $p)
								<li>{{ $p }}</li>
								@endforeach
							</ol>
						</td>
					</tr>
					@endif
					
					@if($isi['jenis'] == 2)
					<tr>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>
							@switch($isi['file'])
							@case('gbr')
							<button class="btn btn-default" title="Upload Gambar" data-toggle="tooltip" data-placement="top"><i class="fa fa-file-photo-o" ></i> Gambar</button>
							@break
							
							@case('dok')
							<button class="btn btn-default" title="Upload Dokumen" data-toggle="tooltip" data-placement="top"><i class="fa fa-file-o" ></i> Dokumen</button>
							@break
							
							@case('vid')
							<button class="btn btn-default" title="Upload Video" data-toggle="tooltip" data-placement="top"><i class="fa fa-file-movie-o" ></i> Video</button>
							@break							
							@endswitch
						</td>
					</tr>
					@endif
					
					<?php $c++; ?>
					@endforeach
				</tbody>
			</table>
			@endif
		</div>
	</div>
	@endif
	
	<div class="f-box" style="border-left: 4px solid #ffdd57;">
		<div class="f-box-body">
			<h4><i class="fa fa-sticky-note-o"></i> Catatan</h4>
			<p>{!! $kegiatan -> catatan !!}</p>
		</div>
	</div>
	
	<div class="f-box">
		<div class="f-box-body">
			<h4><i class="fa fa-comments"></i> Komentar</h4>			
			<div class="box-body chat" id="chat-box" style="overflow-y: auto; width: auto; max-height: 550px;">

			</div>
			
			<form action="{{ route('komentar.post', ['Kegiatan', $kegiatan -> id]) }}" method="post" id="frm-komentar" data-time="0">
				{!! csrf_field() !!}
				<div class="input-group">
					<input type="text" name="komentar" placeholder="Tambahkan Komentar" class="form-control">
					<input type="hidden" name="last_id" value="{{ $last_id ?? 0}}">
					<span class="input-group-btn">
						<button class="btn btn-default modal-link" type="button" data-toggle="modal" href="#myModal" data-href="{{ route('file.manager', 'gambar') }}"><i class="fa fa-image"></i>&nbsp;</button>
						<button class="btn btn-default modal-link" type="button" data-toggle="modal" href="#myModal" data-href="{{ route('file.manager', 'video') }}"><i class="fa fa-video-camera"></i>&nbsp;</button>
						<button class="btn btn-default modal-link" type="button" data-toggle="modal" href="#myModal" data-href="{{ route('file.manager', 'dokumen') }}"><i class="fa fa-file-text-o"></i>&nbsp;</button>
						<button type="button" class="btn btn-success" id="btn-komentar" data-toggle="tooltip" data-placement="top" title="Kirim"><i class="fa fa-send"></i>&nbsp;</button>
					</span>
				</div>
			</form>
			
		</div>
	</div>
	
</div>
@endsection

<div class="modal-container"></div>
<style>
	#tbl-soal tr{
	border-bottom: 1px solid #eee;
	}
	#tbl-soal td{
	padding: 5px;
	}
	td > p{
	margin: 0;
	}
	#cd{
	font-size: 20px;
	}
</style>

@push('scripts')
<script src="{{ url('/js/jquery.form.min.js') }}"></script>

@if($kegiatan -> batas_waktu != '')
<script>
	// https://www.w3schools.com/howto/howto_js_countdown.asp
	var dt = new Date("{{ $kegiatan -> batas_waktu }}").getTime();
	var x = setInterval(function() {
	var now = new Date().getTime();
	var dst = dt - now;
	var cd = '<span class="text-info">';
	
	var hr = Math.floor(dst / (1000 * 60 * 60 * 24));
	var jm = Math.floor((dst % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
	var mn = Math.floor((dst % (1000 * 60 * 60)) / (1000 * 60));
	var dtk = Math.floor((dst % (1000 * 60)) / 1000);
	
	if(hr > 0) cd += hr + " Hari ";
	if(jm > 0) cd += jm + " Jam ";
	if(mn > 0) cd += mn + " Menit ";
	
	if(dtk > 0) cd += dtk + " Detik";
	
	$("#cd").html(cd);
	cd += '</span>';
	
	if (dst < 0) {
	clearInterval(x);
	$("#cd").html('<button class="btn btn-success"><i class="fa fa-check"></i> Selesai</button>');
	}
	}, 1000);
</script>
@endif

<script>
	$(document).on('click', '#btn-komentar', function(){
		if($('input[name=komentar]').val() != ''){
			$('form#frm-komentar').submit();
		}
	});
	
	$('form#frm-komentar').ajaxForm({
		beforeSend: function() {
			if($('input[name=komentar]').val() == '') return false;
			if(Date.now() - $('#frm-komentar').attr('data-time') < 5000){alert('Spam detected');return false;}
		},
		success: function(data) {
			if(!data.success)
			{
				alert('Terjadi kesalahan: ' + data.error);
			}
			else
			{
				var item = '';
				var last_id = 0;
				let i;
				for(i of Object.values(data.items))
				{					
					item += formatKomentar(i);
					last_id = i.id;
				}
				$('input[name=last_id]').val(last_id);
				$('#frm-komentar').attr('data-time', Date.now());
				$('.chat').append(item).scrollTop($("#chat-box")[0].scrollHeight);
				$("time.timeago").timeago();
			}
		},
		complete: function(xhr) {
			$('input[name=komentar]').val('');
		},
		error: function(XMLHttpRequest, textStatus, errorThrown){
			alert('Terjadi kesalahan: ' + errorThrown);
		}
	});  
	
	function getKomentar(model, id, last_id=0)
	{
		$.ajax({
			url: "{{ url('komentar') }}/" + model + "/" + id,
			type: "get",
			success: function(data){
				var item = '';
				var last_id = 0;
				let i;
				for(i of Object.values(data))
				{					
					item += formatKomentar(i);
					last_id = i.id;
				}
				$('input[name=last_id]').val(last_id);
				$('#frm-komentar').attr('data-time', Date.now());
				$('.chat').append(item).scrollTop($("#chat-box")[0].scrollHeight);
				$("time.timeago").timeago();			
			},
		error: function(XMLHttpRequest, textStatus, errorThrown){
			alert('Terjadi kesalahan: ' + errorThrown);
		}
		});  
	}
	function formatKomentar(i)
	{
		return '<div class="item">'+
		'<img src="'+ i.image +'" alt="'+ i.user +'" class="'+ i.status +'">'+			
		'<p class="message">'+
		'<a href="#" class="name">'+
		'<time class="text-muted pull-right timeago" datetime="'+ i.waktu +'">Baru saja</time>'+
		i.user +
		'</a>'+
		i.komentar +
		'</p>'+
		'</div>';
	}
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
	// $('.chat').scrollTop($("#chat-box")[0].scrollHeight);
	// $("time.timeago").timeago();
	getKomentar('Kegiatan', {{ $kegiatan -> id }});
	
	$('[data-toggle="tooltip"]').tooltip();
	
	$('.modal-link').click(function(e) {
		var url = $(this).attr('data-href');
		$('.modal-container').load(url, function(result){
			$('#myModal').modal({show:true});
			$('#myModal').attr('data-type', 'attachment');
			$('#myModal').attr('data-target', 'frm-komentar');
		});
	});
	
	$(document).on('hidden.bs.modal', '#myModal',function (e) {
		$('#myModal').remove();
	});
});
</script>
@endpush
