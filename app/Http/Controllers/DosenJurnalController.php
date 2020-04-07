<?php
	
	namespace Siakad\Http\Controllers;
	
	use Redirect;
	use Illuminate\Http\Request;
	
	
	use Siakad\Dosen;
	use Siakad\DosenJurnal;
	//use Siakad\Http\Requests;
	use Siakad\Http\Controllers\Controller;
	
	class DosenJurnalController extends Controller
	{		
		use \Siakad\DosenTrait;		
		
		protected $rules = [
		//'issn' => ['digits:9']
		];
		
		public function index()
		{
			$jurnal = DosenJurnal::jurnal() -> paginate(30);
			return view('dosen.jurnal.index', compact('jurnal'));
		}
		
		/**
			* Show the form for creating a new resource.
			*
			* @return \Illuminate\Http\Response
		*/
		public function create()
		{
			$auth = \Auth::user();
			$dosen_list = $auth -> role_id == 128 ? null : $this -> getDosenSelection();
			return view('dosen.jurnal.create', compact('dosen_list', 'auth'));
		}
		
		/**
			* Store a newly created resource in storage.
			*
			// * @param  \Illuminate\Http\Request  $request
			* @return \Illuminate\Http\Response
		*/
		public function store(Request $request)
		{
			$this -> validate($request, $this -> rules);
			$input = $request -> all();
			$auth = \Auth::user();
			
			DosenJurnal::create($input);
			
			if($auth -> role_id == 128) return Redirect::route('dosen.jurnal', $auth -> authable_id) -> with('success', 'Data Jurnal berhasil dimasukkan.');
			return Redirect::route('dosen.jurnal.index') -> with('success', 'Data Jurnal berhasil dimasukkan.');
		}
		
		/**
			* Show the form for editing the specified resource.
			*
			* @param  int  $id
			* @return \Illuminate\Http\Response
		*/
		public function edit($jurnal_id)
		{
			$auth = \Auth::user();
			$dosen_list = $auth -> role_id == 128 ? null : $this -> getDosenSelection();;
			$jurnal = DosenJurnal::find($jurnal_id);
			
			return view('dosen.jurnal.edit', compact('jurnal', 'dosen_list', 'auth'));
		}
		
		/**
			* Update the specified resource in storage.
			*
			// * @param  \Illuminate\Http\Request  $request
			* @param  int  $id
			* @return \Illuminate\Http\Response
		*/
		public function update(Request $request, $jurnal_id)
		{
			$this -> validate($request, $this -> rules);
			$input = $request -> except('_method');
			
			$auth = \Auth::user();
			
			DosenJurnal::find($jurnal_id) -> update($input);		
			
			if($auth -> role_id == 128) return Redirect::route('dosen.jurnal', $auth -> authable_id) -> with('success', 'Data Jurnal berhasil diperbarui.');			
			return Redirect::route('dosen.jurnal.index') -> with('message', 'Data Jurnal Dosen berhasil diperbarui.');
		}
		
		/**
			* Remove the specified resource from storage.
			*
			* @param  int  $id
			* @return \Illuminate\Http\Response
		*/
		public function destroy($jurnal_id)
		{
			DosenJurnal::find($jurnal_id) -> delete();		
			return Redirect::route('dosen.jurnal.index', $dosen_id) -> with('message', 'Data Jurnal Dosen berhasil dihapus.');
		}
		
		public function export()
		{
			$title = 'Jurnal Ilmiah Dosen';
			return \Excel::download(new \Siakad\Exports\DosenJurnalExport, str_slug($title) . '.xlsx');
		}
	}
