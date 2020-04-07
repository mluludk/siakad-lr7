<?php
	
	namespace Siakad\Http\Controllers;
	
	use Redirect;
	use Illuminate\Http\Request;
	
	
	use Siakad\Skala;
	//use Siakad\Http\Requests;
	use Siakad\Http\Controllers\Controller;
	
	class SkalaController extends Controller
	{
		use \Siakad\ProdiTrait;
		protected $rules = [
		'angka' => ['required', 'numeric', 'min:0', 'max:100'],
		'bobot_min' => ['required', 'numeric', 'min:0', 'max:99'],
		'bobot_max' => ['required', 'numeric', 'greater_than:bobot_min', 'min:1', 'max:100']
		];
		
		public function index(Request $request)
		{
			$prodi_id = null !== $request -> get('prodi') ? $request -> get('prodi') : 1;
			$prodi = $this -> getProdiSelection();
			$skala = Skala::with('prodi') -> where('prodi_id', $prodi_id) -> orderBy('angka', 'desc') -> get();
			return view('skala.index', compact('skala', 'prodi'));
		}
		
		public function create()
		{
			$prodi = $this -> getProdiSelection();
			return view('skala.create', compact('prodi'));
		}
		
		public function store(Request $request)
		{
			$this -> validate($request, $this -> rules);
			$input= $request -> except('_token');
			Skala::create($input);			
			return Redirect::route('skala.index') -> with('message', 'Data skala berhasil dimasukkan.');
		}
		
		public function edit($id)
		{
			$prodi = $this -> getProdiSelection();
			$skala = Skala::find($id);
			return view('skala.edit', compact('skala', 'prodi'));
		}
		
		public function update(Request $request, $id)
		{
			$this -> validate($request, $this -> rules);
			$input = $request -> except('_method');
			Skala::find($id) -> update($input);			
			return Redirect::route('skala.index') -> with('message', 'Data skala berhasil diperbarui.');
		}
		
		public function destroy($id)
		{
			Skala::find($id) -> delete($input);			
			return Redirect::route('skala.index') -> with('message', 'Data skala berhasil dihapus.');
		}
	}
