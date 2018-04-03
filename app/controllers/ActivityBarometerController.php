<?php

error_reporting(E_ERROR);



class ActivityBarometerController extends BaseController 

{

	public function getIndexOperations()

	{

		return View::make('pages.activity-barometer.operations');

	}

	public function getIndexBubble()

	{

		$SportTableSports = Sports::get()->sortBy('id');

		$demo = Auth::user()->demo;

		return View::make('pages.activity-barometer.bubble', compact('SportTableSports', 'demo'));

	}

	public function getIndexCatchment()

	{

		return View::make('pages.activity-barometer.catchment');

	}

	public function postGraphCatchment()

	{

		$values = GeneralFunctions::arrange_filters(Input::get('filters'));



		$persontype = $values["person_type"];

		$template = $values["programme"];

		$gender = $values["gender"];

		$original_age = $values["original_age"];

		$age = $values["age"];

		$sites = (array)$values["only_lc"];

		$pools = (array)$values["only_pool_lcs"];



		if(count($sites) == 1)

		{

			$pools[] = $sites[0];

			$colorized = $sites[0];

			

			$zoom = Facilities::select('latitude', 'longitude')

			->where('pmi_site_id', $sites[0])

			->groupBy('pmi_site_id')

			->get();

		}

		else

		{

			$zoom = "none";

			$colorized = "";

		}

		//colorized bugging
		// for ($i=0; $i < count($sites); $i++) 
		// { 
		// 	$colorized .= "#all_facilities[id = '".$sites[$i]."'] {marker-fill: #00B300;}";
		// }



		$sites = implode("','",$sites);

		

		if($age == 0 && $gender == 0)

		{

			$conv = "e.pall ";

		}

		elseif($gender == 0 || in_array('U', $gender) || (in_array('M', $gender) && in_array('F', $gender)))

		{

			if($age == 0)

			{

				$conv = "e.pall ";		

			}

			else

			{

				$conv = "e.p".implode("+e.p",$original_age);

			}

		}

		else

		{

			if($gender[0] == "M")

			{

				if($age == 0)

				{

					$conv = "e.mall ";

				}

				else

				{

					$conv = "e.m".implode("+e.m",$original_age);

				}

			}

			else

			{

				if($age == 0)

				{

					$conv = "e.fall ";

				}

				else

				{

					$conv = "e.f".implode("+e.f",$original_age);

				}

			}

		}



		if($gender ==0)

		{

			$g_query = "";

		}

		elseif(count($gender) == 1)

		{

			$g_query = "AND gender = '$gender[0]' ";

		}

		else

		{

			$gender = implode("','",$gender);

			$g_query = "AND gender in ('$gender') ";

		}



		if($age == 0)

		{

			$a_query = "";

		}

		else

		{

			for ($i=0; $i < count($age); $i++) 

			{ 

				$a = $age[$i];

				if($i == 0)

				{

					$a_query .= "(age BETWEEN $a[0] AND $a[1])";

				}

				else

				{

					$a_query .= " OR (age BETWEEN $a[0] AND $a[1])";

				}

			}

			$a_query = "AND (".$a_query.") ";

		}



		if($template ==0)

		{

			$t_query = "";

		}

		elseif(count($template) == 1)

		{

			$t_query = "AND templatename = $template[0] ";

		}

		else

		{

			$template = implode("','",$template);

			$t_query = "AND templatename in ('$template') ";

		}

		

		$where_query = $g_query.$a_query.$t_query;



		$query_c = "SELECT w.lsoacode,e.lsoa11nm, count(w.headcount)*1000/".

		"($conv) ".

		"as c, e.the_geom_webmercator, array_agg(w.siteid) as siteid_array, ".

		"($conv) as p ".

		"FROM population e RIGHT JOIN group_workout_raw_data w ON e.lsoacode = w.lsoacode ".

		"WHERE w.siteid in ('$sites') ".

		$where_query.

		"GROUP BY w.lsoacode,e.lsoa11nm,p, e.the_geom_webmercator";



		$json_c = "SELECT unnest(CDB_QuantileBins(array_agg(sub.calc), 7)) as c FROM (".

		"SELECT count(w.headcount)*1000/".

		"($conv) as calc, ".

		"($conv) as p ".

		"FROM population e RIGHT JOIN group_workout_raw_data w ON e.lsoacode = w.lsoacode ".

		"WHERE w.siteid in ('$sites') ".

		$where_query.

		"GROUP BY p) as sub ORDER BY c DESC";



		$query_p = "SELECT cartodb_id, the_geom_webmercator, ".

		"laname, lsoacode, lsoa11nm, regname, ".

		"($conv) as p ".

		"FROM population e";



		$json_p = "SELECT unnest(CDB_JenksBins(array_agg(($conv)), 7)) as p FROM population e order by p DESC";

		return View::make('pages.activity-barometer.ajax.graphCatchment',compact('query_c','json_c','query_p','json_p','pools','zoom','colorized'));

	}

	public function postReportOperations()

	{

		$filters = Input::get('filters');

		$values = GeneralFunctions::arrange_filters($filters);

		$end_date = $values['date_end_current'];

		$start_date = date_create($end_date.' last day of -11 month')->format('Y-m-01 00:00:00');

		$person_type = $values['person_type'];

		$gender = $values['gender'];

		$age = $values['age'];

		$programme = $values['programme'];

		$sites = $values["lcs"];

		$demo = Auth::user()->demo;

		//site

		list($results) = QueryCacher::group_workout_barometer($person_type, $gender, $age, $programme, $end_date, $start_date, $sites);

		foreach ($results as $value) 

		{

			$dates[] = $value->date;

			$TemplateNames[] = $value->TemplateName;

			$hc[$value->date][$value->TemplateName] = $value->count;

		}

		$dates = array_unique($dates);

		$TemplateNames = array_unique($TemplateNames);

		asort($dates);

		$dates = array_values($dates);

		$TemplateNames = array_values($TemplateNames);

		//$SportTableSports = Sports::whereIn('id', $sport)->get();

		return View::make('pages.activity-barometer.ajax.reportOperations', compact('dates', 'TemplateNames', 'hc', 'start_date', 'end_date', 'demo', 'filters'));

	}


	public function detailedGraphOperations()
	{
		// var_dump($GLOBALS['template_names']);
		// die();


		$template_names = TemplateNames::lists('name', 'id');
		



		$id = Input::get('id');

		$name = Input::get('name');

		$date = Input::get('date');

		$values = GeneralFunctions::arrange_filters(Session::get('filters'));

		// $end_date = $values['date_end_current'];

		// $start_date = date_create($end_date.' last day of -2 month')->format('Y-m-01 00:00:00');

		$person_type = $values['person_type'];

		$gender = $values['gender'];

		$age = $values['age'];

		$programme = $values['programme'];

		$demo = Auth::user()->demo;

		$sites = $values["lcs"];	

		$sport = Auth::user()->sport;

		$model = "DataRaw".$sport;

		$date = date('Y-m-d', strtotime('01-'.$date));
		$start_date = date('Y-m-01 00:00:00', strtotime($date));

		$end_date = date('Y-m-t 23:59:59', strtotime($date));

		
		// $end_date = $values['date_end_current'];

		// $start_date = date_create($end_date.' last day of -2 month')->format('Y-m-01 00:00:00');


		$start_date_prev_three_months = date('Y-m-01 00:00:00', strtotime($date.' -1 month'));

		$end_date_prev_three_months = date('Y-m-t 23:59:59', strtotime($date.' -1 month'));



		$unique_members = $model::select('MemberID')

		->where('TemplateName', $id)

		->whereBetween('DateOfBooking', [$start_date, $end_date])

		->customFilters($person_type,$gender,$age,$programme,$sites)

		->groupBy('MemberID')

		->groupBy(DB::raw("month(DateOfBooking)"))

		// ->rememberForever()
		// 
		->remember(1440)

		->lists('MemberID');


		// print_r($unique_members);
		// echo '<br>';
		$table_data['unique_member_count'] = count($unique_members);


		$unique_members_past = $model::select('MemberID')

		->where('TemplateName', $id)

		->where('DateOfBooking', '<', $start_date)

		->customFilters($person_type,$gender,$age,$programme,$sites)

		->groupBy('MemberID')

		// ->groupBy(DB::raw("month(DateOfBooking)"))

		->rememberForever()
		// ->remember(1440)

		->orderBy('MemberID')

		->lists('MemberID');


		$unique_members_past_all_group_workout_activities = $model::select('MemberID')

		// ->where('TemplateName', $id)

		->where('DateOfBooking', '<', $start_date)

		->customFilters($person_type,$gender,$age,$programme,$sites)

		->groupBy('MemberID')

		// ->groupBy(DB::raw("month(DateOfBooking)"))

		->rememberForever()
		// 
		// ->remember(1440)

		->orderBy('MemberID')

		->lists('MemberID');



		// $unique_members_past_all_activities = DataRaw::select('MemberID')

		// // ->where('TemplateName', $id)

		// ->where('DateOfBooking', '<', $start_date)

		// // ->customFilters($person_type,$gender,$age,$programme,$sites)

		// ->groupBy('MemberID')

		// ->groupBy(DB::raw("month(DateOfBooking)"))

		// ->rememberForever()
		// 
		// ->remember(1440)

		// ->orderBy('MemberID')

		// ->lists('MemberID');

		// print_r($unique_members_past_all_activities);
		// die();

		// foreach ($unique_members as $memberID) {



		// 	if (!in_array($memberID, $unique_members_past)) 
		// 	{
		$time_pre1 = microtime(true);
		$table_data['1st_time_activity'] = array_intersect($unique_members_past, $unique_members);

		$time_post1 = microtime(true);
		$exec_time1 = number_format($time_post1 - $time_pre1, 0);
        // echo "Total time intersect 1: ".$exec_time1.PHP_EOL;
			// }


			// if (!in_array($memberID, $unique_members_past_all_activities)) 
			// {

		$time_pre2 = microtime(true);
		$table_data['1st_time_group_work_out'] = array_intersect($unique_members_past_all_group_workout_activities, $unique_members);
		$time_post2 = microtime(true);
		$exec_time2 = number_format($time_post2 - $time_pre2, 0);
        // echo "Total time intersect 2: ".$exec_time2.PHP_EOL;
		// 	}

				// $table_data['1st_time_any_activity'] = array_intersect($unique_members_past_all_activities, $unique_members);

		// }

		//all group workouts previous 3 months
		$unique_members_prev_three_months_member_array = $model::select('MemberID')

		->whereBetween('DateOfBooking', [$start_date_prev_three_months, $end_date_prev_three_months])

		->customFilters($person_type,$gender,$age,$programme,$sites)

		->groupBy('MemberID')

		->lists('MemberID');



		// $time_pre = microtime(true);
		// $unique_members_prev_three_months = $model::select('MemberID', 'TemplateName')

		// ->where('TemplateName','<>', $id)

		// ->whereBetween('DateOfBooking', [$start_date_prev_three_months, $end_date_prev_three_months])

		// ->customFilters($person_type,$gender,$age,$programme,$sites)

		// ->groupBy('TemplateName', 'MemberID')

		// ->rememberForever()
		// 
		// ->remember(1440)

		// ->get();

		// $time_post = microtime(true);
  //       $exec_time = number_format($time_post - $time_pre, 0);
  //       echo "Total time query: ".$exec_time.PHP_EOL;



  //       $time_pre_ = microtime(true);
		// foreach ($unique_members_prev_three_months as $value) {
		// 	if (in_array($value->MemberID, $unique_members)) 
		// 	{
		// 		$other_group_workout_activities[$value->TemplateName][] = $value->MemberID;
		// 	}
		// }
		// $time_post_ = microtime(true);
  //       $exec_time_ = number_format($time_post_ - $time_pre_, 0);
  //       echo "Total time foreach: ".$exec_time_.PHP_EOL;


		$unique_members_prev_three_months_ = $model::select('MemberID')

		->where('TemplateName','<>', $id)

		->whereBetween('DateOfBooking', [$start_date_prev_three_months, $end_date_prev_three_months])

		->customFilters($person_type,$gender,$age,$programme,$sites)

		->groupBy('MemberID')

		// ->remember(1440)

		->rememberForever()

		->lists('MemberID');

		$memberids = array_intersect($unique_members, $unique_members_prev_three_months_);

		$time_pre = microtime(true);
		$unique_members_prev_three_months = $model::select(DB::Raw('COUNT(DISTINCT(`MemberID`)) as member_count'), 'TemplateName')

		->where('TemplateName','<>', $id)

		->whereIn('MemberID', $memberids)

		->whereBetween('DateOfBooking', [$start_date_prev_three_months, $end_date_prev_three_months])

		->customFilters($person_type,$gender,$age,$programme,$sites)

		->groupBy('TemplateName')

		// ->remember(1440)

		->rememberForever()

		->lists('member_count', 'TemplateName');

		$time_post = microtime(true);
		$exec_time = number_format($time_post - $time_pre, 0);
        // echo "Total time query: ".$exec_time.PHP_EOL;

		$other_group_workout_activities = $unique_members_prev_three_months;



		

		$table_data['no_activity'] = count($no_activity);


		$demo = Auth::user()->demo;
		
		return View::make('pages.activity-barometer.ajax.tableOperations', 

			compact(
				'date',
				'table_data',
				'demo',
				'name',
				'other_group_workout_activities',
				'template_names'
				)

			);
	}

	public function postGraphOperations()

	{

		$id = Input::get('id');

		$name = Input::get('name');

		$values = GeneralFunctions::arrange_filters(Session::get('filters'));

		$end_date = $values['date_end_current'];

		$start_date = date_create($end_date.' last day of -2 month')->format('Y-m-01 00:00:00');

		$person_type = $values['person_type'];

		$gender = $values['gender'];

		$age = $values['age'];

		$programme = $values['programme'];

		$demo = Auth::user()->demo;

		$sites = $values["lcs"];	

		$sport = Auth::user()->sport;

		$model = "DataRaw".$sport;

		//Sites

		$result_sites = $model::select(DB::raw("(sum(`HeadCount`) / count(DISTINCT SiteID)) as count"), DB::raw("DATE_FORMAT(`DateOfBooking`, '%Y-%m-%d') as date"))

		->where('TemplateName', $id)

		->whereBetween('DateOfBooking', [$start_date, $end_date])

		->customFilters($person_type,$gender,$age,$programme,$sites)

		->groupBy(DB::raw("day(DateOfBooking), month(DateOfBooking), year(DateOfBooking)"))

		->rememberForever()

		->get();

		foreach ($result_sites as $value) 

		{

			$graph_sites[strtotime($value->date)] = $value->count;

		}

		//Sector

		$result_sector = $model::select(DB::raw("(sum(`HeadCount`) / count(DISTINCT SiteID)) as count"), DB::raw("DATE_FORMAT(`DateOfBooking`, '%Y-%m-%d') as date"))

		->where('TemplateName', $id)

		->whereBetween('DateOfBooking', [$start_date, $end_date])

		->customFilters($person_type,$gender,$age,$programme,[0])

		->groupBy(DB::raw("day(DateOfBooking), month(DateOfBooking), year(DateOfBooking)"))

		->rememberForever()

		->get();

		foreach ($result_sector as $value) 

		{

			$dates[] = strtotime($value->date);

			$graph_sector[strtotime($value->date)] = $value->count;

		}

		$dates = array_unique($dates);

		asort($dates);

		$dates = array_values($dates);

		$demo = Auth::user()->demo;

		return View::make('pages.activity-barometer.ajax.graphOperations', 

			compact(

				'name', 

				'graph_sites', 

				'graph_sector', 

				'dates',

				'demo'

				)

			);

	}

	public function postDetailedGraphBubble()

	{

		if(Auth::user()->demo)

		{

			$r = number_format(rand(70,160) / 100,1);

		}

		else

		{

			$r = 1;

		}

		$siteID = Input::get('siteID');

		$siteName = Input::get('siteName');

		$sector_hc = Input::get('sector_hc');

		$sector_rv = Input::get('sector_rv');

		$values = GeneralFunctions::arrange_filters(Session::get('filters'));

		$end_date = $values['date_end_current'];

		$start_date = date_create($end_date.' last day of -11 month')->format('Y-m-01 00:00:00');

		$person_type = $values['person_type'];

		$gender = $values['gender'];

		$age = $values['age'];

		$programme = $values['programme'];

		$sport = Auth::user()->sport;

		$model = "DataRaw".$sport;

		$result_site_hc = $model::select(DB::raw('SUM(HeadCount) as HeadCount'), DB::raw("DATE_FORMAT(`DateOfBooking`, '%Y-%m') as date"), 'PersonType')

		->whereBetween('DateOfBooking', array($start_date, $end_date))

		->customFilters($person_type,$gender,$age,$programme,[$siteID])

		->groupBy(DB::raw("MONTH(`DateOfBooking`), YEAR(`DateOfBooking`), `PersonType`"))

		->orderBy('DateOfBooking')

		->rememberForever()

		->get();

		foreach ($result_site_hc as $value) 

		{

			$period[] = $value->date;

			$sites_hc_[$value->date][$value->PersonType] = $value->HeadCount * $r;

		}

		$area = Facilities::selectRaw("pmi_site_id, sum(area) as area")

		->where('pmi_site_id',$siteID)

		->lists('area');

		for ($i=0; $i < count($period); $i++) 

		{ 

			$sites_hc[$period[$i]] = $sites_hc_[$period[$i]][2] / array_sum($sites_hc_[$period[$i]]) * 100;

			// $sites_rv[$period[$i]] = array_sum($sites_hc_[$period[$i]]) / $area[0];

			$sites_rv[$period[$i]] = array_sum($sites_hc_[$period[$i]]) / 1;

		}

		return View::make('pages.activity-barometer.ajax.detailedGraphBubble', compact('siteID', 'siteName','sites_hc','sites_rv','sector_hc','sector_rv','period'));

	}

	public function postGraphBubble()

	{

		if(Auth::user()->demo)

		{

			$r = number_format(rand(70,160) / 100,1);

		}

		else

		{

			$r = 1;

		}

		$filters = Input::get('filters');
		$values = GeneralFunctions::arrange_filters($filters);
		$end_date = $values['date_end_current'];
		$start_date = date_create($end_date.' last day of -2 month')->format('Y-m-01 00:00:00');
		$start_date_mb = date_create($end_date.' last day of this month')->format('Y-m-01 00:00:00');
		$person_type = $values['person_type'];
		$gender = $values['gender'];
		$age = $values['age'];
		$programme = $values['programme'];
		$sites_ = $values["lcs"];
		$demo = Auth::user()->demo;
		list($result_sites_hc, $freq, $consistency_ab) = QueryCacher::group_workout_bubble($person_type, $gender, $age, $programme, $end_date, $start_date, $start_date_mb, $sites_);
		$area = Facilities::selectRaw("pmi_site_id, sum(area) as area")
		->where('facility_type','Studio')
		->wherein('pmi_site_id',$sites_)
		->groupBy('site_id')
		->lists('area','pmi_site_id');
		foreach ($result_sites_hc as $value) 
		{
			$lcs[] = $value->SiteID; 
			$sites_hc_[$value->SiteID][$value->PersonType] = $value->HeadCount * $r;
			$sector_hc_[$value->PersonType] += $value->HeadCount * $r;
		}
		$lcs = array_unique($lcs);
		$lcs = array_values($lcs);
		$count = count($lcs);
		for ($i=0; $i < $count; $i++) 
		{ 
			$sites_hc[$lcs[$i]] = $sites_hc_[$lcs[$i]][2] / array_sum($sites_hc_[$lcs[$i]]) * 100;
			// $sites_rv[$lcs[$i]] = array_sum($sites_hc_[$lcs[$i]]) / $area[$lcs[$i]];
			$sites_rv[$lcs[$i]] = array_sum($sites_hc_[$lcs[$i]]) / 1;
		}
		$sector_hc = $sector_hc_[2] / array_sum($sector_hc_) * 100;
		// $sector_rv = array_sum($sector_hc_) / array_sum($area);
		$sector_rv = array_sum($sector_hc_) / 1;
		$sites = SiteName::wherein('id',$sites_)->lists('id','name');
		$gll_sites = SiteName::wherein('id',$sites_)->where('operator_id', 19)->lists('id','name');
		//Frequency
		foreach ($freq as $value) 
		{
			if($value->freq <= 3)
			{
				$frequency[$value->period]["<=3"] += $value->count * $r;
				// $consistency_temp["<=3"] += $value->count * $r;
			}
			elseif($value->freq >= 4 && $value->freq <=7)
			{
				$frequency[$value->period]["4-7"] += $value->count * $r;
				// $consistency_temp["4-7"] += $value->count * $r;
			}
			elseif($value->freq >= 8 && $value->freq <=11)
			{
				$frequency[$value->period]["8-11"] += $value->count * $r;
				// $consistency_temp["8-11"] += $value->count * $r;
			}
			else
			{
				$frequency[$value->period]["12+"] += $value->count * $r;
				// $consistency_temp["12+"] += $value->count * $r;
			}
		}
		// $consistency_temp = array_reverse($consistency_temp,true);
		// //Consistency
		// $temp = 0;
		// foreach ($consistency_temp as $key => $value) 
		// {
		// 	$temp = $value + $temp;
		// 	$consistency[$key] = $temp; 
		// 	// echo $key.': '.$value.'<br>';
		// }
		// // die();
		// $consistency = array_reverse($consistency,true);


		foreach ($consistency_ab as $key => $value) 

		{

			$consistency["<=3"] += $value * $r;



			if($key >= 4)

			{

				$consistency["4-7"] += $value * $r;

			}

			if($key >= 8)

			{

				$consistency["8-11"] += $value * $r;

			}

			if($key >= 12)

			{

				$consistency["12+"] += $value * $r;

			}

		}



		if($consistency["<=3"] == 0)

		{

			unset($consistency["<=3"]);

		}

		if($consistency["4-7"] == 0)

		{

			unset($consistency["4-7"]);

		}

		if($consistency["8-11"] == 0)

		{

			unset($consistency["8-11"]);

		}

		if($consistency["12+"] == 0)

		{

			unset($consistency["12+"]);

		}

		return View::make('pages.activity-barometer.ajax.graphBubble', compact('sites', 'gll_sites', 'sites_hc','sites_rv','sector_hc','sector_rv','frequency','consistency','end_date','start_date'));

	}

}