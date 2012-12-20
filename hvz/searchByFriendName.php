<?php
require_once('core/init.php');
include('inc/phpfuncs.php');
?>

<html>

<head>

</head>

<body>

<?php
$friend_name = $_POST['friend_name'];
$latitude = $_POST['latitude'];
$longitude = $_POST['longitude'];

$today = date("Y-m-d H:i:s");


echo "<b>Search results</b><br />";

// query the games table (for games still ongoing)
// want to search for all games your friends are in, not the ones they've created!
$query = mysql_query("SELECT `game_id`, `game_name`, `creator_id`, `start_time` FROM `Games` WHERE `creator_id` IN (SELECT fb_id FROM `Fb_Id_Name` WHERE fb_name LIKE '%$friend_name%') AND winner_id = 0 ORDER BY `game_name` ");	

// check the number of returned rows
$num = mysql_num_rows($query);


	
// begin the while loop, set the counter
$i = 0;
if ($friend_name != "") {
while ($row = mysql_fetch_assoc($query)) {
		// fetch associated variables with the $query
		$creator_id 	= $row['creator_id'];
		$game_name  	= $row['game_name'];
		$game_id    	= $row['game_id'];
		$start_time		= $row['start_time'];
			
		// query the Game_Player_Info table for results where game_id and userid match
		$gpi_query = mysql_query("SELECT * FROM `Game_Player_Info` WHERE `player_id`='$user_id' AND `game_id`='$game_id'");
		$gpi_num   = mysql_num_rows($gpi_query);
			
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
				echo "<div style='margin-bottom:4px'><b>" . $game_name . "</b>: Starts on " . formatDate($start_time);
				?>
				
				<div id="joinGame_<?=$i?>">
          			<input id="submit_<?=$i?>" name="submit" type="submit" value="Join as human!">
				</div>
				<br>
				<div id="joinText_<?=$i?>"></div>
				<?php
			}
			
			?>
			<!--
			<div id="joinGame_<?=$i?>">
          			<input id="submit_<?=$i?>" name="submit" type="submit" value="Join">
			</div>
		    -->
		    
			<div id="joinText_<?=$i?>"></div>
			
			<script type="text/javascript">
				
				$("#submit_<?=$i?>").click(function(event) {
					alert("just joined a game");
					$.post("join.php", {
						game_id: <?=$game_id?>, 
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
			
			// echo "<div style='margin-bottom:4px'><b>" . $game_name . " - id:" . $game_id . "</b><br />Created by: " . $fbid_name . "</div>";
			
			// add one to the counter
			$i++;
		}
}
}

// if $i is still 1, it means no results were found
if ($i == 0) {
	echo "Sorry, no games were found.";
}
?>


</body>

</html>
