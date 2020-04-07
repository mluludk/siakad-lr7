<?php
	
	namespace Siakad\Http\Controllers;
	
	use Redirect;
	use Illuminate\Http\Request;
	
	
	use Siakad\Tapel;
	use Siakad\Prodi;
	use Siakad\SettingTapel;
	//use Siakad\Http\Requests;
	use Siakad\Http\Controllers\Controller;
	
	class SettingTapelController extends Controller
	{
		private function seed($id)
		{
			$insert = [];
			$prodi = Prodi::pluck('id');
			foreach($prodi as $p) $insert[] = ['prodi_id' => $p, 'tapel_id' => $id];
			SettingTapel::insertIgnore($insert);			
		}
		public function index($id)
		{
			$this -> seed($id);
			$tapel = Tapel::find($id);
			return view('tapel.setting.index', compact('tapel'));
		}
		
		/**
			* Show the form for editing the specified resource.
			*
			* @param  int  $tapel_id
			* @param  int  $id
			* @return \Illuminate\Http\Response
		*/
		public function edit($tapel_id, $id)
		{
			$setting = SettingTapel::find($id);
			$tapel = $setting -> tapel;
			$prodi = $setting -> prodi;
			return view('tapel.setting.edit', compact('setting', 'tapel', 'prodi'));
		}
		
		/**
			* Update the specified resource in storage.
			*
			// * @param  \Illuminate\Http\Request  $request
			* @param  int  $id
			* @return \Illuminate\Http\Response
		*/
		public function update(Request $request, $tapel_id, $id)
		{
			$input = $request -> except('_method');
			SettingTapel::find($id) -> update($input);			
			return Redirect::route('tapel.setting.index', $tapel_id) -> with('message', 'Setting Tahun Akademik berhasil diperbarui.');
		}
	}
