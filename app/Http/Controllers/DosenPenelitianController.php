<?php
	
	namespace Siakad\Http\Controllers;
	
	use Redirect;
	use Illuminate\Http\Request;
	
	use Siakad\Dosen;
	use Siakad\DosenPenelitian;
	//use Siakad\Http\Requests;
	use Siakad\Http\Controllers\Controller;
	
	class DosenPenelitianController extends Controller
	{			
		use \Siakad\DosenTrait;
		
		public function riwayat($id)
		{
			$dosen = Dosen::whereId($id) -> first();
			if(!$dosen) abort(404);
			$penelitian = DosenPenelitian::riwayatPenelitian($id) -> get();
			
			return view('dosen.penelitian.riwayat', compact('dosen', 'penelitian'));
		}
		
		public function index()
		{
			$penelitian = DosenPenelitian::riwayatPenelitian() -> paginate(30);
			return view('dosen.penelitian.index', compact('penelitian'));
		}
		
		/**
			* Show the form for creating a new resource.
			*
			* @return \Illuminate\Http\Response
		*/
		public function create()
		{
			$dosen_list = $this -> getDosenSelection();
			return view('dosen.penelitian.create', compact('dosen_list'));
		}
		
		/**
			* Store a newly created resource in storage.
			*
			// * @param  \Illuminate\Http\Request  $request
			* @return \Illuminate\Http\Response
		*/
		public function store(Request $request)
		{
			$input = $request -> all();
			
			DosenPenelitian::create($input);
			$auth = \Auth::user();
			
			if($auth -> role_id == 128) return Redirect::route('dosen.penelitian', $auth -> authable_id) -> with('success', 'Data Penelitian berhasil disimpan.');			
			return Redirect::route('dosen.penelitian.index') -> with('success', 'Data Penelitian berhasil dimasukkan.');
		}
		
		/**
			* Show the form for editing the specified resource.
			*
			* @param  int  $id
			* @return \Illuminate\Http\Response
		*/
		public function edit($penelitian_id)
		{
			$dosen_list = $this -> getDosenSelection();
			$penelitian = DosenPenelitian::find($penelitian_id);
			
			return view('dosen.penelitian.edit', compact('penelitian', 'dosen_list'));
		}
		
		/**
			* Update the specified resource in storage.
			*
			// * @param  \Illuminate\Http\Request  $request
			* @param  int  $id
			* @return \Illuminate\Http\Response
		*/
		public function update(Request $request, $penelitian_id)
		{
			$input = $request -> except('_method');
			DosenPenelitian::find($penelitian_id) -> update($input);		
			$auth = \Auth::user();
			
			if($auth -> role_id == 128) return Redirect::route('dosen.penelitian', $auth -> authable_id) -> with('success', 'Data Penelitian berhasil diperbarui.');
			return Redirect::route('dosen.penelitian.index') -> with('message', 'Data Penelitian Dosen berhasil diperbarui.');
		}
		
		/**
			* Remove the specified resource from storage.
			*
			* @param  int  $id
			* @return \Illuminate\Http\Response
		*/
		public function destroy($penelitian_id)
		{
			DosenPenelitian::find($penelitian_id) -> delete();		
			return Redirect::route('dosen.penelitian.index') -> with('message', 'Data Penelitian Dosen berhasil dihapus.');
		}
		public function export()
		{
			$title = 'Penelitian Dosen';
			return \Excel::download(new \Siakad\Exports\DosenJurnalExport, str_slug($title) . '.xlsx');
		}
	}
