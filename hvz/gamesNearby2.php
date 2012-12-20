<?php
require_once('core/init.php');
include('inc/phpfuncs.php');
?>

<html>
<head>

</head>

<body>

<?php

function getDistance($lat1, $lon1, $lat2, $lon2)
{
	$R = 6371;
	$dLat = deg2rad($lat2 - $lat1);
	$dLon = deg2rad($lon2 - $lon1);
	$lat1 = deg2rad($lat1);
	$lat2 = deg2rad($lat2);
	
	$a = sin($dLat / 2) * sin($dLat/2) + sin($dLon/2) * sin($dLon/2) * cos($lat1) * cos($lat2);
	$c = 2 * atan2(sqrt($a), sqrt(1 - $a));
	
	$d = $R * $c * 0.621371;
	$d = round($d, 3);
	return $d;
}

$latitude = $_POST["latitude"];
$longitude = $_POST["longitude"];
//$user_id = $_POST["user_id"];

$today = date("Y-m-d H:i:s");

//echo "Your current latitude = $latitude, and your current longitude = $longitude. <br>";
echo "Games created within 1 mile of your current location: <br>";
		
//$query = "SELECT * FROM Games WHERE '$user_id' NOT IN (SELECT player_id FROM Game_Player_Info WHERE Games.game_id = Game_Player_Info.game_id);";
//$result = mysql_query($query);

// query the games table, finding all games that have no winners (still going on)
$query = mysql_query("SELECT * FROM `Games` WHERE winner_id = 0 ORDER BY `game_name` ");

// begin the while loop, set the counter
$i = 0;
while ($row = mysql_fetch_assoc($query)) {
	
		// fetch associated variables with the $query
		$creator_id 	= $row['creator_id'];
		$game_name  	= $row['game_name'];
		$game_id    	= $row['game_id'];
		$start_time	= $row['start_time'];
		$latitude2	= $row['latitude'];
		$longitude2	= $row['longitude'];
		
		// query the Game_Player_Info table for results where game_id and userid match
		$gpi_query = mysql_query("SELECT * FROM `Game_Player_Info` WHERE `player_id`='$user_id' AND `game_id`='$game_id'");
		$gpi_num   = mysql_num_rows($gpi_query);
		
		// if there is no match, then it means player is not part of that game
		// then, we can run the output
		if ($gpi_num == 0 and getDistance($latitude, $longitude, $latitude2, $longitude2) < 1) {
	
			$fbid_query = mysql_query("SELECT `fb_name` FROM `Fb_Id_Name` WHERE `fb_id`='$creator_id'");
			$fbid_row   = mysql_fetch_assoc($fbid_query);
			$fbid_name  = $fbid_row['fb_name'];
			
			//echo "<div style='margin-bottom:4px'><b>" . $game_name . " - id:" . $game_id . "</b><br />Created by: " . $fbid_name .
			//		"<br />Starting on: " . $start_time . "</div>";
	
			if ($start_time < $today) {
				$human = 0;
				echo "<div style='margin-bottom:4px'><b>" . $game_name . "</b>: Started on " . formatDate($start_time);
				?>
				<div id="joinGame_<?=$i?>">
          			<input id="submit_<?=$i?>" name="submit" type="submit" value="Join as zombie!">
				</div>
				<div id="joinText_<?=$i?>"></div>
				<?php
			}
			else {
				$human = 1;
				echo "<div style='margin-bottom:4px'><b>" . $game_name . "</b>: Starting on " . formatDate($start_time);
				?>
				<div id="joinGame_<?=$i?>">
          			<input id="submit_<?=$i?>" name="submit" type="submit" value="Join!">
				</div>
				<div id="joinText_<?=$i?>"></div>
				<?php
			}
			?>
			
			<!--
			<div id="joinGame_<?=$i?>">
          			<input id="submit_<?=$i?>" name="submit" type="submit" value="Join">
			</div>
			<div id="joinText_<?=$i?>"></div>
			-->
		
			<script type="text/javascript">	
				$("#submit_<?=$i?>").click(function(event) {
					alert("just joined a game");
					$.post("join.php", {
						game_id: <?=$row['game_id']?>, 
						invitee_id: <?=$user_id?>, 
						latitude: <?=$latitude?>, 
						longitude: <?=$longitude?>,
						start_as_human: <?=$human?>
					}, function(data){			
						$("#submit_<?=$i?>").hide();
						$("#joinText_<?=$i?>").html(
						"<a href='game.php?game_id=<?=$row['game_id']?>'><font color='white'> Go to game </font></a> ");
					});				
				});
			</script>
	
			<?php
			
			// add one to the counter
			$i++;
		}
}

// if $i is still 1, it means no results were found
if ($i == 0) {
	echo "Sorry, no games were found.";
}

?>


</div>

</body>

</html>