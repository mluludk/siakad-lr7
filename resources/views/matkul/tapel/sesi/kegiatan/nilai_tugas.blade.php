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
            <a href="{{ route('matkul.tapel.sesi.kegiatan.show', [$kelas -> id, $sesi -> id, $kegiatan -> id])}}"
                class="btn btn-default btn-flat btn-xs"><i class="fa fa-arrow-left"></i> Kembali</a>
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
        <h4>Tugas {{ $mahasiswa -> nama }} ({{ $mahasiswa -> NIM }})</h4>
        @php
        $c = 1;
        $abc = range('A', 'E');
        $total_nilai = 0;
        @endphp

        {!!
        Form::open(['url' => 'kegiatan/'. $kegiatan -> id .'/nilai/'. $mahasiswa -> id, 'method' => 'post', 'id' =>
        'frm-nilai-tugas'])
        !!}

        <table width="100%" id="tbl-soal" class="table">
            <thead>
                <tr>
                    <th width="30px">No.</th>
                    <th width="30px">Jenis</th>
                    <th>Pertanyaan</th>
                    <th>Nilai</th>
                </tr>
            </thead>
            <tbody>
                @foreach($kegiatan -> isi['tugas'] as $s => $isi)
                @php
                    $total_nilai += isset($hasil -> jawaban[$s]['nilai']) ? (int) $hasil -> jawaban[$s]['nilai'] : 0;
                @endphp
                <tr class="tr-soal">
                    <td>{{ $c }}.</td>
                    <td>
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
                    <td colspan="2">{!! $isi['soal'] !!}</td>
                </tr>

                @if($isi['jenis'] == 0)
                <tr>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>
                        {{ $hasil -> jawaban[$s]['jawaban'] }}
                    </td>
                    <td class="nilai">
                        <input type="number" min="0" max="100" name="n-{{ $s }}" class="form-control inp-nilai" value="{{ $hasil -> jawaban[$s]['nilai'] ?? '' }}">
                    </td>
                </tr>

                @elseif($isi['jenis'] == 1)
                <tr>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>
                        @foreach($isi['pilihan'] as $k => $p)
                        <div>
                            <label>
                                @if($hasil -> jawaban[$s]['jawaban'] == $k)
                                <u>{{ $abc[$k] }}. {{ $p }}</u>
                                @else
                                {{ $abc[$k] }}. {{ $p }}
                                @endif
                            </label>
                        </div>
                        @endforeach
                    </td>
                    <td class="nilai">
                        <input type="number" min="0" max="100" name="n-{{ $s }}" class="form-control inp-nilai" value="{{ $hasil -> jawaban[$s]['nilai'] ?? '' }}">
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
                            @foreach ($media_jawaban[$s] as $mj)
                            <div class="thumbnail">
                                <img src="{{ getThumbnail(url('/getfile/' . $mj['fullpath'])) }}">
                                <div class="caption">
                                    <a href="{{ url('/getfile/' . $mj['fullpath']) }}" target="_blank">
                                        {{ $mj['filename'] }}
                                    </a>
                                </div>
                            </div>
                            @endforeach
                            @break

                            @case('dok')
                            @foreach ($media_jawaban[$s] as $mj)
                            <div class="thumbnail">
                                <i class="fa fa-file-o fa-4x"></i>
                                <div class="caption">
                                    <a href="{{ url('/getfile/' . $mj['fullpath']) }}" target="_blank">
                                        {{ $mj['filename'] }}
                                    </a>
                                </div>
                            </div>
                            @endforeach
                            @break

                            @case('vid')
                            @foreach ($media_jawaban[$s] as $mj)
                            <div class="thumbnail">
                                <video controls>
                                    <source src="{{ url('/getfile/' . $mj['fullpath']) }}" type="{{ $mj['mime'] }}">
                                    Your browser does not support the video tag.
                                </video>
                                <div class="caption">
                                    <a href="{{ url('/getfile/' . $mj['fullpath']) }}" target="_blank">
                                        {{ $mj['filename'] }}
                                    </a>
                                </div>
                            </div>
                            @endforeach
                            @break
                            @endswitch
                        </div>
                    </td>
                    <td class="nilai">
                        <input type="number" min="0" max="100" name="n-{{ $s }}" class="form-control inp-nilai" value="{{ $hasil -> jawaban[$s]['nilai'] ?? '' }}">
                    </td>
                </tr>
                @endif

                <?php $c++; ?>
                @endforeach
                <tr>
                    <td colspan="3" class="nilai">Total Nilai</td>
                    <td class="nilai"><span id="total_nilai">{{ $total_nilai }}</span></td>
                </tr>
            </tbody>
        </table>

        <div class="form-group pull-right">
            <button type="submmit" class="btn btn-primary btn-flat"><i class="fa fa-save"></i>
                Simpan Nilai</button>
        </div>
        <div class="clearfix"></div>
        {!! Form::close() !!}

    </div>
</div>
@endsection

@include('matkul.tapel.sesi.kegiatan.partials._show')

@push('scripts')
<script>
    $(document).on('keyup', '.inp-nilai', function(){
        var total = 0;
        $('.inp-nilai').each(function(){
            total += Number($(this).val());
        });
        $('#total_nilai').text(total);
    });
</script>
@endpush

@push('styles')
<style>
    .thumbnail {
        display: inline-block !important;
        margin-right: 3px;
        text-align: center;
    }

    .clearfix {
        margin-top: 5px;
    }

    td.nilai {
        text-align: right !important;
        font-weight: bold;
    }

    td.nilai>input {
        width: 65px;
    }

    .media img {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        border: 2px solid #00a65a;
    }

    td>h4 {
        margin: 0px;
    }

    .half {
        width: 50%;
    }

    span.half {
        display: inline-block;
        float: left;
    }

    span.half:not(.lbl) {
        padding-left: 4px;
        font-weight: bold;
    }

    .lbl {
        text-align: right;
    }
</style>
@endpush
