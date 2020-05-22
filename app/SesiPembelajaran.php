<?php

namespace Siakad;

use Illuminate\Database\Eloquent\Model;

class SesiPembelajaran extends Model
{
    protected $table = 'sesi_pembelajaran';
    protected $guarded = [];

    public function kegiatan()
    {
        return $this->hasMany(Kegiatan::class, 'sesi_pembelajaran_id');
    }
    public function kelas()
    {
        return $this->belongsTo(MatkulTapel::class, 'matkul_tapel_id');
    }
}
