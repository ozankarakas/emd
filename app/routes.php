<?php

// Route::group(['before' => 'route-cache', 'after' => 'route-cache'], function() {

Route::group(['before' => 'guest|maintenance'], function() {

		/**

		 * Login Page (GET)

		 */

		Route::get('/', [

			'as'		=> 'home',

			'uses'		=> 'AuthController@getLogin',

			]);

		Route::get('/login', [

			'as'		=> 'login',

			'uses'		=> 'AuthController@getLogin',

			]);



		Route::get('hubland', [

			'as' => 'hubland',

			'uses' => 'AccountController@hubland'

			]);

		/**

		 * Login Page (POST)

		 */

		Route::post('/login', [

			'before' 	=> 'csrf',

			'as'		=> 'login',

			'uses'		=> 'AuthController@postLogin',

			]);

		Route::get('demo', [

			'as' 		=> 'demo',

			'uses'		=> 'AuthController@getLoginDemo',

			]);

		Route::post('demo', [

			'before' 	=> 'csrf',

			'uses'		=> 'AuthController@postLoginDemo',

			]);

		/**

		 * Forgot Password (GET)

		 */

		Route::get('/account/forgot-password', array(

			'as' 		=> 'account-forgotpassword',

			'uses' 		=> 'AccountController@getForgotPassword'

			));

		/**

		 * Forgot Password (AJAX - POST)

		 */

		Route::post('/account/forgot-password', array(

			'before' 	=> 'csrf',

			'as' 		=> 'account-forgotpassword',

			'uses' 		=> 'AccountController@postForgotPassword'

			));

		/**

		 * Recover Password (GET)

		 */

		Route::get('/account/recover-password/{code}', array(

			'as' 		=> 'account-recoverpassword',

			'uses' 		=> 'AccountController@getRecoverPassword'

			));

	});

	/**

	 * Main Pages (filter -> AUTH)

	 */

	Route::group(['before' => 'auth|lock|maintenance'], function() {



		Route::get('/passport',[

			'as' 		=> 'passport',

			'uses' 		=> 'PassportController@getIndex'

			]);



		/**

		 * accountSetup (GET)

		 */

		Route::get('/add-account',[

			'as' 		=> 'add-account',

			'uses' 		=> 'AccountSetupController@getIndex'

			]);



		Route::get('/list-emd-users',[

			'as' 		=> 'list-emd-users',

			'uses' 		=> 'AccountSetupController@list_emd_users'

			]);



		Route::post('/delete-emd-users',[

			'as' 		=> 'delete-emd-users',

			'uses' 		=> 'AccountSetupController@delete_emd_users'

			]);



		/**

		 * accountSetup (GET)

		 */

		Route::post('/add-account',[

			'as' 		=> 'add-account',

			'uses' 		=> 'AccountSetupController@postInfo'

			]);



		Route::post('/add-account-lcs',[

			'as' 		=> 'add-account-lcs',

			'uses' 		=> 'AccountSetupController@getLcs'

			]);

		/**

		 * leagueTable (GET)

		 */

		Route::get('/leagueTable',[

			'as' 		=> 'leagueTable',

			'uses' 		=> 'LeagueTableController@getIndex'

			]);

		/**

		 * leagueTable (POST)

		 */

		Route::post('/leagueTable',[

			'before' 	=> 'csrf',

			'as' 		=> 'leagueTable',

			'uses' 		=> 'LeagueTableController@postMethod'

			]);

		/**

 * Test (GET)

 */

Route::get('/spreport',[

	'as' 		=> 'spreport',

	'uses' 		=> 'SPReportController@getIndex'

	]);

/**

 * Test (POST)

 */

Route::post('/spreport',[

	'before' 	=> 'csrf',

	'as' 		=> 'spreport',

	'uses' 		=> 'SPReportController@postKPI'

	]);


		/**

		 * authentication (GET)

		 */

		Route::get('/authentication',[

			'as' 		=> 'authentication',

			'uses' 		=> 'AuthenticationController@getIndex'

			]);

		/**

		 * Guidance (GET)

		 */

		Route::get('/guidance',[

			'as' 		=> 'guidance',

			'uses' 		=> 'GuidanceController@getIndex'

			]);



		/**

		 * Guidance (GET)

		 */

		Route::get('/test',function(){

			$redis = Redis::connection();

			$redis->set('name', 'YAPRAK');

			$redis->del('name');

			$name = $redis->get('name');

			var_dump($name);





			// Cache::tags('people', 'authors')->put('John', 'YARAK', 10000);

			// $john = Cache::tags(array('people', 'authors'))->get('John');

			// var_dump($john);

		});



		/**

		 * doverallkpi (GET)

		 */

		Route::get('/doverallkpi',[

			'as' 		=> 'doverallkpi',

			'uses' 		=> 'DoverallkpiController@getIndex'

			]);

		/**

		 * doverallkpi (POST)

		 */

		Route::post('/doverallkpifilters',[

			'before' 	=> 'csrf',

			'as' 		=> 'doverallkpifilters',

			'uses' 		=> 'DoverallkpiController@postFilters'

			]);

		Route::get('/doverallkpifilters',[

			'as' 		=> 'doverallkpifilters',

			'uses' 		=> 'DoverallkpiController@postFilters'

			]);

		/**

		 * doverallkpi (POST)

		 */

		Route::post('/dashboardajax',[

			'before' 	=> 'csrf',

			'as' 		=> 'dashboard',

			'uses' 		=> 'DashboardController@postKPI'

			]);

		/**

		 * dsitekpi (GET)

		 */

		Route::get('/dsitekpi',[

			'as' 		=> 'dsitekpi',

			'uses' 		=> 'DsitekpiController@getIndex'

			]);

		/**

		 * dsitekpi (POST)

		 */

		// Route::post('/dsitekpi',[

		// 	'before' 	=> 'csrf',

		// 	'as' 		=> 'dashboard',

		// 	'uses' 		=> 'DashboardController@postKPI'

		// 	]);

		/**

		 * dprogrammekpi (GET)

		 */

		Route::get('/dprogrammekpi',[

			'as' 		=> 'dprogrammekpi',

			'uses' 		=> 'DprogrammekpiController@getIndex'

			]);

		// /**

		//  * dprogrammekpi (POST)

		//  */

		// Route::post('/dprogrammekpi',[

		// 	'before' 	=> 'csrf',

		// 	'as' 		=> 'dashboard',

		// 	'uses' 		=> 'DashboardController@postKPI'

		// 	]);

		/**

		 * dprogrammekpi (GET)

		 */

		Route::get('/dparticipantkpi',[

			'as' 		=> 'dparticipantkpi',

			'uses' 		=> 'DparticipantkpiController@getIndex'

			]);

		/**

		 * dprogrammekpi (POST)

		 */

		// Route::post('/dparticipantkpi',[

		// 	'before' 	=> 'csrf',

		// 	'as' 		=> 'dashboard',

		// 	'uses' 		=> 'DashboardController@postKPI'

		// 	]);

		/**

		 * Account (GET)

		 */

		Route::get('/account',[

			'as' 		=> 'account',

			'uses' 		=> 'AccountController@getIndex'

			]);

		/**

		 * Account (POST)

		 */

		Route::post('/account',[

			'before' 	=> 'csrf',

			'as' 		=> 'account',

			'uses' 		=> 'AccountController@postInfo'

			]);

		/**

		 * sessions (GET)

		 */

		Route::get('/sessions',[

			'as' 		=> 'sessions',

			'uses' 		=> 'SessionsController@getIndex'

			]);

		/**

		 * sessions (POST)

		 */

		Route::post('/sessions',[

			'before' 	=> 'csrf',

			'as' 		=> 'sessions',

			'uses' 		=> 'SessionsController@postKPI'

			]);

		/**

		 * Preferences (GET)

		 */

		Route::get('/filters',[

			'as' 		=> 'filters',

			'uses' 		=> 'FiltersController@getIndex'

			]);

		/**

		 * Preferences (POST)

		 */

		Route::post('/filters',[

			'before' 	=> 'csrf',

			'as' 		=> 'filters',

			'uses' 		=> 'FiltersController@postKPI'

			]);

		/**

		 * Programme (GET)

		 */

		Route::get('/programme',[

			'as' 		=> 'programme',

			'uses' 		=> 'ProgrammeController@getIndex'

			]);

		/**

		 * Programme (POST)

		 */

		Route::post('/programme',[

			'before' 	=> 'csrf',

			'as' 		=> 'programme',

			'uses' 		=> 'ProgrammeController@postKPI'

			]);

		/**

		 * Participation (GET)

		 */

		Route::get('/participation',[

			'as' 		=> 'participation',

			'uses' 		=> 'ParticipationController@getIndex'

			]);

		/**

		 * Participation (POST)

		 */

		Route::post('/participation',[

			'before' 	=> 'csrf',

			'as' 		=> 'participation',

			'uses' 		=> 'ParticipationController@postKPI'

			]);

		/**

		 * Financial (GET)

		 */

		Route::get('/financial',[

			'as' 		=> 'financial',

			'uses' 		=> 'FinancialController@getIndex'

			]);

		/**

		 * Financial (POST)

		 */

		Route::post('/financial',[

			'before' 	=> 'csrf',

			'as' 		=> 'financial',

			'uses' 		=> 'FinancialController@postKPI'

			]);

		/**

		 * Frequency (GET)

		 */

		Route::get('/frequency',[

			'as' 		=> 'frequency',

			'uses' 		=> 'FrequencyController@getIndex'

			]);

		/**

		 * Frequency (POST)

		 */

		Route::post('/frequency',[

			'before' 	=> 'csrf',

			'as' 		=> 'frequency',

			'uses' 		=> 'FrequencyController@postKPI'

			]);

		/**

		 * Consistency (GET)

		 */

		Route::get('/consistency',[

			'as' 		=> 'consistency',

			'uses' 		=> 'ConsistencyController@getIndex'

			]);

		/**

		 * Consistency (POST)

		 */

		Route::post('/consistency',[

			'before' 	=> 'csrf',

			'as' 		=> 'consistency',

			'uses' 		=> 'ConsistencyController@postKPI'

			]);

		/**

		 * Rateofchurn (GET)

		 */

		Route::get('/rateofchurn',[

			'as' 		=> 'rateofchurn',

			'uses' 		=> 'RateofchurnController@getIndex'

			]);

		/**

		 * Rateofchurn (POST)

		 */

		Route::post('/rateofchurn',[

			'before' 	=> 'csrf',

			'as' 		=> 'rateofchurn',

			'uses' 		=> 'RateofchurnController@postKPI'

			]);

		/**

		 * Change Password (GET)

		 */

		Route::get('/account/change-password', array(

			'as' 		=> 'account-changepassword',

			'uses' 		=> 'AccountController@getChangePassword'

			));

		/**

		 * Change Password (POST)

		 */

		Route::post('/account/change-password', array(

			'before' 	=> 'csrf',

			'as' 		=> 'account-changepassword',

			'uses' 		=> 'AccountController@postChangePassword'

			));

		/*

	|--------------------------------------------------------------------------

	| Activity Barometer Starts

	|--------------------------------------------------------------------------

	*/

	Route::get('activity-barometer-operations', [

		'before' => 'activity-barometer',

		'as'         => 'activity-barometer-operations',

		'uses'         => 'ActivityBarometerController@getIndexOperations'

		]);

	Route::post('activity-barometer-operations/postReportOperations', [

		'before' => 'activity-barometer',

		'as'         => 'activity-barometer.postReportOperations',

		'uses'         => 'ActivityBarometerController@postReportOperations'

		]);

	Route::post('activity-barometer-operations/detailedGraphOperations', [

		'before' => 'activity-barometer',

		'as'         => 'activity-barometer.detailedGraphOperations',

		'uses'         => 'ActivityBarometerController@detailedGraphOperations'

		]);


	Route::post('activity-barometer-operations/postGraphOperations', [

		'before' => 'activity-barometer',

		'as'         => 'activity-barometer.postGraphOperations',

		'uses'         => 'ActivityBarometerController@postGraphOperations'

		]);


	Route::get('activity-barometer-bubble', [

		'before' => 'activity-barometer',

		'as'         => 'activity-barometer-bubble',

		'uses'         => 'ActivityBarometerController@getIndexBubble'

		]);

	Route::post('activity-barometer-bubble/postGraphBubble', [

		'before' => 'activity-barometer',

		'as'         => 'activity-barometer.postGraphBubble',

		'uses'         => 'ActivityBarometerController@postGraphBubble'

		]);

	Route::post('activity-barometer-bubble/postDetailedGraphBubble', [

		'before' => 'activity-barometer',

		'as'         => 'activity-barometer.postDetailedGraphBubble',

		'uses'         => 'ActivityBarometerController@postDetailedGraphBubble'

		]);

	Route::get('activity-barometer-catchment', [

		'before' => 'activity-barometer',

		'as'         => 'activity-barometer-catchment',

		'uses'         => 'ActivityBarometerController@getIndexCatchment'

		]);

	Route::post('activity-barometer-catchment/postGraphCatchment', [

		'before' => 'activity-barometer',

		'as'         => 'activity-barometer.postGraphCatchment',

		'uses'         => 'ActivityBarometerController@postGraphCatchment'

		]);

	/*

	|--------------------------------------------------------------------------

	| Activity Barometer Ends

	|--------------------------------------------------------------------------

	*/

});

Route::group(['before' => 'auth|maintenance'], function() {

		/**

		 * Account Locked (GET)

		 */

		Route::get('/account/locked', array(

			'as' 		=> 'account-locked',

			'uses' 		=> 'AccountController@getAccountLocked'

			));

		/**

		 * Account Locked (POST)

		 */

		Route::post('/account/locked', array(

			'before' 	=> 'csrf',

			'as' 		=> 'account-locked',

			'uses' 		=> 'AccountController@postAccountLocked'

			));

		/**

		 * Account Lock (Ajax) (POST)

		 */

		Route::post('/account/lock', array(

			'as' 		=> 'account-lock',

			'uses' 		=> 'AccountController@ajaxLockAccount'

			));

		/**

		 * LogOut (GET)

		 */

		Route::get('/logout',[

			'as' 		=> 'logout',

			'uses' 		=> 'AuthController@logout'

			]);

	});




// });

