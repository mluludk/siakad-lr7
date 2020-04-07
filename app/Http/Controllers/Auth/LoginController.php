<?php
	
	namespace Siakad\Http\Controllers\Auth;
	
	use \Illuminate\Http\Request;
	
	use Siakad\Http\Controllers\Controller;
	use Siakad\Providers\RouteServiceProvider;
	use Illuminate\Foundation\Auth\AuthenticatesUsers;
	
	class LoginController extends Controller
	{
		/*
			|--------------------------------------------------------------------------
			| Login Controller
			|--------------------------------------------------------------------------
			|
			| This controller handles authenticating users for the application and
			| redirecting them to your home screen. The controller uses a trait
			| to conveniently provide its functionality to your applications.
			|
		*/
		
		use AuthenticatesUsers;
		
		/**
			* Where to redirect users after login.
			*
			* @var string
		*/
		protected $redirectTo = RouteServiceProvider::HOME;
		
		/**
			* Create a new controller instance.
			*
			* @return void
		*/
		public function __construct()
		{
			$this->middleware('guest')->except('logout');
		}
		
		public function username()
		{
			return 'username';
		}
		
		//CUSTOM
		public function postLogin(Request $request)
		{
			$this->validate($request, [
            $this->username() => 'required', 'password' => 'required',
			]);
			
			//Check Login requirement
			$user = User::where('username', $request -> username) -> first();
			
			if($user && $user -> authable_type == 'Siakad\\Mahasiswa')
			{
				if($this -> getTanggungan($user -> authable_id))
				return $this -> sendLockedAccountResponse($request);
			}
			
			// If the class is using the ThrottlesLogins trait, we can automatically throttle
			// the login attempts for this application. We'll key this by the username and
			// the IP address of the client making these requests into this application.
			$throttles = $this->isUsingThrottlesLoginsTrait();
			
			if ($throttles && $this->hasTooManyLoginAttempts($request)) {
				return $this->sendLockoutResponse($request);
			}
			
			$credentials = $this->getCredentials($request);
			
			if (Auth::attempt($credentials, $request->has('remember'))) {
				return $this->handleUserWasAuthenticated($request, $throttles);
			}
			
			// If the login attempt was unsuccessful we will increment the number of attempts
			// to login and redirect the user back to the login form. Of course, when this
			// user surpasses their maximum number of attempts they will get locked out.
			if ($throttles) {
				$this->incrementLoginAttempts($request);
			}
			
			return redirect($this->loginPath())
			->withInput($request->only($this->username(), 'remember'))
			->withErrors([
			$this->username() => $this->getFailedLoginMessage(),
			]);
		}
		/**
			* Get the locked account response instance.
			*
			* @param \Illuminate\Http\Request  $request
			* @return \Illuminate\Http\Response
		*/
		protected function sendLockedAccountResponse(Request $request)
		{
			return redirect()->back()
			->withInput($request->only($this->username(), 'remember'))
			->withErrors([
            $this->username() => $this->getLockedAccountMessage(),
			]);
		}
		
		/**
			* Get the locked account message.
			*
			* @return string
		*/
		protected function getLockedAccountMessage()
		{
			return Lang::has('auth.locked')
            ? Lang::get('auth.locked')
            : 'Anda masih mempunyai tanggungan keuangan. Harap hubungi bagian Keuangan untuk menyelesaikannya.';
		}
		
		public function authenticated(Request $request)
		{
			$date = date('Y-m-d H:i:s');
			$ip =  $request -> ip();
			\Siakad\User::where($this->username(), '=', $request->only($this->username())) -> update(['last_login' => $date, 'last_ip' => $ip]);
			return redirect()->intended($this->redirectPath());
		} 
		/**
			* Get a validator for an incoming registration request.
			*
			* @param  array  $data
			* @return \Illuminate\Contracts\Validation\Validator
		*/
		protected function validator(array $data)
		{
			return Validator::make($data, [
            'name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|confirmed|min:6',
			]);
		}
		
		private function getTanggungan($mahasiswa_id)
		{
			$mahasiswa = \Siakad\Mahasiswa::find($mahasiswa_id);
			if(!$mahasiswa) return false;
			
			$tagihan = \Siakad\Tagihan::join('setup_biaya', 'setup_biaya.id', '=', 'setup_biaya_id')
			-> where('mahasiswa_id', $mahasiswa_id)
			-> where('angkatan', $mahasiswa -> angkatan)
			-> where('prodi_id', $mahasiswa -> prodi_id)
			-> where('kelas_id', $mahasiswa -> kelasMhs)
			-> where('jenisPembayaran', $mahasiswa -> jenisPembayaran)
			-> where('setup_biaya.login', 'y')
			-> get([
			'tagihan.id',
			'tagihan.jumlah', 
			'tagihan.bayar', 
			'tagihan.privilege'
			]); 
			
			if(!$tagihan) return false;
			
			$tanggungan = 0;
			$bayar = 0;
			foreach($tagihan as $t)
			{
				if($t -> privilege == 'y') return false;
				$tanggungan +=  $t -> jumlah;
				$bayar +=  $t -> bayar;
			}
			
			if($tanggungan - $bayar <= 0) return false;
			
			return true;
		}
	}
