<?php
	
	namespace Siakad\Http\Controllers;
	
	use Input;
	use Redirect;
	use Illuminate\Http\Request;
	
	use Siakad\Route;
	
	class RouteController extends Controller
	{
		public function index($role=null)
		{
			$roles = \Cache::get('roles', function() {
				$tmp = \Siakad\Role::orderBy('name') -> get();
				foreach($tmp as $role)
				$roles[$role -> id] = $role -> name . ' ' . $role -> sub;
				return $roles;
			});
			$items = Route::leftJoin('routes AS m2', 'm2.id', '=', 'routes.parent_id');
			if($role != null) $items = $items -> where('routes.roles', 'like', '%"' . $role . '"%');
			$items = $items 
			// -> where('routes.label', '<>', '') 
			-> orderBy('routes.position') -> orderBy('routes.id') -> get(['routes.*', 'm2.label AS parent']);
			
			$route = array();
			$root_id = 0;
			
			if($items -> count())
			{
				foreach ( $items as $item )
				$children[$item['parent_id']][] = $item;
				$loop = !empty( $children[$root_id] );
				$parent = $root_id;
				$parent_stack = array();
				
				$c = 0;
				while ( $loop && ( ( $option = each( $children[$parent] ) ) || ( $parent > $root_id ) ) )
				{
					$c++;
					if ( $option === false )
					{
						$parent = array_pop( $parent_stack );
					}
					elseif ( !empty( $children[$option['value']['id']] ) )
					{
						$route[] = [
						$c,
						'<i class="fa fa-' . $option['value']['icon'] .'"></i>',
						$option['value']['label'],
						$option['value']['uri'],
						$option['value']['parent'],
						$option['value']['roles'],
						$option['value']['position'],
						$option['value'] -> hidden,
						$option['value'] -> id,
						$option['value']['group'],
						$option['value']['method'],
						$option['value']['as'],
						$option['value']['uses'],
						getRoleName($option['value']['roles'], $roles)
						];				
						array_push( $parent_stack, $option['value']['parent_id'] );
						$parent = $option['value']['id'];
					}
					else
					{
						$route[] = [
						$c,
						'<i class="fa fa-' . $option['value']['icon'] .'"></i>',
						$option['value']['label'],
						$option['value']['uri'],
						$option['value']['parent'],
						$option['value']['roles'],
						$option['value']['position'],
						$option['value'] -> hidden,
						$option['value'] -> id,
						$option['value']['group'],
						$option['value']['method'],
						$option['value']['as'],
						$option['value']['uses'],
						getRoleName($option['value']['roles'], $roles)
						];	
					}
				}
			}
			
			$tmp = \Siakad\Role::orderBy('name') -> get();
			foreach($tmp as $r)
			$roles[$r -> id] = $r -> name . ' ' . $r -> sub;
			
			return view('route.index', compact('route', 'roles', 'role'));
		}
		
		/**
			* Show the form for creating a new resource.
			*
			* @return \Illuminate\Http\Response
		*/
		public function create()
		{
			$tmp = \Siakad\Role::orderBy('name') -> get();
			foreach($tmp as $role)
			$roles[$role -> id] = $role -> name . ' ' . $role -> sub;
			
			$parents = [0 => '-'];
			$tmp = Route::orderBy('label') -> pluck('label', 'id');
			foreach($tmp as $k => $v) $parents[$k] = $v;
			
			foreach($this -> icons as $i) $icons[$i] = substr($i, 3);
			
			return view('route.create', compact('roles', 'parents', 'icons'));
		}
		
		/**
			* Store a newly created resource in storage.
			*
			// * @param  \Illuminate\Http\Request  $request
			* @return \Illuminate\Http\Response
		*/
		public function store(Request $request)
		{
			$input= $request -> except('_token');
			
			$input['parent_id'] = isset($input['parent_id']) ? $input['parent_id'] : 0;
			
			if(isset($input['roles']) and count($input['roles'])) foreach($input['roles'] as $r) \Cache::forget('route_' . $r);
			
			$input['group'] = isset($input['group']) ? $input['group'] : null;
			
			$input['roles'] = isset($input['roles']) ? json_encode($input['roles']) : '';
			
			$crud = null;
			if(isset($input['crud'])) {
				$crud = $input['crud'];
				unset($input['crud']);
			}
			
			$routes = Route::create($input);
			
			if($crud != null and $input['label'] != '')
			{
				$route = strtolower($input['label']);
				
				$data[] = [
				'method' => 'get',
				'group' => $input['group'],
				'uri' => '/' . $route . '/' . 'index',
				'as' => $route . '.' . 'index',
				'uses' => $input['label'] . 'Controller@index',
				'label' => 'Index',
				'parent_id' => $routes -> id,
				'position' => 1,
				'roles' => $input['roles']
				];
				$data[] = [
				'method' => 'get',
				'group' => $input['group'],
				'uri' => '/' . $route . '/' . 'create',
				'as' => $route . '.' . 'create',
				'uses' => $input['label'] . 'Controller@create',
				'label' => 'New',
				'parent_id' => $routes -> id,
				'position' => 2,
				'roles' => $input['roles']
				];
				$data[] = [
				'method' => 'post',
				'group' => $input['group'],
				'uri' => '/' . $route,
				'as' => $route . '.' . 'store',
				'uses' => $input['label'] . 'Controller@store',
				'label' => null,
				'parent_id' => 0,
				'position' => 1,
				'roles' => $input['roles']
				];
				$data[] = [
				'method' => 'get',
				'group' => $input['group'],
				'uri' => '/' . $route . '/{id}/edit',
				'as' => $route . '.' . 'edit',
				'uses' => $input['label'] . 'Controller@edit',
				'label' => null,
				'parent_id' => 0,
				'position' => 1,
				'roles' => $input['roles']
				];
				$data[] = [
				'method' => 'patch',
				'group' => $input['group'],
				'uri' => '/' . $route . '/' . '{id}',
				'as' => $route . '.' . 'update',
				'uses' => $input['label'] . 'Controller@update',
				'label' => null,
				'parent_id' => 0,
				'position' => 1,
				'roles' => $input['roles']
				];
				$data[] = [
				'method' => 'get',
				'group' => $input['group'],
				'uri' => '/' . $route . '/' . '{id}',
				'as' => $route . '.' . 'show',
				'uses' => $input['label'] . 'Controller@show',
				'label' => null,
				'parent_id' => 0,
				'position' => 1,
				'roles' => $input['roles']
				];
				$data[] = [
				'method' => 'get',
				'group' => $input['group'],
				'uri' => '/' . $route . '/{id}/delete',
				'as' => $route . '.' . 'delete',
				'uses' => $input['label'] . 'Controller@destroy',
				'label' => null,
				'parent_id' => 0,
				'position' => 1,
				'roles' => $input['roles']
				];
				Route::insert($data);
			}
			
			return Redirect::route('route.index') -> with('success', 'Data route berhasil dimasukkan.');
		}
		
		/**
			* Show the form for editing the specified resource.
		*
		* @param  int  $id
		* @return \Illuminate\Http\Response
		*/
		public function edit($id)
		{
		$route = Route::find($id);
		
		$tmp = \Siakad\Role::orderBy('name') -> get();			
		foreach($tmp as $role)
		$roles[$role -> id] = $role -> name . ' ' . $role -> sub;
		
		$parents = [0 => '-'];
		$tmp = Route::orderBy('label') -> pluck('label', 'id');
		foreach($tmp as $k => $v) $parents[$k] = $v;
		
		foreach($this -> icons as $i) $icons[$i] = substr($i, 3);
		
		$roles_a = json_decode($route -> roles);
		return view('route.edit', compact('route', 'roles', 'parents', 'icons', 'roles_a'));
		}
		
		/**
		* Update the specified resource in storage.
		*
		// * @param  \Illuminate\Http\Request  $request
		* @param  int  $id
		* @return \Illuminate\Http\Response
		*/
		public function update(Request $request, $id)
		{
		$input = $request -> except('_method');
		
		$input['parent_id'] = isset($input['parent_id']) ? $input['parent_id'] : 0;
		
		if(isset($input['roles']) and count($input['roles'])) foreach($input['roles'] as $r) \Cache::forget('route_' . $r);
		
		$input['roles'] = isset($input['roles']) ? json_encode($input['roles']) : '';
		
		/* 			if(isset($input['as_crud_type'])) {
		$input['as'] = $input['as'] . '.' . $input['as_crud_type'];
		unset($input['as_crud_type']);
		}
		
		if(isset($input['uses_crud_type'])) {
		$input['uses'] = $input['uses'] . '@' . $input['uses_crud_type'];
		unset($input['uses_crud_type']);
		} */
		
		Route::find($id) -> update($input);	
		
		return Redirect::route('route.index') -> with('success', 'Data route berhasil diperbarui.');
		}
		
		/**
		* Remove the specified resource from storage.
		*
		* @param  int  $id
		* @return \Illuminate\Http\Response
		*/
		public function destroy($id)
		{
		Route::find($id) -> delete();			
		return Redirect::route('route.index') -> with('success', 'Data route berhasil dihapus.');
		}
		
		protected $icons = [
		'',
		"fa-500px", 
		"fa-address-book", 
		"fa-address-book-o", 
		"fa-address-card", 
		"fa-address-card-o", 
		"fa-adjust", 
		"fa-adn", 
		"fa-align-center", 
		"fa-align-justify", 
		"fa-align-left", 
		"fa-align-right", 
		"fa-amazon", 
		"fa-ambulance", 
		"fa-american-sign-language-interpreting", 
		"fa-anchor", 
		"fa-android", 
		"fa-angellist", 
		"fa-angle-double-down", 
		"fa-angle-double-left", 
		"fa-angle-double-right", 
		"fa-angle-double-up", 
		"fa-angle-down", 
		"fa-angle-left", 
		"fa-angle-right", 
		"fa-angle-up", 
		"fa-apple", 
		"fa-archive", 
		"fa-area-chart", 
		"fa-arrow-circle-down", 
		"fa-arrow-circle-left", 
		"fa-arrow-circle-o-down", 
		"fa-arrow-circle-o-left", 
		"fa-arrow-circle-o-right", 
		"fa-arrow-circle-o-up", 
		"fa-arrow-circle-right", 
		"fa-arrow-circle-up", 
		"fa-arrow-down", 
		"fa-arrow-left", 
		"fa-arrow-right", 
		"fa-arrow-up", 
		"fa-arrows", 
		"fa-arrows-alt", 
		"fa-arrows-h", 
		"fa-arrows-v", 
		"fa-asl-interpreting", 
		"fa-assistive-listening-systems", 
		"fa-asterisk", 
		"fa-at", 
		"fa-audio-description", 
		"fa-automobile", 
		"fa-backward", 
		"fa-balance-scale", 
		"fa-ban", 
		"fa-bandcamp", 
		"fa-bank", 
		"fa-bar-chart", 
		"fa-bar-chart-o", 
		"fa-barcode", 
		"fa-bars", 
		"fa-bath", 
		"fa-battery-empty", 
		"fa-battery-full", 
		"fa-battery-half", 
		"fa-battery-quarter", 
		"fa-battery-three-quarters", 
		"fa-bed", 
		"fa-beer", 
		"fa-behance", 
		"fa-behance-square", 
		"fa-bell", 
		"fa-bell-o", 
		"fa-bell-slash", 
		"fa-bell-slash-o", 
		"fa-bicycle", 
		"fa-binoculars", 
		"fa-birthday-cake", 
		"fa-bitbucket", 
		"fa-bitbucket-square", 
		"fa-bitcoin", 
		"fa-black-tie", 
		"fa-blind", 
		"fa-bluetooth", 
		"fa-bluetooth-b", 
		"fa-bold", 
		"fa-bolt", 
		"fa-bomb", 
		"fa-book", 
		"fa-bookmark", 
		"fa-bookmark-o", 
		"fa-braille", 
		"fa-briefcase", 
		"fa-btc", 
		"fa-bug", 
		"fa-building", 
		"fa-building-o", 
		"fa-bullhorn", 
		"fa-bullseye", 
		"fa-bus", 
		"fa-buysellads", 
		"fa-calculator", 
		"fa-calendar", 
		"fa-calendar-check-o", 
		"fa-calendar-minus-o", 
		"fa-calendar-o", 
		"fa-calendar-plus-o", 
		"fa-calendar-times-o", 
		"fa-camera", 
		"fa-camera-retro", 
		"fa-car", 
		"fa-caret-down", 
		"fa-caret-left", 
		"fa-caret-right", 
		"fa-caret-square-o-down", 
		"fa-caret-square-o-left", 
		"fa-caret-square-o-right", 
		"fa-caret-square-o-up", 
		"fa-caret-up", 
		"fa-cart-arrow-down", 
		"fa-cart-plus", 
		"fa-cc", 
		"fa-cc-amex", 
		"fa-cc-diners-club", 
		"fa-cc-discover", 
		"fa-cc-jcb", 
		"fa-cc-mastercard", 
		"fa-cc-paypal", 
		"fa-cc-stripe", 
		"fa-cc-visa", 
		"fa-certificate", 
		"fa-chain", 
		"fa-chain-broken", 
		"fa-check", 
		"fa-check-circle", 
		"fa-check-circle-o", 
		"fa-check-square", 
		"fa-check-square-o", 
		"fa-chevron-circle-down", 
		"fa-chevron-circle-left", 
		"fa-chevron-circle-right", 
		"fa-chevron-circle-up", 
		"fa-chevron-down", 
		"fa-chevron-left", 
		"fa-chevron-right", 
		"fa-chevron-up", 
		"fa-child", 
		"fa-chrome", 
		"fa-circle", 
		"fa-circle-o", 
		"fa-circle-o-notch", 
		"fa-circle-thin", 
		"fa-clipboard", 
		"fa-clock-o", 
		"fa-clone", 
		"fa-close", 
		"fa-cloud", 
		"fa-cloud-download", 
		"fa-cloud-upload", 
		"fa-cny", 
		"fa-code", 
		"fa-code-fork", 
		"fa-codepen", 
		"fa-codiepie", 
		"fa-coffee", 
		"fa-cog", 
		"fa-cogs", 
		"fa-columns", 
		"fa-comment", 
		"fa-comment-o", 
		"fa-commenting", 
		"fa-commenting-o", 
		"fa-comments", 
		"fa-comments-o", 
		"fa-compass", 
		"fa-compress", 
		"fa-connectdevelop", 
		"fa-contao", 
		"fa-copyright", 
		"fa-creative-commons", 
		"fa-credit-card", 
		"fa-credit-card-alt", 
		"fa-crop", 
		"fa-crosshairs", 
		"fa-css3", 
		"fa-cube", 
		"fa-cubes", 
		"fa-cut", 
		"fa-cutlery", 
		"fa-dashboard", 
		"fa-dashcube", 
		"fa-database", 
		"fa-deaf", 
		"fa-deafness", 
		"fa-dedent", 
		"fa-delicious", 
		"fa-desktop", 
		"fa-deviantart", 
		"fa-diamond", 
		"fa-digg", 
		"fa-dollar", 
		"fa-dot-circle-o", 
		"fa-download", 
		"fa-dribbble", 
		"fa-drivers-license", 
		"fa-drivers-license-o", 
		"fa-dropbox", 
		"fa-drupal", 
		"fa-edge", 
		"fa-edit", 
		"fa-eercast", 
		"fa-eject", 
		"fa-ellipsis-h", 
		"fa-ellipsis-v", 
		"fa-empire", 
		"fa-envelope", 
		"fa-envelope-o", 
		"fa-envelope-open", 
		"fa-envelope-open-o", 
		"fa-envelope-square", 
		"fa-envira", 
		"fa-eraser", 
		"fa-etsy", 
		"fa-eur", 
		"fa-euro", 
		"fa-exchange", 
		"fa-exclamation", 
		"fa-exclamation-circle", 
		"fa-exclamation-triangle", 
		"fa-expand", 
		"fa-expeditedssl", 
		"fa-external-url", 
		"fa-external-url-square", 
		"fa-eye", 
		"fa-eye-slash", 
		"fa-eyedropper", 
		"fa-fa", 
		"fa-facebook", 
		"fa-facebook-f", 
		"fa-facebook-official", 
		"fa-facebook-square", 
		"fa-fast-backward", 
		"fa-fast-forward", 
		"fa-fax", 
		"fa-feed", 
		"fa-female", 
		"fa-fighter-jet", 
		"fa-file", 
		"fa-file-archive-o", 
		"fa-file-audio-o", 
		"fa-file-code-o", 
		"fa-file-excel-o", 
		"fa-file-image-o", 
		"fa-file-movie-o", 
		"fa-file-o", 
		"fa-file-pdf-o", 
		"fa-file-photo-o", 
		"fa-file-picture-o", 
		"fa-file-powerpoint-o", 
		"fa-file-sound-o", 
		"fa-file-text", 
		"fa-file-text-o", 
		"fa-file-video-o", 
		"fa-file-word-o", 
		"fa-file-zip-o", 
		"fa-files-o", 
		"fa-film", 
		"fa-filter", 
		"fa-fire", 
		"fa-fire-extinguisher", 
		"fa-firefox", 
		"fa-first-order", 
		"fa-flag", 
		"fa-flag-checkered", 
		"fa-flag-o", 
		"fa-flash", 
		"fa-flask", 
		"fa-flickr", 
		"fa-floppy-o", 
		"fa-folder", 
		"fa-folder-o", 
		"fa-folder-open", 
		"fa-folder-open-o", 
		"fa-font", 
		"fa-font-awesome", 
		"fa-fonticons", 
		"fa-fort-awesome", 
		"fa-forumbee", 
		"fa-forward", 
		"fa-foursquare", 
		"fa-free-code-camp", 
		"fa-frown-o", 
		"fa-futbol-o", 
		"fa-gamepad", 
		"fa-gavel", 
		"fa-gbp", 
		"fa-ge", 
		"fa-gear", 
		"fa-gears", 
		"fa-genderless", 
		"fa-get-pocket", 
		"fa-gg", 
		"fa-gg-circle", 
		"fa-gift", 
		"fa-git", 
		"fa-git-square", 
		"fa-github", 
		"fa-github-alt", 
		"fa-github-square", 
		"fa-gitlab", 
		"fa-gittip", 
		"fa-glass", 
		"fa-glide", 
		"fa-glide-g", 
		"fa-globe", 
		"fa-google", 
		"fa-google-plus", 
		"fa-google-plus-circle", 
		"fa-google-plus-official", 
		"fa-google-plus-square", 
		"fa-google-wallet", 
		"fa-graduation-cap", 
		"fa-gratipay", 
		"fa-grav", 
		"fa-group", 
		"fa-h-square", 
		"fa-hacker-news", 
		"fa-hand-grab-o", 
		"fa-hand-lizard-o", 
		"fa-hand-o-down", 
		"fa-hand-o-left", 
		"fa-hand-o-right", 
		"fa-hand-o-up", 
		"fa-hand-paper-o", 
		"fa-hand-peace-o", 
		"fa-hand-pointer-o", 
		"fa-hand-rock-o", 
		"fa-hand-scissors-o", 
		"fa-hand-spock-o", 
		"fa-hand-stop-o", 
		"fa-handshake-o", 
		"fa-hard-of-hearing", 
		"fa-hashtag", 
		"fa-hdd-o", 
		"fa-header", 
		"fa-headphones", 
		"fa-heart", 
		"fa-heart-o", 
		"fa-heartbeat", 
		"fa-history", 
		"fa-home", 
		"fa-hospital-o", 
		"fa-hotel", 
		"fa-hourglass", 
		"fa-hourglass-1", 
		"fa-hourglass-2", 
		"fa-hourglass-3", 
		"fa-hourglass-end", 
		"fa-hourglass-half", 
		"fa-hourglass-o", 
		"fa-hourglass-start", 
		"fa-houzz", 
		"fa-html5", 
		"fa-i-cursor", 
		"fa-id-badge", 
		"fa-id-card", 
		"fa-id-card-o", 
		"fa-ils", 
		"fa-image", 
		"fa-imdb", 
		"fa-inbox", 
		"fa-indent", 
		"fa-industry", 
		"fa-info", 
		"fa-info-circle", 
		"fa-inr", 
		"fa-instagram", 
		"fa-institution", 
		"fa-internet-explorer", 
		"fa-intersex", 
		"fa-ioxhost", 
		"fa-italic", 
		"fa-joomla", 
		"fa-jpy", 
		"fa-jsfiddle", 
		"fa-key", 
		"fa-keyboard-o", 
		"fa-krw", 
		"fa-language", 
		"fa-laptop", 
		"fa-lastfm", 
		"fa-lastfm-square", 
		"fa-leaf", 
		"fa-leanpub", 
		"fa-legal", 
		"fa-lemon-o", 
		"fa-level-down", 
		"fa-level-up", 
		"fa-life-bouy", 
		"fa-life-buoy", 
		"fa-life-ring", 
		"fa-life-saver", 
		"fa-lightbulb-o", 
		"fa-line-chart", 
		"fa-url", 
		"fa-urledin", 
		"fa-urledin-square", 
		"fa-linode", 
		"fa-linux", 
		"fa-list", 
		"fa-list-alt", 
		"fa-list-ol", 
		"fa-list-ul", 
		"fa-location-arrow", 
		"fa-lock", 
		"fa-long-arrow-down", 
		"fa-long-arrow-left", 
		"fa-long-arrow-right", 
		"fa-long-arrow-up", 
		"fa-low-vision", 
		"fa-magic", 
		"fa-magnet", 
		"fa-mail-forward", 
		"fa-mail-reply", 
		"fa-mail-reply-all", 
		"fa-male", 
		"fa-map", 
		"fa-map-marker", 
		"fa-map-o", 
		"fa-map-pin", 
		"fa-map-signs", 
		"fa-mars", 
		"fa-mars-double", 
		"fa-mars-stroke", 
		"fa-mars-stroke-h", 
		"fa-mars-stroke-v", 
		"fa-maxcdn", 
		"fa-meanpath", 
		"fa-medium", 
		"fa-medkit", 
		"fa-meetup", 
		"fa-meh-o", 
		"fa-mercury", 
		"fa-microchip", 
		"fa-microphone", 
		"fa-microphone-slash", 
		"fa-minus", 
		"fa-minus-circle", 
		"fa-minus-square", 
		"fa-minus-square-o", 
		"fa-mixcloud", 
		"fa-mobile", 
		"fa-mobile-phone", 
		"fa-modx", 
		"fa-money", 
		"fa-moon-o", 
		"fa-mortar-board", 
		"fa-motorcycle", 
		"fa-mouse-pointer", 
		"fa-music", 
		"fa-navicon", 
		"fa-neuter", 
		"fa-newspaper-o", 
		"fa-object-group", 
		"fa-object-ungroup", 
		"fa-odnoklassniki", 
		"fa-odnoklassniki-square", 
		"fa-opencart", 
		"fa-openid", 
		"fa-opera", 
		"fa-optin-monster", 
		"fa-outdent", 
		"fa-pagelines", 
		"fa-paint-brush", 
		"fa-paper-plane", 
		"fa-paper-plane-o", 
		"fa-paperclip", 
		"fa-paragraph", 
		"fa-paste", 
		"fa-pause", 
		"fa-pause-circle", 
		"fa-pause-circle-o", 
		"fa-paw", 
		"fa-paypal", 
		"fa-pencil", 
		"fa-pencil-square", 
		"fa-pencil-square-o", 
		"fa-percent", 
		"fa-phone", 
		"fa-phone-square", 
		"fa-photo", 
		"fa-picture-o", 
		"fa-pie-chart", 
		"fa-pied-piper", 
		"fa-pied-piper-alt", 
		"fa-pied-piper-pp", 
		"fa-pinterest", 
		"fa-pinterest-p", 
		"fa-pinterest-square", 
		"fa-plane", 
		"fa-play", 
		"fa-play-circle", 
		"fa-play-circle-o", 
		"fa-plug", 
		"fa-plus", 
		"fa-plus-circle", 
		"fa-plus-square", 
		"fa-plus-square-o", 
		"fa-podcast", 
		"fa-power-off", 
		"fa-print", 
		"fa-product-hunt", 
		"fa-puzzle-piece", 
		"fa-qq", 
		"fa-qrcode", 
		"fa-question", 
		"fa-question-circle", 
		"fa-question-circle-o", 
		"fa-quora", 
		"fa-quote-left", 
		"fa-quote-right", 
		"fa-ra", 
		"fa-random", 
		"fa-ravelry", 
		"fa-rebel", 
		"fa-recycle", 
		"fa-reddit", 
		"fa-reddit-alien", 
		"fa-reddit-square", 
		"fa-refresh", 
		"fa-registered", 
		"fa-remove", 
		"fa-renren", 
		"fa-reorder", 
		"fa-repeat", 
		"fa-reply", 
		"fa-reply-all", 
		"fa-resistance", 
		"fa-retweet", 
		"fa-rmb", 
		"fa-road", 
		"fa-rocket", 
		"fa-rotate-left", 
		"fa-rotate-right", 
		"fa-rouble", 
		"fa-rss", 
		"fa-rss-square", 
		"fa-rub", 
		"fa-ruble", 
		"fa-rupee", 
		"fa-s15", 
		"fa-safari", 
		"fa-save", 
		"fa-scissors", 
		"fa-scribd", 
		"fa-search", 
		"fa-search-minus", 
		"fa-search-plus", 
		"fa-sellsy", 
		"fa-send", 
		"fa-send-o", 
		"fa-server", 
		"fa-share", 
		"fa-share-alt", 
		"fa-share-alt-square", 
		"fa-share-square", 
		"fa-share-square-o", 
		"fa-shekel", 
		"fa-sheqel", 
		"fa-shield", 
		"fa-ship", 
		"fa-shirtsinbulk", 
		"fa-shopping-bag", 
		"fa-shopping-basket", 
		"fa-shopping-cart", 
		"fa-shower", 
		"fa-sign-in", 
		"fa-sign-language", 
		"fa-sign-out", 
		"fa-signal", 
		"fa-signing", 
		"fa-simplybuilt", 
		"fa-sitemap", 
		"fa-skyatlas", 
		"fa-skype", 
		"fa-slack", 
		"fa-sliders", 
		"fa-slideshare", 
		"fa-smile-o", 
		"fa-snapchat", 
		"fa-snapchat-ghost", 
		"fa-snapchat-square", 
		"fa-snowflake-o", 
		"fa-soccer-ball-o", 
		"fa-sort", 
		"fa-sort-alpha-asc", 
		"fa-sort-alpha-desc", 
		"fa-sort-amount-asc", 
		"fa-sort-amount-desc", 
		"fa-sort-asc", 
		"fa-sort-desc", 
		"fa-sort-down", 
		"fa-sort-numeric-asc", 
		"fa-sort-numeric-desc", 
		"fa-sort-up", 
		"fa-soundcloud", 
		"fa-space-shuttle", 
		"fa-spinner", 
		"fa-spoon", 
		"fa-spotify", 
		"fa-square", 
		"fa-square-o", 
		"fa-stack-exchange", 
		"fa-stack-overflow", 
		"fa-star", 
		"fa-star-half", 
		"fa-star-half-empty", 
		"fa-star-half-full", 
		"fa-star-half-o", 
		"fa-star-o", 
		"fa-steam", 
		"fa-steam-square", 
		"fa-step-backward", 
		"fa-step-forward", 
		"fa-stethoscope", 
		"fa-sticky-note", 
		"fa-sticky-note-o", 
		"fa-stop", 
		"fa-stop-circle", 
		"fa-stop-circle-o", 
		"fa-street-view", 
		"fa-strikethrough", 
		"fa-stumbleupon", 
		"fa-stumbleupon-circle", 
		"fa-subscript", 
		"fa-subway", 
		"fa-suitcase", 
		"fa-sun-o", 
		"fa-superpowers", 
		"fa-superscript", 
		"fa-support", 
		"fa-table", 
		"fa-tablet", 
		"fa-tachometer", 
		"fa-tag", 
		"fa-tags", 
		"fa-tasks", 
		"fa-taxi", 
		"fa-telegram", 
		"fa-television", 
		"fa-tencent-weibo", 
		"fa-terminal", 
		"fa-text-height", 
		"fa-text-width", 
		"fa-th", 
		"fa-th-large", 
		"fa-th-list", 
		"fa-themeisle", 
		"fa-thermometer", 
		"fa-thermometer-0", 
		"fa-thermometer-1", 
		"fa-thermometer-2", 
		"fa-thermometer-3", 
		"fa-thermometer-4", 
		"fa-thermometer-empty", 
		"fa-thermometer-full", 
		"fa-thermometer-half", 
		"fa-thermometer-quarter", 
		"fa-thermometer-three-quarters", 
		"fa-thumb-tack", 
		"fa-thumbs-down", 
		"fa-thumbs-o-down", 
		"fa-thumbs-o-up", 
		"fa-thumbs-up", 
		"fa-ticket", 
		"fa-times", 
		"fa-times-circle", 
		"fa-times-circle-o", 
		"fa-times-rectangle", 
		"fa-times-rectangle-o", 
		"fa-tint", 
		"fa-toggle-down", 
		"fa-toggle-left", 
		"fa-toggle-off", 
		"fa-toggle-on", 
		"fa-toggle-right", 
		"fa-toggle-up", 
		"fa-trademark", 
		"fa-train", 
		"fa-transgender", 
		"fa-transgender-alt", 
		"fa-trash", 
		"fa-trash-o", 
		"fa-tree", 
		"fa-trello", 
		"fa-tripadvisor", 
		"fa-trophy", 
		"fa-truck", 
		"fa-try", 
		"fa-tty", 
		"fa-tumblr", 
		"fa-tumblr-square", 
		"fa-turkish-lira", 
		"fa-tv", 
		"fa-twitch", 
		"fa-twitter", 
		"fa-twitter-square", 
		"fa-umbrella", 
		"fa-underline", 
		"fa-undo", 
		"fa-universal-access", 
		"fa-university", 
		"fa-unurl", 
		"fa-unlock", 
		"fa-unlock-alt", 
		"fa-upload", 
		"fa-usb", 
		"fa-usd", 
		"fa-user", 
		"fa-user-circle", 
		"fa-user-circle-o", 
		"fa-user-md", 
		"fa-user-o", 
		"fa-user-plus", 
		"fa-user-secret", 
		"fa-user-times", 
		"fa-users", 
		"fa-venus", 
		"fa-venus-double", 
		"fa-venus-mars", 
		"fa-viacoin", 
		"fa-viadeo", 
		"fa-viadeo-square", 
		"fa-video-camera", 
		"fa-vimeo", 
		"fa-vimeo-square", 
		"fa-vine", 
		"fa-vk", 
		"fa-volume-control-phone", 
		"fa-volume-down", 
		"fa-volume-off", 
		"fa-volume-up", 
		"fa-weibo", 
		"fa-weixin", 
		"fa-whatsapp", 
		"fa-wheelchair", 
		"fa-wheelchair-alt", 
		"fa-wifi", 
		"fa-wikipedia-w", 
		"fa-window-close", 
		"fa-window-close-o", 
		"fa-window-maximize", 
		"fa-window-minimize", 
		"fa-window-restore", 
		"fa-windows", 
		"fa-wordpress", 
		"fa-wpbeginner", 
		"fa-wpexplorer", 
		"fa-wpforms", 
		"fa-wrench", 
		"fa-xing", 
		"fa-xing-square", 
		"fa-y-combinator", 
		"fa-yahoo", 
		"fa-yelp", 
		"fa-yoast", 
		"fa-youtube", 
		"fa-youtube-play", 
		"fa-youtube-square"];
		
		}
				