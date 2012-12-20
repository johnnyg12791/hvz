<?php
/* *****************************************************************************
 * *****************************************************************************
 * Title:           User Profile
 * Location:        "/userProfile.php"
 * 
 * Description:     Profile for a user
 * 
 * Author:          Justin Salloum
 * Date:            11/15/12
 * Version:         1.1
 * *****************************************************************************
 * *****************************************************************************
 */
 	// Initialize database and Facebook stuff
	require_once('core/init.php');
	
	// Redirect to index.php if user not logged in.
	include('inc/verifyLogin.php');
?>

<html>

<?php

// generate page title and include the header
// $header_title is passed in the header.php file
$header_title = "User Profile";
include('inc/header.php');

?>

<body>

<div data-role="page">

			<?php
				$player_id = $_GET['id'];
				$fql;
				if ($player_id == $user_id) {
					$fql = "SELECT uid, name, pic FROM user WHERE uid = '$user_id'";
				} else {
					$fql = "SELECT uid, name, pic FROM user WHERE uid IN (SELECT uid2 FROM friend WHERE uid1 = me() AND uid2 = '".$player_id."')";
				}
				?>

				<?php
				$ret_obj = $facebook->api(array(
										'method' => 'fql.query',
										'query' => $fql,
										));
				$player_profile = $ret_obj[0];
			?>


		<div data-role="header">
				<a onclick="history.back(-1)" data-icon="arrow-l">Back</a>
				<h6><?=$player_profile['name']?></h6>
		</div><!-- /header -->
		
		<div data-role="content">

								
				<img src=<?=$player_profile['pic']?><img><br> <?php

				$result = mysql_query("SELECT COUNT( * ) FROM  `Game_Player_Info` WHERE player_id =  '$player_id'");
				$row = mysql_fetch_array($result);
				$num_games_played = $row[0];
				?>
				<p style="color:white">Number of games played: <?=$num_games_played?></p>
				<!-- echo "Number of games played: $num_games_played<br>"; -->
				
				<?php
				$result = mysql_query("SELECT sum(num_bites) FROM  `Game_Player_Info` GROUP BY player_id HAVING player_id =  '$player_id'");
				$row = mysql_fetch_array($result);
				$num_humans_bitten = $row[0];
				if($row[0] == "")
				$num_humans_bitten = 0;
				?>

				<p style="color:white">Number of humans bitten: <?=$num_humans_bitten?></p>
				<!-- echo "Number of humans bitten: $num_humans_bitten<br>"; -->
				
				<?php
				$result = mysql_query("SELECT COUNT( * ) FROM  `Game_Player_Info` WHERE `player_id` = '$player_id' AND `bitten_by` <> 0");
				$row = mysql_fetch_array($result);
				$num_zombies_bitten_by = $row[0];
				?>
				
				<p style="color:white">Number of zombies bitten by: <?=$num_zombies_bitten_by?></p>
				<!-- echo "Number of zombies bitten by: $num_zombies_bitten_by<br>"; -->
				
		</div><!-- /content-->
		
		<div data-role="footer" data-id="samebar" class="nav-icons" data-position="fixed" data-tap-toggle="false">
					<?php
					include('inc/navBar.php');
					?>
		</div>

</div><!-- /page -->

</body>
</html>