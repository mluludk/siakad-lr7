<?php
	
	namespace Siakad\Http\Controllers;
	
	use Redirect;
	use Illuminate\Http\Request;
	
	
	use Siakad\Pegawai;
	//use Siakad\Http\Requests;
	use Siakad\Http\Controllers\Controller;
	
	class PegawaiController extends Controller
	{				
		//use \Siakad\DataExchangeTrait;	
		public function export()
		{
			$data = Pegawai::orderBy('created_at') -> get();
			$title = 'Form PTKI (Non Dosen)';
			// $this -> toXlsx(str_slug($title), $title, 'pegawai.export', $data);
			return \Excel::download(new \Siakad\Exports\DataExport('pegawai.export', $data), $title . '.xlsx');	
		}
		public function index()
		{			
			$pegawai = Pegawai::orderBy('created_at') -> paginate(30);
			return view('pegawai.index', compact('pegawai'));
		}
		public function create()
		{
			return view('pegawai.create');
		}
		public function store(Request $request)
		{
			$input = $request -> all();			
			Pegawai::create($input);
			
			return Redirect::route('pegawai.index') -> with('success', 'Data pegawai berhasil dimasukkan.');
		}
		public function edit($pegawai_id)
		{
			$pegawai = Pegawai::find($pegawai_id);			
			return view('pegawai.edit', compact('pegawai', 'prodi', 'dosen_list', 'tapel'));
		}
		public function update(Request $request, $pegawai_id)
		{
			$input = $request -> except('_method');
			Pegawai::find($pegawai_id) -> update($input);					
			return Redirect::route('pegawai.index') -> with('message', 'Data pegawai berhasil diperbarui.');
		}
		
		public function show($pegawai_id)
		{
			$pegawai = Pegawai::find($pegawai_id);
			return view('pegawai.show', compact('pegawai'));
		}
		public function destroy($id)
		{
			Pegawai::find($id) -> delete();					
			return Redirect::route('pegawai.index') -> with('message', 'Data pegawai berhasil dihapus.');
		}
	}
