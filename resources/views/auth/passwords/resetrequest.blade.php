<style>
.button::-moz-focus-inner{
  border: 0;
  padding: 0;
}

.button{
  display: inline-block;
  *display: inline;
  zoom: 1;
  padding: 6px 20px;
  margin: 0;
  cursor: pointer;
  border: 1px solid #bbb;
  overflow: visible;
  font: bold 13px arial, helvetica, sans-serif;
  text-decoration: none;
  white-space: nowrap;
  color: #555;
  
  background-color: #ddd;
  background-image: -webkit-gradient(linear, left top, left bottom, from(rgba(255,255,255,1)), to(rgba(255,255,255,0)));
  background-image: -webkit-linear-gradient(top, rgba(255,255,255,1), rgba(255,255,255,0));
  background-image: -moz-linear-gradient(top, rgba(255,255,255,1), rgba(255,255,255,0));
  background-image: -ms-linear-gradient(top, rgba(255,255,255,1), rgba(255,255,255,0));
  background-image: -o-linear-gradient(top, rgba(255,255,255,1), rgba(255,255,255,0));
  background-image: linear-gradient(top, rgba(255,255,255,1), rgba(255,255,255,0));
  
  -webkit-transition: background-color .2s ease-out;
  -moz-transition: background-color .2s ease-out;
  -ms-transition: background-color .2s ease-out;
  -o-transition: background-color .2s ease-out;
  transition: background-color .2s ease-out;
  background-clip: padding-box; /* Fix bleeding */
  -moz-border-radius: 3px;
  -webkit-border-radius: 3px;
  border-radius: 3px;
  -moz-box-shadow: 0 1px 0 rgba(0, 0, 0, .3), 0 2px 2px -1px rgba(0, 0, 0, .5), 0 1px 0 rgba(255, 255, 255, .3) inset;
  -webkit-box-shadow: 0 1px 0 rgba(0, 0, 0, .3), 0 2px 2px -1px rgba(0, 0, 0, .5), 0 1px 0 rgba(255, 255, 255, .3) inset;
  box-shadow: 0 1px 0 rgba(0, 0, 0, .3), 0 2px 2px -1px rgba(0, 0, 0, .5), 0 1px 0 rgba(255, 255, 255, .3) inset;
  text-shadow: 0 1px 0 rgba(255,255,255, .9);
  
  -webkit-touch-callout: none;
  -webkit-user-select: none;
  -khtml-user-select: none;
  -moz-user-select: none;
  -ms-user-select: none;
  user-select: none;
}

.button:hover{
  background-color: #eee;
  color: #555;
}

.button:active{
  background: #e9e9e9;
  position: relative;
  top: 1px;
  text-shadow: none;
  -moz-box-shadow: 0 1px 1px rgba(0, 0, 0, .3) inset;
  -webkit-box-shadow: 0 1px 1px rgba(0, 0, 0, .3) inset;
  box-shadow: 0 1px 1px rgba(0, 0, 0, .3) inset;
}

.button[disabled], .button[disabled]:hover, .button[disabled]:active{
  border-color: #eaeaea;
  background: #fafafa;
  cursor: default;
  position: static;
  color: #999;
  /* Usually, !important should be avoided but here it's really needed :) */
  -moz-box-shadow: none !important;
  -webkit-box-shadow: none !important;
  box-shadow: none !important;
  text-shadow: none !important;
}

/* Smaller buttons styles */

.button.small{
  padding: 4px 12px;
}

/* Larger buttons styles */

.button.large{
  padding: 12px 30px;
  text-transform: uppercase;
}

.button.large:active{
  top: 2px;
}

/* Colored buttons styles */

.button.green, .button.red, .button.blue {
  color: #fff;
  text-shadow: 0 1px 0 rgba(0,0,0,.2);
  
  background-image: -webkit-gradient(linear, left top, left bottom, from(rgba(255,255,255,.3)), to(rgba(255,255,255,0)));
  background-image: -webkit-linear-gradient(top, rgba(255,255,255,.3), rgba(255,255,255,0));
  background-image: -moz-linear-gradient(top, rgba(255,255,255,.3), rgba(255,255,255,0));
  background-image: -ms-linear-gradient(top, rgba(255,255,255,.3), rgba(255,255,255,0));
  background-image: -o-linear-gradient(top, rgba(255,255,255,.3), rgba(255,255,255,0));
  background-image: linear-gradient(top, rgba(255,255,255,.3), rgba(255,255,255,0));
}

/* */

.button.blue{
  background-color: #269CE9;
  border-color: #269CE9;
}

.button.blue:hover{
  background-color: #70B9E8;
}

.button.blue:active{
  background: #269CE9;
}

/* */

.blue[disabled], .blue[disabled]:hover, .blue[disabled]:active{
  border-color: #269CE9;
  background: #269CE9;
  color: #93D5FF;
}	
</style>
<strong>Selamat {{ $greeting }}</strong>,
<p>
	Kami menerima permintaan penggantian password untuk Akun anda di {{ $data['config']['app']['abbr'] }} 
{{ htmlspecialchars_decode($data['config']['app']['title'], ENT_QUOTES) }} 
dari alamat IP <strong>{{ $data['ip'] }}</strong> pada <strong>{{ date('d-m-Y H:i') }}</strong>.<br/>
Klik link dibawah ini untuk mengganti Password anda. Jika anda tidak meminta penggantian Password ini, anda dapat mengabaikan email ini. Akun dan Password anda tetap aman
</p>

<a href="{{ url('/password/reset/' . $data['username'] . '/' . $data['reset_token']) }}" class="button large blue">Ganti Password</a><br/><br/>

(Jika Link diatas tidak bekerja, silahkan kopi & tempel link berikut langsung di browser anda)<br/>
{{ url('/password/reset/' . $data['username'] . '/' . $data['reset_token']) }}<br/>

<p>
	Untuk pertanyaan lebih lanjut hubungi Sekretariat {{ $data['config']['profil']['nama'] }} di:<br/>
<address class="center-block">
	{{ $data['config']['profil']['alamat']['jalan'] }} {{ $data['config']['profil']['alamat']['kabupaten'] }}<br/>
	<strong>Telepon:</strong> {{ $data['config']['profil']['telepon'] }}<br/>
	<strong>Email:</strong> @foreach(explode(',', $data['config']['profil']['email']) as $email) <a href="mailto:{{ trim($email) }}">{{ trim($email) }}</a> &nbsp; @endforeach<br/>
	<strong>Website:</strong> <a href="{!! $data['config']['profil']['website'] !!}">{!! $data['config']['profil']['website'] !!}</a><br/>
	<strong>Fabecook:</strong> <a href="{!! $data['config']['profil']['facebook'] !!}">Facebook {!! $data['config']['profil']['singkatan'] !!}</a><br/>
	<strong>Twtiter:</strong> <a href="{!! $data['config']['profil']['twitter'] !!}">Twitter {!! $data['config']['profil']['singkatan'] !!}</a><br/>
</address>
</p>

<br/>
<em><strong>Perhatian:</strong> Email ini dikirimkan secara otomatis oleh sistem, mohon untuk tidak mengirimkan pertanyaan atau balasan ke alamat email ini. </em>

