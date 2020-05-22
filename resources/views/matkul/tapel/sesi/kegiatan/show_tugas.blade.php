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

        <div class="f-box-side pull-right">
            @if($kegiatan -> jenis != 4 or $kegiatan -> dibagikan == 'n')
            <a href="{{ route('matkul.tapel.sesi.kegiatan.edit', [$kelas -> id, $sesi -> id, $kegiatan -> id])}}"
                class="btn btn-warning btn-flat btn-xs"><i class="fa fa-edit"></i> Ubah</a>
            @endif
            <a href="{{ route('matkul.tapel.sesi.kegiatan.delete', [$kelas -> id, $sesi -> id, $kegiatan -> id])}}"
                class="btn btn-danger btn-flat btn-xs has-confirmation"><i class="fa fa-trash"></i> Hapus</a>
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
            <span class="label label-warning label-flat"><i class="fa fa-exclamation-triangle"></i> Belum
                dibagikan</span>
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
            <a href="{{ url('/getfile/' . $g['fullpath']) }}" class="btn btn-default btn-flat">
                @if(!isset($icons[$ext]))<i class="fa fa-file-o"></i>
                @else <i class="fa {{ $icons[$ext] }}"></i>
                @endif {{ $g['filename'] }}
            </a>
        </p>
        @endforeach
        @else
        <p class="text-muted">Tidak ada dokumen</p>
        @endif

        <h4>Tanggal & Waktu Selesai</h4>
        <p>
            @if($kegiatan -> batas_waktu != '')
            {{ formatTanggalWaktu($kegiatan -> batas_waktu) }}
            <span class="pull-right" id="cd"><i class="fa fa-spinner fa-spin"></i></span>
            @else
            -
            @endif
        </p>

        <h4>Tampilkan laman diskusi?</h4>
        <p>
            @if($kegiatan -> diskusi == 'y')
            <span class="label label-info label-flat"><i class="fa fa-check"></i> Ditampilkan</span>
            @else
            <span class="label label-default label-flat"><i class="fa fa-exclamation-triangle"></i> Tidak
                Ditampilkan</span>
            @endif
        </p>
    </div>
</div>

<div class="f-box">
    <div class="f-box-body">
        <h4>Tugas</h4>
        @if(isset($kegiatan -> isi['tugas']))
        @php
        $c = 1;
        $abc = range('A', 'E');
        @endphp
        <table width="100%" id="tbl-soal" class="table">
            <tbody>
                @foreach($kegiatan -> isi['tugas'] as $s => $isi)
                <tr class="tr-soal">
                    <td width="30px">{{ $c }}.</td>
                    <td width="30px">
                        @switch($isi['jenis'])
                        @case(0)
                        <i class="fa fa-align-left" title="Isian" data-toggle="tooltip" data-placement="top"></i>
                        @break

                        @case(1)
                        <i class="fa fa-check-square-o" data-toggle="tooltip" data-placement="top"
                            title="Pilihan Ganda"></i>
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

                @elseif($isi['jenis'] == 2)
                <tr>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td id="td-{{ $s }}">
                        <div class="clearfix">
                            @switch($isi['file'])
                            @case('gbr')
                            <button class="btn btn-flat btn-default" title="Upload Gambar" data-toggle="tooltip"
                                data-placement="top" disabled><i class="fa fa-file-photo-o"></i> Gambar</button>
                            @break

                            @case('dok')
                            <button class="btn btn-flat btn-default" title="Upload Dokumen" data-toggle="tooltip"
                                data-placement="top" disabled><i class="fa fa-file-o"></i> Dokumen</button>
                            @break

                            @case('vid')
                            <button class="btn btn-flat btn-default" title="Upload Video" data-toggle="tooltip"
                                data-placement="top" disabled><i class="fa fa-file-movie-o"></i> Video</button>
                            @break
                            @endswitch
                        </div>
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

<div class="f-box">
    <div class="f-box-body">
        <h4><i class="fa fa-area-chart"></i> Laporan Tugas</h4>
        <table class="table">
            <tbody>
                @php
                $c=1;
                @endphp
                @foreach ($hasil as $h)
                @php
                $pending = 0;
                foreach ($h -> jawaban as $key => $value) {
                if($value['benar'] == '-') $pending++;
                }
                @endphp
                <tr>
                    <td><span class="label label-warning">#{{ $c }}</span></td>
                    <td class="media">
                        <div class="media-left">
                            <img src="{{ url('/getimage/' . $h -> mahasiswa -> foto) }}"
                                alt="{{ $h -> mahasiswa -> nama }}" class="online" height="64px">
                        </div>
                        <div class="media-body">
                            <strong>{{ $h -> mahasiswa -> nama }}</strong><br />
                            {{ $h -> mahasiswa -> NIM }}
                        </div>
                    </td>
                    <td>
                        <span class="text-muted">Nilai</span>
                        <h4>{{ $h -> total_nilai }}</h4>
                    </td>
                    <td>
                        <span class="text-muted">Benar</span>
                        <h4 class="text-success">{{ $h -> total_benar }}</h4>
                    </td>
                    <td>
                        <span class="text-muted">Salah</span>
                        <h4 class="text-danger">{{ count($h -> jawaban) - $h -> total_benar }}</h4>
                    </td>
                    <td>
                        <span class="text-muted">Peluang Curang</span>
                        <h4 class="text-danger">{{ floor($h -> total_leaving / 3000) }}</h4>
                    </td>
                    <td>
                        {{-- @if($pending > 0) --}}
                        <a href="{{ route('kegiatan.hasil.nilai.form', [$kegiatan ->id, $h -> mahasiswa -> id]) }}"
                            class="btn btn-success btn-flat btn-xs" title="Penilaian" data-toggle="tooltip"
                            data-placement="top"><i class="fa fa-edit"></i></a>
                        {{-- @endif --}}
                    </td>
                    <td>

                    </td>
                </tr>
                @php
                $c++;
                @endphp
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<div class="f-box" style="border-left: 4px solid #ffdd57;">
    <div class="f-box-body">
        <h4><i class="fa fa-sticky-note-o"></i> Catatan</h4>
        <p>{!! $kegiatan -> catatan !!}</p>
    </div>
</div>

@if($kegiatan -> diskusi == 'y')
@include('komentar.form')
@endif

@endsection

@include('matkul.tapel.sesi.kegiatan.partials._show')
