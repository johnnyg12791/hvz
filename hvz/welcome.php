<?php
/* *****************************************************************************
 * *****************************************************************************
 * Title:           Welcome2
 * Location:        "/welcome2.php"
 * 
 * Description:     The welcome page for the application
 					Users see this after they log in, or continue without 
 					logging in (for testing)
 * 
 * Author:          Lucille
 * Date:            11/7/12
 * Version:         1.1
 * *****************************************************************************
 * *****************************************************************************
 */
 


// initialise the app
// init.php contains all facebook sdk info, session control, and the works
	require_once('core/init.php');
	

if (!$user_id) {
	?>
	<script>
		window.location = "http://stanford.edu/~johngold/cgi-bin/hvz"
	</script>
	<?php
}
?>


<html>

<?php
// generate page title and include the header
// $header_title is passed in the header.php file
$header_title = "Welcome";
include('inc/header.php');
?>


<body background="background.png">

	<!--<audio id="sound" style="visibility:hidden;" src="sounds/Monster Growl-SoundBible.com-344645592.mp3" controls preload="auto" autobuffer></audio>
	<script>
			document.getElementById("sound").play()
	</script>-->

	<?php include_once("analyticstracking.php") ?>

    <div data-role="page">
    
			<div data-role="header">
					<h1>HUMANS vs ZOMBIES</h1>
			</div><!-- /header -->

			<div data-role="content">
					<div class="ui-grid-a">	
	    				<div class="ui-block-a"><a class="menu-btn" href="#popupMenu" data-theme="a" data-rel="popup" data-position-to="window" data-role="button"><img src="buttons/mygames2.png" width="70" height="80"/></a></div>
	    				<div class="ui-block-b"><a class="menu-btn" href="#popupMenuInvites" data-theme="a" data-rel="popup" data-position-to="window" data-role="button"><img src="buttons/invites2.png" width="70" height="80"/></a></div>
	    				<div class="ui-block-a"><a class="menu-btn" button type="button" data-theme="a" href="createGame.php"><img src="buttons/newgame2.png" width="70" height="80"/></a></div>
	    				<div class="ui-block-b"><a class="menu-btn" button type="button" data-theme="a" href="searchForGames.php"><img src="buttons/search2.png" width="70" height="80"/></a></div>
	    				<div class="ui-block-b"><a class="menu-btn" href="#whosPlaying" data-theme="a" data-rel="popup" data-position-to="window" data-role="button"><img src="buttons/friends2.png" width="70" height="80"/></a></div>
	    				<div class="ui-block-b"><a class="menu-btn" button type="button" data-theme="a" href="howToPlay.php"><img src="buttons/help2.png" width="70" height="80"/></a></div>
	    				
					
					</div>
					
		<div data-role="popup" id="popupMenu" data-theme="a">
		<a href="#" data-rel="back" data-role="button" data-theme="a" data-icon="delete" data-iconpos="notext" class="ui-btn-right">Close</a>
			<ul id="my_games_list" data-role="listview" data-theme="a" data-inset="true" style="min-width:290px; max-width:290px;">
			<li data-role="divider" data-theme="a">My Games</li>
				
				<?php
				$uid = $user_id;
				/*double checks to get the users name and id
				$user_profile = $facebook->api('/me');
				$user_name = $user_profile['name'];
   				$checkQuery = "select * from Fb_Id_Name where fb_id = '$user_id' ";
   				$checkResult = mysql_query($checkQuery);
   	
   				if(mysql_num_rows($checkResult) == 0){
   					$query = "insert into Fb_Id_Name values ('$user_id', '$user_name');";
   					$first_login = mysql_query($query);          
   				}*/
   				
    					
				$query = "SELECT * FROM Game_Player_Info, Games where player_id = '$uid' and Games.game_id = Game_Player_Info.game_id";
				$result = mysql_query($query);
				
				$i = 0;
				while ($row = mysql_fetch_assoc($result)) {
					?><!--this takes us to safari, whatever...-->
					<li><a href="game.php?game_id=<?=$row['game_id']?>" data-ajax='false'>
					<?php
					echo $row['game_name'];
					
					$is_started = 0;
					$is_over = 0;
					if(strtotime($row['start_time']) < time())
						$is_started = 1;
					if($row['winner_id'] != 0)
						$is_over = 1;
					if($is_over) 
						echo ": Over";
					else if($is_started) 
						echo ": Started";
					else 
						echo ": Not Started";
					echo "</a></li>";
					++$i;
				}
				if ($i == 0) {
					echo 'No current games.';
				}
				?>
				
				</ul>
		</div>
		<div data-role="popup" data-theme="a" id="popupMenuInvites">
		<a href="#" data-rel="back" data-role="button" data-theme="a" data-icon="delete" data-iconpos="notext" class="ui-btn-right">Close</a>
				<ul data-role="listview" data-theme="a" data-inset="true" style="min-width:290px; max-width:290px;">
				<li data-role="divider" data-theme="a">Game Invites</li>
		<?php
			//select the games where the current user is the one being invited
			$uid = $user_id;
			//I think this query is correct now
			$query = "SELECT * FROM Games, Game_Invites, Fb_Id_Name where invitee_id = '$uid' AND Games.game_id = Game_Invites.game_id AND fb_id = inviter_id AND accepted=0 AND winner_id=0;";
			$result = mysql_query($query);
			$rowNum = 0;
			while ($row = mysql_fetch_assoc($result)) {
				
				echo "Game: " . $row['game_name'] ;
				?>
				<div id="acceptInvite_<?=$rowNum?>">
					<input name='latitude' id="latitude" type='hidden'>
					<input name='longitude' id="longitude" type='hidden'>
					<input name='submit' id="submit_<?=$rowNum?>" type="submit" data-theme="a" value='Accept Invite'>
				</div>
				<div id="invitedText_<?=$rowNum?>"></div>
				<script type="text/javascript">
				$("#submit_<?=$rowNum?>").click(function(event) {
					//double invite accepted?
					$.post("inviteAccepted.php", {
						game_id: <?=$row['game_id']?>, 
						inviter_id: <?=$row['inviter_id']?>,
						invitee_id: <?=$uid?>,
						latitude : $("#latitude").val(),
						longitude : $("#longitude").val() 
					}, function(data){
						//alert("Game Joined");
						//take you to the game.php?id=game_id page
						$("#acceptInvite_<?=$rowNum?>").hide();
						$("#invitedText_<?=$rowNum?>").html(
						"<a href='game.php?game_id=<?=$row['game_id']?>'><font color='white'> Go to game </font></a> ");
						$("#my_games_list").append("<li data-corners='false' data-shadow='false' data-iconshadow='true' data-wrapperels='div' data-icon='arrow-r' data-iconpos='right' data-theme='a' class='ui-btn ui-btn-icon-right ui-li-has-arrow ui-li ui-btn-up-a'><div class='ui-btn-inner ui-li'><div class='ui-btn-text'><a href='game.php?game_id=<?=$row['game_id']?>' class='ui-link-inherit'><?=$row['game_name']?></a></div><span class='ui-icon ui-icon-arrow-r ui-icon-shadow'>&nbsp;</span></div></li>");
					});
				});
				</script>
				<?php
				$rowNum++;	
			}
			if ($rowNum == 0) {
				echo 'No game invites';
			}
			?>
		</ul>

		</div>	
		<div data-role="popup" id="whosPlaying" data-theme="a">
		<a href="#" data-rel="back" data-role="button" data-theme="a" data-icon="delete" data-iconpos="notext" class="ui-btn-right">Close</a>
				<ul data-role="listview" data-inset="true" style="min-width:290px; max-width:290px;">
				<li data-role="divider" data-theme="a">Friends Playing HvZ</li>
				<?php
	
					$fql = 'SELECT uid, name, pic_square FROM user WHERE uid IN (SELECT uid2 FROM friend WHERE uid1 = me()) AND is_app_user = 1 ORDER BY name';
					$ret_obj = $facebook->api(array(
		     	                   			'method' => 'fql.query',
		          	               			'query' => $fql,
		           		           			));
					$count = count($ret_obj);
					for ($i = 0; $i < $count; $i++) {
				?>
						<img src="<?=$ret_obj[$i]['pic_square']?>">
						<a href="userProfile.php?id=<?=$ret_obj[$i]['uid']?>"><?=$ret_obj[$i]['name']?></a><br>
				<?php
					}	
				?>								
				</ul>
		</div>
						
						<!-- DO NOT DELETE THIS php stuff...like below, it's magically making Ajax work -->
						<?php
						//select the games where the current user is the one being invited
						$uid = $user_id;
						//I think this query is correct now
						$query = "SELECT * FROM Games, Game_Invites, Fb_Id_Name where invitee_id = '$uid' and Games.game_id = Game_Invites.game_id and fb_id = inviter_id and accepted=0;";
						$result = mysql_query($query);
						$num_invites = mysql_num_rows($result);
						$rowNum = 0;
						?>
	    						
		
		<!-- DO NOT DELETE THIS even though it's duplicated. Apparently it's making Ajax work -->
						<div data-role="popup" id="popupMenuInvites" data-theme="a">
								<a href="#" data-rel="back" data-role="button" data-theme="a" data-icon="delete" data-iconpos="notext" class="ui-btn-right">Close</a>
								<ul data-role="listview" data-inset="true" style="min-width:290px; max-width:290px;">
									<li data-role="divider" data-theme="a">Game Invites</li>
									<?php
									while ($row = mysql_fetch_assoc($result)) {
										?>
										<div id="acceptInvite_<?=$rowNum?>">
												<input name='latitude' id="latitude" type='hidden'>
												<input name='longitude' id="longitude" type='hidden'>
												<input name='submit' id="submit_<?=$rowNum?>" type="submit" value='Accept Invite'>
										</div>
										<div id="invitedText_<?=$rowNum?>"></div>
										
										<?php
										$rowNum++;	
									}
									?>
								</ul>
						</div>

		
		</div><!-- /content -->	
		<div data-role="footer" data-id="samebar" class="nav-icons" data-position="fixed" data-tap-toggle="false">
	<?php
		/*include('inc/navBar.php');*/
	 ?>
 </div> <!-- end of footer -->
	
</div><!-- /page -->
	

    </body>
<script type="text/javascript" src="geolocation.js"></script>
</html>