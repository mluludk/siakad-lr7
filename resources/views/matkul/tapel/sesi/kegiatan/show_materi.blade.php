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
            @if($kegiatan -> jenis != 4 or ($kegiatan -> dibagikan == 'n'))
            <a href="{{ route('matkul.tapel.sesi.kegiatan.edit', [$kelas -> id, $sesi -> id, $kegiatan -> id])}}"
                class="btn btn-warning btn-flat btn-xs"><i class="fa fa-edit"></i> Ubah</a>
            @endif
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
            <a href="{{ url('/getfile/' . $g['fullpath']) }}"
                class="btn btn-default btn-flat">@if(!isset($icons[$ext]))<i class="fa fa-file-o"></i> @else <i
                    class="fa {{ $icons[$ext] }}"></i>@endif {{ $g['filename'] }}</a>
        </p>
        @endforeach
        @else
        <p class="text-muted">Tidak ada dokumen</p>
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

@if($kegiatan -> diskusi == 'y')
@include('komentar.form', $kegiatan)
@endif

@endsection

@include('matkul.tapel.sesi.kegiatan.partials._show', $kegiatan)
