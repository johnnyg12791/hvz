<?php
/* *****************************************************************************
 * *****************************************************************************
 * Title:           Create Game
 * Location:        "/createGame.php"
 * 
 * Description:     Create a new game
 * 
 * Author:          John Gold
 * Date:            11/5/12
 * Version:         1.1
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
$header_title = "Create Game";
include('inc/header.php');

?>

	<body>
    
    <div data-role="page">

	<div data-role="header">
	<a onclick="history.back(-1)" data-icon="arrow-l">Back</a>
		<h1>NEW GAME</h1>
	</div><!-- /header -->

	<div data-role="content">

	<script type="text/javascript" src="geolocation.js"></script>
	
	<ul data-role="listview" data-inset="true" id="beforeCreatedGame">
	<div id="createGame">
    	<h1>Enter Game Name</h1>
       	<input id="game_name" name = "game_name" type="text" placeholder="Enter Game Name" autofocus required>   
       	<h1>Enter Start Time (The game will automatically start on this time if you have 3 or more players)</h1>
       	<input id="start_date" name = "start_date" type="date" placeholder="Enter Start Date">
        <input id="latitude" name = "latitude" type="hidden">
        <input id="longitude" name = "longitude" type="hidden">
       	<input id="submit" data-theme="a" type="button" value="Create Game!">
	</div>
	</ul>
	
	<div id="GameCreatedResults"></div>
	<div id="FriendsList"></div>
	
	</div><!-- /content -->
	
	<!--submit search for game name script -->
	<script type="text/javascript">
	$("#submit").click(function(event) {
		//makes sure the date and game name fields are not null
		if($('#start_date').val() == ""){
			alert("Please pick a game start date");
			return false;
		} else if ($("#game_name").val() == "") {
			alert("Please type a game name");
			return false;	
		}
		//makes sure picked time is in the future
		var pickedTime = (new Date($('#start_date').val())).getTime()/1000;
		var currentTime = Math.round(new Date().getTime()/1000);
		if (pickedTime < currentTime){
			alert("This date is in the past, choose a time in the future");	
			return false;
		}
		
		$.post("submitCreateGame.php", {
			game_name: $("#game_name").val(), 
			start_date: $("#start_date").val(),
			latitude : $("#latitude").val(),
			longitude : $("#longitude").val() 
		}, function(data){
				<!--$("#beforeCreatedGame").hide();-->
				$("#beforeCreatedGame").remove();
				$("#GameCreatedResults").html(data);
				
			});
	});
	
	</script>
	
	<div data-role="footer" data-id="samebar" class="nav-icons" data-position="fixed" data-tap-toggle="false">
	
	<?php
		include('inc/navBar.php');
	 ?>

	<!--#include virtual="inc/navBar.php" -->
	
	</div><!-- /page -->
	
	<script>
	$("#toggle_menu").bind("click", function() {
			if ($("#navigation").is(":visible") ) {
				$("#toggle_menu").html("Up");
				$("#toggle_menu").css("color", "blue");
			} else {
				$("#toggle_menu").html("Down");
				$("#toggle_menu").css("color", "pink");
			}
			$("#navigation").slideToggle();
		});
	</script>
	
    </body>

	
</html>