<?php
	
	namespace Siakad\Http\Controllers;
	
	use Input;
	use Session;
	use Response;
	use Redirect;
	
	use Siakad\JenisNilai;
	//use Siakad\Http\Requests;
	use Siakad\Http\Controllers\Controller;
	
	use Illuminate\Http\Request;
	
	class JenisNilaiController extends Controller
	{
		use \Siakad\ProdiTrait;
		protected $rules = [
		'nama' => ['required'],
		'bobot' => ['required', 'digits_between:0,100']
		];
		public function index()
		{
			$types = JenisNilai::with('prodi') -> where('id', '>', 1) -> orderBy('prodi_id')  -> orderBy('id') -> get();
			return view('jenisnilai.index', compact('types'));
		}
		
		
		public function edit($id)
		{
			$jenis = JenisNilai::find($id);
			
			$prodi = $this -> getProdiSelection();
			
			return view('jenisnilai.edit', compact('jenis', 'prodi'));
		}
		
		public function update(Request $request, $id)
		{
			$input = $request -> except('_method');
			JenisNilai::find($id) -> update($input);
			
			return Redirect::route('jenisnilai.index') -> with('message', 'Data berhasil diperbarui.');
		}
		
		public function destroy($id)
		{
			JenisNilai::find($id) -> delete();
			
			return Redirect::route('jenisnilai.index') -> with('message', 'Data berhasil dihapus.');
		}
		
		public function create()
		{
			$prodi = $this -> getProdiSelection();
			return view('jenisnilai.create', compact('prodi'));
		}
		
		
		public function store(Request $request)
		{
			$this -> validate($request, $this->rules);
			
			$input = $request -> all();
			JenisNilai::create($input);
			
			return Redirect::route('jenisnilai.index') -> with('message', 'Data berhasil dimasukkan');
		}
		
	/* 
	public function checkBobotNilai($matkul_tapel_id, $jenis_nilai_id)
	{
	$b = JenisNilai::whereId($jenis_nilai_id)->pluck('bobot');
	$ag = \DB::select('
	SELECT 
	SUM(`bobot`) AS aggregate 
	FROM `jenis_nilai` 
	WHERE `id` IN(
	SELECT DISTINCT `nilai`.`jenis_nilai_id` 
	FROM `nilai` 
	WHERE `nilai`.`matkul_tapel_id` = :id AND `nilai`.`jenis_nilai_id` <> 0
	);', 
	['id' => $matkul_tapel_id]);
	if(!isset($ag[0] -> aggregate) or $ag[0] -> aggregate == null) return 0; else return intval($ag[0] -> aggregate) + $b;
	} */
	
	/* 		public function useExistingType($matkul_tapel_id, $jenis_nilai_id)
	{
	$bobot = $this -> checkBobotNilai($matkul_tapel_id, $jenis_nilai_id);
	if($bobot > 100) return Redirect::route('matkul.tapel.nilai', $matkul_tapel_id) -> withErrors('Total bobot nilai harus = 100%');
	
	$anggota = \DB::select('SELECT DISTINCT `mahasiswa_id` FROM `nilai` WHERE `matkul_tapel_id` = :id', ['id' => $matkul_tapel_id]);
	
	if( \Siakad\Nilai::where('jenis_nilai_id', $jenis_nilai_id)
	-> where('matkul_tapel_id', $matkul_tapel_id)
	-> where('mahasiswa_id', $anggota[0] -> mahasiswa_id)
	-> exists()
	)
	{
	return Redirect::route('matkul.tapel.nilai', $matkul_tapel_id) -> withErrors('Data nilai sudah terdaftar');
	}
	
	foreach($anggota as $a)
	{
	$data[] = ['jenis_nilai_id' => $jenis_nilai_id, 'matkul_tapel_id' => $matkul_tapel_id, 'mahasiswa_id' => $a -> mahasiswa_id, 'nilai' => null, 'created_at' => date('Y-m-d h:i:s'), 'updated_at' => date('Y-m-d h:i:s')];	
	}
	\Siakad\Nilai::insert($data);
	return Redirect::route('matkul.tapel.nilai', $matkul_tapel_id) -> with('message', 'Data berhasil dimasukkan');
	} */
	
	}
		