<?php

require_once('core/init.php');

?>


<html>
<body>

<?php

/*$query2 = "Select * from Games";
$result2 = mysql_query($query2);
if($result2)
echo "some stuff works";
else
echo "oops";
*/

$invitee_id = $_POST['invitee_id'];
$inviter_id = $_POST['inviter_id'];
$game_id = $_POST['game_id'];

//echo " '$invitee_id' and '$inviter_id' and '$game_id' ";


$query = "Update Game_Invites set accepted = 1 where game_id = '$game_id' and inviter_id = '$inviter_id' and invitee_id = '$invitee_id';";
$result = mysql_query($query);
if($result)
	echo "successfully accepted invite";
else
	echo "invite not accepted correctly";
	
	

$latitude = $_POST['latitude'];
$longitude = $_POST['longitude'];	
	
	
	
$query2 = "insert into Game_Player_Info (game_id, player_id, recent_latitude, recent_longitude) values ('$game_id', '$invitee_id', '$latitude', '$longitude');";
$result2 = mysql_query($query2);
if($result2)
	echo "joined the game database";
else
	echo "could not join game database";

?>

</body>
</html>