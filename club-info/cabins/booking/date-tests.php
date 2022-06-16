<?php

global $BeginDate,
	$MONTHS,
	$MONTH_DAYS;

	$MONTHS = array ( "" , "Jan" , "Feb" , "Mar" , "Apr" , "May" , "Jun" , "Jul" , "Aug" , "Sep" , "Oct" , "Nov" , "Dec"  );
	$MONTH_DAYS = array ( 0 , 31 , 28 , 31 , 30 , 31 , 30 , 31 , 31 , 30 , 31 , 30 , 31 );
	

date_default_timezone_set('America/Los_Angeles');
	
function six_month_year_select($field_name, $date_array)
  {
    $this_year = $date_array["year"];
    $out = "<select name ='" . $field_name . "'>";
    $out .= "<option value='" . sprintf("%4d", $this_year) . "'>$this_year</option>\n";
    if ($date_array['mon'] > 6)
       $out .= "<option value='" . sprintf("%4d", $this_year + $i)  . "'>" . ($this_year + 1) . "</option>\n";
    $out .= "</select>";
    return $out;
  }

function month_select($field_name, $date_array)
  {
	global $MONTHS;
	
	$month = $date_array['mon'];
	  
    $out = "<select name ='" . $field_name . "'>";
    for($i = 1; $i < 7; $i++)
    {
      $out .= "<option value='" . substr("0" . $month , -2) . "'";
      $out .= ">" . $MONTHS[$month]  . "</option>";
      $month += 1;
      if ($month > 12) $month = 1;
    }
    $out .= "</select>";
    return $out;
  }

function day_select($field_name, $date_array)
  {
    $out = "<select name ='" . $field_name . "'>";
    for($i=1; $i<=31; $i++)
    {
      $out .= "<option value='" . substr("0" . $i , -2) . "'";
      if ($date_array["mday"] == $i)
        $out .= " SELECTED";
      $out .= ">" . sprintf("%2d", $i) . "</option>";
    }
    $out .= "</select>";
    return $out;
  }
  
function SixMonthLimit () {
	return getdate(strtotime('+6 month'));
}

$inSixMonths = SixMonthLimit();

echo "<pre>" . print_r($inSixMonths,true) . "</pre>\n";

echo "<pre>" . six_month_year_select("sy", $inSixMonths) . month_select("sm", $inSixMonths) . day_select("sd", $inSixMonths) . "</pre>\n";

function get_date_selects($start_date) {
	
	$MONTHS = array ( "" , "Jan" , "Feb" , "Mar" , "Apr" , "May" , "Jun" , "Jul" , "Aug" , "Sep" , "Oct" , "Nov" , "Dec"  );
	$MONTH_DAYS = array ( 0 , 31 , 28 , 31 , 30 , 31 , 30 , 31 , 31 , 30 , 31 , 30 , 31 );
	
	$is_leap_year = false;
	$start_year = $start_date['year'];
	if (is_int($start_year / 4)) $is_leap_year = true;
	
	if ($is_leap_year) $MONTH_DAYS[2] = 29;
	
	$month_end = $MONTH_DAYS[$start_date['mon']];
	$first_day = $start_date['mday'];
	$days_after = $month_end - $first_day;
	
	$days_before_first_day = $month_end - $days_after ;
	
	echo "<pre>$first_day : $month_end : To add: $days_before_first_day </pre>\n";
	
	$year_list = $start_year;
	if ($start_date['mon'] > 7) $year_list .= ", " . ($start_year + 1);
	$year_list .= "\n";
	
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
		$month_day_list .= $MONTHS[$month_start] . ": ";
		$month_day_list .= "1 thru $days_before_first_day \n" ;		
	}
	
	return "<pre>" . $year_list . $month_day_list . "</pre>\n";
}


$six_month_dates = get_date_selects($inSixMonths);

echo $six_month_dates;




/*
echo "<pre>Before date selects</pre>\n";

$today_array = getdate(time() + (24 * 60 * 60));

echo "<form method='POST' action='date-tests.php' >\n";
echo year_select("sy", $today_array);
echo month_select("sm", $today_array);
echo day_select("sd", $today_array);
echo "\n<input type='submit' name='submit' value='Set Date'></form>\n";
echo "</form>\n";

echo "<pre>TodayArray:\n" . print_r($today_array,true) . "</pre>\n";
echo "<pre>Post:\n" . print_r($_POST,true) . "</pre>\n";

$BeginDate = $_POST['sy'] . '-' . substr("0" . $_POST['sm'] , -2) . '-' . substr("0" . $_POST['sd'] , -2);

$SixMonths = SixMonthLimit ();

echo "<p>Begin: " . $BeginDate . "   -   6 Months: " .  $SixMonths . "</p>";

if ($BeginDate > $SixMonths) {
	echo "<p>$BeginDate is greater than 6 Month</p>";
} else {
	echo "<p>$BeginDate is less than 6 Month</p>";

}
*/


?>