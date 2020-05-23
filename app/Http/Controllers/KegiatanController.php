<?php

namespace Siakad\Http\Controllers;

use Redirect;
use Illuminate\Http\Request;

use Siakad\MatkulTapel;
use Siakad\SesiPembelajaran;
use Siakad\Kegiatan;
use Siakad\FileEntry;
use Siakad\HasilKegiatan;
use Siakad\Http\Controllers\Controller;

class KegiatanController extends Controller
{
    use \Siakad\ZoomTrait;
    protected $rules = [
        'topik' => 'required',
    ];
    protected $jenis = [1 => 'Materi', 2 => 'Quiz', 3 => 'Tugas', 4 => 'Video Conference'];
    protected $icons = [
        'pdf' => 'fa-file-pdf-o', 'docx' => 'fa-file-word-o', 'doc' => 'fa-file-word-o',
        'xls' => 'fa-file-excel-o', 'xlsx' => 'fa-file-excel-o', 'pptx' => 'fa-file-powerpoint-o',
        'ppt' => 'fa-file-powerpoint-o', 'mp4' => 'fa-file-video-o', 'ogg' => 'fa-file-video-o'
    ];
    protected $media_type = ['gambar', 'dokumen', 'video'];

    protected $allowed = [1, 2, 8, 128];

    public function index(MatkulTapel $kelas, SesiPembelajaran $sesi)
    {
        $this->checkDosen($kelas, true);
        $kegiatan = Kegiatan::where('sesi_pembelajaran_id', $sesi->id);

        $kegiatan->when(\Auth::user()->role_id == 512, function ($q) {
            return $q->where('dibagikan', 'y');
        });

        $kegiatan = $kegiatan
            ->orderBy('urutan')
            ->get();

        $jenis = $this->jenis;
        $user = \Auth::user();
        $allowed = $this->allowed;
        return view('matkul.tapel.sesi.kegiatan.index', compact('kelas', 'sesi', 'kegiatan', 'jenis', 'user', 'allowed'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(MatkulTapel $kelas, SesiPembelajaran $sesi, $jenis_id)
    {
        $this->checkDosen($kelas);
        $jenis = $this->jenis[$jenis_id];
        return view('matkul.tapel.sesi.kegiatan.create',  compact('kelas', 'sesi', 'jenis', 'jenis_id'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, MatkulTapel $kelas, SesiPembelajaran $sesi, $jenis_id)
    {
        $this->validate($request, $this->rules);
        $input = $request->except('_token', 'files');

        if ($jenis_id == 2) // QUIZ
        {
            foreach ($input['soal'] as $k => $v) {
                $jwb = explode(';', rtrim($input['pilihan'][$k], ';'));
                $input['isi'][$k] = [
                    'soal' => $v,
                    'bobot' => $input['bobot'][$k],
                    'pilihan' => $jwb,
                    'benar' => $input['benar'][$k]
                ];
            }
        }

        if ($jenis_id == 3) // TUGAS
        {
            $c = 0;
            foreach ($input['soal'] as $k => $v) {
                $input['isi']['tugas'][$c] = [
                    'jenis' => $input['jenis_soal'][$k],
                    'soal' => $v,
                    'pilihan' => $input['pilihan'][$k] ?? null,
                    'file' => $input['file'][$k] ?? null,
                ];

                $c++;
            }
        }

        if ($jenis_id == 4) // Conference
        {
            if ($input['dibagikan'] !== 'n') {
                $create = [
                    'topic' => $input['topik'],
                    'password' => $input['password'] ?? ''
                ];

                if ($input['dibagikan'] == 'y') {
                    $create['type'] = 2;
                    $create['start_time'] = date('Y-m-d') . 'T' . date('H:i:s');
                }
                if ($input['dibagikan'] == 'j') {
                    $days = [1 => 'monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday'];
                    $jadwal = $kelas->jadwal[0];
                    $next = strtotime('next ' . $days[$jadwal->hari]);
                    $create['type'] = 2;
                    $create['start_time'] = date('Y-m-d', $next) . 'T' . date($jadwal->jam_mulai . ':00');
                }

                $meeting = $this->createMeeting($create);

                if (!$meeting['success']) return Redirect::route('matkul.tapel.sesi.kegiatan.index', [$kelas->id, $sesi->id])
                    ->withErrors(['ZOOM_ERROR' => 'Tidak dapat mendaftarkan Sesi Video Conference. Hubungi Administrator.']);

                unset($meeting['success']);
                $input['isi'] = $meeting;
            }
        }

        if (isset($input['batas1'])) {
            $input['batas_waktu'] = $input['batas1'];

            if (isset($input['batas2'])) $input['batas_waktu'] .= ' ' . $input['batas2'] . ':00';
            else $input['batas_waktu'] .= ' 00:00:00';
        }
        $input = $this->discardVariables($input);

        $input['jenis'] = $jenis_id;
        $input['sesi_pembelajaran_id'] = $sesi->id;

        $created = Kegiatan::create($input);

        return Redirect::route('matkul.tapel.sesi.kegiatan.show', [$kelas->id, $sesi->id, $created->id])
            ->with('success', 'Data ' . $this->jenis[$jenis_id] . ' berhasil disimpan.');
    }

    public function startMeeting(Kegiatan $meeting)
    {
        $this->checkDosen($kelas);
        if (isset($meeting->isi['started'])) return Redirect::back()->with('warning', 'Conference sudah dimulai');

        $isi = $meeting->isi;
        $isi['started'] = true;
        $meeting->update(['isi' => $isi]);
        return Redirect::to($isi['start_url']);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(MatkulTapel $kelas, SesiPembelajaran $sesi, Kegiatan $kegiatan)
    {
        $this->checkDosen($kelas);
        $jenis_id = $kegiatan->jenis;
        $jenis = $this->jenis[$jenis_id];
        $media = [];
        $icons = $this->icons;
        $media_type = $this->media_type;

        foreach ($media_type as $t) {
            if (isset($kegiatan->isi[$t])) {
                foreach ($kegiatan->isi[$t] as $g) {
                    $file = FileEntry::find($g);
                    if ($file) $media[$t][] = [
                        'id' => $file->id,
                        'fullpath' => $file->namafile,
                        'filename' => $file->nama,
                        'mime' => $file->mime
                    ];
                }
            }
        }

        $hasil = HasilKegiatan::with('mahasiswa')
            ->where('kegiatan_pembelajaran_id', $kegiatan->id)
            ->exists();

        return view('matkul.tapel.sesi.kegiatan.edit', compact('sesi', 'kelas', 'kegiatan', 'jenis', 'jenis_id', 'icons', 'media', 'hasil'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, MatkulTapel $kelas, SesiPembelajaran $sesi, Kegiatan $kegiatan)
    {
        $this->validate($request, $this->rules);
        $input = $request->except('_method', 'files');

        if ($kegiatan->jenis == 2) {
            foreach ($input['soal'] as $k => $v) {
                $jwb = explode(';', rtrim($input['pilihan'][$k], ';'));
                $input['isi'][$k] = [
                    'soal' => $v,
                    'bobot' => $input['bobot'][$k],
                    'pilihan' => $jwb,
                    'benar' => $input['benar'][$k]
                ];
            }
        }

        if ($kegiatan->jenis == 3) // TUGAS
        {
            $c = 0;
            foreach ($input['soal'] as $k => $v) {
                $input['isi']['tugas'][$c] = [
                    'jenis' => $input['jenis_soal'][$k],
                    'soal' => $v,
                    'pilihan' => $input['pilihan'][$k] ?? null,
                    'file' => $input['file'][$k] ?? null,
                ];

                $c++;
            }
        }

        if (isset($input['batas1'])) {
            $input['batas_waktu'] = $input['batas1'];

            if (isset($input['batas2'])) $input['batas_waktu'] .= ' ' . $input['batas2'] . ':00';
            else $input['batas_waktu'] .= ' 00:00:00';
        }
        $input = $this->discardVariables($input);


        $kegiatan->update($input);
        return Redirect::route('matkul.tapel.sesi.kegiatan.index', [$kelas->id, $sesi->id])->with('success', 'Data  ' . $this->jenis[$kegiatan->jenis] . '  berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(MatkulTapel $kelas, SesiPembelajaran $sesi, Kegiatan $kegiatan)
    {
        $this->checkDosen($kelas);
        $kegiatan->delete();
        return Redirect::route('matkul.tapel.sesi.kegiatan.index', [$kelas->id, $sesi->id])->with('success', 'Data  ' . $this->jenis[$kegiatan->jenis] . '  berhasil dihapus.');
    }

    public function duplicate(MatkulTapel $kelas, SesiPembelajaran $sesi, Kegiatan $kegiatan)
    {
        $this->checkDosen($kelas);
        $input = $kegiatan->toArray();
        unset($input['id']);
        $input['urutan'] = $this->getUrutanKegiatan($sesi->id);
        $input['created_at'] = date('Y-m-d H:i:s');
        $input['updated_at'] = date('Y-m-d H:i:s');

        Kegiatan::create($input);

        return Redirect::route('matkul.tapel.sesi.kegiatan.index', [$kelas->id, $sesi->id])->with('success', 'Data Materi berhasil digandakan.');
    }

    public function show(MatkulTapel $kelas, SesiPembelajaran $sesi, Kegiatan $kegiatan)
    {
        $this->checkDosen($kelas, true);
        $media = [];
        $icons = $this->icons;
        $jenis = $this->jenis;

        if ($kegiatan->jenis == 1 or $kegiatan->jenis == 3) {
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
        }

        $user = \Auth::user();
        $allowed = $this->allowed;


        if ($kegiatan->jenis == 1)
            return view('matkul.tapel.sesi.kegiatan.show_materi', compact('sesi', 'kelas', 'kegiatan', 'media', 'icons', 'jenis', 'user', 'allowed'));

        if ($kegiatan->jenis == 2 or $kegiatan->jenis == 3) {
            $hasil = null;
            $admin = $stop = false;
            $media_jawaban = [];

            if ($user->role_id == 512) {
                $hasil = HasilKegiatan::where('kegiatan_pembelajaran_id', $kegiatan->id)
                    ->where('mahasiswa_id', $user->authable->id)
                    ->first();

                if ($hasil) {
                    $stop = true;
                    if ($kegiatan->jenis == 3) {
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
                    }
                }
            } else {
                $hasil = HasilKegiatan::with('mahasiswa')
                    ->where('kegiatan_pembelajaran_id', $kegiatan->id)
                    ->orderBy('total_nilai', 'desc')
                    ->orderBy('total_leaving', 'desc')
                    ->orderBy('total_waktu')
                    ->get();
                $admin = true;
            }
        }

        if ($kegiatan->jenis == 2) {
            return view('matkul.tapel.sesi.kegiatan.show_quiz', compact('sesi', 'kelas', 'kegiatan', 'media', 'icons', 'jenis', 'user', 'allowed', 'hasil', 'admin', 'stop'));
        } elseif ($kegiatan->jenis == 3) {
            if ($user->role_id == 512) return view('matkul.tapel.sesi.kegiatan.show_tugas_mahasiswa', compact('sesi', 'kelas', 'kegiatan', 'user', 'media', 'icons', 'jenis', 'hasil', 'admin', 'stop', 'media_jawaban'));

            return view('matkul.tapel.sesi.kegiatan.show_tugas', compact('sesi', 'kelas', 'kegiatan', 'media', 'icons', 'jenis', 'hasil', 'stop', 'media_jawaban'));
        }

        return view('matkul.tapel.sesi.kegiatan.show', compact('sesi', 'kelas', 'kegiatan', 'media', 'icons', 'jenis', 'user', 'allowed'));
    }

    private function checkDosen($kelas, $view = false) //Check Jika dosen tidak mengajar Kelas Kuliah
    {
        $authorized = false;
        $user = \Auth::user();

        if ($user->role_id <= 8) return true; //allow admin
        if (!$view && $user->role_id == 512) return abort(401);

        if ($user->role_id == 128) { //check if dosen
            $dosen_id = $user->authable->id;
            foreach ($kelas->tim_dosen as $d) {
                if ($d->id == $dosen_id) $authorized = true;
            }
            if (!$authorized) return abort(401);
        }
        return true;
    }
    private function createMeeting($data)
    {
        $create_data = [];

        $create_data['topic']      = $data['topic'];
        $create_data['agenda']     = $data['agenda'] ?? "";
        $create_data['type']       = $data['type'] ?? 1;

        $create_data['start_time']       = $data['start_time'] ?? '';
        $create_data['timezone']       = !empty($data['start_time']) ? 'Asia/Jakarta' : '';

        $create_data['password']   = $data['password'] ?? '';
        $create_data['duration']   = $data['duration'] ?? 40;
        $create_data['settings']   = [
            'host_video'        => true,
            'participant_video' => false,
            'join_before_host'  => true,
            'enforce_login'     => false,
            'waiting_room'     => true,
        ];

        $response = $this->sendRequest('/v2/users/me/meetings', $create_data);

        if (!$response)
            return ['success' => false, 'message' => 'Terjadi kesalahan'];

        return [
            'success' => true,
            'meeting_id' => $response->id,
            'host_id' => $response->host_id,
            'uuid' => $response->uuid,
            'topic' => $response->topic,
            'type' => $response->type,
            'password' => $response->password ?? '',
            'start_time' => $response->start_time ?? '',
            'start_url' => $response->start_url,
            'join_url' => $response->join_url
        ];
    }
    private function discardVariables($input)
    {
        $vars = ['soal', 'jenis_soal', 'bobot', 'pilihan', 'benar', 'batas1', 'batas2', 'file', 'password'];
        foreach ($vars as $var) unset($input[$var]);

        return $input;
    }
    private function getUrutanKegiatan($sesi_id)
    {
        $last = Kegiatan::where('sesi_pembelajaran_id', $sesi_id)->orderBy('urutan', 'desc')->get('urutan');

        if ($last) return $last[0]->urutan + 1;

        return 1;
    }
}
