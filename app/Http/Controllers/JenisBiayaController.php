<?php
	
	namespace Siakad\Http\Controllers;
	
	
	use Redirect;
	
	use Illuminate\Http\Request;
	
	use Siakad\JenisBiaya;
	//use Siakad\Http\Requests;
	use Siakad\Http\Controllers\Controller;
	
	class JenisBiayaController extends Controller
	{
		protected $rules = ['nama' => ['required', 'min:2']];
		protected $periode = [
		1 => 'Awal Masuk',
		'Per-bulan',
		'Per-semester',
		'Per-tahun',
		'Tagihan Kelulusan'
		];
		protected $golongan = [
		1 => 'BAMK',
		'BAMP',
		'SPP',
		'Her Registrasi',
		'Kelulusan',
		99 => 'Lain-lain'
		];
		
		/**
			* Display a listing of the resource.
			*
			* @return \Illuminate\Http\Response
		*/
		public function index()
		{
			$periode = $this -> periode;
			$golongan = $this -> golongan;
			$jbiaya = JenisBiaya::orderBy('golongan') -> get();
			$jenis = [];
			foreach($jbiaya as $j)
			{
				foreach($golongan as $k => $v) if($k == $j -> golongan) $jenis[$v][] = $j;
			}
			return view('jenisbiaya.index', compact('jenis', 'periode', 'golongan'));
		}
		
		/**
			* Show the form for creating a new resource.
			*
			* @return \Illuminate\Http\Response
		*/
		public function create()
		{
			$periode = $this -> periode;
			$golongan = $this -> golongan;
			return view('jenisbiaya.create', compact('periode', 'golongan'));
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
		
		JenisBiaya::create($input);
		return Redirect::route('jenisbiaya.index') -> with('message', 'Jenis pembayaran berhasil disimpan');
		}
		
		/**
			* Show the form for editing the specified resource.
			*
			* @param  int  $id
			* @return \Illuminate\Http\Response
		*/
		public function edit($id)
		{
			$periode = $this -> periode;
			$golongan = $this -> golongan;
			$jbiaya = JenisBiaya::find($id);
			return view('jenisbiaya.edit', compact('jbiaya', 'periode', 'golongan'));
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
			$this -> validate($request, $this -> rules);
			$input = $request -> except('_token', '_method');
			JenisBiaya::find($id) -> update($input);
			return Redirect::route('jenisbiaya.index') -> with('message', 'Jenis pembayaran berhasil diperbarui');
		}
		
		/**
			* Remove the specified resource from storage.
			*
			* @param  int  $id
			* @return \Illuminate\Http\Response
		*/
		public function destroy($id)
		{
			JenisBiaya::find($id) -> delete();
			return Redirect::route('jenisbiaya.index') -> with('message', 'Jenis pembayaran berhasil dihapus');			
		}
	}
