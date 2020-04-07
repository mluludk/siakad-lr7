<?php
	
	namespace Siakad\Http\Controllers;
	
	use Redirect;
	
	use Illuminate\Http\Request;
	
	
	use Siakad\Informasi;
	//use Siakad\Http\Requests;
	use Siakad\Http\Controllers\Controller;
	
	class InformasiController extends Controller
	{
		/**
			* Display a listing of the resource.
			*
			* @return \Illuminate\Http\Response
		*/
		public function index()
		{
			$info = Informasi::with('poster') -> orderBy('created_at', 'desc') -> paginate(30);
			return view('informasi.index', compact('info'));
		}
		
		/**
			* Show the form for creating a new resource.
			*
			* @return \Illuminate\Http\Response
		*/
		public function create()
		{
			$tmp = \Siakad\Role::whereNotIn('id', [1, 2, 4096]) -> get();
			foreach($tmp as $a) $akses[$a->id] = $a -> name . ' ' . $a -> sub; 
			return view('informasi.create', compact('akses'));
		}
		
		/**
			* Store a newly created resource in storage.
			*
			* @param  \Illuminate\Http\Request  $request
			* @return \Illuminate\Http\Response
		*/
		public function store(Request $request)
		{
		$this -> validate($request, ['judul' => ['required', 'min:5'], 'isi' => ['required', 'min:30'], 'akses' => ['required']]);
		$input = $request -> only('isi', 'akses', 'judul');
		$input['user_id'] = \Auth::user() -> id;
		$input['akses'] = json_encode($input['akses']);
		Informasi::create($input);
		return Redirect::route('informasi.index') -> with('message', 'Informasi berhasil diposting');
		}
		
		/**
		* Display the specified resource.
		*
		* @param  int  $id
		* @return \Illuminate\Http\Response
		*/
		public function show($id)
		{
		$data = Informasi::whereId($id) -> first(['isi']);
		return \Response::json(['success' => true, 'isi' => $data['isi']]);
		}
		
		public function publicShow($id)
		{
		$data = Informasi::whereId($id) -> first();
		return view('informasi.public', compact('data'));
		}
		
		/**
		* Show the form for editing the specified resource.
		*
		* @param  int  $id
		* @return \Illuminate\Http\Response
		*/
		public function edit($id)
		{
		$informasi = Informasi::find($id);
		
		$akses = \Siakad\Role::pluck('name', 'id');
		$akses = array_except($akses, [1, 2, 4096]);
		
		$to = json_decode($informasi -> akses);
		return view('informasi.edit', compact('akses', 'informasi', 'to'));
		}
		
		/**
		* Update the specified resource in storage.
		*
		* @param  \Illuminate\Http\Request  $request
		* @param  int  $id
		* @return \Illuminate\Http\Response
		*/
		public function update(Request $request, $id)
		{
		$this -> validate($request, ['judul' => ['required', 'min:5'], 'isi' => ['required', 'min:30'], 'akses' => ['required']]);
		$input = $request -> only('isi', 'akses', 'judul');
		$input['updated_by'] = \Auth::user() -> id;
		$input['akses'] = json_encode($input['akses']);
		Informasi::find($id) -> update($input);
		return Redirect::route('informasi.index') -> with('message', 'Informasi berhasil diperbarui');
		}
		
		/**
		* Remove the specified resource from storage.
		*
		* @param  int  $id
		* @return \Illuminate\Http\Response
		*/
		public function destroy($id)
		{
		//
		}
		}
				