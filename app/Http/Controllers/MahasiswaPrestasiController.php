<?php
	
	namespace Siakad\Http\Controllers;
	
	use Redirect;
	use Illuminate\Http\Request;
	
	use Siakad\Mahasiswa;
	use Siakad\MahasiswaPrestasi;
	//use Siakad\Http\Requests;
	use Siakad\Http\Controllers\Controller;
	
	class MahasiswaPrestasiController extends Controller
	{		
		//use \Siakad\DataExchangeTrait;	
		
		protected $rules = [
		'nama' => ['required'],
		'tahun' => ['required', 'numeric'],
		'penyelenggara' => ['required'],
		'peringkat' => ['required'],
		];
		
		public function index()
		{
			$prestasi = MahasiswaPrestasi::riwayatPrestasi() -> paginate(30);
			return view('mahasiswa.prestasi.index', compact('prestasi'));
		}
		
		public function prestasi($mahasiswa_id=null)
		{
			$auth = \Auth::user();
			
			if($auth -> role_id == 512) $mahasiswa_id = $auth -> authable_id;
			
			
			$mahasiswa = Mahasiswa::find($mahasiswa_id);
			$prestasi = MahasiswaPrestasi::riwayatPrestasi($mahasiswa_id) -> get();
			return view('mahasiswa.prestasi.detail', compact('auth', 'prestasi', 'mahasiswa'));
		}
		
		/**
			* Show the form for creating a new resource.
			*
			* @return \Illuminate\Http\Response
		*/
		public function create($mahasiswa_id=null)
		{
			$auth = \Auth::user();
			if($auth -> role_id == 512) $mahasiswa_id = $auth -> authable_id;
			$mahasiswa = Mahasiswa::find($mahasiswa_id);
			return view('mahasiswa.prestasi.create', compact('mahasiswa', 'auth'));
		}
		
		/**
			* Store a newly created resource in storage.
			*
			// * @param  \Illuminate\Http\Request  $request
			* @return \Illuminate\Http\Response
		*/
		public function store(Request $request, $mahasiswa_id=null)
		{
			$this -> validate($request, $this -> rules);
			
			$auth = \Auth::user();
			$input = $request -> all();
			$input['mahasiswa_id'] = $mahasiswa_id;
			if($auth -> role_id == 512) $input['mahasiswa_id'] = $auth -> authable_id;
			$input['user_id'] = $auth -> id;
			
			MahasiswaPrestasi::create($input);
			
			if($auth -> role_id == 512) return Redirect::route('mahasiswa.prestasi.personal') -> with('message', 'Data Prestasi Mahasiswa berhasil dimasukkan.');
			return Redirect::route('mahasiswa.prestasi', $mahasiswa_id) -> with('success', 'Data Prestasi berhasil dimasukkan.');
		}
		
		/**
			* Show the form for editing the specified resource.
			*
			* @param  int  $id
			* @return \Illuminate\Http\Response
		*/
		public function edit($mahasiswa_id, $prestasi_id)
		{
			$auth = \Auth::user();
			if($auth -> role_id == 512) $mahasiswa_id = $auth -> authable_id;
			
			$prestasi = MahasiswaPrestasi::find($prestasi_id);
			$mahasiswa = Mahasiswa::find($prestasi -> mahasiswa_id);
			
			return view('mahasiswa.prestasi.edit', compact('prestasi', 'mahasiswa'));
		}
		
		/**
			* Update the specified resource in storage.
			*
			// * @param  \Illuminate\Http\Request  $request
			* @param  int  $id
			* @return \Illuminate\Http\Response
		*/
		public function update(Request $request, $mahasiswa_id, $prestasi_id)
		{
			$auth = \Auth::user();
			if($auth -> role_id == 512) $mahasiswa_id = $auth -> authable_id;
			
			$this -> validate($request, $this -> rules);
			$input = $request -> except('_method');
			MahasiswaPrestasi::find($prestasi_id) -> update($input);	
			
			if($auth -> role_id == 512) 	return Redirect::route('mahasiswa.prestasi.personal') -> with('message', 'Data Prestasi Mahasiswa berhasil diperbarui.');
			return Redirect::route('mahasiswa.prestasi', $mahasiswa_id) -> with('message', 'Data Prestasi Mahasiswa berhasil diperbarui.');
		}
		
		/**
			* Remove the specified resource from storage.
			*
			* @param  int  $id
			* @return \Illuminate\Http\Response
		*/
		public function destroy($mahasiswa_id, $prestasi_id)
		{
			$auth = \Auth::user();
			if($auth -> role_id == 512) $mahasiswa_id = $auth -> authable_id;
			MahasiswaPrestasi::find($prestasi_id) -> delete();	
			
			if($auth -> role_id == 512) 	return Redirect::route('mahasiswa.prestasi.personal') -> with('message', 'Data Prestasi Mahasiswa berhasil diperbarui.');
			return Redirect::route('mahasiswa.prestasi', $mahasiswa_id) -> with('message', 'Data Prestasi Mahasiswa berhasil dihapus.');
		}
		
		public function export()
		{
			$prestasi = MahasiswaPrestasi::riwayatPrestasi() -> get();
			$data = [];
			foreach($prestasi as $j) 
			{
				$data [] = [
				$j -> NIM,  
				$j -> mahasiswa,  
				$j -> jenis, 
				$j -> tingkat,
				$j -> nama, 
				$j -> tahun,
				$j -> penyelenggara,
				$j -> peringkat,
				$j -> kode_dikti
				];
			}
			$title = 'PDDIKTI PRESTASI MAHASISWA';
			// $this -> toXlsx(str_slug($title), $title, 'mahasiswa.prestasi.export.dikti_tpl', $data);
			return \Excel::download(new \Siakad\Exports\DataExport('mahasiswa.prestasi.export.dikti_tpl', $data), $title . '.xlsx');
		}
	}
