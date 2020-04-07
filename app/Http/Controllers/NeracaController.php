<?php
	
	namespace Siakad\Http\Controllers;
	
	use Redirect;
	
	use Illuminate\Http\Request;
	
	
	use Siakad\Neraca;
	//use Siakad\Http\Requests;
	use Siakad\Http\Controllers\Controller;
	
	class NeracaController extends Controller
	{
		use \Siakad\NeracaTrait;
		/**
			* Display a listing of the resource.
			*
			* @return \Illuminate\Http\Response
		*/
		public function index()
		{
			$query = '
			SELECT
			`hitungSaldo`.`id`,
			`hitungSaldo`.`masuk`,
			`hitungSaldo`.`keluar`,
			`hitungSaldo`.`saldo`,
			DATE_FORMAT(`hitungSaldo`.`created_at`, "%d %b %y") AS `tanggal`
			FROM (
			SELECT 
			`neraca`.`id`,
			`neraca`.`masuk`,
			`neraca`.`keluar`,
			@saldo := @saldo + `neraca`.`masuk` - `neraca`.`keluar` AS `saldo`,
			`neraca`.`created_at`
			FROM `neraca`, (SELECT @saldo := 0) AS saldoAsal
			ORDER BY `neraca`.`id` ASC
			) AS `hitungSaldo`
			ORDER BY `hitungSaldo`.`id` DESC;
			';
			$neraca = \DB::select($query);
			return view('neraca.index', compact('neraca'));
		}
		
		/**
			* Show the form for creating a new resource.
			*
			* @return \Illuminate\Http\Response
		*/
		public function create()
		{			
			//
		} 
		
		/**
			* Store a newly created resource in storage.
			*
			* @param  \Illuminate\Http\Request  $request
			* @return \Illuminate\Http\Response
		*/
		public function store(Request $request)
		{
			
		}
		
		/**
			* Display the specified resource.
			*
			* @param  int  $id
			* @return \Illuminate\Http\Response
		*/
		public function show($id)
		{
			//
		}
		
		/**
			* Show the form for editing the specified resource.
			*
			* @param  int  $id
			* @return \Illuminate\Http\Response
		*/
		public function edit($id)
		{
			//
		}
		
		/**
			* Update the specified resource in storage.
			*
			* @param  \Illuminate\Http\Request  $request
			* @param  int  $id
			* @return \Illuminate\Http\Response
		*/
		public function update(Request $request, $id)
		{
			//
		}
		
		/**
			* Remove the specified resource from storage.
			*
			* @param  int  $id
			* @return \Illuminate\Http\Response
		*/
		public function destroy($id)
		{
			//
		}
	}
