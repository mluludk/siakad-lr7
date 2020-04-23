<?php
	
	namespace Siakad\Http\Controllers;
	
	
	use Redirect;
	
	use Illuminate\Http\Request;
	
	use Siakad\Skripsi;
	use Siakad\DosenSkripsi;
	use Siakad\PengajuanSkripsi;
	//use Siakad\Http\Requests;
	use Siakad\Http\Controllers\Controller;
	
	class PengajuanSkripsiController extends Controller
	{
		use \Siakad\DosenTrait;
		use \Siakad\SkripsiTrait;
		
		public function recount($id = null)
		{			
			$pengajuan = $id == null ? PengajuanSkripsi::where('diterima', '<>', 'y') -> get() : PengajuanSkripsi::whereId($id) -> get();
			
			$c = 0;
			foreach($pengajuan as $p)
			{
				//?? if($p -> id == $id && $p -> diterima == 'y') break; 
				$data = [];
				
				// if ada Revisi
				if($p -> judul_revisi != '')
				{
					$similarity = $this -> similarity($p -> judul_revisi, null, $p -> judul);
					if($p -> similarity != $similarity['sim'][0] or $p -> similar_id != $similarity['sim'][1])
					{
						$data['similarity2'] = $similarity['sim'][0];
						$data['similarity2_id'] = $similarity['sim'][1];
						$data['similarity2_array'] = $similarity['sim_array'];
						
						PengajuanSkripsi::find($p -> id) -> update($data);
						$c++;
					}
				}
				else
				{
					$similarity = $this -> similarity($p -> judul);				
					if($p -> similarity != $similarity['sim'][0] or $p -> similar_id != $similarity['sim'][1])
					{
						$data['similarity'] = $similarity['sim'][0];
						$data['similar_id'] = $similarity['sim'][1];
						$data['similarity_array'] = $similarity['sim_array'];
						
						PengajuanSkripsi::find($p -> id) -> update($data);
						$c++;
					}					
				}				
			}
			return $id == null ? view('mahasiswa.skripsi.pengajuan.index2', compact('pengajuan')) -> with('message', 'Tingkat kesamaan judul telah dihitung ulang. ' . $c . ' data telah diperbarui.') : Redirect::route('mahasiswa.skripsi.pengajuan.edit', $id) -> with('message', 'Tingkat kesamaan judul telah dihitung ulang. ' . $c . ' data telah diperbarui.');
		}
		
		public function index()
		{
			$auth = \Auth::user();
			
			$pengajuan = PengajuanSkripsi::index($auth) -> get();
			
			return view('mahasiswa.skripsi.pengajuan.index', compact('pengajuan'));
		}
		
		public function create()
		{
			$auth = \Auth::user();
			
			if($auth -> authable -> statusMhs != 1) return Redirect::route('mahasiswa.skripsi.pengajuan.index') 
			-> with('warning', 'Status Mahasiswa Tidak Aktif. Anda belum dapat melakukan pengajuan Skripsi.');
			
			$diterima = PengajuanSkripsi::where('mahasiswa_id', $auth -> authable_id) -> where('diterima', 'y') -> first();
			if($diterima) 
			{
				return Redirect::back() -> with('success_raw', 'Judul skripsi anda <strong>'. $diterima -> judul .' </strong> telah diterima');
			}
			
			$jadwal_buka = [];
			$now = time();
			$jadwal = \Siakad\JadwalPengajuanSkripsi::with('gelombang') -> where('prodi_id', $auth -> authable -> prodi_id) -> get();
			
			foreach($jadwal as $j)
			{
				foreach($j -> gelombang as $g)
				{
					if($now >= strtotime($g -> tgl_mulai . ' 00:00:00') and $now <= strtotime($g -> tgl_selesai . ' 23:59:59')) 
					$jadwal_buka[$g -> id] = $j -> nama . ' ' . $g -> nama . ', Tgl ' . $g -> tgl_mulai . ' - ' . $g -> tgl_selesai;
				}
			}
			if(count($jadwal_buka) < 1) return Redirect::back() -> with('warning', 'Pengajuan Skripsi belum dibuka');
			return view('mahasiswa.skripsi.pengajuan.create', compact('jadwal_buka'));
		}
		
		public function store(Request $request)
		{
			$auth = \Auth::user();
			$input = $request -> all();
			
			$input['mahasiswa_id'] = $auth -> authable_id;
			
			if(PengajuanSkripsi::where('mahasiswa_id', $input['mahasiswa_id']) -> where('judul', $input['judul']) -> exists())
			{
				return Redirect::route('mahasiswa.skripsi.pengajuan.index') -> with('warning', 'Judul sudah terdaftar. Mohon periksa kembali Judul anda.');
			}
			
			foreach($input['rumusan_masalah'] as $k => $v) if($v == '') unset($input['rumusan_masalah'][$k]);
			if(count($input['rumusan_masalah']) < 2) return Redirect::back() -> withErrors(['ERROR' => 'Rumusan Masalah minimal terdiri dari 2 point.']);
			
			$similarity = $this -> similarity($input['judul']);
			
			$input['similarity'] = $similarity['sim'][0];
			
			//cek 
			$gelombang = \Siakad\JadwalPengajuanSkripsiGelombang::find($input['jadwal_pengajuan_skripsi_gelombang_id']);
			if($input['similarity'] > $gelombang -> jadwal -> max_similarity) $input['diterima'] = 'n';
			
			$input['similar_id'] = $similarity['sim'][1];
			$input['similarity_array'] = $similarity['sim_array'];
			
			$input['judul'] = strtoupper($input['judul']);
			
			PengajuanSkripsi::create($input);
			
			return Redirect::route('mahasiswa.skripsi.pengajuan.index') -> with('message', 'Proposal Judul berhasil disimpan.');
		}
		
		public function edit($id)
		{
			$pengajuan = PengajuanSkripsi::with('similar', 'gelombang.jadwal') -> find($id);
			$dosen = $this -> getDosenSelection();
			return view('jadwal.pengajuan.skripsi.gelombang.peserta.edit', compact('pengajuan', 'id', 'dosen'));			
		}
		
		public function update(Request $request, $id)
		{
			$input = $request -> except(['_method', '_token']);
			
			$pembimbing = intval($input['dosen_id']) > 0 ? $input['dosen_id'] : null;
			
			$pengajuan = PengajuanSkripsi::find($id);
			
			$diterima = PengajuanSkripsi::where('mahasiswa_id', $pengajuan -> mahasiswa_id) -> where('diterima', 'y') -> first();
			if($input['diterima'] == 'y' and $diterima and $diterima -> id != $id) 
			return Redirect::back() -> with('success_raw', 'Judul skripsi mahasiswa <strong>'. $diterima -> judul .' </strong> telah diterima. Hanya satu Judul yang dapat diterima');
			
			//SET VALIDASI DOSPEM MENGIKUTI KAPRODI JIKA DITOLAK
			if($input['diterima'] == 'n') $input['diterima_dosen'] = 'n';
			
			$pengajuan -> update($input);
			
			if($input['diterima'] == 'y')
			{
				//INSERT INTO SKRIPSI
				$skripsi = Skripsi::create(['judul' => $pengajuan -> judul]);
				\Siakad\Mahasiswa::find($pengajuan -> mahasiswa_id) -> update(['skripsi_id' => $skripsi -> id]);
				
				if($pembimbing !== null ) 
				{					
					$aktif = \Siakad\Tapel::whereAktif('y') -> first();
					DosenSkripsi::where('skripsi_id', $skripsi -> id) -> delete();
					DosenSkripsi::create(['dosen_id' => $pembimbing, 'skripsi_id' => $skripsi -> id, 'tapel_id' => $aktif -> id]);
				}
				else return Redirect::back() -> with('warning', 'Pembimbing Skripsi belum dipilih.');
				
				//tolak judul yang lain
				PengajuanSkripsi::where('mahasiswa_id', $pengajuan -> mahasiswa_id) 
				-> where('diterima', '<>', 'y') -> update(['diterima' => 'n']);
			}
			elseif($input['diterima'] == 'n')
			{
				\Siakad\Mahasiswa::find($pengajuan -> mahasiswa_id) -> update(['skripsi_id' => 0]);				
			}
			
			$gelombang_id = $pengajuan -> gelombang -> id;
			
			return Redirect::route('jadwal.pengajuan.skripsi.gelombang.peserta', $gelombang_id) -> with('message', 'Proposal Judul Skripsi berhasil diubah.');
		}
	}
