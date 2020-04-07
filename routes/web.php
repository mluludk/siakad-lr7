<?php
	
	use Illuminate\Support\Facades\Route;
	
	/*
		|--------------------------------------------------------------------------
		| Web Routes
		|--------------------------------------------------------------------------
		|
		| Here is where you can register web routes for your application. These
		| routes are loaded by the RouteServiceProvider within a group which
		| contains the "web" middleware group. Now create something great!
		|
	*/
	
	Auth::routes();
	
	// Route::get('/home', 'HomeController@index')->name('home');
	
	
	// Event::listen('illuminate.query', function($sql){echo '<pre>'; var_dump($sql); echo '</pre>'; });
	
	//WS FEEDER DEBUGGER
	Route::get(
	'/ws/feeder/debug', [
	'uses' => 'FeederSyncController@wsDebugger'
	]
	);	
	
	//WS BRIS
	Route::get(
	'/v1/apiDataTagihanMhs/{nim}/{api_key}', [
	'as' => 'ws.bris.tagihan',
	'uses' => 'WSBRISController@getTagihanMhs'
	]
	);
	Route::get(
	'/v1/apiUpdate/{nim}/{ref}/{id_tagihan}/{password?}', [
	'as' => 'ws.bris.update',
	'uses' => 'WSBRISController@update'
	]
	);
	
	Route::get(
	'/about', function() {
		return view('about');
	}
	);
	
	Route::get(
	'/password/reset/{username}/{reset_token}', [
	'uses' => 'UsersController@getResetPassword'
	]
	);
	Route::post(
	'/password/reset', 
	'UsersController@postResetPassword'
	);
	
	Route::get(
	'/password/username', [
	'as' => 'password.username',
	'uses' => 'UsersController@getUsername'
	]
	);
	Route::post(
	'/password/username', 
	'UsersController@postUsername'
	);
	
	Route::post('/upload/image', 'UploadController@storeImage');
	Route::get(
	'/getimage/{year}/{month}/{file}', 
	'UploadController@getImage'
	);
	
	Route::get('getfile/{year}/{month}/{date}/{file}', [
	'as' => 'getfile', 
	'uses' => 'FileEntryController@getFile'
	]);
	
	//directly
	Route::get('file/{year}/{month}/{file}', [
	'as' => 'getfile.direct', 
	'uses' => 'FileEntryController@getFileDirect'
	]);
	
	//FORMULIR PMB
	Route::get('/pmb/formulir', 
	'PmbPesertaController@create'
	);
	Route::post('/pmb/formulir', [
	'as' => 'pmb.peserta.store',
	'uses' =>'PmbPesertaController@store'
	]);
	Route::get('/pmb/print/{type}', [
	'as' => 'pmb.peserta.print.dialog',
	'uses' =>'PmbPesertaController@dialog'
	]);
	Route::get('/pmb/print/{type}/{kode}', [
	'as' => 'pmb.peserta.print',
	'uses' =>'PmbPesertaController@printing'
	]);
	/* 
		Route::get('/pmb/kartu/{kode}/print', [
		'as' => 'pmb.peserta.kartu.print',
		'uses' =>'PmbPesertaController@printCard'
		]); 
	*/
	Route::get('/pmb/formulir/{kode}', [
	'as' => 'pmb.peserta.stored',
	'uses' =>'PmbPesertaController@stored'
	]);
	
	// kalender akademik
	Route::get('/kalenderakademik/{tahun?}', [
	'as' => 'kalender.public',
	'uses' => 'KalenderController@index'
	]);
	Route::get('/kalenderakademik2/{tahun?}', [
	'as' => 'kalender.public2',
	'uses' => 'KalenderController@index2'
	]);	
	
	Route::get('info/{id}',[
	'as' => 'informasi.public', 
	'uses' =>'InformasiController@publicShow'
	]);
	
	Route::get('download/{id}/{token}', [
	'uses' => 'FileEntryController@download'
	]);
	
	Route::group(['middleware' => ['auth', 'maintenis']], function(){
		
		//MAIL	
		Route::get('/mail', [
		'as' => 'mail.index',
		'uses' => 'MailController@index'
		]);
		Route::get('/mail/compose', [
		'as' => 'mail.create',
		'uses' => 'MailController@create'
		]);
		Route::post('/mail/compose', [
		'as' => 'mail.store',
		'uses' => 'MailController@store'
		]);
		Route::get('/mail/read/{title}_{id}', [
		'as' => 'mail.read',
		'uses' => 'MailController@show'
		]);
		
		Route::get('/patch/get/{version}/{token}', [
		'as' => 'patch.process',
		'roles' => ['administrator'],
		'uses' => 'PatchController@process'
		]);
		Route::get('/patch', [
		'as' => 'patch',
		'roles' => ['administrator'],
		'uses' => 'PatchController@check'
		]);
		Route::get('/patch/check', [
		'as' => 'patch.check',
		'roles' => ['administrator'],
		'uses' => 'PatchController@check'
		]);
		Route::get('/patch/create', [
		'as' => 'patch.create',
		'roles' => ['root'],
		'uses' => 'PatchController@create'
		]);
		
		Route::get('/users/stop', [
		'as' => 'users.impersonate.stop',
		'roles' => ['mahasiswa', 'dosen'],
		'uses' => 'UsersController@stopImpersonate'
		]);
		
		Route::get('ganti-pass', ['as' => 'password.change', 'uses' => 'UsersController@changePassword']);
		Route::patch('ganti-pass/{user_id}', ['as' => 'password.update', 'uses' => 'UsersController@updatePassword']);
		
		Route::get('/profil', [
		'as' => 'user.profile',
		'roles' => ['administrator', 'dosen', 'mahasiswa'],
		'uses' => 'UsersController@myProfile'
		]);
		Route::get('/profil/edit', [
		'as' => 'user.profile.edit',
		'roles' => ['administrator', 'dosen', 'mahasiswa'],
		'uses' => 'UsersController@myProfileEdit'
		]);
		Route::patch('/profil', [
		'as' => 'user.profile.update',
		'roles' => ['administrator', 'dosen', 'mahasiswa'],
		'uses' => 'UsersController@myProfileUpdate'
		]);	
		
		//FIX AKM
		Route::get(
		'/mahasiswa/akm/hitungulang/{prodi?}/{tapel?}', [
		'roles' => ['administrator'],
		'uses' => 'AktivitasController@hitungUlangAKMMhsAktif'
		]
		);
		
		//FIX Tagihan
		Route::get(
		'/tagihan/fix/nama', [
		'roles' => ['administrator'],
		'uses' => 'TagihanController@fixNama'
		]
		);		
		Route::get(
		'/tagihan/fix/duplicate/{angkatan}', [
		'roles' => ['administrator'],
		'uses' => 'TagihanController@fixDuplicate'
		]
		); 
	});
	
	Route::group(['middleware' => ['auth', 'roles', 'profil', 'kuesioner', 'maintenis', 'nilai']], function()
	{	
		Route::get('logs', [
		'as' => 'logs',
		'roles' => ['administrator'],
		'uses' => '\Rap2hpoutre\LaravelLogViewer\LogViewerController@index'
		]);
		
		Route::get('/', ['as' => 'root', 'uses' => 'HomeController@index']);
		Route::get('home', ['as' => 'root', 'uses' => 'HomeController@index']);	
		
		Route::get('/krs', [
		'as' => 'krs.index',
		'roles' => ['mahasiswa'],
		'uses' => 'KrsController@index'
		]);
		Route::get('/krs/print', [
		'as' => 'krs.print',
		'roles' => ['mahasiswa'],
		'uses' => 'KrsController@printKrs'
		]);
		Route::get('/tawaran/{mahasiswa_id?}', [
		'as' => 'krs.create',
		'roles' => ['mahasiswa', 'administrator', 'prodi'],
		'uses' => 'KrsController@create'
		]);
		Route::post('/tawaran', [
		'as' => 'krs.store',
		'roles' => ['mahasiswa', 'administrator', 'prodi'],
		'uses' => 'KrsController@store'
		]);		
		Route::delete('/krs/', [
		'as' => 'krs.destroy',
		'roles' => ['mahasiswa'],
		'uses' => 'KrsController@destroy'
		]);	
		
		/*
			* Mahasiswa
		*/
		Route::get('/khs/cetak/{ta?}', [
		'as' => 'printmykhs',
		'roles' => ['mahasiswa'], 
		'uses' =>'MahasiswaController@printMyKhs'
		]);
		Route::get('/khs/{ta?}', [
		'as' => 'viewmykhs',
		'roles' => ['mahasiswa'], 
		'uses' =>'MahasiswaController@viewMyKhs'
		]);
		
		Route::get('/jadwalmahasiswa', [
		'roles' => ['mahasiswa'], 
		'uses' =>'JadwalController@mahasiswa'
		]);
		
	});
	
	Route::group(['middleware' => ['web', 'auth', 'roles', 'maintenis', 'profil', 'nilai']], function()
	{	
		//ZOOM
		Route::get('/matkul/tapel/{matkul_tapel_id}/meeting', [
		'as' => 'matkul.tapel.meeting',
		'roles' => ['administrator', 'dosen'],
		'uses' => 'MatkulTapelMeetingController@index'
		]);
		Route::get('/matkul/tapel/{matkul_tapel_id}/meeting/create', [
		'as' => 'matkul.tapel.meeting.create',
		'roles' => ['administrator', 'dosen'],
		'uses' => 'MatkulTapelMeetingController@create'
		]);
		Route::post('/matkul/tapel/{matkul_tapel_id}/meeting', [
		'as' => 'matkul.tapel.meeting.store',
		'roles' => ['administrator', 'dosen'],
		'uses' => 'MatkulTapelMeetingController@store'
		]);
		Route::get('/meeting/{meeting}/start', [
		'as' => 'meeting.start',
		'roles' => ['administrator', 'dosen'],
		'uses' => 'MatkulTapelMeetingController@start'
		]);
		
		// Route::get('/zoom/meeting/create', [
		// 'as' => 'zoom.meeting.create',
		// 'roles' => ['administrator', 'dosen'],
		// 'uses' => 'ZoomController@createMeeting'
		// ]);
		// Route::get('/zoom/user/create', [
		// 'as' => 'zoom.user.create',
		// 'roles' => ['administrator', 'dosen'],
		// 'uses' => 'ZoomController@createUser'
		// ]);
		// Route::get('/zoom/user/list', [
		// 'as' => 'zoom.user.list',
		// 'roles' => ['administrator', 'dosen'],
		// 'uses' => 'ZoomController@listAllUsers'
		// ]);
		
		// Validasi krs
		Route::get('/validasi/krs/', [
		'as' => 'krs.validasi',
		'roles' => ['administrator', 'dosen'],
		'uses' => 'KrsController@validasiKrs'
		]);
		
		Route::post('/validasi/krs/', [
		'as' => 'krs.validasi.post',
		'roles' => ['administrator', 'dosen'],
		'uses' => 'KrsController@validasiKrsPost'
		]);	
		
		//KRS
		Route::get('/mahasiswa/{nim}/krs/{action?}/{tapel?}', [
		'as' => 'mahasiswa.krs',
		'roles' => ['administrator', 'akademik', 'dosen', 'prodi'],
		'uses' => 'KrsController@adminKrs'
		]);	
		
		//Tulisan Mahasiswa		
		Route::get('/mahasiswa/tulisan', [
		'as' => 'mahasiswa.tulisan.index',
		'roles' => ['administrator', 'akademik'],
		'uses' => 'MahasiswaTulisanController@index'
		]);
		
		Route::get('/mahasiswa/{mahasiswa}/tulisan', [
		'as' => 'mahasiswa.tulisan.daftar',
		'roles' => ['administrator', 'akademik', 'mahasiswa', 'prodi'],
		'uses' => 'MahasiswaTulisanController@daftar'
		]);
		Route::get('/mahasiswa/{mahasiswa}/tulisan/create', [
		'as' => 'mahasiswa.tulisan.create',
		'roles' => ['administrator', 'akademik', 'mahasiswa'],
		'uses' => 'MahasiswaTulisanController@create'
		]);
		Route::post('/mahasiswa/{mahasiswa}/tulisan', [
		'as' => 'mahasiswa.tulisan.store',
		'roles' => ['administrator', 'akademik', 'mahasiswa'],
		'uses' => 'MahasiswaTulisanController@store'
		]);
		Route::get('/mahasiswa/tulisan/{tulisan}/edit', [
		'as' => 'mahasiswa.tulisan.edit',
		'roles' => ['administrator', 'akademik', 'mahasiswa'],
		'uses' => 'MahasiswaTulisanController@edit'
		]);		
		Route::patch('/mahasiswa/tulisan/{tulisan}', [
		'as' => 'mahasiswa.tulisan.update',
		'roles' => ['administrator', 'akademik', 'mahasiswa'],
		'uses' => 'MahasiswaTulisanController@update'
		]);	
		
		Route::get('/mahasiswa/tulisan/export', [
		'as' => 'mahasiswa.tulisan.export',
		'roles' => ['administrator', 'akademik'],
		'uses' => 'MahasiswaTulisanController@export'
		]);
		
		Route::get('/mahasiswa/tulisan/{tulisan}/delete', [
		'as' => 'mahasiswa.tulisan.delete',
		'roles' => ['administrator', 'akademik', 'mahasiswa'],
		'uses' => 'MahasiswaTulisanController@destroy'
		]);
		
		//Penelitian Mahasiswa		
		Route::get('/mahasiswa/penelitian', [
		'as' => 'mahasiswa.penelitian.index',
		'roles' => ['administrator', 'akademik'],
		'uses' => 'MahasiswaPenelitianController@index'
		]);
		
		Route::get('/mahasiswa/{mahasiswa}/penelitian', [
		'as' => 'mahasiswa.penelitian.daftar',
		'roles' => ['administrator', 'akademik', 'mahasiswa', 'prodi'],
		'uses' => 'MahasiswaPenelitianController@daftar'
		]);
		Route::get('/mahasiswa/{mahasiswa}/penelitian/create', [
		'as' => 'mahasiswa.penelitian.create',
		'roles' => ['administrator', 'akademik', 'mahasiswa'],
		'uses' => 'MahasiswaPenelitianController@create'
		]);
		Route::post('/mahasiswa/{mahasiswa}/penelitian', [
		'as' => 'mahasiswa.penelitian.store',
		'roles' => ['administrator', 'akademik', 'mahasiswa'],
		'uses' => 'MahasiswaPenelitianController@store'
		]);
		Route::get('/mahasiswa/penelitian/{penelitian}/edit', [
		'as' => 'mahasiswa.penelitian.edit',
		'roles' => ['administrator', 'akademik', 'mahasiswa'],
		'uses' => 'MahasiswaPenelitianController@edit'
		]);		
		Route::patch('/mahasiswa/penelitian/{penelitian}', [
		'as' => 'mahasiswa.penelitian.update',
		'roles' => ['administrator', 'akademik', 'mahasiswa'],
		'uses' => 'MahasiswaPenelitianController@update'
		]);	
		
		Route::get('/mahasiswa/penelitian/export', [
		'as' => 'mahasiswa.penelitian.export',
		'roles' => ['administrator', 'akademik'],
		'uses' => 'MahasiswaPenelitianController@export'
		]);
		
		Route::get('/mahasiswa/penelitian/{penelitian}/delete', [
		'as' => 'mahasiswa.penelitian.delete',
		'roles' => ['administrator', 'akademik', 'mahasiswa'],
		'uses' => 'MahasiswaPenelitianController@destroy'
		]);
		
		//Jurnal Mahasiswa		
		Route::get('/mahasiswa/jurnal', [
		'as' => 'mahasiswa.jurnal.index',
		'roles' => ['administrator', 'akademik'],
		'uses' => 'MahasiswaJurnalController@index'
		]);
		
		Route::get('/mahasiswa/{mahasiswa}/jurnal', [
		'as' => 'mahasiswa.jurnal.daftar',
		'roles' => ['administrator', 'akademik', 'mahasiswa', 'prodi'],
		'uses' => 'MahasiswaJurnalController@daftar'
		]);
		Route::get('/mahasiswa/{mahasiswa}/jurnal/create', [
		'as' => 'mahasiswa.jurnal.create',
		'roles' => ['administrator', 'akademik', 'mahasiswa'],
		'uses' => 'MahasiswaJurnalController@create'
		]);
		Route::post('/mahasiswa/{mahasiswa}/jurnal', [
		'as' => 'mahasiswa.jurnal.store',
		'roles' => ['administrator', 'akademik', 'mahasiswa'],
		'uses' => 'MahasiswaJurnalController@store'
		]);
		Route::get('/mahasiswa/jurnal/{jurnal}/edit', [
		'as' => 'mahasiswa.jurnal.edit',
		'roles' => ['administrator', 'akademik', 'mahasiswa'],
		'uses' => 'MahasiswaJurnalController@edit'
		]);		
		Route::patch('/mahasiswa/jurnal/{jurnal}', [
		'as' => 'mahasiswa.jurnal.update',
		'roles' => ['administrator', 'akademik', 'mahasiswa'],
		'uses' => 'MahasiswaJurnalController@update'
		]);	
		
		Route::get('/mahasiswa/jurnal/export', [
		'as' => 'mahasiswa.jurnal.export',
		'roles' => ['administrator', 'akademik'],
		'uses' => 'MahasiswaJurnalController@export'
		]);
		
		Route::get('/mahasiswa/jurnal/{jurnal}/delete', [
		'as' => 'mahasiswa.jurnal.delete',
		'roles' => ['administrator', 'akademik', 'mahasiswa'],
		'uses' => 'MahasiswaJurnalController@destroy'
		]);
		
		//Buku Mahasiswa		
		Route::get('/mahasiswa/buku', [
		'as' => 'mahasiswa.buku.index',
		'roles' => ['administrator', 'akademik'],
		'uses' => 'MahasiswaBukuController@index'
		]);
		
		Route::get('/mahasiswa/{mahasiswa}/buku', [
		'as' => 'mahasiswa.buku.daftar',
		'roles' => ['administrator', 'akademik', 'mahasiswa', 'prodi'],
		'uses' => 'MahasiswaBukuController@daftar'
		]);
		Route::get('/mahasiswa/{mahasiswa}/buku/create', [
		'as' => 'mahasiswa.buku.create',
		'roles' => ['administrator', 'akademik', 'mahasiswa'],
		'uses' => 'MahasiswaBukuController@create'
		]);
		Route::post('/mahasiswa/{mahasiswa}/buku', [
		'as' => 'mahasiswa.buku.store',
		'roles' => ['administrator', 'akademik', 'mahasiswa'],
		'uses' => 'MahasiswaBukuController@store'
		]);
		Route::get('/mahasiswa/buku/{buku}/edit', [
		'as' => 'mahasiswa.buku.edit',
		'roles' => ['administrator', 'akademik', 'mahasiswa'],
		'uses' => 'MahasiswaBukuController@edit'
		]);		
		Route::patch('/mahasiswa/buku/{buku}', [
		'as' => 'mahasiswa.buku.update',
		'roles' => ['administrator', 'akademik', 'mahasiswa'],
		'uses' => 'MahasiswaBukuController@update'
		]);	
		
		Route::get('/mahasiswa/buku/export', [
		'as' => 'mahasiswa.buku.export',
		'roles' => ['administrator', 'akademik'],
		'uses' => 'MahasiswaBukuController@export'
		]);
		
		Route::get('/mahasiswa/buku/{buku}/delete', [
		'as' => 'mahasiswa.buku.delete',
		'roles' => ['administrator', 'akademik', 'mahasiswa'],
		'uses' => 'MahasiswaBukuController@destroy'
		]);
		
		//BANK
		Route::get('/bank', [
		'as' => 'bank.index',
		'roles' => ['administrator', 'prodi'],
		'uses' =>'BankController@index'
		]);
		Route::get('/bank/create', [
		'as' => 'bank.create',
		'roles' => ['administrator'],
		'uses' =>'BankController@create'
		]);
		Route::post('/bank/create', [
		'as' => 'bank.store',
		'roles' => ['administrator'],
		'uses' =>'BankController@store'
		]);
		Route::get('/bank/{bank}/detail', [
		'as' => 'bank.detail',
		'roles' => ['administrator', 'prodi'],
		'uses' =>'BankController@detail'
		]);
		Route::get('/bank/{bank}/delete', [
		'as' => 'bank.delete',
		'roles' => ['administrator'],
		'uses' =>'BankController@destroy'
		]);
		Route::get('/bank/{bank}/edit', [
		'as' => 'bank.edit',
		'roles' => ['administrator'],
		'uses' =>'BankController@edit'
		]);
		Route::patch('/bank/{bank}', [
		'as' => 'bank.update',
		'roles' => ['administrator'],
		'uses' =>'BankController@update'
		]);
		
		/* WS FEEDER */
		//AKM
		Route::get('/export/feeder/akm', [
		'as' => 'export.feeder.akm.get',
		'roles' => ['administrator', 'akademik'],
		'uses' => 'FeederSyncController@getAKM'
		]);	
		Route::post('/export/feeder/akm', [
		'as' => 'export.feeder.akm.post',
		'roles' => ['administrator', 'akademik'],
		'uses' => 'FeederSyncController@postAKM'
		]);	
		
		//RIWAYAT
		Route::get('/update/feeder/riwayat', [
		'as' => 'update.feeder.riwayat.get',
		'roles' => ['administrator', 'akademik'],
		'uses' => 'FeederSyncController@getRiwayatPendidikan'
		]);	
		Route::post('/update/feeder/riwayat', [
		'as' => 'update.feeder.riwayat.post',
		'roles' => ['administrator', 'akademik'],
		'uses' => 'FeederSyncController@postRiwayatPendidikan'
		]);	
		
		//SKALA
		Route::get('/export/feeder/skala', [
		'as' => 'export.feeder.skala.get',
		'roles' => ['administrator', 'akademik'],
		'uses' => 'FeederSyncController@getSkala'
		]);	
		Route::post('/export/feeder/skala', [
		'as' => 'export.feeder.skala.post',
		'roles' => ['administrator', 'akademik'],
		'uses' => 'FeederSyncController@postSkala'
		]);		
		
		//PERIODE
		Route::get('/export/feeder/periode', [
		'as' => 'export.feeder.periode.get',
		'roles' => ['administrator', 'akademik'],
		'uses' => 'FeederSyncController@getPeriode'
		]);	
		Route::post('/export/feeder/periode', [
		'as' => 'export.feeder.periode.post',
		'roles' => ['administrator', 'akademik'],
		'uses' => 'FeederSyncController@postPeriode'
		]);	
		
		//FEEDER		
		Route::get('/sync/feeder/mahasiswa', [
		'as' => 'sync.feeder',
		'roles' => ['administrator', 'akademik'],
		'uses' => 'FeederSyncController@syncFeeder'
		]);		
		Route::any('/feeder/mahasiswa/delete', [
		'as' => 'delete.feeder.mahasiswa',
		'roles' => ['administrator', 'akademik'],
		'uses' => 'FeederSyncController@feederMahasiswaDelete'
		]);		
		Route::get('/export/feeder/mahasiswa', [
		'as' => 'export.feeder.mahasiswa.get',
		'roles' => ['administrator', 'akademik'],
		'uses' => 'FeederSyncController@getExportFeeder'
		]);	
		Route::post('/export/feeder/mahasiswa', [
		'as' => 'export.feeder.mahasiswa.post',
		'roles' => ['administrator', 'akademik'],
		'uses' => 'FeederSyncController@postExportFeeder'
		]);
		
		//PRESTASI
		Route::get('/export/feeder/prestasi', [
		'as' => 'export.feeder.prestasi.get',
		'roles' => ['administrator', 'akademik'],
		'uses' => 'FeederSyncController@getPrestasi'
		]);	
		Route::post('/export/feeder/prestasi', [
		'as' => 'export.feeder.prestasi.post',
		'roles' => ['administrator', 'akademik'],
		'uses' => 'FeederSyncController@postPrestasi'
		]);	
		
		//KELAS KULIAH	
		Route::any('/feeder/kelaskuliah/delete', [
		'as' => 'delete.feeder.kelas',
		'roles' => ['administrator', 'akademik'],
		'uses' => 'FeederSyncController@feederKelasDelete'
		]);
		Route::get('/export/feeder/kelaskuliah', [
		'as' => 'export.feeder.kelaskuliah.get',
		'roles' => ['administrator', 'akademik'],
		'uses' => 'FeederSyncController@getKelasKuliah'
		]);	
		Route::post('/export/feeder/kelaskuliah', [
		'as' => 'export.feeder.kelaskuliah.post',
		'roles' => ['administrator', 'akademik'],
		'uses' => 'FeederSyncController@postKelasKuliah'
		]);	
		Route::get('/sync/feeder/kelaskuliah', [
		'as' => 'sync.feeder.kelaskuliah',
		'roles' => ['administrator', 'akademik'],
		'uses' => 'FeederSyncController@syncKelasKuliah'
		]);	
		
		//KRS	
		Route::any('/feeder/krs/delete', [
		'as' => 'delete.feeder.krs',
		'roles' => ['administrator', 'akademik'],
		'uses' => 'FeederSyncController@feederKrsDelete'
		]);
		Route::get('/export/feeder/krs', [
		'as' => 'export.feeder.krs.get',
		'roles' => ['administrator', 'akademik'],
		'uses' => 'FeederSyncController@getKRS'
		]);	
		Route::post('/export/feeder/krs', [
		'as' => 'export.feeder.krs.post',
		'roles' => ['administrator', 'akademik'],
		'uses' => 'FeederSyncController@postKRS'
		]);	
		
		//NILAI
		Route::get('/delete/feeder/nilaiv1', [
		'as' => 'delete.feeder.nilaiv1',
		'roles' => ['administrator', 'akademik'],
		'uses' => 'FeederSyncController@deleteNilaiV1'
		]);
		Route::get('/export/feeder/nilaiv1', [
		'as' => 'export.feeder.nilaiv1.get',
		'roles' => ['administrator', 'akademik'],
		'uses' => 'FeederSyncController@getNilaiV1'
		]);
		Route::post('/export/feeder/nilaiv1', [
		'as' => 'export.feeder.nilaiv1.post',
		'roles' => ['administrator', 'akademik'],
		'uses' => 'FeederSyncController@postNilaiV1'
		]);
		Route::get('/export/feeder/nilaikelas', [
		'as' => 'export.feeder.nilaikelas.get',
		'roles' => ['administrator', 'akademik'],
		'uses' => 'FeederSyncController@getNilaiKelas'
		]);
		Route::get('/export/feeder/nilai/{mt_id}/{id_kelas}', [
		'as' => 'export.feeder.nilai.get',
		'roles' => ['administrator', 'akademik'],
		'uses' => 'FeederSyncController@getNilai'
		]);
		Route::post('/export/feeder/nilai', [
		'as' => 'export.feeder.nilai.post',
		'roles' => ['administrator', 'akademik'],
		'uses' => 'FeederSyncController@postNilai'
		]);	
		
		//KELULUSAN		
		Route::any('/feeder/kelulusan/delete', [
		'as' => 'delete.feeder.kelulusan',
		'roles' => ['administrator', 'akademik'],
		'uses' => 'FeederSyncController@feederKelulusanDelete'
		]);
		Route::get('/export/feeder/kelulusan', [
		'as' => 'export.feeder.kelulusan.get',
		'roles' => ['administrator', 'akademik'],
		'uses' => 'FeederSyncController@getKelulusan'
		]);	
		Route::post('/export/feeder/kelulusan', [
		'as' => 'export.feeder.kelulusan.post',
		'roles' => ['administrator', 'akademik'],
		'uses' => 'FeederSyncController@postKelulusan'
		]);		
		//Penugasan Mahasiswa e-Tugas
		
		//Login MHS
		Route::get('/tugas',[
		'as' => 'mahasiswa.tugas.index2',
		'roles' => ['mahasiswa'],
		'uses' => 'TugasController@index2'		
		]);
		
		Route::get('/mahasiswa/tugas',[
		'as' => 'mahasiswa.tugas.index',
		'roles' => ['administrator', 'dosen'],
		'uses' => 'TugasController@index'		
		]);
		Route::get('/mahasiswa/tugas/create',[
		'as' => 'mahasiswa.tugas.create',
		'roles' => ['administrator', 'dosen'],
		'uses' => 'TugasController@create'		
		]);
		Route::post('/mahasiswa/tugas',[
		'as' => 'mahasiswa.tugas.store', 
		'roles' => ['administrator', 'dosen'],
		'uses' => 'TugasController@store'		
		]);
		Route::get('/mahasiswa/tugas/{tugas}/edit',[
		'as' => 'mahasiswa.tugas.edit',
		'roles' => ['administrator', 'dosen'],
		'uses' => 'TugasController@edit'		
		]);
		Route::patch('/mahasiswa/tugas/{tugas}',[
		'as' => 'mahasiswa.tugas.update',
		'roles' => ['administrator', 'dosen'],
		'uses' => 'TugasController@update'		
		]);
		Route::get('/mahasiswa/tugas/{tugas}/publish/{type?}',[
		'as' => 'mahasiswa.tugas.publish',
		'roles' => ['administrator', 'dosen'],
		'uses' => 'TugasController@publish'		
		]);
		Route::get('/mahasiswa/tugas/{tugas}/delete',[
		'as' => 'mahasiswa.tugas.delete',
		'roles' => ['administrator', 'dosen'],
		'uses' => 'TugasController@destroy'		
		]);
		
		//Tugas Hasil
		Route::get('/mahasiswa/tugas/{tugas}/hasil',[
		'as' => 'mahasiswa.tugas.hasil.index',
		'roles' => ['administrator', 'dosen'],
		'uses' => 'MahasiswaTugasController@index'		
		]);
		Route::post('/mahasiswa/tugas/{tugas_id}/hasil/{mahasiswa_id}',[
		'as' => 'mahasiswa.tugas.hasil.nilai',
		'roles' => ['administrator', 'dosen'],
		'uses' => 'MahasiswaTugasController@nilai'		
		]);
		Route::get('/mahasiswa/tugas/{tugas_id}/hasil/{mahasiswa_id}',[
		'as' => 'mahasiswa.tugas.hasil.detail',
		'roles' => ['administrator', 'dosen'],
		'uses' => 'MahasiswaTugasController@detail'		
		]);
		Route::get('/mahasiswa/tugas/{tugas_id}/hasil/{mahasiswa_id}/status/{status}',[
		'as' => 'mahasiswa.tugas.hasil.status',
		'roles' => ['administrator', 'dosen'],
		'uses' => 'MahasiswaTugasController@setStatus'		
		]);
		
		//Mahasiswa Tugas Detail
		//Login MHS
		Route::get('/mahasiswa/tugas/{tugas}/detail2/edit',[
		'as' => 'mahasiswa.tugas.detail2.edit',
		'roles' => ['mahasiswa'],
		'uses' => 'MahasiswaTugasDetailController@edit'		
		]);
		Route::get('/mahasiswa/tugas/{tugas}/detail2',[
		'as' => 'mahasiswa.tugas.detail2.index',
		'roles' => ['mahasiswa'],
		'uses' => 'MahasiswaTugasDetailController@index'		
		]);
		Route::post('/mahasiswa/tugas/{tugas}/detail2',[
		'as' => 'mahasiswa.tugas.detail2.store',
		'roles' => ['mahasiswa'],
		'uses' => 'MahasiswaTugasDetailController@store'		
		]);
		Route::get('/mahasiswa/tugas/{tugas_id}/get2/{mahasiswa_tugas_id}/{tugas_detail_id}', [
		'as' => 'mahasiswa.tugas.detail2.get', 
		'uses' => 'MahasiswaTugasDetailController@get'
		]);
		
		//Admin
		Route::get('/mahasiswa/tugas/{tugas}/detail',[
		'as' => 'mahasiswa.tugas.detail.index',
		'roles' => ['administrator', 'dosen'],
		'uses' => 'TugasDetailController@index'		
		]);
		Route::post('/mahasiswa/tugas/{tugas}',[
		'as' => 'mahasiswa.tugas.detail.store',
		'roles' => ['administrator', 'dosen'],
		'uses' => 'TugasDetailController@store'		
		]);
		Route::get('/mahasiswa/tugas/{tugas}/get/{tugas_detail_id}', [
		'as' => 'mahasiswa.tugas.detail.get', 
		'uses' => 'TugasDetailController@get'
		]);
		Route::patch('/mahasiswa/tugas/{tugas}/{tugas_detail_id}', [
		'as' => 'mahasiswa.tugas.detail.update', 
		'uses' => 'TugasDetailController@update'
		]);
		Route::get('/mahasiswa/tugas/{tugas}/edit/{tugas_detail_id}', [
		'as' => 'mahasiswa.tugas.detail.edit', 
		'uses' => 'TugasDetailController@edit'
		]);
		Route::get('/mahasiswa/tugas/{tugas}/delete/{tugas_detail_id}', [
		'as' => 'mahasiswa.tugas.detail.delete', 
		'uses' => 'TugasDetailController@destroy'
		]);
		
		//Peserta Gelombang PENDAFTARAN UJIAN Skripsi		
		Route::any('/ujian/skripsi/{j}/gelombang/{id}/peserta/{mahasiswa_id}/delete', [
		'as' => 'jadwal.ujian.skripsi.gelombang.peserta.delete',
		'roles' => ['administrator', 'akademik', 'prodi'],
		'uses' => 'PesertaUjianSkripsiController@destroy'
		]); 
		
		Route::post('/ujian/skripsi/peserta/edit',[
		'as' => 'jadwal.ujian.skripsi.gelombang.peserta.edit',
		'roles' => ['administrator', 'akademik', 'prodi'],
		'uses' => 'PesertaUjianSkripsiController@edit'		
		]);
		
		Route::get('/ujian/skripsi/{j}/gelombang/{id}/peserta', [
		'as' => 'jadwal.ujian.skripsi.gelombang.peserta.index',
		'roles' => ['administrator', 'akademik', 'prodi'],
		'uses' => 'PesertaUjianSkripsiController@index'
		]);
		
		Route::get('/ujian/skripsi/{j}/gelombang/{id}/peserta/print', [
		'as' => 'jadwal.ujian.skripsi.gelombang.peserta.print',
		'roles' => ['administrator', 'akademik', 'prodi'],
		'uses' => 'PesertaUjianSkripsiController@cetak'
		]);
		
		//Gelombang PENDAFTARAN UJIAN Skripsi
		Route::get('/ujian/skripsi/{j}/gelombang/{id}/create', [
		'as' => 'jadwal.ujian.skripsi.gelombang.create',
		'roles' => ['administrator', 'akademik', 'prodi'],
		'uses' => 'JadwalUjianSkripsiGelombangController@create'
		]);
		Route::post('/ujian/skripsi/{j}/gelombang/{id}', [
		'as' => 'jadwal.ujian.skripsi.gelombang.store',
		'roles' => ['administrator', 'akademik', 'prodi'],
		'uses' => 'JadwalUjianSkripsiGelombangController@store'
		]);
		Route::get('/ujian/skripsi/{j}/gelombang/{id}/edit', [
		'as' => 'jadwal.ujian.skripsi.gelombang.edit',
		'roles' => ['administrator', 'akademik', 'prodi'],
		'uses' => 'JadwalUjianSkripsiGelombangController@edit'
		]);
		Route::patch('/ujian/skripsi/{j}/gelombang/{id}', [
		'as' => 'jadwal.ujian.skripsi.gelombang.update',
		'roles' => ['administrator', 'akademik', 'prodi'],
		'uses' => 'JadwalUjianSkripsiGelombangController@update'
		]);
		Route::get('/ujian/skripsi/{j}/gelombang/{id}/delete', [
		'as' => 'jadwal.ujian.skripsi.gelombang.delete',
		'roles' => ['administrator', 'akademik', 'prodi'],
		'uses' => 'JadwalUjianSkripsiGelombangController@destroy'
		]);
		
		//PENDAFTARAN UJIAN Skripsi
		Route::get('/ujian/skripsi/{j}', [
		'as' => 'jadwal.ujian.skripsi.index',
		'roles' => ['administrator', 'akademik', 'prodi'],
		'uses' => 'JadwalUjianSkripsiController@index'
		]);
		Route::get('/ujian/skripsi/{j}/create', [
		'as' => 'jadwal.ujian.skripsi.create',
		'roles' => ['administrator', 'akademik', 'prodi'],
		'uses' => 'JadwalUjianSkripsiController@create'
		]);
		Route::post('/ujian/skripsi/{j}', [
		'as' => 'jadwal.ujian.skripsi.store',
		'roles' => ['administrator', 'akademik', 'prodi'],
		'uses' => 'JadwalUjianSkripsiController@store'
		]);
		Route::get('/ujian/skripsi/{j}/{id}/edit', [
		'as' => 'jadwal.ujian.skripsi.edit',
		'roles' => ['administrator', 'akademik', 'prodi'],
		'uses' => 'JadwalUjianSkripsiController@edit'
		]);
		Route::patch('/ujian/skripsi/{j}/{id}', [
		'as' => 'jadwal.ujian.skripsi.update',
		'roles' => ['administrator', 'akademik', 'prodi'],
		'uses' => 'JadwalUjianSkripsiController@update'
		]);
		Route::get('/ujian/skripsi/{j}/{id}/delete', [
		'as' => 'jadwal.ujian.skripsi.delete',
		'roles' => ['administrator', 'akademik', 'prodi'],
		'uses' => 'JadwalUjianSkripsiController@destroy'
		]);
		
		//KRS DETAIL 
		Route::get('/krs/{krs_id}/{matkul_tapel_id}/delete', [
		'as' => 'krs.detail.delete',
		'roles' => ['administrator', 'root'],
		'uses' => 'KrsDetailController@destroy'
		]);	
		
		//Impersonate
		Route::get('/users/{id}/impersonate', [
		'as' => 'users.impersonate',
		'roles' => ['administrator', 'root'],
		'uses' => 'UsersController@impersonate'
		]);
		
		//AKM
		Route::get('/mahasiswa/akm/recount2/{prodi_id}/{angkatan}/{tapel_id}', [
		'as' => 'mahasiswa.akm.recount2', 
		'roles' => ['administrator', 'akademik'],
		'uses' =>'AktivitasController@recount2'
		]);
		Route::get('/mahasiswa/akm/recount/{mahasiswa_id}/{tapel_id}', [
		'as' => 'mahasiswa.akm.recount', 
		'roles' => ['administrator', 'akademik'],
		'uses' =>'AktivitasController@recount'
		]);
		Route::get('/mahasiswa/akm', [
		'as' => 'mahasiswa.akm', 
		'roles' => ['administrator', 'akademik'],
		'uses' =>'AktivitasController@index'
		]);
		Route::post('/mahasiswa/akm', [
		'as' => 'mahasiswa.akm.update', 
		'roles' => ['administrator', 'akademik'],
		'uses' =>'AktivitasController@update'
		]);	
		
		
		//Validasi
		Route::get('/validasi', [
		'as' => 'mahasiswa.validasi', 
		'roles' => ['prodi'],
		'uses' =>'MahasiswaController@validasi'
		]);
		Route::post('/validasi', [
		'as' => 'mahasiswa.validasi.post', 
		'roles' => ['prodi'],
		'uses' =>'MahasiswaController@postValidasi'
		]); 
		
		
		//skala Nilai
		Route::get('skala', [
		'as' => 'skala.index', 
		'roles' => ['administrator', 'akademik'],
		'uses' =>'SkalaController@index'
		]);
		Route::get('skala/create', [
		'as' => 'skala.create', 
		'roles' => ['administrator', 'akademik'],
		'uses' =>'SkalaController@create'
		]);
		Route::post('skala', [
		'as' => 'skala.store', 
		'roles' => ['administrator', 'akademik'],
		'uses' =>'SkalaController@store'
		]);
		Route::get('skala/{skala}/edit', [
		'as' => 'skala.edit', 
		'roles' => ['administrator', 'akademik'],
		'uses' =>'SkalaController@edit'
		]);
		Route::patch('skala/{skala}', [
		'as' => 'skala.update', 
		'roles' => ['administrator', 'akademik'],
		'uses' =>'SkalaController@update'
		]);
		
		//Cetak Kartu Ujian Mahasiswa
		Route::get('/mahasiswa/{id?}/kartu/{kartu?}/cetak', [
		'as' => 'mahasiswa.cetak.kartu',
		'roles' => ['administrator', 'akademik', 'prodi', 'mahasiswa'],
		'uses' => 'MahasiswaController@cetakKartu'
		]);
		
		//Prestasi Mahasiswa
		Route::get('/prestasi', [
		'as' => 'mahasiswa.prestasi.personal',
		'roles' => ['mahasiswa'],
		'uses' => 'MahasiswaPrestasiController@prestasi'
		]);
		Route::get('/mahasiswa/{mahasiswa}/prestasi', [
		'as' => 'mahasiswa.prestasi',
		'roles' => ['administrator', 'akademik', 'prodi'],
		'uses' => 'MahasiswaPrestasiController@prestasi'
		]);
		Route::get('/mahasiswa/prestasi', [
		'as' => 'mahasiswa.prestasi.index',
		'roles' => ['administrator', 'akademik'],
		'uses' => 'MahasiswaPrestasiController@index'
		]);
		Route::get('/mahasiswa/{mahasiswa}/prestasi/create', [
		'as' => 'mahasiswa.prestasi.create',
		'roles' => ['administrator', 'akademik', 'mahasiswa'],
		'uses' => 'MahasiswaPrestasiController@create'
		]);
		Route::post('/mahasiswa/{mahasiswa}/prestasi', [
		'as' => 'mahasiswa.prestasi.store',
		'roles' => ['administrator', 'akademik', 'mahasiswa'],
		'uses' => 'MahasiswaPrestasiController@store'
		]);
		Route::get('/mahasiswa/{mahasiswa}/prestasi/{prestasi}/edit', [
		'as' => 'mahasiswa.prestasi.edit',
		'roles' => ['administrator', 'akademik', 'mahasiswa'],
		'uses' => 'MahasiswaPrestasiController@edit'
		]);	
		Route::patch('/mahasiswa/{mahasiswa}/prestasi/{prestasi}', [
		'as' => 'mahasiswa.prestasi.update',
		'roles' => ['administrator', 'akademik', 'mahasiswa'],
		'uses' => 'MahasiswaPrestasiController@update'
		]);	
		Route::get('/mahasiswa/{mahasiswa}/prestasi/{prestasi}/delete', [
		'as' => 'mahasiswa.prestasi.delete',
		'roles' => ['administrator', 'akademik', 'mahasiswa'],
		'uses' => 'MahasiswaPrestasiController@destroy'
		]);		
		Route::get('/export/dikti/prestasi', [
		'as' => 'mahasiswa.prestasi.export',
		'roles' => ['administrator', 'akademik'],
		'uses' => 'MahasiswaPrestasiController@export'
		]);		
		
		//PDDIKTI
		Route::get('/export/kurikulum', [
		'as' => 'export.kurikulum',
		'roles' => ['administrator'],
		'uses' => 'DataExchangeController@exportKurikulum'
		]);
		Route::get('/export/{data}', [
		'as' => 'export',
		'roles' => ['administrator'],
		'uses' => 'DataExchangeController@export'
		]);	
		Route::get('/export/{data}/{prodi}/{type}/{var?}', [
		'as' => 'export.format',
		'roles' => ['administrator'],
		'uses' => 'DataExchangeController@exportInto'
		]);	
		
		
		Route::get('/export/dikti/kelulusan', [
		'as' => 'export.dikti.kelulusan.get',
		'roles' => ['administrator', 'akademik'],
		'uses' => 'MahasiswaController@getExportKelulusan'
		]);
		Route::post('/export/dikti/kelulusan', [
		'as' => 'export.dikti.kelulusan.post',
		'roles' => ['administrator', 'akademik'],
		'uses' => 'MahasiswaController@postExportKelulusan'
		]);
		Route::get('/export/dikti/akm', [
		'as' => 'export.dikti.akm.get',
		'roles' => ['administrator', 'akademik'],
		'uses' => 'MahasiswaController@getExportAkm'
		]);
		Route::post('/export/dikti/akm', [
		'as' => 'export.dikti.akm.post',
		'roles' => ['administrator', 'akademik'],
		'uses' => 'MahasiswaController@postExportAkm'
		]);
		
		Route::get('/export/dikti/mahasiswa', [
		'as' => 'export.dikti.mahasiswa.get',
		'roles' => ['administrator', 'akademik'],
		'uses' => 'MahasiswaController@getExportDikti'
		]);
		
		Route::post('/export/dikti/mahasiswa', [
		'as' => 'export.dikti.mahasiswa.post',
		'roles' => ['administrator', 'akademik'],
		'uses' => 'MahasiswaController@postExportDikti'
		]);
		Route::get('/export/dikti/kelaskuliah', [
		'as' => 'export.dikti.kelaskuliah.get',
		'roles' => ['administrator', 'akademik'],
		'uses' => 'MatkulTapelController@getExportDikti'
		]);
		Route::post('/export/dikti/kelaskuliah', [
		'as' => 'export.dikti.kelaskuliah.post',
		'roles' => ['administrator', 'akademik'],
		'uses' => 'MatkulTapelController@postExportDikti'
		]);
		Route::get('/export/dikti/krs', [
		'as' => 'export.dikti.krs.get',
		'roles' => ['administrator', 'akademik'],
		'uses' => 'KrsController@getExportDikti'
		]);
		Route::post('/export/dikti/krs', [
		'as' => 'export.dikti.krs.post',
		'roles' => ['administrator', 'akademik'],
		'uses' => 'KrsController@postExportDikti'
		]);
		Route::get('/export/dikti/dosen', [
		'as' => 'export.dikti.dosen.get',
		'roles' => ['administrator', 'akademik'],
		'uses' => 'DosenController@getExportDikti'
		]);
		Route::post('/export/dikti/dosen', [
		'as' => 'export.dikti.dosen.post',
		'roles' => ['administrator', 'akademik'],
		'uses' => 'DosenController@postExportDikti'
		]);
		Route::get('/export/dikti/nilai', [
		'as' => 'export.dikti.nilai.get',
		'roles' => ['administrator', 'akademik'],
		'uses' => 'MatkulTapelController@getExportNilaiDikti'
		]);
		Route::post('/export/dikti/nilai', [
		'as' => 'export.dikti.nilai.post',
		'roles' => ['administrator', 'akademik'],
		'uses' => 'MatkulTapelController@postExportNilaiDikti'
		]);
		
		
		//EMIS
		Route::get('/export/emis/lulusan', [
		'as' => 'export.emis.lulusan.get',
		'roles' => ['administrator', 'akademik'],
		'uses' => 'MahasiswaController@getExportLulusan'
		]);
		
		Route::get('/export/emis/dosen', [
		'as' => 'export.emis.dosen.get',
		'roles' => ['administrator', 'akademik'],
		'uses' => 'DosenController@getExport'
		]);
		Route::post('/export/emis/dosen', [
		'as' => 'export.emis.dosen.post',
		'roles' => ['administrator', 'akademik'],
		'uses' => 'DosenController@postExport'
		]);	
		
		Route::get('/export/emis/mahasiswa', [
		'as' => 'export.emis.mahasiswa.get',
		'roles' => ['administrator', 'akademik'],
		'uses' => 'MahasiswaController@getExport'
		]);
		Route::post('/export/emis/mahasiswa', [
		'as' => 'export.emis.mahasiswa.post',
		'roles' => ['administrator', 'akademik'],
		'uses' => 'MahasiswaController@postExport'
		]);
		
		//Mahasiswa Cuti
		Route::get('mahasiswa/cuti', [
		'as' => 'mahasiswa.cuti.index',
		'roles' => ['administrator', 'akademik', 'prodi'], 
		'uses' => 'MahasiswaCutiController@index'
		]);
		Route::get('mahasiswa/{mahasiswa}/cuti', [
		'as' => 'mahasiswa.cuti.detail',
		'roles' => ['administrator', 'akademik', 'prodi'], 
		'uses' => 'MahasiswaCutiController@detail'
		]);
		Route::get('mahasiswa/cuti/create/{mahasiswa?}', [
		'as' => 'mahasiswa.cuti.create',
		'roles' => ['administrator', 'akademik', 'prodi'], 
		'uses' => 'MahasiswaCutiController@create'
		]);
		Route::post('mahasiswa/cuti', [
		'as' => 'mahasiswa.cuti.store',
		'roles' => ['administrator', 'akademik', 'prodi'], 
		'uses' => 'MahasiswaCutiController@store'
		]);
		Route::get('mahasiswa/cuti/{cuti}/edit', [
		'as' => 'mahasiswa.cuti.edit',
		'roles' => ['administrator', 'akademik', 'prodi'], 
		'uses' => 'MahasiswaCutiController@edit'
		]);
		Route::patch('mahasiswa/cuti/{cuti}', [
		'as' => 'mahasiswa.cuti.update',
		'roles' => ['administrator', 'akademik', 'prodi'], 
		'uses' => 'MahasiswaCutiController@update'
		]);
		Route::get('mahasiswa/cuti/{cuti}/reactivate', [
		'as' => 'mahasiswa.cuti.reactivate',
		'roles' => ['administrator', 'akademik', 'prodi'], 
		'uses' => 'MahasiswaCutiController@reactivate'
		]);
		Route::get('mahasiswa/cuti/{cuti}/delete', [
		'as' => 'mahasiswa.cuti.delete',
		'roles' => ['administrator', 'akademik', 'prodi'], 
		'uses' => 'MahasiswaCutiController@delete'
		]);
		
		//PMB TES
		Route::get('pmb/{id}/ujian', [
		'as' => 'pmb.ujian.index',
		'uses' => 'PmbPesertaController@index'
		]);
		
		//PMB Peserta
		Route::get('pmb/{id}/peserta', [
		'as' => 'pmb.peserta.index',
		'uses' => 'PmbPesertaController@index'
		]);
		Route::get('/pmb/peserta/{kode}/show', [
		'as' => 'pmb.peserta.show',
		'uses' =>'PmbPesertaController@printForm'
		]);	
		Route::get('/pmb/{id}/peserta/{kode}/delete', [
		'as' => 'pmb.peserta.delete',
		'roles' => ['administrator'], 
		'uses' =>'PmbPesertaController@destroy'
		]);
		Route::get('/pmb/{id}/peserta/{kode}/edit', [
		'as' => 'pmb.peserta.edit',
		'roles' => ['administrator'], 
		'uses' =>'PmbPesertaController@edit'
		]);
		Route::patch('/pmb/{id}/peserta/{kode}', [
		'as' => 'pmb.peserta.update',
		'roles' => ['administrator'], 
		'uses' =>'PmbPesertaController@update'
		]);
		
		//PMB
		Route::get('pmb/{id}/delete', [
		'as' => 'pmb.delete',
		'roles' => ['administrator', 'akademik'], 
		'uses' => 'PmbController@destroy'
		]);
		Route::patch('pmb/{id}', [
		'as' => 'pmb.update',
		'roles' => ['administrator', 'akademik'], 
		'uses' => 'PmbController@update'
		]);
		Route::get('pmb/{id}/edit', [
		'as' => 'pmb.edit',
		'roles' => ['administrator', 'akademik'], 
		'uses' => 'PmbController@edit'
		]);
		Route::post('pmb', [
		'as' => 'pmb.store',
		'roles' => ['administrator', 'akademik', 'pmb'], 
		'uses' => 'PmbController@store'
		]);
		Route::get('pmb/create', [
		'as' => 'pmb.create',
		'roles' => ['administrator', 'akademik', 'pmb'], 
		'uses' => 'PmbController@create'
		]);
		Route::get('pmb', [
		'as' => 'pmb.index',
		'roles' => ['administrator', 'akademik', 'pmb'], 
		'uses' => 'PmbController@index'
		]);
		/* 		Route::get('pmb/grafik', [
			'as' => 'pmb.grafik', 
			'roles' => ['administrator', 'akademik', 'pmb'], 
			'uses' => 'PmbController@graph'
		]); */
		Route::get('pmb/{id}/export/{format}', [
		'as' => 'pmb.export', 'uses' => 'PmbPesertaController@exportTo'
		]);
		Route::get('pmb/{no_pendaftaran}', [
		'as' => 'pmb.show', 'uses' => 'PmbController@show'
		]);
		Route::get('pmb/{no_pendaftaran}/cetak', [
		'as' => 'pmb.cetak', 'uses' => 'PmbController@cetak'
		]);
		
		//KURIKULUM MATKUL
		Route::post('/kurikulum/{kurikulum}/matkul/add', [
		'as' => 'prodi.kurikulum.matkul.add',
		'roles' => ['administrator'],
		'uses' =>'KurikulumMatkulController@addFrom'
		]);
		Route::get('/kurikulum/{kurikulum}/matkul/edit', [
		'as' => 'prodi.kurikulum.matkul.edit',
		'roles' => ['administrator'],
		'uses' =>'KurikulumMatkulController@edit'
		]);
		Route::post('/kurikulum/{kurikulum}/matkul/update', [
		'as' => 'prodi.kurikulum.matkul.update',
		'roles' => ['administrator'],
		'uses' =>'KurikulumMatkulController@update'
		]);
		
		Route::get('/kurikulum/{kurikulum}/matkul/create', [
		'as' => 'prodi.kurikulum.matkul.create',
		'roles' => ['administrator'],
		'uses' =>'KurikulumMatkulController@create'
		]);
		Route::post('/kurikulum/{kurikulum}/matkul', [
		'as' => 'prodi.kurikulum.matkul.store',
		'roles' => ['administrator'],
		'uses' =>'KurikulumMatkulController@store'
		]);
		Route::get('/kurikulum/{kurikulum}/matkul/{matkul}/delete', [
		'as' => 'prodi.kurikulum.matkul.delete',
		'roles' => ['administrator'],
		'uses' =>'KurikulumMatkulController@destroy'
		]);
		
		
		//KURIKULUM
		Route::get('/kurikulum', [
		'as' => 'prodi.kurikulum.index',
		'roles' => ['administrator', 'prodi'],
		'uses' =>'KurikulumController@index'
		]);
		Route::get('/kurikulum/create', [
		'as' => 'prodi.kurikulum.create',
		'roles' => ['administrator'],
		'uses' =>'KurikulumController@create'
		]);
		Route::post('/kurikulum/create', [
		'as' => 'prodi.kurikulum.store',
		'roles' => ['administrator'],
		'uses' =>'KurikulumController@store'
		]);
		Route::get('/kurikulum/{kurikulum}/detail', [
		'as' => 'prodi.kurikulum.detail',
		'roles' => ['administrator', 'prodi'],
		'uses' =>'KurikulumController@detail'
		]);
		Route::get('/kurikulum/{kurikulum}/delete', [
		'as' => 'prodi.kurikulum.delete',
		'roles' => ['administrator'],
		'uses' =>'KurikulumController@destroy'
		]);
		Route::get('/kurikulum/{kurikulum}/edit', [
		'as' => 'prodi.kurikulum.edit',
		'roles' => ['administrator'],
		'uses' =>'KurikulumController@edit'
		]);
		Route::patch('/kurikulum/{kurikulum}', [
		'as' => 'prodi.kurikulum.update',
		'roles' => ['administrator'],
		'uses' =>'KurikulumController@update'
		]);
		
		Route::post('/mahasiswa/golongan', [
		'roles' => ['keuangan / administrasi', 'administrator', 'akademik'],
		'uses' =>'MahasiswaController@getGolongan'
		]);
		
		//CONFIG
		Route::get('config', [
		'as' => 'config.edit',
		'roles' => ['administrator'],
		'uses' => 'ConfigController@edit'
		]);
		Route::patch('config', [
		'as' => 'config.update',
		'roles' => ['administrator'],
		'uses' => 'ConfigController@update'
		]);
		
		// Transfer fast edit
		Route::get('/mahasiswa/transfer', [
		'roles' => ['administrator', 'akademik'],
		'uses' => 'MahasiswaController@transfer'
		]);
		Route::post('/mahasiswa/transfer', [
		'roles' => ['administrator', 'akademik'],
		'uses' => 'MahasiswaController@filterMahasiswa'
		]);
		Route::post('/mahasiswa/do/transfer', [
		'roles' => ['administrator', 'akademik'],
		'uses' =>'MahasiswaController@doTransfer'
		]);
		
		// Jenis Pembiayaaan fast edit
		Route::get('/mahasiswa/adminpembiayaan', [
		'roles' => ['administrator'],
		'uses' => 'MahasiswaController@adminFundingTypeForm'
		]);
		Route::post('/mahasiswa/adminpembiayaan', [
		'roles' => ['administrator', 'akademik'],
		'uses' =>'MahasiswaController@adminFundingTypeUpdate'
		]);
		Route::post('/mahasiswa/adminpembiayaan/anggota', [
		'roles' => ['administrator', 'akademik'],
		'uses' => 'MahasiswaController@adminFundingTypeMember'
		]);	
		
		// Status fast edit
		Route::get('/mahasiswa/adminstatus', [
		'as' => 'admin.status',
		'roles' => ['administrator', 'akademik'],
		'uses' => 'MahasiswaController@adminStatus'
		]);	
		Route::post('/mahasiswa/adminstatus', [
		'as' => 'admin.status.update',
		'roles' => ['administrator', 'akademik'],
		'uses' => 'MahasiswaController@adminStatusUpdate'
		]);
		Route::post('/mahasiswa/adminstatus/anggota', [
		'roles' => ['administrator', 'akademik'],
		'uses' => 'MahasiswaController@adminStatusAnggota'
		]);	
		
		// Perwalian fast edit
		Route::get('/mahasiswa/adminperwalian', [
		'as' => 'admin.perwalian',
		'roles' => ['administrator', 'akademik'],
		'uses' => 'MahasiswaController@adminCustodian'
		]);	
		Route::post('/mahasiswa/adminperwalian', [
		'as' => 'admin.perwalian.update',
		'roles' => ['administrator', 'akademik'],
		'uses' => 'MahasiswaController@adminCustodianUpdate'
		]);
		Route::post('/mahasiswa/adminperwalian/anggota', [
		'roles' => ['administrator', 'akademik'],
		'uses' => 'MahasiswaController@adminCustodianAnggota'
		]);	
		
		/*
			Data exchange
		*/
		Route::get('/mahasiswa/yudisium/impor', [
		'as' => 'mahasiswa.yudisium.import',
		'roles' => ['administrator', 'akademik'],
		'uses' => 'DataExchangeController@importYudisiumForm'
		]);	
		Route::post('/mahasiswa/yudisium/impor', [
		'as' => 'mahasiswa.yudisium.import.post',
		'roles' => ['administrator', 'akademik'],
		'uses' => 'DataExchangeController@importYudisium'
		]);	
		
		Route::get('/mahasiswa/impor', [
		'as' => 'mahasiswa.import',
		'roles' => ['administrator', 'akademik'],
		'uses' => 'DataExchangeController@importForm'
		]);	
		Route::post('/import/mahasiswa', [
		'as' => 'mahasiswa.import.post',
		'roles' => ['administrator', 'akademik'],
		'uses' => 'DataExchangeController@import'
		]);	
		
		
		Route::get('/skripsi/bimbingan/selesai', [
		'as' => 'skripsi.bimbingan.selesai',
		'roles' => ['dosen'],
		'uses' => 'DosenSkripsiController@selesaiBimbingan'
		]);
		
		//KANDIDAT BIMBINGAN
		Route::get('/skripsi/bimbingan/kandidat', [
		'as' => 'skripsi.bimbingan.kandidat',
		'roles' => ['dosen'],
		'uses' => 'DosenSkripsiController@kandidatBimbingan'
		]);	
		
		Route::get('/skripsi/{skripsi_id}/bimbingan/kandidat/{aksi}', [
		'as' => 'skripsi.bimbingan.kandidat.aksi',
		'roles' => ['dosen'],
		'uses' => 'DosenSkripsiController@KandidatBimbinganAksi'
		]);
		
		Route::get('/skripsi/{skripsi_id}/revisi', [
		'as' => 'skripsi.revisi',
		'roles' => ['mahasiswa'],
		'uses' => 'SkripsiController@revisi'
		]);
		Route::any('/skripsi/{skripsi_id}/revisi/post', [
		'as' => 'skripsi.revisi.post',
		'roles' => ['mahasiswa'],
		'uses' => 'SkripsiController@revisiPost'
		]);
		
		//BIMBINGAN Skripsi
		Route::get('/skripsi/pembagian', [
		'as' => 'skripsi.pembagian.pembimbing',
		'roles' => ['prodi'],
		'uses' => 'DosenSkripsiController@pembagianPembimbing'
		]);		
		
		Route::get('/skripsi/bimbingan', [
		'as' => 'skripsi.bimbingan',
		'roles' => ['dosen'],
		'uses' => 'DosenSkripsiController@bimbingan'
		]);	
		
		Route::get('/skripsi/{skripsi}/validasi/{jenis}', [
		'as' => 'skripsi.validasi',
		'roles' => ['administrator', 'akademik', 'dosen'], 
		'uses' =>'SkripsiController@validasi'
		]);
		
		Route::get('/skripsi/{skripsi}/validasi/{jenis}/cetak', [
		'as' => 'skripsi.ujian.validasi.print',
		'roles' => ['administrator', 'akademik', 'dosen', 'mahasiswa'], 
		'uses' =>'SkripsiController@cetakValidasi'
		]);
		Route::get('/skripsi/{skripsi}/ujian/pendaftaran/{jenis}/cetak', [
		'as' => 'skripsi.ujian.pendaftaran.print',
		'roles' => ['administrator', 'akademik', 'dosen', 'mahasiswa', 'prodi'], 
		'uses' =>'SkripsiController@cetakPendaftaran'
		]);
		Route::get('/skripsi/{skripsi}/ujian/pendaftaran/{jenis}', [
		'as' => 'skripsi.ujian.pendaftaran',
		'roles' => ['administrator', 'akademik', 'dosen', 'mahasiswa'], 
		'uses' =>'SkripsiController@pendaftaran'
		]);
		
		Route::get('/skripsi/{skripsi}/bimbingan/create', [
		'as' => 'mahasiswa.skripsi.bimbingan.create',
		'roles' => ['administrator', 'akademik', 'dosen', 'mahasiswa'], 
		'uses' =>'BimbinganSkripsiController@create'
		]);
		Route::post('/skripsi/{skripsi}/bimbingan', [
		'as' => 'mahasiswa.skripsi.bimbingan.store',
		'roles' => ['administrator', 'akademik', 'dosen', 'mahasiswa'], 
		'uses' =>'BimbinganSkripsiController@store'
		]);
		Route::get('/skripsi/{skripsi}/bimbingan/{bimbingan}/edit', [
		'as' => 'mahasiswa.skripsi.bimbingan.edit',
		'roles' => ['administrator', 'akademik', 'dosen'], 
		'uses' =>'BimbinganSkripsiController@edit'
		]);
		Route::patch('/skripsi/{skripsi}/bimbingan/{bimbingan}/edit', [
		'as' => 'mahasiswa.skripsi.bimbingan.update',
		'roles' => ['administrator', 'akademik', 'dosen'], 
		'uses' =>'BimbinganSkripsiController@update'
		]);
		
		//PEMBIMBING SKRIPSI
		Route::get('/skripsi/pembimbing', [
		'as' => 'skripsi.pembimbing',
		'roles' => ['administrator', 'prodi'],
		'uses' => 'DosenSkripsiController@pembimbing'
		]);	
		Route::post('/skripsi/pembimbing', [
		'as' => 'skripsi.pembimbing.update',
		'roles' => ['administrator', 'prodi'],
		'uses' => 'DosenSkripsiController@pembimbingUpdate'
		]);
		Route::post('/skripsi/pembimbing/anggota', [
		'roles' => ['administrator', 'prodi'],
		'uses' => 'DosenSkripsiController@pembimbingAnggota'
		]);
		
		//JADWAL PENGAJUAN JUDUL SKRIPSI
		//JADWAL PENGAJUAN JUDUL SKRIPSI GELOMBANG
		Route::get('/jadwal/pengajuan/skripsi/gelombang/{id}/peserta', [
		'as' => 'jadwal.pengajuan.skripsi.gelombang.peserta',
		'roles' => ['administrator', 'akademik', 'prodi'],
		'uses' => 'JadwalPengajuanSkripsiGelombangController@peserta'
		]);
		Route::get('/jadwal/pengajuan/skripsi/gelombang/{id}/create', [
		'as' => 'jadwal.pengajuan.skripsi.gelombang.create',
		'roles' => ['administrator', 'akademik', 'prodi'],
		'uses' => 'JadwalPengajuanSkripsiGelombangController@create'
		]);
		Route::post('/jadwal/pengajuan/skripsi/gelombang/{id}', [
		'as' => 'jadwal.pengajuan.skripsi.gelombang.store',
		'roles' => ['administrator', 'akademik', 'prodi'],
		'uses' => 'JadwalPengajuanSkripsiGelombangController@store'
		]);
		Route::get('/jadwal/pengajuan/skripsi/gelombang/{id}/edit', [
		'as' => 'jadwal.pengajuan.skripsi.gelombang.edit',
		'roles' => ['administrator', 'akademik', 'prodi'],
		'uses' => 'JadwalPengajuanSkripsiGelombangController@edit'
		]);
		Route::patch('/jadwal/pengajuan/skripsi/gelombang/{id}', [
		'as' => 'jadwal.pengajuan.skripsi.gelombang.update',
		'roles' => ['administrator', 'akademik', 'prodi'],
		'uses' => 'JadwalPengajuanSkripsiGelombangController@update'
		]);
		Route::get('/jadwal/pengajuan/skripsi/gelombang/{id}/delete', [
		'as' => 'jadwal.pengajuan.skripsi.gelombang.delete',
		'roles' => ['administrator', 'akademik', 'prodi'],
		'uses' => 'JadwalPengajuanSkripsiGelombangController@destroy'
		]);
		
		//JADWAL PENGAJUAN JUDUL SKRIPSI
		Route::get('/jadwal/pengajuan/skripsi', [
		'as' => 'jadwal.pengajuan.skripsi.index',
		'roles' => ['administrator', 'akademik', 'prodi'],
		'uses' => 'JadwalPengajuanSkripsiController@index'
		]);
		Route::get('/jadwal/pengajuan/skripsi/create', [
		'as' => 'jadwal.pengajuan.skripsi.create',
		'roles' => ['administrator', 'akademik', 'prodi'],
		'uses' => 'JadwalPengajuanSkripsiController@create'
		]);
		Route::post('/jadwal/pengajuan/skripsi', [
		'as' => 'jadwal.pengajuan.skripsi.store',
		'roles' => ['administrator', 'akademik', 'prodi'],
		'uses' => 'JadwalPengajuanSkripsiController@store'
		]);
		Route::get('/jadwal/pengajuan/skripsi/{id}/edit', [
		'as' => 'jadwal.pengajuan.skripsi.edit',
		'roles' => ['administrator', 'akademik', 'prodi'],
		'uses' => 'JadwalPengajuanSkripsiController@edit'
		]);
		Route::patch('/jadwal/pengajuan/skripsi/{id}', [
		'as' => 'jadwal.pengajuan.skripsi.update',
		'roles' => ['administrator', 'akademik', 'prodi'],
		'uses' => 'JadwalPengajuanSkripsiController@update'
		]);
		Route::get('/jadwal/pengajuan/skripsi/{id}/delete', [
		'as' => 'jadwal.pengajuan.skripsi.delete',
		'roles' => ['administrator', 'akademik', 'prodi'],
		'uses' => 'JadwalPengajuanSkripsiController@destroy'
		]);
		
		//PENGAJUAN JUDUL SKRIPSI
		Route::get('/skripsi/pengajuan/recount/{id?}', [
		'as' => 'mahasiswa.skripsi.pengajuan.recount',
		'roles' => ['administrator', 'prodi'],
		'uses' => 'PengajuanSkripsiController@recount'
		]);
		
		Route::get('/skripsi/pengajuan', [
		'as' => 'mahasiswa.skripsi.pengajuan.index',
		'roles' => ['mahasiswa'],
		'uses' => 'PengajuanSkripsiController@index'
		]);
		
		Route::get('/skripsi/pengajuan/{id}/edit', [
		'as' => 'mahasiswa.skripsi.pengajuan.edit',
		'roles' => ['administrator', 'prodi'],
		'uses' => 'PengajuanSkripsiController@edit'
		]);
		Route::patch('/skripsi/pengajuan/{id}', [
		'as' => 'mahasiswa.skripsi.pengajuan.update',
		'roles' => ['administrator', 'prodi'],
		'uses' => 'PengajuanSkripsiController@update'
		]); 
		Route::get('/skripsi/pengajuan/create', [
		'as' => 'mahasiswa.skripsi.pengajuan.create',
		'roles' => ['mahasiswa'],
		'uses' => 'PengajuanSkripsiController@create'
		]);
		Route::post('/skripsi/pengajuan', [
		'as' => 'mahasiswa.skripsi.pengajuan.store',
		'roles' => ['mahasiswa'],
		'uses' => 'PengajuanSkripsiController@store'
		]);
		
		//SKRIPSI
		Route::get('/skripsi/mahasiswa/search', [
		'roles' => ['administrator', 'akademik', 'prodi'],
		'uses' => 'SkripsiController@filter'
		]);
		Route::get('/skripsi/search', [
		'roles' => ['administrator', 'akademik', 'prodi'],
		'uses' => 'SkripsiController@search'
		]);
		
		Route::get('/skripsi/create', [
		'as' => 'skripsi.create',
		'roles' => ['administrator', 'akademik', 'prodi'], 
		'uses' =>'SkripsiController@create'
		]);
		Route::get('/skripsi/tmp/{id}/delete', [
		'as' => 'skripsi.tmp.delete',
		'roles' => ['administrator', 'akademik', 'prodi'], 
		'uses' =>'SkripsiController@destroy_tmp'
		]);
		Route::post('/skripsi/tmp', [
		'as' => 'skripsi.tmp.store',
		'roles' => ['administrator', 'akademik', 'prodi'], 
		'uses' =>'SkripsiController@store_tmp'
		]);
		Route::get('/skripsi/tmp/save', [
		'as' => 'skripsi.store',
		'roles' => ['administrator', 'akademik', 'prodi'], 
		'uses' =>'SkripsiController@store'
		]);
		Route::get('/skripsi/tmp/remove', [
		'as' => 'skripsi.tmp.remove',
		'roles' => ['administrator', 'akademik', 'prodi'], 
		'uses' =>'SkripsiController@remove_tmp'
		]);
		Route::get('/skripsi', [
		'as' => 'skripsi.index',
		'roles' => ['administrator', 'akademik', 'prodi', 'mahasiswa'], 
		'uses' =>'SkripsiController@index'
		]);
		Route::get('/skripsi/{id}/file', [
		'as' => 'skripsi.file',
		'roles' => ['administrator', 'akademik', 'prodi'], 
		'uses' =>'SkripsiController@downloadFile'
		]);
		Route::get('/skripsi/{id}', [
		'as' => 'skripsi.show',
		'roles' => ['administrator', 'akademik', 'prodi'], 
		'uses' =>'SkripsiController@show'
		]);
		Route::get('/skripsi/{id}/delete', [
		'as' => 'skripsi.delete',
		'roles' => ['administrator', 'akademik', 'prodi'], 
		'uses' =>'SkripsiController@destroy'
		]);
		Route::get('/skripsi/{id}/edit', [
		'as' => 'skripsi.edit',
		'roles' => ['administrator', 'akademik', 'prodi', 'mahasiswa'], 
		'uses' =>'SkripsiController@edit'
		]);
		Route::patch('/skripsi/{id}', [
		'as' => 'skripsi.update',
		'roles' => ['administrator', 'akademik', 'prodi', 'mahasiswa'], 
		'uses' =>'SkripsiController@update'
		]);
		
		// PEMBIMBING SKRIPSI
		Route::post('/dosen/{id}/mahasiswa/add', [
		'as' => 'dosen.skripsi.mahasiswa.store',
		'roles' => ['administrator', 'akademik'], 
		'uses' =>'DosenSkripsiController@store'
		]);
		Route::get('/dosen/{id}/mahasiswa', [
		'as' => 'dosen.skripsi.mahasiswa',
		'roles' => ['administrator', 'akademik', 'dosen', 'prodi'], 
		'uses' =>'DosenSkripsiController@index'
		]);
		Route::get('/dosen/{id}/mahasiswa/{mahasiswa_id}/delete', [
		'as' => 'dosen.skripsi.mahasiswa.delete',
		'roles' => ['administrator', 'akademik'], 
		'uses' =>'DosenSkripsiController@destroy'
		]);
		
		//PPL
		//Mahasiswa 
		Route::get('/ppl/{mahasiswa_id}/status', [
		'as' => 'mahasiswa.ppl.status',
		'roles' => ['administrator', 'prodi', 'akademik'], 
		'uses' =>'MahasiswaPplLokasiController@status'
		]);
		Route::get('/ppl/daftar', [
		'as' => 'mahasiswa.ppl.formdaftar',
		'roles' => ['mahasiswa'], 
		'uses' =>'MahasiswaPplLokasiController@create'
		]);
		Route::post('/ppl/daftar', [
		'as' => 'mahasiswa.ppl.daftar',
		'roles' => ['mahasiswa'], 
		'uses' =>'MahasiswaPplLokasiController@store'
		]); 
		Route::get('/ppl/{lokasi_id?}/cetakformulir/{mahasiswa_id?}', [
		'as' => 'mahasiswa.ppl.daftar.cetak',
		'roles' => ['mahasiswa', 'akademik'], 
		'uses' =>'MahasiswaPplLokasiController@cetakFormulir'
		]);
		
		//Peserta
		Route::get('/ppl/{id}/peserta/{print?}', [
		'as' => 'mahasiswa.ppl.lokasi.peserta.index',
		'roles' => ['administrator', 'akademik', 'prodi', 'dosen'], 
		'uses' =>'MahasiswaPplLokasiController@index'
		]);
		// Route::get('/ppl/{id}/peserta/print', [
		// 'as' => 'mahasiswa.ppl.lokasi.peserta.print',
		// 'roles' => ['administrator', 'akademik', 'prodi'], 
		// 'uses' =>'MahasiswaPplLokasiController@print'
		// ]);
		Route::post('/ppl/{matkul_id}/peserta', [
		'as' => 'mahasiswa.ppl.lokasi.peserta.nilai.store',
		'roles' => ['administrator', 'akademik', 'prodi', 'dosen'], 
		'uses' =>'MahasiswaPplLokasiController@storeNilai'
		]);
		Route::get('/ppl/{ppl_id}/lokasi/{ppl_lokasi_id}/peserta/{mahasiswa_id}/delete', [
		'as' => 'mahasiswa.ppl.lokasi.peserta.delete',
		'roles' => ['administrator', 'akademik', 'prodi'], 
		'uses' =>'MahasiswaPplLokasiController@destroy'
		]);
		
		//Pendamping Lokasi PPL
		Route::get('/ppl/{ppl_id}/lokasi/{lokasi_id}/pendamping/create', [
		'as' => 'mahasiswa.ppl.lokasi.pendamping.create',
		'roles' => ['administrator', 'akademik', 'prodi'], 
		'uses' =>'PplLokasiDosenController@create'
		]);
		Route::post('/ppl/{ppl_id}/lokasi/{lokasi_id}/pendamping', [
		'as' => 'mahasiswa.ppl.lokasi.pendamping.store',
		'roles' => ['administrator', 'akademik', 'prodi'], 
		'uses' =>'PplLokasiDosenController@store'
		]);
		
		Route::get('/ppl/pendamping/{id}/delete', [
		'as' => 'mahasiswa.ppl.lokasi.pendamping.delete',
		'roles' => ['administrator', 'akademik', 'prodi'], 
		'uses' =>'PplLokasiDosenController@destroy'
		]);
		
		//Lokasi PPL
		Route::get('/ppl/{id}/lokasi/create', [
		'as' => 'mahasiswa.ppl.lokasi.create',
		'roles' => ['administrator', 'akademik', 'prodi'], 
		'uses' =>'PplLokasiController@create'
		]);
		Route::post('/ppl/{id}/lokasi', [
		'as' => 'mahasiswa.ppl.lokasi.store',
		'roles' => ['administrator', 'akademik', 'prodi'], 
		'uses' =>'PplLokasiController@store'
		]);	
		Route::get('/ppl/{id}/lokasi/{lokasi_id}/edit', [
		'as' => 'mahasiswa.ppl.lokasi.edit',
		'roles' => ['administrator', 'akademik', 'prodi'], 
		'uses' =>'PplLokasiController@edit'
		]);
		Route::patch('/ppl/{id}/lokasi/{lokasi_id}', [
		'as' => 'mahasiswa.ppl.lokasi.update',
		'roles' => ['administrator', 'akademik', 'prodi'], 
		'uses' =>'PplLokasiController@update'
		]);
		Route::get('/ppl/lokasi/{lokasi_id}/delete', [
		'as' => 'mahasiswa.ppl.lokasi.delete',
		'roles' => ['administrator', 'akademik', 'prodi'], 
		'uses' =>'PplLokasiController@destroy'
		]);
		
		//PPL
		Route::get('/ppl', [
		'as' => 'mahasiswa.ppl.index',
		'roles' => ['administrator', 'akademik', 'prodi', 'dosen'], 
		'uses' =>'PplController@index'
		]);
		Route::get('/ppl/create', [
		'as' => 'mahasiswa.ppl.create',
		'roles' => ['administrator', 'akademik', 'prodi'], 
		'uses' =>'PplController@create'
		]);
		Route::post('/ppl', [
		'as' => 'mahasiswa.ppl.store',
		'roles' => ['administrator', 'akademik', 'prodi'], 
		'uses' =>'PplController@store'
		]);
		
		Route::get('/ppl/{id}/edit', [
		'as' => 'mahasiswa.ppl.edit',
		'roles' => ['administrator', 'akademik', 'prodi'], 
		'uses' =>'PplController@edit'
		]);
		Route::patch('/ppl/{id}', [
		'as' => 'mahasiswa.ppl.update',
		'roles' => ['administrator', 'akademik', 'prodi'], 
		'uses' =>'PplController@update'
		]);
		Route::get('/ppl/{id}/delete', [
		'as' => 'mahasiswa.ppl.delete',
		'roles' => ['administrator', 'akademik', 'prodi'], 
		'uses' =>'PplController@destroy'
		]);
		
		//PKM
		//Mahasiswa 
		Route::get('/pkm/{mahasiswa_id}/status', [
		'as' => 'mahasiswa.pkm.status',
		'roles' => ['administrator', 'prodi', 'akademik'], 
		'uses' =>'MahasiswaPkmLokasiController@status'
		]);
		Route::get('/pkm/daftar', [
		'as' => 'mahasiswa.pkm.formdaftar',
		'roles' => ['mahasiswa'], 
		'uses' =>'MahasiswaPkmLokasiController@create'
		]);
		Route::post('/pkm/daftar', [
		'as' => 'mahasiswa.pkm.daftar',
		'roles' => ['mahasiswa'], 
		'uses' =>'MahasiswaPkmLokasiController@store'
		]); 
		Route::get('/pkm/{lokasi_id?}/cetakformulir/{mahasiswa_id?}', [
		'as' => 'mahasiswa.pkm.daftar.cetak',
		'roles' => ['mahasiswa', 'akademik'], 
		'uses' =>'MahasiswaPkmLokasiController@cetakFormulir'
		]);
		
		//Peserta	
		Route::get('/pkm/{id}/peserta/{print?}', [
		'as' => 'mahasiswa.pkm.lokasi.peserta.index',
		'roles' => ['administrator', 'akademik', 'prodi', 'dosen', 'p3m (pusat penelitian dan pengambangan)'], 
		'uses' =>'MahasiswaPkmLokasiController@index'
		]);
		/* Route::get('/pkm/{id}/peserta/print', [
			'as' => 'mahasiswa.pkm.lokasi.peserta.print',
			'roles' => ['administrator', 'akademik', 'prodi', 'p3m (pusat penelitian dan pengambangan)'], 
			'uses' =>'MahasiswaPkmLokasiController@print'
		]); */
		Route::get('/pkm/{pkm_id}/lokasi/{pkm_lokasi_id}/nilai/{matkul_id}', [
		'as' => 'mahasiswa.pkm.lokasi.peserta.nilai',
		'roles' => ['administrator', 'akademik', 'prodi', 'dosen', 'p3m (pusat penelitian dan pengambangan)'], 
		'uses' =>'MahasiswaPkmLokasiController@nilai'
		]);
		Route::post('/pkm/{pkm_id}/lokasi/{pkm_lokasi_id}/nilai/{matkul_id}', [
		'as' => 'mahasiswa.pkm.lokasi.peserta.nilai.store',
		'roles' => ['administrator', 'akademik', 'prodi', 'dosen', 'p3m (pusat penelitian dan pengambangan)'], 
		'uses' =>'MahasiswaPkmLokasiController@storeNilai'
		]);
		Route::get('/pkm/{pkm_id}/lokasi/{pkm_lokasi_id}/peserta/{mahasiswa_id}/delete', [
		'as' => 'mahasiswa.pkm.lokasi.peserta.delete',
		'roles' => ['administrator', 'akademik', 'prodi', 'p3m (pusat penelitian dan pengambangan)'], 
		'uses' =>'MahasiswaPkmLokasiController@destroy'
		]);
		
		//Matkul Lokasi PKM
		Route::get('/pkm/{pkm_id}/lokasi/{lokasi_id}/matkul/create', [
		'as' => 'mahasiswa.pkm.lokasi.matkul.create',
		'roles' => ['administrator', 'akademik', 'prodi', 'p3m (pusat penelitian dan pengambangan)'], 
		'uses' =>'PkmLokasiMatkulController@create'
		]);
		Route::post('/pkm/{pkm_id}/lokasi/{lokasi_id}/matkul', [
		'as' => 'mahasiswa.pkm.lokasi.matkul.store',
		'roles' => ['administrator', 'akademik', 'prodi', 'p3m (pusat penelitian dan pengambangan)'], 
		'uses' =>'PkmLokasiMatkulController@store'
		]);
		
		Route::get('/pkm/matkul/{id}/delete', [
		'as' => 'mahasiswa.pkm.lokasi.matkul.delete',
		'roles' => ['administrator', 'akademik', 'prodi', 'p3m (pusat penelitian dan pengambangan)'], 
		'uses' =>'PkmLokasiMatkulController@destroy'
		]);
		
		//Pendamping Lokasi PKM
		Route::get('/pkm/{pkm_id}/lokasi/{lokasi_id}/pendamping/create', [
		'as' => 'mahasiswa.pkm.lokasi.pendamping.create',
		'roles' => ['administrator', 'akademik', 'prodi', 'p3m (pusat penelitian dan pengambangan)'], 
		'uses' =>'PkmLokasiDosenController@create'
		]);
		Route::post('/pkm/{pkm_id}/lokasi/{lokasi_id}/pendamping', [
		'as' => 'mahasiswa.pkm.lokasi.pendamping.store',
		'roles' => ['administrator', 'akademik', 'prodi', 'p3m (pusat penelitian dan pengambangan)'], 
		'uses' =>'PkmLokasiDosenController@store'
		]);
		
		Route::get('/pkm/pendamping/{id}/delete', [
		'as' => 'mahasiswa.pkm.lokasi.pendamping.delete',
		'roles' => ['administrator', 'akademik', 'prodi', 'p3m (pusat penelitian dan pengambangan)'], 
		'uses' =>'PkmLokasiDosenController@destroy'
		]);
		
		//Lokasi PKM
		Route::get('/pkm/{id}/lokasi/create', [
		'as' => 'mahasiswa.pkm.lokasi.create',
		'roles' => ['administrator', 'akademik', 'prodi', 'p3m (pusat penelitian dan pengambangan)'], 
		'uses' =>'PkmLokasiController@create'
		]);
		Route::post('/pkm/{id}/lokasi', [
		'as' => 'mahasiswa.pkm.lokasi.store',
		'roles' => ['administrator', 'akademik', 'prodi', 'p3m (pusat penelitian dan pengambangan)'], 
		'uses' =>'PkmLokasiController@store'
		]);	
		Route::get('/pkm/{id}/lokasi/{lokasi_id}/edit', [
		'as' => 'mahasiswa.pkm.lokasi.edit',
		'roles' => ['administrator', 'akademik', 'prodi', 'p3m (pusat penelitian dan pengambangan)'], 
		'uses' =>'PkmLokasiController@edit'
		]);
		Route::patch('/pkm/{id}/lokasi/{lokasi_id}', [
		'as' => 'mahasiswa.pkm.lokasi.update',
		'roles' => ['administrator', 'akademik', 'prodi', 'p3m (pusat penelitian dan pengambangan)'], 
		'uses' =>'PkmLokasiController@update'
		]);
		Route::get('/pkm/lokasi/{lokasi_id}/delete', [
		'as' => 'mahasiswa.pkm.lokasi.delete',
		'roles' => ['administrator', 'akademik', 'prodi', 'p3m (pusat penelitian dan pengambangan)'], 
		'uses' =>'PkmLokasiController@destroy'
		]);
		
		//PKM
		Route::get('/pkm', [
		'as' => 'mahasiswa.pkm.index',
		'roles' => ['administrator', 'akademik', 'prodi', 'dosen', 'p3m (pusat penelitian dan pengambangan)'], 
		'uses' =>'PkmController@index'
		]);
		Route::get('/pkm/create', [
		'as' => 'mahasiswa.pkm.create',
		'roles' => ['administrator', 'akademik', 'prodi', 'p3m (pusat penelitian dan pengambangan)'], 
		'uses' =>'PkmController@create'
		]);
		Route::post('/pkm', [
		'as' => 'mahasiswa.pkm.store',
		'roles' => ['administrator', 'akademik', 'prodi', 'p3m (pusat penelitian dan pengambangan)'], 
		'uses' =>'PkmController@store'
		]);
		
		Route::get('/pkm/{id}/edit', [
		'as' => 'mahasiswa.pkm.edit',
		'roles' => ['administrator', 'akademik', 'prodi', 'p3m (pusat penelitian dan pengambangan)'], 
		'uses' =>'PkmController@edit'
		]);
		Route::patch('/pkm/{id}', [
		'as' => 'mahasiswa.pkm.update',
		'roles' => ['administrator', 'akademik', 'prodi', 'p3m (pusat penelitian dan pengambangan)'], 
		'uses' =>'PkmController@update'
		]);
		Route::get('/pkm/{id}/delete', [
		'as' => 'mahasiswa.pkm.delete',
		'roles' => ['administrator', 'akademik', 'prodi', 'p3m (pusat penelitian dan pengambangan)'], 
		'uses' =>'PkmController@destroy'
		]);
		
		
		/*
			15 Okt 2016
			Wisuda
		*/		
		Route::get('/wisuda/{mahasiswa_id}/status', [
		'as' => 'mahasiswa.wisuda.status',
		'roles' => ['administrator', 'prodi', 'akademik'], 
		'uses' =>'MahasiswaController@statusWisuda'
		]);
		
		Route::get('/wisuda/{id}/peserta/{mhs}/cetak', [
		'as' => 'mahasiswa.wisuda.peserta.cetak',
		'roles' => ['administrator', 'akademik'], 
		'uses' =>'WisudaController@cetakFormulir'
		]);
		Route::get('/wisuda/cetakformulir', [
		'as' => 'mahasiswa.wisuda.peserta.cetak2',
		'roles' => ['mahasiswa'], 
		'uses' =>'WisudaController@cetakFormulir2'
		]);
		
		Route::get('/wisuda', [
		'as' => 'mahasiswa.wisuda.index',
		'roles' => ['administrator', 'akademik', 'prodi'], 
		'uses' =>'WisudaController@index'
		]);
		Route::get('/wisuda/create', [
		'as' => 'mahasiswa.wisuda.create',
		'roles' => ['administrator', 'akademik'], 
		'uses' =>'WisudaController@create'
		]);
		Route::post('/wisuda/create', [
		'as' => 'mahasiswa.wisuda.store',
		'roles' => ['administrator', 'akademik'], 
		'uses' =>'WisudaController@store'
		]);
		Route::get('/wisuda/{id}/peserta', [
		'as' => 'mahasiswa.wisuda.peserta',
		'roles' => ['administrator', 'akademik', 'prodi'], 
		'uses' =>'WisudaController@peserta'
		]);
		Route::get('/wisuda/{id}/peserta/export', [
		'as' => 'mahasiswa.wisuda.peserta.export',
		'roles' => ['administrator', 'akademik'], 
		'uses' =>'WisudaController@export'
		]);
		Route::get('/wisuda/{id}/peserta/{mhs}/hapus', [
		'as' => 'mahasiswa.wisuda.peserta.delete',
		'roles' => ['administrator', 'akademik'], 
		'uses' =>'WisudaController@hapusPeserta'
		]);
		
		Route::get('/wisuda/{id}/peserta/{mhs}', [
		'as' => 'mahasiswa.wisuda.peserta.show',
		'roles' => ['administrator', 'akademik', 'prodi'], 
		'uses' =>'WisudaController@showPeserta'
		]);
		Route::get('/wisuda/{id}/edit', [
		'as' => 'mahasiswa.wisuda.edit',
		'roles' => ['administrator', 'akademik'], 
		'uses' =>'WisudaController@edit'
		]);
		Route::patch('/wisuda/{id}', [
		'as' => 'mahasiswa.wisuda.update',
		'roles' => ['administrator', 'akademik'], 
		'uses' =>'WisudaController@update'
		]);
		Route::get('/wisuda/{id}/delete', [
		'as' => 'mahasiswa.wisuda.delete',
		'roles' => ['administrator', 'akademik'], 
		'uses' =>'WisudaController@destroy'
		]);
		
		Route::get('/wisuda/daftar', [
		'as' => 'mahasiswa.wisuda.formdaftar',
		'roles' => ['mahasiswa'], 
		'uses' =>'WisudaController@formDaftarWisuda'
		]);
		Route::post('/wisuda/daftar', [
		'as' => 'mahasiswa.wisuda.daftar',
		'roles' => ['mahasiswa'], 
		'uses' =>'WisudaController@daftarWisuda'
		]);
		
		/**
			* 05 Apr 2016
			* Absensi Dosen
		**/
		Route::get('dosen/absensi/{month?}/{year?}', [
		'as' => 'dosen.absensi.index', 
		'roles' => ['administrator', 'keuangan / administrasi', 'akademik'],
		'uses' =>'AbsensiDosenController@index'
		])
		-> where(['month' => '[0-9]+', 'year' => '[0-9]+']);
		Route::get('dosen/absensi/create/{d?}/{m?}/{y?}/{id?}/{st?}', [
		'as' => 'dosen.absensi.create', 
		'roles' => ['administrator', 'keuangan / administrasi', 'akademik'],
		'uses' =>'AbsensiDosenController@create'
		]);
		Route::post('dosen/absensi', [
		'as' => 'dosen.absensi.store', 
		'roles' => ['administrator', 'keuangan / administrasi', 'akademik'],
		'uses' =>'AbsensiDosenController@store'
		]);
		
		/**
			* Ruangan
		**/
		Route::get('ruangan', [
		'as' => 'ruangan.index', 
		'roles' => ['administrator', 'akademik'],
		'uses' =>'RuanganController@index'
		]);
		Route::get('ruangan/create', [
		'as' => 'ruangan.create', 
		'roles' => ['administrator', 'akademik'],
		'uses' =>'RuanganController@create'
		]);
		Route::get('ruangan/{ruangan}/edit', [
		'as' => 'ruangan.edit', 
		'roles' => ['administrator', 'akademik'],
		'uses' =>'RuanganController@edit'
		]);
		Route::patch('ruangan/{ruangan}', [
		'as' => 'ruangan.update', 
		'roles' => ['administrator', 'akademik'],
		'uses' =>'RuanganController@update'
		]);
		Route::post('ruangan', [
		'as' => 'ruangan.store', 
		'roles' => ['administrator', 'akademik'],
		'uses' =>'RuanganController@store'
		]);
		
		/**
			* Kelas
		**/
		Route::get('kelas', [
		'as' => 'kelas.index', 
		'roles' => ['administrator', 'akademik'],
		'uses' =>'KelasController@index'
		]);
		Route::get('kelas/create', [
		'as' => 'kelas.create', 
		'roles' => ['administrator', 'akademik'],
		'uses' =>'KelasController@create'
		]);
		Route::get('kelas/{kelas}/edit', [
		'as' => 'kelas.edit', 
		'roles' => ['administrator', 'akademik'],
		'uses' =>'KelasController@edit'
		]);
		Route::patch('kelas/{kelas}', [
		'as' => 'kelas.update', 
		'roles' => ['administrator', 'akademik'],
		'uses' =>'KelasController@update'
		]);
		Route::post('kelas', [
		'as' => 'kelas.store', 
		'roles' => ['administrator', 'akademik'],
		'uses' =>'KelasController@store'
		]);
		
		/**
			* PIMPINAN PT
		**/
		Route::get('pimpinan', [
		'as' => 'pimpinan.index', 
		'roles' => ['administrator'],
		'uses' =>'PimpinanController@index'
		]);
		Route::get('pimpinan/create', [
		'as' => 'pimpinan.create', 
		'roles' => ['administrator'],
		'uses' =>'PimpinanController@create'
		]);
		Route::post('pimpinan/store', [
		'as' => 'pimpinan.store', 
		'roles' => ['administrator'],
		'uses' =>'PimpinanController@store'
		]);
		Route::get('pimpinan/detail', [
		'as' => 'pimpinan.detail', 
		'roles' => ['administrator'],
		'uses' =>'PimpinanController@detail'
		]);
		Route::get('pimpinan/{pimpinan}/edit', [
		'as' => 'pimpinan.edit', 
		'roles' => ['administrator'],
		'uses' =>'PimpinanController@edit'
		]);
		Route::patch('pimpinan/{pimpinan}', [
		'as' => 'pimpinan.update', 
		'roles' => ['administrator'],
		'uses' =>'PimpinanController@update'
		]);
		Route::get('pimpinan/{pimpinan}/delete', [
		'as' => 'pimpinan.delete', 
		'roles' => ['administrator'],
		'uses' =>'PimpinanController@destroy'
		]); 
		
		/**
			* PRODI
		**/
		
		//History
		Route::get('prodi/{prodi}/riwayat', [
		'as' => 'prodi.riwayat.index', 
		'roles' => ['administrator', 'akademik'],
		'uses' =>'ProdiRiwayatController@index'
		]);
		Route::get('prodi/{prodi}/riwayat/create', [
		'as' => 'prodi.riwayat.create', 
		'roles' => ['administrator', 'akademik'],
		'uses' =>'ProdiRiwayatController@create'
		]);
		Route::post('prodi/{prodi}/riwayat/store', [
		'as' => 'prodi.riwayat.store', 
		'roles' => ['administrator', 'akademik'],
		'uses' =>'ProdiRiwayatController@store'
		]);Route::get('prodi/{prodi}/riwayat/{riwayat}/edit', [
		'as' => 'prodi.riwayat.edit', 
		'roles' => ['administrator', 'akademik'],
		'uses' =>'ProdiRiwayatController@edit'
		]);
		Route::patch('prodi/{prodi}/riwayat/{riwayat}', [
		'as' => 'prodi.riwayat.update', 
		'roles' => ['administrator', 'akademik'],
		'uses' =>'ProdiRiwayatController@update'
		]);
		Route::get('prodi/{prodi}/riwayat/{riwayat}/delete', [
		'as' => 'prodi.riwayat.delete', 
		'roles' => ['administrator', 'akademik'],
		'uses' =>'ProdiRiwayatController@destroy'
		]); 
		
		
		Route::get('prodi', [
		'as' => 'prodi.index', 
		'roles' => ['administrator', 'akademik'],
		'uses' =>'ProdiController@index'
		]);
		Route::get('prodi/create', [
		'as' => 'prodi.create', 
		'roles' => ['administrator', 'akademik'],
		'uses' =>'ProdiController@create'
		]);
		Route::post('prodi/store', [
		'as' => 'prodi.store', 
		'roles' => ['administrator', 'akademik'],
		'uses' =>'ProdiController@store'
		]);
		Route::get('prodi/detail', [
		'as' => 'prodi.detail', 
		'roles' => ['prodi'],
		'uses' =>'ProdiController@detail'
		]);
		Route::get('prodi/{prodi}/edit', [
		'as' => 'prodi.edit', 
		'roles' => ['administrator', 'akademik'],
		'uses' =>'ProdiController@edit'
		]);
		Route::patch('prodi/{prodi}', [
		'as' => 'prodi.update', 
		'roles' => ['administrator', 'akademik'],
		'uses' =>'ProdiController@update'
		]);
		Route::get('prodi/{prodi}/delete', [
		'as' => 'prodi.delete', 
		'roles' => ['administrator', 'akademik'],
		'uses' =>'ProdiController@destroy'
		]); 
		
		/**
			* Jadwal
		**/
		Route::get('jadwal/cover', [
		'as' => 'jadwal.cover', 
		'roles' => ['prodi', 'administrator', 'akademik'],
		'uses' =>'JadwalController@formPrintCover'
		]);
		Route::get('jadwal/cover/print', [
		'as' => 'jadwal.cover.print', 
		'roles' => ['prodi', 'administrator', 'akademik'],
		'uses' =>'JadwalController@printCover'
		]);
		
		Route::get('jadwal', [
		'as' => 'matkul.tapel.jadwal', 
		'roles' => ['prodi', 'administrator', 'akademik'],
		'uses' =>'JadwalController@index'
		]);
		Route::get('jadwal2', [
		'as' => 'matkul.tapel.jadwal2', 
		'roles' => ['prodi', 'administrator', 'akademik'],
		'uses' =>'JadwalController@index2'
		]);
		Route::get('jadwal/create', [
		'as' => 'matkul.tapel.jadwal.create', 
		'roles' => ['prodi', 'administrator', 'akademik'],
		'uses' =>'JadwalController@create'
		]);
		Route::post('jadwal/store', [
		'as' => 'matkul.tapel.jadwal.store', 
		'roles' => ['prodi', 'administrator', 'akademik'],
		'uses' =>'JadwalController@store'
		]);
		Route::get('jadwal/{jadwal}/delete', [
		'as' => 'matkul.tapel.jadwal.delete', 
		'roles' => ['prodi', 'administrator', 'akademik'],
		'uses' =>'JadwalController@delete'
		]);
		Route::get('jadwal/{jadwal}/edit', [
		'as' => 'matkul.tapel.jadwal.edit', 
		'roles' => ['prodi', 'administrator', 'akademik'],
		'uses' =>'JadwalController@edit'
		]);
		Route::patch('jadwal/{jadwal}', [
		'as' => 'matkul.tapel.jadwal.update', 
		'roles' => ['prodi', 'administrator', 'akademik'],
		'uses' =>'JadwalController@update'
		]);
		Route::get('/jadwaldosen', [
		'roles' => ['dosen'], 
		'uses' =>'JadwalController@dosen'
		]);
		Route::get('/jadwalsemua', [
		'roles' => ['dosen'], 
		'uses' =>'JadwalController@dosenAllJadwal'
		]);
		
		/**
			* Transaksi lain
		**/
		/*
			Route::get('/transaksi', [
			'as' => 'transaksi.index',
			'roles' => ['keuangan / administrasi', 'administrator'],
			'uses' => 'TransaksiController@index'
			]);
			Route::get('/transaksi/create', [
			'as' => 'transaksi.create',
			'roles' => ['keuangan / administrasi', 'administrator'],
			'uses' => 'TransaksiController@create'
			]);
			Route::post('/transaksi', [
			'as' => 'transaksi.store',
			'roles' => ['keuangan / administrasi', 'administrator'],
			'uses' => 'TransaksiController@store'
			]);
			Route::get('/transaksi/{transaksi}', [
			'as' => 'transaksi.edit',
			'roles' => ['keuangan / administrasi'],
			'uses' => 'TransaksiController@edit'
			]);
			Route::patch('/transaksi/{transaksi}', [
			'as' => 'transaksi.update',
			'roles' => ['keuangan / administrasi'],
			'uses' => 'TransaksiController@update'
		]); */
		
		/**
			* Neraca
		**/
		Route::get('/neraca', [
		'as' => 'neraca.index',
		'roles' => ['keuangan / administrasi', 'administrator'],
		'uses' => 'NeracaController@index'
		]);
		
		/**
			* Gaji
		**/
		Route::get('/gaji', [
		'as' => 'gaji.index',
		'roles' => ['keuangan / administrasi', 'dosen', 'administrator'],
		'uses' => 'GajiController@index'
		]);
		Route::get('/gaji/create/{dosen_id}', [
		'as' => 'gaji.create',
		'roles' => ['keuangan / administrasi', 'administrator'],
		'uses' => 'GajiController@create'
		]);
		Route::post('/gaji', [
		'as' => 'gaji.store',
		'roles' => ['keuangan / administrasi', 'administrator'],
		'uses' => 'GajiController@store'
		]);
		Route::get('/gaji/{dosen_id}/{bulan}/delete', [
		'as' => 'gaji.delete',
		'roles' => ['keuangan / administrasi', 'administrator'],
		'uses' => 'GajiController@destroy'
		]);
		Route::get('/gaji/{dosen_id}/{bulan}/confirm', [
		'as' => 'gaji.confirm',
		'roles' => ['keuangan / administrasi', 'dosen', 'administrator'],
		'uses' => 'GajiController@confirm'
		]);
		
		//MAHASISWA	
		//Aktivitas Perkuliahan
		Route::get('/mahasiswa/aktivitas/{id?}', [
		'as' => 'mahasiswa.aktivitas',
		'roles' => ['administrator', 'akademik', 'dosen', 'mahasiswa'],
		'uses' => 'MahasiswaController@Aktivitas'
		]);	
		
		Route::get('/mahasiswa/{id}/kemajuan', [
		'as' => 'mahasiswa.kemajuan',
		'roles' => ['administrator', 'akademik', 'dosen', 'mahasiswa'],
		'uses' => 'MahasiswaController@Kemajuan'
		]);	
		
		Route::get('/mahasiswa/{id}/transkrip', [
		'as' => 'mahasiswa.transkrip',
		'roles' => ['administrator', 'akademik', 'dosen', 'prodi'],
		'uses' => 'MahasiswaController@transkrip'
		]);	
		Route::get('/mahasiswa/{id}/transkrip/print', [
		'as' => 'mahasiswa.transkrip.print',
		'roles' => ['administrator', 'akademik', 'dosen', 'prodi'],
		'uses' => 'MahasiswaController@printTranskrip'
		]);	
		
		Route::get('/mahasiswa/create', [
		'as' => 'mahasiswa.create',
		'roles' => ['administrator', 'akademik'],
		'uses' => 'MahasiswaController@create'
		]);	
		Route::post('/mahasiswa', [
		'as' => 'mahasiswa.store',
		'roles' => ['administrator', 'akademik'],
		'uses' => 'MahasiswaController@store'
		]);	
		Route::get('/mahasiswa/{id}/edit', [
		'as' => 'mahasiswa.edit',
		'roles' => ['administrator', 'akademik'],
		'uses' => 'MahasiswaController@edit'
		]);	
		Route::patch('/mahasiswa/{id}', [
		'as' => 'mahasiswa.update',
		'roles' => ['administrator', 'akademik'],
		'uses' => 'MahasiswaController@update'
		]);	
		Route::delete('/mahasiswa/{id}', [
		'as' => 'mahasiswa.destroy',
		'roles' => ['administrator', 'akademik'],
		'uses' => 'MahasiswaController@destroy'
		]);		
		Route::get('/mahasiswa', [
		'as' => 'mahasiswa.index',
		'roles' => ['administrator', 'akademik', 'ketua', 'keuangan / administrasi', 'prodi'],
		'uses' => 'MahasiswaController@index'
		]);	
		
		Route::get('/mahasiswa/{nim}/khs/cetak/{ta?}', [
		'as' => 'mahasiswa.khs.cetak', 
		'roles' => ['administrator', 'akademik', 'prodi'],
		'uses' =>'MahasiswaController@cetakKhs'
		]);
		Route::get('/mahasiswa/{nim}/khs/{ta?}', [
		'as' => 'mahasiswa.khs', 
		'roles' => ['administrator', 'akademik', 'dosen', 'prodi'],
		'uses' =>'MahasiswaController@viewKhs'
		]);
		
		//DELETE KELAS KULIAH di KHS
		Route::get('/mahasiswa/{nim}/kelas/{mtid}/delete', [
		'as' => 'mahasiswa.kelas.delete', 
		'roles' => ['root', 'administrator'],
		'uses' =>'NilaiController@deleteKelas'
		]);
		
		
		Route::post('/mahasiswa/angkatan/{docheck?}', [
		'roles' => ['administrator', 'akademik'],
		'uses' => 'MahasiswaController@angkatan'
		]);
		Route::get('/mahasiswa/search', [
		'roles' => ['administrator', 'akademik', 'ketua', 'keuangan / administrasi', 'prodi'],
		'uses' => 'MahasiswaController@search'
		]);
		Route::get('/mahasiswa/search/{q}', [
		'as' => 'mahasiswa.search', 
		'roles' => ['administrator', 'akademik', 'ketua', 'keuangan / administrasi', 'prodi'],
		'uses' => 'MahasiswaController@search'
		]);	
		Route::get('/mahasiswa/filter', [
		'roles' => ['administrator', 'akademik', 'ketua', 'keuangan / administrasi', 'prodi'],
		'uses' => 'MahasiswaController@filter'
		]);	
		
		Route::get('/mahasiswa/{id}', [
		'as' => 'mahasiswa.show',
		'roles' => ['administrator', 'akademik', 'prodi', 'mahasiswa', 'keuangan / administrasi'],
		'uses' => 'MahasiswaController@show'
		]);		
		
		//Pembayaran
		Route::get('/pembayaran/token', [
		'as' => 'pembayaran.token',
		'roles' => ['keuangan / administrasi', 'administrator'],
		'uses' => 'PembayaranController@createWithToken'
		]);
		Route::post('/pembayaran/token', [
		'as' => 'pembayaran.token.post',
		'roles' => ['keuangan / administrasi', 'administrator'],
		'uses' => 'PembayaranController@storeWithToken'
		]);
		Route::get('/pembayaran/form', [
		'as' => 'biayakuliah.form',
		'roles' => ['keuangan / administrasi', 'administrator'],
		'uses' => 'PembayaranController@create'
		]);
		// Route::get('/biaya/form', [
		// 'as' => 'biayakuliah.form',
		// 'roles' => ['keuangan / administrasi', 'administrator'],
		// 'uses' => 'PembayaranController@create'
		// ]);
		Route::post('/biaya/form', [
		'as' => 'biayakuliah.form.submit',
		'roles' => ['keuangan / administrasi', 'administrator'],
		'uses' => 'PembayaranController@store'
		]);		
		Route::get('/biaya/{nim}/cetak/status', [
		'as' => 'biaya.mahasiswa.status',
		'roles' => ['keuangan / administrasi', 'administrator'],
		'uses' => 'PembayaranController@printStatus'
		]);
		Route::get('/biaya/{id}/cetak/kwitansi', [
		'as' => 'biaya.mahasiswa.receipt',
		'roles' => ['keuangan / administrasi', 'administrator'],
		'uses' => 'PembayaranController@printReceipt'
		]);		
		Route::get('/biaya/{id}/delete', [
		'as' => 'biaya.delete',
		'roles' => ['keuangan / administrasi', 'administrator'],
		'uses' => 'PembayaranController@destroy'
		]);
		Route::get('/biaya', [
		'as' => 'biaya.index',
		'roles' => ['keuangan / administrasi', 'administrator'],
		'uses' => 'PembayaranController@index'
		]);
		
		Route::get('/biaya/detail', [
		'as' => 'biaya.detail',
		'roles' => ['keuangan / administrasi', 'administrator'],
		'uses' => 'TagihanController@rincian'
		]);	
		Route::get('/pembayaran/mahasiswa/{id?}', [
		'as' => 'mahasiswa.pembayaran',
		'roles' => ['keuangan / administrasi', 'administrator', 'mahasiswa', 'dosen'],
		'uses' => 'PembayaranController@pembayaran'
		]);	
		
		Route::get('/pembayaran/riwayat/mahasiswa', [
		'as' => 'mahasiswa.pembayaran.riwayat',
		'roles' => ['mahasiswa'],
		'uses' => 'PembayaranController@pembayaran'
		]);	
		Route::get('/pembayaran/status/mahasiswa', [
		'as' => 'mahasiswa.pembayaran.status',
		'roles' => ['mahasiswa'],
		'uses' => 'TagihanController@tagihan'
		]);
		Route::get('/tagihan/status/mahasiswa', [
		'as' => 'mahasiswa.tagihan.status',
		'roles' => ['mahasiswa'],
		'uses' => 'TagihanController@tagihanToken'
		]);
		Route::get('/tagihan/{tagihan_id}/bayar/mahasiswa/{golongan?}', [
		'as' => 'mahasiswa.tagihan.token',
		'roles' => ['mahasiswa'],
		'uses' => 'TagihanController@getTagihanToken'
		]);
		
		//Tagihan		
		Route::get('/tagihan/mahasiswa/{id?}', [
		'as' => 'mahasiswa.tagihan',
		'roles' => ['keuangan / administrasi', 'administrator', 'mahasiswa', 'dosen'],
		'uses' => 'TagihanController@tagihan'
		]);	
		Route::get('/tagihan/create', [
		'as' => 'tagihan.create',
		'roles' => ['keuangan / administrasi', 'administrator'],
		'uses' => 'TagihanController@create'
		]);
		Route::post('/tagihan', [
		'as' => 'tagihan.store',
		'roles' => ['keuangan / administrasi', 'administrator'],
		'uses' => 'TagihanController@store'
		]);
		Route::get('/tagihan', [
		'as' => 'tagihan.index',
		'roles' => ['keuangan / administrasi', 'administrator'],
		'uses' => 'TagihanController@index'
		]);
		Route::get('/tagihan/{id}/delete', [
		'as' => 'tagihan.delete',
		'roles' => ['keuangan / administrasi', 'administrator'],
		'uses' => 'TagihanController@destroy'
		]);
		
		//Privilege Pembayaran
		Route::get('/tagihan/privilege/golongan/{golongan_id}', [
		'as' => 'tagihan.privilege.golongan',
		'roles' => ['pesantren'],
		'uses' => 'TagihanController@privilegeGolongan'
		]);	
		Route::get('/tagihan/{golongan_id}/{mahasiswa_id}/edit/{privilege}/{value}', [
		'as' => 'tagihan.update.golongan',
		'roles' => ['pesantren'],
		'uses' => 'TagihanController@updateGolongan'
		]);
		
		Route::get('/tagihan/privilege', [
		'as' => 'tagihan.privilege',
		'roles' => ['keuangan / administrasi', 'administrator'],
		'uses' => 'TagihanController@privilege'
		]);	
		Route::get('/tagihan/{id}/edit', [
		'as' => 'tagihan.edit',
		'roles' => ['keuangan / administrasi', 'administrator', 'pesantren'],
		'uses' => 'TagihanController@edit'
		]);
		Route::patch('/tagihan/{id}', [
		'as' => 'tagihan.update',
		'roles' => ['keuangan / administrasi', 'administrator', 'pesantren'],
		'uses' => 'TagihanController@update'
		]);	
		
		Route::get('/tagihan/{id}/unlock', [
		'as' => 'tagihan.unlock',
		'roles' => ['keuangan / administrasi', 'administrator', 'pesantren'],
		'uses' => 'TagihanController@unlock'
		]);	
		
		//SETUP BIAYA
		Route::post('/biaya/setup/copy', [
		'as' => 'biaya.setup.copy',
		'roles' => ['keuangan / administrasi', 'administrator'],
		'uses' => 'BiayaKuliahController@setup_copy'
		]);
		Route::get('/biaya/setup', [
		'as' => 'biaya.setup.index',
		'roles' => ['keuangan / administrasi', 'administrator'],
		'uses' => 'BiayaKuliahController@index'
		]);
		Route::get('/biaya/setup/create', [
		'as' => 'biaya.setup.create',
		'roles' => ['keuangan / administrasi', 'administrator'],
		'uses' => 'BiayaKuliahController@create'
		]);
		Route::post('/biaya/setup', [
		'as' => 'biaya.setup.store',
		'roles' => ['keuangan / administrasi', 'administrator'],
		'uses' => 'BiayaKuliahController@store'
		]);
		Route::get('/biaya/setup/{jenis_biaya_id}/{angkatan}/{prodi_id}/{kelas_id}/{jenis_pembayaran}/edit', [
		'as' => 'biaya.setup.edit',
		'roles' => ['keuangan / administrasi', 'administrator'],
		'uses' => 'BiayaKuliahController@edit'
		]);
		
		Route::patch('/biaya/setup', [
		'as' => 'biaya.setup.update',
		'roles' => ['keuangan / administrasi', 'administrator'],
		'uses' => 'BiayaKuliahController@update'
		]);
		Route::get('/biaya/setup/{jenis_biaya_id}/{angkatan}/{prodi_id}/{kelas_id}/{jenis_pembayaran}/delete', [
		'as' => 'biaya.setup.delete',
		'roles' => ['keuangan / administrasi', 'administrator'],
		'uses' => 'BiayaKuliahController@destroy'
		]);
		
		Route::match(['get', 'post'], '/biaya/submit', [
		'as' => 'biayakuliah.setup.submit',
		'roles' => ['keuangan / administrasi', 'administrator'],
		'uses' => 'BiayaKuliahController@setupSubmit'
		]);
		
		/**
			* Jenis Gaji
		**/
		Route::get('/jenisgaji/{jenisgaji}/delete', [
		'as' => 'jenisgaji.delete',
		'roles' => ['keuangan / administrasi', 'administrator'],
		'uses' => 'JenisGajiController@destroy'
		]);
		Route::get('/jenisgaji', [
		'as' => 'jenisgaji.index',
		'roles' => ['keuangan / administrasi', 'administrator'],
		'uses' => 'JenisGajiController@index'
		]);
		Route::get('/jenisgaji/create', [
		'as' => 'jenisgaji.create',
		'roles' => ['keuangan / administrasi', 'administrator'],
		'uses' => 'JenisGajiController@create'
		]);
		Route::post('/jenisgaji', [
		'as' => 'jenisgaji.store',
		'roles' => ['keuangan / administrasi', 'administrator'],
		'uses' => 'JenisGajiController@store'
		]);
		Route::get('/jenisgaji/{jenisgaji}/edit', [
		'as' => 'jenisgaji.edit',
		'roles' => ['keuangan / administrasi', 'administrator'],
		'uses' => 'JenisGajiController@edit'
		]);
		Route::patch('/jenisgaji/{jenisgaji}', [
		'as' => 'jenisgaji.update',
		'roles' => ['keuangan / administrasi', 'administrator'],
		'uses' => 'JenisGajiController@update'
		]);
		
		/**
			* Jenis Biaya
		**/
		Route::get('/jenisbiaya', [
		'as' => 'jenisbiaya.index',
		'roles' => ['keuangan / administrasi', 'administrator'],
		'uses' => 'JenisBiayaController@index'
		]);
		Route::get('/jenisbiaya/create', [
		'as' => 'jenisbiaya.create',
		'roles' => ['keuangan / administrasi', 'administrator'],
		'uses' => 'JenisBiayaController@create'
		]);
		Route::post('/jenisbiaya', [
		'as' => 'jenisbiaya.store',
		'roles' => ['keuangan / administrasi', 'administrator'],
		'uses' => 'JenisBiayaController@store'
		]);
		Route::get('/jenisbiaya/{jenisbiaya}', [
		'as' => 'jenisbiaya.edit',
		'roles' => ['keuangan / administrasi', 'administrator'],
		'uses' => 'JenisBiayaController@edit'
		]);
		Route::get('/jenisbiaya/{jenisbiaya}/delete', [
		'as' => 'jenisbiaya.delete',
		'roles' => ['keuangan / administrasi', 'administrator'],
		'uses' => 'JenisBiayaController@destroy'
		]);
		Route::patch('/jenisbiaya/{jenisbiaya}', [
		'as' => 'jenisbiaya.update',
		'roles' => ['keuangan / administrasi', 'administrator'],
		'uses' => 'JenisBiayaController@update'
		]);
		
		
		/*
			Ketua
		*/
		Route::get('informasi',[
		'as' => 'informasi.index', 
		'roles' => ['administrator', 'ketua', 'akademik'],
		'uses' =>'InformasiController@index'
		]);
		Route::get('informasi/{id}/show',[
		'as' => 'informasi.show', 
		'roles' => ['administrator', 'ketua', 'akademik'],
		'uses' =>'InformasiController@show'
		]);
		Route::patch('informasi/{id}/edit',[
		'as' => 'informasi.update', 
		'roles' => ['administrator', 'ketua', 'akademik'],
		'uses' =>'InformasiController@update'
		]);
		Route::get('informasi/{id}/edit',[
		'as' => 'informasi.edit', 
		'roles' => ['administrator', 'ketua', 'akademik'],
		'uses' =>'InformasiController@edit'
		]);
		Route::post('informasi',[
		'as' => 'informasi.store', 
		'roles' => ['administrator', 'ketua', 'akademik'],
		'uses' =>'InformasiController@store'
		]);
		Route::get('informasi/create',[
		'as' => 'informasi.create', 
		'roles' => ['administrator', 'ketua', 'akademik'],
		'uses' =>'InformasiController@create'
		]);
		
		/*
			Kelas kuliah
		*/
		Route::get('perkuliahan', [
		'as' => 'matkul.tapel.perkuliahan', 
		'roles' => ['prodi'],
		'uses' =>'MatkulTapelController@index'
		]);
		
		Route::post('matkul/tapel/getangkatanlist', [
		'as' => 'matkul.tapel.getangkatanlist', 
		'roles' => ['administrator', 'akademik'],
		'uses' =>'MatkulTapelController@getAngkatanList'
		]);
		Route::post('matkul/tapel/getmatkullist', [
		'as' => 'matkul.tapel.getmatkullist', 
		'roles' => ['administrator', 'akademik'],
		'uses' =>'MatkulTapelController@getMatkulList'
		]);		
		
		//Komponen Penilaian
		Route::get('/jenisnilai',[
		'as' => 'jenisnilai.index', 
		'roles' => ['administrator', 'akademik'],
		'uses' =>'JenisNilaiController@index'
		]);
		Route::get('/jenisnilai/create', [
		'as' => 'jenisnilai.create', 
		'roles' => ['administrator', 'akademik'],
		'uses' =>'JenisNilaiController@create'
		]);
		Route::post('/jenisnilai',[
		'as' => 'jenisnilai.store',
		'roles' => ['administrator', 'akademik'],
		'uses' =>'JenisNilaiController@store'
		]);
		Route::get('/jenisnilai/{jenis_nilai_id}/edit', [
		'as' => 'jenisnilai.edit', 
		'roles' => ['administrator', 'akademik'],
		'uses' =>'JenisNilaiController@edit'
		]);
		Route::patch('/jenisnilai/{jenis_nilai_id}',[
		'as' => 'jenisnilai.update',
		'roles' => ['administrator', 'akademik'],
		'uses' =>'JenisNilaiController@update'
		]);
		Route::get('/jenisnilai/{jenis_nilai_id}/delete', [
		'as' => 'jenisnilai.delete', 
		'roles' => ['administrator', 'akademik'],
		'uses' =>'JenisNilaiController@destroy'
		]);
		
		//MatkulTapel >> Kelas Perkuliahan NEW
		Route::get('/kelasperkuliahan', [
		'as' => 'kelasperkuliahan.index', 
		'roles' => ['administrator', 'akademik'],
		'uses' =>'MatkulTapelController@index2'
		]);
		Route::get('/kelasperkuliahan/{matkul_tapel_id}/edit', [
		'as' => 'kelasperkuliahan.edit', 
		'roles' => ['administrator', 'akademik'],
		'uses' =>'MatkulTapelController@edit2'
		]);
		Route::patch('/kelasperkuliahan/{matkul_tapel_id}', [
		'as' => 'kelasperkuliahan.update', 
		'roles' => ['administrator', 'akademik'],
		'uses' => 'MatkulTapelController@update2'
		]);
		Route::get('/kelasperkuliahan/{kurikulum_matkul_id}/create/{tapel_id?}', [
		'as' => 'kelasperkuliahan.create',
		'roles' => ['administrator'],
		'uses' => 'MatkulTapelController@create2'
		]);
		Route::post('/kelasperkuliahan/{kurikulum_matkul_id}', [
		'as' => 'kelasperkuliahan.store',
		'roles' => ['administrator'],
		'uses' => 'MatkulTapelController@store2'
		]);
		
		//LOCK Kelas Kuliah
		Route::get('/matkul/tapel/{matkul_tapel_id}/lock', [
		'as' => 'matkul.tapel.lock', 
		'roles' => ['administrator', 'akademik', 'dosen'],
		'uses' =>'MatkulTapelController@lock'
		]);
		Route::post('/matkul/tapel/{matkul_tapel_id}/unlock', [
		'as' => 'matkul.tapel.unlock', 
		'roles' => ['administrator', 'akademik'],
		'uses' =>'MatkulTapelController@unlock'
		]);
		
		
		//DISKUSI		
		Route::get('matkul/tapel/{kelas}/diskusi', [
		'as' => 'matkul.tapel.diskusi.index', 
		'roles' => ['administrator', 'akademik'],
		'uses' => 'DiskusiPembelajaranController@index'
		]);
		
		//ANGGOTA		
		Route::get('matkul/tapel/{kelas}/anggota', [
		'as' => 'matkul.tapel.anggota.index', 
		'roles' => ['administrator', 'akademik'],
		'uses' => 'AnggotaPembelajaranController@index'
		]);
		
		//LAPORAN		
		Route::get('matkul/tapel/{kelas}/laporan', [
		'as' => 'matkul.tapel.laporan.index', 
		'roles' => ['administrator', 'akademik'],
		'uses' => 'LaporanPembelajaranController@index'
		]);
		
		//SESI PEMBELAJARAN	> Materi	
		Route::get('matkul/tapel/{kelas}/sesi/{sesi}/materi', [
		'as' => 'matkul.tapel.sesi.materi.index', 
		'roles' => ['administrator', 'akademik'],
		'uses' => 'MateriPembelajaranController@index'
		]);
		
		//SESI PEMBELAJARAN		
		Route::get('matkul/tapel/{kelas}/sesi', [
		'as' => 'matkul.tapel.sesi.index', 
		'roles' => ['administrator', 'akademik'],
		'uses' => 'SesiPembelajaranController@index'
		]);
		Route::get('matkul/tapel/{kelas}/sesi/create', [
		'as' => 'matkul.tapel.sesi.create', 
		'roles' => ['administrator', 'akademik'],
		'uses' => 'SesiPembelajaranController@create'
		]);
		Route::post('matkul/tapel/{kelas}/sesi', [
		'as' => 'matkul.tapel.sesi.store', 
		'roles' => ['administrator', 'akademik'],
		'uses' => 'SesiPembelajaranController@store'
		]);
		Route::get('matkul/tapel/{kelas}/sesi/{sesi}/edit', [
		'as' => 'matkul.tapel.sesi.edit', 
		'roles' => ['administrator', 'akademik'],
		'uses' => 'SesiPembelajaranController@edit'
		]);
		Route::patch('matkul/tapel/{kelas}/sesi/{sesi}', [
		'as' => 'matkul.tapel.sesi.update', 
		'roles' => ['administrator', 'akademik'],
		'uses' => 'SesiPembelajaranController@update'
		]);
		Route::get('matkul/tapel/{kelas}/sesi/{sesi}/delete', [
		'as' => 'matkul.tapel.sesi.delete', 
		'roles' => ['administrator', 'akademik'],
		'uses' => 'SesiPembelajaranController@destroy'
		]);
		Route::get('matkul/tapel/{kelas}/sesi/{sesi}/duplicate', [
		'as' => 'matkul.tapel.sesi.duplicate', 
		'roles' => ['administrator', 'akademik'],
		'uses' => 'SesiPembelajaranController@duplicate'
		]);
		
		//KELAS KULIAH		
		Route::get('matkul/tapel', [
		'as' => 'matkul.tapel.index', 
		'roles' => ['administrator', 'akademik'],
		'uses' =>'MatkulTapelController@index'
		]);		
		
		Route::get('matkul/tapel/{matkul_tapel_id}/delete', [
		'as' => 'matkul.tapel.delete', 
		'roles' => ['administrator', 'akademik'],
		'uses' =>'MatkulTapelController@destroy'
		]);
		Route::get('matkul/tapel/{matkul_tapel_id}/cetak/formabsensi', [
		'as' => 'matkul.tapel.print.formabsensi', 
		'roles' => ['administrator', 'akademik', 'prodi'],
		'uses' =>'MatkulTapelController@cetakFormAbsensi'
		]);
		
		Route::get('matkul/tapel/{matkul_tapel_id}/edit', [
		'as' => 'matkul.tapel.edit', 
		'roles' => ['administrator', 'akademik'],
		'uses' =>'MatkulTapelController@edit'
		]);
		Route::patch('matkul/tapel/{matkul_tapel_id}', [
		'as' => 'matkul.tapel.update', 
		'roles' => ['administrator', 'akademik'],
		'uses' => 'MatkulTapelController@update'
		]);
		
		Route::get('matkul/tapel/create', [
		'as' => 'matkul.tapel.create',
		'roles' => ['administrator', 'akademik'],
		'uses' => 'MatkulTapelController@create'
		]);
		Route::post('matkul/tapel', [
		'as' => 'matkul.tapel.store', 
		'roles' => ['administrator', 'akademik'],
		'uses' => 'MatkulTapelController@store'
		]);
		
		Route::get('matkul/tapel/{matkul_tapel_id}/mahasiswa', [
		'as' => 'matkul.tapel.addmhs', 
		'roles' => ['administrator', 'akademik'],
		'uses' =>'MatkulTapelController@AddMhs'
		]);
		Route::post('matkul/tapel/{matkul_tapel_id}/mahasiswa/in', [
		'as' => 'matkul.tapel.addmhsin', 
		'roles' => ['administrator', 'akademik'],
		'uses' =>'MatkulTapelController@AddMhsIn'
		]);
	Route::post('matkul/tapel/{matkul_tapel_id}/mahasiswa/out', [
	'as' => 'matkul.tapel.addmhsout', 
	'roles' => ['administrator', 'akademik'],
	'uses' =>'MatkulTapelController@AddMhsOut'
	]);
	
	Route::get('/kelaskuliah/{matkul_tapel_id}/peserta', [
	'roles' => ['dosen'], 
	'uses' =>'MatkulTapelController@pesertaKuliah'
	]);
	
	Route::get('/dosen/{id}/aktifitasmengajar', [
	'as' => 'dosen.aktifitasmengajar',
	'roles' => ['administrator', 'akademik', 'dosen', 'prodi'], 
	'uses' =>'DosenController@aktifitasMengajarDosen'
	]);		
	
	Route::get('/kelaskuliah', [
	'roles' => ['dosen'], 
	'uses' =>'MatkulTapelController@mataKuliahDosen'
	]);
	Route::post('/kelaskuliah/{matkul_tapel_id}/upload/{tipe}', [
	'as' => 'matkul.tapel.upload',
	'roles' => ['administrator', 'akademik', 'dosen'], 
	'uses' =>'MatkulTapelController@uploadFile'
	]);
	Route::get('/kelaskuliah/{matkul_tapel_id}/upload/{tipe}', [
	'roles' => ['administrator', 'akademik', 'dosen'], 
	'uses' =>'MatkulTapelController@showFormUploadFile'
	]);
	
	/* 		Route::get('/kelaskuliah/{matkul_tapel_id}/nilai', [
	'roles' => ['dosen'], 
	'uses' =>'NilaiController@index'
	]);  */
	
	Route::get('matkul/tapel/{matkul_tapel_id}/nilai', [
	'as' => 'matkul.tapel.nilai', 
	'roles' => ['administrator', 'akademik', 'akademik', 'dosen'], 
	'uses' =>'NilaiController@index'
	]);
	Route::post('matkul/tapel/{matkul_tapel_id}/nilai', [
	'as' => 'matkul.tapel.nilai.store', 
	'roles' => ['administrator', 'akademik', 'akademik', 'dosen'], 
	'uses' =>'NilaiController@store'
	]);
	
	Route::get('matkul/tapel/{matkul_tapel_id}/export', [
	'as' => 'matkul.tapel.export', 
	'roles' => ['administrator', 'akademik', 'akademik'],
	'uses' =>'NilaiController@export'
	]);
	
	Route::post('matkul/tapel/nilai/import', [
	'as' => 'matkul.tapel.nilai.import', 
	'roles' => ['administrator', 'akademik'],
	'uses' =>'NilaiController@import'
	]);
	
	Route::get('matkul/tapel/{matkul_tapel_id}/nilai/form', [
	'as' => 'matkul.tapel.nilai.form', 
	'roles' => ['administrator', 'dosen', 'akademik'], 
	'uses' =>'NilaiController@formNilai'
	]);
	Route::match(['get', 'post'], 'matkul/tapel/{matkul_tapel_id}/nilai/cetak', [
	'as' => 'matkul.tapel.nilai.cetak', 
	'roles' => ['administrator', 'dosen', 'akademik'], 
	'uses' =>'NilaiController@cetakFormNilai'
	]);
	
	Route::post('matkul/tapel/nilai/destroy', [
	'as' => 'matkul.tapel.nilai.destroy',
	'roles' => ['administrator', 'dosen', 'akademik'], 
	'uses' =>'NilaiController@destroy'
	]);
	Route::post('matkul/tapel/nilai/store', [
	'as' => 'matkul.tapel.nilai.store', 
	'roles' => ['administrator', 'dosen', 'akademik'],
	'uses' =>'NilaiController@store'
	]);
	Route::get('matkul/tapel/{matkul_tapel_id}/hitungnilai', [
	'as' => 'matkul.tapel.hitungnilai', 
	'roles' => ['administrator', 'dosen', 'akademik'], 
	'uses' =>'NilaiController@hitungNilaiAkhir'
	]);
	
	/*
	Kuesioner
	*/
	Route::get('/penilaian/{id?}/{mtid?}', [
	'as' => 'penilaian.index',
	'roles' => ['mahasiswa'], 
	'uses' =>'KuesionerMahasiswaController@penilaian'
	]);
	Route::patch('/penilaian/{id}', [
	'as' => 'penilaian.update',
	'roles' => ['mahasiswa'], 
	'uses' =>'KuesionerMahasiswaController@update'
	]);
	
	Route::get('kuesioner', [
	'as' => 'kuesioner.index',
	'roles' => ['administrator', 'p2m (pusat penjaminan mutu)'],
	'uses' => 'KuesionerController@index'
	]);
	Route::get('kuesioner/results', [
	'as' => 'kuesioner.results',
	'roles' => ['administrator', 'p2m (pusat penjaminan mutu)'],
	'uses' => 'KuesionerController@results'
	]);
	Route::get('kuesioner/result/{matkul_tapel_id}/detail', [
	'as' => 'kuesioner.result.detail',
	'roles' => ['administrator', 'p2m (pusat penjaminan mutu)'],
	'uses' => 'KuesionerController@detail'
	]);
	Route::get('kuesioner/result/{matkul_tapel_id}/detail2', [
	'as' => 'kuesioner.result.detail2',
	'roles' => ['administrator', 'p2m (pusat penjaminan mutu)'],
	'uses' => 'KuesionerController@detail2'
	]);
	Route::get('kuesioner/result/{tapel_id}/{mode?}', [
	'as' => 'kuesioner.result',
	'roles' => ['administrator', 'p2m (pusat penjaminan mutu)'],
	'uses' => 'KuesionerController@result'
	]);
	
	/* 	Route::get('kuesioner/result/prodi/{tapel_id}/{prodi_id?}/{mode?}/', [
	'as' => 'kuesioner.result.prodi',
	'roles' => ['administrator', 'p2m (pusat penjaminan mutu)'],
	'uses' => 'KuesionerController@result'
	]);
	Route::get('kuesioner/result/dosen/{tapel_id}/{dosen_id?}/{mode?}/', [
	'as' => 'kuesioner.result.dosen',
	'roles' => ['administrator', 'p2m (pusat penjaminan mutu)'],
	'uses' => 'KuesionerController@result'
	]); */
	
	Route::get('kuesioner/create', [
	'as' => 'kuesioner.create',
	'roles' => ['administrator', 'p2m (pusat penjaminan mutu)'],
	'uses' => 'KuesionerController@create'
	]);
	Route::post('kuesioner', [
	'as' => 'kuesioner.store',
	'roles' => ['administrator', 'p2m (pusat penjaminan mutu)'],
	'uses' => 'KuesionerController@store'
	]);
	Route::get('kuesioner/{id}/edit', [
	'as' => 'kuesioner.edit',
	'roles' => ['administrator', 'p2m (pusat penjaminan mutu)'],
	'uses' => 'KuesionerController@edit'
	]);
	Route::patch('kuesioner/{id}', [
	'as' => 'kuesioner.update',
	'roles' => ['administrator', 'p2m (pusat penjaminan mutu)'],
	'uses' => 'KuesionerController@update'
	]);
	
	/*
	Program Kerja
	*/
	Route::get('/program', [
	'as' => 'program.index',
	'roles' => ['keuangan / administrasi', 'akademik', 'kemahasiswaan', 'prodi', 'manajemen mutu'], 
	'uses' =>'ProgramController@index'
	]);
	Route::post('/program', [
	'as' => 'program.store',
	'roles' => ['keuangan / administrasi', 'akademik', 'kemahasiswaan', 'prodi', 'manajemen mutu'], 
	'uses' =>'ProgramController@store'
	]);
	Route::get('/program/edit', [
	'as' => 'program.edit',
	'roles' => ['keuangan / administrasi', 'akademik', 'kemahasiswaan', 'prodi', 'manajemen mutu'], 
	'uses' =>'ProgramController@edit'
	]);
	Route::post('/program/edit', [
	'as' => 'program.update',
	'roles' => ['keuangan / administrasi', 'akademik', 'kemahasiswaan', 'prodi', 'manajemen mutu'], 
	'uses' =>'ProgramController@update'
	]);
	
	/*
	Absensi
	*/
	Route::post('/kuliah/absensi/submit', [
	'as' => 'absensi.submit',
	'roles' => ['dosen'], 
	'uses' =>'AbsensiController@store'
	]);
	Route::get('/kelaskuliah/{matkul_tapel_id}/absensi', [
	'roles' => ['dosen'], 
	'uses' =>'AbsensiController@index'
	]);
	Route::get('/kelaskuliah/{matkul_tapel_id}/absensi/cetak', [
	'roles' => ['dosen', 'administrator', 'akademik', 'prodi'], 
	'uses' =>'AbsensiController@cetak'
	]);
	
	/*
	Jurnal perkuliahan
	*/
	Route::get('/kelaskuliah/{matkul_tapel_id}/jurnal', [
	'as' => 'matkul.tapel.jurnal.index',
	'roles' => ['dosen'], 
	'uses' =>'JurnalController@index'
	]);
	Route::get('/kelaskuliah/{matkul_tapel_id}/jurnal/create', [
	'as' => 'matkul.tapel.jurnal.create',
	'roles' => ['dosen'], 
	'uses' =>'JurnalController@create'
	]);
	Route::post('/kelaskuliah/{matkul_tapel_id}/jurnal', [
	'as' => 'matkul.tapel.jurnal.store',
	'roles' => ['dosen'], 
	'uses' =>'JurnalController@store'
	]);
	Route::get('/kelaskuliah/{matkul_tapel_id}/jurnal/{jurnal_id}/edit', [
	'as' => 'matkul.tapel.jurnal.edit',
	'roles' => ['dosen'], 
	'uses' => 'JurnalController@edit'
	]);
	Route::patch('/kelaskuliah/{matkul_tapel_id}/jurnal/{jurnal_id}', [
	'as' => 'matkul.tapel.jurnal.update',
	'roles' => ['dosen'], 
	'uses' => 'JurnalController@update'
	]);
	Route::get('/kelaskuliah/{matkul_tapel_id}/cetak/formjurnal', [
	'roles' => ['administrator', 'dosen', 'prodi'], 
	'uses' => 'JurnalController@printFormJurnal'
	]);
	Route::get('/kelaskuliah/{matkul_tapel_id}/jurnal/cetak', [
	'as' => 'matkul.tapel.jurnal.print',
	'roles' => ['dosen', 'prodi'], 
	'uses' => 'JurnalController@printJurnal'
	]);
	Route::get('/kelaskuliah/{matkul_tapel_id}/jurnal/{jurnal_id}', [
	'as' => 'matkul.tapel.jurnal.show',
	'roles' => ['dosen'], 
	'uses' => 'JurnalController@show'
	]);
	Route::get('/kelaskuliah/{matkul_tapel_id}/jurnal/{jurnal_id}/hapus', [
	'as' => 'matkul.tapel.jurnal.delete',
	'roles' => ['dosen'], 
	'uses' => 'JurnalController@destroy'
	]);
	
	/**
	* Dosen
	**/
	
	//Pegawai
	Route::get('/pegawai', [
	'as' => 'pegawai.index',
	'roles' => ['administrator', 'akademik'],
	'uses' => 'PegawaiController@index'
	]);
	Route::get('/pegawai/create', [
	'as' => 'pegawai.create',
	'roles' => ['administrator', 'akademik'],
	'uses' => 'PegawaiController@create'
	]);
	Route::post('/pegawai', [
	'as' => 'pegawai.store',
	'roles' => ['administrator', 'akademik'],
	'uses' => 'PegawaiController@store'
	]);
	Route::get('/pegawai/{pegawai}/delete', [
	'as' => 'pegawai.delete',
	'roles' => ['administrator', 'akademik'],
	'uses' => 'PegawaiController@destroy'
	]);
	Route::get('/pegawai/{pegawai}/edit', [
	'as' => 'pegawai.edit',
	'roles' => ['administrator', 'akademik'],
	'uses' => 'PegawaiController@edit'
	]);		
	Route::patch('/pegawai/{pegawai}', [
	'as' => 'pegawai.update',
	'roles' => ['administrator', 'akademik'],
	'uses' => 'PegawaiController@update'
	]);	
	Route::get('/pegawai/export', [
	'as' => 'pegawai.export',
	'roles' => ['administrator', 'akademik'],
	'uses' => 'PegawaiController@export'
	]);
	Route::get('/pegawai/{pegawai}', [
	'as' => 'pegawai.show',
	'roles' => ['administrator', 'akademik'],
	'uses' => 'PegawaiController@show'
	]);	
	
	//Penelitian Dosen
	Route::get('/dosen/penelitian', [
	'as' => 'dosen.penelitian.index',
	'roles' => ['administrator', 'akademik'],
	'uses' => 'DosenPenelitianController@index'
	]);
	Route::get('/dosen/{dosen}/penelitian', [
	'as' => 'dosen.penelitian',
	'roles' => ['administrator', 'akademik', 'dosen', 'prodi'],
	'uses' => 'DosenPenelitianController@riwayat'
	]);
	Route::get('/dosen/penelitian/create', [
	'as' => 'dosen.penelitian.create',
	'roles' => ['administrator', 'akademik', 'dosen'],
	'uses' => 'DosenPenelitianController@create'
	]);
	Route::post('/dosen/penelitian', [
	'as' => 'dosen.penelitian.store',
	'roles' => ['administrator', 'akademik', 'dosen'],
	'uses' => 'DosenPenelitianController@store'
	]);
	Route::get('/dosen/penelitian/{penelitian}/edit', [
	'as' => 'dosen.penelitian.edit',
	'roles' => ['administrator', 'akademik', 'dosen'],
	'uses' => 'DosenPenelitianController@edit'
	]);		
	Route::patch('/dosen/penelitian/{penelitian}', [
	'as' => 'dosen.penelitian.update',
	'roles' => ['administrator', 'akademik', 'dosen'],
	'uses' => 'DosenPenelitianController@update'
	]);	
	Route::get('/dosen/penelitian/export', [
	'as' => 'dosen.penelitian.export',
	'roles' => ['administrator', 'akademik'],
	'uses' => 'DosenPenelitianController@export'
	]);
	Route::get('/dosen/penelitian/{penelitian}', [
	'as' => 'dosen.penelitian.show',
	'roles' => ['administrator', 'akademik'],
	'uses' => 'DosenPenelitianController@show'
	]);	
	
	//Buku Dosen
	Route::get('/dosen/buku', [
	'as' => 'dosen.buku.index',
	'roles' => ['administrator', 'akademik'],
	'uses' => 'DosenBukuController@index'
	]);
	Route::get('/dosen/{dosen}/buku', [
	'as' => 'dosen.buku',
	'roles' => ['administrator', 'akademik', 'dosen', 'prodi'],
	'uses' => 'DosenBukuController@buku'
	]);
	Route::get('/dosen/buku/create', [
	'as' => 'dosen.buku.create',
	'roles' => ['administrator', 'akademik', 'dosen'],
	'uses' => 'DosenBukuController@create'
	]);
	Route::post('/dosen/buku', [
	'as' => 'dosen.buku.store',
	'roles' => ['administrator', 'akademik', 'dosen'],
	'uses' => 'DosenBukuController@store'
	]);
	Route::get('/dosen/buku/{buku}/edit', [
	'as' => 'dosen.buku.edit',
	'roles' => ['administrator', 'akademik', 'dosen'],
	'uses' => 'DosenBukuController@edit'
	]);		
	Route::patch('/dosen/buku/{buku}', [
	'as' => 'dosen.buku.update',
	'roles' => ['administrator', 'akademik', 'dosen'],
	'uses' => 'DosenBukuController@update'
	]);	
	Route::get('/dosen/buku/export', [
	'as' => 'dosen.buku.export',
	'roles' => ['administrator', 'akademik'],
	'uses' => 'DosenBukuController@export'
	]);	
	
	//Jurnal Dosen
	Route::get('/dosen/jurnal', [
	'as' => 'dosen.jurnal.index',
	'roles' => ['administrator', 'akademik'],
	'uses' => 'DosenJurnalController@index'
	]);
	Route::get('/dosen/{dosen}/jurnal', [
	'as' => 'dosen.jurnal',
	'roles' => ['administrator', 'akademik', 'dosen', 'prodi'],
	'uses' => 'DosenController@jurnal'
	]);
	Route::get('/dosen/jurnal/create', [
	'as' => 'dosen.jurnal.create',
	'roles' => ['administrator', 'akademik', 'dosen'],
	'uses' => 'DosenJurnalController@create'
	]);
	Route::post('/dosen/jurnal', [
	'as' => 'dosen.jurnal.store',
	'roles' => ['administrator', 'akademik', 'dosen'],
	'uses' => 'DosenJurnalController@store'
	]);
	Route::get('/dosen/jurnal/{jurnal}/edit', [
	'as' => 'dosen.jurnal.edit',
	'roles' => ['administrator', 'akademik', 'dosen'],
	'uses' => 'DosenJurnalController@edit'
	]);		
	Route::patch('/dosen/jurnal/{jurnal}', [
	'as' => 'dosen.jurnal.update',
	'roles' => ['administrator', 'akademik', 'dosen'],
	'uses' => 'DosenJurnalController@update'
	]);	
	Route::get('/dosen/jurnal/export', [
	'as' => 'dosen.jurnal.export',
	'roles' => ['administrator', 'akademik'],
	'uses' => 'DosenJurnalController@export'
	]);	
	
	//Penugasan Dosen
	Route::get('/dosen/penugasan/filter', [
	'roles' => ['administrator', 'akademik'],
	'uses' => 'DosenPenugasanController@filter'
	]);
	Route::get('/dosen/penugasan', [
	'as' => 'dosen.penugasan.index',
	'roles' => ['administrator', 'akademik'],
	'uses' => 'DosenPenugasanController@index'
	]);
	Route::get('/dosen/{dosen}/penugasan', [
	'as' => 'dosen.penugasan',
	'roles' => ['administrator', 'akademik', 'dosen', 'prodi'],
	'uses' => 'DosenPenugasanController@riwayat'
	]);
	Route::get('/dosen/penugasan/create', [
	'as' => 'dosen.penugasan.create',
	'roles' => ['administrator', 'akademik', 'dosen'],
	'uses' => 'DosenPenugasanController@create'
	]);
	Route::post('/dosen/penugasan', [
	'as' => 'dosen.penugasan.store',
	'roles' => ['administrator', 'akademik', 'dosen'],
	'uses' => 'DosenPenugasanController@store'
	]);
	Route::get('/dosen/penugasan/{penugasan}/edit', [
	'as' => 'dosen.penugasan.edit',
	'roles' => ['administrator', 'akademik', 'dosen'],
	'uses' => 'DosenPenugasanController@edit'
	]);		
	Route::patch('/dosen/penugasan/{penugasan}', [
	'as' => 'dosen.penugasan.update',
	'roles' => ['administrator', 'akademik', 'dosen'],
	'uses' => 'DosenPenugasanController@update'
	]);	
	Route::get('/dosen/penugasan/{id}/delete', [
	'as' => 'dosen.penugasan.delete',
	'roles' => ['administrator', 'akademik', 'dosen'],
	'uses' => 'DosenPenugasanController@destroy'
	]);		
	Route::get('/dosen/penugasan/export', [
	'as' => 'dosen.penugasan.export',
	'roles' => ['administrator', 'akademik'],
	'uses' => 'DosenPenugasanController@export'
	]);	
	Route::get('/dosen/penugasan/export', [
	'as' => 'dosen.penugasan.export',
	'roles' => ['administrator', 'akademik'],
	'uses' => 'DosenPenugasanController@export'
	]);	
	
	//Pendidikan Dosen
	Route::get('/dosen/pendidikan', [
	'as' => 'dosen.pendidikan.index',
	'roles' => ['administrator', 'akademik'],
	'uses' => 'DosenPendidikanController@index'
	]);
	Route::get('/dosen/{dosen}/pendidikan', [
	'as' => 'dosen.pendidikan',
	'roles' => ['administrator', 'akademik', 'dosen', 'prodi'],
	'uses' => 'DosenPendidikanController@riwayat'
	]);
	Route::get('/dosen/{dosen}/pendidikan/create', [
	'as' => 'dosen.pendidikan.create',
	'roles' => ['administrator', 'akademik', 'dosen'],
	'uses' => 'DosenPendidikanController@create'
	]);
	Route::post('/dosen/{dosen}/pendidikan', [
	'as' => 'dosen.pendidikan.store',
	'roles' => ['administrator', 'akademik', 'dosen'],
	'uses' => 'DosenPendidikanController@store'
	]);
	Route::get('/dosen/{dosen}/pendidikan/{pendidikan}/edit', [
	'as' => 'dosen.pendidikan.edit',
	'roles' => ['administrator', 'akademik', 'dosen'],
	'uses' => 'DosenPendidikanController@edit'
	]);		
	Route::patch('/dosen/{dosen}/pendidikan/{pendidikan}', [
	'as' => 'dosen.pendidikan.update',
	'roles' => ['administrator', 'akademik', 'dosen'],
	'uses' => 'DosenPendidikanController@update'
	]);	
	Route::get('/dosen/{dosen}/pendidikan/{pendidikan}', [
	'as' => 'dosen.pendidikan.show',
	'roles' => ['administrator', 'akademik'],
	'uses' => 'DosenPendidikanController@show'
	]);	
	
	//Riwayat Fungsional Dosen
	Route::get('/dosen/fungsional', [
	'as' => 'dosen.fungsional.index',
	'roles' => ['administrator', 'akademik'],
	'uses' => 'DosenFungsionalController@index'
	]);
	Route::get('/dosen/{dosen}/fungsional', [
	'as' => 'dosen.fungsional',
	'roles' => ['administrator', 'akademik', 'dosen', 'prodi'],
	'uses' => 'DosenFungsionalController@riwayat'
	]);
	Route::get('/dosen/{dosen}/fungsional/create', [
	'as' => 'dosen.fungsional.create',
	'roles' => ['administrator', 'akademik', 'dosen'],
	'uses' => 'DosenFungsionalController@create'
	]);
	Route::post('/dosen/{dosen}/fungsional', [
	'as' => 'dosen.fungsional.store',
	'roles' => ['administrator', 'akademik', 'dosen'],
	'uses' => 'DosenFungsionalController@store'
	]);
	Route::get('/dosen/{dosen}/fungsional/{fungsional}/edit', [
	'as' => 'dosen.fungsional.edit',
	'roles' => ['administrator', 'akademik', 'dosen'],
	'uses' => 'DosenFungsionalController@edit'
	]);		
	Route::patch('/dosen/{dosen}/fungsional/{fungsional}', [
	'as' => 'dosen.fungsional.update',
	'roles' => ['administrator', 'akademik', 'dosen'],
	'uses' => 'DosenFungsionalController@update'
	]);		
	
	//Riwayat Kepangkatan Dosen
	Route::get('/dosen/kepangkatan', [
	'as' => 'dosen.kepangkatan.index',
	'roles' => ['administrator', 'akademik'],
	'uses' => 'DosenKepangkatanController@index'
	]);
	Route::get('/dosen/{dosen}/kepangkatan', [
	'as' => 'dosen.kepangkatan',
	'roles' => ['administrator', 'akademik', 'dosen', 'prodi'],
	'uses' => 'DosenKepangkatanController@riwayat'
	]);
	Route::get('/dosen/{dosen}/kepangkatan/create', [
	'as' => 'dosen.kepangkatan.create',
	'roles' => ['administrator', 'akademik', 'dosen'],
	'uses' => 'DosenKepangkatanController@create'
	]);
	Route::post('/dosen/{dosen}/kepangkatan', [
	'as' => 'dosen.kepangkatan.store',
	'roles' => ['administrator', 'akademik', 'dosen'],
	'uses' => 'DosenKepangkatanController@store'
	]);
	Route::get('/dosen/{dosen}/kepangkatan/{kepangkatan}/edit', [
	'as' => 'dosen.kepangkatan.edit',
	'roles' => ['administrator', 'akademik', 'dosen'],
	'uses' => 'DosenKepangkatanController@edit'
	]);		
	Route::patch('/dosen/{dosen}/kepangkatan/{kepangkatan}', [
	'as' => 'dosen.kepangkatan.update',
	'roles' => ['administrator', 'akademik', 'dosen'],
	'uses' => 'DosenKepangkatanController@update'
	]);	
	
	//Riwayat Sertifikasi Dosen
	Route::get('/dosen/sertifikasi', [
	'as' => 'dosen.sertifikasi.index',
	'roles' => ['administrator', 'akademik'],
	'uses' => 'DosenSertifikasiController@index'
	]);
	Route::get('/dosen/{dosen}/sertifikasi', [
	'as' => 'dosen.sertifikasi',
	'roles' => ['administrator', 'akademik', 'dosen', 'prodi'],
	'uses' => 'DosenSertifikasiController@riwayat'
	]);
	Route::get('/dosen/{dosen}/sertifikasi/create', [
	'as' => 'dosen.sertifikasi.create',
	'roles' => ['administrator', 'akademik', 'dosen'],
	'uses' => 'DosenSertifikasiController@create'
	]);
	Route::post('/dosen/{dosen}/sertifikasi', [
	'as' => 'dosen.sertifikasi.store',
	'roles' => ['administrator', 'akademik', 'dosen'],
	'uses' => 'DosenSertifikasiController@store'
	]);
	Route::get('/dosen/{dosen}/sertifikasi/{sertifikasi}/edit', [
	'as' => 'dosen.sertifikasi.edit',
	'roles' => ['administrator', 'akademik', 'dosen'],
	'uses' => 'DosenSertifikasiController@edit'
	]);		
	Route::patch('/dosen/{dosen}/sertifikasi/{sertifikasi}', [
	'as' => 'dosen.sertifikasi.update',
	'roles' => ['administrator', 'akademik', 'dosen'],
	'uses' => 'DosenSertifikasiController@update'
	]);	
	Route::get('/dosen/{dosen}/sertifikasi/{sertifikasi}', [
	'as' => 'dosen.sertifikasi.show',
	'roles' => ['administrator', 'akademik', 'dosen'],
	'uses' => 'DosenSertifikasiController@show'
	]);	
	
	Route::get('/profildosen', [
	'as' => 'dosen.public',
	'roles' => ['keuangan / administrasi', 'prodi', 'administrator'],
	'uses' => 'DosenController@index'
	]);	
	Route::get('/gajidosen', [
	'as' => 'dosen.gaji',
	'roles' => ['keuangan / administrasi', 'administrator'],
	'uses' => 'DosenController@gaji'
	]);	
	
	
	Route::get('/dosen/{dosen}/delete', [
	'as' => 'dosen.delete',
	'roles' => ['administrator', 'akademik'],
	'uses' => 'DosenController@delete'
	]);
	Route::post('/dosen/search', [
	'roles' => ['administrator', 'akademik', 'keuangan / administrasi'],
	'uses' => 'DosenController@preSearch'
	]);
	Route::get('/dosen/search/{q}', [
	'roles' => ['administrator', 'akademik', 'keuangan / administrasi'],
	'as' => 'dosen.search', 
	'uses' => 'DosenController@search'
	]);
	
	
	/* Akademik */
	/** File **/
	Route::post('/upload/file',[ 
	'as' => 'uploadfile', 
	'roles' => ['administrator', 'akademik'],
	'uses' => 'FileEntryController@upload'
	]);
	Route::get('file/delete/{id}', [
	'roles' => ['administrator', 'akademik'],
	'uses' => 'FileEntryController@delete'
	]);
	Route::get('file', [
	'as' => 'indexfile',
	'roles' => ['administrator', 'akademik'],
	'uses' => 'FileEntryController@index'
	]);
	Route::get('/upload/file',[ 
	'roles' => ['administrator', 'akademik'],
	'uses' => 'FileEntryController@uploadForm'
	]);
	
	/** Kalender **/
	Route::get('/kalender/create', [
	'as' => 'kalender.create',
	'roles' => ['administrator', 'akademik', 'kemahasiswaan'],
	'uses' => 'KalenderController@create'
	]);	
	Route::post('/kalender', [
	'as' => 'kalender.store',
	'roles' => ['administrator', 'akademik', 'kemahasiswaan'],
	'uses' => 'KalenderController@store'
	]);	
	Route::get('/kalender/{id}/edit', [
	'as' => 'kalender.edit',
	'roles' => ['administrator', 'akademik', 'kemahasiswaan'],
	'uses' => 'KalenderController@edit'
	]);	
	Route::patch('/kalender/{id}', [
	'as' => 'kalender.update',
	'roles' => ['administrator', 'akademik', 'kemahasiswaan'],
	'uses' => 'KalenderController@update'
	]);	
	Route::delete('/kalender/{id}', [
	'as' => 'kalender.destroy',
	'roles' => ['administrator', 'akademik', 'kemahasiswaan'],
	'uses' => 'KalenderController@destroy'
	]);		
	Route::get('/kalender', [
	'as' => 'kalender.index',
	'roles' => ['administrator', 'akademik', 'kemahasiswaan', 'prodi'],
	'uses' => 'KalenderController@index'
	]);	
	Route::get('/kalender2', [
	'as' => 'kalender.index2',
	'roles' => ['administrator', 'akademik', 'kemahasiswaan'],
	'uses' => 'KalenderController@index2'
	]);	
	
	/*
	Admin
	*/
	Route::get('/mahasiswa/{mahasiswa_id}/delete', [
	'as' => 'mahasiswa.delete', 
	'roles' => ['administrator', 'akademik'],
	'uses' =>'MahasiswaController@destroy'
	]);	
	
	/** Users **/
	
	//Reset Password 
	//target =mahasiswa||dosen
	Route::get('/pengguna/cetakpassword', [
	'as' => 'password.print',
	'roles' => ['administrator'],
	'uses' => 'UsersController@printPassword'
	]);	
	Route::get('/pengguna/resetpassword/{target}/{filter?}', [
	'as' => 'password.reset',
	'roles' => ['administrator'],
	'uses' => 'UsersController@resetPassword'
	]);	
	Route::post('/pengguna/resetpassword/{target}/{filter?}', [
	'as' => 'password.reset.result',
	'roles' => ['administrator'],
	'uses' => 'UsersController@resetPasswordProses'
	]);	
	Route::post('/pengguna/cari', [
	'as' => 'password.reset.caripengguna',
	'roles' => ['administrator'],
	'uses' => 'UsersController@cariPengguna'
	]);	
	
	Route::get('/pengguna/create', [
	'as' => 'pengguna.create',
	'roles' => ['administrator'],
	'uses' => 'UsersController@create'
	]);	
	Route::post('/pengguna', [
	'as' => 'pengguna.store',
	'roles' => ['administrator'],
	'uses' => 'UsersController@store'
	]);	
	Route::get('/pengguna/{id}/edit', [
	'as' => 'pengguna.edit',
	'roles' => ['administrator'],
	'uses' => 'UsersController@edit'
	]);	
	Route::get('/pengguna/{id}', [
	'as' => 'pengguna.show',
	'roles' => ['administrator'],
	'uses' => 'UsersController@show'
	]);	
	Route::patch('/pengguna/{id}', [
	'as' => 'pengguna.update',
	'roles' => ['administrator'],
	'uses' => 'UsersController@update'
	]);	
	Route::delete('/pengguna/{id}', [
	'as' => 'pengguna.destroy',
	'roles' => ['administrator'],
	'uses' => 'UsersController@destroy'
	]);		
	Route::get('/pengguna', [
	'as' => 'pengguna.index',
	'roles' => ['administrator'],
	'uses' => 'UsersController@index'
	]);	
	Route::post('/users/search', [
	'roles' => ['administrator'],
	'uses' => 'UsersController@preSearch'
	]);
	Route::get('/users/search/{q}', [
	'roles' => ['administrator'],
	'as' => 'user.search', 
	'uses' => 'UsersController@search'
	]);
	
	//Dosen	
	Route::get('/dosen/sks', [
	'as' => 'dosen.sks',
	'roles' => ['administrator', 'prodi'],
	'uses' => 'DosenController@sks'
	]);
	Route::get('/dosen/keahlian', [
	'as' => 'dosen.keahlian',
	'roles' => ['administrator', 'prodi'],
	'uses' => 'DosenController@keahlian'
	]);
	// jumlah mahasiswa perwalian 
	Route::get('/dosen/perwalian', [
	'as' => 'dosen.perwalian',
	'roles' => ['administrator', 'prodi'],
	'uses' => 'MahasiswaController@jumlahMahasiswaPerwalian'
	]);
	
	Route::get('/perwalian', [
	'as' => 'dosen.custodian',
	'roles' => ['dosen', 'prodi'],
	'uses' => 'MahasiswaController@custodian'
	]);	
	
	Route::get('/dosen/filter', [
	'as' => 'dosen.create',
	'roles' => ['administrator', 'akademik'],
	'uses' => 'DosenController@filter'
	]);
	
	Route::get('/dosen/create', [
	'as' => 'dosen.create',
	'roles' => ['administrator', 'akademik'],
	'uses' => 'DosenController@create'
	]);	
	Route::post('/dosen', [
	'as' => 'dosen.store',
	'roles' => ['administrator', 'akademik'],
	'uses' => 'DosenController@store'
	]);	
	Route::get('/dosen/{dosen}', [
	'as' => 'dosen.show',
	'roles' => ['administrator', 'akademik', 'prodi'],
	'uses' => 'DosenController@show'
	]);	
	Route::get('/dosen/{id}/edit', [
	'as' => 'dosen.edit',
	'roles' => ['administrator', 'akademik'],
	'uses' => 'DosenController@edit'
	]);	
	Route::patch('/dosen/{id}', [
	'as' => 'dosen.update',
	'roles' => ['administrator', 'akademik'],
	'uses' => 'DosenController@update'
	]);	
	Route::delete('/dosen/{id}', [
	'as' => 'dosen.destroy',
	'roles' => ['administrator', 'akademik'],
	'uses' => 'DosenController@destroy'
	]);		
	Route::get('/dosen', [
	'as' => 'dosen.index',
	'roles' => ['administrator', 'akademik', 'ketua', 'keuangan / administrasi'],
	'uses' => 'DosenController@index'
	]);	
	
	//MATKUL
	Route::get('/matkul/search', [
	'roles' => ['administrator', 'akademik'], 		
	'uses' => 'MatkulController@preSearch'
	]);
	Route::get('/matkul/search/{q}', [
	'as' => 'matkul.search', 
	'roles' => ['administrator', 'akademik'], 		
	'uses' => 'MatkulController@search'
	]);
	
	Route::get('/matkul/filter', [
	'as' => 'matkul.filtering',
	'roles' => ['administrator', 'akademik'],
	'uses' => 'MatkulController@filtering'
	]);	
	
	Route::get('/matkul/create', [
	'as' => 'matkul.create',
	'roles' => ['administrator', 'akademik'],
	'uses' => 'MatkulController@create'
	]);	
	Route::post('/matkul', [
	'as' => 'matkul.store',
	'roles' => ['administrator', 'akademik'],
	'uses' => 'MatkulController@store'
	]);	
	Route::get('/matkul/{id}', [
	'as' => 'matkul.show',
	'roles' => ['administrator', 'akademik'],
	'uses' => 'MatkulController@show'
	]);	
	Route::get('/matkul/{id}/edit', [
	'as' => 'matkul.edit',
	'roles' => ['administrator', 'akademik'],
	'uses' => 'MatkulController@edit'
	]);	
	Route::patch('/matkul/{id}', [
	'as' => 'matkul.update',
	'roles' => ['administrator', 'akademik'],
	'uses' => 'MatkulController@update'
	]);	
	Route::get('/matkul/{id}/delete', [
	'as' => 'matkul.delete',
	'roles' => ['administrator', 'akademik'],
	'uses' => 'MatkulController@destroy'
	]);		
	Route::get('/matkul', [
	'as' => 'matkul.index',
	'roles' => ['administrator', 'akademik', 'ketua', 'keuangan / administrasi'],
	'uses' => 'MatkulController@index'
	]);	
	
	
	//BACKUP	
	Route::get('/backup', [
	'as' => 'backup.index',
	'roles' => ['administrator', 'root'],
	'uses' => 'BackupController@index'
	]);
	Route::get('/backup/create', [
	'as' => 'backup.create',
	'roles' => ['administrator', 'root'],
	'uses' => 'BackupController@create'
	]);
	
	Route::get('/backup/restore/{id}', [
	'as' => 'backup.restore',
	'roles' => ['administrator', 'root'],
	'uses' => 'BackupController@restore'
	]);
	
	Route::get('/backup/delete/{id}', [
	'as' => 'backup.delete',
	'roles' => ['administrator', 'root'],
	'uses' => 'BackupController@destroy'
	]);
	
	Route::get('/backup/export/{id}', [
	'as' => 'backup.export',
	'roles' => ['administrator', 'root'],
	'uses' => 'BackupController@export'
	]);
	
	Route::get('/backup/import', [
	'as' => 'backup.importform',
	'roles' => ['administrator', 'root'],
	'uses' => 'BackupController@importForm'
	]);
	Route::post('/backup/import', [
	'as' => 'backup.import',
	'roles' => ['administrator', 'root'],
	'uses' => 'BackupController@import'
	]);
	
	//SETTING TA
	Route::get('/tapel/{id}/setting', [
	'as' => 'tapel.setting.index',
	'roles' => ['administrator', 'akademik'],
	'uses' => 'SettingTapelController@index'
	]);	
	Route::get('/tapel/{tapel_id}/setting/{setting_id}/edit', [
	'as' => 'tapel.setting.edit',
	'roles' => ['administrator', 'akademik'],
	'uses' => 'SettingTapelController@edit'
	]);
	Route::patch('/tapel/{tapel_id}/setting/{setting_id}', [
	'as' => 'tapel.setting.update',
	'roles' => ['administrator', 'akademik'],
	'uses' => 'SettingTapelController@update'
	]);
	
	//TA	
	Route::get('/tapel/create', [
	'as' => 'tapel.create',
	'roles' => ['administrator', 'akademik'],
	'uses' => 'TapelController@create'
	]);	
	Route::post('/tapel', [
	'as' => 'tapel.store',
	'roles' => ['administrator', 'akademik'],
	'uses' => 'TapelController@store'
	]);	
	Route::get('/tapel/{id}', [
	'as' => 'tapel.show',
	'roles' => ['administrator', 'akademik'],
	'uses' => 'TapelController@show'
	]);	
	Route::get('/tapel/{id}/edit', [
	'as' => 'tapel.edit',
	'roles' => ['administrator', 'akademik'],
	'uses' => 'TapelController@edit'
	]);	
	Route::patch('/tapel/{id}', [
	'as' => 'tapel.update',
	'roles' => ['administrator', 'akademik'],
	'uses' => 'TapelController@update'
	]);	
	Route::get('/tapel/{id}/delete', [
	'as' => 'tapel.delete',
	'roles' => ['administrator', 'akademik'],
	'uses' => 'TapelController@destroy'
	]);		
	Route::get('/tapel', [
	'as' => 'tapel.index',
	'roles' => ['administrator', 'akademik', 'prodi'],
	'uses' => 'TapelController@index'
	]);	
	
	Route::get('/report/create', [
	'as' => 'report.create',
	'roles' => ['administrator'],
	'uses' => 'BugReportController@create'
	]);	
	Route::post('/report', [
	'as' => 'report.store',
	'roles' => ['administrator'],
	'uses' => 'BugReportController@store'
	]);	
	Route::get('/report/{id}', [
	'as' => 'report.show',
	'roles' => ['administrator'],
	'uses' => 'BugReportController@show'
	]);	
	Route::get('/report/{id}/edit', [
	'as' => 'report.edit',
	'roles' => ['administrator'],
	'uses' => 'BugReportController@edit'
	]);	
	Route::patch('/report/{id}', [
	'as' => 'report.update',
	'roles' => ['administrator'],
	'uses' => 'BugReportController@update'
	]);		
	Route::get('/report', [
	'as' => 'report.index',
	'roles' => ['administrator'],
	'uses' => 'BugReportController@index'
	]);
	
	Route::get('/bug/{id}/resolve', [
	'as' => 'report.resolve',
	'roles' => ['root'],
	'uses' => 'BugReportController@resolve'
	]);
	Route::patch('/bug/{id}/resolved', [
	'as' => 'report.resolved',
	'roles' => ['root'],
	'uses' => 'BugReportController@resolved'
	]);
	Route::post('/bug/{id}/resolved', [
	'as' => 'report.resolved.user',
	'roles' => ['root', 'administrator'],
	'uses' => 'BugReportController@resolvedByUser'
	]);
	
	Route::post('/bug/comment', [
	'as' => 'report.comment.store',
	'roles' => ['root', 'administrator'],
	'uses' => 'BugReportCommentController@store'
	]);
	
	
	});				