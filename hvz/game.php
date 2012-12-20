<?php
/* *****************************************************************************
 * *****************************************************************************
 * Title:           Games
 * Location:        "/game.php"
 * 
 * Description:     This will show the main map of the game, all the impt info
 * 
 * Author:          John Gold
 * Date:            11/1/12
 * Version:         1.0
 * *****************************************************************************
 * *****************************************************************************
 */
 


// initialise the app
// init.php contains all facebook sdk info, session control, and the works
require_once('core/init.php');

include('inc/phpfuncs.php');
include('inc/verifyLogin.php');
?>

<html>
<?php

// generate page title and include the header
// $header_title is passed in the header.php file
$header_title = "Humans vs. Zombies";
include('inc/header.php');


?>

<body>
<?php

$user_profile_query = 'SELECT name FROM user WHERE uid = me()';
$user_profile_result = $facebook->api(array(
                              'method' => 'fql.query',
                              'query' => $user_profile_query,                             
                            ));


$game_id = $_GET['game_id'];

$nameQuery = "select * from Games where game_id = '$game_id';";
$humansQuery = "select count(*) as num from Game_Player_Info where game_id = '$game_id' and is_human = 1;";
$zombiesQuery = "select count(*) as num from Game_Player_Info where game_id = '$game_id' and is_human = 0;";
$startTimeQuery = "select start_time from Games where game_id = '$game_id';";

$game_nameArray = mysql_fetch_assoc(mysql_query($nameQuery));
$num_humansArray = mysql_fetch_assoc(mysql_query($humansQuery));
$num_zombiesArray = mysql_fetch_assoc(mysql_query($zombiesQuery));
$start_timeArray = mysql_fetch_assoc(mysql_query($startTimeQuery));


$game_name = $game_nameArray['game_name'];
$num_humans = $num_humansArray['num'];
$num_zombies = $num_zombiesArray['num'];
$start_timestamp = $start_timeArray['start_time'];
//$start_time = strtotime($start_timestamp)
date_default_timezone_set('America/Los_Angeles'); //just get users actual time zone
$cur_date = date('Y-m-d h:i:s', time());

$now = time();
$start = strtotime($start_timestamp);


	if ($now < $start) {
		include('gameNotStarted.php');	
	} else if($num_humans == 1){
		include('gameOver.php');
	} else if($num_zombies > 0){
		include('gameStarted.php');
	} else if($num_humans >= 3 && $num_zombies == 0){
		
		//pick random player to be a zombie
		$indexOfZombie = rand(0, $num_humans-1);
		$counter = 0;
		$player_id = 0;
		$query = "SELECT * FROM Game_Player_Info WHERE game_id = '$game_id'";
		$results = mysql_query($query);
		while($row = mysql_fetch_assoc($results)){	
			$player_id = $row['player_id'];
			if($counter == $indexOfZombie) break;
			$counter++;
		}	
		$query2 = "UPDATE Game_Player_Info SET is_human = 0 WHERE player_id = '$player_id'";
		mysql_query($query2);
		include('gameStarted.php');
		
	} else if($num_humans + $num_zombies < 3){
		//set time of game start back 1 day from current time
		$futureSeconds = $now + 24*60*60;
		$futureTimestamp = date('Y-m-d h:i:s', $futureSeconds);
		$query = "UPDATE Games SET start_time = '$futureTimestamp' WHERE game_id = '$game_id'";
		mysql_query($query);
		//and give them an alert in gameNotStarted
		$gamePushedBack = 1;
		include('gameNotStarted.php');	
	} else {
		//there should be no more condtions
		echo "How did you get here???";	
	}
	
	?>

</body>
</html>