<?php
	require_once('core/init.php');
?>

<html>

<body>
<!-- called when a user joins a game after searching (by friend name or game name) -->
<?php


$game_id = $_POST['game_id']; 

$player_id = $_POST['invitee_id'];//CHECK THIS
$start_as_human = $_POST['start_as_human'];
//$start_as_human = 1;

/*basically get current latitude and longitude*/
/*maybe can also use get/post requests*/
$recent_latitude = $_POST['latitude'];
$recent_longitude = $_POST['longitude'];

$query = "insert into Game_Player_Info (game_id, player_id, is_human, recent_latitude, recent_longitude) values ('$game_id', '$player_id', '$start_as_human', '$recent_latitude', '$recent_longitude')";
$result = mysql_query($query);

$deleteFromInvitesQuery = "UPDATE Game_Invites SET accepted = 1 WHERE game_id = '$game_id' and invitee_id = '$player_id'";
mysql_query($deleteFromInvitesQuery);

if($result) {
	//echo "good stuff, user joined the game_player_info database";
	?>
	alert('good stuff, user joined the game_player_info database');
	<?php
} else {
	//echo "database submit error";
	?>
	alert('database submit error');
	<?php
}

?>


<html>
<body>
