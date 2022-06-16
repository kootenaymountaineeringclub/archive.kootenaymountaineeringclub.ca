<?php

$answer = array();

exec('php -l course-registration.php',$answer);

echo "<pre>" . print_r($answer,true) . "</pre>";

if ( mail('timclint@gmail.com','Lint response',print_r($answer,true))) {
	 echo "<pre>Mail returned true</pre>";
} else {
	 echo "<pre>Mail returned false</pre>";
	
}
?>