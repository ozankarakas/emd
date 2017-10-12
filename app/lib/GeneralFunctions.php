<?php
error_reporting(E_ERROR);

$GLOBALS['age'] = [

"All",

"00-04 Yrs",

"05-13 Yrs",

"14-25 Yrs",

"26-35 Yrs",

"36-55 Yrs",

"56+ Yrs"

];



$GLOBALS['person_types'] = PersonTypes::rememberForever()->get()->sortBy('id');

$GLOBALS['booking_types'] = BookingTypes::rememberForever()->get()->sortBy('id');

$GLOBALS['payment_types'] = PaymentTypes::rememberForever()->whereIn('id',[0,1,2,7,10])->get()->sortBy('id');

$GLOBALS['activities'] = [];

$GLOBALS['sports'] = [];

$GLOBALS['demo'] = Auth::user()->demo;



//PROGRAMME

$GLOBALS['template_names'] = TemplateNames::remember(24*60)

->join('sports_table', 'sports_table_template_name.name', '=', 'sports_table.TemplateName')

->where('sports_table.TemplateName','!=','')

->whereIn('Sport', ['Group Workout', 'Dance'])
->orWhere('sports_table_template_name.id', '76')

->groupBy('sports_table_template_name.id')

->get(['sports_table_template_name.id','sports_table_template_name.name'])->sortBy('name');



if(!Session::has('person_count') && !Session::has('age_count') && !Session::has('gender_count'))

{

    for ($i=0; $i <= 11; $i++) 

    {

        $age = "age_emd_".$i;

        $age_count[] = Totals::select()->where('name',$age)->first()->count;    

    }

    array_splice($age_count, 0, 0, array_sum($age_count));

    $person_casual = Totals::select()->where('name','person_emd_casual')->first()->count;    

    $person_member = Totals::select()->where('name','person_emd_member')->first()->count;   

    $person_total = $person_casual + $person_member;

    $person_count = [$person_total, $person_casual, $person_member];



    $gender_male = Totals::select()->where('name','gender_emd_male')->first()->count;    

    $gender_female = Totals::select()->where('name','gender_emd_female')->first()->count;  

    $gender_unknown = Totals::select()->where('name','gender_emd_unknown')->first()->count;


    $gender_total = $gender_male + $gender_female + $gender_unknown;

    $gender_count = [$gender_total, $gender_unknown, $gender_male, $gender_female];



    Session::put('person_count', $person_count);

    Session::put('age_count', $age_count);

    Session::put('gender_count', $gender_count);

}





class GeneralFunctions

{

    public static function find_lcs($role, $region)

    {

        if($role == "ADM")

        {

            $lcs = Facilities::select('leisure_centre_id')

            ->where('country', 1)

            ->where('facility_type', 'Studio')

            ->groupBy('leisure_centre_id')

            ->lists('leisure_centre_id');

        }

        elseif($role == "OPT")

        {

            //REGION IS OPT ID HERE

            $lcs = Facilities::select('leisure_centre_id', 'site_name')

            ->where('country', 1)

            ->where('facility_type', 'Studio')

            ->where('pmi_op_id', $region)  

            ->groupBy('leisure_centre_id')

            ->lists('leisure_centre_id');

        }



        return $lcs;

    }

    public static function getTemplateNames()

    {

        return $GLOBALS['template_names'];

    }



    public static function filter_details()

    {

        if(Session::has('filters'))

        {

            $person_type = ["All","Casual","Member"];

            $age = ["All","< 14 yrs","15-19","20-24","25-29","30-34","35-39","40-44","45-49","50-54","55-59","60-64","65+"];

            $gender = ["All","Unknown","Male","Female"];

            $no_of_sites = ["All","1","2 to 3","4 to 5","6 to 10"];



            $filter_details = Session::get('filters');

            $values = json_decode($filter_details,true);



            ?>

            <ul class="dropdown-menu" id="filter_details">


                <li>

                    <a><b>Consumer type: </b>

                        <?php 

                        for ($i=0; $i < count($person_type); $i++) 

                        { 

                            if(in_array($i, (array)$values["person_type"]))

                            {

                                $out .= $person_type[$i].", ";         

                            }

                        }

                        echo rtrim($out,", ");

                        $out = "";

                        ?>

                    </a>

                </li>

                <li>

                    <a>

                        <b>Age: </b>

                        <?php 

                        for ($i=0; $i < count($age); $i++) 

                        { 

                            if(in_array($i, (array)$values["age"]))

                            {

                                $out .= $age[$i].", ";         

                            }

                        }

                        echo rtrim($out,", ");

                        $out = "";

                        ?>

                    </a>

                </li>

                <li>

                    <a>

                        <b>Gender: </b>

                        <?php 

                        for ($i=0; $i < count($gender); $i++) 

                        { 

                            if(in_array($i, (array)$values["gender"]))

                            {

                                $out .= $gender[$i].", ";         

                            }

                        }

                        echo rtrim($out,", ");

                        $out = "";

                        ?>

                    </a>

                </li>

                <li>

                    <a>

                        <b>Number of sites owned: </b>

                        <?php 

                        for ($i=0; $i < count($no_of_sites); $i++) 

                        { 

                            if(in_array($i, (array)$values["no_of_sites"]))

                            {

                                $out .= $no_of_sites[$i].", ";         

                            }

                        }

                        echo rtrim($out,", ");

                        $out = "";

                        ?>

                    </a>

                </li>

                <li>

                    <a>

                        <b>Location: </b>

                        <?php

                        if(Auth::user()->role == "ASM")

                        {

                            $names = HubLcs::wherein('pmi_site_id', (array)$values["location"])->lists('name');

                            echo implode('<br>', $names);

                        }

                        elseif(Auth::user()->role == "NO")

                        {

                            if($values["selected_location"] === "0")

                            {

                                $names = HubOperators::wherein('pmi_op_id', (array)$values["location"])->lists('name');

                                echo implode('<br>', $names);

                            }

                            else

                            {

                                $names = HubLcs::wherein('pmi_site_id', (array)$values["selected_location"])->lists('name');

                                echo implode('<br>', $names);

                            }

                        }

                        else

                        {

                            echo DB::table('f_location')->where('id', $values["location"])->first()->name;

                        }

                        ?>

                    </a>

                </li>

                <li>

                    <a><b>End Date: </b> <?php echo $values["date"]; ?></a>

                </li>

                <li>

                    <a>

                        <b>Programme: </b>

                        <?php  

                        if($values["programme"] == 0)

                        {

                            echo "All";   

                        }

                        elseif(is_array($values["programme"]))

                        {

                            echo "More than 1 selected";

                        }

                        else

                        {

                            echo TemplateNames::find($values["programme"])->name;

                        }

                        ?>

                    </a>

                </li>





            </ul>

            <?php

        }

        else

        {

            //

        }

    }



    public static function arrange_filters($values)

    {

        $values = json_decode($values, true);

        //DATE

        $date = new DateTime($values["date"]);

        $date_end_current = $date->modify('last day of last month')->format("Y-m-d 23:59:59");

        $date_start_last = date('Y-01-01 00:00:00', strtotime("$date_end_current -1 year"));

        $date = new DateTime($values["date"]);

        $date_start_current = date('Y-01-01 00:00:00', strtotime("$date_end_current"));

        $date_end_last = date('Y-12-31 23:59:59', strtotime("$date_end_current -1 year"));

        $date = new DateTime($values["date"]);

        $c_year = date('Y',strtotime("$date_end_current"));

        $l_year = date('Y',strtotime("$date_end_current -1 year"));

        $date = new DateTime($values["date"]);

        $c_month = $date->modify('first day of last month')->format("Y-m");

        $date = new DateTime($values["date"]);

        $c_month_int_ajax = $date->modify('first day of last month')->format("m");

        $date = new DateTime($values["date"]);

        $c_month_ajax = $date->modify('first day of last month')->format("M");

        $date = new DateTime($values["date"]);

        $temp = $date->modify('first day of last year')->format("Y-m-01");

        $l_month = date("Y-m",strtotime("$temp -1 day"));

        $date = new DateTime($values["date"]);

        $c_month_text = $date->modify('first day of last month')->format("M-Y");

        $date = new DateTime($values["date"]);

        $temp = $date->modify('first day of last year')->format("Y-m-01");

        $l_month_text = date("M-Y",strtotime("$temp -1 day"));

        //LOCATION

        $location = $values["location"];

        if(isset($values["selected_location"]))
        {
            $selected_location = $values["selected_location"];
        }


        if(isset($selected_location) && $location == 1)

        {

            $lc = Facilities::where('csp_name', $selected_location)

            ->where('pmi_site_id', '<>', '')

            ->groupBy('pmi_site_id')

            ->lists('pmi_site_id');

        }

        elseif(isset($selected_location) && $location == 2)

        {

            $lc = SiteName::select("id")

            ->where('region_id', $selected_location)

            ->lists('id');

        }

        elseif(isset($selected_location) && $location == 4)

        {

            $lc = SiteName::select("id")

            ->where('operator_id', $selected_location)

            ->lists('id');

        }

        elseif(isset($selected_location) && $location == 5)

        {

            $lc = $selected_location;

        }

        else

        {

            $lc = SiteName::lists('id');

        }



        //NO OF SITES

        if(!isset($values["no_of_sites"]))

        {

            $no_of_sites = 0;

        }

        else

        {

            $no_of_sites = $values["no_of_sites"];

        }


        $second = Facilities::selectRaw("pmi_site_id, COUNT(*) AS no_of_sites")
        ->where('facility_type','Studio')
        ->where('pmi_site_id','<>','')
        ->groupBy('site_id')
        ->get();

        foreach ($second as $value) 

        {

            if($no_of_sites == 0)

            {

                $third[] = $value->pmi_site_id;

            }

            else

            {

                if(in_array(1, $no_of_sites) == 1 && $value->no_of_sites == 1)

                {

                    $third[] = $value->pmi_site_id;

                }

                if(in_array(2, $no_of_sites) && ($value->no_of_sites == 2 || $value->no_of_sites == 3))

                {

                    $third[] = $value->pmi_site_id;

                }

                if(in_array(3, $no_of_sites) && ($value->no_of_sites == 4 || $value->no_of_sites == 5))

                {

                    $third[] = $value->pmi_site_id;

                }

                if(in_array(4, $no_of_sites) && ($value->no_of_sites >= 6 && $value->no_of_sites <= 10))

                {

                    $third[] = $value->pmi_site_id;

                }

            } 

        }



        $fourth = array_intersect((array)$lc, (array)$third);

        //fixing keys

        $fourth = array_values($fourth);

        //PERSON TYPE

        if(!isset($values["person_type"]))

        {

            $person_type = 0;

        }

        else

        {

            $person_type = $values["person_type"];   

        }

        //GENDER

        if(!isset($values["gender"]))

        {

            $gender = [0];

        }

        else

        {

            $gender = (array)$values["gender"];

        }

        for ($i=0; $i < count($gender) ; $i++) 

        { 

            if($gender[$i] == "0"){$genders = 0; break;}

            if($gender[$i] == "1"){$genders[] = "U";}

            if($gender[$i] == "2"){$genders[] = "M";}

            if($gender[$i] == "3"){$genders[] = "F";}

        }

        //PROGRAMME

        $programme = $values["programme"];

        //AGE

        //$age = ["All","< 14 yrs","15-19","20-24","25-29","30-34","35-39","40-44","45-49","50-54","55-59","60-64","65+"];

        if(!isset($values["age"]))

        {

            $age = [0];

        }

        else

        {

            $age = (array)$values["age"];    

        }

        for ($i=0; $i < count($age) ; $i++) 

        { 

            if($age[$i] == "0"){$ages = 0; break;}

            if($age[$i] == "1"){$ages[] = [0, 14];}

            if($age[$i] == "2"){$ages[] = [15, 19];}

            if($age[$i] == "3"){$ages[] = [20, 24];}

            if($age[$i] == "4"){$ages[] = [25, 29];}

            if($age[$i] == "5"){$ages[] = [30, 34];}

            if($age[$i] == "6"){$ages[] = [35, 39];}

            if($age[$i] == "7"){$ages[] = [40, 44];}

            if($age[$i] == "8"){$ages[] = [45, 49];}

            if($age[$i] == "9"){$ages[] = [50, 54];}

            if($age[$i] == "10"){$ages[] = [55, 59];}

            if($age[$i] == "11"){$ages[] = [60, 64];}

            if($age[$i] == "12"){$ages[] = [65, 1000];}

        }

        for ($i=0; $i < count($age) ; $i++) 

        { 

            if($age[$i] == "0"){$original_age = 0; break;}

            if($age[$i] == "1"){$original_age[] = "0004"; $original_age[] = "0509"; $original_age[] = "1014";}

            if($age[$i] == "2"){$original_age[] = "1519";}

            if($age[$i] == "3"){$original_age[] = "2024";}

            if($age[$i] == "4"){$original_age[] = "2529";}

            if($age[$i] == "5"){$original_age[] = "3034";}

            if($age[$i] == "6"){$original_age[] = "3539";}

            if($age[$i] == "7"){$original_age[] = "4044";}

            if($age[$i] == "8"){$original_age[] = "4549";}

            if($age[$i] == "9"){$original_age[] = "5054";}

            if($age[$i] == "10"){$original_age[] = "5559";}

            if($age[$i] == "11"){$original_age[] = "6064";}

            if($age[$i] == "12"){$original_age[] = "6569"; $original_age[] = "7074"; $original_age[] = "7579"; $original_age[] = "8084"; $original_age[] = "8589"; $original_age[] = "90";}

        }

        return array(

            'original_age' => $original_age,

            'lcs' => $fourth,

            'only_lc' => $lc,

            'person_type' => $person_type,

            'programme' => $programme, 

            'age' => $ages,

            'gender' => $genders,

            'date_start_last' => $date_start_last,

            'date_end_last' => $date_end_last,

            'date_start_current' => $date_start_current,

            'date_end_current' => $date_end_current,

            'l_year' => $l_year,

            'c_year' => $c_year,

            'l_month' => $l_month,

            'c_month' => $c_month,

            'c_month_ajax' => $c_month_ajax,

            'c_month_int_ajax' => $c_month_int_ajax,

            'l_month_text' => $l_month_text,

            'c_month_text' => $c_month_text);

    }

    public static function rank($values)

    {

        $ordered_values = $values;

        rsort($ordered_values);

        foreach ($values as $key => $value) 

        {

            $SiteIDS = $key;

            foreach ($ordered_values as $ordered_key => $ordered_value) 

            {

                if ($value === $ordered_value) 

                {

                    $key = $ordered_key;

                    break;

                }

            }

            $ranks[$SiteIDS] = ((int) $key + 1);

        }

        return $ranks;

    }

    public static function get_start_date()

    {

        ?>

        <div class="form-group col-sm-12">

            <label class="col-sm-3 control-label">Start Date <i class="fa fa-clock-o"></i></label>

            <div class="col-sm-9">

                <div class="input-group">

                    <input type="text" id="start_date" placeholder="Select a date" class="form-control datepicker" data-dateformat="dd-mm-yy" value="<?php echo date("d-m-Y", mktime(0, 0, 0, date("m")-1, 1, date("Y")));?>" >

                    <span class="input-group-addon"><i class="fa fa-calendar"></i></span>

                </div>

            </div>

        </div>

        <?php

    }

    public static function get_end_date()

    {

        ?>

        <div class="form-group col-sm-12">

            <label class="col-sm-3 control-label">End Date <i class="fa fa-clock-o"></i></label>

            <div class="col-sm-9">

                <div class="input-group">

                    <input type="text" id="end_date" placeholder="Select a date" class="form-control datepicker" data-dateformat="dd-mm-yy" value="<?php echo date("d-m-Y"); ?>" >

                    <span class="input-group-addon"><i class="fa fa-calendar"></i></span>

                </div>

            </div>

        </div>

        <?php

    }

    public static function get_button()

    {

        ?>

        <div class="form-group col-sm-12" style="margin-top: 1px;">

            <div class="col-sm-3"> </div>

            <div class="col-sm-9">

                <button type="submit" class="btn btn-primary btn-sm btn-block confirm_update">

                    <i class="fa fa-save fa-lg"></i> Submit

                </button>

            </div>

        </div>

        <?php

    }

    public static function get_ages()

    {

        $age_ = $GLOBALS['age'];

        ?>

        <div class="form-group col-sm-12">

            <label class="col-sm-3 control-label">Age <i class="fa fa-sort-amount-asc"></i></label>

            <div class="col-sm-9">

                <select id="age" class="select2_single" style="width: 100%">

                    <?php

                    for ($i = 0; $i < count($age_); $i ++)

                    {

                        echo "<option value=$i>$age_[$i]</option>";

                    }

                    ?>

                </select>

            </div>

        </div>

        <?php

    }

    public static function get_ages_for_preferences($l)

    {

        $age_ = $GLOBALS['age'];

        ?>

        <select id="age" name="age" class="form-control input-lg">

            <?php

            for ($i = 0; $i < count($age_); $i ++)

            {

                ?>

                <option value="<?php echo $i; ?>"<?php echo $l == $i ? ' selected="selected"' : '';?>><?php echo $age_[$i]; ?></option>

                <?php

            }

            ?>

        </select>

        <?php

    }

    public static function get_gender()

    {

        ?>

        <div class="form-group col-sm-12">

            <label class="col-sm-3 control-label">Gender <i class="fa fa-female"></i></label>

            <div class="col-sm-9">

                <select id="gender" class="select2_single" style="width: 100%">

                    <option selected="selected" value="All">All</option>

                    <option value="U">Unknown</option>

                    <option value="M">Male</option>

                    <option value="F">Female</option>

                </select>

            </div>

        </div>

        <?php

    }

    public static function get_gender_for_preferences($l)

    {

        ?>

        <select id="gender" name="gender" class="form-control input-lg">

            <option selected="selected" value="All">All</option>

            <option <?php echo $l == "U" ? ' selected="selected"' : '';?> value="U">Unknown</option>

            <option <?php echo $l == "M" ? ' selected="selected"' : '';?> value="M">Male</option>

            <option <?php echo $l == "F" ? ' selected="selected"' : '';?> value="F">Female</option>

        </select>

        <?php

    }

    public static function get_person()

    {

        ?>

        <div class="form-group col-sm-12">

            <label class="col-sm-3 control-label">Person <i class="fa fa-user"></i></label>

            <div class="col-sm-9">

                <select id="person" class="select2_single" style="width: 100%">

                    <?php

                    foreach ($GLOBALS['person_types'] as $person_type) 

                    {

                        echo "<option value=$person_type->id>$person_type->name</option>";

                    }

                    ?>

                </select>

            </div>

        </div>

        <?php

    }

    public static function get_person_for_preferences($l)

    {

        ?>

        <select id="person" name="person" class="form-control input-lg">

            <?php

            foreach ($GLOBALS['person_types'] as $person_type) 

            {

                ?>

                <option value="<?php echo $person_type->id; ?>"<?php echo $l == $person_type->id ? ' selected="selected"' : '';?>><?php echo $person_type->name; ?></option>

                <?php

            }

            ?>

        </select>

        <?php

    }

    public static function get_booking()

    {

        ?>

        <div class="form-group col-sm-12">

            <label class="col-sm-3 control-label">Booking <i class="fa fa-book"></i></label>

            <div class="col-sm-9">

                <select id="booking" class="select2_single" style="width: 100%">

                    <?php

                    foreach ($GLOBALS['booking_types'] as $booking_type) 

                    {

                        echo "<option value=$booking_type->id>$booking_type->name</option>";

                    }

                    ?>

                </select>

            </div>

        </div>

        <?php

    }

    public static function get_payment()

    {

        ?>

        <div class="form-group col-sm-12">

            <label class="col-sm-3 control-label">Payment <i class="fa fa-book"></i></label>

            <div class="col-sm-9">

                <select id="payment" class="select2_single" style="width: 100%">

                    <?php

                    foreach ($GLOBALS['payment_types'] as $payment_type) 

                    {

                        echo "<option value=$payment_type->id>$payment_type->name</option>";

                    }

                    ?>

                </select>

            </div>

        </div>

        <?php

    }

    public static function get_location()

    {

        $location_ = DB::table('f_location')->get();

        ?>

        <div class="form-group col-sm-12">

            <label class="col-sm-3 control-label">Location <i class="fa fa-location-arrow"></i></label>

            <div class="col-sm-9">

                <select id="location" class="select2_single" style="width: 100%">

                    <?php

                    foreach ($location_ as $location)

                    {

                        echo "<option value=$location->id>$location->name</option>";

                    }

                    ?>

                </select>

            </div>

        </div>

        <?php

    }

    public static function get_location_filter_page_view($l,$id)

    {



        $location_ = DB::table('f_location')->get();

        ?>

        <label><b>Location</b></label>

        <select id="location<?php echo $id; ?>" name="location" class="select2_single" style="width: 100%">

            <?php

            foreach ($location_ as $location)

            {

                if($location->id != 3)
                {

                    if(Auth::user()->role == "OPT")

                    {

                        if($location->id == 4 || $location->id == 5)

                        {

                            ?>

                            <option value="<?php echo $location->id; ?>"<?php echo $l == $location->id ? ' selected="selected"' : '';?>><?php echo $location->name ?></option>

                            <?php

                        }

                    }

                    else

                    {

                        ?>

                        <option value="<?php echo $location->id; ?>"<?php echo $l == $location->id ? ' selected="selected"' : '';?>><?php echo $location->name ?></option>

                        <?php

                    }
                }
            }

            ?>

        </select>

        <?php

    }

    public static function get_period()

    {

        $period_ = [

        "Monthly",

        "Weekly"

        ];

        ?>

        <div class="form-group col-sm-12">

            <label class="col-sm-3 control-label">Period <i class="fa fa-calendar-o"></i></label>

            <div class="col-sm-9">

                <select id="period" class="select2_single" style="width: 100%">

                    <?php

                    for ($i = 0; $i < count($period_); $i ++)

                    {

                        echo "<option value=$i>$period_[$i]</option>";

                    }

                    ?>

                </select>

            </div>

        </div>

        <?php

    }

    public static function get_count()

    {

        $count_ = [

        "1",

        "2",

        "3",

        "4",

        "5",

        "6",

        "7",

        "8",

        "9",

        "10",

        "11",

        "12"

        ];

        ?>

        <div class="form-group col-sm-12">

            <label class="col-sm-3 control-label">Count <i class="fa fa-sort-numeric-asc"></i></label>

            <div class="col-sm-9">

                <select id="count" class="select2_single" style="width: 100%">

                    <?php

                    for ($i = 0; $i < count($count_); $i ++)

                    {

                        $k = ($i+1);

                        echo "<option value=$k>$count_[$i]</option>";

                    }

                    ?>

                </select>

            </div>

        </div>

        <?php

    }

    public static function get_frequency()

    {

        $freq_ = [

        "1",

        "2",

        "3",

        "4",

        "5",

        "6",

        "7",

        "8",

        "9",

        "10",

        "11",

        "12"

        ];

        ?>

        <div class="form-group col-sm-12">

            <label class="col-sm-3 control-label">Frequency <i class="fa fa-sort-numeric-asc"></i></label>

            <div class="col-sm-9">

                <select id="frequency" class="select2_single" style="width: 100%">

                    <?php

                    for ($i = 0; $i < count($freq_); $i ++)

                    {

                        $k = ($i+1);

                        echo "<option value=$k>$freq_[$i]</option>";

                    }

                    ?>

                </select>

            </div>

        </div>

        <?php

    }

    public static function get_lcs_filter_page_view($mode, $l, $id)

    {

        if($mode == 1)

        {

            $csps = Facilities::where('facility_type','Studio')->where('csp_code','<>','')->where('pmi_site_id','<>','')->orderby('csp_name')->groupby('csp_code')->lists('csp_name', 'csp_code');

            ?>

            <label></label>

            <select id="selected_location<?php echo $id; ?>" name="selected_location" class="select2_single_ajax<?php echo $id; ?>" style="width: 100%">

                <option value=""></option>

                <?php

                foreach ($csps as $location => $value)

                {

                    ?>

                    <option value="<?php echo $location; ?>"<?php echo $l == $location ? ' selected="selected"' : '';?>><?php echo $value ?></option>

                    <?php

                }

                ?>

            </select>

            <script type="text/javascript">

                $(".select2_single_ajax<?php echo $id; ?>").select2({

                    placeholder: "Please select an option",

                    allowClear: true,

                });

            </script>

            <?php

        }

        elseif($mode == 2)

        {

            $regions = Facilities::where('facility_type','Studio')->where('region_code','<>','')->where('pmi_site_id','<>','')->orderby('region_name')->groupby('region_code')->lists('region_name', 'region_code');

            ?>

            <label></label>

            <select id="selected_location<?php echo $id; ?>" name="selected_location" class="select2_single_ajax<?php echo $id; ?>" style="width: 100%">

                <option value=""></option>

                <?php

                foreach ($regions as $location => $value)

                {

                    ?>

                    <option value="<?php echo $location; ?>"<?php echo $l == $location ? ' selected="selected"' : '';?>><?php echo $value ?></option>

                    <?php

                }

                ?>

            </select>

            <script type="text/javascript">

                $(".select2_single_ajax<?php echo $id; ?>").select2({

                    placeholder: "Please select an option",

                    allowClear: true,

                });

            </script>

            <?php

        }

        elseif($mode == 4)

        {

            $operators = Facilities::where('facility_type','Studio')->where('op_id','<>','')->where('pmi_site_id','<>','')->orderby('op_name')->groupby('pmi_op_id')->lists('op_name', 'pmi_op_id');

            ?>

            <label></label>

            <select id="selected_location<?php echo $id; ?>" name="selected_location" class="select2_single_ajax<?php echo $id; ?>" style="width: 100%">

                <option value=""></option>

                <?php

                $counter = 1;

                foreach ($operators as $location => $value)

                {

                    if($GLOBALS['demo'])

                    {

                        $op_name = "Demo Operator ".$counter;

                    }

                    else

                    {

                        $op_name = $value;

                    }

                    if(Auth::user()->role == "OPT")

                    {

                        if(Auth::user()->account == $location)

                        {

                            ?>

                            <option value="<?php echo $location; ?>"<?php echo $l == $location ? ' selected="selected"' : '';?>><?php echo $op_name; ?></option>

                            <?php

                        }

                    }

                    else

                    { 

                        ?>

                        <option value="<?php echo $location; ?>"<?php echo $l == $location ? ' selected="selected"' : '';?>><?php echo $op_name; ?></option>

                        <?php

                    }

                    $counter++;

                }

                ?>

            </select>

            <script type="text/javascript">

                $(".select2_single_ajax<?php echo $id; ?>").select2({

                    placeholder: "Please select an option",

                    allowClear: true,

                });

            </script>

            <?php

        }

        elseif($mode == 5)

        {

            if(Auth::user()->role == "OPT")

            {

                $lcs_ = Facilities::where('facility_type','Studio')->where('pmi_op_id', Auth::user()->account)->groupBy('site_id')->orderBy('site_name')->get();

            }   

            else

            {

                $lcs_ = Facilities::where('facility_type','Studio')->where('pmi_site_id','<>','')->groupBy('site_id')->orderBy('site_name')->get();

            }

            ?>

            <label></label>

            <select id="selected_location<?php echo $id; ?>" name="selected_location" class="select2_single_ajax<?php echo $id; ?>" style="width: 100%">

                <option value=""></option>

                <?php

                $counter = 1;

                foreach ($lcs_ as $location)

                {

                    if($GLOBALS['demo'])

                    {

                        $lc_name = "Demo Leisure Centre ".$counter;

                    }

                    else

                    {

                        $lc_name = $location->site_name;

                    }

                    ?>

                    <option value="<?php echo $location->pmi_site_id; ?>"<?php echo $l == $location->pmi_site_id ? ' selected="selected"' : '';?>><?php echo $lc_name; ?></option>

                    <?php

                    $counter++;

                }

                ?>

            </select>

            <script type="text/javascript">

                $(".select2_single_ajax<?php echo $id; ?>").select2({

                    placeholder: "Please select an option",

                    allowClear: true,

                });

            </script>

            <?php

        }

    }

    public static function get_programme()

    {

        ?>

        <div class="form-group col-sm-12">

            <label class="col-sm-3 control-label">Programme <i class="fa fa-globe"></i></label>

            <div class="col-sm-9">

                <select id="programme" class="select2_single" style="width: 100%">

                    <?php

                    echo "<option value=0>All</option>";

                    foreach ($GLOBALS['template_names'] as $template_name) 

                    {

                        echo "<option value=$template_name->id>$template_name->name</option>";

                    }

                    ?>

                </select>

            </div>

        </div>

        <?php

    }

    public static function get_programme_filter()

    {

        ?>

        <label class="col-sm-12 control-label"><b>Programme</b></label>

        <div class="col-sm-12">

            <select name="programme" id="programme" class="select2_single" style="width: 100%">

                <?php

                echo "<option value=0>All</option>";

                foreach ($GLOBALS['template_names'] as $template_name) 

                {

                    echo "<option value=$template_name->id>$template_name->name</option>";

                }

                ?>

            </select>

        </div>

        <?php

    }

    public static function get_programme_filter_multi()

    {

        ?>

        <label class="col-sm-12 control-label"><b>Programme</b></label>

        <div class="col-sm-12">

            <select multiple name="programme" id="programme" class="select2_single" style="width: 100%">

                <?php

                echo "<option value=0>All</option>";

                foreach ($GLOBALS['template_names'] as $template_name) 

                {

                    echo "<option value=$template_name->id>$template_name->name</option>";

                }

                ?>

            </select>

        </div>

        <?php

    }

    public static function get_programme_filter_multi_page_view($l, $id)

    {

        if(!isset($l))

        {

            $l = 0;

        }

        ?>

        <label><b>Programme</b></label>

        <div class="form-group">

            <select multiple name="programme" id="programme<?php echo $id; ?>" class="select2_single" style="width: 100%">

                <option value="0"<?php echo in_array(0, (array)$l) ? ' selected="selected"' : '';?>>All</option>

                <?php

                foreach ($GLOBALS['template_names'] as $template_name) 

                {

                    ?>

                    <option value="<?php echo $template_name->id; ?>"<?php echo in_array($template_name->id, (array)$l) ? ' selected="selected"' : '';?>><?php echo $template_name->name; ?></option>

                    <?php

                }

                ?>

            </select>

        </div>

        <?php

    }


    public static function get_report_date()
    {
        ?>
            <div class="form-group col-sm-12">
                <label class="col-sm-3 control-label">Report Date <i class="fa fa-calendar"></i></label>
                <div class="col-sm-5">
                        <select id="month" class="select2_single" style="width: 100%">
                            <option <?php if(date('m', strtotime('-1 month')) == 1) {echo 'selected'; } ?> value="1">January</option>
                            <option <?php if(date('m', strtotime('-1 month')) == 2) {echo 'selected'; } ?> value="2">February</option>
                            <option <?php if(date('m', strtotime('-1 month')) == 3) {echo 'selected'; } ?> value="3">March</option>
                            <option <?php if(date('m', strtotime('-1 month')) == 4) {echo 'selected'; } ?> value="4">April</option>
                            <option <?php if(date('m', strtotime('-1 month')) == 5) {echo 'selected'; } ?> value="5">May</option>
                            <option <?php if(date('m', strtotime('-1 month')) == 6) {echo 'selected'; } ?> value="6">June</option>
                            <option <?php if(date('m', strtotime('-1 month')) == 7) {echo 'selected'; } ?> value="7">July</option>
                            <option <?php if(date('m', strtotime('-1 month')) == 8) {echo 'selected'; } ?> value="8">August</option>
                            <option <?php if(date('m', strtotime('-1 month')) == 9) {echo 'selected'; } ?> value="9">September</option>
                            <option <?php if(date('m', strtotime('-1 month')) == 10) {echo 'selected'; } ?> value="10">October</option>
                            <option <?php if(date('m', strtotime('-1 month')) == 11) {echo 'selected'; } ?> value="11">November</option>
                            <option <?php if(date('m', strtotime('-1 month')) == 0) {echo 'selected'; } ?> value="12">December</option>
                        </select>
                </div>

                <div class="col-sm-4">
                        <select id="year" class="select2_single" style="width: 100%">
                            <?php 
                                for ($i=date('Y'); $i > 2010; $i--) { 
                                    if ($i == date('Y')) {
                                        echo '<option selected value="'.$i.'">'.$i.'</option>';
                                    }
                                    else
                                    {
                                         echo '<option value="'.$i.'">'.$i.'</option>';
                                    }
                                   
                                }
                            ?>
                        </select>
                </div>

            </div>
        <?php
    }


    public static function get_programme_for_preferences($l)

    {

        ?>

        <select id="programme" name="programme" class="form-control input-lg">

            <?php

            echo "<option value=0>All</option>";

            foreach ($GLOBALS['template_names'] as $template_name) 

            {

                ?>

                <option value="<?php echo $template_name->id; ?>"<?php echo $l == $template_name->id ? ' selected="selected"' : '';?>><?php echo $template_name->name; ?></option>

                <?php

            }

            ?>

        </select>

        <?php

    }

    public static function get_ethnicity_for_preferences()

    {

        $ethnicity = "";

        ?>

        <select id="ethnicity" name="ethnicity" class="form-control input-lg">

            <?php

            echo "<option value=All>All</option>"; 

            for ($i = 1; $i < count($ethnicity); $i ++)

            {

                echo "<option value="."'".$ethnicity[$i]."'".">".$ethnicity[$i]."</option>";

            }

            ?>

        </select>

        <?php

    }

    public static function get_post_code_for_preferences()

    {

        $post_code = "";

        ?>

        <select id="post_code" name="post_code" class="form-control input-lg">

            <?php

            echo "<option value=All>All</option>"; 

            for ($i = 1; $i < count($post_code); $i ++)

            {

                echo "<option value="."'".$post_code[$i]."'".">".$post_code[$i]."</option>";

            }

            ?>

        </select>

        <?php

    }

    public static function arrange_dates_and_headers_rateofchurn($var)

    {

        $period = $var["period"];

        $count = $var["count"];

        $frequency = $var["frequency"];    

        $start_date = $var["start_date"];

        $end_date = $var["end_date"];

        $date_end_array = explode("-", $start_date);

        $day = $date_end_array[0];

        $month = $date_end_array[1];

        $year = $date_end_array[2];

        if($period == 0)

        {

            $date_start_parsed = date("Y-m-d", mktime(0, 0, 0, date($month) - $count, date("1"), date($year)));

            $date_end_parsed = date('Y-m-d', strtotime('-1 second', strtotime($month . '/01/' . $year)));

            $widget_exp = "from: (" . date("d/m/Y", strtotime($date_start_parsed)) . ") to: (" . date("d/m/Y", strtotime($date_end_parsed)) . ")";

        }

        else

        {

            $date_start_parsed = date("Y-m-d", strtotime("-$count week monday this week", mktime(0, 0, 0, date($month), date($day), date($year))));

            $date_end_parsed = date("Y-m-d", strtotime("sunday last week", mktime(0, 0, 0, date($month), date($day), date($year))));

            $widget_exp = "from: W-" . date("W", strtotime($date_start_parsed)) . " (" . date("d/m/Y", strtotime($date_start_parsed)) . ") to: W-" . date("W", strtotime($date_end_parsed)) . " (" . date("d/m/Y", strtotime($date_end_parsed)) . ")";

        }

        $date_start_parsed = $date_start_parsed . " 00:00:00";

        $date_end_parsed = $date_end_parsed . " 23:59:59";

        return array("period" => $period, "frequency" => $frequency, "widget_exp" => $widget_exp, "date_start"=>$date_start_parsed, "date_end"=>$date_end_parsed);

    }

    public static function arrange_dates_and_headers($var)

    {

        $period = $var["period"];

        $count = $var["count"];

        $end_date = $var["end_date"];

        $date_end_array = explode("-", $end_date);

        $day = $date_end_array[0];

        $month = $date_end_array[1];

        $year = $date_end_array[2];

        $widget_exp = "";

        if($period == 0)

        {

            $date_start_parsed = date("Y-m-d", mktime(0, 0, 0, date($month) - $count, date("1"), date($year)));

            $date_end_parsed = date('Y-m-d', strtotime('-1 second', strtotime($month . '/01/' . $year)));

            $widget_exp = "from: (" . date("d/m/Y", strtotime($date_start_parsed)) . ") to: (" . date("d/m/Y", strtotime($date_end_parsed)) . ")";

        }

        else

        {

            $date_start_parsed = date("Y-m-d", strtotime("-$count week monday this week", mktime(0, 0, 0, date($month), date($day), date($year))));

            $date_end_parsed = date("Y-m-d", strtotime("sunday last week", mktime(0, 0, 0, date($month), date($day), date($year))));

            $widget_exp = "from: W-" . date("W", strtotime($date_start_parsed)) . " (" . date("d/m/Y", strtotime($date_start_parsed)) . ") to: W-" . date("W", strtotime($date_end_parsed)) . " (" . date("d/m/Y", strtotime($date_end_parsed)) . ")";

        }

        $date_start_parsed = $date_start_parsed . " 00:00:00";

        $date_end_parsed = $date_end_parsed . " 23:59:59";

        return array("period" => $period, "widget_exp" => $widget_exp, "date_start"=>$date_start_parsed, "date_end"=>$date_end_parsed);

    }

    //not using

    public static function get_lcs($mode)

    {

        if($mode == 1)

        {

            $csps = DB::table('f_csp')->get();

            ?>

            <div class="form-group col-sm-12">

                <label class="col-sm-3 control-label">CSP Name <i class="fa fa-institution"></i></label>

                <div class="col-sm-9">

                    <select id="selected_location" class="select2_single_ajax" style="width: 100%">

                        <option value=""></option>

                        <?php

                        foreach ($csps as $csp)

                        {

                            echo "<option value=$csp->id>$csp->name</option>";

                        }

                        ?>

                    </select>

                </div>

            </div>

            <script type="text/javascript">

                $(".select2_single_ajax").select2({

                    placeholder: "Please select an option",

                    allowClear: true,

                });

            </script>

            <?php

        }

        if($mode == 2)

        {

            $regions = DB::table('f_region')->get();

            ?>

            <div class="form-group col-sm-12">

                <label class="col-sm-3 control-label">Region Name <i class="fa fa-institution"></i></label>

                <div class="col-sm-9">

                    <select id="selected_location" class="select2_single_ajax" style="width: 100%">

                        <option value=""></option>

                        <?php

                        foreach ($regions as $region)

                        {

                            echo "<option value=$region->id>$region->name</option>";

                        }

                        ?>

                    </select>

                </div>

            </div>

            <script type="text/javascript">

                $(".select2_single_ajax").select2({

                    placeholder: "Please select an option",

                    allowClear: true,

                });

            </script>

            <?php

        }

        elseif($mode == 3)

        {

            $asa_divisions = DB::table('f_asa_division')->get();

            ?>

            <div class="form-group col-sm-12">

                <label class="col-sm-3 control-label">Asa Division Name<i class="fa fa-institution"></i></label>

                <div class="col-sm-9">

                    <select id="selected_location" class="select2_single_ajax" style="width: 100%">

                        <option value=""></option>

                        <?php

                        foreach ($asa_divisions as $asa_division)

                        {

                            echo "<option value=$asa_division->id>$asa_division->name</option>";

                        }

                        ?>

                    </select>

                </div>

            </div>

            <script type="text/javascript">

                $(".select2_single_ajax").select2({

                    placeholder: "Please select an option",

                    allowClear: true,

                });

            </script>

            <?php

        }

        elseif($mode == 4)

        {

            $operators = DB::table('f_operator')->get();

            ?>

            <div class="form-group col-sm-12">

                <label class="col-sm-3 control-label">Operator Name<i class="fa fa-institution"></i></label>

                <div class="col-sm-9">

                    <select id="selected_location" class="select2_single_ajax" style="width: 100%">

                        <option value=""></option>

                        <?php

                        foreach ($operators as $operator)

                        {

                            echo "<option value=$operator->id>$operator->name</option>";

                        }

                        ?>

                    </select>

                </div>

            </div>

            <script type="text/javascript">

                $(".select2_single_ajax").select2({

                    placeholder: "Please select an option",

                    allowClear: true,

                });

            </script>

            <?php

        }

        elseif($mode == 5)

        {

            $lcs_ = DB::table('site_id_name')->orderBy('name')->get();

            ?>

            <div class="form-group col-sm-12">

                <label class="col-sm-3 control-label">Leisure Centres <i class="fa fa-institution"></i></label>

                <div class="col-sm-9">

                    <select id="selected_location" class="select2_single_ajax" style="width: 100%">

                        <option value=""></option>

                        <?php

                        $counter = 1;

                        foreach ($lcs_ as $lc)

                        {

                            if($GLOBALS['demo'])

                            {

                                $lc_name = "Demo Leisure Centre ".$counter;

                            }

                            else

                            {

                                $lc_name = $lc->name;

                            }

                            echo "<option value=$lc->id>$lc_name</option>";

                            $counter++;

                        }

                        ?>

                    </select>

                </div>

            </div>

            <script type="text/javascript">

                $(".select2_single_ajax").select2({

                    placeholder: "Please select an option",

                    allowClear: true,

                });

            </script>

            <?php

        }

    }

    public static function get_lcs_for_preferences($mode, $l)

    {

        ?>

        <div class="form-group">

            <div class="input-group">

                <span class="input-group-addon"><i class="fa fa-institution" style="width: 30px;"></i></span>

                <?php

                if($mode == 1)

                {

                    $csps = DB::table('f_csp')->get();

                    ?>

                    <select id="selected_location" name="selected_location" class="form-control input-lg">

                        <option value="">Select Location</option>";

                        <?php

                        foreach ($csps as $location)

                        {

                            ?>

                            <option value="<?php echo $location->id; ?>"<?php echo $l == $location->id ? ' selected="selected"' : '';?>><?php echo $location->name ?></option>

                            <?php

                        }

                        ?>

                    </select>

                    <?php

                }

                if($mode == 2)

                {

                    $regions = DB::table('f_region')->get();

                    ?>

                    <select id="selected_location" name="selected_location" class="form-control input-lg">

                        <option value="">Select Location</option>";

                        <?php

                        foreach ($regions as $location)

                        {

                            ?>

                            <option value="<?php echo $location->id; ?>"<?php echo $l == $location->id ? ' selected="selected"' : '';?>><?php echo $location->name ?></option>

                            <?php

                        }

                        ?>

                    </select>

                    <?php

                }

                elseif($mode == 3)

                {

                    $asa_divisions = DB::table('f_asa_division')->get();

                    ?>

                    <select id="selected_location" name="selected_location" class="form-control input-lg">

                        <option value="">Select Location</option>";

                        <?php

                        foreach ($asa_divisions as $location)

                        {

                            ?>

                            <option value="<?php echo $location->id; ?>"<?php echo $l == $location->id ? ' selected="selected"' : '';?>><?php echo $location->name ?></option>

                            <?php

                        }

                        ?>

                    </select>

                    <?php

                }

                elseif($mode == 4)

                {

                    $operators = DB::table('f_operator')->get();

                    ?>

                    <select id="selected_location" name="selected_location" class="form-control input-lg">

                        <option value="">Select Location</option>";

                        <?php

                        foreach ($operators as $location)

                        {

                            ?>

                            <option value="<?php echo $location->id; ?>"<?php echo $l == $location->id ? ' selected="selected"' : '';?>><?php echo $location->name ?></option>

                            <?php

                        }

                        ?>

                    </select>

                    <?php

                }

                elseif($mode == 5)

                {

                    $lcs_ = DB::table('site_id_name')->orderBy('name')->get();

                    ?>

                    <select id="selected_location" name="selected_location" class="form-control input-lg">

                        <option value="">Select Location</option>";

                        <?php

                        $counter = 1;

                        foreach ($lcs_ as $location)

                        {

                            if($GLOBALS['demo'])

                            {

                                $lc_name = "Demo Leisure Centre ".$counter;

                            }

                            else

                            {

                                $lc_name = $location->name;

                            }

                            ?>

                            <option value="<?php echo $location->id; ?>"<?php echo $l == $location->id ? ' selected="selected"' : '';?>><?php echo $lc_name; ?></option>

                            <?php

                            $counter++;

                        }

                        ?>

                    </select>

                    <?php

                }

                ?>

            </div>

        </div>

        <?php

    }

    public static function get_location_for_preferences($l)

    {

        $location_ = DB::table('f_location')->get();

        ?>

        <select id="location" name="location" class="form-control input-lg">

            <?php

            foreach ($location_ as $location)

            {   

                ?>

                <option value="<?php echo $location->id; ?>"<?php echo $l == $location->id ? ' selected="selected"' : '';?>><?php echo $location->name ?></option>

                <?php

            }

            ?>

        </select>

        <?php

    }

    public static function get_facility_sub_types($l)

    {

        $sub_types = DB::table('f_facility_sub_type')->get();

        ?>

        <select id="f_sub_type" name="f_sub_type" class="form-control input-lg">

            <?php

            foreach ($sub_types as $f_sub_type)

            {   

                ?>

                <option value="<?php echo $f_sub_type->id; ?>"<?php echo $l == $f_sub_type->id ? ' selected="selected"' : '';?>><?php echo $f_sub_type->name ?></option>

                <?php

            }

            ?>

        </select>

        <?php

    }

    public static function get_pool_dimensions($l)

    {

        $dimensions = DB::table('f_pool_dimensions')->get();

        ?>

        <select id="p_dimension" name="p_dimension" class="form-control input-lg">

            <?php

            foreach ($dimensions as $p_dimension)

            {   

                ?>

                <option value="<?php echo $p_dimension->id; ?>"<?php echo $l == $p_dimension->id ? ' selected="selected"' : '';?>><?php echo $p_dimension->name ?></option>

                <?php

            }

            ?>

        </select>

        <?php

    }

    public static function get_select_location_name($mode, $select_location)

    {

        if($mode == 1)

        {

            $csps = DB::table('f_csp')

            ->where('id', $select_location)

            ->get();

            return $csps[0]->name;

        }

        elseif($mode == 2)

        {

            $regions = DB::table('f_region')

            ->where('id', $select_location)

            ->get();

            return $regions[0]->name;

        }

        elseif($mode == 3)

        {

            $asa_divisions = DB::table('f_asa_division')

            ->where('id', $select_location)

            ->get();

            return $asa_divisions[0]->name;

        }

        elseif($mode == 4)

        {

            $operators = DB::table('f_operator')

            ->where('id', $select_location)

            ->get();

            return $operators[0]->name;

        }

        elseif($mode == 5)

        {

            $csps = DB::table('site_id_name')->orderBy('name')

            ->where('id', $select_location)

            ->get();

            return $csps[0]->name;

        }

        else

        {

            $csps[0]->name = "All";

            return $csps[0]->name;

        }

    }

    public static function arrange_variables($var)

    {

        $_sport = Auth::user()->sport;

        $age = $var["age"];

        $gender = $var["gender"];

        $location = $var["location"];

        $programme = $var["programme"];

        $booking = $var["booking"];

        $person = $var["person"];

        if(isset($var["lc"]) && $location == 1)

        {

            $lc = SiteName::select("id")

            ->where('csp_id', $var["lc"])

            ->lists('id');

        }

        elseif(isset($var["lc"]) && $location == 2)

        {

            $lc = SiteName::select("id")

            ->where('region_id', $var["lc"])

            ->lists('id');

        }

        elseif(isset($var["lc"]) && $location == 3)

        {

            $lc = SiteName::select("id")

            ->where('asa_division_id', $var["lc"])

            ->lists('id');

        }

        elseif(isset($var["lc"]) && $location == 4)

        {

            $lc = SiteName::select("id")

            ->where('operator_id', $var["lc"])

            ->lists('id');

        }

        elseif(isset($var["lc"]) && $location == 5)

        {

            $lc = $var["lc"];

        }

        else

        {

            $lc ="none";

        }

        $start_date = $var["start_date"];

        $end_date = $var["end_date"];



        $report_month = $var["report_month"];
        $report_year = $var["report_year"];
        

        if($age == "0")

        {

            $age_start = 0;

            $age_end = 1000;

        }

        elseif($age == "1")

        {

            $age_start = 0;

            $age_end = 4;

        }

        elseif($age == "2")

        {

            $age_start = 5;

            $age_end = 13;

        }

        elseif($age == "3")

        {

            $age_start = 14;

            $age_end = 25;

        }

        elseif($age == "4")

        {

            $age_start = 26;

            $age_end = 35;

        }

        elseif($age == "5")

        {

            $age_start = 36;

            $age_end = 55;

        }

        else

        {

            $age_start = 56;

            $age_end = 1000;

        }

        if($gender == "All")

        {

            $gender = "";

        }

        if($person == "0")

        {

            $person = "";

        }

        if($booking == "0")

        {

            $booking = "";

        }

        if($programme == "0")

        {

            $programme = "";

        }

        $gender = "%" . $gender . "%";

        $person = "%" . $person . "%";

        $booking = "%" . $booking . "%";

        $programme = "%" . $programme . "%";

        $date_start_array = explode("-", $start_date);

        $date_end_array = explode("-", $end_date);

        $date_start_parsed = $date_start_array[2] . "-" . $date_start_array[1] . "-" . $date_start_array[0];

        $date_end_parsed = $date_end_array[2] . "-" . $date_end_array[1] . "-" . $date_end_array[0];

        $date_start_parsed = $date_start_parsed . " 00:00:00";

        $date_end_parsed = $date_end_parsed . " 23:59:59";

        return array("sport" => $_sport, "age_start"=>$age_start, "age_end"=>$age_end, "gender"=>$gender, "person"=>$person,"booking"=>$booking,"programme"=>$programme,"lc"=>$lc, "date_start"=>$date_start_parsed, "date_end"=>$date_end_parsed, "month" => $report_month, "year" => $report_year);

    }

    public static function arrange_variables_for_finance($var)

    {

        //var

        $_sport = Auth::user()->sport;

        $age = $var["age"];

        $gender = $var["gender"];

        $location = $var["location"];

        $payment = $var["payment"];

        if(isset($var["lc"]) && $location == 1)

        {

            $lc = SiteName::select("id")

            ->where('csp_id', $var["lc"])

            ->lists('id');

        }

        elseif(isset($var["lc"]) && $location == 2)

        {

            $lc = SiteName::select("id")

            ->where('region_id', $var["lc"])

            ->lists('id');

        }

        elseif(isset($var["lc"]) && $location == 3)

        {

            $lc = SiteName::select("id")

            ->where('asa_division_id', $var["lc"])

            ->lists('id');

        }

        elseif(isset($var["lc"]) && $location == 4)

        {

            $lc = SiteName::select("id")

            ->where('operator_id', $var["lc"])

            ->lists('id');

        }

        elseif(isset($var["lc"]) && $location == 5)

        {

            $lc = $var["lc"];

        }

        else

        {

            $lc ="none";

        }

        $start_date = $var["start_date"];

        $end_date = $var["end_date"];

        //Arrange them

        if($age == "0")

        {

            $age_start = 0;

            $age_end = 1000;

        }

        elseif($age == "1")

        {

            $age_start = 0;

            $age_end = 4;

        }

        elseif($age == "2")

        {

            $age_start = 5;

            $age_end = 13;

        }

        elseif($age == "3")

        {

            $age_start = 14;

            $age_end = 25;

        }

        elseif($age == "4")

        {

            $age_start = 26;

            $age_end = 35;

        }

        elseif($age == "5")

        {

            $age_start = 36;

            $age_end = 55;

        }

        else

        {

            $age_start = 56;

            $age_end = 1000;

        }

        if($gender == "All")

        {

            $gender = "";

        }

        if($person == "0")

        {

            $person = "";

        }

        $date_start_array = explode("-", $start_date);

        $date_end_array = explode("-", $end_date);

        $date_start_parsed = $date_start_array[2] . "-" . $date_start_array[1] . "-" . $date_start_array[0];

        $date_end_parsed = $date_end_array[2] . "-" . $date_end_array[1] . "-" . $date_end_array[0];

        $date_start_parsed = $date_start_parsed . " 00:00:00";

        $date_end_parsed = $date_end_parsed . " 23:59:59";

        return array("sport" => $_sport, "age_start"=>$age_start, "age_end"=>$age_end, "gender"=>$gender, "payment"=>$payment,"lc"=>$lc, "date_start"=>$date_start_parsed, "date_end"=>$date_end_parsed);

    }

}

?>