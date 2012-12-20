<?php
require_once('core/init.php');

?>

<html>
<head>
</head>
<body>
<!-- format of 2012-11-08 00:00:00 -->
<?php

$game_id = $_POST['game_id'];

$curTime = date("Y-m-d H:i:s"); 
$query = "UPDATE Games SET start_time = '$curTime' where game_id = '$game_id'";
$result = mysql_query($query);


?>


</body>
</html>