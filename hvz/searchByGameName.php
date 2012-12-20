<?php
require_once('core/init.php');
include('inc/phpfuncs.php');
?>

<html>
<head>

</head>

<body>


<?php
$game_name = $_POST['game_name'];
$latitude = $_POST['latitude'];
$longitude = $_POST['longitude'];


$today = date("Y-m-d H:i:s");


/*
$query = "SELECT distinct Games.game_id, fb_id, player_id, creator_id, game_name, fb_name, start_time FROM `Games`, `Game_Player_Info`, `Fb_Id_Name` WHERE fb_id = player_id AND Game_Player_Info.game_id = Games.game_id AND game_name LIKE '%$game_name%' AND creator_id <> '$user_id' AND player_id <> '$user_id' AND start_time > '$current_time' ORDER BY start_time";
$result = mysql_query($query);
*/

echo "<b>Search results</b><br />";

// query the games table
$query = mysql_query("SELECT `game_id`, `game_name`, `creator_id`, `start_time` FROM `Games` WHERE `game_name` LIKE '%$game_name%' AND winner_id = 0 ORDER BY `game_name` ");

// check the number of returned rows
$num = mysql_num_rows($query);

	
// begin the while loop, set the counter
$i = 0;
if ($game_name != "") {
while ($row = mysql_fetch_assoc($query)) {
		
		// fetch associated variables with the $query
		$creator_id 	= $row['creator_id'];
		$game_name  	= $row['game_name'];
		$game_id    	= $row['game_id'];
		$start_time	= $row['start_time'];
			
		// query the Game_Player_Info table for results where game_id and userid match
		$gpi_query = mysql_query("SELECT * FROM `Game_Player_Info` WHERE `player_id`='$user_id' AND `game_id`='$game_id'");
		$gpi_num   = mysql_num_rows($gpi_query);
			
		// if there is no match, then it means player is not part of that game
		// then, we can run the output
		if ($gpi_num == 0) {
			
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
						$("#joinText_<?=$i?>").html("<a href='game.php?game_id=<?=$row['game_id']?>'><font color='white'> Go to game </font></a> ");
					});				
				});
			</script>
	
			<?php

			
			// add one to the counter
			$i++;
		}
}
}


// if $i is still 1, it means no results were found
if ($i == 0) {
	echo "Sorry, no games were found.";
}
















/*
$i = 0;
while($row = mysql_fetch_array($result)){
	if (strtotime($row['start_time']) > $current_time) {
		$fql = "SELECT uid, pic_square FROM user WHERE uid IN (SELECT uid2 FROM friend WHERE uid1 = me() AND uid2 = '".$row["creator_id"]."')";
        $creator_profile = $facebook->api(array(
                                   			'method' => 'fql.query',
                                   			'query' => $fql,
                                 			));
		echo "Game <b>" . $row['game_name'] . "</b>" . " created by " . $row['fb_name'] . " "; 
		?><img src="<?php echo $creator_profile[0]['pic_square']; ?>"><br><?php
		echo " Starts on " . $row['start_time'];
		?>
		
		<div id="joinGame_<?=$i?>">
          		<input id="submit_<?=$i?>" name="submit" type="submit" value="Join">
		</div>
		<div id="joinText_<?=$i?>"></div>
		
		<script type="text/javascript">	
			$("#submit_<?=$i?>").click(function(event) {
				alert("just joined a game");
				$.post("join.php", {
					game_id: <?=$row['game_id']?>, 
					invitee_id: <?=$user_id?>, 
					latitude: <?=$latitude?>, 
					longitude: <?=$longitude?>
				}, function(data){			
					$("#submit_<?=$i?>").hide();
					$("#joinText_<?=$i?>").html("Joined!");
				});				
			});
		</script>
		
		<?php
	}
	++$i;
}



} // this is the end of my if loop. hope it works!

*/
?>



</body>
</html>