<?php
require_once('core/init.php');
?>

<div data-role="page">
		<div data-role="header">
		<?php
		
		if($gamePushedBack == 1)
			echo "The start time has been pushed back because there are less than 3 players<br>";
		//echo "This game has not yet started, it will start on $start_time<br>";
		$num_hours_till_start = ($start - $now)/3600;
		$start_date_info = date('Y-m-d h:i:s', $start - $now)
		//echo "That is in $num_hours_till_start hours";
		
		?>
		<a onclick="history.back(-1)" data-icon="arrow-l">Back</a>
		<h1><?=$game_name?></h1>
		</div>
	<div data-role="content">
	<?php
		$creator_id = $game_nameArray['creator_id'];
		echo "<font color='white'>This game has not yet started, it will start in " . getHoursInFuture($start_timestamp) . " hours<br></font>";
		if($creator_id == $user_id){
			echo "<font color='white'>However, since you created this game, you can start the game immediately <br></font>";
	
		?>
			<div id = "startGameNow">
				<input id="startNow" value="Start Now!" type="button" data-theme='a'>
			</div>
			
			<script type="text/javascript">
				$("#startNow").click(function(event) {
					$.post("startGameNow.php", {
						game_id: <?=$game_id?> 
					},function(data){
						window.location.reload(false);
					});
				});
			</script>
	<?
		}
	?>
		<div data-role="collaspsible" data-inset="true" data-theme="a">
			<h3>Players currently in game</h3>
			<ul data-role="listview" data-inset="true">
				<?php
					$current_players_query = "SELECT * FROM Game_Player_Info, Fb_Id_Name WHERE game_id = '$game_id' AND fb_id = player_id";
					$current_players_result = mysql_query($current_players_query);
					while ($row = mysql_fetch_assoc($current_players_result)) {
						?>
						<a href="userProfile.php?id=<?=$row['fb_id']?>"><?=$row['fb_name']?></a><br>
						<?php
					}
				?>
			</ul>
		</div>
		
		<?php
		
		$invited = "SELECT invitee_id FROM Game_Invites WHERE game_id = '$game_id' UNION SELECT player_id FROM Game_Player_Info WHERE game_id = '$game_id'";
		$invited_result = mysql_query($invited);
		//we are creating our own array of people invited to the game, or already playing the game
		$idArray = array();
		while($row = mysql_fetch_assoc($invited_result)){
			$idArray[] = $row['invitee_id'];	
		}
		
		$fql = 'SELECT uid, name, pic_square FROM user WHERE uid IN (SELECT uid2 FROM friend WHERE uid1 = me()) AND is_app_user = 1 ORDER BY name';
		$ret_obj = $facebook->api(array(
                                   'method' => 'fql.query',
                                   'query' => $fql,
                                 ));
                                 
		$count = count($ret_obj);
		//we want to be able to invite friends who have not been invited or not in the game -->
		?>
		<!-- <div data-role="collapsible" data-inset="true" data-theme="a"> -->
		<h3>Select friends to invite!</h3>
		<ul data-role="listview" data-inset="true" id="invite">
		<?php
		for ($i = 0; $i < $count; $i++) {
        	if (!in_array($ret_obj[$i]['uid'], $idArray)) {
        		?>
        		<img src="<?php echo $ret_obj[$i]['pic_square']; ?>">
       			<?php
       			echo $ret_obj[$i]['name'];
       			?>
		        <a id="submit_<?=$ret_obj[$i]['uid']?>" data-role="button" data-theme="a" data-inline="true">Invite</a>
				<div id="invitedText_<?=$ret_obj[$i]['uid']?>"></div>
			
				<script type="text/javascript">				
					$("#submit_<?=$ret_obj[$i]['uid']?>").click(function(event) {
						$.post("invite.php", {
							friend_id: <?=$ret_obj[$i]['uid']?>,
							game_id: <?=$game_id?> 
						},function(data){
							//this removes the button
							$("#submit_<?=$ret_obj[$i]['uid']?>").html("Invited!");
						});
					});
				</script>
       			<?php
        	}
        }
        ?>
        </ul>
        <!-- </div> -->
    
    </div> <!--end of content-->
    <div data-role="footer" data-id="samebar" class="nav-icons" data-position="fixed" data-tap-toggle="false">
	
	<?php
		include('inc/navBar.php');
	 ?>
</div> <!--end of footer-->
</div> <!--end of page-->