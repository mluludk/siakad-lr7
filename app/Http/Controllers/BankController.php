<?php
	
	namespace Siakad\Http\Controllers;
	
	use Redirect;
	use Illuminate\Http\Request;
	
	
	use Siakad\Bank;
	//use Siakad\Http\Requests;
	use Siakad\Http\Controllers\Controller;
	
	class BankController extends Controller
	{
		public function index()
		{
			$bank = Bank::where('id', '>', 0) -> get();
			return view('bank.index', compact('bank'));
		}
		
		/**
			* Show the form for creating a new resource.
			*
			* @return \Illuminate\Http\Response
		*/
		public function create()
		{
			return view('bank.create');
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
			Bank::create($input);			
			return Redirect::route('bank.index') -> with('message', 'Data Bank berhasil dimasukkan.');
		}
		
		/**
			* Show the form for editing the specified resource.
			*
			* @param  int  $id
			* @return \Illuminate\Http\Response
		*/
		public function edit($id)
		{
			$bank = Bank::find($id);
			return view('bank.edit', compact('bank'));
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
			Bank::find($id) -> update($input);			
			return Redirect::route('bank.index') -> with('message', 'Data Bank berhasil diperbarui.');
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
