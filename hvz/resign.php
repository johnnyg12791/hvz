<?php
/* *****************************************************************************
 * *****************************************************************************
 * Title:           Resign
 * Location:        "/resign.php"
 * 
 * Description:     Just a database request really
 * 
 * Author:          John Gold
 * Date:            11/2/12
 * Version:         1.0
 * *****************************************************************************
 * *****************************************************************************
 */
 


// initialise the app
// init.php contains all facebook sdk info, session control, and the works
require_once('core/init.php');
?>

<html>

<?php

// generate page title and include the header
// $header_title is passed in the header.php file
$header_title = "Humans vs. Zombies";
include('inc/header.php');
?>

<body>

<? 

$player_id = $_POST['user_id'];
$game_id = $_POST['game_id'];
mysql_query("DELETE from Game_Player_Info WHERE game_id = '$game_id' AND player_id = '$player_id'");
?>
<script>
location.href='welcome.php';
</script>
</body>
</html>