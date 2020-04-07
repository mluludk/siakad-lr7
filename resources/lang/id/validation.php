<?php
	
	return [
	
	"valid_name"                => "Kolom :attribute hanya boleh berisi huruf, spasi, titik, atau tanda petik.",
	
	"accepted"             => "The :attribute must be accepted.",
	"active_url"           => "The :attribute is not a valid URL.",
	"after"                => "The :attribute must be a date after :date.",
	"alpha"                => "The :attribute may only contain letters.",
	"alpha_dash"           => "The :attribute may only contain letters, numbers, and dashes.",
	"alpha_num"            => "The :attribute may only contain letters and numbers.",
	"array"                => "The :attribute must be an array.",
	"before"               => "The :attribute must be a date before :date.",
	"between"              => [
	"numeric" => "The :attribute must be between :min and :max.",
	"file"    => "The :attribute must be between :min and :max kilobytes.",
	"string"  => "The :attribute must be between :min and :max characters.",
	"array"   => "The :attribute must have between :min and :max items.",
	],
	"boolean"              => "The :attribute field must be true or false.",
	"confirmed"            => "The :attribute confirmation does not match.",
	"date"                 => "The :attribute is not a valid date.",
	"date_format"          => "The :attribute does not match the format :format.",
	"different"            => "Kolom :attribute dan :other harus berbeda.",
	"digits"               => "The :attribute must be :digits digits.",
	"digits_between"       => "The :attribute must be between :min and :max digits.",
	"email"                => "The :attribute must be a valid email address.",
	"filled"               => "The :attribute field is required.",
	"exists"               => "The selected :attribute is invalid.",
	"image"                => ":attribute harus berupa gambar.",
	"in"                   => "The selected :attribute is invalid.",
	"integer"              => "The :attribute must be an integer.",
	"ip"                   => "The :attribute must be a valid IP address.",
	"max"                  => [
	"numeric" => "The :attribute may not be greater than :max.",
	"file"    => "The :attribute may not be greater than :max kilobytes.",
	"string"  => "The :attribute may not be greater than :max characters.",
	"array"   => "The :attribute may not have more than :max items.",
	],
	"mimes"                => "The :attribute must be a file of type: :values.",
	"min"                  => [
	"numeric" => "The :attribute must be at least :min.",
	"file"    => "The :attribute must be at least :min kilobytes.",
	"string"  => "The :attribute must be at least :min characters.",
	"array"   => "The :attribute must have at least :min items.",
	],
	"not_in"               => "The selected :attribute is invalid.",
	"numeric"              => "The :attribute must be a number.",
	"regex"                => "The :attribute format is invalid.",
	"required"             => "Kolom :attribute harus diisi.",
	"required_if"          => "The :attribute field is required when :other is :value.",
	"required_with"        => "The :attribute field is required when :values is present.",
	"required_with_all"    => "The :attribute field is required when :values is present.",
	"required_without"     => "The :attribute field is required when :values is not present.",
	"required_without_all" => "The :attribute field is required when none of :values are present.",
	"same"                 => "The :attribute and :other must match.",
	"size"                 => [
	"numeric" => "The :attribute must be :size.",
	"file"    => "The :attribute must be :size kilobytes.",
	"string"  => "The :attribute must be :size characters.",
	"array"   => "The :attribute must contain :size items.",
	],
	"unique"               => ":attribute sudah terdaftar.",
	"url"                  => "The :attribute format is invalid.",
	"timezone"             => "The :attribute must be a valid zone.",
	'greater_than' => 'Nilai pada kolom :attribute harus lebih besar dari :value ',
	
	/*
		|--------------------------------------------------------------------------
		| Custom Validation Language Lines
		|--------------------------------------------------------------------------
		|
		| Here you may specify custom validation messages for attributes using the
		| convention "attribute.rule" to name the lines. This makes it quick to
		| specify a specific custom language line for a given attribute rule.
		|
	*/
	
	'custom' => [
	'NIM' => [
	'required' => 'Nomor Induk Mahasiswa (NIM) harus diisi.',
	'unique' => 'Mahasiswa dengan NIM tersebut sudah terdaftar, silahkan periksa kembali.',
	],
	'issn' => [
	'required' => 'No. ISSN / ISBN harus diisi.',
	'digits' => 'No. ISSN / ISBN harus terdiri dari  9 digit.',
	],
	'NIP' => [
	'required' => 'Nomor Induk Pegawai (NIP) harus diisi bagi PNS.',
	'digits' => 'NIP harus terdiri dari 18 digit. Hanya diisi untuk PNS',
	],
	'NIDN' => [
	'required' => 'Nomor Induk Dosen Nasional (NIDN) harus diisi.',
	'digits' => 'Nomor Induk Dosen Nasional (NIDN) harus terdiri dari 10 digit. Kosongkan jika belum mempunyai NIDN',
	],
	'NIK' => [
	'required' => 'Nomor Induk Kependudukan (NIK) / No. KTP harus diisi.',
	'digits_between' => 'Nomor Induk Kependudukan (NIK) / No. KTP terdiri dari 16 digit untuk e-KTP atau 17 digit untuk KTP lama.',
	],
	'tmpLahir' =>[
	'required' => 'Tempat kelahiran harus diisi.',
	],
	'tglLahir' =>[
	'required' => 'Tanggal kelahiran harus diisi.',
	],
	],
	
	/*
		|--------------------------------------------------------------------------
		| Custom Validation Attributes
		|--------------------------------------------------------------------------
		|
		| The following language lines are used to swap attribute place-holders
		| with something more reader friendly such as E-Mail Address instead
		| of "email". This simply helps us make messages a little cleaner.
		|
	*/
	
	'attributes' => [
	'pembimbing1' => 'Pembimbing I',
	'pembimbing2' => 'Pembimbing II',
	],
	
	];
