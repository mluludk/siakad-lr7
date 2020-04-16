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
		protected $icons = [
		'pdf' => 'fa-file-pdf-o', 'docx' => 'fa-file-word-o', 'doc' => 'fa-file-word-o', 
		'xls' => 'fa-file-excel-o', 'xlsx' => 'fa-file-excel-o', 'pptx' => 'fa-file-powerpoint-o', 
		'ppt' => 'fa-file-powerpoint-o', 'mp4' => 'fa-file-video-o', 'ogg' => 'fa-file-video-o'
		];
		protected $media_type = ['gambar', 'dokumen', 'video'];
		
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
			return Redirect::route('matkul.tapel.sesi.kegiatan.index', [$kelas -> id, $sesi->id]) -> with('success', 'Data Materi berhasil dimasukkan.');
		}
		
		/**
			* Show the form for editing the specified resource.
			*
			* @param  int  $id
			* @return \Illuminate\Http\Response
		*/
		public function edit(MatkulTapel $kelas, SesiPembelajaran $sesi, Kegiatan $kegiatan)
		{
			$jenis_id = $kegiatan -> jenis;
			$jenis = $this -> jenis[$jenis_id];
			$media = [];
			$icons = $this -> icons;
			$media_type = $this -> media_type;
			
			foreach($media_type as $t)
			{
				if(isset($kegiatan -> isi[$t]))
				{
					foreach($kegiatan -> isi[$t] as $g)
					{
						$file = FileEntry::find($g);
						if($file) $media[$t][] = [
						'id' => $file -> id,
						'fullpath' => $file -> namafile,
						'filename' => $file -> nama,
						'mime' => $file -> mime
						];
					}
				}
			}
			
			return view('matkul.tapel.sesi.kegiatan.edit', compact('sesi', 'kelas', 'kegiatan', 'jenis', 'jenis_id', 'icons', 'media'));
		}
		
		/**
			* Update the specified resource in storage.
			*
			// * @param  \Illuminate\Http\Request  $request
			* @param  int  $id
			* @return \Illuminate\Http\Response
		*/
		public function update(Request $request, MatkulTapel $kelas, SesiPembelajaran $sesi, Kegiatan $kegiatan)
		{
			// $this -> validate($request, $this -> rules);
			$input = $request -> except('_method', 'files');
			$kegiatan -> update($input);			
			return Redirect::route('matkul.tapel.sesi.kegiatan.index', [$kelas -> id, $sesi->id]) -> with('success', 'Data Materi berhasil diperbarui.');
		}
		
		/**
			* Remove the specified resource from storage.
			*
			* @param  int  $id
			* @return \Illuminate\Http\Response
		*/
		public function destroy(MatkulTapel $kelas, SesiPembelajaran $sesi, Kegiatan $kegiatan)
		{
			$kegiatan -> delete();			
			return Redirect::route('matkul.tapel.sesi.kegiatan.index', [$kelas -> id, $sesi->id]) -> with('success', 'Data Materi berhasil dihapus.');
		}
		
		public function show(MatkulTapel $kelas, SesiPembelajaran $sesi, Kegiatan $kegiatan)
		{
			$media = [];
			$icons = $this -> icons;
			$media_type = $this -> media_type;
			
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
