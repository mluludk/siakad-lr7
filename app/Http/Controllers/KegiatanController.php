<?php
	
	namespace Siakad\Http\Controllers;
	
	use Redirect;
	use Illuminate\Http\Request;
	
	use Siakad\MatkulTapel;
	use Siakad\SesiPembelajaran;
	use Siakad\Kegiatan;
	use Siakad\FileEntry;
	
	use Siakad\Http\Controllers\Controller;
	
	class KegiatanController extends Controller
	{
		
		protected $jenis = [1 => 'Materi', 2 => 'Quiz', 3 => 'Tugas', 4 => 'Video Conference'];
		
		public function index(MatkulTapel $kelas, SesiPembelajaran $sesi)
		{
			$kegiatan =Kegiatan::where('sesi_pembelajaran_id', $sesi -> id) -> orderBy('urutan') -> get();
			
			$jenis = $this -> jenis;
			return view('matkul.tapel.sesi.kegiatan.index', compact('kelas', 'sesi', 'kegiatan', 'jenis'));
		}
		
		/**
			* Show the form for creating a new resource.
			*
			* @return \Illuminate\Http\Response
		*/
		public function create(MatkulTapel $kelas, SesiPembelajaran $sesi, $jenis_id)
		{
			$jenis = $this -> jenis[$jenis_id];
			return view('matkul.tapel.sesi.kegiatan.create',  compact('kelas', 'sesi', 'jenis', 'jenis_id'));
		}
		
		/**
			* Store a newly created resource in storage.
			*
			// * @param  \Illuminate\Http\Request  $request
			* @return \Illuminate\Http\Response
		*/
		public function store(Request $request, MatkulTapel $kelas, SesiPembelajaran $sesi, $jenis_id)
		{
			$input = $request -> except('_token', 'files');
			
			$input['jenis'] = $jenis_id;
			$input['sesi_pembelajaran_id'] = $sesi -> id;
			Kegiatan::create($input);			
			return Redirect::route('matkul.tapel.sesi.kegiatan.index', [$kelas -> id, $sesi->id]) -> with('success', 'Data Sesi Pembelajaran berhasil dimasukkan.');
		}
		
		/**
			* Show the form for editing the specified resource.
			*
			* @param  int  $id
			* @return \Illuminate\Http\Response
		*/
		public function edit(MatkulTapel $kelas, SesiPembelajaran $sesi)
		{
			$hari = config('custom.hari');
			$jadwal = $kelas -> jadwal[0];
			return view('matkul.tapel.sesi.kegiatan.edit', compact('sesi', 'kelas', 'hari', 'jadwal'));
		}
		
		/**
			* Update the specified resource in storage.
			*
			// * @param  \Illuminate\Http\Request  $request
			* @param  int  $id
			* @return \Illuminate\Http\Response
		*/
		public function update(Request $request, MatkulTapel $kelas, SesiPembelajaran $sesi)
		{
			$this -> validate($request, $this -> rules);
			$input = $request -> except('_method', 'files');
			$sesi -> update($input);			
			return Redirect::route('matkul.tapel.sesi.kegiatan.index', $kelas -> id) -> with('success', 'Data Sesi Pembelajaran berhasil diperbarui.');
		}
		
		/**
			* Remove the specified resource from storage.
			*
			* @param  int  $id
			* @return \Illuminate\Http\Response
		*/
		public function destroy(MatkulTapel $kelas, SesiPembelajaran $sesi)
		{
			$sesi -> delete();			
			return Redirect::route('matkul.tapel.sesi.kegiatan.index', $kelas -> id) -> with('success', 'Data Sesi Pembelajaran berhasil dihapus.');
		}
		
		public function show(MatkulTapel $kelas, SesiPembelajaran $sesi, Kegiatan $kegiatan)
		{
		$icons = [
			'pdf' => 'fa-file-pdf-o', 'docx' => 'fa-file-word-o', 'doc' => 'fa-file-word-o', 
			'xls' => 'fa-file-excel-o', 'xlsx' => 'fa-file-excel-o', 'pptx' => 'fa-file-powerpoint-o', 
			'ppt' => 'fa-file-powerpoint-o', 'mp4' => 'fa-file-video-o', 'ogg' => 'fa-file-video-o'
			];
			$media = [];
			$media_type = ['gambar', 'dokumen', 'video'];
			
			foreach($media_type as $t)
			{
				if(isset($kegiatan -> isi[$t]))
				{
					foreach($kegiatan -> isi[$t] as $g)
					{
						$file = FileEntry::find($g);
						if($file) $media[$t][] = [
						'fullpath' => $file -> namafile,
						'filename' => $file -> nama,
						'mime' => $file -> mime
						];
					}
				}
			}
			
			return view('matkul.tapel.sesi.kegiatan.show', compact('sesi', 'kelas', 'kegiatan', 'media', 'icons'));			
			}
		}
		