<?php
require_once('core/init.php');

?>
<html>
<head>

</head>

<body>
<?php

$player = $_POST["user_id"];
$latitude = $_POST["latitude"];
$longitude = $_POST["longitude"];



		
mysql_query("UPDATE Game_Player_Info SET recent_latitude='$latitude', recent_longitude='$longitude' WHERE player_id='$player'");
?>

</body>

</html>