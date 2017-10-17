<?php

class QueryCacher {

	public static function query_dashboard($person_type,$gender,$age,$programme,$date_end_current,$date_start_last,$date_end_last,$date_start_current,$start_of_churn_date,$end_of_churn_date,$start_date_mb,$lcs) 

	{

		$sport = Auth::user()->sport;

		$model = "DataRaw".$sport;

		$unique_lcs = $model::select('SiteID', DB::raw("DATE_FORMAT(`DateOfBooking`, '%m-%y') as date"))

		->whereBetween('DateOfBooking', array($date_start_last, $date_end_current))

		->groupBy('SiteID', DB::raw("month(`DateOfBooking`), year(`DateOfBooking`)"))

		->rememberForever()

		->get();

		foreach ($unique_lcs as $value) 

		{

			$array[$value->date][] = $value->SiteID;

		}

		$first = call_user_func_array('array_intersect',$array);

		$lcs = array_values(array_intersect($first,$lcs));



		/**

		* KPI 1 - Casual Visits

		* KPI 2 - Member Visits

		* KPI 3 - Total Visits

		* KPI 6 - Percentage of visits by members

		* KPI 7 - Unique Member count

		*/

		$tab1 = $model::select('PersonType', DB::raw("DATE_FORMAT(`DateOfBooking`, '%Y-%m') as period"), DB::raw("sum(`HeadCount`) as sum"), DB::raw("Count(DISTINCT MemberID) as count"))

		->where('BookingType','<>','5')

		->whereBetween('DateOfBooking', array($date_start_last, $date_end_current))

		->customFilters($person_type,$gender,$age,$programme,$lcs)

		->groupBy(DB::raw("month(`DateOfBooking`), year(`DateOfBooking`)"), 'PersonType')

		->orderBy('DateOfBooking')

		->rememberForever()

		->get();

		/**

		* KPI 4 - Weekly participation % (paid members)

		*/

		$query = $model::selectRaw("DATE_FORMAT(`DateOfBooking`, '%Y-%m') AS period, COUNT(*) AS freq, `MemberID`")

		->where('BookingType','<>','5')

		->where('PersonType','2')

		->whereBetween('DateOfBooking', array($date_start_last, $date_end_current))

		->customFilters($person_type,$gender,$age,$programme,$lcs)

		->groupBy(DB::raw("MONTH(`DateOfBooking`), YEAR(`DateOfBooking`), `MemberID`"))

		->having('freq', '>=', 4)

		->orderBy('DateOfBooking');

		$tab4 = DB::table(DB::raw("({$query->toSql()} ) as sub"))

		->mergeBindings($query->getQuery())

		->selectRaw("period, count(freq) as sum")

		->groupBy('period')

		->rememberForever()

		->get();



		/**

		* KPI 5 - Weekly participation members lost %

		*/

		$query = $model::where('BookingType','<>','5')

		->where('PersonType','2')

		->whereBetween('DateOfBooking', array($start_of_churn_date, $end_of_churn_date))

		->customFilters($person_type,$gender,$age,$programme,$lcs)

		->groupBy(DB::raw("MONTH(`DateOfBooking`), YEAR(`DateOfBooking`), `MemberID`"))

		->havingRaw("count(*) >= 4");

		$query_churn_1_current = DB::table(DB::raw("({$query->toSql()} ) as sub"))

		->mergeBindings($query->getQuery())

		->groupBy('MemberID')

		->havingRaw("count(*) = 3")

		->rememberForever()

		->lists('MemberID');



		$query_churn_2_current = $model::wherein('MemberID',$query_churn_1_current)

		->whereBetween('DateOfBooking', array($start_date_mb, $date_end_current))

		->havingRaw("count(*) >= 4")

		->groupBy('MemberID')

		->rememberForever()

		->lists('MemberID');



		$churn_current = [count($query_churn_1_current), count($query_churn_2_current)];



		$start_of_churn_date_last = date_create($start_of_churn_date.' -1 year')->format('Y-m-01 00:00:00');

		$end_of_churn_date_last = date_create($end_of_churn_date.' -1 year')->format('Y-m-d 23:59:59');

		$start_date_mb_last = date_create($start_date_mb.' -1 year')->format('Y-m-01 00:00:00');

		$date_end_current_last = date_create($date_end_current.' last day of -12 month')->format('Y-m-d 23:59:59');



		$query = $model::where('BookingType','<>','5')

		->where('PersonType','2')

		->whereBetween('DateOfBooking', array($start_of_churn_date_last, $end_of_churn_date_last))

		->customFilters($person_type,$gender,$age,$programme,$lcs)

		->groupBy(DB::raw("MONTH(`DateOfBooking`), YEAR(`DateOfBooking`), `MemberID`"))

		->havingRaw("count(*) >= 4");

		$query_churn_1_last = DB::table(DB::raw("({$query->toSql()} ) as sub"))

		->mergeBindings($query->getQuery())

		->groupBy('MemberID')

		->havingRaw("count(*) = 3")

		->rememberForever()

		->lists('MemberID');



		$query_churn_2_last = $model::wherein('MemberID',$query_churn_1_last)

		->whereBetween('DateOfBooking', array($start_date_mb_last, $date_end_current_last))

		->havingRaw("count(*) >= 4")

		->groupBy('MemberID')

		->rememberForever()

		->lists('MemberID');



		$churn_last = [count($query_churn_1_last), count($query_churn_2_last)];



 		//all last graph

		$get_all_last = $model::select(DB::raw("DATE_FORMAT(`DateOfBooking`, '%m') as date"), DB::raw("sum(`HeadCount`) as count"))

		->where('BookingType','<>','5')

		->whereBetween('DateOfBooking', array($date_start_last, $date_end_last))

		->customFilters($person_type,$gender,$age,$programme,[0])

		->groupBy(DB::raw("month(`DateOfBooking`), year(`DateOfBooking`), `SiteID`"))

		->orderBy('DateOfBooking')

		->rememberForever()

		->get();

		//all first graph

		$get_all_current = $model::select(DB::raw("DATE_FORMAT(`DateOfBooking`, '%m') as date"), DB::raw("sum(`HeadCount`) as count"))

		->where('BookingType','<>','5')

		->whereBetween('DateOfBooking', array($date_start_current, $date_end_current))

		->customFilters($person_type,$gender,$age,$programme,[0])

		->groupBy(DB::raw("month(`DateOfBooking`), year(`DateOfBooking`), `SiteID`"))

		->orderBy('DateOfBooking')

		->rememberForever()

		->get();

		return [$unique_lcs, $lcs, $tab1, $tab4, $get_all_last, $get_all_current, $churn_current, $churn_last];

	}

	public static function group_workout_barometer($person_type, $gender, $age, $programme, $end_date, $start_date,$lcs)

	{

		$sport = Auth::user()->sport;

		$model = "DataRaw".$sport;

		$results = $model::select('TemplateName', DB::raw("DATE_FORMAT(`DateOfBooking`, '%Y-%m') as date"), DB::raw("sum(`HeadCount`) as count"))

		->where('BookingType','<>','5')

		->whereBetween('DateOfBooking', array($start_date, $end_date))

		->customFilters($person_type,$gender,$age,$programme,$lcs)

		->groupBy('TemplateName', DB::raw("month(`DateOfBooking`), year(`DateOfBooking`)"))

		->rememberForever()

		->get();

		return [$results];

	}

	public static function group_workout_bubble($person_type, $gender, $age, $programme, $end_date, $start_date,$start_date_mb,$lcs)

	{

		$sport = Auth::user()->sport;
		$model = "DataRaw".$sport;
		$result_sites_hc = $model::select(DB::raw('SUM(HeadCount) as HeadCount'), 'SiteID', 'PersonType')
		->whereBetween('DateOfBooking', array($start_date_mb, $end_date))
		->customFilters($person_type,$gender,$age,$programme,$lcs)
		->groupBy('SiteID', 'PersonType')
		->rememberForever()
		->get();
		$query = $model::selectRaw("DATE_FORMAT(`DateOfBooking`, '%Y-%m') AS period, COUNT(*) AS freq")
		->where('BookingType','<>','5')
		->where('PersonType','2')
		->whereBetween('DateOfBooking', array($start_date, $end_date))
		->customFilters($person_type,$gender,$age,$programme,$lcs)
		->groupBy(DB::raw("MONTH(`DateOfBooking`), YEAR(`DateOfBooking`), `MemberID`"))
		->orderBy('DateOfBooking');
		$freq = DB::table(DB::raw("({$query->toSql()} ) as sub"))
		->mergeBindings($query->getQuery())
		->selectRaw("period, freq, count(freq) as count")
		->groupBy('period')
		->groupBy('freq')
		->rememberForever()
		->get();



		if(!Cache::has($cache_name))

		{

			$results_consistency = $model::select(DB::raw("DATE_FORMAT(`DateOfBooking`, '%Y-%m') as date"), DB::raw("count(*) as count"), 'MemberID')

			->where('BookingType','<>','5')

			->where('PersonType','2')

			->where('MemberID', '<>', '')

			->whereBetween('DateOfBooking', array($start_date, $end_date))

			->customFilters(0,$gender,$age,$programme,$lcs)

			->groupBy(DB::raw("month(`DateOfBooking`), year(`DateOfBooking`), `MemberID`"))

			->orderBy('DateOfBooking')

			->rememberForever()

			->get();



			foreach ($results_consistency as $result)

			{

				$dates[] = $result->date;

				$totals[$result->date][] = $result->MemberID;

				$occurrence[$result->MemberID][] = $result->count;

			}



			$dates = array_unique($dates);

			$dates = array_values($dates);



			if(count($totals) >1)

			{

				$intersected_memberids = array_values(call_user_func_array("array_intersect", $totals));

			}

			else

			{

				$intersected_memberids = array_values($totals);

				$intersected_memberids = $intersected_memberids[0];

			}



			$consistency = array();



			for ($i = 0; $i < count($intersected_memberids); $i ++)

			{

				$frequency = min($occurrence[$intersected_memberids[$i]]);

				$consistency[$frequency] += 1;

			}



			ksort($consistency);



			Cache::forever($cache_name, $consistency);

		}

		else

		{

			$consistency = Cache::get($cache_name);

		}
		return [$result_sites_hc, $freq, $consistency];

	}

	public static function league_table_queries($person_type, $gender, $age, $programme, $end_date, $start_date, $end_date_last, $start_date_last,$lcs)

	{

		unset($lcs);

		$lcs[0] = 0;

		

		$sport = Auth::user()->sport;

		$model = "DataRaw".$sport;

		$results = $model::select('SiteID', DB::raw("DATE_FORMAT(`DateOfBooking`, '%b-%y') as date"), DB::raw("sum(`HeadCount`) as count"))

		->where('BookingType','<>','5')

		->whereBetween('DateOfBooking', array($start_date, $end_date))

		->customFilters($person_type,$gender,$age,$programme,$lcs)
		// ->customFilters($person_type,$gender,$age,$programme)

		->groupBy('SiteID', DB::raw("month(`DateOfBooking`), year(`DateOfBooking`)"))

		->orderBy('DateOfBooking')

		->rememberForever()

		->get();

		//last year

		$results_last = $model::select('SiteID', DB::raw("sum(`HeadCount`) as count"))

		->where('BookingType','<>','5')

		->whereBetween('DateOfBooking', array($start_date_last, $end_date_last))

		->customFilters($person_type,$gender,$age,$programme,$lcs)

		// ->customFilters($person_type,$gender,$age,$programme)

		->groupBy('SiteID')

		->rememberForever()

		->get();

		return [$results, $results_last];

	}

}