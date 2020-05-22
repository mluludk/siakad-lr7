<?php namespace Siakad\Http\Controllers;

	use Cache;
	use Input;
	use Session;
	use Redirect;
	use Siakad\User;

	use Siakad\Http\Controllers\Controller;

	use Illuminate\Http\Request;

	use Siakad\Jobs\SendResetPasswordRequestEmail;

	class UsersController extends Controller {

		protected $roles = [
		'mahasiswa' => 512,
		'dosen' => 128
		];
		public function impersonate($id)
		{
			if(!$id) return Redirect::back() -> withErrors(['INVALID_ID' => 'Invalid ID.']);

			$user = User::find($id);
			// Guard against administrator impersonate
			if(! $user -> isAdministrator())
			{
				\Session::put('orig_user', \Auth::id());
				\Session::put('orig_user_avatar', \Auth::user() -> authable -> foto);
				\Auth::login($user);
			}
			else
			{
				return Redirect::back() -> withErrors(['NOT_ALLOWED' => 'Impersonate disabled for this user.']);
			}

			return Redirect::to('/') -> with('success', 'Login sebagai [' . $user -> authable -> nama . ']');
		}

		public function stopImpersonate()
		{
			$id = \Session::pull('orig_user');
			\Session::forget('orig_user_avatar');
			$orig_user = User::find($id);
			if($orig_user)
			{
				\Auth::login($orig_user);
				return Redirect::to('/') -> with('success', 'Welcome back '. $orig_user -> authable -> nama .'!');
			}
			return Redirect::to('/') -> withErrors(['ERROR' => 'User not recognized']);
		}

		public function getUsername()
		{
			if(config('custom.user.reset-password') == 0) return view('auth.passwords.username', ['locked' => true, 'message' => 'Fasilitas Reset Password tidak dapat digunakan. Silahkan hubungi Admin untuk informasi lebih lanjut']);
			return view('auth.passwords.username', ['locked' => false]);
		}

		public function getResetPassword($username, $reset_token)
		{
			if(config('custom.user.reset-password') == 0) return view('auth.passwords.reset', ['locked' => true, 'message' => 'Fasilitas Reset Password tidak dapat digunakan. Silahkan hubungi Admin untuk informasi lebih lanjut']);
			$user = User::whereUsername($username) -> where('reset_token', $reset_token) -> first();
			if(!$user) abort(404);
			$locked=false;
			return view('auth.passwords.reset', compact('user', 'locked'));
		}

		public function postResetPassword(Request $request)
		{
			if(config('custom.user.reset-password') == 0) return view('auth.passwords.reset', ['locked' => true, 'message' => 'Fasilitas Reset Password tidak dapat digunakan. Silahkan hubungi Admin untuk informasi lebih lanjut']);
			$this->rules['password'] = ['min:3', 'same:password_confirmation'];
			$this -> validate($request, $this->rules);

			$input = array_except($request -> all(), ['_token', 'password_confirmation']);

			$user = User::whereUsername($input['username']) -> where('reset_token', $input['reset_token']);
			if(!$user) abort(404);

			$input['password'] = bcrypt($input['password']);
			unset($input['old-password']);
			$input['reset_token'] = null;
			$input['reset_ip'] = null;
			$input['remember_token'] = null;
			$user -> update($input);
			return Redirect::to('/login') -> with('message', 'Password berhasil diubah.');
		}

		public function postUsername(Request $request)
		{
			if(config('custom.user.reset-password') == 0) return view('auth.passwords.username', ['locked' => true, 'message' => 'Fasilitas Reset Password tidak dapat digunakan. Silahkan hubungi Admin untuk informasi lebih lanjut']);
			$this->validate($request, ['username' => 'required']);
			$user = User::whereUsername($request -> get('username')) -> first();

			if(!$user) return view('auth.passwords.username', ['locked' => false]) -> withErrors(['username' => 'Maaf, Username tidak terdaftar.']);

			switch($user -> authable_type)
			{
				case 'Siakad\Dosen':
				$data = \Siakad\Dosen::whereId($user -> authable_id) -> first() -> toArray();
				break;

				case 'Siakad\Mahasiswa':
				$data = \Siakad\Mahasiswa::whereId($user -> authable_id) -> first() -> toArray();
				break;

				case 'Siakad\Admin':
				$data = \Siakad\Admin::whereId($user -> authable_id) -> first() -> toArray();
				break;
			}

			if($user -> authable_type == 'Siakad\Mahasiswa')
			{
				if($data['statusMhs'] != '1') return view('auth.passwords.username', ['locked' => false]) -> withErrors(['username' => 'Status Mahasiswa tidak aktif. Silahkan hubungi Administrator untuk informasi lebih lanjut.']);
			}

			$validator = \Validator::make($data, ['email' => 'required|email']);
			if($validator -> fails()) return view('auth.passwords.username', ['locked' => false])
				-> withErrors(['username' => 'Email belum diisi / format email salah. Silahkan hubungi Administrator untuk informasi lebih lanjut.']);

			$data['username'] = $user -> username;
			$data['ip'] = $request -> ip();
			$data['config'] = config('custom');
			$data['reset_token'] = str_random(64);

			$user -> update([
			'reset_token' => $data['reset_token'],
			'reset_ip' => $data['ip']
			]);

			$this -> dispatch(new SendResetPasswordRequestEmail($data));
			return Redirect::route('password.username', ['locked' => false]) -> with('message', 'Petunjuk reset password telah dikirimkan ke email anda.
			Email dari kami mungkin dikategorikan sebagai SPAM oleh Gmail. Periksa folder SPAM anda jika email tidak ditampilkan di INBOX');
		}

		public function myProfile()
		{
			$auth = \Auth::user();
			switch($auth -> authable_type)
			{
				case 'Siakad\Dosen':
				$dosen = \Siakad\Dosen::whereId($auth -> authable_id) -> first();

				$tmp = \Siakad\Prodi::orderBy('id') -> get();
				foreach($tmp as $p) $prodi[$p -> id] = $p -> nama . ' (' . $p -> singkatan . ')';

				return view('dosen.profil', compact('dosen', 'prodi'));
				break;

				case 'Siakad\Mahasiswa':
				$mahasiswa = \Siakad\Mahasiswa::whereId($auth -> authable_id) -> first();
				$alamat = '';
				if($mahasiswa['jalan'] != '') $alamat .= 'Jl. ' . $mahasiswa['jalan'] . ' ';
				if($mahasiswa['dusun'] != '') $alamat .= $mahasiswa['dusun'] . ' ';
				if($mahasiswa['rt'] != '') $alamat .= 'RT ' . $mahasiswa['rt'] . ' ';
				if($mahasiswa['rw'] != '') $alamat .= 'RW ' . $mahasiswa['rw'] . ' ';
				if($mahasiswa['kelurahan'] != '') $alamat .= $mahasiswa['kelurahan'] . ' ';
				if($mahasiswa['id_wil'] != '')
				{
					$data = \Siakad\Wilayah::dataKecamatan($mahasiswa['id_wil']) -> first();
					if($data)
					$alamat .= trim($data -> kec) . ' ' . trim($data -> kab) . ' ' . trim($data -> prov) . ' ';
				}

				if($mahasiswa['kodePos'] != '') $alamat .= $mahasiswa['kodePos'];
				return view('mahasiswa.profil', compact('mahasiswa', 'alamat'));
				break;

				default:
				$user = $auth;
				return view('users.admin.show', compact('user'));
			}
		}
		public function myProfileEdit()
		{
			$auth = \Auth::user();
			switch($auth -> authable_type)
			{
				case 'Siakad\Dosen':
				$dosen = \Siakad\Dosen::whereId($auth -> authable_id) -> first();

				$tmp = \Siakad\Prodi::orderBy('id') -> get();
				foreach($tmp as $p) $prodi[$p -> id] = $p -> nama . ' (' . $p -> singkatan . ')';

				//Bidang Matkul
				$bid_matkul = \Cache::get('bid_matkul_cache', function(){
					$tmp = \Siakad\Kurikulum::matkulKurikulum() -> get();
					foreach($tmp as $t) $bid_matkul[$t -> id] = $t -> kode . ' - ' . $t -> nama;

					\Cache::put('bid_matkul_cache', $bid_matkul, 30);
					return $bid_matkul;
				});

				return view('dosen.profiledit', compact('dosen', 'prodi', 'bid_matkul'));
				break;

				case 'Siakad\Mahasiswa':
				$mahasiswa = \Siakad\Mahasiswa::whereId($auth -> authable_id) -> first();
				$negara = Cache::get('negara', function() {
					$negara = \Siakad\Negara::orderBy('nama') -> pluck('nama', 'kode');
					Cache::put('negara', $negara, 60);
					return $negara;
				});
				$wilayah = Cache::get('wilayah', function() {
					$wilayah = \Siakad\Wilayah::kecamatan() -> get();
					$tmp[1] = '';
					foreach($wilayah as $kec)
					{
						$tmp[$kec -> id_wil] = $kec['kec'] . ' - ' . $kec['kab'] . ' - ' . $kec['prov'];
					}
					Cache::put('wilayah', $tmp, 60);
					return $tmp;
				});
				return view('mahasiswa.profiledit', compact('mahasiswa', 'wilayah', 'negara'));
				break;

				default:
				$user = $auth;
				return view('users.admin.edit', compact('user'));
			}
		}

		public function myProfileUpdate(Request $request)
		{
			switch(\Auth::user() -> authable_type)
			{
				case 'Siakad\Dosen':
				$input = array_except($request -> all(), ['_method', 'bid_matkul']);
				$dosen = \Siakad\Dosen::find(\Auth::user() -> authable_id);
				$dosen -> update($input);

				//Bidang Matkul
				$bid_matkul = $request -> get('bid_matkul');
				if(is_array($bid_matkul))
				{
					$error = [];
					\Siakad\DosenMatkul::where('dosen_id', $dosen -> id) -> delete();
					foreach($bid_matkul as $m)
					{
						if(\Siakad\DosenMatkul::where('matkul_id', $m) -> exists())
						$error[] = $m;
						else
						\Siakad\DosenMatkul::create(['dosen_id' => $dosen -> id, 'matkul_id' => $m]);
					}
				}

				break;

				case 'Siakad\Mahasiswa':
				$rules = [
				'tmpLahir' => ['required', 'valid_name'],
				'tglLahir' => ['required', 'date', 'date_format:d-m-Y'],
				'jenisKelamin' => ['required'],
				'NIK' => ['required', 'numeric'],
				'kelurahan' => ['required'],
				'hp' => ['required'],
				'id_wil' => ['required', 'numeric'],
				'namaIbu' => ['required', 'valid_name'],
				'namaAyah' => ['required', 'valid_name'],
				];
				$this -> validate($request, $rules);
				$input = array_except($request -> all(), '_method');
				$mahasiswa = \Siakad\Mahasiswa::find(\Auth::user() -> authable_id);
				$mahasiswa -> update($input);
				break;


			}
			return Redirect::route('user.profile') -> with('message', 'Profil berhasil diperbarui.');
		}

		/**
			* Display a listing of the resource.
			*
			* @return Response
		*/
		public function index(Request $request)
		{
			$input = $request -> all();
			$q = $request -> get('q');
			$filter = $request -> get('filter', 'all');
			$users = User::leftJoin('roles', 'users.role_id', '=', 'roles.id');
			if(isset($q) and $q != '') $users = $users -> orWhere('username', 'like', '%' . $q . '%');

			$subtitle = '';
			switch($filter)
			{
				case 'struktural':
				$users = $users -> leftJoin('admin', 'admin.id', '=', 'users.authable_id')
				-> whereNotIn('role_id', [1, 32, 128, 512, 1024, 2048]) -> orderBy('roles.sort');
				$users = $users -> select('admin.nama', 'users.username', 'users.id AS user_id', 'roles.sub', 'roles.name AS role_name');
				$subtitle = 'Struktural';
				break;

				case 'dosen':
				$users = $users -> leftJoin('dosen', 'dosen.id', '=', 'users.authable_id') -> where('role_id', 128);
				if(isset($q) and $q != '') $users = $users -> orWhere('dosen.nama', 'like', '%' . $q . '%');
				$users = $users -> select('dosen.*', 'users.username', 'users.id AS user_id', 'roles.sub', 'roles.name AS role_name');
				$subtitle = 'Dosen';
				break;

				// case 'mahasiswa':
				default:
				$users = $users -> leftJoin('mahasiswa', 'mahasiswa.id', '=', 'users.authable_id') -> leftJoin('prodi', 'mahasiswa.prodi_id', '=', 'prodi.id')  -> join('kelas', 'mahasiswa.kelasMhs', '=', 'kelas.id') -> where('role_id', 512);
				if(isset($q) and $q != '') $users = $users -> orWhere('mahasiswa.nama', 'like', '%' . $q . '%');
				$users = $users -> select('prodi.strata', 'prodi.nama AS prodi', 'mahasiswa.nama', 'mahasiswa.NIM', 'users.username', 'users.id AS user_id', 'roles.sub', 'roles.name AS role_name', 'kelas.nama AS kelas');
				$subtitle = 'Mahasiswa';
				break;
			}
			$users = $users -> paginate(40);

			return view('users.index', compact('users', 'subtitle'));
		}

		/**
			* Show the form for creating a new resource.
			*
			* @return Response
		*/
		public function create()
		{
			$roles0 = \Siakad\Role::whereNotIn('id', [1, 32, 128, 512, 1024, 2048]) ->get();
			foreach($roles0 as $role)
			{
				$roles[$role -> id] = $role -> name . ' ' . $role -> sub;
			}
			return view('users.create', compact('roles'));
		}

		/**
			* Store a newly created resource in storage.
			*
			* @param \Illuminate\Http\Request $request
			* @return Response
			*
		*/
		public function store(Request $request)
		{
			$this->rules['username'] = ['required', 'alpha_num', 'min:3', 'unique:users'];
			$this->rules['password'] = ['required', 'min:3', 'same:password_confirmation'];
			$this -> validate($request, $this->rules);

			$input = array_except($request -> all(), ['_token', 'password_confirmation']);
			$input['password'] = bcrypt($input['password']);

			$authable = array_only($input, ['nama', 'telp', 'email', 'foto']);
			$admin = \Siakad\Admin::create($authable);

			$authinfo = array_except($input, ['nama', 'telp', 'email', 'foto']);
			$authinfo['authable_id'] = $admin -> id;
			$authinfo[ 'authable_type'] = 'Siakad\Admin';
			User::create($authinfo);

			return Redirect::route('pengguna.index') -> with('message', 'Data pengguna telah disimpan');
		}

		/**
			* Display the specified resource.
			*
			* @param  int  $id
			* @return Response
		*/
		public function show($id)
		{
			$user = User::find($id);
			return view('users.show', compact('user'));
		}

		/**
			* Show the form for editing the specified resource.
			*
			* @param  int  $id
			* @return Response
		*/
		public function edit($id)
		{
			$user = User::find($id);
			if($user -> role_id == 512 or $user -> role_id == 128) // dont change role is user is Mahasiswa
			{
				$roles = null;
			}
			else
			{
				$tmp = \Siakad\Role::whereNotIn('id', [1, 32, 128, 512, 1024, 2048]) ->get();
				foreach($tmp as $role)
				{
					$roles[$role -> id] = $role -> name . ' ' . $role -> sub;
				}
			}

			return view('users.edit', compact('user', 'roles'));
		}

		public function changePassword()
		{
			$user = \Auth::user();
			return view('users.changepassword', compact('user'));
		}
		public function updatePassword($user_id, Request $request)
		{
			$this->rules['password'] = array('min:3', 'same:password_confirmation');
			$this -> validate($request, $this->rules);

			$input = array_except($request -> all(), ['_token', 'password_confirmation']);
			$user = User::find($user_id);

			if(\Hash::check($input['old-password'], $user -> password))
			{
				$input['password'] = bcrypt($input['password']);
				$input['remember_token'] = null;
				unset($input['old-password']);
				$user -> update($input);

				//invalidate session
				$request -> session() -> invalidate();

				return Redirect::route('password.change') -> with('message', 'Password berhasi diubah. Silahkan Login ulang');
			}
			return Redirect::route('password.change') -> withErrors('Password sekarang salah, mohon periksa kembali.');
		}

		//reset password mahasiswa
		//25 09 2016
		//reset password
		//20 10  2016

		public function cariPengguna(Request $request)
		{
			$input = $request -> all();
			$results = \DB::select('
			SELECT username, nama
			FROM users
			INNER JOIN mahasiswa ON users.authable_id = mahasiswa.id
			WHERE mahasiswa.nama LIKE :nama OR mahasiswa.NIM LIKE :nim
			ORDER BY mahasiswa.NIM',
			['nama' => '%' . $input['query'] . '%', 'nim' => '%' . $input['query'] . '%']
			);

			return \Response::json(['results' => $results]);
		}

		public function resetPassword($target,  $filter=null)
		{
			if($target == 'mahasiswa')
			{
				if($filter != null)
				{
					return view('users.resetpasswordmahasiswa2');
				}
				else
				{
					return view('users.resetpasswordmahasiswa');
				}
			}
			elseif($target == 'dosen')
			{
				abort(404);
			}
		}
		public function resetPasswordProses(Request $request, $target, $filter=null)
		{
			$tmp = null;
			$input = $request -> except(['_token']);
			if($target == 'mahasiswa')
			{
				if($filter == null)
				{
					$users = User::where('role_id', $this -> roles[$target]);
					if($users -> count())
					{
						$plain_password = isset($input['password']) && $input['password'] != '' ? $input['password'] : str_random();
						$password = bcrypt($plain_password);
						$users -> update(['password' => $password, 'remember_token' => null]);

						return redirect('/pengguna/resetpassword/'. $target) -> with('success', 'Password seluruh Mahasiswa berhasil diubah menjadi "' . $plain_password . '".');
					}
				}
				else
				{
					$input['target'] = json_decode($input['target'], true);
					if(count($input['target']) > 0)
					{
						if($input['options'] == 'random-all')
						{
							$users = User::whereIn('username', $input['target']);
							if($users -> count())
							{
								$plain_password = str_random(6);

								foreach($input['target'] as $target) $tmp[$target] = $plain_password;
								$cache_name = md5(date('Y-m-d H:i:s'));
								\Cache::put($cache_name, $tmp, 60);

								$password = bcrypt($plain_password);
								$users -> update(['password' => $password, 'remember_token' => null]);

								return redirect('/pengguna/resetpassword/mahasiswa/filter') -> with('success_raw', 'Password Mahasiswa berhasil diubah. <a target="_blank" href="'. url('/pengguna/cetakpassword?key=' . $cache_name) .'" class="btn btn-danger btn-xs btn-flat"><i class="fa fa-print"></i> Cetak kartu</a>');
							}
						}
						elseif($input['options'] == 'text')
						{
							if($input['textPassword'] != '')
							{
								$users = User::whereIn('username', $input['target']);
								if($users -> count())
								{
									$plain_password = $input['textPassword'];

									foreach($input['target'] as $target) $tmp[$target] = $plain_password;
									$cache_name = md5(date('Y-m-d H:i:s'));
									\Cache::put($cache_name, $tmp, 60);

									$password = bcrypt($plain_password);
									$users -> update(['password' => $password, 'remember_token' => null]);

									return redirect('/pengguna/resetpassword/mahasiswa/filter') -> with('success_raw', 'Password Mahasiswa berhasil diubah. <a target="_blank" href="'. url('/pengguna/cetakpassword?key=' . $cache_name) .'" class="btn btn-danger btn-xs btn-flat"><i class="fa fa-print"></i> Cetak kartu</a>');
								}
							}
							else
							{
								return back() -> withErrors(['text_not_set' => 'Password harus diisi']);
							}
						}
						elseif($input['options'] == 'random')
						{
							foreach($input['target'] as $target)
							{
								$plain_password = str_random(6);
								$tmp[$target] = $plain_password;
								User::whereUsername($target) -> update(['password' => bcrypt($plain_password), 'remember_token' => null]);
							}
							$cache_name = md5(date('Y-m-d H:i:s'));
							\Cache::put($cache_name, $tmp, 60);
							return redirect('/pengguna/resetpassword/mahasiswa/filter') -> with('success_raw', 'Password Mahasiswa berhasil diubah. <a target="_blank" href="'. url('/pengguna/cetakpassword?key=' . $cache_name) .'" class="btn btn-danger btn-xs btn-flat"><i class="fa fa-print"></i> Cetak kartu</a>');
						}
					}
				}
			}
			return back()  -> withErrors(['role_not_found' => 'Data pengguna tidak ditemukan.']);
		}

		public function printPassword(Request $request)
		{
			$tmp = \Cache::get($request -> get('key'));
			$users = User::whereIn('username', array_keys($tmp)) -> get();
			if($tmp == null) abort(404);
			return view('users.printpassword', compact('tmp', 'users'));
		}

		/**
			* Update the specified resource in storage.
			*
			* @param  int  $id
			* @return Response
		*/
		public function update(Request $request, $id)
		{
			$this->rules['password'] = [
			'nullable',
			'min:3',
			'same:password_confirmation'
			];
			$this -> validate($request, $this->rules);

			$user = User::find($id);
			$input = $request -> except('_token', 'password_confirmation');

			if($input['password'] != '' && $input['password'] != null)
			{
				$input['password'] = bcrypt($input['password']);
				$input['remember_token'] = null;
			}
			else
			{
				unset($input['password']);
			}

			if($input['foto'] != '')	$authable = array_only($input, ['nama', 'telp', 'email', 'foto']);
			else $authable = array_only($input, ['nama', 'telp', 'email']);
			$authinfo = array_except($input, ['nama', 'telp', 'email', 'foto']);

			$user -> authable() -> update($authable);
			$user -> update($authinfo);

			return Redirect::route('pengguna.index') -> with('success', 'Data pengguna berhasil diperbarui.');
		}

		/**
			* Remove the specified resource from storage.
			*
			* @param  int  $id
			* @return Response
		*/
		public function destroy($id)
		{
			User::find($id) -> delete();
			return redirect() -> back() -> with('success', 'Data Pengguna berhasil dihapus.');
		}
	}

