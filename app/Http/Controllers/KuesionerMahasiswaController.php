<?php
	
	namespace Siakad\Http\Controllers;
	
	use Redirect;
	
	use Illuminate\Http\Request;
	
	
	use Siakad\KuesionerMahasiswa;
	//use Siakad\Http\Requests;
	use Siakad\Http\Controllers\Controller;
	
	class KuesionerMahasiswaController extends Controller
	{
		
		/**
			* Update the specified resource in storage.
			*
			* @param  \Illuminate\Http\Request  $request
			* @param  int  $id
			* @return \Illuminate\Http\Response
		*/
		public function update(Request $request, $id)
		{
			$mhs_id = \Auth::user() -> authable_id;
			$total = \Siakad\Kuesioner::where('tampil', 'y') -> count();
			$input = array_except($request -> all(), ['_method', '_token']);
			if($total != count($input)) return redirect() -> back() -> withErrors('Semua pertanyaan kuesioner harus dijawab');
			// dd($input);
			foreach($input as $k => $v)
			{
				$kk = explode('-', $k);
				KuesionerMahasiswa::where('mahasiswa_id', $mhs_id) 
				-> where('matkul_tapel_id', $id) 
				-> where('kuesioner_id', $kk[1]) 
				-> update(['skor' => $v]);
			}
			return redirect('/home');
		}
		
		/* private function populateKuesioner($mhs_id, $mt_id)
			{
			$result = \DB::insert("
			INSERT IGNORE INTO kuesioner_mahasiswa (kuesioner_id, mahasiswa_id, matkul_tapel_id, created_at) 
			SELECT id, :mhs_id, :mt_id, now() FROM kuesioner WHERE tampil = 'y'", 
			['mhs_id' => $mhs_id, 'mt_id' => $mt_id]
			);	
		} */
		
		private function checkKuesioner($mhs_id, $mt_id)
		{
			return KuesionerMahasiswa::where('mahasiswa_id', $mhs_id) -> where ('matkul_tapel_id', $mt_id) -> exists();
		}
		
		public function penilaian($id = null, $mtid = null)
		{
			$mhs_id = \Auth::user() -> authable_id;
			
			$mt_id = [];
			$completed_course = \Siakad\Nilai::getCompletedCourse() -> get();
			foreach($completed_course as $mt)
			{
				/* if(!$this -> checkKuesioner($mhs_id, $mt -> mtid))
					{
					$this -> populateKuesioner($mhs_id, $mt -> mtid);
				} */
				$mt_id[] = $mt -> mtid;
			}
			if($id != null)
			{
				$mt_id[] = $mtid;
				$dosen_matkul = \Siakad\MatkulTapel::getDataMataKuliah($mtid) ->first();
				$kuesioners = \Siakad\Kuesioner::whereTampil('y') -> orderBy('kompetensi') -> get();
				foreach($kuesioners as $kuesioner)
				{
					$poin[$kuesioner -> kompetensi][] = $kuesioner;
				}
			}
/* 
			$dosens = \DB::select("
			SELECT 
			d.nama AS dosen, d.id AS iddosen, d.*, 
			m.nama AS matakuliah, 
			mt.id AS idmt, skor, 
			t.nama as tapel 
			FROM kuesioner_mahasiswa km
			INNER JOIN matkul_tapel mt ON km.matkul_tapel_id = mt.id
			INNER JOIN kurikulum_matkul krm ON krm.id = mt.kurikulum_matkul_id
			INNER JOIN matkul m ON krm.matkul_id = m.id
			INNER JOIN tim_dosen td ON td.matkul_tapel_id = mt.id
			INNER JOIN dosen d ON d.id = td.dosen_id
			INNER JOIN tapel t ON t.id = mt.tapel_id
			WHERE skor < 1 
			AND km.mahasiswa_id = :mhs_id AND km.matkul_tapel_id IN
			(
				SELECT mt.id 
				FROM matkul_tapel mt 
				INNER JOIN tapel 
				ON tapel.id = mt.tapel_id 
				WHERE mt.tapel_id in 
				(
					SELECT tapel.id FROM tapel WHERE tapel.nama2 < 
					(
						SELECT tapel.nama2 FROM tapel WHERE tapel.aktif = 'y'
					)
				)
			)
			GROUP BY mt.id
			ORDER BY t.nama2
			",
			['mhs_id' => $mhs_id]
			); */
			$dosens = \DB::select("
			SELECT 
			d.nama AS dosen, d.id AS iddosen, d.*, 
			m.nama AS matakuliah, 
			mt.id AS idmt, skor, 
			t.nama as tapel 
			FROM kuesioner_mahasiswa km
			INNER JOIN matkul_tapel mt ON km.matkul_tapel_id = mt.id
			INNER JOIN kurikulum_matkul krm ON krm.id = mt.kurikulum_matkul_id
			INNER JOIN matkul m ON krm.matkul_id = m.id
			INNER JOIN tim_dosen td ON td.matkul_tapel_id = mt.id
			INNER JOIN dosen d ON d.id = td.dosen_id
			INNER JOIN tapel t ON t.id = mt.tapel_id
			WHERE skor < 1 
			AND km.mahasiswa_id = :mhs_id AND km.matkul_tapel_id IN
			(
				SELECT mt.id 
				FROM matkul_tapel mt 
				INNER JOIN tapel 
				ON tapel.id = mt.tapel_id 
				WHERE mt.tapel_id = 
				(
					SELECT tapel.id FROM tapel WHERE tapel.nama2 < 
					(
						SELECT tapel.nama2 FROM tapel WHERE tapel.aktif = 'y'
					) 
					ORDER BY nama2 DESC 
					LIMIT 1
				)
			)
			GROUP BY mt.id
			ORDER BY t.nama2
			",
			['mhs_id' => $mhs_id]
			);
			
			if($id == null)
			{
				return view('kuesioner.penilaian', compact('dosens'));
			}
			else
			{
				return view('kuesioner.penilaian', compact('dosens', 'dosen_matkul', 'poin', 'mtid'));
			}
		}
		
	}
