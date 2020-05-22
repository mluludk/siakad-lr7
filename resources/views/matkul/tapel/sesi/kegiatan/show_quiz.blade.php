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
            @if($kegiatan -> jenis != 4 or $kegiatan -> dibagikan == 'n')
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

        <h4>Tanggal & Waktu Selesai</h4>
        <p>
            @if($kegiatan -> batas_waktu != '')
            {{ formatTanggalWaktu($kegiatan -> batas_waktu) }}
            <span class="pull-right" id="cd"><i class="fa fa-spinner fa-spin"></i></span>
            @else
            -
            @endif
        </p>

        @if(in_array($user -> role_id, $allowed))
        <h4>Tampilkan laporan kepada peserta setelah quiz selesai?</h4>
        <p>
            @if($kegiatan -> laporan == 'y')
            <span class="label label-info label-flat"><i class="fa fa-check"></i> Ditampilkan</span>
            @else
            <span class="label label-default label-flat"><i class="fa fa-exclamation-triangle"></i> Tidak
                Ditampilkan</span>
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
        @endif

    </div>
</div>

<div class="f-box">
    <div class="f-box-body">
        <h4>Pertanyaan Quiz</h4>
        @if(isset($kegiatan -> isi))
        @php
        $c = 1;
        $abc = range('A', 'E');
        $buka = true;
        if ($kegiatan->batas_waktu != '') {
        $buka = strtotime($kegiatan->batas_waktu) >= time() ? true : false;
        }

        if($hasil !== null) $buka = false; //Mahasiswa sudah mengerjakan
        @endphp
        @if($user -> role_id == 512)

        @if($buka)
        {!! Form::open(['url' => '/kegiatan/' . $kegiatan -> id . '/kirim', 'method' => 'post', 'id' => 'frm-quiz']) !!}
        @endif

        <table width="100%" id="tbl-soal" class="table">
            <tbody>

                @foreach($kegiatan -> isi as $s => $isi)
                <tr class="tr-soal">
                    <td width="30px">{{ $c }}.</td>
                    <td>{!! $isi['soal'] !!}</td>
                </tr>
                <tr>
                    <td>&nbsp;</td>
                    <td>

                        @foreach($isi['pilihan'] as $k => $p)
                        <div>
                            <label>
                                @if($buka)<input type="radio" name="j-{{ $s }}" value="{{ $k }}" />@endif
                                {{ $abc[$k] }}. {{ $p }}
                            </label>
                        </div>
                        @endforeach

                    </td>
                </tr>
                <?php $c++; ?>
                @endforeach

            </tbody>
        </table>

        @if ($buka)
        {!! Form::hidden('mahasiswa_id', $user -> authable -> id) !!}
        {!! Form::hidden('start', 0) !!}
        {!! Form::hidden('leaving', 0) !!}
        <div class="form-group pull-right">
            <button type="button" class="btn btn-primary btn-flat" id="btn-submit-hasil"><i class="fa fa-send"></i>
                Kirim</button>
        </div>
        <div class="clearfix"></div>
        {!! Form::close() !!}
        @endif

        @else

        <table width="100%" id="tbl-soal" class="table">
            <tbody>
                <?php $c = 1; ?>
                @foreach($kegiatan -> isi as $isi)
                <tr class="tr-soal">
                    <td width="30px" valign="top">{{ $c }}.</td>
                    <td>{!! $isi['soal'] !!}</td>
                    <td width="30px" class="text-info" valign="top"><strong>{{ $isi['bobot'] }}</strong></td>
                </tr>
                <tr>
                    <td>&nbsp;</td>
                    <td>
                        @foreach($isi['pilihan'] as $k => $p)
                        <div>{{ $abc[$k] }}. {{ $p }}</div>
                        @endforeach
                    </td>
                </tr>
                <?php $c++; ?>
                @endforeach
            </tbody>
        </table>
        @endif
        @else
        <p>Belum ada pertanyaan</p>
        @endif
    </div>
</div>

@if(in_array($user -> role_id, $allowed) or ($kegiatan -> laporan == 'y'))
<div class="f-box">
    <div class="f-box-body">
        <h4><i class="fa fa-area-chart"></i> Laporan Quiz</h4>
        @if(!$admin)
        @if($hasil)
        @php
        $seconds = $hasil -> total_waktu;
        $H = floor($seconds / 3600);
        $i = ($seconds / 60) % 60;
        $s = $seconds % 60;
        @endphp
        <div class="pull-left half">
            <div>
                <span class="lbl half">Jumlah Pertanyaan:</span>
                <span class="half">{{ count($kegiatan -> isi) }}</span>
            </div>
            <div>
                <span class="lbl half">Pertanyaan dijawab:</span>
                <span class="half">{{ count($hasil -> jawaban) }}</span>
            </div>
            <div>
                <span class="lbl half">Jawaban benar:</span>
                <span class="half">{{ $hasil -> total_benar }}</span>
            </div>
            <div>
                <span class="lbl half">Jawaban salah:</span>
                <span class="half">{{ count($hasil -> jawaban) - $hasil -> total_benar }}</span>
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
        @else
        <table class="table">
            <tbody>
                @php
                $c=1;
                @endphp
                @foreach ($hasil as $h)
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
                </tr>
                @php
                $c++;
                @endphp
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

@if($kegiatan -> diskusi == 'y')
@include('komentar.form', $kegiatan)
@endif

@endsection

@include('matkul.tapel.sesi.kegiatan.partials._show', [$kegiatan, $hasil])

@push('styles')
<style>
    .media img {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        border: 2px solid #00a65a;
    }

    td>h4 {
        margin: 0px;
    }

    /* .nilai {
        padding: 10px 0;
        border: 1px solid black;
        border-radius: 3px;
        font-weight: bold;
        font-size: 50px;
        line-height: 52px;
        text-align: center;
        width: 90px;
        height: 80px;
    } */

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

@if(!in_array($user -> role_id, $allowed) && $hasil == null)
@push('scripts')
<script>
    $('#btn-submit-hasil').click(function(){
        var empty = false;
        $('#frm-quiz tr').removeClass('danger');
        $('#frm-quiz input[type="radio"]').each(function(){
            if ($("input[name='"+ $(this).attr('name') +"']:checked").val() == '' || $("input[name='"+ $(this).attr('name') +"']:checked").val() == undefined) {
                $(this).closest('tr').addClass('danger');
                empty = true;
            }
        });

        if(empty) {
            toastr.warning('Semua soal harus dijawab.', 'Peringatan');
            return false;
        }

        $('#frm-quiz').submit();
    });

    var t_leaving;
    $(window).on('focus', function () {
        var old = parseInt($('input[name=leaving]').val());
        if(t_leaving != undefined) $('input[name=leaving]').val(old + parseInt((+new Date) - t_leaving));
    });

    $(window).on('blur', function () {
        t_leaving = +new Date;
    });

    $('input[type=radio]').on('click', function(){
       if($('input[name=start]').val() == 0)  $('input[name=start]').val(Math.floor(Date.now() / 1000));
    });
</script>
@endpush
@endif
