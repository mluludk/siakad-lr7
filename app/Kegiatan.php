<?php

namespace Siakad;

use Illuminate\Database\Eloquent\Model;

class Kegiatan extends Model
{
    protected $table = 'kegiatan_pembelajaran';
    protected $guarded = [];

    protected $casts = [
        'isi' => 'array'
    ];

    public function sesi()
    {
        return $this->belongsTo(SesiPembelajaran::class, 'sesi_pembelajaran_id');
    }

    public function komentar()
    {
        return $this->morphMany(Komentar::class, 'commentable')->orderBy('waktu');
    }
}
