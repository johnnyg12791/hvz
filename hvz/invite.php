<?php

require_once('core/init.php');

?>

<html>
<body>


<?php

$inviter_id = $user_id;
$invitee_id = $_POST['friend_id'];
$game_id = $_POST['game_id'];

echo 'Inviter ID: ' . $inviter_id . "<br>";
echo 'Invitee ID: ' . $invitee_id . "<br>";
echo 'Game ID: ' . $game_id . "<br>";


$query = "Insert into Game_Invites (game_id, inviter_id, invitee_id, accepted) values ('$game_id', '$inviter_id', '$invitee_id', 0);";
$result = mysql_query($query);
if($result)
	echo "successfully invited";
else
	echo "invite didn't go though";
?>

</body>
</html>