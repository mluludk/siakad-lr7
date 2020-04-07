<?php
	
	namespace Siakad\Http\Controllers;
	
	use Illuminate\Http\Request;
	
	
	use Siakad\Mail;
	
	//use Siakad\Http\Requests;
	use Siakad\Http\Controllers\Controller;
	
	class MailController extends Controller
	{
		/**
			* Display a listing of the resource.
			*
			* @return \Illuminate\Http\Response
		*/
		public function index()
		{
			$user = \Auth::user();
			$my_mail = Mail::with('from.authable') -> where('recipient', $user -> id);
			
			$inbox = $my_mail -> orderBy('time', 'desc') -> paginate(30);
			// $unread = $my_mail -> where('read', 'n') -> count();
			
			return view('mail.index', compact('inbox'));
		}
		
		/**
			* Show the form for creating a new resource.
			*
			* @return \Illuminate\Http\Response
		*/
		public function create()
		{
			return view('mail.create');
		}
		
		/**
			* Store a newly created resource in storage.
			*
			* @param  \Illuminate\Http\Request  $request
			* @return \Illuminate\Http\Response
		*/
		public function store(Request $request)
		{
			//
		}
		
		/**
			* Display the specified resource.
			*
			* @param  int  $id
			* @return \Illuminate\Http\Response
		*/
		public function show($title, $id)
		{
			$user = \Auth::user();
			$my_mail = Mail::with('from.authable') -> where('recipient', $user -> id);
			// $unread = $my_mail -> where('read', 'n') -> count();
			
			$mail = Mail::with('from.authable') -> find($id);
			$mail -> update(['read' => 'y']);
			
			return view('mail.show', compact('mail'));
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
		