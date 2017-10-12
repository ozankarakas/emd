<?php
class FiltersController extends BaseController {
	public function getIndex()
	{
		return View::make('filters');
	}
	public function postKPI()
	{
		if(Input::get('ajax_action') == "save_filters")
		{
			$values = GeneralFunctions::arrange_filters(Input::get('filters'));
			$person_type = $values['person_type'];
			$gender = $values['gender'];
			$age = $values['age'];
			$programme = $values['programme'];
			$date_end_current = $values['date_end_current'];
			$date_start_last = $values['date_start_last'];
			$date_end_last = $values['date_end_last'];
			$date_start_current = $values['date_start_current'];
			$lcs = $values["lcs"];

			$end_date = $values['date_end_current'];
			$start_date = date_create($end_date.' last day of -2 month')->format('Y-m-01 00:00:00');
			$start_date_year = date_create($end_date.' last day of -11 month')->format('Y-m-01 00:00:00');
			$end_date_last = date_create($end_date.' last day of -12 month')->format('Y-m-d 23:59:59');
			$start_date_last = date_create($end_date.' last day of -12 month')->format('Y-m-01 00:00:00');

			$start_of_churn_date = date_create($date_end_current.' last day of -3 month')->format('Y-m-01 00:00:00');
			$end_of_churn_date = date_create($date_end_current.' last day of -1 month')->format('Y-m-d 23:59:59');
			$start_date_mb = date_create($date_end_current.' last day of this month')->format('Y-m-01 00:00:00');
			//churn period | start => start_date_mb, end => $date_end_current

			//Save to database
			$UserPreferences = UserPreferences::firstOrNew(array('filter_id' => Auth::user()->id.Input::get('filter_id')));
			$UserPreferences->filter_id = Auth::user()->id.Input::get('filter_id');
			$UserPreferences->user_id = Auth::user()->id;
			$UserPreferences->filters = Input::get('filters');
			$UserPreferences->save();

			exec("php-cli ". QueryCacher::query_dashboard($person_type,$gender,$age,$programme,$date_end_current,$date_start_last,$date_end_last,$date_start_current,$start_of_churn_date,$end_of_churn_date,$start_date_mb,$lcs) ." >/dev/null &");
			
			
			exec("php-cli ". QueryCacher::league_table_queries($person_type, $gender, $age, $programme, $end_date, $start_date, $end_date_last, $start_date_last,$lcs) ." >/dev/null &");
			
			
			exec("php-cli ". QueryCacher::group_workout_barometer($person_type, $gender, $age, $programme, $end_date, $start_date_year,$lcs) ." >/dev/null &");
			
			exec("php-cli ". QueryCacher::group_workout_bubble($person_type, $gender, $age, $programme, $end_date, $start_date,$start_date_mb,$lcs) ." >/dev/null &");
			
			// Mail::send('emails.dashboardcreated', array('name'=>Auth::user()->name), function($message)
			// {
			// 	$message->to(Auth::user()->email, Auth::user()->name)->subject('Your dashboard is ready!');
			// });
			//Send notification to Pusher
			$filter_name = Input::get('filter_name');
			$message = "The filter called \"{$filter_name}\" is ready to be displayed in dashboard!";
			Pusherer::trigger('personal.'.Auth::user()->id, 'dashboard_created', [
				'message' => $message,
				]);
		}
		elseif(Input::get('ajax_action') == "get_lcs")
		{
			GeneralFunctions::get_lcs_filter_page_view(Input::get('change_mode'), Input::get('selected_location'), Input::get('id'));
		}
	}
}
