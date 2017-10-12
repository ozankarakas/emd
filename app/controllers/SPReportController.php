<?php



class SPReportController extends BaseController

{

	public function getIndex()

	{

		return View::make('spreport');

	}



	public function postKPI()

	{

		if (Input::get('ajax_action') == "get_kpi")

		{
            $time_pre1 = microtime(true);
			$totalMemberCountAge = $totalMemberCountGender = $totalMemberCountType = 0;

			$var = GeneralFunctions::arrange_variables(Input::all());

			$model = "DataRawGroupWorkout";

			$all_db_model = "Income";

			$sport_table_sport_model = 'Sports';





			if ($var["month"] < 10) {
				$var["month"] = '0'.$var["month"];
			}

			$var["date_start"] = $var["year"].'-'.$var["month"].'-'.'01 00:00:00';
			$var["date_end"] = $var["year"].'-'.$var["month"].'-'.date('t', strtotime($var["date_start"])).' 23:59:59';

            // var_dump($var["date_start"], '<br>');
            // var_dump($var["date_end"], '<br>');
            // die();


            // $time_pre1 = microtime(true);
			$ageList = $model::select(DB::raw("count(DISTINCT(`MemberID`)) as count"), 'Age')

			->where('PersonType', "2")

			->where('MemberID','<>','')

			->where('BookingType','<>','5')

			->whereBetween('DateOfBooking', array($var["date_start"], $var["date_end"]))

			->groupBy('MemberID')

			->remember(1440*30*12)

			->get();
            // $time_post1 = microtime(true);
            // $exec_time1 = number_format($time_post1 - $time_pre1, 0);
            // echo "Total time: ".$exec_time1.PHP_EOL;
            // die();

            // foreach ($ageList as $key => $value) {
            // 	echo $value->Age.': '.$value->count.'<br>';
            // }
            // die();


            // $time_pre1 = microtime(true);
			$genderList = $model::select(DB::raw("count(DISTINCT(`MemberID`)) as count"), 'Gender')

			->where('PersonType', "2")

			->where('MemberID','<>','')

			->where('BookingType','<>','5')

			->whereBetween('DateOfBooking', array($var["date_start"], $var["date_end"]))

            // ->groupBy('Gender')

			->groupBy('MemberID')

			->orderBy('Gender')

			->remember(1440*30*12)

			->get();

            // $time_post1 = microtime(true);
            // $exec_time1 = number_format($time_post1 - $time_pre1, 0);
            // echo "Total time: ".$exec_time1.PHP_EOL;
            // die();



            // $time_pre1 = microtime(true);
			$memberTypeList = $model::select(DB::raw("count(*) as count"), 'PersonType')

			->whereBetween('DateOfBooking', array($var["date_start"], $var["date_end"]))

			->groupBy('PersonType')

			->orderBy('PersonType')

			->remember(1440*30*12)

			->get();

            // $time_post1 = microtime(true);
            // $exec_time1 = number_format($time_post1 - $time_pre1, 0);
            // echo "Total time: ".$exec_time1.PHP_EOL;
            // die();



            //FREQ IN GIVEN DATE PERIOD

            // $time_pre1 = microtime(true);
			$freq = $model::select(DB::raw("count(*) as count"))

			->where('PersonType', "2")

			->where('MemberID','<>','')

			->where('BookingType','<>','5')

			->whereBetween('DateOfBooking', array($var["date_start"], $var["date_end"]))

			->groupBy('MemberID')

			->remember(1440*30*12)

			->get();

            // $time_post1 = microtime(true);
            // $exec_time1 = number_format($time_post1 - $time_pre1, 0);
            // echo "Total time: ".$exec_time1.PHP_EOL;
            // die();


            // $time_pre1 = microtime(true);
			foreach ($freq as $f) 

			{

				$freq_holder[] = $f->count;

			}

            // $time_post1 = microtime(true);
            // $exec_time1 = number_format($time_post1 - $time_pre1, 0);
            // echo "Total time: ".$exec_time1.PHP_EOL;
            // die();



			$freq_holder = array_count_values($freq_holder);

			ksort($freq_holder);


            // $time_pre1 = microtime(true);
			$members = $model::select('MemberID')

			->where('PersonType', "2")

			->where('MemberID','<>','')

			->where('BookingType','<>','5')

			->whereBetween('DateOfBooking', array($var["date_start"], $var["date_end"]))

			->groupBy('MemberID')

			->remember(1440*30*12)

            ->lists('MemberID');

			// ->get();

            $memberIDs = $members;

            // $time_post1 = microtime(true);
            // $exec_time1 = number_format($time_post1 - $time_pre1, 0);
            // echo "Total time: ".$exec_time1.PHP_EOL;
            // die();

            // $time_pre1 = microtime(true);

            
			// foreach ($members as $key => $value) {

			// 	$memberIDs[] = $value->MemberID;

			// }

            // $time_post1 = microtime(true);
            // $exec_time1 = number_format($time_post1 - $time_pre1, 0);
            // echo "Total time: ".$exec_time1.PHP_EOL;
            // die();


   //          $time_pre1 = microtime(true);
			// $freq_all_db = $all_db_model::select(DB::raw("count(*) as count"),  DB::raw('(SUM(`NetValue`) + SUM(`VatAmount`)) as Cost'), 'PersonID', 'Sport', 'income.HeadCount', 'MemberID')

			// ->join('raw_data', 'raw_data.IncomePaymentID', '=', 'income.PaymentID')

   //          // ->whereIn('MemberID', (array)$memberIDs)

			// ->whereBetween('DateOfBooking', array($var["date_start"], $var["date_end"]))

			// ->whereBetween('ApplicableDate', array($var["date_start"], $var["date_end"]))

			// ->where('SecondarySpend', 0)

			// ->groupBy('PersonID')

			// ->groupBy('Sport')

			// ->remember(1440*30*12)

			// ->get();

   //          $time_post1 = microtime(true);
   //          $exec_time1 = number_format($time_post1 - $time_pre1, 0);
   //          echo "Total time: ".$exec_time1.PHP_EOL;
   //          die();

			// foreach ($freq_all_db as $f) 

			// {
			// 	if(in_array($f->MemberID, $memberIDs))
			// 	{

			// 		foreach ($GLOBALS['sports'] as $all_sports) 

			// 		{

			// 			$member_sport = 'Other';

			// 			if($all_sports->id === $f->Sport)

			// 			{

			// 				$member_sport = $all_sports->name;

			// 				break;

			// 			}

			// 		}



			// 		if($member_sport=='Badminton')

			// 		{

			// 			$spend_details['direct']['bookingCount'] += $f->count;

			// 			$spend_details['direct']['headCount'] += $f->HeadCount;

			// 			$spend_details['direct']['cost'] += $f->Cost;

			// 		}

			// 		else

			// 		{

			// 			$spend_details['indirect']['bookingCount'] += $f->count;

			// 			$spend_details['indirect']['headCount'] += $f->HeadCount;

			// 			$spend_details['indirect']['cost'] += $f->Cost;

			// 		}      
			// 	}          

			// }


            // $time_pre1 = microtime(true);
			$secondary_spent = $all_db_model::select(DB::raw('(SUM(`NetValue`) + SUM(`VatAmount`)) as Cost'), 'PersonID')

			->where('SecondarySpend', 1)

			// ->whereIn('PersonID', (array)$memberIDs)

			->whereBetween('ApplicableDate', array($var["date_start"], $var["date_end"]))

			->remember(1440*30*12)

			->get();
            // $time_post1 = microtime(true);
            // $exec_time1 = number_format($time_post1 - $time_pre1, 0);
            // echo "Total time: ".$exec_time1.PHP_EOL;
            // die();


			// foreach ($secondary_spent as $s) {

			// 	if(in_array($s->PersonID, $memberIDs))
			// 	{
			// 		$spend_details['secondary']['cost'] += $s->Cost;
			// 	}

			// }


   //          // $time_pre1 = microtime(true);
			// $all_bookings = DataRaw::select(DB::raw("count(*) as count"), 'MemberID', 'Sport')

			// // ->whereIn('MemberID', (array)$memberIDs)

			// ->whereBetween('DateOfBooking', array($var["date_start"], $var["date_end"]))

			// ->groupBy('MemberID')

			// ->groupBy('Sport')

			// ->remember(1440*30*12)

			// ->get();

   //          $all_bookings_member_array = $all_bookings->lists('MemberID');

   //          // $all_bookings_intersect = array_intersect($all_bookings_member_array, $memberIDs);


   //          // $time_post1 = microtime(true);
   //          // $exec_time1 = number_format($time_post1 - $time_pre1, 0);
   //          // echo "Total time: ".$exec_time1.PHP_EOL;
   //          // die();

   //          $time_pre1 = microtime(true);
			// foreach ($all_bookings as $f) 

			// {
			// 	if(in_array($f->MemberID, $memberIDs))
			// 	{

			// 	foreach ($GLOBALS['sports'] as $all_sports) 

			// 	{

			// 		$member_sport = 'Other';

			// 		if($all_sports->id === $f->Sport)

			// 		{

			// 			$member_sport = $all_sports->name;

			// 			break;

			// 		}

			// 	}



			// 	$freq_by_member[$f->MemberID][$member_sport] = $f->count;
			// 	}

			// }

   //          $time_post1 = microtime(true);
   //          $exec_time1 = number_format($time_post1 - $time_pre1, 0);
   //          echo "Total time: ".$exec_time1.PHP_EOL;
   //          die();



			// foreach ((array)$freq_by_member as $key => $value) 

			// {

			// 	$member_active_sport = array_keys((array)$value, max((array)$value));



			// 	if($member_active_sport[0]=='Badminton')

			// 	{

			// 		$main_badminton_players++;

			// 		$dumb_badminton_player_second = array();

			// 		foreach ((array)$value as $sport_ => $count) {

			// 			if($sport_ != 'Badminton')

			// 			{

			// 				$dumb_badminton_player_second[$sport_] = $count;

   //                          $badminton_players_second[$sport_] += $count; //booking count

   //                      }                                                                

   //                  }

   //                  $seconadry_active_sport = array_keys((array)$dumb_badminton_player_second, max((array)$dumb_badminton_player_second));

   //                  if(!isset($seconadry_active_sport[0]))

   //                  {

   //                  	$seconadry_active_sport[0] = 'No other sport';

   //                  }

   //                  $badminton_players_second_sport[$seconadry_active_sport[0]]++;    

   //              }

   //              else

   //              {

   //              	$dumb_badminton_player_second = array();

   //              	$dump_non_badminton_sports = array();

   //              	foreach ($value as $sport_ => $count) {

   //              		$dumb_non_badminton_player_second[$sport_] = $count;  

   //              		$dump_non_badminton_sports[] = $sport_;                                      

   //              	}

   //              	if(in_array('Badminton', (array)$dump_non_badminton_sports))

   //              	{

   //              		if(!isset($member_active_sport[0]))

   //              		{

   //              			$member_active_sport[0] = 'No other sport';

   //              		}

   //              		$non_badminton_players_second_sport[$member_active_sport[0]]++; 

   //              	}                      

   //              }

   //          }



            $date_start_array = explode(" ", $var["date_start"]);

            $date_end_array = explode(" ", $var["date_end"]);



            $formatted_start_date = explode("-", $date_start_array[0]);

            $formatted_end_date = explode("-", $date_end_array[0]);





            $date_start_parsed = $formatted_start_date[2]."-".$formatted_start_date[1]."-".$formatted_start_date[0];

            $date_end_parsed = $formatted_end_date[2]."-".$formatted_end_date[1]."-".$formatted_end_date[0];



            $ageGroup00_Count = 0;

            $ageGroup01_Count = 0;

            $ageGroup02_Count = 0;

            $ageGroup03_Count = 0;

            $ageGroup04_Count = 0;

            $ageGroup05_Count = 0;

            $ageGroup06_Count = 0;

            $ageGroup07_Count = 0;







            foreach ($ageList as $age) {



            	if ($age->Age >= 0 & $age->Age <15) {

            		$ageGroup00_Count+= $age->count;

                    //'0-14';

            	}

            	else if ($age->Age >= 15 & $age->Age <25) {

            		$ageGroup01_Count+= $age->count;

                    //'15-24';

            	}                

            	else if ($age->Age >= 25 & $age->Age <35) {

            		$ageGroup02_Count+= $age->count;

                    //'25-34';

            	}

            	else if ($age->Age >= 35 & $age->Age <45) {

            		$ageGroup03_Count+= $age->count;

                    //'35-44';

            	}

            	else if ($age->Age >= 45 & $age->Age <55) {

            		$ageGroup04_Count+= $age->count;

                    //'45-54';

            	}

            	else if ($age->Age >= 55 & $age->Age <65) {

            		$ageGroup05_Count+= $age->count;

                    //'55-64';

            	}

            	else if ($age->Age >= 65 & $age->Age <75) {

            		$ageGroup06_Count+= $age->count;

                    //'65-74';

            	}

            	else if($age->Age >= 75)

            	{

            		$ageGroup07_Count+= $age->count;

                    //'75+';

            	}





            	$totalMemberCountAge += $age->count;

            }



            $ageGroups = array(

            	'0-14',

            	'15-24',

            	'25-34',

            	'35-44',

            	'45-54',

            	'55-64',

            	'65-74',

            	'75+'  

            	);

            $ageGroupCount = array(

            	$ageGroup00_Count,

            	$ageGroup01_Count,

            	$ageGroup02_Count,

            	$ageGroup03_Count,

            	$ageGroup04_Count,

            	$ageGroup05_Count,

            	$ageGroup06_Count,

            	$ageGroup07_Count

            	);


            foreach ($genderList as $gender) {

            	if ($gender->Gender=='M') 
            	{
            		$gender_array['Male']++;
                   // $gender->Gender = 'Male';
            	}
            	elseif ($gender->Gender=='F') 
            	{
            		$gender_array['Female']++;
                    // $gender->Gender = 'Female';
            	}
            	else
            	{
            		$gender_array['Unknown']++;
                    // $gender->Gender = 'Unknown';
            	}

            	$totalMemberCountGender++;
                // $totalMemberCountGender += $gender->count;
            }

            // var_dump($gender_array);
            // die();

            // foreach ($genderList as $gender)

            // {

            //     if($gender->Gender=='M')

            //     {

            //         $gender->Gender = 'Male';

            //     }

            //     elseif($gender->Gender=='F')

            //     {

            //         $gender->Gender = 'Female';

            //     }

            //     else

            //     {

            //         $gender->Gender = 'Unknown';

            //     }



            //     $totalMemberCountGender += $gender->count;

            // }



            foreach ($memberTypeList as $member) {

            	$totalMemberCountType += $member->count;

            }

            ?>

            <div id="widget-grid_ajax">

            	<!-- ************************* AGE BEGIN  ************************* -->

            	<article class="col-xs-12 col-sm-6">

            		<div class="jarviswidget jarviswidget-color-green" data-widget-editbutton="false"

            		data-widget-colorbutton="false" data-widget-deletebutton="false">

            		<header>

            			<span class="widget-icon"> <i class="fa fa-table"></i>

            			</span>



            			<h2>Table 1 - AGE</h2>

            		</header>

            		<div>

            			<div class="widget-body no-padding">

            				<!-- table-striped table-hover -->

            				<table class="table table-striped table-bordered table-bordered table-hover"

            				id="ageTable" style="table-layout: fixed; min-height: 400px;" width="100%">

            				<thead>

            					<th>Age</th>

            					<th style="text-align: right;">Count</th>

            					<th style="text-align: right;">%</th>

            				</thead>

            				<tbody>

            					<?php

            					for ($i=0; $i < count($ageGroups) ; $i++) { 

            						?>

            						<tr>

            							<td><?php echo $ageGroups[$i]; ?></td>

            							<td style="text-align: right;"><?php echo number_format($ageGroupCount[$i]); ?></td>

            							<td style="text-align: right;"><?php echo number_format(($ageGroupCount[$i]/$totalMemberCountAge)*100, 2); ?>%</td>

            						</tr>

            						<?php

            					}

                                // foreach ($ageList as $age) {

            					?>

                                    <!-- <tr>

                                        <td><?php echo $age->Age; ?></td>

                                        <td><?php echo $age->count; ?></td>

                                        <td><?php echo number_format(($age->count/$totalMemberCountAge)*100, 2); ?></td>

                                    </tr> -->

                                    <?php

                                // }

                                    ?>

                                    <tfoot>

                                    	<tr>

                                    		<td><b>Total Members</b></td>

                                    		<td align="right"><b><?php echo number_format($totalMemberCountAge);?></b></td>

                                    		<td align="right"><b><?php echo '100'; ?>%</b></td> 

                                    	</tfoot>

                                    </tbody>

                                </table>

                            </div>

                        </div>

                    </div>

                </article>

                <article class="col-xs-12 col-sm-6">

                	<div class="jarviswidget jarviswidget-color-green" data-widget-editbutton="false"

                	data-widget-colorbutton="false" data-widget-deletebutton="false">

                	<header>

                		<span class="widget-icon"> <i class="fa fa-table"></i>

                		</span>



                		<h2>Graph 1 - AGE</h2>

                	</header>

                	<div>

                		<div class="widget-body no-padding">

                			<div id="ageGraph" style="margin-right: 10px; padding-bottom: 2px;"></div>

                		</div>

                	</div>

                </div>

            </article>

            <!-- ************************* AGE END  ************************* -->



            <!-- ************************* GENDER BEGIN  ************************* -->

            <article class="col-xs-12 col-sm-6">

            	<div class="jarviswidget jarviswidget-color-blue" data-widget-editbutton="false"

            	data-widget-colorbutton="false" data-widget-deletebutton="false">

            	<header>

            		<span class="widget-icon"> <i class="fa fa-table"></i>

            		</span>



            		<h2>Table 2 - GENDER</h2>

            	</header>

            	<div>

            		<div class="widget-body no-padding">

            			<!-- table-striped table-hover -->

            			<table class="table table-striped table-bordered table-bordered table-hover"

            			id="genderTable" style="table-layout: fixed; min-height: 200px;" width="100%">

            			<thead>

            				<th>Gender</th>

            				<th style="text-align: right;">Count</th>

            				<th style="text-align: right;">%</th>

            			</thead>

            			<tbody>

            				<?php

            				foreach ($gender_array as $gender => $count) {

            					?>

            					<tr>

            						<td><?php echo $gender; ?></td>

            						<td style="text-align: right;"><?php echo number_format($count); ?></td>

            						<td style="text-align: right;"><?php echo number_format(($count/$totalMemberCountGender)*100, 2); ?>%</td>

            					</tr>

            					<?php

            				}

            				?>

            				<tfoot>

            					<tr>

            						<td><b>Total Members</b></td>

            						<td align="right"><b><?php echo number_format($totalMemberCountGender);?></b></td>

            						<td align="right"><b><?php echo '100'; ?>%</b></td> 

            					</tfoot>

            				</tbody>

            			</table>



            		</div>

            	</div>

            </div>

        </article>

        <article class="col-xs-12 col-sm-6">

        	<div class="jarviswidget jarviswidget-color-yellow" data-widget-editbutton="false"

        	data-widget-colorbutton="false" data-widget-deletebutton="false">

        	<header>

        		<span class="widget-icon"> <i class="fa fa-table"></i>

        		</span>



        		<h2 style='color: black;'>Table 3 - PLAYER STATUS</h2>

        	</header>

        	<div>

        		<div class="widget-body no-padding">

        			<!-- table-striped table-hover -->

        			<table class="table table-striped table-bordered table-bordered table-hover"

        			id="memberTypeTable" style="table-layout: fixed; min-height: 200px;" width="100%">

        			<thead>

        				<th>Player Status</th>

        				<th style="text-align: right;">Booking Count</th>

        				<th style="text-align: right;">%</th>

        			</thead>

        			<tbody>

        				<?php

        				foreach ($memberTypeList as $member) {

        					if($member->PersonType == 1 )

        					{

        						$person_type = 'Casual';

        					}

        					else if($member->PersonType == 2)

        					{

        						$person_type = 'Member';

        					}

        					?>

        					<tr>

        						<td><?php echo $person_type; ?></td>

        						<td style="text-align: right;"><?php echo number_format($member->count); ?></td>

        						<td style="text-align: right;"><?php echo number_format(($member->count/$totalMemberCountType)*100, 2); ?>%</td>

        					</tr>

        					<?php

        				}

        				?>

        				<tfoot>

        					<tr>

        						<td><b>Total Bookings</b></td>

        						<td align="right"><b><?php echo number_format($totalMemberCountType);?></b></td>

        						<td align="right"><b><?php echo '100'; ?>%</b></td>





        					</tfoot>

        				</tbody>

        			</table>



        		</div>

        	</div>

        </div>

    </article>



    <!-- ************************* GENDER END  ************************* -->



    <!-- ************************* MEMBER TYPE BEGIN  ************************* -->

    <article class="col-xs-12 col-sm-6">

    	<div class="jarviswidget jarviswidget-color-blue" data-widget-editbutton="false"

    	data-widget-colorbutton="false" data-widget-deletebutton="false">

    	<header>

    		<span class="widget-icon"> <i class="fa fa-table"></i>

    		</span>



    		<h2>Graph 2 - GENDER</h2>

    	</header>

    	<div>

    		<div class="widget-body no-padding">

    			<div id="genderGraph" style="margin-right: 10px; padding-bottom: 2px;"></div>

    		</div>

    	</div>

    </div>

</article>

<article class="col-xs-12 col-sm-6">

	<div class="jarviswidget jarviswidget-color-yellow" data-widget-editbutton="false"

	data-widget-colorbutton="false" data-widget-deletebutton="false">

	<header>

		<span class="widget-icon"> <i class="fa fa-table"></i>

		</span>



		<h2 style='color: black;'>Graph 3 - PLAYER STATUS</h2>

	</header>

	<div>

		<div class="widget-body no-padding">

			<div id="memberTypeGraph" style="margin-right: 10px; padding-bottom: 2px;"></div>

		</div>

	</div>

</div>

</article>

<!-- ************************* MEMBER TYPE END  ************************* -->





<!-- ************************* FREQUENCY BEGIN  ************************* -->

<article class="col-xs-12 col-sm-6">

	<div class="jarviswidget jarviswidget-color-red" data-widget-editbutton="false"

	data-widget-colorbutton="false" data-widget-deletebutton="false">

	<header>

		<span class="widget-icon"> <i class="fa fa-table"></i>

		</span>



		<h2>Table 4 - FREQUENCY</h2>

	</header>

	<div>

		<div class="widget-body no-padding">

			<!-- table-striped table-hover -->

			<table class="table table-striped table-bordered table-bordered table-hover"

			id="frequencyTable" style="table-layout: fixed; min-height: 100px;" width="100%">

			<thead>

				<th>Frequency</th>

				<th style="text-align: right;">Count</th>

				<th style="text-align: right;">%</th>

			</thead>

			<tbody>

				<?php

				$total_member = array_sum($freq_holder);

				foreach ($freq_holder as $key => $fh) {

					?>

					<tr>

						<td><?php echo $key; ?></td>

						<td style="text-align: right;"><?php echo number_format($fh,0); ?></td>

						<td style="text-align: right;"><?php echo number_format($fh/$total_member*100,2); ?>%</td>

					</tr>

					<?php

				}

				?>

				<tfoot>

					<tr>

						<td><b>Total Members</b></td>

						<td align="right"><b><?php echo number_format($total_member,0);?></b></td>

						<td align="right"><b><?php echo number_format($total_member/$total_member*100,2); ?>%</b></td> 

					</tfoot>

				</tbody>

			</table>



		</div>

	</div>

</div>

</article>

<!-- ************************* FREQUENCY END  ************************* -->



<!-- ************************* FREQUENCY GRAPH END  ************************* -->

<article class="col-xs-12 col-sm-6">

	<div class="jarviswidget jarviswidget-color-red" data-widget-editbutton="false"

	data-widget-colorbutton="false" data-widget-deletebutton="false">

	<header>

		<span class="widget-icon"> <i class="fa fa-table"></i>

		</span>



		<h2>Graph 4 - FREQUENCY</h2>

	</header>

	<div>

		<div class="widget-body no-padding">

			<div id="frequencyGraph" style="margin-right: 10px; padding-bottom: 2px;"></div>

		</div>

	</div>

</div>

</article>

<!-- ************************* FREQUENCY GRAPH END  ************************* -->



<!-- ************************* MEMBERSHIP BEGIN  ************************* -->

<!-- <article class="col-xs-12 col-sm-6">

	<div class="jarviswidget jarviswidget-color-green" data-widget-editbutton="false"

	data-widget-colorbutton="false" data-widget-deletebutton="false">

	<header>

		<span class="widget-icon"> <i class="fa fa-table"></i>

		</span>



		<h2>Table 5 - BADMINTON AS PRIMARY SPORT</h2>

	</header>

	<div>

		<div class="widget-body no-padding">

			<table class="table table-striped table-bordered table-bordered table-hover"

			id="membershipStatusTable" style="table-layout: fixed; min-height: 100px;" width="100%">

			<thead>

				<th>Is badminton your primary sport?</th>

				<th style="text-align: right;">Count</th>

				<th style="text-align: right;">%</th>

			</thead>

			<tbody>

				<tr>

					<td>Yes</td>

					<td align="right"><?php echo number_format($main_badminton_players);?></td>

					<td align="right"><?php echo number_format($main_badminton_players/$totalMemberCountGender*100,2);?> %</td>

				</tr>

				<tr>

					<td>No</td>

					<td align="right"><?php echo number_format($totalMemberCountGender-$main_badminton_players);?></td>

					<td align="right"><?php echo number_format(100-$main_badminton_players/$totalMemberCountGender*100,2);?> %</td>

				</tr>

				<tfoot>

					<tr>

						<td><b>Total Members</b></td>

						<td align="right"><b><?php echo number_format($totalMemberCountGender);?></b></td>

						<td align="right"><b><?php echo '100'; ?>%</b></td>





					</tfoot>

				</tbody>

			</table>



		</div>

	</div>

</div>

</article> -->



<!-- ************************* MEMBERSHIP END  ************************* -->



<!-- *************************MEMBERSHIP STATUS BEGIN  ************************* -->

<!-- <article class="col-xs-12 col-sm-6">

	<div class="jarviswidget jarviswidget-color-green" data-widget-editbutton="false"

	data-widget-colorbutton="false" data-widget-deletebutton="false">

	<header>

		<span class="widget-icon"> <i class="fa fa-table"></i>

		</span>



		<h2>Graph 5 - BADMINTON AS PRIMARY SPORT</h2>

	</header>

	<div>

		<div class="widget-body no-padding">

			<div id="badmintonPlayerGraph" style="margin-right: 10px; padding-bottom: 2px;"></div>

		</div>

	</div>

</div>

</article> -->

<!-- *************************MEMBERSHIP STATUS END  ************************* -->



<!-- ************************* PARTICIPATION BEGIN  ************************* -->

<!-- <article class="col-xs-12 col-sm-6">

	<div class="jarviswidget jarviswidget-color-blue" data-widget-editbutton="false"

	data-widget-colorbutton="false" data-widget-deletebutton="false">

	<header>

		<span class="widget-icon"> <i class="fa fa-table"></i>

		</span>



		<h2>Table 6 - SECONDARY SPORT OF MAIN BADMINTON PLAYERS</h2>

	</header>

	<div>

		<div class="widget-body no-padding">


			<table class="table table-striped table-bordered table-bordered table-hover"

			id="participationTable" style="table-layout: fixed; min-height: 100px;" width="100%">

			<thead>

				<th>Secondary Sport</th>

				<th style="text-align: right;">Count</th>

				<th style="text-align: right;">%</th>

			</thead>

			<tbody>

				<?php

				foreach ($badminton_players_second_sport as $key => $value) {

					?>

					<tr>

						<td><?php echo $key?></td>

						<td style="text-align: right;"><?php echo number_format($value);?></td>

						<td style="text-align: right;"><?php echo number_format($value/array_sum($badminton_players_second_sport)*100, 2);?> %</td>

					</tr>

					<?php

				}

				?>

				<tfoot>

					<tr>

						<td><b>Total Members</b></td>

						<td align="right"><b><?php echo number_format(array_sum($badminton_players_second_sport));?></b></td>

						<td align="right"><b><?php echo '100'; ?>%</b></td> 

					</tfoot>

				</tbody>

			</table>



		</div>

	</div>

</div>

</article> -->

<!-- ************************* PARTICIPATION END  ************************* -->



<!-- ************************* OTHER SPORTS BEGIN  ************************* -->

<!-- <article class="col-xs-12 col-sm-6">

	<div class="jarviswidget jarviswidget-color-yellow" data-widget-editbutton="false"

	data-widget-colorbutton="false" data-widget-deletebutton="false">

	<header>

		<span class="widget-icon"> <i class="fa fa-table"></i>

		</span>



		<h2 style='color: black;'>Table 7 - PRIMARY SPORT OF NON-PRIMARY BADMINTON PLAYERS</h2>

	</header>

	<div>

		<div class="widget-body no-padding">

			<table class="table table-striped table-bordered table-bordered table-hover"

			id="otherSportsTable" style="table-layout: fixed; min-height: 100px;" width="100%">

			<thead>

				<th>Primary Sport</th>

				<th style="text-align: right;">Count</th>

				<th style="text-align: right;">%</th>

			</thead>

			<tbody>

				<?php

				foreach ($non_badminton_players_second_sport as $key => $value) {

					?>

					<tr>

						<td><?php echo $key;?></td>

						<td align="right"><?php echo number_format($value);?></td>

						<td align="right"><?php echo number_format($value/array_sum($non_badminton_players_second_sport)*100, 2);?> %</td>

					</tr>

					<?php

				}

				?>

				<tfoot>

					<tr>

						<td><b>Total Members</b></td>

						<td align="right"><b><?php echo number_format(array_sum($non_badminton_players_second_sport));?></b></td>

						<td align="right"><b><?php echo '100'; ?>%</b></td> 

					</tfoot>

				</tbody>

			</table>



		</div>

	</div>

</div>

</article> -->

<!-- ************************* OTHER SPORTS END  ************************* -->



<!-- ************************* MEMBER TYPE BEGIN  ************************* -->

<!-- <article class="col-xs-12 col-sm-6">

	<div class="jarviswidget jarviswidget-color-blue" data-widget-editbutton="false"

	data-widget-colorbutton="false" data-widget-deletebutton="false">

	<header>

		<span class="widget-icon"> <i class="fa fa-table"></i>

		</span>



		<h2>Graph 6 - SECONDARY SPORT OF MAIN BADMINTON PLAYERS</h2>

	</header>

	<div>

		<div class="widget-body no-padding">

			<div id="participationGraph" style="margin-right: 10px; padding-bottom: 2px;"></div>

		</div>

	</div>

</div>

</article> -->

<!-- <article class="col-xs-12 col-sm-6">

	<div class="jarviswidget jarviswidget-color-yellow" data-widget-editbutton="false"

	data-widget-colorbutton="false" data-widget-deletebutton="false">

	<header>

		<span class="widget-icon"> <i class="fa fa-table"></i>

		</span>



		<h2 style='color: black;'>Graph 7 - PRIMARY SPORT OF NON-PRIMARY BADMINTON PLAYERS</h2>

	</header>

	<div>

		<div class="widget-body no-padding">

			<div id="otherSportsGraph" style="margin-right: 10px; padding-bottom: 2px;"></div>

		</div>

	</div>

</div>

</article> -->

<!-- ************************* MEMBER TYPE END  ************************* -->



<!-- ************************* AVERAGE SPEND ON SITE BEGIN  ************************* -->

<!-- <article class="col-xs-12 col-sm-6">

	<div class="jarviswidget jarviswidget-color-red" data-widget-editbutton="false"

	data-widget-colorbutton="false" data-widget-deletebutton="false">

	<header>

		<span class="widget-icon"> <i class="fa fa-table"></i>

		</span>



		<h2>Table 8 - VALUE OF BADMINTON PLAYERS</h2>

	</header>

	<div>

		<div class="widget-body no-padding">

			<table class="table table-striped table-bordered table-bordered table-hover"

			id="averageTable" style="table-layout: fixed; min-height: 100px;" width="100%">

			<thead>

				<th>Categories</th>

				<th style="text-align: right;">Member Count</th>

				<th style="text-align: right;">Booking Count</th>

				<th style="text-align: right;">Headcount</th>

				<th style="text-align: right;">Total Income</th>

				<th style="text-align: right;">Average Income</th>

			</thead>

			<tbody>

				<tr>

					<td>Direct Spend</td>

					<td style="text-align: right;"><?php echo number_format($totalMemberCountGender, 0);?></td>

					<td style="text-align: right;"><?php echo number_format($spend_details['direct']['bookingCount'], 0);?></td>

					<td style="text-align: right;"><?php echo number_format($spend_details['direct']['headCount'], 0);?></td>

					<td style="text-align: right;">&pound; <?php echo number_format($spend_details['direct']['cost'], 2);?></td>

					<td style="text-align: right;"> &pound;<?php $direct_spend_average = number_format($spend_details['direct']['cost']/$totalMemberCountGender, 2); echo $direct_spend_average;?></td>

				</tr>

				<tr>

					<td>Indirect Spend</td>

					<td style="text-align: right;"><?php echo number_format($totalMemberCountGender, 0);?></td>

					<td style="text-align: right;"><?php echo number_format($spend_details['indirect']['bookingCount'], 0);?></td>

					<td style="text-align: right;"><?php echo number_format($spend_details['indirect']['headCount'], 0);?></td>

					<td style="text-align: right;">&pound; <?php echo number_format($spend_details['indirect']['cost'], 2);?></td>

					<td style="text-align: right;">&pound; <?php $indirect_spend_average = number_format($spend_details['indirect']['cost']/$totalMemberCountGender, 2); echo $indirect_spend_average;?></td>

				</tr>

				<tr>

					<td>Secondary Spend</td>

					<td style="text-align: right;"><?php echo number_format($totalMemberCountGender, 0);?></td>

					<td style="text-align: right;">-</td>

					<td style="text-align: right;">-</td>

					<td style="text-align: right;">&pound; <?php echo number_format($spend_details['secondary']['cost'], 2);?></td>

					<td style="text-align: right;">&pound; <?php $secondary_spend_average = number_format($spend_details['secondary']['cost']/$totalMemberCountGender, 2); echo $secondary_spend_average;?></td>

				</tr>

				<tfoot>

					<tr>

						<td><b>Total</b></td>

						<td align="right"><b><?php echo number_format($totalMemberCountGender);?></b></td>

						<td style="text-align: right;"><?php echo number_format($spend_details['indirect']['bookingCount']+$spend_details['direct']['bookingCount'], 0);?></td>

						<td style="text-align: right;"><?php echo number_format($spend_details['indirect']['headCount']+$spend_details['direct']['headCount'], 0);?></td>

						<td style="text-align: right;">&pound; <?php echo number_format($spend_details['indirect']['cost']+$spend_details['direct']['cost']+$spend_details['secondary']['cost'], 2);?></td>

						<td style="text-align: right;">&pound; <?php $total_spend_average = number_format(($spend_details['indirect']['bookingCount']+$spend_details['direct']['bookingCount']+$spend_details['secondary']['cost'])/$totalMemberCountGender, 2); echo $total_spend_average;?></td>

					</tfoot>

				</tbody>

			</table>



		</div>

	</div>

</div>

</article> -->

<!-- <article class="col-xs-12 col-sm-6">

	<div class="jarviswidget jarviswidget-color-red" data-widget-editbutton="false"

	data-widget-colorbutton="false" data-widget-deletebutton="false">

	<header>

		<span class="widget-icon"> <i class="fa fa-table"></i>

		</span>



		<h2>Graph 8 - VALUE OF BADMINTON PLAYERS</h2>

	</header>

	<div>

		<div class="widget-body no-padding">

			<div id="averageGraph" style="margin-right: 10px; padding-bottom: 2px;"></div>

		</div>

	</div>

</div>

</article> -->



<!-- ************************* AVERAGE SPEND ON SITE END  ************************* -->









<?php

echo View::make('templates.datatable', array("table_id" => "ageTable", "sort" => "true", "column" => "0"));

echo View::make('templates.datatable', array("table_id" => "genderTable", "sort" => "true", "direction"=> "asc", "column" => "0"));

echo View::make('templates.datatable', array("table_id" => "memberTypeTable", "sort" => "true", "column" => "0"));



echo View::make('templates.datatable', array("table_id" => "frequencyTable", "sort" => "true", "direction"=> "asc", "column" => "0"));

echo View::make('templates.datatable', array("table_id" => "membershipStatusTable", "sort" => "true", "column" => "0"));

echo View::make('templates.datatable', array("table_id" => "participationTable", "sort" => "true", "column" => "1"));

echo View::make('templates.datatable', array("table_id" => "otherSportsTable", "sort" => "true", "column" => "1"));

echo View::make('templates.datatable', array("table_id" => "averageTable", "sort" => "false", "column" => "0"));



echo View::make('templates.widget');

?>

<script>

	$(function () {

		$('#ageGraph').highcharts({

			chart: {

				plotBackgroundColor: null,

				plotBorderWidth: null,

				plotShadow: false

			},

			title: {

				text: 'Graph 1 - AGE'

			},

			subtitle: {

				text: 'Start Date: <?php echo $date_start_parsed;?> / End Date: <?php echo $date_end_parsed;?>'

			},

			tooltip: {

				pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'

			},

			plotOptions: {

				pie: {

					allowPointSelect: true,

					cursor: 'pointer',

					dataLabels: {

						enabled: true,

						format: '<b>{point.name}</b>: {point.percentage:.1f}%',

						style: {

							color: (Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black'

						}

					}

				}

			},

			series: [{

				type: 'pie',

				name: 'percentage',

				data: [





				<?php



				for ($i=0; $i < count($ageGroups) ; $i++) { 

					?>

					['<?php echo $ageGroups[$i];?>',   <?php echo number_format(($ageGroupCount[$i]/$totalMemberCountAge)*100, 2);?>],

                                    // <tr>

                                    //     <td><?php echo $ageGroups[$i]; ?></td>

                                    //     <td><?php echo $ageGroupCount[$i]; ?></td>

                                    //     <td><?php echo number_format(($ageGroupCount[$i]/$totalMemberCountAge)*100, 2); ?></td>

                                    // </tr>

                                    <?php

                                }



                                ?>

                                ]

                            }]

                        });



		$('#genderGraph').highcharts({

			chart: {

				plotBackgroundColor: null,

				plotBorderWidth: null,

				plotShadow: false

			},

			title: {

				text: 'Graph 2 - GENDER'

			},

			subtitle: {

				text: 'Start Date: <?php echo $date_start_parsed;?> / End Date: <?php echo $date_end_parsed;?>'

			},

			tooltip: {

				pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'

			},

			plotOptions: {

				pie: {

					allowPointSelect: true,

					cursor: 'pointer',

					dataLabels: {

						enabled: true,

						format: '<b>{point.name}</b>: {point.percentage:.1f}%',

						style: {

							color: (Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black'

						}

					}

				}

			},

			series: [{

				type: 'pie',

				name: 'percentage',

				data: [

				<?php

				foreach($gender_array as $gender => $count)

				{

					?>

					['<?php echo $gender;?>',   <?php echo number_format(($count/$totalMemberCountGender)*100, 2);?>],

					<?php

				}

				?>

				]

			}]

		});



		$('#memberTypeGraph').highcharts({

			chart: {

				plotBackgroundColor: null,

				plotBorderWidth: null,

				plotShadow: false

			},

			title: {

				text: 'Graph 3 - PLAYER STATUS'

			},

			subtitle: {

				text: 'Start Date: <?php echo $date_start_parsed;?> / End Date: <?php echo $date_end_parsed;?>'

			},

			tooltip: {

				pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'

			},

			plotOptions: {

				pie: {

					allowPointSelect: true,

					cursor: 'pointer',

					dataLabels: {

						enabled: true,

						format: '<b>{point.name}</b>: {point.percentage:.1f}%',

						style: {

							color: (Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black'

						}

					}

				}

			},

			series: [{

				type: 'pie',

				name: 'percentage',

				data: [

				<?php

				foreach($memberTypeList as $member)

				{

					if($member->PersonType == 1 )

					{

						$person_type = 'Casual';

					}

					else if($member->PersonType == 2)

					{

						$person_type = 'Member';

					}

					?>

					['<?php echo $person_type;?>',   <?php echo number_format(($member->count/$totalMemberCountType)*100, 2);?>],

					<?php

				}

				?>

				]

			}]

		});









		$('#frequencyGraph').highcharts({

			chart: {

				plotBackgroundColor: null,

				plotBorderWidth: null,

				plotShadow: false

			},

			title: {

				text: 'Graph 4 - FREQUENCY'

			},

			subtitle: {

				text: 'Start Date: <?php echo $date_start_parsed;?> / End Date: <?php echo $date_end_parsed;?>'

			},

			tooltip: {

				pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'

			},

			plotOptions: {

				pie: {

					allowPointSelect: true,

					cursor: 'pointer',

					dataLabels: {

						enabled: true,

						format: '<b>{point.name}</b>: {point.percentage:.1f}%',

						style: {

							color: (Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black'

						}

					}

				}

			},

			series: [{

				type: 'pie',

				name: 'percentage',

				data: [

				<?php

				foreach($freq_holder as $key => $fh)

				{

					if(number_format($key)>10)

					{

						$ten_plus += $fh;

					}

					else

					{

						?>

						['<?php echo $key;?>',   <?php echo number_format(($fh/$total_member)*100, 2);?>],

						<?php

					}

				}

				?>

				['10+',   <?php echo number_format(($ten_plus/$total_member)*100, 2);?>]

				<?php

				?>

				]

			}]

		});











		$('#badmintonPlayerGraph').highcharts({

			chart: {

				plotBackgroundColor: null,

				plotBorderWidth: null,

				plotShadow: false

			},

			title: {

				text: 'Graph 5 - BADMINTON AS PRIMARY SPORT'

			},

			subtitle: {

				text: 'Start Date: <?php echo $date_start_parsed;?> / End Date: <?php echo $date_end_parsed;?>'

			},

			tooltip: {

				pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'

			},

			plotOptions: {

				pie: {

					allowPointSelect: true,

					cursor: 'pointer',

					dataLabels: {

						enabled: true,

						format: '<b>{point.name}</b>: {point.percentage:.1f}%',

						style: {

							color: (Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black'

						}

					}

				}

			},

			series: [{

				type: 'pie',

				name: 'percentage',

				data: [

				['Main Sport',   <?php echo number_format($main_badminton_players/$totalMemberCountGender*100,2);?>],

				['Not Main Sport', <?php echo number_format(100-$main_badminton_players/$totalMemberCountGender*100,2);?>]

				]

			}]

		});





		$('#participationGraph').highcharts({

			chart: {

				type: 'column'

			},

			title: {

				text: 'Graph 6 - SECONDARY SPORT OF MAIN BADMINTON PLAYERS'

			},

			subtitle: {

				text: 'Start Date: <?php echo $date_start_parsed;?> / End Date: <?php echo $date_end_parsed;?>'

			},

			xAxis: {

				categories: [

				'Sport',

				],

				crosshair: true

			},

			yAxis: {

				min: 0,

				title: {

					text: 'Count'

				}

			},

			tooltip: {

				headerFormat: '<span style="font-size:10px">{point.key}</span><table>',

				pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +

				'<td style="padding:0"><b>{point.y:.0f}</b></td></tr>',

				footerFormat: '</table>',

				shared: true,

				useHTML: true

			},

			plotOptions: {

				column: {

					pointPadding: 0.2,

					borderWidth: 0

				}

			},

			series: [

			<?php 

			$top_ten = 0;

			arsort($badminton_players_second_sport);

			foreach ($badminton_players_second_sport as $key => $value) {

				?>

				{

					name: '<?php echo $key?>',

					data: [<?php echo $value;?>],

					visible: <?php if($top_ten<10 && $key!= 'No other sport'){echo 'true'; $top_ten++;}else{echo 'false';}?>

				},

				<?php

			}

			?>

			]

		});





		$('#otherSportsGraph').highcharts({

			chart: {

				type: 'column'

			},

			title: {

				text: 'Graph 7 - PRIMARY SPORT OF NON-PRIMARY BADMINTON PLAYERS'

			},

			subtitle: {

				text: 'Start Date: <?php echo $date_start_parsed;?> / End Date: <?php echo $date_end_parsed;?>'

			},

			xAxis: {

				categories: [

				'Sport',

				],

				crosshair: true

			},

			yAxis: {

				min: 0,

				title: {

					text: 'Count'

				}

			},

			tooltip: {

				headerFormat: '<span style="font-size:10px">{point.key}</span><table>',

				pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +

				'<td style="padding:0"><b>{point.y:.0f}</b></td></tr>',

				footerFormat: '</table>',

				shared: true,

				useHTML: true

			},

			plotOptions: {

				column: {

					pointPadding: 0.2,

					borderWidth: 0

				}

			},

			series: [

			<?php 

			$top_ten = 0;

			arsort($non_badminton_players_second_sport);

			foreach ($non_badminton_players_second_sport as $key => $value) {

				?>

				{

					name: '<?php echo $key?>',

					data: [<?php echo $value;?>],

					visible: <?php if($top_ten<10){echo 'true';}else{echo 'false';}?>

				},

				<?php

				$top_ten++;

			}

			?>

			]

		});



		// $('#averageGraph').highcharts({

		// 	chart: {

		// 		plotBackgroundColor: null,

		// 		plotBorderWidth: null,

		// 		plotShadow: false

		// 	},

		// 	title: {

		// 		text: 'Graph 8 - VALUE OF BADMINTON PLAYERS'

		// 	},

		// 	subtitle: {

		// 		text: 'Start Date: <?php echo $date_start_parsed;?> / End Date: <?php echo $date_end_parsed;?>'

		// 	},

		// 	tooltip: {

		// 		pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'

		// 	},

		// 	plotOptions: {

		// 		pie: {

		// 			allowPointSelect: true,

		// 			cursor: 'pointer',

		// 			dataLabels: {

		// 				enabled: true,

		// 				format: '<b>{point.name}</b>: {point.percentage:.1f}%',

		// 				style: {

		// 					color: (Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black'

		// 				}

		// 			}

		// 		}

		// 	},

		// 	series: [{

		// 		type: 'pie',

		// 		name: 'percentage',

		// 		data: [

		// 		['Direct Spend', <?php echo number_format($direct_spend_average/$total_spend_average*100,2);?>],

		// 		['Indirect Spend', <?php echo number_format($indirect_spend_average/$total_spend_average*100,2);?>],

		// 		['Secondary Spend', <?php echo number_format($secondary_spend_average/$total_spend_average*100,2);?>]

		// 		]

		// 	}]

		// });













	});



$('#memberTypeGraph').css('height', $('#memberTypeTable').parent().height());

$('#ageGraph').css('height', $('#ageTable').parent().height());

$('#frequencyGraph').css('height', $('#frequencyTable').parent().height());

$('#badmintonPlayerGraph').css('height', $('#membershipStatusTable').parent().height());

$('#participationGraph').css('height', $('#participationTable').parent().height());

$('#otherSportsGraph').css('height', $('#otherSportsTable').parent().height());

// $('#averageGraph').css('height', $('#averageTable').parent().height());







$(window).on('resize', function(){

	$('#memberTypeGraph').css('height', $('#memberTypeTable').parent().height());

	$('#genderGraph').css('height', $('#genderTable').parent().height());

	$('#ageGraph').css('height', $('#ageTable').parent().height());

	$('#frequencyGraph').css('height', $('#frequencyTable').parent().height());

	$('#badmintonPlayerGraph').css('height', $('#membershipStatusTable').parent().height());

	$('#participationGraph').css('height', $('#participationTable').parent().height());

	$('#otherSportsGraph').css('height', $('#otherSportsTable').parent().height());

	// $('#averageGraph').css('height', $('#averageTable').parent().height());

});

</script>

<?php

$time_post1 = microtime(true);
            $exec_time1 = number_format($time_post1 - $time_pre1, 0);
            echo "Total time: ".$exec_time1.PHP_EOL;

}

}

}

