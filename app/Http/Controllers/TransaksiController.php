<?php
	
	namespace Siakad\Http\Controllers;
	
	use Redirect;
	
	
	use Illuminate\Http\Request;
	
	use Siakad\Transaksi;
	//use Siakad\Http\Requests;
	use Siakad\Http\Controllers\Controller;
	
	class TransaksiController extends Controller
	{
		use \Siakad\NeracaTrait;
		protected $rules = ['jumlah' => ['required:', 'numeric'], 'jenis' => ['required']];
		/**
			* Display a listing of the resource.
			*
			* @return \Illuminate\Http\Response
		*/
		public function index()
		{
			$transaksi = Transaksi::orderBy('tanggal', 'desc') -> with('petugas') -> paginate(30);
			return view('transaksi.index', compact('transaksi'));
		}
		
		/**
			* Show the form for creating a new resource.
			*
			* @return \Illuminate\Http\Response
		*/
		public function create()
		{
			return view('transaksi.create');
		}
		
		/**
			* Store a newly created resource in storage.
			*
			* @param  \Illuminate\Http\Request  $request
			* @return \Illuminate\Http\Response
		*/
		public function store(Request $request)
		{
			$this -> validate($request, $this -> rules);
			$input = $request -> except('_token');
			$jenis_transaksi = [1 => 'masuk', 'keluar'];
			
			$input['user_id'] = \Auth::user() -> id;
			$transaksi = Transaksi::create($input);
			//Neraca
			$this -> storeIntoNeraca(['transable_id' => $transaksi -> id, 'transable_type' => 'Siakad\Transaksi', 'jenis' => $jenis_transaksi[$input['jenis']], 'jumlah' => $input['jumlah']]);
			
			return Redirect::route('transaksi.index') -> with('message', 'Transaksi berhasil disimpan');
		}
		
		/**
			* Display the specified resource.
			*
			* @param  int  $id
			* @return \Illuminate\Http\Response
		*/
		public function show($id)
		{
			//
		}
		
		/**
		* Show the form for editing the specified resource.
		*
		* @param  int  $id
		* @return \Illuminate\Http\Response
		*/
		public function edit($id)
		{
		//
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
		//
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
				