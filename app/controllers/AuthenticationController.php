<?php
class AuthenticationController extends BaseController {
	public function getIndex() 
	{
		$session = new Sessions();
		$session->id = Session::getId();
		$session->user_id = Auth::user()->id;
		$session->in = time();
		$session->ip = $_SERVER['REMOTE_ADDR'];
		$session->sport = Auth::user()->sport;
		$session->save();
		if(Auth::user()->role == "ADM")
		{
			return Redirect::route('doverallkpi');
		}
		else
		{
			return Redirect::route('guidance');
		}
	}
}
