<?php

namespace Siakad\Http\Controllers;

use Redirect;
use Illuminate\Http\Request;
use Siakad\Kegiatan;
use Siakad\Mahasiswa;
use Siakad\HasilKegiatan;
use Siakad\FileEntry;

class HasilKegiatanController extends Controller
{
    protected $jenis = [1 => 'Materi', 2 => 'Quiz', 3 => 'Tugas', 4 => 'Video Conference'];
    protected $icons = [
        'pdf' => 'fa-file-pdf-o', 'docx' => 'fa-file-word-o', 'doc' => 'fa-file-word-o',
        'xls' => 'fa-file-excel-o', 'xlsx' => 'fa-file-excel-o', 'pptx' => 'fa-file-powerpoint-o',
        'ppt' => 'fa-file-powerpoint-o', 'mp4' => 'fa-file-video-o', 'ogg' => 'fa-file-video-o'
    ];
    protected $media_type = ['gambar', 'dokumen', 'video'];

    public function nilaiPost(Request $request, Kegiatan $kegiatan, Mahasiswa $mahasiswa)
    {
        $input = $request->all();

        $hasil = HasilKegiatan::where('kegiatan_pembelajaran_id', $kegiatan->id)
            ->where('mahasiswa_id', $mahasiswa->id)
            ->first();
        $total_nilai = $total_benar = 0;
        $jawaban = $hasil->jawaban;
        foreach ($jawaban as $k => $j) {
            if (isset($input['n-' . $k]) && intval($input['n-' . $k]) > 0) {
                $jawaban[$k]['nilai'] = $input['n-' . $k];
                $jawaban[$k]['benar'] = 'y';
                $total_nilai += (int) $input['n-' . $k];
                $total_benar++;
            }
            else
            {
                $jawaban[$k]['nilai'] = 0;
                $jawaban[$k]['benar'] = 'n';
            }
        }

        HasilKegiatan::where('kegiatan_pembelajaran_id', $kegiatan->id)
            ->where('mahasiswa_id', $mahasiswa->id)
            ->update(['jawaban' => $jawaban, 'total_nilai' => $total_nilai, 'total_benar' => $total_benar]);
        return Redirect::route('matkul.tapel.sesi.kegiatan.show', [$kegiatan->sesi->kelas->id, $kegiatan->sesi->id, $kegiatan->id])->with('success', 'Nilai telah disimpan');
    }
    public function nilai(Kegiatan $kegiatan, Mahasiswa $mahasiswa)
    {

        $hasil = HasilKegiatan::where('kegiatan_pembelajaran_id', $kegiatan->id)
            ->where('mahasiswa_id', $mahasiswa->id)
            ->first();

        if (!$hasil) abort(404);

        $media = $media_jawaban = [];
        $stop = true;

        $sesi = $kegiatan->sesi;
        $kelas = $sesi->kelas;

        foreach ($this->media_type as $t) {
            if (isset($kegiatan->isi[$t])) {
                foreach ($kegiatan->isi[$t] as $g) {
                    $file = FileEntry::find($g);
                    if ($file) $media[$t][] = [
                        'fullpath' => $file->namafile,
                        'filename' => $file->nama,
                        'mime' => $file->mime
                    ];
                }
            }
        }

        $icons = $this->icons;
        $jenis = $this->jenis;

        foreach ($hasil->jawaban as $k => $v) {
            if ($v['jenis'] == 2) { // only for jenis 2
                foreach ($v['jawaban'] as $id) {
                    $file = FileEntry::find($id);
                    if ($file) $media_jawaban[$k][] = [
                        'fullpath' => $file->namafile,
                        'filename' => $file->nama,
                        'mime' => $file->mime
                    ];
                }
            }
        }

        return view('matkul.tapel.sesi.kegiatan.nilai_tugas', compact('sesi', 'kelas', 'kegiatan', 'media', 'icons', 'jenis', 'hasil', 'media_jawaban', 'mahasiswa', 'stop'));
    }
    public function store(Request $request, Kegiatan $kegiatan)
    {
        $input = $request->all();

        $data = [
            'kegiatan_pembelajaran_id' => $kegiatan->id,
            'mahasiswa_id' => $input['mahasiswa_id'],
            'total_waktu' => time() - $input['start'],
            'total_leaving' => $input['leaving']
        ];
        if ($kegiatan->jenis == 2) {
            $data['total_nilai'] = $data['total_benar'] = 0;
            foreach ($kegiatan->isi as $key => $value) {
                $nilai = $input['j-' . $key] != $value['benar'] ? 0 : intval($value['bobot']);
                $data['jawaban'][] = [
                    'jawaban' => $input['j-' . $key],
                    'benar' => $value['benar'],
                    'nilai' => $nilai
                ];
                if ($nilai > 0) $data['total_benar']++;
                $data['total_nilai'] += $nilai;
            }
        }
        if ($kegiatan->jenis == 3) {
            $data['total_nilai'] = $data['total_benar'] = 0;
            foreach ($kegiatan->isi['tugas'] as $key => $value) {
                $data['jawaban'][] = [
                    'jawaban' => $input['j-' . $key],
                    'jenis' => $value['jenis'],
                    'benar' => '-', //Y|N|-
                    'nilai' => ''
                ];
            }
        }

        $data['jawaban'] = json_encode($data['jawaban']);
        HasilKegiatan::insertOrIgnore($data); // need manual json_encode() as oppose to create()

        return Redirect::route('matkul.tapel.sesi.kegiatan.show', [$kegiatan->sesi->kelas->id, $kegiatan->sesi->id, $kegiatan->id])
            ->with('message', 'Jawaban telah disimpan');
    }
}
