<?php

function getSqlTime(){
	return date('Y-m-d h:i:s', time());
}

function getHoursAgo($sqlTime){
	$curTime = time();
	$pastTime = strtotime($sqlTime);
	$secondsAgo = $curTime-$pastTime;
	$hoursAgo = $secondsAgo/(60 * 60);
	return round($hoursAgo);
}

function getHoursInFuture($sqlTime){
	$curTime = time();
	$futureTime = strtotime($sqlTime);
	$secondsFuture = $futureTime-$curTime;
	$hoursFuture = $secondsFuture/(60 * 60);
	return round($hoursFuture);
}

function formatDate($sqlTime){
	return date('M j, Y, g:i a', strtotime($sqlTime));

}


?>