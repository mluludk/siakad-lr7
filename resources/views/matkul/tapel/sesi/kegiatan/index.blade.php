<?php
$active = null;
$id = $kelas->id;
$sesi_side = $kelas->sesi;
?>
@extends('matkul.tapel.sesi.layout')

@section('tengah')
<div class="f-box-row">
    <div class="f-box-side" style="flex-grow:3;">
        <h4><i class="fa fa-list"></i> Sesi ke {{ $sesi -> sesi_ke }} - {{ $sesi -> judul }}</h4>
    </div>
</div>

<div class="f-box">
    <div class="f-box-body">
        <h4>Topik</h4>
        <p>{{ $sesi -> judul }}</p>
        <h4>Tujuan Pembelajaran</h4>
        <p>{!! $sesi -> tujuan !!}</p>
    </div>
</div>

<div class="f-box-row">
    <div class="f-box-side" style="flex-grow:3;">
        <h4>Materi Pembelajaran</h4>
    </div>
    @if(in_array($user -> role_id, $allowed))
    <div class="f-box-side" style="flex-grow:1;  text-align: right; padding-top: 11px;">
        <div class="dropdown">
            <button class="btn btn-success btn-xs btn-flat dropdown-toggle" type="button" id="dm1"
                data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                <i class="fa fa-plus"></i> Tambah
            </button>
            <ul class="dropdown-menu dropdown-menu-right" aria-labelledby="dm1">
                @foreach($jenis as $k => $v)
                <li><a href="{{ route('matkul.tapel.sesi.kegiatan.create', [$kelas -> id, $sesi -> id, $k]) }}">
                        {{ $v }}</a></li>
                @endforeach
            </ul>
        </div>
    </div>
    @endif
</div>

@if($kegiatan -> count())
@foreach($kegiatan as $k)
<div class="f-box">
    <div class="f-box-row">
        <div class="f-box-side" style="width: 58px; padding: 10px;">
            <button class="btn btn-default"><i class="fa fa-navicon"></i></button>
        </div>
        <div class="f-box-side" style="width: 100%; border-left: 1px solid #ddd;">
            <div class="f-box-body">
                <h4>{{ $k -> topik }} <small><time class="timeago" datetime="{{ $k -> created_at }}"></time></small>
                </h4>
                <p>
                    <span class="text-muted">{{ $jenis[$k -> jenis] ?? '-' }}</span>
                </p>

                <div class="clearfix"></div>
                <div class="pull-left">
                    @if($k -> jenis == 2) <i class="fa fa-check text-success"></i> <i class="fa fa-pencil-square"></i>
                    @endif
                    @if($k -> jenis == 4) <i class="fa fa-check text-success"></i> <i class="fa fa-video-camera"></i>
                    @endif
                    @if(isset($k -> isi['gambar'])) <i class="fa fa-check text-success"></i> <i class="fa fa-image"></i>
                    @endif
                    @if(isset($k -> isi['video'])) <i class="fa fa-check text-success"></i> <i
                        class="fa fa-video-camera"></i> @endif
                    @if(isset($k -> isi['dokumen'])) <i class="fa fa-check text-success"></i> <i
                        class="fa fa-file-text-o"></i> @endif
                </div>
                <div class="pull-right">
                    @if($k -> dibagikan == 'y')
                    <span class="label label-info label-flat"><i class="fa fa-check"></i> Dibagikan</span>
                    @elseif($k -> dibagikan == 'j')
                    <span class="label label-default label-flat"><i class="fa fa-clock-o"></i> Sesuai jadwal</span>
                    @else
                    <span class="label label-warning label-flat"><i class="fa fa-exclamation-triangle"></i> Belum
                        dibagikan</span>
                    @endif
                </div>
                @if ($k -> batas_waktu != '')
                <div class="pull-right">
                    <span id="cd-{{ $k->id }}" class="countdown" countdown data-text="%s hari %s jam %s menit %s detik"
                        data-date="{{ $k -> batas_waktu }}"></span>
                </div>
                @endif
                <div class="clearfix" style="margin: 5px 0px;"></div>
                <a href="{{ route('matkul.tapel.sesi.kegiatan.show', [$kelas -> id, $sesi -> id, $k -> id])}}"
                    class="btn btn-link btn-block">Detail Materi</a>
            </div>
        </div>

        @if(in_array($user -> role_id, $allowed))
        <div class="f-box-side" style="width: 58px; padding: 10px;">
            <div class="dropdown">
                <button class="btn btn-default dropdown-toggle" type="button" id="dm1" data-toggle="dropdown"
                    aria-haspopup="true" aria-expanded="true">
                    <i class="fa fa-ellipsis-v"></i>
                </button>
                <ul class="dropdown-menu dropdown-menu-right" aria-labelledby="dm1">
                    <li><a
                            href="{{ route('matkul.tapel.sesi.kegiatan.duplicate', [$kelas -> id, $sesi -> id, $k -> id]) }}"><i
                                class="fa fa-paste"></i> Duplikat Sesi</a></li>
                    <li role="separator" class="divider"></li>
                    <li><a href="{{ route('matkul.tapel.sesi.kegiatan.delete', [$kelas -> id, $sesi -> id, $k -> id]) }}"
                            class="has-confirmation"><span class="text-danger"><i class="fa fa-trash"></i>
                                Hapus</span></a></li>
                </ul>
            </div>
        </div>
        @endif
    </div>
</div>
@endforeach
@else
<div class="f-box">
    <div class="f-box-body">
        Belum ada Materi.
    </div>
</div>
@endif
<div class="f-box-side pull-left">
    <a href="{{ route('matkul.tapel.sesi.index', $kelas -> id)}}" class="btn btn-default btn-flat btn-lg">
        <i class="fa fa-arrow-left"></i> Kembali
    </a>
</div>
@endsection

@push('styles')
<style>
    .countdown {
        display: inline-block;
        margin-right: 10px;
    }
</style>
@endpush

@push('scripts')
<script src="{{ asset('/js/countdown.min.js') }}"></script>
<script>
    @foreach($kegiatan as $k)
@if($k -> batas_waktu != '')
var cd{{ $k -> id}} = $('#cd-{{$k -> id }}');
$(cd{{ $k -> id}}).countdown({
    end: function (){
        cd{{ $k -> id}}.html('<label class="label label-success label-flat"><i class="fa fa-check"></i> Selesai</label>');
    }
});
@endif
@endforeach
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

	$(function() {
		$("time.timeago").timeago();
	});
</script>
@endpush
