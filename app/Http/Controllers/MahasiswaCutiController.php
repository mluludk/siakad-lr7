<?php
	
	namespace Siakad\Http\Controllers;
	
	use Redirect;
	
	use Siakad\Mahasiswa;
	use Siakad\MahasiswaCuti;
	//use Siakad\Http\Requests;
	use Siakad\Http\Controllers\Controller;
	
	use Illuminate\Http\Request;
	
	use Illuminate\Database\QueryException;
	
	class MahasiswaCutiController extends Controller
	{
		use \Siakad\MahasiswaTrait;
		use \Siakad\TapelTrait;
		
		public function index()
		{
			$user = \Auth::user();
			
			$prodi_id = null;
			if($user -> role_id > 60 and $user -> role_id < 128)
			{
				$prodi_id = \Siakad\Prodi::whereSingkatan($user -> role -> sub) -> pluck('id');
			}
			
			$data = MahasiswaCuti::getData($prodi_id) -> paginate(30);
			return view('mahasiswa.cuti.index', compact('data'));
		}
		
		public function detail($mahasiswa_id)
		{
			// $mahasiswa = Mahasiswa::whereId(null, $mahasiswa_id) -> first();
			$mahasiswa = Mahasiswa::find($mahasiswa_id);
			$data = MahasiswaCuti::getData($mahasiswa_id) -> get();
			return view('mahasiswa.cuti.detail', compact('data', 'mahasiswa'));
		}
		
		public function create($mahasiswa_id=null)
		{
			$user = \Auth::user();
			
			$prodi_id = null;
			if($user -> role_id > 60 and $user -> role_id < 128)
			{
				$prodi_id = \Siakad\Prodi::whereSingkatan($user -> role -> sub) -> pluck('id');
			}
			
			$mahasiswa = $this -> getMahasiswaSelection(1, $prodi_id, 'NIM', 'desc');
			$tapel = $this -> getTapelSelection();
			return view('mahasiswa.cuti.create', compact('mahasiswa_id', 'tapel', 'mahasiswa'));
		}
		
		public function store(Request $request)
		{
			$input = $request -> all();
			$tapel = \Siakad\Tapel::whereId($input['tapel_id']) -> first();
			
			// $input['mahasiswa_id'] = $mahasiswa_id;
			$input['tgl_mulai'] = $tapel -> mulai;
			$input['user_id'] = \Auth::user() -> authable_id;
			
			try {
				MahasiswaCuti::create($input);
			} 
			catch(QueryException $e)
			{
				return Redirect::route('mahasiswa.cuti.index') -> withErrors(['ERROR' => 'Data cuti gagal disimpan. Mohon periksa ulang apakah mahasiswa sudah tercatat sedang cuti.']);
			}
			
			Mahasiswa::find($input['mahasiswa_id']) -> update(['statusMhs' => $input['status'], 'tgl_cuti' => date('d-m-Y'), 'tgl_aktif' => null]);
			
			return Redirect::route('mahasiswa.cuti.index') -> with('message', 'Data cuti berhasil disimpan');
		}
		
		public function edit($id)
		{
			$user = \Auth::user();
			$prodi_id = null;
			if($user -> role_id > 60 and $user -> role_id < 128)
			{
				$prodi_id = \Siakad\Prodi::whereSingkatan($user -> role -> sub) -> pluck('id');
			}
			$mahasiswa = $this -> getMahasiswaSelection(1, $prodi_id, 'NIM', 'desc');
			$data = MahasiswaCuti::whereId($id) -> first();
			$mahasiswa_id = $data -> mahasiswa_id;
			$tapel = $this -> getTapelSelection();
			
			return view('mahasiswa.cuti.edit', compact('mahasiswa_id', 'mahasiswa', 'data', 'tapel'));
		}
		
		public function update(Request $request, $id)
		{
			$input = $request -> all();
			$data = MahasiswaCuti::find($id) -> update($input);
			return Redirect::route('mahasiswa.cuti.index') -> with('message', 'Data cuti berhasil diubah');
		}
		
		public function reactivate($id)
		{
			$data = MahasiswaCuti::find($id);
			Mahasiswa::find($data -> mahasiswa_id) -> update(['statusMhs' => 1, 'tgl_cuti' => null, 'tgl_aktif' => date('d-m-Y')]);
			$data -> update(['status' => 0]);
			
			return Redirect::route('mahasiswa.cuti.index') -> with('message', 'Mahasiswa telah aktif kembali');
		}
		
		
		public function delete($id)
		{
			$data = MahasiswaCuti::find($id) -> delete();
			
			return redirect() -> back() -> with('message', 'Data telah dihapus');
		}
	}
	
