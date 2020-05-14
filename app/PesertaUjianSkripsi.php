<?php
//PENDAFTARAN UJIAN SKRIPSI
namespace Siakad;

use Illuminate\Database\Eloquent\Model;

class PesertaUjianSkripsi extends Model
{
	protected $guarded = [];
	protected $table = 'mahasiswa_jusg';
	public $timestamps = false;

	public function scopeCheckPeserta($query, $mahasiswa_id, $jenis)
	{
		$query
			->join('jadwal_ujian_skripsi_gelombang', 'jadwal_ujian_skripsi_gelombang.id', '=', 'mahasiswa_jusg.jusg_id')
			->join('jadwal_ujian_skripsi', 'jadwal_ujian_skripsi.id', '=', 'jadwal_ujian_skripsi_gelombang.jadwal_ujian_skripsi_id')
			->where('mahasiswa_id', $mahasiswa_id)
			->where('jenis', $jenis)
			->select('mahasiswa_id');
	}
	public function scopeJadwalUjian($query, $mahasiswa_id, $jenis)
	{
		$query
			->join('jadwal_ujian_skripsi_gelombang', 'jadwal_ujian_skripsi_gelombang.id', '=', 'mahasiswa_jusg.jusg_id')
			->join('jadwal_ujian_skripsi', 'jadwal_ujian_skripsi.id', '=', 'jadwal_ujian_skripsi_gelombang.jadwal_ujian_skripsi_id')
			->leftJoin('ruang', 'ruang.id', '=', 'mahasiswa_jusg.ruang_id')
			->leftJoin('dosen as d1', 'd1.id', '=', 'mahasiswa_jusg.penguji_utama')
			->leftJoin('dosen as d2', 'd2.id', '=', 'mahasiswa_jusg.ketua')
			->leftJoin('dosen as d3', 'd3.id', '=', 'mahasiswa_jusg.sekretaris')
			->where('mahasiswa_id', $mahasiswa_id)
			->where('jenis', $jenis)
			->select(
				'jadwal_ujian_skripsi.nama as nama_ujian',
				'no_surat',
				'jadwal_ujian_skripsi_gelombang.nama as nama_gelombang',
				'ruang.nama as ruang',
				'tanggal',
				'jam_mulai',
				'jam_selesai',
				'd1.gelar_depan as p_gd',
				'd1.nama as p_nama',
				'd1.gelar_belakang as p_gb',
				'd2.gelar_depan as k_gd',
				'd2.nama as k_nama',
				'd2.gelar_belakang as k_gb',
				'd3.gelar_depan as k_gd',
				'd3.nama as s_nama',
				'd3.gelar_belakang as s_gb'
			);
	}

	public function scopeDaftarPeserta($query, $jusg_id)
	{
		$query
			->join('mahasiswa', 'mahasiswa.id', '=', 'mahasiswa_jusg.mahasiswa_id')
			->join('skripsi', 'mahasiswa.skripsi_id', '=', 'skripsi.id')
			->leftJoin('ruang', 'ruang.id', '=', 'mahasiswa_jusg.ruang_id')
			->leftJoin('dosen as d1', 'd1.id', '=', 'mahasiswa_jusg.penguji_utama')
			->leftJoin('dosen as d2', 'd2.id', '=', 'mahasiswa_jusg.ketua')
			->leftJoin('dosen as d3', 'd3.id', '=', 'mahasiswa_jusg.sekretaris')
			->where('jusg_id', $jusg_id)
			// -> where('deleted', 'n')
			->orderBy('mahasiswa_jusg.tanggal', 'desc')
			->select(
				'jusg_id',
				'mahasiswa_id',
				'mahasiswa.nama',
				'NIM',
				'skripsi.judul',
				'ruang.nama as ruang',
				'tanggal',
				'jam_mulai',
				'jam_selesai',
				'd1.gelar_depan as p_gd',
				'd1.nama as p_nama',
				'd1.gelar_belakang as p_gb',
				'd2.gelar_depan as k_gd',
				'd2.nama as k_nama',
				'd2.gelar_belakang as k_gb',
				'd3.gelar_depan as k_gd',
				'd3.nama as s_nama',
				'd3.gelar_belakang as s_gb'
			);
	}

	public function mahasiswa()
	{
		return $this->belongsTo(Mahasiswa::class);
	}
	public function ruang()
	{
		return $this->belongsTo(Ruang::class);
	}
	public function p()
	{
		return $this->belongsTo(Dosen::class, 'penguji_utama');
	}
	public function k()
	{
		return $this->belongsTo(Dosen::class, 'ketua');
	}
	public function s()
	{
		return $this->belongsTo(Dosen::class, 'sekretaris');
	}
}
