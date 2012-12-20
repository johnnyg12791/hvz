<?php
require_once('core/init.php');

?>


<html>
<head>

</head>

<body>
<?php

$name = $_POST["game_name"];
$date = $_POST["start_date"];
$latitude = $_POST["latitude"];
$longitude = $_POST["longitude"];


$user_profile_result;

if ($user_id) {
	$user_profile_query = 'SELECT name FROM user WHERE uid = me()';
	$user_profile_result = $facebook->api(array(
                                   'method' => 'fql.query',
                                   'query' => $user_profile_query,
                                 ));
}
else {
	?>
	<script>
	window.location = "http://stanford.edu/~johngold/cgi-bin/humans_vs_zombies/index.php"
	</script>
	<?php
}


$creator_id = $user_id;
$creator_name = $user_profile_result[0]['name'];

//echo "the latitude = $latitude, and the longitude = $longitude.<br>";
//echo "The game you created is called: $name, the game will start on $date";

include("core/db/dbConfig.php");

$query = "insert into Games (game_name, creator_id, latitude, longitude, start_time) values ('$name', '$creator_id', '$latitude', '$longitude', '$date');";

$result = mysql_query($query);

/*this is to get the game_id of the game just created */
$query2 = "SELECT game_id from Games where game_name = '".$name."'";
$game_id_result = mysql_query($query2);
$row = mysql_fetch_assoc($game_id_result);
$game_id = $row["game_id"];


echo "<h4> <font color='white'>Game: '$name' successfully created. Invite some of your friends.<br></font></h4>";
echo "<a href='game.php?game_id=" . $game_id . "'>Click here to see game information</a>";
//echo "and the game id is $game_id<br>";
//echo "the player name is $creator_name<br>";

?>


<?php
if ($user_id) {
	try {
		$fql = 'SELECT uid, name, pic_square FROM user WHERE uid IN (SELECT uid2 FROM friend WHERE uid1 = me()) AND is_app_user = 1 ORDER BY name';
		$ret_obj = $facebook->api(array(
                                   'method' => 'fql.query',
                                   'query' => $fql,
                                 ));
                                 
		$count = count($ret_obj);
		?>
		<h3>Select friends to invite!</h3>
		<ul data-role="listview" data-inset="true" id="invite">
		<?php
		for ($i = 0; $i < $count; $i++) {
        	?>
        	<img src="<?php echo $ret_obj[$i]['pic_square']; ?>">
       		<?php
       		echo $ret_obj[$i]['name'];
       		?>
					       		
       		<div id="inviteFriend_<?=$ret_obj[$i]['uid']?>">
            	<input id="friend_id_<?=$i?>" name="friend_id" value="<?php echo $ret_obj[$i]['uid']; ?>" type="hidden">
           	 	<input id="game_id" name="game_id" value="<?php echo $game_id; ?>" type="hidden">
        		<input id="submit_<?=$ret_obj[$i]['uid']?>" type="submit" name="submit" value="Invite">
			</div>
			<div id="invitedText_<?=$ret_obj[$i]['uid']?>"></div>
			
			
			<script type="text/javascript">
				
				$("#submit_<?=$ret_obj[$i]['uid']?>").click(function(event) {$.post("invite.php", {
						friend_id: $("#friend_id_<?=$i?>").val(),
						game_id: $("#game_id").val() 
					},function(data){
						//this removes the button and displays the text Invited instead
						$("#submit_<?=$ret_obj[$i]['uid']?>").remove();
						$("#invitedText_<?=$ret_obj[$i]['uid']?>").html("Invited!");
					});
					
				});
			</script>

       		<?php
        }
        ?>
        </ul>
        <?php
	}
	catch(FacebookApiException $e) {
		$login_url = $facebook->getLoginUrl(); 
        echo 'Please <a href="' . $login_url . '">login.</a>';
        error_log($e->getType());
        error_log($e->getMessage());
	}		
}
?>

	

<?php

$query3 = "insert into Game_Player_Info (game_id, player_id, recent_latitude, recent_longitude) values ('$game_id', '$creator_id', '$latitude', '$longitude');";
$result2 = mysql_query($query3);
?>
</body>

</html>