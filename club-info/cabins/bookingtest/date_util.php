<?php
      function day_select($field_name, $date_array)
      {
        $out = "<select name ='" . $field_name . "'>";
        for($i=1; $i<=31; $i++)
        {
          $out .= "<option value='" . $i . "'";
          if ($date_array["mday"] == $i)
            $out .= " SELECTED";
          $out .= ">" . sprintf("%2d", $i) . "</option>";
        }
        $out .= "</select>";
        return $out;
      }
      function month_select($field_name, $date_array)
      {
        $months = array("Jan","Feb","Mar","Apr","May","June","July","Aug","Sept","Oct","Nov","Dec");
        $out = "<select name ='" . $field_name . "'>";
        for($i=1; $i<=12; $i++)
        {
          $out .= "<option value='" . $i . "'";
          if ($date_array["mon"] == $i)
            $out .= " SELECTED";
          $out .= ">" . $months[$i-1]  . "</option>";
        }
        $out .= "</select>";
        return $out;
      }
      function year_select($field_name, $date_array)
      {
        $this_year = $date_array["year"];
        $out = "<select name ='" . $field_name . "'>";
        for($i=0; $i<=2; $i++)
        {
          $out .= "<option value='" . sprintf("%4d", $this_year + $i) . "'";
          $out .= ">" . sprintf("%4d", $this_year + $i)  . "</option>";
        }
        $out .= "</select>";
        return $out;
      }
      
      // Test if the selected date is in the past 
      // $yyyy - Year 4 digit
      // $mm - month 1-12
      // $dd day of month
      function is_date_passed($yyyy, $mm, $dd) // Returns true or false
      {
        date_default_timezone_set( 'America/Los_Angeles');
      	$date = strtotime($yyyy . '-' . $mm . '-' . $dd . ' 23:59:59'); // MySql format
      	return ($date < $_SERVER['REQUEST_TIME']);
      }
      
      

?>