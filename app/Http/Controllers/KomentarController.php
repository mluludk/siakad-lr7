<?php

namespace Siakad\Http\Controllers;

use Illuminate\Http\Request;
use Siakad\Komentar;

use Siakad\Http\Controllers\Controller;

class KomentarController extends Controller
{

    public function store(Request $request, $model, $id)
    {
        $auth = \Auth::user();
        $waktu = date('Y-m-d H:i:s');
        $komentar = $request->get('komentar');
        $attachment = json_decode($request->get('attachment'), true);

        // extract attachment
        $img = getContents($komentar, '[i]', '[/i]');
        if (count($img)) {
            foreach ($img as $value) {
                $komentar = str_ireplace(
                    '[i]' . $value . '[/i]',
                    '<a target="_blank" href="' . url('/getfile' . $attachment[$value]['f']) . '" class="att"><img src="' . url('/getfile' . getThumbnail($attachment[$value]['f'])) . '"></a>',
                    $komentar
                );
            }
        }
        $doc = getContents($komentar, '[d]', '[/d]');
        if (count($doc)) {
            foreach ($doc as $value) {
                $komentar = str_ireplace(
                    '[d]' . $value . '[/d]',
                    '<a target="_blank" href="' . url('/getfile' . $attachment[$value]['f']) . '" class="att"><i class="fa fa-file-text-o"></i> ' . substr($attachment[$value]['f'], 12) . '</a>',
                    $komentar
                );
            }
        }
        $vid = getContents($komentar, '[v]', '[/v]');
        if (count($vid)) {
            foreach ($vid as $value) {
                $komentar = str_ireplace(
                    '[v]' . $value . '[/v]',
                    '<video controls class="att"><source src="' . url('/getfile' . $attachment[$value]['f']) . '">Your browser does not support the video tag.</video>',
                    $komentar
                );
            }
        }

        $input = [
            'commentable_type' => 'Siakad\\' . $model,
            'commentable_id' => $id,
            'komentar' => $komentar,
            'waktu' => $waktu,
            'user_id' => $auth->id
        ];

        if(null !== $request->get('reply_id'))
        {
            $input['reply_id'] =$request->get('reply_id');
        }

        $submit = Komentar::create($input);
        if ($submit) {
            $komentar = $this->getKomentar($model, $id, $request->get('last_id'));

            return [
                'success' => true,
                'items' => $komentar
            ];
        }

        return ['success' => false, 'error' => 'Gagal menyimpan Komentar.'];
    }

    public function destroy(Komentar $komentar)
    {
        if ($komentar->author->role_id > 2) {
            if ($komentar->author->id != \Auth::user()->id) {
                return ['success' => false, 'message' => 'Anda tidak berhak menghapus Komentar ini.'];
            }
        }
        if ($komentar->delete())
            return ['success' => true, 'message' => 'Komentar telah dihapus.'];
        else
            return ['success' => false, 'message' => 'Gagal menghapus Komentar.'];
    }

    public function getKomentar($model, $id, $last_id = 0)
    {
        $auth_id = \Auth::user()->id;
        $komentar = [];
        $url = url('/');
        $foto = $url . '/images/logo.png';

        //Get "new" chat
        $new = Komentar::with('author')
            ->where('commentable_type', 'Siakad\\' . $model)
            ->where('commentable_id', $id)
            ->where('id', '>', $last_id)
            ->orderBy('waktu')
            ->get();

        foreach ($new as $n) {
            $reply = '';
            if ($n->author->authable->foto != '' and file_exists(storage_path('app/upload/images/') . $n->author->authable->foto)) {
                $foto = $url . '/getimage/' . $n->author->authable->foto;
            }

            //Reply
            if ($n->reply_id > 0) {
                $r = Komentar::with('author')->find($n->reply_id);

                $pat = [
                    '/<a[^>]+?><img[^>]+?><\/a>/',
                    '/<a[^>]+?><i [^>]+?><\/i>([^$]+?)<\/a>/',
                    '/<video[^>]+?>([^$]+?)<\/video>/'
                ];
                $rep = ['[Gambar]', '[Dokumen]', '[Video]'];

                if ($r) {
                    $reply = '<a href="#komentar-' . $r->id . '" class="reply">
                    <strong>' . $r->author->authable->nama . '</strong><br/>
                      ' . preg_replace($pat, $rep, $r->komentar) . '
                  </a>';
                }
            }
            $komentar[] = [
                'id' => $n->id,
                'auth_id' => $auth_id,
                'image' => $foto,
                'user' => $n->author->authable->nama,
                'user_id' => $n->author->id,
                'status' => strtotime($n->author->last_login) >= strtotime('5 minutes ago') ? 'online' : 'offline',
                'waktu' => $n->waktu,
                'komentar' => $n->komentar,
                'reply' => $reply
            ];
        }

        return $komentar;
    }
}
