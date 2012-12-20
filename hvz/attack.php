<?
require_once('core/init.php');
?>
<html>
<?php
include('inc/header.php');
?>
<body>
<?
$bitten_id = $_POST['bitten_id'];
$game_id = $_POST['game_id'];
$biter_id = $_POST['biter_id'];
$curTime = date('Y-m-d G:i:s');



//stuff
$word = "word";

//convert bitten player to zombie, add bitten_by id and time_of_bite
$query = "UPDATE Game_Player_Info SET is_human = 0, bitten_by = '$biter_id', time_of_bite =\"'$curTime'\" where game_id = '$game_id' and player_id = '$bitten_id'";
$result = mysql_query($query);
echo "stuff";


//increase the num_bites for player who did the biting
$query2 = "UPDATE Game_Player_Info SET num_bites = num_bites+1 where game_id = '$game_id' and player_id = '$biter_id'";
$result2 = mysql_query($query2);
?>


</body>
</html>