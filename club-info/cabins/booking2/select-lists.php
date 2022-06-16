<?php


function get_date_selects($start_date) {
	
	$MONTHS = array ( "" , "Jan" , "Feb" , "Mar" , "Apr" , "May" , "Jun" , "Jul" , "Aug" , "Sep" , "Oct" , "Nov" , "Dec"  );
	$MONTH_DAYS = array ( 0 , 31 , 28 , 31 , 30 , 31 , 30 , 31 , 31 , 30 , 31 , 30 , 31 );
	$MONTH_AND_DAYS = array ("Jan" => 31 , "Feb" => 28 , "Mar" => 31 , "Apr" => 30 , "May" => 31 , "Jun" => 30 ,
						"Jul" => 31 , "Aug" => 31 , "Sep" => 30 , "Oct" => 31 , "Nov" => 30 , "Dec" => 31 );
	
	$is_leap_year = false;
	$start_year = $start_date['year'];
	$start_month = $start_date['mon'];
	
	if (is_int($start_year / 4)) $is_leap_year = true;
	
	if ($is_leap_year) {
		$MONTH_DAYS[2] = 29;
		$MONTH_AND_DAYS['Feb'] = 29;
	}
	
	// year list
	
	$year_list = $start_year;
	if ($start_date['mon'] > 7) $year_list .= ", " . ($start_year + 1);
	
	$year_array = split(", ",$year_list);
	
	// first month
	
	$first_month_end = $MONTH_DAYS[$start_date['mon']];
	$first_day = $start_date['mday'];
	$days_after = $first_month_end - $first_day;
	
	$days_to_add = $first_month_end - $days_after ;
	
	$total_days = $days_after + 1;
	
	// month list
	
	$month_list = $start_date['mon'];
	$month_count = 1;
	$current_month = $start_month + 1 ;
	
	for ($month_count = 1 ; $month_count < 7 ; $month_count++ ) {
		$total_days += $MONTH_DAYS[$current_month];
		$month_list .=  ", " . $MONTHS[$current_month] ;
		$current_month += 1;
		if ( $current_month > 12 ) $current_month = 1 ;
	}
	
	if ($days_to_add > 0) {
		$month_list .=  ", " . $MONTHS[$current_month] ;
		$total_days += $days_to_add ;
	}
	
	$month_array = split(", ", $month_list);
	
	// day counts
	
	$day_array = new(array);
	
	foreach($month_array as $name) {
		$day_array[$name] = (1,$MONTH_AND_DAYS[$name] );
	}


	$month_start = $start_date['mon'];
	$month_day_list = "";
	
	$day_limit = $start_date['mday'];
	
	for ($which = 1 ; $which < 7 ; $which ++) {
		$month_day_list .= $MONTHS[$month_start] . ": ";
		$start_day = 1;
		$end_day = $MONTH_DAYS[$month_start];
		
		echo "<pre>Month" . $month_start . ": End Day of " . $MONTHS[$month_start] . " is " . $MONTH_DAYS[$month_start] .  "</pre>\n";
		
		if ($which == 1) $start_day = $day_limit;
		if ($which == 6) $end_day = $day_limit;
		
		$month_day_list .= $start_day . " thru " . $end_day . "\n" ;
		
		$month_start += 1;
		if ($month_start == 13) $month_start = 1;
	}
	
	if ($days_before_first_day > 0) {
		$month_start += 1 ;
		if ($month_start == 13) $month_start = 1;
		$month_day_list .= $MONTHS[$month_start] . ": ";
		$month_day_list .= "1 thru $days_before_first_day \n" ;		
	}
	
	return "<pre>" . $year_list . $month_day_list . "</pre>\n";
}


$six_month_dates = get_date_selects($today_array);

echo $six_month_dates;

?>