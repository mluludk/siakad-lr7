<?php
	
	namespace Siakad\Http\Controllers;
	
	use Redirect;
	use Illuminate\Http\Request;
	
	
	use Siakad\JadwalUjianSkripsiGelombang;
	use Siakad\PesertaUjianSkripsi;
	//use Siakad\Http\Requests;
	use Siakad\Http\Controllers\Controller;
	
	class PesertaUjianSkripsiController extends Controller
	{
		use \Siakad\RuangTrait;
		use \Siakad\DosenTrait;
		
		public function index($j, $id)
		{
			$gelombang = JadwalUjianSkripsiGelombang::with('ujian') -> find($id);
			$peserta = PesertaUjianSkripsi::daftarPeserta($id) -> get();
			
			$ruang = $this -> getRuangSelection();
			$dosen = $this -> getDosenSelection();
			
			return view('jadwal.ujian.skripsi.gelombang.peserta.index', compact('j', 'gelombang', 'peserta', 'ruang', 'dosen'));
		}
		
		public function cetak($j, $id)
		{
			$gelombang = JadwalUjianSkripsiGelombang::with('ujian') -> find($id);
			$peserta = PesertaUjianSkripsi::daftarPeserta($id) -> get();			
			return view('jadwal.ujian.skripsi.gelombang.peserta.print', compact('j', 'gelombang', 'peserta'));
		}
		/* 
			public function edit($j, $jusg_id, $mahasiswa_id)
			{
			$jus = PesertaUjianSkripsi::where('mahasiswa_id', $mahasiswa_id) -> where('jusg_id', $jusg_id) -> first();
			$gelombang = JadwalUjianSkripsiGelombang::with('ujian') -> find($jusg_id);
			
			$ruang = $this -> getRuangSelection();
			$dosen = $this -> getDosenSelection();
			
			return view('jadwal.ujian.skripsi.gelombang.peserta.edit', compact('j', 'jus', 'gelombang', 'ruang', 'dosen'));
			} 
		*/
		public function edit()
		{
			$input = $request -> all();
			
			$tipe = $input['tipe'];
			switch($input['tipe'])
			{
				case 'ruang':
				$tipe = 'ruang_id';
				break;
				
				case 'mulai':
				$tipe = 'jam_mulai';
				break;
				
				case 'selesai':
				$tipe = 'jam_selesai';
				break;
				
				case 'penguji':
				$tipe = 'penguji_utama';
				break;
				
				case 'sekretaris':
				$tipe = 'sekretaris';
				break;
			}
			
			//Cek Jadwal Bentrok
			if($this -> cekBentrok($input)) return ['success' => false, 'message' => 'Jadwal Dosen bentrok. Mohon periksa kembali'];
			
			$update = PesertaUjianSkripsi::where('mahasiswa_id', $input['mahasiswa_id']) 
			-> where('jusg_id', $input['jusg_id'])
			// -> where('deleted', 'n')
			-> update([$tipe => $input['value']]);
			
			if($update)	return ['success' => true];
			
			return ['success' => false];
		}
		
		private function cekBentrok($input)
		{
			$jadwal = PesertaUjianSkripsi::where('mahasiswa_id', $input['mahasiswa_id']) 
			-> where('jusg_id', $input['jusg_id']) 
			-> first();
			
			if($jadwal -> tanggal == null or $jadwal -> jam_mulai == null/*  or $jadwal -> jam_selesai == null */) return false;
			
			$cek = \DB::select('
			SELECT * 
			FROM `mahasiswa_jusg`
			WHERE (`mahasiswa_id` <> :mahasiswa_id OR `jusg_id` <> :jusg_id)
			AND (`penguji_utama` = :d1 OR `ketua` = :d2 OR `sekretaris` = :d3)
			AND tanggal = :tanggal 
			AND jam_mulai BETWEEN TIME(:jam_mulai) AND TIME(:jam_selesai)
			', [
			'mahasiswa_id' => $jadwal -> mahasiswa_id,
			'jusg_id' => $jadwal -> jusg_id,
			'd1' => $input['value'],
			'd2' => $input['value'],
			'd3' => $input['value'],
			'tanggal' => $jadwal -> tanggal,
			'jam_mulai' => $jadwal -> jam_mulai,
			'jam_selesai' => $jadwal -> jam_selesai			
			]);				
			
			if(count($cek)) return true;
			
			//default
			return false;			
		}
		
		/* public function update(Request $request, $j, $jusg_id, $mahasiswa_id)
			{
			$input = $request -> except('_method', '_token');
			PesertaUjianSkripsi::where('mahasiswa_id', $mahasiswa_id) -> where('jusg_id', $jusg_id) -> update($input);			
		return Redirect::route('jadwal.ujian.skripsi.gelombang.peserta.index', [$j, $jusg_id]) -> with('message', 'Data Peserta berhasil diperbarui.');	
		} */
		
		public function destroy($j, $id, $mahasiswa_id)
		{
			PesertaUjianSkripsi::where('mahasiswa_id', $mahasiswa_id) -> where('jusg_id', $id) -> limit(1) -> delete();
			// PesertaUjianSkripsi::where('mahasiswa_id', $mahasiswa_id) -> where('jusg_id', $id) -> update(['deleted' => 'y']);
			return Redirect::back() -> with('success', 'Data telah dihapus');
		}
	}
