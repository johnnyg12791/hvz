<?php
/* *****************************************************************************
 * *****************************************************************************
 * Title:           Index
 * Location:        "/index.php"
 * 
 * Description:     The landing page for the application
 * 
 * Author:          Justin/John
 * Date:            date
 * Version:         1.1
 * *****************************************************************************
 * *****************************************************************************
 */
 

// initialise the app
// init.php contains all facebook sdk info, session control, and the works
require_once('core/init.php');


$params = array(
  'redirect_uri' => 'http://stanford.edu/~johngold/cgi-bin/hvz/welcome.php'
);
$logoutUrl = $facebook->getLogoutUrl();
$loginUrl = $facebook->getLoginUrl($params);
?>

<!doctype html>
<html xmlns:fb="http://www.facebook.com/2008/fbml">

<?php
/* 
 * generate page title and include the header
 * $header_title is passed in the header.php file
 */
$header_title = "Login";
include('inc/header.php');
?>

<body>

	<div data-role="page">

		<div data-role="header">
				<h1>Login!</h1>
		</div><!-- /header -->

		<div data-role="content">
			
			<?php
			if ($user_id) {
				try {
    				// Proceed knowing you have a logged in user who's authenticated.
    				$user_profile = $facebook->api('/me');
    
    				//This is for the Fb_Id_Name table in the database
    				$user_name = $user_profile['name'];
    
    				$checkQuery = "select * from Fb_Id_Name where fb_id = '$user_id' ";
    				$checkResult = mysql_query($checkQuery);
    	
    				if(mysql_num_rows($checkResult) == 0){
    					$query = "insert into Fb_Id_Name values ('$user_id', '$user_name');";
    					$first_login = mysql_query($query);                
    					if($first_login) {
    						?>
    						<div>
        						<h3 style="color:white">Welcome to Humans vs Zombies (for the first time)</h3>
        					</div>
        					<?php
    						//echo "Welcome to Humans vs Zombies (for the first time)";
    					}
    				} //else user is in the database, no need to change anything
  				} catch (FacebookApiException $e) {
    				error_log($e);
    				$user_id = null;
				}
			}
			
    		if ($user_id) {
      			try {
        			$fql = 'SELECT uid, name, pic_square FROM user WHERE uid IN (SELECT uid2 FROM friend WHERE uid1 = me()) AND is_app_user = 1 ORDER BY name';
        			$ret_obj = $facebook->api(array(
                                   			'method' => 'fql.query',
                                   			'query' => $fql,
                                 			));
                                
                    $query = "SELECT COUNT( * ) AS num, player_id FROM Game_Player_Info WHERE player_id <> '$user_id' GROUP BY player_id ORDER BY num DESC";		
         			$result = mysql_query($query);
         			$top_players = array();
         			while($row = mysql_fetch_assoc($result)) {
         				$top_players[] = $row['player_id'];
         			}
         			
         			
         			$top_player1; $top_player2; $top_player3;
        			$count = count($ret_obj);
        			
        			if ($count > 3) {
        				$count = 3;
        			}
        			
        			shuffle($ret_obj);
        			
        			if (!$first_login) {
        			?>
        			
        			<div>
        					<h3 style="color:white">Welcome back, <?=$user_name?>!</h3>
        			</div>
        			
        			<div>
        					<?php if ($count > 0) { ?>
        					<h4 id="friendsPlaying" style="color:white">Friends playing Humans vs. Zombies:</h4>
        					<?php } 
        			}
        			echo '<p>';
        			
        			for ($i = 0; $i < $count; $i++) {
        				?><img src="<?php echo $ret_obj[$i]['pic_square']; ?>"><?php
        			}
        			echo '</p>';
        			?>
        			
        			</div>
        			<?php
      			} catch(FacebookApiException $e) {
        			// If the user is logged out, you can have a 
        			// user ID even though the access token is invalid.
        			// In this case, we'll get an exception, so we'll
        			// just ask the user to login again here.
        			echo 'Please <a href="' . $loginUrl . '">login.</a>';
        			error_log($e->getType());
        			error_log($e->getMessage());
      			} 
      			?>
      			<a href="welcome.php" style="color:white">Continue to application</a><br>
    
      			<!-- <img src="https://graph.facebook.com/<?php echo $user_id; ?>/picture"> -->
      			<div>
      				<a href="<?php echo $logoutUrl; ?>" style="color:white">Logout</a><br>	
      			</div>
  				<?php
    		} 
  			else { 
  				?>
      			<div>
        			<a href="<?php echo $loginUrl; ?>" style="color:white">Login to Humans vs. Zombies</a>
      			</div>
      			<?php
  			}
  			?>   	  
		
		</div><!-- /content -->

	<div data-role="footer">	

	</div>

	</div><!-- /page -->

</body>

</html>