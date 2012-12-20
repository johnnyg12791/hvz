<div data-role="page">
	<div data-role="header">
		<a onclick="history.back(-1)" data-icon="arrow-l">Back</a>
		<h1><?=$game_name?></h1>
		<a href="gameDetails.php?game_id=<?=$game_id?>" data-icon="arrow-r">More</a>
	</div><!-- /header -->


	<div data-role="content">
	
	<?php
	/*This takes care of the sql stuff, if a winner hasnt been declared, its updated and so is the end_time*/
	$query = "SELECT * FROM Games, Fb_Id_Name WHERE game_id = '$game_id' AND fb_id = winner_id";
	$result = mysql_fetch_assoc(mysql_query($query));
	$winner_id = $result['winner_id'];
	$winner_name = $result['fb_name'];
	/*Not sure what this if-statement is good for???
	if($winner_id == 0){
		$humanQuery = "SELECT player_id, fb_name FROM Game_Player_Info, Fb_Id_Name WHERE game_id = '$game_id' AND fb_id = player_id AND is_human = 1";
		$humanResult = mysql_fetch_assoc(mysql_query($humanQuery));
		$human_id = $humanResult['player_id'];
		$human_name = $humanResult['fb_name'];
		echo "$human_name is the human id (who is still alive)<br>";
		$updateQuery = "UPDATE Games SET winner_id = '$human_id', end_time = '$cur_date' WHERE game_id = '$game_id'";
		mysql_query($updateQuery);
	}else{*/
	
	echo "<font color='white'>Game Over!<br>$winner_name is the winner!</font>";

	/*From here, we can display all sorts of information, stats, whatever*/
	
	?>
	</div><!-- /content -->

	
	<div data-role="footer" data-id="samebar" class="nav-icons" data-position="fixed" data-tap-toggle="false">	
	<?php
		include('inc/navBar.php');
	 ?>
	 </div> <!-- end of footer -->
	
</div><!-- /page -->