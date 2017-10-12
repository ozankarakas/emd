<?php

class AccountController extends BaseController {

	public function getIndex()
	{
		return View::make('account');
	}
	public function postInfo()
	{
		if(Input::get('ajax_action') == "save_user_information")
		{
			$user        = User::find(Auth::User()->id);
			$user->name  = Input::get("name");
			$user->email = Input::get("email");
			if(Input::get("password") != "")
			{
				$user->password = Hash::make(Input::get("password"));
			}
			$user->save();
		}
	}
	public function getCreate()
	{
		return View::make('account.create');
	}
	public function postCreate()
	{
		/**
		 * Getting all input data
		 */
		$data = Input::all();
		/**
		 * Assigning all input data to variables
		 * For example: 
		 * @var $data['name'] 
		 * will be 
		 * @var $name
		 */
		foreach ($data as $key => $value) {
			${$key} = $value;
		}
		/**
		 * Laravel validator, setting up requirements
		 */
		$validator = Validator::make(
			/**
			 * First the inputs
			 */
			Input::all(),
			/**
			 * Than the requirements
			 */
			array(
				'username' 			=> 'required|min:3|max:25|unique:users|alpha_num',
				'name' 				=> 'required|max:25|alpha',
				'surname' 			=> 'required|max:25|alpha',
				'email' 			=> 'required|min:3|max:50|email|unique:users',
				'password' 			=> 'required|min:6',
				'password_again'	=> 'required|same:password'
				)
			);
		/**
		 * If the validator fails.
		 */
		if ($validator->fails()) {
			return Response::json([
				'success' 	=> false,
				'errors' 	=> $validator->errors()->toArray()
				]);
		}
		$code = str_random(32);
		/**
		 * If the validator passes
		 */	
		$user = User::create([
			'username' 			=> $username,
			'name' 				=> $name,
			'surname' 			=> $surname,
			'email' 			=> $email,
			'password' 			=> Hash::make($password),
			'code' 				=> $code,
			'active' 			=> 0
			]);
		if ($user) {
			/**
			 * Sending activation email
			 */
			Mail::send('emails.auth.activation', array('link' => URL::route('account-activate', $code), 'username' => $username), function($message) use ($user) {
				$message->to($user->email,$user->username)->subject('Activate your account');
			});
			/**
			 * Redirecting to home while flashing a message with Session
			 */
			Session::flash('message','User has been created. We have sent you an email to activate your account.');
			return Response::json([
				'success' 	=> true,
				]);
		} else {
			return Response::json([
				'success' 	=> false,
				'errors' 	=> array(
					['User could not be created.']
					)
				]);
		}
	}
	public function getActivate($code)
	{
		$user = User::where('code', '=', $code)->where('active', '=', 0);
		if ($user->count()) {
			$user = $user->first();
			if($user->code == $code) 
			{
				$user->code = '';
				$user->active = 1;
				if ($user->save()) {
					return  Redirect::route('home')
					->with('message', 'You have successfully activated your account, you can now sign in.');
				}
			}
		}
		return  Redirect::route('home')
		->with('message', 'There was a problem about activating your account.');
	}
	public function getSignin()
	{
		return View::make('account.signin');
	}
	public function postSignin()
	{
		/**
		 * Getting all input data
		 */
		$data = Input::all();
		/**
		 * Assigning all input data to variables
		 * For example: 
		 * @var $data['name'] 
		 * will be 
		 * @var $name
		 */
		foreach ($data as $key => $value) {
			${$key} = $value;
		}
		/**
		 * Laravel validator, setting up requirements
		 */
		$validator = Validator::make(
			/**
			 * First the inputs
			 */
			Input::all(),
			/**
			 * Than the requirements
			 */
			array(
				'username' 			=> 'required',
				'password' 			=> 'required',
				),
			array(
				'username.required' => 'Username is required.',
				'password.required' => 'Password is required.',
				)
			);
		/**
		 * If the validator fails.
		 */
		if ($validator->fails()) {
			return Response::json([
				'success' 	=> false,
				'errors' 	=> $validator->errors()->toArray()
				]);
		}
		/**
		 * If the validator passes
		 */
		$remember = ($remember == 'true') ? true : false;
		$auth = Auth::attempt(array(
			'email' 		=> $username,
			'password' 		=> $password,
//			'active' 		=> 1
			), $remember);
		if ($auth) {
			Session::flash('message', 'You have successfully logged in.');
			return Response::json([
				'success' 	=> true,
				]);
		}
		return Response::json([
			'success' 	=> false,
			'errors'	=> [['The email/password combination is wrong or the account isn\'t activated.']]
			]);
	}
	public function getSignout()
	{
		Auth::logout();
		return  Redirect::route('home')
		->with('message', 'You have successfully signed out.');
	}
	public function getChangePassword()
	{
		return View::make('account.change_password');
	}
	public function postChangePassword()
	{
		$validator = Validator::make(Input::all(),
			array(
				'old_password' 			=> 'required',
				'new_password' 			=> 'required|min:6|different:old_password',
				'new_password_again' 	=> 'required|same:new_password',
				)
			);
		if ($validator->fails()) {
			return Response::json([
				'success' 	=> false,
				'errors' 	=> $validator->errors()->toArray()
				]);
		} else {
			$user = User::find(Auth::user()->id);
			$old_password = Input::get('old_password');
			$new_password = Input::get('new_password');
			if (Hash::check($new_password, $user->getAuthPassword())) {
				return Response::json([
					'success' 	=> false,
					'errors' 	=> [['The new password is same as the current password.']]
					]);	
			}
			if (Hash::check($old_password, $user->getAuthPassword())) {
				$user->password = Hash::make($new_password);
				if ($user->save()) {
					Session::flash('massage', 'You have successfully changed your password.');
					return Response::json([
						'success' 	=> true,
						]);
				}
			}
		}
	}
	public function getForgotPassword()
	{
		return View::make('account.forgot_password');
	}
	public function postForgotPassword()
	{
		$validator = Validator::make(Input::all(),
			array(
				'email' => 'required|email|min:6'
				)
			);
		if ($validator->fails()) {
			return Response::json([
				'success' 	=> false,
				'errors' 	=> $validator->errors()->toArray()
				]);
		} else {
			$user = User::where('email', '=', Input::get('email'))->first();
			if (!$user) {
				return Response::json([
					'success' 	=> false,
					'errors' 	=> ['Email does not match to any user.']
					]);
			} else {
				$code 		= str_random(32);
				$password 	= str_random(10);
				$user->code 			= $code;
				$user->password_temp	= Hash::make($password);
				$username 				= $user->username;
				$user->save();
				/**
				 * Sending recovery email
				 */
				Mail::send('emails.auth.reminder', array('link' => URL::route('account-recoverpassword', $code), 'username' => $username, 'password' => $password), function($message) use ($user) {
					$message->to($user->email,$user->name)->subject('Recover your account');
				});
				Session::flash('message', 'We have sent you a recovery email.');
				return Response::json([
					'success' 	=> true,
					]);
			}
		}
	}
	public function getRecoverPassword($code)
	{
		$user = User::where('code', '=', $code)->first();
		if (!$user) {
			return "The code is false or user could not be found.";
		} else {
			$user->password = $user->password_temp;
			$user->password_temp = '';
			$user->code = '';
			$user->save();
			return  Redirect::route('home')
			->with('message', 'You can use the password you recieved by the email we have sent.');
		}
	}
	public function getAccountLocked() {
		if (Session::get('locked') === false) {
			return Redirect::route('doverallkpi');
		}
		return View::make('account.locked')->with('user', Auth::user());
	}
	public function postAccountLocked() {
		$v = Validator::make(
			Input::all(), 
			array(
				'password' => 'required'
				)
			);
		if ($v->fails()) {
			Session::flash('error', 'The password field is required.');
			return Redirect::back();
		}
		else
		{
			if ( Hash::check(Input::get('password'), Auth::user()->getAuthPassword())) {
				Session::put('locked', false);
				//here
				return Redirect::route(Session::get('last_page', 'doverallkpi'));
			}
			else
			{
				Session::flash('error', 'The password is not correct.');
				return Redirect::back();
			}
		}
	}
	public function ajaxLockAccount() {
		Session::put('locked', true);
	}
	public function hubland()
	{
		$user = User::findOrFail(Helper::Decrypt(str_replace(" ","+",Input::get('token')), 'dort@global@1190'));

		Auth::login($user);

		$session = new Sessions();

		$session->id = Session::getId();

		$session->user_id = Auth::user()->id;

		$session->in = time();

		$session->ip = $_SERVER['REMOTE_ADDR'];

		$session->sport = Auth::user()->sport;

		$session->save();

		return Redirect::route('doverallkpi');
	}
}