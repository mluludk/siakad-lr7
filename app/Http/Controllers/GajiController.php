<?php
	
	namespace Siakad\Http\Controllers;
	
	use Redirect;
	
	
	use Illuminate\Http\Request;
	
	use Siakad\Gaji;
	//use Siakad\Http\Requests;
	use Siakad\Http\Controllers\Controller;
	
	class GajiController extends Controller
	{
		use \Siakad\NeracaTrait;
		/**
			* Display a listing of the resource.
			*
			* @return \Illuminate\Http\Response
		*/
		public function index()
		{
			$jgaji = \Siakad\JenisGaji::orderBy('id') -> pluck('nama', 'id') -> toArray();
			
			if( \Auth::user() -> role_id == 128) //dosen
			{
				$gaji = Gaji::where('dosen_id', \Auth::user() -> authable -> id) -> orderBy('bulan', 'desc')-> with('dosen') -> paginate(30);
			}
			else			
			{
				$gaji = Gaji::orderBy('bulan', 'desc') -> with('dosen') -> paginate(30);
			}
			
			$dgaji = [];
			
			//very crude -.-a // 24-04-2016
			foreach($gaji as $g)
			{
				$dgaji[$g -> dosen_id . '-' . $g -> bulan] = ['id' => $g -> dosen_id, 'dosen' => $g -> dosen -> nama, 'bulan' => $g -> bulan, 'diterima' => $g -> tgl_terima];
				foreach($jgaji as $k => $v)
				{
					$dgaji[$g -> dosen_id . '-' . $g -> bulan]['gaji'][$k] = 0;
				}
			}
			foreach($gaji as $g)
			{
				$dgaji[$g -> dosen_id . '-' . $g -> bulan]['gaji'][$g -> jenis_gaji_id] = $g -> jumlah;
			}
			// end
			
			return view('gaji.index', compact('dgaji', 'jgaji', 'gaji'));
		}
		
		/**
			* Show the form for creating a new resource.
			*
			* @return \Illuminate\Http\Response
		*/
		public function create($dosen_id)
		{
			$dosen = \Siakad\Dosen::find($dosen_id);
			$jgaji = \Siakad\JenisGaji::orderBy('nama') -> get();
			return view('gaji.create', compact('dosen', 'jgaji'));
		}
		
		/**
			* Store a newly created resource in storage.
			*
			* @param  \Illuminate\Http\Request  $request
			* @return \Illuminate\Http\Response
		*/
		public function store(Request $request)
		{
			// $this -> validate($request, $this -> rules);
			$idj = [];
			$petugas = \Auth::user() -> id;
			$time = date('Y-m-d H:i:s');
			$input = $request -> except('_token');
			foreach($input['gaji'] as $k => $v) if($v != '') 
			{
				$gaji = [
				'created_at' => $time, 
				'updated_at' => $time, 
				'jenis_gaji_id' => $k, 
				'dosen_id' => $input['dosen_id'], 
				'bulan' => $input['tahun'] . '-' . $input['bulan'], 
				'jumlah' => $v, 
				'user_id' => $petugas
				];
				
				$ga = Gaji::create($gaji);
				$idj[$ga -> id] = $v;
			}
			
			//Neraca
			$this -> storeIntoNeraca(['transable_id' => $idj, 'transable_type' => 'Siakad\Gaji', 'jenis' => 'keluar']);
			return Redirect::route('gaji.index') -> with('message', 'Pembayaran gaji berhasil disimpan');
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
		public function destroy($dosen_id, $bulan)
		{
			$gaji = Gaji::where('dosen_id', $dosen_id) -> where('bulan', $bulan);
			$g_data = $gaji -> get();
			
			foreach($g_data as $g) $gaji_id[] = $g -> id;
			
			$gaji -> delete();
			
			//delete from Neraca
			$this -> deleteFromNeraca(['transable_id' => $gaji_id, 'transable_type' => 'Siakad\Gaji']);
			
			return Redirect::route('gaji.index') -> with('message', 'Data gaji berhasil dihapus');			
		}
		
		public function confirm($dosen_id, $bulan)
		{
			$gaji = Gaji::where('dosen_id', $dosen_id) -> where('bulan', $bulan) -> update(['tgl_terima' => date('Y-m-d')]);
			return Redirect::route('gaji.index') -> with('message', 'Gaji telah diterima');			
		}
	}
