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

        <div class="clearfix"></div>
        <h4>Topik</h4>
        <p>{{ $kegiatan -> topik }}</p>

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
    </div>
</div>

<div class="f-box">
    <div class="f-box-body">
        <h4>Tugas</h4>
        @if(isset($kegiatan -> isi['tugas']))
        @php
        $c = 1;

        $abc = range('A', 'E');
        $buka = true;
        if ($kegiatan->batas_waktu != '') {
        $buka = strtotime($kegiatan->batas_waktu) >= time() ? true : false;
        }

        if($hasil !== null) $buka = false; //Mahasiswa sudah mengerjakan
        $total_nilai = 0;
        @endphp

        @if($buka)
        {!!
        Form::open(['url' => '/kegiatan/' . $kegiatan -> id . '/kirim', 'method' => 'post', 'id' => 'frm-tugas'])
        !!}
        @endif

        <table width="100%" id="tbl-soal" class="table">
            <tbody>
                @foreach($kegiatan -> isi['tugas'] as $s => $isi)
                @php
               if($hasil != null) $total_nilai += intval($hasil -> jawaban[$s]['nilai']);
                @endphp
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
                    <td @if($hasil !=null) colspan="2" @endif>{!! $isi['soal'] !!}</td>
                </tr>

                @if($isi['jenis'] == 0)
                <tr>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>
                        @if($hasil !== null)
                        {{ $hasil -> jawaban[$s]['jawaban'] }}
                        @else
                        <textarea name="j-{{ $s }}" style="width: 100%;" rows="5"></textarea>
                        @endif
                    </td>
                    @if($hasil !== null)
                    <td class="nilai">
                        {{ $hasil -> jawaban[$s]['nilai'] ?? '-'}}
                    </td>
                    @endif
                </tr>

                @elseif($isi['jenis'] == 1)
                <tr>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>
                        @if($hasil == null )
                        @foreach($isi['pilihan'] as $k => $p)
                        <div>
                            <label>
                                <input type="radio" name="j-{{ $s }}" value="{{ $k }}" />
                                {{ $abc[$k] }}. {{ $p }}
                            </label>
                        </div>
                        @endforeach
                        @else
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
                        @endif
                    </td>
                    @if($hasil !== null && !$admin)
                    <td class="nilai">
                        {{ $hasil -> jawaban[$s]['nilai'] ?? '-'}}
                    </td>
                    @endif
                </tr>

                @elseif($isi['jenis'] == 2)
                <tr>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td id="td-{{ $s }}">
                        <div class="clearfix">
                            @switch($isi['file'])
                            @case('gbr')
                            @if($hasil == null )
                            <button class="btn btn-flat btn-default fm-tugas btn-gambar" type="button"
                                data-toggle="modal" href="#myModal" data-href="{{ route('file.manager', 'gambar') }}">
                                <i class="fa fa-file-photo-o"></i> Gambar
                            </button>
                            @else
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
                            @endif
                            @break

                            @case('dok')
                            @if($hasil == null )
                            <button class="btn btn-flat btn-default fm-tugas btn-dokumen" type="button"
                                data-toggle="modal" href="#myModal" data-href="{{ route('file.manager', 'dokumen') }}">
                                <i class="fa fa-file-o"></i> Dokumen
                            </button>
                            @else
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
                            @endif
                            @break

                            @case('vid')
                            @if($hasil == null )
                            <button class="btn btn-default btn-flat fm-tugas btn-video" type="button"
                                data-toggle="modal" href="#myModal" data-href="{{ route('file.manager', 'video') }}">
                                <i class="fa fa-file-movie-o"></i> Video
                            </button>
                            @else
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
                            @endif
                            @break
                            @endswitch
                        </div>
                    </td>
                    @if($hasil !== null)
                    <td class="nilai">
                        {{ $hasil -> jawaban[$s]['nilai'] ?? '-'}}
                    </td>
                    @endif
                </tr>
                @endif

                <?php $c++; ?>
                @endforeach
                @if($hasil !== null)
                <tr>
                    <td colspan="3" class="nilai">Total Nilai</td>
                    <td class="nilai">{{ $total_nilai }}</td>
                </tr>
                @endif
            </tbody>
        </table>
        @endif

        @if ($buka)
        {!! Form::hidden('mahasiswa_id', $user -> authable -> id) !!}
        {!! Form::hidden('start', time()) !!}
        {!! Form::hidden('leaving', 0) !!}
        <div class="form-group pull-right">
            <button type="button" class="btn btn-primary btn-flat" id="btn-submit-hasil"><i class="fa fa-send"></i>
                Kirim</button>
        </div>
        <div class="clearfix"></div>
        {!! Form::close() !!}
        @endif

    </div>
</div>

<div class="f-box">
    <div class="f-box-body">
        <h4><i class="fa fa-area-chart"></i> Laporan Tugas</h4>
        @if($hasil)
        @php
        $seconds = $hasil -> total_waktu;
        $H = floor($seconds / 3600);
        $i = ($seconds / 60) % 60;
        $s = $seconds % 60;

        $benar = $salah = $pending = 0;
        foreach ($hasil -> jawaban as $key => $value) {
            if($value['benar'] == 'y') $benar++;
            if($value['benar'] == 'n') $salah++;
            if($value['benar'] == '-') $pending++;
        }
        @endphp
        <div class="pull-left half">
            <div>
                <span class="lbl half">Jumlah Pertanyaan:</span>
                <span class="half">{{ count($kegiatan -> isi['tugas']) }}</span>
            </div>
            <div>
                <span class="lbl half">Pertanyaan dijawab:</span>
                <span class="half">{{ count($hasil -> jawaban) }}</span>
            </div>
            <div>
                <span class="lbl half">Jawaban benar:</span>
                <span class="half">@if(count($kegiatan -> isi['tugas']) == $pending) - @else {{ $benar }} @endif</span>
            </div>
            <div>
                <span class="lbl half">Jawaban salah:</span>
                <span class="half">@if(count($kegiatan -> isi['tugas']) == $pending) - @else {{ $salah }} @endif</span>
            </div>
            <div>
                <span class="lbl half">Total Waktu:</span>
                <span class="half">{{ sprintf("%02d:%02d:%02d", $H, $i, $s) }}</span>
            </div>
        </div>
        <div class="pull-left">
            <strong>Nilai Anda</strong>
            <h1 style="margin-top:5px;"><span class="label label-info">{{ $hasil -> total_nilai }}</span></h1>
        </div>
        <div class="clearfix"></div>
        @endif
    </div>
</div>

<div class="f-box" style="border-left: 4px solid #ffdd57;">
    <div class="f-box-body">
        <h4><i class="fa fa-sticky-note-o"></i> Catatan</h4>
        <p>{!! $kegiatan -> catatan !!}</p>
    </div>
</div>

<div id="fm-tugas"></div>
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

@if($kegiatan -> diskusi == 'y')
@include('komentar.form', $kegiatan)
@endif

@endsection

@include('matkul.tapel.sesi.kegiatan.partials._show', $kegiatan)

@if($hasil == null)
@push('scripts')
<script>
    $('.fm-tugas').click(function(e) {
    var url = $(this).attr('data-href');
    var target = $(this).closest('td').attr('id');
    $('#fm-tugas').load(url, function(result){
        $('#myModal').modal({show:true});
        $('#myModal').attr('data-type', 'tugas');
        $('#myModal').attr('data-target', target);
    });
    });
    $(document).on('hidden.bs.modal', '#myModal',function (e) {
		$('#myModal').remove();
	});

    $('#btn-submit-hasil').click(function(){
        var empty = false;
        $('#fm-tugas tr').removeClass('danger');

        @foreach($kegiatan -> isi['tugas'] as $s => $isi)

        @if($isi['jenis'] == 0)
        if($('textarea[name=j-{{ $s }}]').val() == '' || $('textarea[name=j-{{ $s }}]').val() == undefined) empty=true;
        @elseif($isi['jenis'] == 1)
        if($('input[name=j-{{ $s }}]:checked').val() == '' || $('input[name=j-{{ $s }}]:checked').val() == undefined) empty=true;
        @elseif($isi['jenis'] == 2)
        if($('input[name^=j-{{ $s }}').val() == '' || $('input[name^=j-{{ $s }}').val() == undefined) empty=true;
        @endif

        @endforeach

        if(empty) {
            toastr.warning('Semua soal harus dijawab.', 'Peringatan');
            return false;
        }

        $('#frm-tugas').submit();
    });

    var t_leaving;
    $(window).on('focus', function () {
        var old = parseInt($('input[name=leaving]').val());
        if(t_leaving != undefined) $('input[name=leaving]').val(old + parseInt((+new Date) - t_leaving));
    });

    $(window).on('blur', function () {
        t_leaving = +new Date;
    });
</script>
@endpush
@endif
