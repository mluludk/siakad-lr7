<?php
	
	namespace Siakad\Http\Controllers;
	
	use Redirect;
	use Illuminate\Http\Request;
	
	
	use Siakad\Krs;
	use Siakad\Nilai;
	use Siakad\KrsDetail;
	//use Siakad\Http\Requests;
	use Siakad\Http\Controllers\Controller;
	
	class KrsDetailController extends Controller
	{		
		public function destroy($krs_id, $matkul_tapel_id)
		{
			$krs = Krs::find($krs_id);
			
			if($matkul_tapel_id > 0)
			{
				KrsDetail::where('krs_id', $krs_id) -> where('matkul_tapel_id', $matkul_tapel_id) -> delete();
				Nilai::where('mahasiswa_id', $krs -> mahasiswa_id) -> where('matkul_tapel_id', $matkul_tapel_id) -> delete();
				return Redirect::back() -> with('message', 'Mata Kuliah berhasil dihapus.');
			}
			// else
			// {
			// KrsDetail::where('krs_id', $krs_id) -> where('matkul_tapel_id', $matkul_tapel_id) -> delete();
			// }
			return Redirect::back() -> with('warning', 'Mata Kuliah gagal dihapus.');
			
		}
	}
