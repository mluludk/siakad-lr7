<?php
$active = null;
$id = $kelas->id;
$sesi_side = $kelas->sesi;
?>
@extends('matkul.tapel.sesi.layout')

@section('tengah')
<div class="f-box-row">
    <div class="f-box-side" style="flex-grow:3;">
        <h4><i class="fa fa-list"></i> Sesi ke {{ $sesi -> sesi_ke }}</h4>
    </div>
</div>

<div class="f-box">
    <div class="f-box-body">

        <div class="f-box-side pull-left">
            <a href="{{ route('matkul.tapel.sesi.kegiatan.index', [$kelas -> id, $sesi -> id])}}"
                class="btn btn-default btn-flat btn-xs"><i class="fa fa-arrow-left"></i> Kembali</a>
        </div>

        @if(in_array($user -> role_id, $allowed))
        <div class="f-box-side pull-right">
            <a href="{{ route('matkul.tapel.sesi.kegiatan.delete', [$kelas -> id, $sesi -> id, $kegiatan -> id])}}"
                class="btn btn-danger btn-flat btn-xs has-confirmation"><i class="fa fa-trash"></i> Hapus</a>
        </div>
        @endif

        <div class="clearfix"></div>
        <h4>Topik</h4>
        <p>{{ $kegiatan -> topik }}</p>

        @if(in_array($user -> role_id, $allowed))
        <h4>Bagikan {{ $jenis[$kegiatan -> jenis] }}</h4>
        <p>
            @if($kegiatan -> dibagikan == 'y')
            <span class="label label-info label-flat"><i class="fa fa-check"></i> Dibagikan</span>
            @elseif($kegiatan -> dibagikan == 'j')
            <span class="label label-default label-flat"><i class="fa fa-clock-o"></i> Sesuai jadwal</span>
            @else
            <span class="label label-warning label-flat"><i class="fa fa-exclamation-triangle"></i> Belum
                dibagikan</span>
            @endif
        </p>
        @endif

        @if(in_array($user -> role_id, $allowed))
        <h4>Tampilkan laman diskusi?</h4>
        <p>
            @if($kegiatan -> diskusi == 'y')
            <span class="label label-info label-flat"><i class="fa fa-check"></i> Ditampilkan</span>
            @else
            <span class="label label-default label-flat"><i class="fa fa-exclamation-triangle"></i> Tidak
                Ditampilkan</span>
            @endif
        </p>
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
        <h4><i class="fa fa-video-camera"></i> Video Conference</h4>
        <p style="text-align:center">
            @if(is_array($kegiatan -> isi))
            @if(isset($kegiatan -> isi['started']))
            <a href="" class="btn btn-info btn-flat" disabled="disabled" title="Video Conference sudah dimulai"><i
                    class="fa fa-video-camera"></i> Video Conference sudah dimulai</a>
            @else

            @if($kegiatan -> isi['type'] == 2 and strtotime($kegiatan -> isi['start_time']) > time())
            Video Conference akan dimulai dalam <br />
            <span id="cd">stand by ...</span>
            @else
            <a href="{{ route('meeting.start', $kegiatan -> id) }}" class="btn btn-info btn-flat"
                title="Klik untuk memulai Conference" target="_blank"><i class="fa fa-video-camera"></i> Mulai
                Conference</a>
            @endif
            <div>
                <strong>ID</strong>
                <p>{{ $kegiatan -> isi['meeting_id'] }}</p>

                <strong>Password</strong>
                <p>{{ $kegiatan -> isi['password'] ?? '' }}</p>
            </div>
            @endif
            @endif
        </p>

    </div>
</div>

@if($kegiatan -> diskusi == 'y')
@include('komentar.form', $kegiatan)
@endif

@endsection

@include('matkul.tapel.sesi.kegiatan.partials._show', $kegiatan)

@if($kegiatan -> jenis == 4 and isset($kegiatan -> isi['start_time']) and strtotime($kegiatan -> isi['start_time']) >
time())

@push('scripts')
<script>
    var dt = new Date("{{ date('Y-m-d H:i:s', strtotime($kegiatan -> isi['start_time'])) }}").getTime();
    var x = setInterval(function() {
        var now = new Date().getTime();
        var dst = dt - now;
        var cd = '';

        var hr = Math.floor(dst / (1000 * 60 * 60 * 24));
        var jm = Math.floor((dst % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
        var mn = Math.floor((dst % (1000 * 60 * 60)) / (1000 * 60));
        var dtk = Math.floor((dst % (1000 * 60)) / 1000);

        if (hr > 0) cd += hr + " Hari ";
        if (jm > 0) cd += jm + " Jam ";
        if (mn > 0) cd += mn + " Menit ";

        if (dtk > 0) cd += dtk + " Detik";

        $("#cd").html(cd);

        if (dst < 0) {
            clearInterval(x);
            $("#cd").html('stand by...');
            window.location.reload(true);
        }
    }, 1000);
</script>
@endpush
@endif
