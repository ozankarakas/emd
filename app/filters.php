<?php



/*

|--------------------------------------------------------------------------

| Application & Route Filters

|--------------------------------------------------------------------------

|

| Below you will find the "before" and "after" events for the application

| which may be used to do any work before or after a request into your

| application. Here you may also register your custom route filters.

|

*/



App::before(function($request)

{

	DB::connection()->disableQueryLog();

	// Event::listen('illuminate.query', function ($query) {

	// 	Log::info($query);

	// });

});





App::after(function($request, $response)
{
	
});





// Route::filter('route-cache', function($route, $request, $response = null)

// {

	

//     $key = 'route-'.Str::slug(Request::url());



//     if(is_null($response) && Cache::has($key))

//     {

//         return Cache::get($key);

//     }

//     elseif(!is_null($response) && !Cache::has($key))

//     {

//         Cache::put($key, $response->getContent(), 30);

//     }

// });





/*

|--------------------------------------------------------------------------

| Authentication Filters

|--------------------------------------------------------------------------

|

| The following filters are used to verify that the user of the current

| session is logged into this application. The "basic" filter easily

| integrates HTTP Basic authentication for quick, simple checking.

|

*/



Route::filter('auth', function()

{

	if ( Auth::guest() ) // If the user is not logged in

	{

		if (Request::ajax())

		{

			return Response::make('Unauthorized', 401);

		}

		return Redirect::guest('/login');

	}

});



Route::filter('auth.basic', function()

{

	return Auth::basic();

});



/*

|--------------------------------------------------------------------------

| Guest Filter

|--------------------------------------------------------------------------

|

| The "guest" filter is the counterpart of the authentication filters as

| it simply checks that the current user is not logged in. A redirect

| response will be issued if they are, which you may freely change.

|

*/



Route::filter('guest', function()

{

	if ( Auth::check() ) return Redirect::to('/guidance');

});



/*

|--------------------------------------------------------------------------

| Locked Session Filter

|--------------------------------------------------------------------------

|

| What if a user idles for 30 minutes? It's session is locked.

|

*/



Route::filter('lock', function()

{

	if (Session::get('locked') === true) {

		return Redirect::route('account-locked');

	}

});



/*

|--------------------------------------------------------------------------

| CSRF Protection Filter

|--------------------------------------------------------------------------

|

| The CSRF filter is responsible for protecting your application against

| cross-site request forgery attacks. If this special token in a user

| session does not match the one given in this request, we'll bail.

|

*/



Route::filter('csrf', function()

{

	if (Request::ajax()) {

		if (Session::token() != Request::header('X-CSRF-Token'))

		{

			return Response::json([

					'success' 	=> false,

					'errors'	=> ['Token mismatch!']

				]);

		}

	}

	else 

	{

		if (Session::token() != Input::get('_token'))

		{

			//throw new Illuminate\Session\TokenMismatchException;

			return Redirect::route('login');

		}

	}

});



Route::filter('maintenance', function()

{

	if (false) {

		return View::make('maintenance');

	}

});